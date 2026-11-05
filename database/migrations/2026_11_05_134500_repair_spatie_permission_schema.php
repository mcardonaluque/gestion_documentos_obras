<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    protected $connection = 'Obras';

    public function up(): void
    {
        $connection = DB::connection('Obras');

        if ($connection->getDriverName() !== 'sqlsrv' || $this->usesStandardIdentityKeys($connection)) {
            return;
        }

        $connection->unprepared(<<<'SQL'
SET XACT_ABORT ON;
BEGIN TRY
BEGIN TRANSACTION;

DECLARE @nextRoleId BIGINT = (SELECT ISNULL(MAX([id]), 0) FROM [dbo].[roles]);

;WITH missing_role_ids AS (
    SELECT [id], [name], [guard_name], ROW_NUMBER() OVER (ORDER BY [name], [guard_name]) AS [row_number]
    FROM [dbo].[roles]
    WHERE [id] IS NULL
)
UPDATE missing_role_ids
SET [id] = @nextRoleId + [row_number];

;WITH permission_canonical AS (
    SELECT [id], MIN([id]) OVER (PARTITION BY [name], [guard_name]) AS [canonical_id]
    FROM [dbo].[permissions]
)
UPDATE [role_has_permissions]
SET [permission_id] = permission_canonical.[canonical_id]
FROM [dbo].[role_has_permissions]
INNER JOIN permission_canonical ON permission_canonical.[id] = [role_has_permissions].[permission_id]
WHERE permission_canonical.[id] <> permission_canonical.[canonical_id];

;WITH permission_canonical AS (
    SELECT [id], MIN([id]) OVER (PARTITION BY [name], [guard_name]) AS [canonical_id]
    FROM [dbo].[permissions]
)
UPDATE [model_has_permissions]
SET [permission_id] = permission_canonical.[canonical_id]
FROM [dbo].[model_has_permissions]
INNER JOIN permission_canonical ON permission_canonical.[id] = [model_has_permissions].[permission_id]
WHERE permission_canonical.[id] <> permission_canonical.[canonical_id];

DELETE [role_has_permissions]
FROM [dbo].[role_has_permissions]
LEFT JOIN [dbo].[permissions] ON [permissions].[id] = [role_has_permissions].[permission_id]
WHERE [permissions].[id] IS NULL;

DELETE [model_has_permissions]
FROM [dbo].[model_has_permissions]
LEFT JOIN [dbo].[permissions] ON [permissions].[id] = [model_has_permissions].[permission_id]
WHERE [permissions].[id] IS NULL;

;WITH duplicate_permissions AS (
    SELECT [id], MIN([id]) OVER (PARTITION BY [name], [guard_name]) AS [canonical_id]
    FROM [dbo].[permissions]
)
DELETE [permissions]
FROM [dbo].[permissions]
INNER JOIN duplicate_permissions ON duplicate_permissions.[id] = [permissions].[id]
WHERE duplicate_permissions.[id] <> duplicate_permissions.[canonical_id];

CREATE TABLE [dbo].[roles_repaired] (
    [id] BIGINT IDENTITY(1, 1) NOT NULL,
    [name] NVARCHAR(255) NOT NULL,
    [guard_name] NVARCHAR(255) NOT NULL,
    [created_at] DATETIME2 NULL,
    [updated_at] DATETIME2 NULL,
    CONSTRAINT [roles_primary] PRIMARY KEY ([id]),
    CONSTRAINT [roles_name_guard_name_unique] UNIQUE ([name], [guard_name])
);

SET IDENTITY_INSERT [dbo].[roles_repaired] ON;
INSERT INTO [dbo].[roles_repaired] ([id], [name], [guard_name], [created_at], [updated_at])
SELECT [id], [name], [guard_name], [created_at], [updated_at]
FROM [dbo].[roles];
SET IDENTITY_INSERT [dbo].[roles_repaired] OFF;

CREATE TABLE [dbo].[permissions_repaired] (
    [id] BIGINT IDENTITY(1, 1) NOT NULL,
    [name] NVARCHAR(255) NOT NULL,
    [guard_name] NVARCHAR(255) NOT NULL,
    [created_at] DATETIME2 NULL,
    [updated_at] DATETIME2 NULL,
    CONSTRAINT [permissions_primary] PRIMARY KEY ([id]),
    CONSTRAINT [permissions_name_guard_name_unique] UNIQUE ([name], [guard_name])
);

SET IDENTITY_INSERT [dbo].[permissions_repaired] ON;
INSERT INTO [dbo].[permissions_repaired] ([id], [name], [guard_name], [created_at], [updated_at])
SELECT [id], [name], [guard_name], [created_at], [updated_at]
FROM [dbo].[permissions];
SET IDENTITY_INSERT [dbo].[permissions_repaired] OFF;

CREATE TABLE [dbo].[role_has_permissions_repaired] (
    [permission_id] BIGINT NOT NULL,
    [role_id] BIGINT NOT NULL,
    CONSTRAINT [role_has_permissions_permission_id_role_id_primary] PRIMARY KEY ([permission_id], [role_id]),
    CONSTRAINT [role_has_permissions_permission_id_foreign]
        FOREIGN KEY ([permission_id]) REFERENCES [dbo].[permissions_repaired] ([id]) ON DELETE CASCADE,
    CONSTRAINT [role_has_permissions_role_id_foreign]
        FOREIGN KEY ([role_id]) REFERENCES [dbo].[roles_repaired] ([id]) ON DELETE CASCADE
);

INSERT INTO [dbo].[role_has_permissions_repaired] ([permission_id], [role_id])
SELECT DISTINCT [permission_id], [role_id]
FROM [dbo].[role_has_permissions];

CREATE TABLE [dbo].[model_has_permissions_repaired] (
    [permission_id] BIGINT NOT NULL,
    [model_type] NVARCHAR(255) NOT NULL,
    [model_id] BIGINT NOT NULL,
    CONSTRAINT [model_has_permissions_permission_model_type_model_id_primary]
        PRIMARY KEY ([permission_id], [model_id], [model_type]),
    CONSTRAINT [model_has_permissions_permission_id_foreign]
        FOREIGN KEY ([permission_id]) REFERENCES [dbo].[permissions_repaired] ([id]) ON DELETE CASCADE
);

CREATE INDEX [model_has_permissions_model_id_model_type_index]
ON [dbo].[model_has_permissions_repaired] ([model_id], [model_type]);

INSERT INTO [dbo].[model_has_permissions_repaired] ([permission_id], [model_type], [model_id])
SELECT DISTINCT [permission_id], [model_type], [model_id]
FROM [dbo].[model_has_permissions];

CREATE TABLE [dbo].[model_has_roles_repaired] (
    [role_id] BIGINT NOT NULL,
    [model_type] NVARCHAR(255) NOT NULL,
    [model_id] BIGINT NOT NULL,
    CONSTRAINT [model_has_roles_role_model_type_model_id_primary]
        PRIMARY KEY ([role_id], [model_id], [model_type]),
    CONSTRAINT [model_has_roles_role_id_foreign]
        FOREIGN KEY ([role_id]) REFERENCES [dbo].[roles_repaired] ([id]) ON DELETE CASCADE
);

CREATE INDEX [model_has_roles_model_id_model_type_index]
ON [dbo].[model_has_roles_repaired] ([model_id], [model_type]);

INSERT INTO [dbo].[model_has_roles_repaired] ([role_id], [model_type], [model_id])
SELECT DISTINCT [role_id], [model_type], [model_id]
FROM [dbo].[model_has_roles];

DROP TABLE [dbo].[role_has_permissions];
DROP TABLE [dbo].[model_has_permissions];
DROP TABLE [dbo].[model_has_roles];
DROP TABLE [dbo].[roles];
DROP TABLE [dbo].[permissions];

EXEC sp_rename N'dbo.roles_repaired', N'roles';
EXEC sp_rename N'dbo.permissions_repaired', N'permissions';
EXEC sp_rename N'dbo.role_has_permissions_repaired', N'role_has_permissions';
EXEC sp_rename N'dbo.model_has_permissions_repaired', N'model_has_permissions';
EXEC sp_rename N'dbo.model_has_roles_repaired', N'model_has_roles';

IF OBJECT_ID(N'[dbo].[permissions_id_sequence]', N'SO') IS NOT NULL
    DROP SEQUENCE [dbo].[permissions_id_sequence];

IF OBJECT_ID(N'[dbo].[roles_id_sequence]', N'SO') IS NOT NULL
    DROP SEQUENCE [dbo].[roles_id_sequence];

COMMIT TRANSACTION;
END TRY
BEGIN CATCH
    IF @@TRANCOUNT > 0 ROLLBACK TRANSACTION;
    THROW;
END CATCH;
SQL);
    }

    public function down(): void
    {
        throw new RuntimeException('La reparación del esquema de permisos es irreversible.');
    }

    private function usesStandardIdentityKeys($connection): bool
    {
        return (int) $connection->scalar(
            "SELECT COLUMNPROPERTY(OBJECT_ID(N'[dbo].[roles]'), 'id', 'IsIdentity')"
        ) === 1
            && (int) $connection->scalar(
                "SELECT COLUMNPROPERTY(OBJECT_ID(N'[dbo].[permissions]'), 'id', 'IsIdentity')"
            ) === 1;
    }
};

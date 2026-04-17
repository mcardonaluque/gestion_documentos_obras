USE Obras_test;
GO

IF OBJECT_ID('dbo.sp_migrar_usuarios_a_users', 'P') IS NOT NULL
    DROP PROCEDURE dbo.sp_migrar_usuarios_a_users;
GO

CREATE PROCEDURE dbo.sp_migrar_usuarios_a_users
AS
BEGIN
    SET NOCOUNT ON;
    SET XACT_ABORT ON;

    DECLARE @hoy DATETIME2(0) = SYSDATETIME();

    BEGIN TRY
        BEGIN TRANSACTION;

        ;WITH origen AS (
            SELECT
                LTRIM(RTRIM(CAST(Id_usuario AS varchar(100)))) AS id_usuario,
                LTRIM(RTRIM(CAST(Nombre_largo AS varchar(255)))) AS nombre_persona,
                LTRIM(RTRIM(CAST(Departamento AS varchar(50)))) AS departamento,
                LTRIM(RTRIM(CAST(Clave AS varchar(255)))) AS clave,
                LOWER(REPLACE(LTRIM(RTRIM(CAST(Id_usuario AS varchar(100)))), ' ', '')) + '@malaga.es' AS email
            FROM dbo.Usuarios
            WHERE NULLIF(LTRIM(RTRIM(CAST(Id_usuario AS varchar(100)))), '') IS NOT NULL
        )
        MERGE dbo.users AS destino
        USING origen AS src
            ON destino.id_usuario = src.id_usuario
        WHEN MATCHED THEN
            UPDATE SET
                destino.name = src.id_usuario,
                destino.email = src.email,
                destino.nombre_persona = src.nombre_persona,
                destino.departamento = src.departamento,
                destino.is_active = 1,
                destino.interno = 1,
                destino.updated_at = @hoy
        WHEN NOT MATCHED BY TARGET THEN
            INSERT (
                name,
                email,
                password,
                created_at,
                updated_at,
                departamento,
                nombre_persona,
                id_usuario,
                is_active,
                interno
            )
            VALUES (
                src.id_usuario,
                src.email,
                'PENDIENTE_HASH_LARAVEL',
                @hoy,
                @hoy,
                src.departamento,
                src.nombre_persona,
                src.id_usuario,
                1,
                1
            );

        INSERT INTO dbo.team_user (user_id, team_id)
        SELECT u.id, 0
        FROM dbo.users u
        INNER JOIN dbo.Usuarios o
            ON LTRIM(RTRIM(CAST(o.Id_usuario AS varchar(100)))) = u.id_usuario
        WHERE NOT EXISTS (
            SELECT 1
            FROM dbo.team_user tu
            WHERE tu.user_id = u.id
              AND tu.team_id = 0
        );

        COMMIT TRANSACTION;

        SELECT
            'OK' AS resultado,
            COUNT(*) AS usuarios_en_users_con_team_0
        FROM dbo.users u
        INNER JOIN dbo.team_user tu
            ON tu.user_id = u.id
           AND tu.team_id = 0
        WHERE u.interno = 1
          AND u.is_active = 1;

    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0
            ROLLBACK TRANSACTION;

        THROW;
    END CATCH
END;
GO

EXEC dbo.sp_migrar_usuarios_a_users;
GO

SELECT TOP (50)
    u.id,
    u.id_usuario,
    u.name,
    u.email,
    u.nombre_persona,
    u.departamento,
    u.is_active,
    u.interno,
    tu.team_id,
    u.created_at
FROM dbo.users u
LEFT JOIN dbo.team_user tu
    ON tu.user_id = u.id
WHERE tu.team_id = 0
ORDER BY u.id DESC;
GO

/*
NOTA IMPORTANTE:
La contraseña compatible con la aplicación no se genera con T-SQL puro.
Después de ejecutar este procedimiento base, ejecuta en Laravel:

php artisan app:migrar-usuarios-a-users

Ese comando toma la Clave original de dbo.Usuarios y la guarda en dbo.users
con el mismo hash bcrypt que usa la aplicación
*/

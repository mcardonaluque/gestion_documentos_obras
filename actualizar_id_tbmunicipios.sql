USE [tablas_test]
GO

CREATE OR ALTER PROCEDURE dbo.sp_actualizar_id_tbmunicipios
AS
BEGIN
    SET NOCOUNT ON;

    IF COL_LENGTH('dbo.TbMunicipios', 'id') IS NULL
    BEGIN
        RAISERROR('La columna id no existe en dbo.TbMunicipios.', 16, 1);
        RETURN;
    END;

    ;WITH MunicipiosOrdenados AS (
        SELECT
            id,
            ROW_NUMBER() OVER (
                ORDER BY Codigo_Provincia ASC, Codigo_Municipio ASC
            ) AS nuevo_id
        FROM dbo.TbMunicipios
    )
    UPDATE MunicipiosOrdenados
    SET id = nuevo_id;
END
GO

-- Ejecucion:
-- EXEC dbo.sp_actualizar_id_tbmunicipios;

-- =====================================================================
-- Script directo (sin SP): actualizar id y establecerlo como clave primaria
-- =====================================================================
/*
USE [tablas_test];
GO

IF COL_LENGTH('dbo.TbMunicipios', 'id') IS NULL
BEGIN
    RAISERROR('La columna id no existe en dbo.TbMunicipios.', 16, 1);
    RETURN;
END;

;WITH MunicipiosOrdenados AS
(
    SELECT
        id,
        ROW_NUMBER() OVER (ORDER BY Codigo_Provincia ASC, Codigo_Municipio ASC) AS nuevo_id
    FROM dbo.TbMunicipios
)
UPDATE MunicipiosOrdenados
SET id = nuevo_id;

IF EXISTS (SELECT 1 FROM dbo.TbMunicipios WHERE id IS NULL)
BEGIN
    RAISERROR('No se puede crear la PK: existen valores NULL en id.', 16, 1);
    RETURN;
END;

IF EXISTS (SELECT id FROM dbo.TbMunicipios GROUP BY id HAVING COUNT(*) > 1)
BEGIN
    RAISERROR('No se puede crear la PK: existen valores duplicados en id.', 16, 1);
    RETURN;
END;

DECLARE @pkName sysname;
DECLARE @sql nvarchar(max);

SELECT @pkName = kc.name
FROM sys.key_constraints kc
WHERE kc.parent_object_id = OBJECT_ID('dbo.TbMunicipios')
  AND kc.[type] = 'PK';

IF @pkName IS NOT NULL
BEGIN
    SET @sql = N'ALTER TABLE dbo.TbMunicipios DROP CONSTRAINT ' + QUOTENAME(@pkName) + N';';
    EXEC sp_executesql @sql;
END;

ALTER TABLE dbo.TbMunicipios ALTER COLUMN id int NOT NULL;

ALTER TABLE dbo.TbMunicipios
ADD CONSTRAINT PK_TbMunicipios_id PRIMARY KEY CLUSTERED (id);
GO
*/

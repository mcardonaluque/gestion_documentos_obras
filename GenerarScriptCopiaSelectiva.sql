-- Script para generar instrucciones de copia de datos entre PRODUCCION y TEST
-- Solo copia columnas que existen en AMBAS bases de datos
-- Las columnas nuevas en TEST quedaran con valores NULL o por defecto

USE OBRAS_TEST;
GO

-- ============================================
-- CONFIGURACION
-- ============================================
-- Este script genera los INSERT statements
-- Revisa la salida y ejecuta los comandos generados

DECLARE @TablasPorCopiar TABLE (NombreTabla NVARCHAR(256));

-- IMPORTANTE: Lista aqui las tablas de las que quieres copiar datos
-- Comenta/descomenta segun necesites
INSERT INTO @TablasPorCopiar VALUES 
    ('users'),
    ('teams'),
    ('roles'),
    ('Expedientes'),
    ('Proyectos'),
    ('Contratos'),
    ('Facturas');
    -- Agrega mas tablas segun necesites

-- ============================================
-- GENERACION DE SCRIPT
-- ============================================

DECLARE @Tabla NVARCHAR(256);
DECLARE @ColumnasComunes NVARCHAR(MAX);
DECLARE @Script NVARCHAR(MAX) = '';

DECLARE tabla_cursor CURSOR FOR
SELECT NombreTabla FROM @TablasPorCopiar;

OPEN tabla_cursor;
FETCH NEXT FROM tabla_cursor INTO @Tabla;

WHILE @@FETCH_STATUS = 0
BEGIN
    -- Obtener columnas que existen en AMBAS bases de datos
    SELECT @ColumnasComunes = STRING_AGG(QUOTENAME(column_name), ', ')
    FROM (
        -- Columnas en TEST
        SELECT column_name, ordinal_position
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE table_name = @Tabla AND table_schema = 'dbo'

        INTERSECT

        -- Columnas en PRODUCCION (via linked server o servidor remoto)
        SELECT column_name, ordinal_position
        FROM [GUADIX].[OBRAS].INFORMATION_SCHEMA.COLUMNS
        WHERE table_name = @Tabla AND table_schema = 'dbo'
    ) AS ColumnasComunes
    ORDER BY ordinal_position;

    IF @ColumnasComunes IS NOT NULL
    BEGIN
        SET @Script = @Script + CHAR(13) + CHAR(10) + 
            '-- Tabla: ' + @Tabla + CHAR(13) + CHAR(10) +
            'PRINT ''Copiando datos de ' + @Tabla + '...'';' + CHAR(13) + CHAR(10) +
            'DELETE FROM [dbo].[' + @Tabla + '];' + CHAR(13) + CHAR(10) +
            'INSERT INTO [dbo].[' + @Tabla + '] (' + @ColumnasComunes + ')' + CHAR(13) + CHAR(10) +
            'SELECT ' + @ColumnasComunes + CHAR(13) + CHAR(10) +
            'FROM [GUADIX].[OBRAS].[dbo].[' + @Tabla + '];' + CHAR(13) + CHAR(10) +
            'PRINT ''Registros copiados: '' + CAST(@@ROWCOUNT AS VARCHAR(10));' + CHAR(13) + CHAR(10) +
            'GO' + CHAR(13) + CHAR(10);

        SET @ColumnasComunes = NULL;
    END

    FETCH NEXT FROM tabla_cursor INTO @Tabla;
END

CLOSE tabla_cursor;
DEALLOCATE tabla_cursor;

-- Mostrar el script generado
PRINT @Script;
GO

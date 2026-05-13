-- Script para copiar datos de PRODUCCIÓN a TEST
-- Copia tabla por tabla manteniendo el esquema de TEST

-- ============================================
-- CONFIGURACIÓN
-- ============================================
-- Modifica estos valores según tus servidores:
-- Si están en el mismo servidor, solo cambia los nombres de BD
-- Si están en servidores diferentes, usa [servidor].[base_datos].[dbo].[tabla]

-- ============================================
-- EJEMPLO: Copiar datos de una tabla
-- ============================================

-- OPCIÓN A: Vaciar tabla en TEST y copiar todos los datos de PRODUCCIÓN
/*
TRUNCATE TABLE [DB_Test].[dbo].[NombreTabla];
GO

INSERT INTO [DB_Test].[dbo].[NombreTabla]
SELECT * FROM [DB_Produccion].[dbo].[NombreTabla];
GO
*/

-- OPCIÓN B: Eliminar solo filas existentes y agregar las de producción
/*
DELETE FROM [DB_Test].[dbo].[NombreTabla];
GO

INSERT INTO [DB_Test].[dbo].[NombreTabla]
SELECT * FROM [DB_Produccion].[dbo].[NombreTabla];
GO
*/

-- OPCIÓN C: Copiar solo registros que no existen (según ID)
/*
INSERT INTO [DB_Test].[dbo].[NombreTabla]
SELECT p.* 
FROM [DB_Produccion].[dbo].[NombreTabla] p
WHERE NOT EXISTS (
    SELECT 1 
    FROM [DB_Test].[dbo].[NombreTabla] t 
    WHERE t.ID = p.ID
);
GO
*/

-- OPCIÓN D: Actualizar registros existentes e insertar nuevos (MERGE)
/*
MERGE [DB_Test].[dbo].[NombreTabla] AS target
USING [DB_Produccion].[dbo].[NombreTabla] AS source
ON (target.ID = source.ID)
WHEN MATCHED THEN 
    UPDATE SET 
        target.Columna1 = source.Columna1,
        target.Columna2 = source.Columna2
        -- Agrega todas las columnas que quieras actualizar
WHEN NOT MATCHED BY TARGET THEN
    INSERT (Columna1, Columna2, ID)
    VALUES (source.Columna1, source.Columna2, source.ID);
GO
*/

-- ============================================
-- PLANTILLA PARA MÚLTIPLES TABLAS
-- ============================================

-- Tabla 1: Ejemplo
/*
PRINT 'Copiando tabla: Ejemplo';
TRUNCATE TABLE [DB_Test].[dbo].[Ejemplo];
INSERT INTO [DB_Test].[dbo].[Ejemplo]
SELECT * FROM [DB_Produccion].[dbo].[Ejemplo];
PRINT 'Registros copiados: ' + CAST(@@ROWCOUNT AS VARCHAR(10));
GO
*/

-- Agrega más tablas según necesites...

PRINT 'Migración de datos completada';

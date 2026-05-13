-- Script para obtener la lista de todas las tablas de la base de datos
-- en formato para copiar directamente al script PowerShell

-- OPCION 1: Lista simple de nombres de tablas
SELECT 
    '    "' + TABLE_NAME + '",'
FROM INFORMATION_SCHEMA.TABLES
WHERE TABLE_TYPE = 'BASE TABLE'
  AND TABLE_SCHEMA = 'dbo'
ORDER BY TABLE_NAME;

-- OPCION 2: Lista completa con estadisticas (para que decidas cuales copiar)
SELECT 
    TABLE_NAME as [Nombre Tabla],
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = t.TABLE_NAME) as [Num Columnas],
    '    "' + TABLE_NAME + '",' as [Para PowerShell]
FROM INFORMATION_SCHEMA.TABLES t
WHERE TABLE_TYPE = 'BASE TABLE'
  AND TABLE_SCHEMA = 'dbo'
ORDER BY TABLE_NAME;

-- OPCION 3: Generar array completo de PowerShell listo para copiar
DECLARE @resultado NVARCHAR(MAX) = '';

SELECT @resultado = @resultado + '    "' + TABLE_NAME + '",' + CHAR(13) + CHAR(10)
FROM INFORMATION_SCHEMA.TABLES
WHERE TABLE_TYPE = 'BASE TABLE'
  AND TABLE_SCHEMA = 'dbo'
ORDER BY TABLE_NAME;

-- Quitar la ultima coma
SET @resultado = LEFT(@resultado, LEN(@resultado) - 3);

PRINT '# Copiar esto en el script PowerShell:';
PRINT '$tablasACopiar = @(';
PRINT @resultado;
PRINT ')';

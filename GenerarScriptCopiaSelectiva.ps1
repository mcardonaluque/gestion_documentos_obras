# Script para generar INSERT statements de solo columnas comunes
# entre PRODUCCION y TEST

# CONFIGURACION
$servidorProduccion = "GUADIX"
$dbProduccion = "OBRAS"
$servidorTest = "NAYADE"
$dbTest = "OBRAS_TEST"
$archivoSalida = ".\CopiarDatos_SoloColumnasComunes.sql"

# Tablas de PRODUCCION a copiar a TEST
# Lista obtenida de GUADIX/OBRAS excluyendo tablas de sistema y temporales
# IMPORTANTE: Revisa esta lista y comenta (#) las tablas que NO quieras copiar
$tablasACopiar = @(
    "AutorizacionesProyectos",
    "Avisos",
    "Ayuda_Tecnica",
    "CambiosDestino",
    "CambiosDestinoAltas",
    "CambiosDestinoBajas",
    "CertificacionesDeObras",
    "ClasificacionProyectos",
    "CodigosProyectosModificados",
    "Datos_Ejecucion_Obras",
    "Datos_Estadisticos",
    "Datosadicionales",
    "DatosInicioDeObras",
    "Decretos",
    "DestinosDeDocumentos",
    "documentacionexpedientes",
    "documento_expedientes",
    "documento_genericos",
    "Documentos_de_fases_de_proyectos",
    "Documentos_de_fases_de_proyectos2",
    "Empresas_Estudio_Geo",
    "ExcluirOrganismos",
    "expedientes",
    "fase_documentos",
    "FasesDeProyectos",
    "firmante_documentos",
    "firmante_genericos",
    "firmantesdocumentosexpediente",
    "FormasDeEjecucion",
    "FormulasRevision",
    "GruposClasificacion",
    "GruposPlanes",
    "Honorarios",
    "Importes_financiacion",
    "Importes_financiacion1",
    "ImportesDeObras",
    "ImportesPorOrganismo",
    "IndicadoresDeObras",
    "Justificacion_Obras",
    "MesaContratacionCargos",
    "Obras_En_Ejecucion",
    "Obras_En_Ejecucion_Organismo",
    "ObrasCedidas",
    "ObrasRelacionadas",
    "PartidosJudiciales",
    "Planes",
    "PlanesAsignaciones",
    "PlanSeguridadYSalud",
    "Pliegos",
    "PliegosClasificacion",
    "PliegosObras",
    "programas",
    "Prorrogas",
    "ProrrogasConPenalidades",
    "Proyectos",
    "reglas_validacion_fechas",
    "RelacionesObras",
    "SubContrataciones",
    "SubcontratacionesCertif",
    "Subvenciones",
    "zonas"

    # Tablas de usuarios/permisos - Descomenta si quieres copiar usuarios
    # "users",
    # "Usuarios",
    # "roles",
    # "model_has_permissions",
    # "model_has_roles",
    # "role_has_permissions"

    # Tablas historicas/calendario - Descomenta si las necesitas
    # "_PDT2010",
    # "CALENDARIO_2010"
)

Write-Host "Generando script de copia selectiva de datos..." -ForegroundColor Cyan
Write-Host "Produccion: $servidorProduccion.$dbProduccion" -ForegroundColor Yellow
Write-Host "Test: $servidorTest.$dbTest" -ForegroundColor Yellow
Write-Host ""

# Query para obtener columnas de una tabla
$queryColumnas = @"
SELECT COLUMN_NAME, ORDINAL_POSITION, DATA_TYPE
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = 'dbo' AND TABLE_NAME = @tabla
ORDER BY ORDINAL_POSITION
"@

# Conectar a ambos servidores
$connProduccion = New-Object System.Data.SqlClient.SqlConnection
$connProduccion.ConnectionString = "Server=$servidorProduccion;Database=$dbProduccion;Integrated Security=True;TrustServerCertificate=True;"

$connTest = New-Object System.Data.SqlClient.SqlConnection
$connTest.ConnectionString = "Server=$servidorTest;Database=$dbTest;Integrated Security=True;TrustServerCertificate=True;"

try {
    $connProduccion.Open()
    $connTest.Open()
    Write-Host "Conexiones establecidas correctamente" -ForegroundColor Green
    Write-Host ""

    $scriptCompleto = @"
-- ============================================
-- Script de copia selectiva de datos
-- De: $servidorProduccion.$dbProduccion (PRODUCCION)
-- A: $servidorTest.$dbTest (TEST)
-- ============================================
-- Este script solo copia columnas que existen en AMBAS bases de datos
-- Las columnas nuevas en TEST mantendran sus valores por defecto o NULL
-- ============================================

USE [$dbTest];
GO

"@

    foreach ($tabla in $tablasACopiar) {
        Write-Host "Procesando tabla: $tabla" -ForegroundColor Cyan

        # Obtener columnas de PRODUCCION
        $cmdProd = New-Object System.Data.SqlClient.SqlCommand
        $cmdProd.Connection = $connProduccion
        $cmdProd.CommandText = $queryColumnas
        $cmdProd.Parameters.AddWithValue("@tabla", $tabla) | Out-Null

        $adapterProd = New-Object System.Data.SqlClient.SqlDataAdapter $cmdProd
        $dtProd = New-Object System.Data.DataTable
        $adapterProd.Fill($dtProd) | Out-Null

        # Obtener columnas de TEST
        $cmdTest = New-Object System.Data.SqlClient.SqlCommand
        $cmdTest.Connection = $connTest
        $cmdTest.CommandText = $queryColumnas
        $cmdTest.Parameters.AddWithValue("@tabla", $tabla) | Out-Null

        $adapterTest = New-Object System.Data.SqlClient.SqlDataAdapter $cmdTest
        $dtTest = New-Object System.Data.DataTable
        $adapterTest.Fill($dtTest) | Out-Null

        if ($dtProd.Rows.Count -eq 0 -or $dtTest.Rows.Count -eq 0) {
            Write-Host "  Tabla no encontrada en una o ambas bases de datos - OMITIDA" -ForegroundColor Yellow
            continue
        }

        # Encontrar columnas comunes
        $columnasProd = $dtProd | Select-Object -ExpandProperty COLUMN_NAME
        $columnasTest = $dtTest | Select-Object -ExpandProperty COLUMN_NAME
        $columnasComunes = $columnasProd | Where-Object { $columnasTest -contains $_ }

        if ($columnasComunes.Count -eq 0) {
            Write-Host "  No hay columnas comunes - OMITIDA" -ForegroundColor Yellow
            continue
        }

        $listaColumnas = ($columnasComunes | ForEach-Object { "[$_]" }) -join ", "

        Write-Host "  Columnas comunes: $($columnasComunes.Count)" -ForegroundColor Green

        # Generar script para esta tabla
        $scriptCompleto += @"

-- ============================================
-- Tabla: $tabla
-- Columnas comunes: $($columnasComunes.Count)
-- ============================================
PRINT 'Copiando datos de tabla: $tabla';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[$tabla];
GO

INSERT INTO [dbo].[$tabla] ($listaColumnas)
SELECT $listaColumnas
FROM [$servidorProduccion].[$dbProduccion].[dbo].[$tabla];
GO

DECLARE @rowcount$tabla INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla $tabla' + ': ' + CAST(@rowcount$tabla AS VARCHAR(10));
GO

"@
    }

    $scriptCompleto += @"

-- ============================================
-- FIN DEL SCRIPT
-- ============================================
PRINT 'Proceso completado';
GO
"@

    # Guardar el script
    $scriptCompleto | Out-File -FilePath $archivoSalida -Encoding UTF8

    Write-Host ""
    Write-Host "============================================" -ForegroundColor Green
    Write-Host "Script generado exitosamente" -ForegroundColor Green
    Write-Host "============================================" -ForegroundColor Green
    Write-Host ""
    Write-Host "Archivo: $archivoSalida" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "SIGUIENTE PASO:" -ForegroundColor Cyan
    Write-Host "1. Abre el archivo $archivoSalida" -ForegroundColor White
    Write-Host "2. Revisa las opciones para cada tabla" -ForegroundColor White
    Write-Host "3. Descomenta la opcion que necesites (Opcion 1 o 2)" -ForegroundColor White
    Write-Host "4. Ejecuta el script en SSMS contra $servidorTest.$dbTest" -ForegroundColor White
    Write-Host ""

} catch {
    Write-Host "Error: $_" -ForegroundColor Red
} finally {
    if ($connProduccion.State -eq 'Open') { $connProduccion.Close() }
    if ($connTest.State -eq 'Open') { $connTest.Close() }
}

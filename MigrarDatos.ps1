# Script para generar el script de migracion de datos de PRODUCCION a TEST
$sqlPackagePath = 'C:\Program Files\Microsoft Visual Studio\18\Community\Common7\IDE\Extensions\Microsoft\SQLDB\DAC\SqlPackage.exe'
$servidorProduccion = 'GUADIX'
$baseDatosProduccion = 'OBRAS'
$servidorTest = 'NAYADE'
$baseDatosTest = 'OBRAS_TEST'
$connStringProduccion = "Server=$servidorProduccion;Database=$baseDatosProduccion;Trusted_Connection=True;TrustServerCertificate=True;Encrypt=False"
$connStringTest = "Server=$servidorTest;Database=$baseDatosTest;Trusted_Connection=True;TrustServerCertificate=True;Encrypt=False"
$dacpacProduccion = '.\Produccion_Data.dacpac'
$scriptMigracionDatos = '.\MigracionDatos_Produccion_a_Test.sql'
Write-Host 'Extrayendo datos de PRODUCCION (GUADIX/OBRAS)...' -ForegroundColor Cyan
& $sqlPackagePath /Action:Extract /SourceConnectionString:$connStringProduccion /TargetFile:$dacpacProduccion /p:ExtractAllTableData=True /p:VerifyExtraction=False
if ($LASTEXITCODE -ne 0) { Write-Host 'Error al extraer datos' -ForegroundColor Red; exit 1 }
Write-Host 'Generando script de migracion hacia TEST (NAYADE/OBRAS_TEST)...' -ForegroundColor Cyan
& $sqlPackagePath /Action:Script /SourceFile:$dacpacProduccion /TargetConnectionString:$connStringTest /OutputPath:$scriptMigracionDatos /p:IgnoreColumnCollation=True /p:IgnoreComments=True /p:IgnoreWhitespace=True /p:VerifyDeployment=False /p:BlockOnPossibleDataLoss=False
if ($LASTEXITCODE -ne 0) { Write-Host 'Error al generar script' -ForegroundColor Red; exit 1 }
Write-Host '' -ForegroundColor Green
Write-Host 'COMPLETADO EXITOSAMENTE' -ForegroundColor Green
Write-Host 'Script generado en: MigracionDatos_Produccion_a_Test.sql' -ForegroundColor Yellow
Write-Host 'Revisa el archivo antes de ejecutarlo en TEST' -ForegroundColor Yellow

# Script para generar script de migración entre bases de datos SQL Server
# Usando SqlPackage.exe directamente (ignora errores de case-sensitivity del proyecto)

$sqlPackagePath = "C:\Program Files\Microsoft Visual Studio\18\Community\Common7\IDE\Extensions\Microsoft\SQLDB\DAC\SqlPackage.exe"

# CONFIGURACIÓN - Modifica estos valores según tus necesidades
$servidorOrigen = "localhost"  # Servidor de origen
$baseDatosOrigen = "DB_Origen"  # Base de datos origen
$servidorDestino = "localhost"  # Servidor de destino
$baseDatosDestino = "DB_Destino"  # Base de datos destino
$archivoScriptSalida = ".\ScriptMigracion.sql"

# Autenticación (cambiar según necesites)
# Para Windows Authentication usa Trusted_Connection=True
# Para SQL Authentication usa User Id=usuario;Password=contraseña
$authOrigen = "Trusted_Connection=True"
$authDestino = "Trusted_Connection=True"

Write-Host "=== Generador de Script de Migración ===" -ForegroundColor Cyan
Write-Host "Origen: $servidorOrigen.$baseDatosOrigen" -ForegroundColor Yellow
Write-Host "Destino: $servidorDestino.$baseDatosDestino" -ForegroundColor Yellow
Write-Host ""

# Construir connection strings
$connectionStringOrigen = "Server=$servidorOrigen;Database=$baseDatosOrigen;$authOrigen"
$connectionStringDestino = "Server=$servidorDestino;Database=$baseDatosDestino;$authDestino"

Write-Host "Generando script de migración..." -ForegroundColor Green

# Ejecutar SqlPackage para generar el script
& $sqlPackagePath `
    /Action:Script `
    /SourceConnectionString:$connectionStringOrigen `
    /TargetConnectionString:$connectionStringDestino `
    /OutputPath:$archivoScriptSalida `
    /p:IgnoreColumnCollation=True `
    /p:IgnoreComments=True `
    /p:IgnoreWhitespace=True `
    /p:VerifyDeployment=False

if ($LASTEXITCODE -eq 0) {
    Write-Host ""
    Write-Host "✓ Script generado exitosamente: $archivoScriptSalida" -ForegroundColor Green
    Write-Host ""
    Write-Host "Puedes revisar y ejecutar el script en SSMS" -ForegroundColor Cyan
} else {
    Write-Host ""
    Write-Host "✗ Error al generar el script. Código: $LASTEXITCODE" -ForegroundColor Red
}

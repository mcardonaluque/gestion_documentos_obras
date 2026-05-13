# Script para obtener lista de tablas de una base de datos

# CONFIGURACION
$servidor = "GUADIX"
$database = "OBRAS"

Write-Host "Obteniendo tablas de PRODUCCION: $servidor.$database" -ForegroundColor Cyan
Write-Host ""

# Query que excluye tablas de sistema, Laravel y temporales
$query = @"
SELECT TABLE_NAME
FROM INFORMATION_SCHEMA.TABLES
WHERE TABLE_TYPE = 'BASE TABLE'
  AND TABLE_SCHEMA = 'dbo'
  -- Excluir tablas de sistema
  AND TABLE_NAME NOT IN ('sysdiagrams', 'dtproperties')
  -- Excluir tablas de Laravel/sistema
  AND TABLE_NAME NOT IN ('cache', 'cache_locks', 'sessions', 'jobs', 'failed_jobs', 'job_batches', 'migrations', 'password_reset_tokens')
  -- Excluir tablas temporales o de prueba
  AND TABLE_NAME NOT LIKE 'Temp_%'
  AND TABLE_NAME NOT LIKE '%_old'
  AND TABLE_NAME NOT LIKE '%_OLD'
  AND TABLE_NAME NOT LIKE '%_org'
  AND TABLE_NAME NOT LIKE 'Prueba%'
  AND TABLE_NAME NOT LIKE 'T_%'
  AND TABLE_NAME NOT LIKE 't_%'
  -- Excluir tablas muy especificas de sistema
  AND TABLE_NAME NOT LIKE 'mlg_%'
  AND TABLE_NAME NOT IN ('a', 'date_rule_executions', 'date_validation_rules', 'event_logs', 'notifications')
ORDER BY TABLE_NAME
"@

try {
    $conn = New-Object System.Data.SqlClient.SqlConnection
    $conn.ConnectionString = "Server=$servidor;Database=$database;Integrated Security=True;TrustServerCertificate=True;"
    $conn.Open()

    $cmd = New-Object System.Data.SqlClient.SqlCommand
    $cmd.Connection = $conn
    $cmd.CommandText = $query

    $adapter = New-Object System.Data.SqlClient.SqlDataAdapter $cmd
    $dt = New-Object System.Data.DataTable
    $adapter.Fill($dt) | Out-Null

    Write-Host "============================================" -ForegroundColor Green
    Write-Host "TABLAS ENCONTRADAS: $($dt.Rows.Count)" -ForegroundColor Green
    Write-Host "============================================" -ForegroundColor Green
    Write-Host ""

    # Mostrar la lista en formato PowerShell
    Write-Host "# Copia esto en GenerarScriptCopiaSelectiva.ps1:" -ForegroundColor Yellow
    Write-Host ""
    Write-Host '$tablasACopiar = @(' -ForegroundColor White

    for ($i = 0; $i -lt $dt.Rows.Count; $i++) {
        $tabla = $dt.Rows[$i]["TABLE_NAME"]
        if ($i -eq $dt.Rows.Count - 1) {
            Write-Host "    `"$tabla`"" -ForegroundColor White
        } else {
            Write-Host "    `"$tabla`"," -ForegroundColor White
        }
    }

    Write-Host ')' -ForegroundColor White
    Write-Host ""

    # Guardar en archivo
    $archivoSalida = ".\ListaTablas_PRODUCCION.txt"
    $contenido = '$tablasACopiar = @(' + "`r`n"

    for ($i = 0; $i -lt $dt.Rows.Count; $i++) {
        $tabla = $dt.Rows[$i]["TABLE_NAME"]
        if ($i -eq $dt.Rows.Count - 1) {
            $contenido += "    `"$tabla`"`r`n"
        } else {
            $contenido += "    `"$tabla`",`r`n"
        }
    }

    $contenido += ')'
    $contenido | Out-File -FilePath $archivoSalida -Encoding UTF8

    Write-Host "Lista guardada en: $archivoSalida" -ForegroundColor Green
    Write-Host ""

    # Mostrar tambien lista simple
    Write-Host "============================================" -ForegroundColor Cyan
    Write-Host "LISTA SIMPLE DE TABLAS:" -ForegroundColor Cyan
    Write-Host "============================================" -ForegroundColor Cyan

    foreach ($row in $dt.Rows) {
        Write-Host "  - $($row['TABLE_NAME'])" -ForegroundColor White
    }

    $conn.Close()

} catch {
    Write-Host "Error: $_" -ForegroundColor Red
}

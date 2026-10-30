<?php
// Script temporal: inserta un docblock de 1 línea antes de la declaración de clase.
$map = [
    'app/Filament/Obras/Resources/ActasDeRecepcion/Pages/CreateActaderecepcion.php' => 'Página estándar de creación de actas de recepción (sin lógica propia).',
    'app/Filament/Obras/Resources/ActasDeRecepcion/Pages/EditActaderecepcion.php' => 'Página estándar de edición de actas de recepción (sin lógica propia).',
    'app/Filament/Obras/Resources/ActasDeRecepcion/Pages/ListActaderecepcions.php' => 'Listado estándar de actas de recepción (sin lógica propia).',
    'app/Filament/Obras/Resources/ActasDeReplanteo/Pages/CreateActadereplanteo.php' => 'Página estándar de creación de actas de replanteo (sin lógica propia).',
    'app/Filament/Obras/Resources/ActasDeReplanteo/Pages/EditActadereplanteo.php' => 'Página estándar de edición de actas de replanteo (sin lógica propia).',
    'app/Filament/Obras/Resources/ActasDeReplanteo/Pages/ListActadereplanteos.php' => 'Listado estándar de actas de replanteo (sin lógica propia).',
    'app/Filament/Obras/Resources/Alertas/Pages/CreateAlerta.php' => 'Página estándar de creación de alertas (sin lógica propia).',
    'app/Filament/Obras/Resources/Alertas/Pages/EditAlerta.php' => 'Página estándar de edición de alertas (sin lógica propia).',
    'app/Filament/Obras/Resources/Alertas/Pages/ListAlertas.php' => 'Listado estándar de alertas (sin lógica propia).',
    'app/Filament/Obras/Resources/Avisos/Pages/CreateAviso.php' => 'Página estándar de creación de avisos (sin lógica propia).',
    'app/Filament/Obras/Resources/Avisos/Pages/EditAviso.php' => 'Página estándar de edición de avisos (sin lógica propia).',
    'app/Filament/Obras/Resources/Avisos/Pages/ListAvisos.php' => 'Listado estándar de avisos (sin lógica propia).',
    'app/Filament/Obras/Resources/Certificaciones/Pages/CreateCertificaciones.php' => 'Página estándar de creación de certificaciones (sin lógica propia).',
    'app/Filament/Obras/Resources/Certificaciones/Pages/EditCertificaciones.php' => 'Página estándar de edición de certificaciones (sin lógica propia).',
    'app/Filament/Obras/Resources/Certificaciones/Pages/ListCertificaciones.php' => 'Listado estándar de certificaciones (sin lógica propia).',
    'app/Filament/Obras/Resources/Contratistas/Pages/ListContratistas.php' => 'Listado estándar de contratistas (sin lógica propia).',
    'app/Filament/Obras/Resources/DatosDeInicioDeObras/Pages/ViewInicioDeObra.php' => 'Vista estándar de los datos de inicio de obra (sin lógica propia).',
    'app/Filament/Obras/Resources/DatosEjecucionObras/Pages/CreateDatosEjecucionObras.php' => 'Página estándar de creación de datos de ejecución de obras (sin lógica propia).',
    'app/Filament/Obras/Resources/DatosEjecucionObras/Pages/EditDatosEjecucionObras.php' => 'Página estándar de edición de datos de ejecución de obras (sin lógica propia).',
    'app/Filament/Obras/Resources/Documentoexpedientes/Pages/CreateDocumentoexpediente.php' => 'Página estándar de creación de documentos de expediente (sin lógica propia).',
    'app/Filament/Obras/Resources/Documentoexpedientes/Pages/ListDocumentoexpedientes.php' => 'Listado estándar de documentos de expediente (sin lógica propia).',
    'app/Filament/Obras/Resources/Expedientes/Pages/CreateExpediente.php' => 'Página estándar de creación de expedientes (sin lógica propia).',
    'app/Filament/Obras/Resources/Expedientes/Pages/EditExpediente.php' => 'Página estándar de edición de expedientes (sin lógica propia).',
    'app/Filament/Obras/Resources/FasedeProyectos/Pages/CreateFasedeProyecto.php' => 'Página estándar de creación de fases de proyecto (sin lógica propia).',
    'app/Filament/Obras/Resources/FasedeProyectos/Pages/EditFasedeProyecto.php' => 'Página estándar de edición de fases de proyecto (sin lógica propia).',
    'app/Filament/Obras/Resources/FasedeProyectos/Pages/ListFasedeProyectos.php' => 'Listado estándar de fases de proyecto (sin lógica propia).',
    'app/Filament/Obras/Resources/ImportesDeObras/Pages/CreateImportesDeobras.php' => 'Página estándar de creación de importes de obras (sin lógica propia).',
    'app/Filament/Obras/Resources/ImportesDeObras/Pages/EditImportesDeobras.php' => 'Página estándar de edición de importes de obras (sin lógica propia).',
    'app/Filament/Obras/Resources/ImportesDeObras/Pages/ListImportesDeobras.php' => 'Listado estándar de importes de obras (sin lógica propia).',
    'app/Filament/Obras/Resources/ImportesPorOrganismos/Pages/CreateImportesPorOrganismo.php' => 'Página estándar de creación de importes por organismo (sin lógica propia).',
    'app/Filament/Obras/Resources/ImportesPorOrganismos/Pages/EditImportesPorOrganismo.php' => 'Página estándar de edición de importes por organismo (sin lógica propia).',
    'app/Filament/Obras/Resources/ImportesPorOrganismos/Pages/ListImportesPorOrganismos.php' => 'Listado estándar de importes por organismo (sin lógica propia).',
    'app/Filament/Obras/Resources/NormativaPpacs/Pages/CreateNormativaPpac.php' => 'Página estándar de creación de normativas PPAC (sin lógica propia).',
    'app/Filament/Obras/Resources/NormativaPpacs/Pages/ListNormativaPpacs.php' => 'Listado estándar de normativas PPAC (sin lógica propia).',
    'app/Filament/Obras/Resources/Notifications/Pages/EditNotification.php' => 'Página estándar de edición de notificaciones (sin lógica propia).',
    'app/Filament/Obras/Resources/Notifications/Pages/ListNotifications.php' => 'Listado estándar de notificaciones (sin lógica propia).',
    'app/Filament/Obras/Resources/ObraCedidas/Pages/ListObraCedidas.php' => 'Listado estándar de obras cedidas (sin lógica propia).',
    'app/Filament/Obras/Resources/ObrasCedidas/Pages/CreateObrasCedidas.php' => 'Página estándar de creación del recurso esqueleto de obras cedidas (sin lógica propia).',
    'app/Filament/Obras/Resources/ObrasCedidas/Pages/EditObrasCedidas.php' => 'Página estándar de edición del recurso esqueleto de obras cedidas (sin lógica propia).',
    'app/Filament/Obras/Resources/ObrasCedidas/Pages/ListObrasCedidas.php' => 'Listado estándar del recurso esqueleto de obras cedidas (sin lógica propia).',
    'app/Filament/Obras/Resources/ObrasPendientesProyecto/Pages/EditObrasPendientesProyecto.php' => 'Página estándar de edición de obras pendientes de proyecto (sin lógica propia).',
    'app/Filament/Obras/Resources/ObrasPendientesProyecto/Pages/ListObrasPendientesProyectos.php' => 'Listado estándar de obras pendientes de proyecto (sin lógica propia).',
    'app/Filament/Obras/Resources/ObrasPendientesProyecto/Pages/ViewObrasPendientesProyecto.php' => 'Vista estándar de una obra pendiente de proyecto (sin lógica propia).',
    'app/Filament/Obras/Resources/PendienteContratacionObras/Pages/CreatePendienteContratacionObra.php' => 'Página estándar de creación de contrataciones de obra (sin lógica propia).',
    'app/Filament/Obras/Resources/PendienteContratacionObras/Pages/EditPendienteContratacionObra.php' => 'Página estándar de edición de contrataciones de obra (sin lógica propia).',
    'app/Filament/Obras/Resources/PendienteContratacionObras/Pages/ListPendienteContratacionObras.php' => 'Listado estándar de contrataciones de obra (oculta el encabezado).',
    'app/Filament/Obras/Resources/PlanSses/Pages/CreatePlanSs.php' => 'Página estándar de creación de planes de seguridad y salud (sin lógica propia).',
    'app/Filament/Obras/Resources/PlanSses/Pages/EditPlanSs.php' => 'Página estándar de edición de planes de seguridad y salud (sin lógica propia).',
    'app/Filament/Obras/Resources/PlanSses/Pages/ListPlanSses.php' => 'Listado estándar de planes de seguridad y salud (sin lógica propia).',
    'app/Filament/Obras/Resources/Prorrogas/Pages/EditProrroga.php' => 'Página de edición del expediente que aloja el relation manager de prórrogas (sin lógica propia).',
    'app/Filament/Obras/Resources/Prorrogas/Pages/ListProrrogas.php' => 'Listado estándar de expedientes para la gestión de prórrogas (sin lógica propia).',
];

$ok = 0;
foreach ($map as $file => $text) {
    $contents = file_get_contents($file);
    if ($contents === false) {
        fwrite(STDERR, "NO EXISTE: $file\n");
        continue;
    }
    if (!preg_match('/^((?:final |abstract )?class )/m', $contents, $m, PREG_OFFSET_CAPTURE)) {
        fwrite(STDERR, "SIN CLASE: $file\n");
        continue;
    }
    $pos = $m[1][1];
    // Comprobar que no haya ya docblock justo antes
    $before = rtrim(substr($contents, 0, $pos));
    if (substr($before, -2) === '*/') {
        fwrite(STDERR, "YA DOCUMENTADO: $file\n");
        continue;
    }
    $docblock = "/** {$text} */\n";
    $contents = substr($contents, 0, $pos) . $docblock . substr($contents, $pos);
    file_put_contents($file, $contents);
    $ok++;
}
echo "Insertados: $ok de " . count($map) . "\n";

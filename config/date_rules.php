<?php

declare(strict_types=1);

use App\Models\DatosDeInicioDeObras;
use App\Models\DatosEjecucionObras;
use App\Models\DocumentoExpediente;
use App\Models\Expediente;
use App\Models\Justificacion_Obra;
use App\Models\ObraCedida;

return [
    'async_notifications' => true,
    'notifications_queue' => 'default',

    'observed_models' => [
        Expediente::class,
        DocumentoExpediente::class,
        DatosDeInicioDeObras::class,
        DatosEjecucionObras::class,
        ObraCedida::class,
        Justificacion_Obra::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Catalogo de hitos de fechas
    |--------------------------------------------------------------------------
    | Define hitos funcionales y su origen real (tabla + campo) para crear una
    | proyeccion unificada de fechas por expediente.
    */
    'hitos_catalogo' => [
        ['codigo_hito' => 'notif_ayto', 'descripcion' => 'Notificacion al ayuntamiento', 'tabla_origen' => 'DatosInicioDeObras', 'campo_origen' => 'fecha_notificacion_ayto', 'fase' => 'inicio', 'obligatorio' => true, 'repetible' => false],
        ['codigo_hito' => 'pet_acta_replanteo_previo', 'descripcion' => 'Peticion de acta de replanteo previo', 'tabla_origen' => 'DatosInicioDeObras', 'campo_origen' => 'fecha_pet_acta_replanteo', 'fase' => 'inicio', 'obligatorio' => true, 'repetible' => false],
        ['codigo_hito' => 'acta_replanteo_previo', 'descripcion' => 'Acta de replanteo previo', 'tabla_origen' => 'DatosInicioDeObras', 'campo_origen' => 'fecha_acta_replanteo_previo', 'fase' => 'inicio', 'obligatorio' => false, 'repetible' => false],
        ['codigo_hito' => 'pet_ayuda_tecnica', 'descripcion' => 'Peticion de ayuda tecnica', 'tabla_origen' => 'DatosInicioDeObras', 'campo_origen' => 'fecha_rem_pet_ayuda', 'fase' => 'inicio', 'obligatorio' => false, 'repetible' => false],
        ['codigo_hito' => 'envio_fiscalizacion', 'descripcion' => 'Envio a fiscalizacion', 'tabla_origen' => 'DatosInicioDeObras', 'campo_origen' => 'fecha_envio_fiscalizacion', 'fase' => 'inicio', 'obligatorio' => false, 'repetible' => false],
        ['codigo_hito' => 'fecha_fiscalizacion', 'descripcion' => 'Fecha de fiscalizacion', 'tabla_origen' => 'DatosInicioDeObras', 'campo_origen' => 'fecha_fiscalizacion', 'fase' => 'inicio', 'obligatorio' => false, 'repetible' => false],
        ['codigo_hito' => 'prev_comienzo_obra', 'descripcion' => 'Fecha prevista de comienzo', 'tabla_origen' => 'DatosInicioDeObras', 'campo_origen' => 'fecha_prev_comienzo_obra', 'fase' => 'inicio', 'obligatorio' => false, 'repetible' => false],
        ['codigo_hito' => 'prev_fin_obra', 'descripcion' => 'Fecha prevista de finalizacion', 'tabla_origen' => 'DatosInicioDeObras', 'campo_origen' => 'fecha_prev_term_obra', 'fase' => 'inicio', 'obligatorio' => false, 'repetible' => false],

        ['codigo_hito' => 'entrega_proyecto', 'descripcion' => 'Entrega de proyecto', 'tabla_origen' => 'Proyectos', 'campo_origen' => 'fecha_entrega_proyecto', 'fase' => 'proyecto', 'obligatorio' => false, 'repetible' => false],
        ['codigo_hito' => 'recepcion_proyecto', 'descripcion' => 'Recepcion de proyecto', 'tabla_origen' => 'Proyectos', 'campo_origen' => 'fecha_recepcion_proyecto', 'fase' => 'proyecto', 'obligatorio' => false, 'repetible' => false],
        ['codigo_hito' => 'remision_proyecto_ayto', 'descripcion' => 'Remision de proyecto al ayuntamiento', 'tabla_origen' => 'Proyectos', 'campo_origen' => 'fecha_remision_ayto', 'fase' => 'proyecto', 'obligatorio' => false, 'repetible' => false],
        ['codigo_hito' => 'aprobacion_proyecto_ayto', 'descripcion' => 'Aprobacion de proyecto por ayuntamiento', 'tabla_origen' => 'Proyectos', 'campo_origen' => 'fecha_aprobacion_ayto', 'fase' => 'proyecto', 'obligatorio' => false, 'repetible' => false],

        ['codigo_hito' => 'acta_replanteo_inicio', 'descripcion' => 'Inicio del acta de replanteo', 'tabla_origen' => 'ActasDeReplanteo', 'campo_origen' => 'Fecha_Inicio_Acta_Replanteo', 'fase' => 'ejecucion', 'obligatorio' => true, 'repetible' => false],
        ['codigo_hito' => 'acta_replanteo_fin', 'descripcion' => 'Fin del acta de replanteo', 'tabla_origen' => 'ActasDeReplanteo', 'campo_origen' => 'Fecha_Final_Acta_Replanteo', 'fase' => 'ejecucion', 'obligatorio' => false, 'repetible' => false],
        ['codigo_hito' => 'acta_replanteo_prorroga', 'descripcion' => 'Prorroga de acta de replanteo', 'tabla_origen' => 'ActasDeReplanteo', 'campo_origen' => 'Fecha_Prorroga_Acta_Replanteo', 'fase' => 'ejecucion', 'obligatorio' => false, 'repetible' => true],

        ['codigo_hito' => 'acta_recepcion_provisional', 'descripcion' => 'Acta de recepcion provisional', 'tabla_origen' => 'ActasRecepcionObra', 'campo_origen' => 'Fecha_Acta_RecProv', 'fase' => 'recepcion', 'obligatorio' => true, 'repetible' => false],
        ['codigo_hito' => 'acta_recepcion_definitiva', 'descripcion' => 'Acta de recepcion', 'tabla_origen' => 'ActasRecepcionObra', 'campo_origen' => 'Fecha_Acta_Rec', 'fase' => 'recepcion', 'obligatorio' => false, 'repetible' => false],
        ['codigo_hito' => 'recepcion_medicion', 'descripcion' => 'Fecha de medicion', 'tabla_origen' => 'ActasRecepcionObra', 'campo_origen' => 'Fecha_Medicion', 'fase' => 'recepcion', 'obligatorio' => false, 'repetible' => false],

        ['codigo_hito' => 'cesion_fecha', 'descripcion' => 'Fecha de cesion', 'tabla_origen' => 'ObrasCedidas', 'campo_origen' => 'FechaCesion', 'fase' => 'cesion', 'obligatorio' => true, 'repetible' => false],
        ['codigo_hito' => 'cesion_adjudicacion', 'descripcion' => 'Fecha de adjudicacion en cesion', 'tabla_origen' => 'ObrasCedidas', 'campo_origen' => 'FechaAdjudicacion', 'fase' => 'cesion', 'obligatorio' => false, 'repetible' => false],
        ['codigo_hito' => 'cesion_contrato', 'descripcion' => 'Fecha de contrato en cesion', 'tabla_origen' => 'ObrasCedidas', 'campo_origen' => 'FechaContrato', 'fase' => 'cesion', 'obligatorio' => false, 'repetible' => false],

        ['codigo_hito' => 'doc_incorporacion', 'descripcion' => 'Fecha de incorporacion de documento', 'tabla_origen' => 'dbo.documentacionexpedientes', 'campo_origen' => 'fechaincorporacion', 'fase' => 'documental', 'obligatorio' => false, 'repetible' => true],
        ['codigo_hito' => 'doc_help', 'descripcion' => 'Fecha help de documento', 'tabla_origen' => 'dbo.documentacionexpedientes', 'campo_origen' => 'fechaHelp', 'fase' => 'documental', 'obligatorio' => false, 'repetible' => true],
    ],
];

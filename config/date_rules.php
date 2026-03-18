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
];

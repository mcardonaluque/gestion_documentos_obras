<?php

   return [
   'events' => [

        'expediente_aprobado' => [
            'title'   => 'Expediente aprobado',
            'message' => 'El expediente :codigo ha sido aprobado.',
            'type'    => 'success',
        ],

        'documento_aprobado' => [
            'title'   => 'Documento aprobado',
            'message' => 'El documento :documento ha sido aprobado.',
            'type'    => 'success',
        ],

        'documento_caducado' => [
            'title'   => 'Documento caducado',
            'message' => 'El documento :documento ha caducado.',
            'type'    => 'warning',
        ],

        'documento_firmado' => [
            'title'   => 'Documento firmado',
            'message' => 'El documento :documento ha sido firmado.',
            'type'    => 'success',
        ],

        'documento_notificado' => [
            'title'   => 'Documento notificado',
            'message' => 'El documento :documento ha sido notificado.',
            'type'    => 'info',
        ],

        'documento_incorporado' => [
            'title'   => 'Documento incorporado',
            'message' => 'El documento :documento ha sido incorporado al expediente :codigo.',
            'type'    => 'info',
        ],

        'alerta_activada' => [
            'title'   => 'Alerta activada',
            'message' => 'La alerta ":alerta" ha sido activada.',
            'type'    => 'danger',
        ],

        'peticion_documento' => [
            'title'   => 'Petición de documento realizada',
            'message' => 'Se ha solicitado el documento :documento.',
            'type'    => 'info',
        ],

        'expediente_estado_cambiado' => [
            'title'   => 'Estado del expediente cambiado',
            'message' => 'El expediente :codigo ha cambiado al estado :estado.',
            'type'    => 'info',
        ],

        'expediente_fase_cambiada' => [
            'title'   => 'Fase del expediente cambiada',
            'message' => 'El expediente :codigo ha cambiado a la fase :fase.',
            'type'    => 'info',
        ],
         'peticion_subsanación' => [
            'title'   => 'Petición de subsanación realizada',
            'message' => 'Se ha solicitado el documento :documento.',
            'type'    => 'info',
        ],
         'Plazo_aviso_documento' => [
            'title'   => 'Aviso de plazo de documento a expirar',
            'message' => 'Se ha solicitado el documento:documento.',
            'type'    => 'info',
        ],
         'Plazo_terminado_documento' => [
            'title'   => 'Alerta de plazo de documento terminado',
            'message' => 'Se ha solicitado el documento :documento.',
            'type'    => 'info',
        ],
    ],
];

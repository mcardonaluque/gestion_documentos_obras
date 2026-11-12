<?php

declare(strict_types=1);

return [
    /*
     * La plantilla Modelo 0 debe usar estas macros:
     * ${ANUALIDAD}, ${SECRETARIO_NOMBRE}, ${ENTIDAD_LOCAL},
     * ${PROGRAMA_DENOMINACION}, ${PROYECTO_ACTUACION},
     * ${REFERENCIA_ACTUACION}.
     *
     * Para la tabla repetible, una única fila debe incluir ${LINEA_ID},
     * ${REFERENCIA}, ${CONTENIDO}, ${DENOMINACION} y ${CSV}.
     */
    'modelo_cero' => [
        'template_path' => 'plantillas/aprobacion/modelo-0.docx',
    ],
];

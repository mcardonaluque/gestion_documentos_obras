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
    'modelo_uno' => [
        /*
         * Macros: ${ANUALIDAD}, ${SECRETARIO_NOMBRE}, ${ENTIDAD_LOCAL},
         * ${ACTUACION}, ${FECHA_DOCUMENTO}, ${COMPETENCIA_PROPIA},
         * ${COMPETENCIA_DELEGADA} y ${COMPETENCIA_DISTINTA}.
         */
        'template_path' => 'plantillas/aprobacion/modelo-1.docx',
    ],
    'modelo_tres' => [
        /*
         * Macros: ${ANUALIDAD}, ${SECRETARIO_NOMBRE}, ${CATEGORIA_SECRETARIO},
         * ${ENTIDAD_LOCAL}, ${IMPORTE_APROBADO_AYUNTAMIENTO},
         * ${PARTIDA_PRESUPUESTARIA} y ${ACTUACION}.
         */
        '3a_template_path' => 'plantillas/aprobacion/modelo-3a.docx',
        '3b_template_path' => 'plantillas/aprobacion/modelo-3b.docx',
    ],
];

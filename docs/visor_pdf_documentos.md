# Visor de documentos PDF

## Objetivo

Permitir la visualización de documentos PDF desde el widget de documentos del expediente, admitiendo dos orígenes:

1. Ruta física del servidor, por ejemplo:
    - X:\docs\expediente_ie\nombrearchivo.pdf
2. Enlace web completo, por ejemplo:
    - https://servidor/documentos/nombrearchivo.pdf

## Flujo funcional

1. El usuario informa la ruta o URL en el campo archivo del documento.
2. Desde la tabla de documentos se ejecuta la acción Ver PDF.
3. El modal carga una vista con un iframe.
4. La ruta interna de Laravel resuelve el origen:
    - si es URL, redirige al recurso externo;
    - si es ruta local, intenta localizar el fichero y servirlo inline.

## Archivos involucrados

- app/Filament/Widgets/DocumentosTable.php
- resources/views/filament/widgets/documento-detalle-modal.blade.php
- app/Http/Controllers/DocumentoPdfController.php
- routes/web.php

## Consideraciones técnicas

- La ruta de previsualización está protegida con autenticación.
- El controlador busca el archivo en varias ubicaciones habituales del proyecto.
- Si no existe el PDF, la aplicación devuelve un error 404 controlado.
- El visor está pensado para documentos PDF, no para otros formatos.

## Campo de datos recomendado

Se prioriza el campo archivo como fuente del PDF. Si no existe valor, se usa csv como compatibilidad con registros antiguos.

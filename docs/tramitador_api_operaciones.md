# Funciones API del Tramitador

## Propósito

Las llamadas al tramitador se describen en base de datos y se administran en
el portal de Administración, grupo de navegación **Tramitador**, opción
**Funciones API**. Esto permite incorporar o modificar funciones sin duplicar
la definición de sus rutas y parámetros en los servicios de negocio.

La llamada se ejecuta con:

```php
$response = app(\App\Services\Tramitador\TramitadorApiOperationService::class)
    ->ejecutar('CODIGO_OPERACION', [
        'dir_emisor3' => '...',
        'id_exp' => '...',
    ]);

$salida = app(\App\Services\Tramitador\TramitadorApiResponseReader::class)
    ->leer('CODIGO_OPERACION', $response);
```

## Tablas

### `tramitador_api_operaciones`

Almacena una función API por fila. `codigo` es el identificador estable usado
por el código PHP. `metodo_http`, `ruta`, `tipo_contenido` y
`timeout_segundos` definen cómo se envía la petición. Solo se ejecutan las
operaciones con `activa = true`.

La ruta puede incluir marcadores entre llaves, como
`/{dir_emisor3}/exp/{id_exp}`. Cada marcador debe tener un parámetro de entrada
con ubicación `path` y el mismo nombre.

### `tramitador_api_parametros`

Define los parámetros de una operación. La combinación de operación,
dirección, ubicación y nombre es única.

| Campo               | Uso                                                                                                 |
| ------------------- | --------------------------------------------------------------------------------------------------- | --------------------------------------------------- |
| `direccion`         | `entrada` para construir la solicitud; `salida` para leer la respuesta.                             |
| `ubicacion`         | Entrada: `path`, `query`, `header` o `body`. Salida: `response`.                                    |
| `nombre`            | Nombre técnico enviado o clave devuelta por el lector.                                              |
| `obligatorio`       | Añade la regla Laravel `required`. Un parámetro `path` siempre debe tener valor para formar la URL. |
| `reglas_validacion` | Reglas Laravel separadas por `                                                                      | `, aplicadas a la entrada antes de llamar a la API. |
| `valor_por_defecto` | Valor usado si no se recibe uno en la llamada.                                                      |
| `ruta_json`         | Solo para salidas; ruta relativa a `data` para extraer el valor.                                    |
| `orden`             | Orden de los parámetros dentro de su dirección.                                                     |
| `activo`            | Excluye temporalmente el parámetro de la ejecución sin borrarlo.                                    |

## Mantenimiento en Administración

1. Crear una función API e indicar código, método HTTP, ruta, contenido y
   tiempo máximo.
2. En la relación **Parámetros**, crear los parámetros de entrada y salida.
3. Para entrada, seleccionar `Ruta` para marcadores URL, `Consulta` para query
   string y `Cuerpo` para datos JSON o de formulario.
4. Para salida, seleccionar dirección `Salida`, ubicación `Respuesta` y
   configurar `ruta_json`, por ejemplo `expediente.id`.

Los parámetros de entrada se ordenan automáticamente como `path`, `query`,
`header` y `body`. Las entradas y salidas tienen secuencias de orden
independientes. No se deben crear parámetros de salida llamados `status`,
`message` o `data`.

## Contrato de respuesta

Toda respuesta debe tener esta estructura:

```json
{
    "status": "OK",
    "message": "Operación realizada",
    "data": {
        "expediente": {
            "id": 123
        }
    }
}
```

`TramitadorApiResponseReader` devuelve siempre `status`, `message` y `data`.
Los parámetros de salida del catálogo se leen dentro de `data`. Para el ejemplo
anterior, un parámetro `id_exp` con `ruta_json = expediente.id` produce
`$salida['data']['id_exp'] = 123`.

## Responsabilidades del código

- `TramitadorApiClient`: añade autenticación y ejecuta la solicitud HTTP. No
  conoce las tablas de configuración.
- `TramitadorApiOperationService`: busca la operación, valida entradas,
  sustituye parámetros de ruta y compone `query` y `body`.
- `TramitadorApiResponseReader`: aplica el contrato fijo de respuesta y extrae
  las salidas configuradas.

## Seguridad

No se debe almacenar ni ejecutar PHP, closures ni expresiones dinámicas en
`reglas_validacion`. Actualmente contiene reglas Laravel de texto; su edición
debe quedar limitada a administradores de confianza.

`TramitadorApiOperationService::ORIGENES_PERMITIDOS` declara las fuentes de
datos autorizadas para validaciones contra catálogos. La integración que lea
una regla declarativa con `origen` debe aceptar solo esas claves, nunca una
conexión, tabla o columna proporcionada directamente por la configuración.

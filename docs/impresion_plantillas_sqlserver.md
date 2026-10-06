# Impresion de plantillas Word con SQL Server

## Objetivo

El sistema genera PDF a partir de plantillas Word heredadas y datos obtenidos de procedimientos almacenados SQL Server. La configuracion documental reside en las tablas heredadas `RegistroModelos` y `RegistroDatos`; el codigo Laravel solo declara que procedimiento puede usarse y como se relaciona cada variable Word con su columna de salida.

## Flujo completo

1. Una accion Filament invoca un servicio de caso de uso, por ejemplo `TechnicalAssistanceDocumentService`.
2. El servicio declara el nombre funcional del documento, el procedimiento permitido, los parametros de ejecucion y el mapa manual de variables.
3. `ImpresionPlantillaSqlServerService` consulta `RegistroModelos` por el nombre del documento.
4. La fila de `RegistroModelos` aporta `CodPro`, `NumApli`, `NumMod` y `DestinoModelo`.
5. Con la clave `(CodPro, NumApli, NumMod)`, `TechnicalAssistanceTemplateRepository` consulta `RegistroDatos.NombreWord`.
6. `EjecutorProcedimientosAlmacenadosSqlServer` ejecuta el procedimiento declarado en `ProcedimientoAlmacenadoObras` con parametros enlazados.
7. La primera fila devuelta por el procedimiento se transforma en valores de plantilla.
8. `WordTemplatePrintService` abre la plantilla, sustituye las variables y usa LibreOffice para producir el PDF descargable.

## Catalogo documental heredado

### RegistroModelos

La busqueda se realiza por `Nombre`. Debe existir una unica configuracion funcional por documento. Los campos usados son:

- `CodPro`, `NumApli`, `NumMod`: clave de relacion con `RegistroDatos`.
- `Nombre`: nombre funcional usado por el servicio Laravel.
- `DestinoModelo`: directorio principal donde se localiza el archivo `.dot`.

La aplicacion busca primero `DestinoModelo/<Nombre>.dot`. Si esa ruta no esta montada, intenta `storage/app/Modelos_Doc/<Nombre>.dot` como fallback de desarrollo.

### RegistroDatos

Cada fila contiene el valor `NombreWord`, por ejemplo `WD_LOCALIDAD`. Solo estas variables se intentan sustituir: que aparezca texto en el archivo Word no basta si no existe tambien en `RegistroDatos` para la clave de la plantilla.

## Procedimientos almacenados permitidos

Los procedimientos se registran en `App\Enums\ProcedimientoAlmacenadoObras`. No se acepta un nombre de procedimiento recibido desde formularios o peticiones HTTP.

Ejemplo:

```php
enum ProcedimientoAlmacenadoObras: string
{
    case DatosListados = 'dbo.PA_R_DatosListados';
    case DatosContrato = 'dbo.PA_R_DatosContrato';
}
```

`EjecutorProcedimientosAlmacenadosSqlServer` admite cualquier numero de parametros. El array asociativo conserva el nombre de cada parametro SQL Server y sus valores se envian como bindings:

```php
$ejecutor->ejecutar(
    ProcedimientoAlmacenadoObras::DatosContrato,
    [
        'ExpedienteId' => 'EXP-2026-001',
        'IncluirHistorico' => false,
    ],
);
```

Produce una llamada equivalente a:

```sql
EXECUTE dbo.PA_R_DatosContrato @ExpedienteId = ?, @IncluirHistorico = ?
```

## Mapa manual de variables

La salida de un procedimiento no siempre usa el mismo nombre que la variable Word. Cada caso de uso declara esas equivalencias mediante un array `variable => columna`.

Ejemplo de ayuda tecnica:

```php
[
    'WD_DEFIPLAN' => 'denominacion_plan',
    'WD_LOCALIDAD' => 'nombre_municipio',
    'WD_IMPORTEAPROBADO' => 'importe_aprobado',
    'WD_FORMAEJE' => 'DEN_CONTRATA',
]
```

Si una variable de `RegistroDatos` no esta incluida en el mapa, el sistema busca una columna de salida con exactamente el mismo nombre. Si tampoco existe, la sustituye por una cadena vacia. Para documentos administrativos es recomendable declarar todas las equivalencias para que el resultado sea auditable.

## Plantillas .dot heredadas

Las plantillas antiguas usan marcadores planos como `WD_LOCALIDAD`; no utilizan la sintaxis `${WD_LOCALIDAD}` de PhpWord. Antes de procesarlas, el servicio convierte una copia temporal de `.dot` a `.docx` con LibreOffice. Durante la sustitucion configura PhpWord para tratar `WD_*` como texto literal. La plantilla original nunca se modifica.

Las plantillas OOXML modernas (`.docx` o `.dotx`) siguen usando macros `${VARIABLE}`.

## Crear un nuevo documento

1. Registrar el modelo en `RegistroModelos` con nombre y `DestinoModelo`.
2. Registrar sus variables `NombreWord` en `RegistroDatos` con la misma clave `(CodPro, NumApli, NumMod)`.
3. Crear o registrar el procedimiento en `ProcedimientoAlmacenadoObras`.
4. Crear un servicio de caso de uso que invoque `ImpresionPlantillaSqlServerService::descargarPdf()`.
5. Declarar parametros y el mapa manual de variables en ese servicio.
6. Exponerlo desde una accion Filament y probarlo con una obra cuyo procedimiento devuelva al menos una fila.

## Diagnostico

- "No existe una plantilla registrada": no hay fila con el `Nombre` solicitado en `RegistroModelos`.
- "La plantilla no tiene variables": no hay `NombreWord` para la clave de la plantilla en `RegistroDatos`.
- "El procedimiento no ha devuelto datos": revisar directamente el procedimiento y las relaciones requeridas por sus joins.
- "No se ha encontrado el archivo": comprobar `DestinoModelo`, el nombre exacto y que la unidad de red sea accesible desde PHP/Apache.
- Variables sin sustituir: comprobar que figuran en `RegistroDatos` y que el mapa contiene la columna de salida correcta. En `.dot`, los marcadores deben ser textos continuos, sin partirse entre varios fragmentos de formato Word.

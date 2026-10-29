# Sistema de control de fechas, hitos y avisos

Documento de referencia del sistema que valida fechas de la gestión de obras, mantiene una vista unificada de hitos por expediente y genera avisos/notificaciones, incluyendo los plazos de la normativa PPAC.

Documentos relacionados: [catalogo_hitos_fechas_obras.md](./catalogo_hitos_fechas_obras.md) (listado completo de hitos) y [secuencia_comprobacion_reglas_fechas.md](./secuencia_comprobacion_reglas_fechas.md) (servicios, llamadas y secuencia detallada de la comprobación).

---

## 1. Visión general

El sistema tiene **tres mecanismos independientes** que cooperan:

| # | Mecanismo | Cuándo actúa | Qué hace |
|---|-----------|--------------|----------|
| A | **Reglas de fechas** (motor por eventos) | Al guardar/editar una fecha | Compara fechas (entre fases, con hoy, con la normativa), bloquea o avisa y notifica |
| B | **Hitos materializados** | Bajo demanda (comando) | Reúne en `expediente_fecha_hitos` las fechas clave de cada expediente para consultarlas en un solo sitio |
| C | **Avisos de normativa PPAC** | Cada noche a las 03:00 | Detecta plazos de la normativa próximos o vencidos, notifica y registra en `Avisos` |

Todo se parametriza en la tabla **`date_validation_rules`** (pantalla *Validación de fechas → Reglas de fechas*). No hay reglas en código.

```
            ┌────────────────────── date_validation_rules ──────────────────────┐
            │  (una fila = una comparación de fechas)                            │
            └───────────┬───────────────────────────────────────────┬───────────┘
        al guardar      │                                           │   cada noche 03:00
   ┌────────────────────▼──────────────┐             ┌──────────────▼─────────────────┐
   │ A. Motor por eventos              │             │ C. normativa-ppac:avisos        │
   │ Observer / trait → Evaluator      │             │ NormativaPpacAvisosService      │
   │  → Logger → Notifier              │             │  (reglas con tabla2=NormativaPPAC)
   └──────┬──────────────────┬─────────┘             └───────┬──────────────┬──────────┘
          │                  │                               │              │
 date_rule_executions   CustomNotification (campanita)   Avisos (registro)  CustomNotification
```

---

## 2. Piezas y su función

### 2.1 Tabla `date_validation_rules` — las reglas
Modelo: `App\Models\DateValidationRule`. Pantalla: `DateValidationRuleResource`.

| Campo | Función |
|-------|---------|
| `nombre` | Identificador de la regla (para las de normativa también es el `codigo_hito` que se guarda en `Avisos`) |
| `descripcion` | Explicación funcional |
| `tabla1` / `campo1` | **Fecha principal** a controlar (la fecha real del expediente). `*` = cualquier tabla/campo fecha modificado |
| `tabla2` / `campo2` | **Fecha de comparación**: otra tabla/campo, un valor de sistema (`today`, `now`) o la **normativa PPAC** (`tabla2 = NormativaPPAC`, `campo2 = fecha_limite_*`) |
| `condicion` | Tipo de comparación (ver 2.2) |
| `plazo_dias` | Días de plazo (condiciones de plazo/días entre fechas) |
| `aviso_dias` | Días de antelación para el preaviso |
| `tipo` | `warning` (advertencia), `error` (bloquea el guardado) o `notification` (notifica al equipo) |
| `accion` | `none`, `notify`, `generate_document`, `request_document` |
| `operacion` | `create`, `update` o `both`: cuándo se evalúa |
| `fase` / `estado` | Filtran por fase o estado del registro (opcional) |
| `mensaje`, `mensaje_preventivo`, `mensaje_cumplida`, `mensaje_incumplida` | Textos según la etapa del resultado |
| `dispara_si_cumple` | Si es `1`, la regla se *dispara* cuando la condición **se cumple**; si es `0`, cuando **no se cumple** |
| `connection_name` | Conexión de BD (por defecto `Obras`) |
| `activa` | Activa/desactiva la regla |

### 2.2 Condiciones (`DateRuleCondition`)
`is_date`, `after`, `after_or_equal`, `before`, `before_or_equal`, `after_today`, `after_or_equal_today`, `before_today`, `before_or_equal_today`, `max_days_between`, `min_days_between`, `deadline_not_expired`, `days_to_deadline_lte`.
Las que comparan dos fechas exigen `tabla2/campo2`; las de plazo usan `plazo_dias` y `aviso_dias`.

### 2.3 Tipo y destinatarios (`DateRuleType`)

| Tipo | Bloquea | Destinatario de la notificación |
|------|---------|---------------------------------|
| `warning` | No | El usuario que guarda |
| `error` | **Sí** (impide guardar) | El usuario que guarda |
| `notification` | No | Todos los usuarios del equipo (ayuntamiento) del expediente |

### 2.4 Motor por eventos (mecanismo A)

| Componente | Función |
|------------|---------|
| `config/date_rules.php → observed_models` | Lista de modelos vigilados (`Expediente`, `DocumentoExpediente`, `DatosDeInicioDeObras`, `DatosEjecucionObras`, `ObraCedida`, `Justificacion_Obra`) |
| `AppServiceProvider` | Registra `DateRuleObserver` en cada modelo observado |
| `DateRuleObserver` | En `saving` (antes de guardar) evalúa y **lanza `ValidationException`** si hay reglas tipo `error`; en `saved` registra y notifica. Solo actúa si cambió algún campo cuyo nombre contiene `fecha` o termina en `_at` |
| `NotifiesAndValidatesDates` (trait) | Para páginas Filament: validación al salir del campo (`validateDateFieldOnBlur`), `persistWithDateControls` y `applyDateControlsForManualChanges` para guardados manuales |
| `DateRuleEvaluator` | Núcleo: busca reglas activas para la tabla/campo/operación, resuelve ambos valores y evalúa la condición. Devuelve `DateRuleEvaluationResult` (regla, pasó, disparada, etapa, mensaje, valores) |
| `DateRuleExecutionLogger` | Guarda cada evaluación en `date_rule_executions` (auditoría) |
| `DateRuleNotifier` | Crea notificaciones (`CustomNotification`, tipo `date-rule`) para los destinatarios; asíncrono por cola si `async_notifications` es `true` |
| `DispatchDateRuleNotificationsJob` | Job que inserta las notificaciones en cola (`notifications_queue`) |

**Etapas del mensaje**: `preaviso` (condición `days_to_deadline_lte` cumplida), `cumplida`, `incumplida`.

### 2.5 Hitos materializados (mecanismo B)

| Componente | Función |
|------------|---------|
| `config/date_rules.php → hitos_catalogo` | Define cada hito: `codigo_hito`, descripción, tabla/campo origen, fase, obligatorio, repetible |
| Tabla `fecha_hitos_catalogo` (`FechaHitoCatalogo`) | Copia persistida del catálogo |
| Tabla `expediente_fecha_hitos` (`ExpedienteFechaHito`) | **Una fila por expediente y hito** con su fecha, tomada de la tabla origen |
| `SincronizadorFechasHitosService` | Sincroniza catálogo y proyección |
| Comando `date-hitos:sincronizar {--expediente=} {--limpiar}` | Lanza la sincronización (todo o un expediente) |
| `ConsultaExpedienteFechaHitosService` y página *Hitos materializados* (panel Obras) | Consulta/filtrado por expediente, hito y tabla |

Los hitos materializados **son consulta/reporting**; no disparan avisos por sí solos.

### 2.6 Normativa PPAC (mecanismo C)

| Componente | Función |
|------------|---------|
| Tabla `NormativaPPAC` (`NormativaPpac`) | Fechas y plazos límite por **año y plan** (`ao_plan` + `Codigo_Plan`; `Codigo_Plan` nulo = normativa general del año) |
| `NormativaPpac::resolveFor($año, $plan)` | Devuelve la normativa del plan si existe; si no, la general del año |
| Acción *Asignar a planes* | Copia las mismas fechas a varios planes (los ya asignados aparecen marcados y deshabilitados) |
| Reglas `NORMATIVA_*` en `date_validation_rules` | Comparan una fecha real del expediente (`tabla1/campo1`) con un límite de la normativa (`tabla2 = NormativaPPAC`, `campo2`) |
| `NormativaPpacHitosService` | Resuelve el límite de un expediente: elige la normativa, **elige D/A**, y aplica prórrogas |
| `NormativaPpacAvisosService` | Recorre expedientes, calcula etapa, registra en `Avisos` y notifica |
| Comando `normativa-ppac:avisos` | Ejecuta el proceso; programado a diario a las 03:00 |
| Tabla `Avisos` (`Aviso`) | Registro de cada aviso emitido |
| Tabla `TiposAvisos` (`TiposAviso`) | Catálogo de tipos: **10** preaviso normativa PPAC, **11** normativa PPAC vencida |

**Reglas de normativa incluidas**

| Regla | Fecha real (`tabla1.campo1`) | Límite (`NormativaPPAC.campo2`) |
|-------|------------------------------|---------------------------------|
| `NORMATIVA_PROYECTO` | `Proyectos.fecha_entrega_proyecto` | `fecha_limite_presentacion_proyectoD` |
| `NORMATIVA_DOCUMENTACION` | `Proyectos.fecha_aprobacion_ayto` | `fecha_limite_presentacion_documentacionD` |
| `NORMATIVA_CONTRATACION` | `ObrasCedidas.FechaContrato` | `fecha_limite_contratacion` |
| `NORMATIVA_CESION` | `ObrasCedidas.FechaCesion` | `fecha_limite_cesion_obra` |
| `NORMATIVA_EJECUCION` | `ActasRecepcionObra.Fecha_Acta_RecProv` | `fecha_limite_terminacion_plan` |

Todas con `condicion = before_or_equal`, `tipo = warning`, `dispara_si_cumple = 0` y `aviso_dias = 30`. Se pueden editar, desactivar o añadir otras (p. ej. justificación) desde la pantalla de reglas.

**Reglas especiales al resolver el límite**
- **D/A**: si `campo2` acaba en `D` o `A` se usa la variante **D** (Diputación) cuando `Proyecto.SubvencionEconRedaccion` es verdadero y la **A** (Ayuntamiento) en caso contrario. Basta definir la regla con la variante D.
- **Prórrogas**: para proyecto, documentación, ejecución y justificación, si `PlazosObraActivos` (fase activa) tiene una fecha fin **posterior** a la de la normativa, prevalece esa.

---

## 3. Secuencias de funcionamiento

### 3.1 Un usuario guarda una fecha (mecanismo A)
1. El usuario edita un formulario. Con el trait `NotifiesAndValidatesDates`, al salir de un campo fecha se ejecuta `validateDateFieldOnBlur` (aviso inmediato; un `error` marca el campo).
2. Al guardar, el modelo dispara `saving` → `DateRuleObserver`. Si no cambió ninguna fecha, termina.
3. `DateRuleEvaluator` carga las reglas activas con `operacion` aplicable y `tabla1` = tabla del modelo, y para cada una:
   - obtiene `campo1` (valor del modelo) y `campo2` (otra tabla del mismo expediente, `today/now` o normativa);
   - evalúa la condición y decide si la regla se **dispara** (`dispara_si_cumple`).
4. Si alguna regla `error` se dispara → `ValidationException` y **no se guarda**.
5. Tras guardar (`saved`), se vuelve a evaluar, `DateRuleExecutionLogger` registra en `date_rule_executions` y `DateRuleNotifier` encola las notificaciones.
6. `DispatchDateRuleNotificationsJob` crea las filas `CustomNotification`, que se ven en la **campanita**.

### 3.2 Avisos nocturnos de normativa (mecanismo C)
Se activa por el scheduler (`routes/console.php`: `normativa-ppac:avisos` a las 03:00, sin solapamiento). Requiere que el servidor ejecute `php artisan schedule:run` cada minuto (cron/Task Scheduler).

1. Se seleccionan expedientes de los **3 últimos años** (`ao_ejecucion >= año actual − 2`; configurable en `normativa_avisos.anios_revision`).
2. Para cada expediente se resuelve su normativa (`resolveFor`). Sin normativa → se ignora.
3. Para cada regla activa con `tabla2 = NormativaPPAC` se calcula el **límite** (D/A + prórrogas). Sin fecha en normativa → se ignora.
4. Se lee la **fecha real** (`tabla1.campo1` del expediente):
   - **Existe** → el hito está resuelto: no se avisa y se **cierran** los avisos abiertos de esa regla (`FecSolucion`).
   - **No existe** → se calcula la etapa:
     - **preaviso**: faltan `aviso_dias` o menos (por defecto 30);
     - **vencido**: pasó el límite, hasta `max_dias_vencido` días (30) para no avisar de expedientes antiguos;
     - nada: fuera de ambas ventanas.
5. Si toca avisar y **no existe ya** un aviso para ese expediente + tipo + regla + fecha límite, en una transacción:
   - se inserta una fila en `Avisos` (`Usuario = 'SISTEMA'`, `TipoAviso` 10 u 11, `codigo_hito` = nombre de regla, `fecha_hito` = límite, `expediente_id`, `team_id`, plan, referencia…);
   - se notifican (campanita, `NotificationService::sendByTargets`) los **tramitadores asignados** al expediente y los **usuarios del equipo/ayuntamiento** (`Expediente.team_id`), ambos en sus paneles.
6. Un cambio de fecha límite (p. ej. por prórroga) genera un aviso nuevo; el mismo aviso nunca se repite.

### 3.3 Hitos materializados (mecanismo B)
1. Ejecutar `php artisan date-hitos:sincronizar` (opcional `--expediente=ID`, `--limpiar`).
2. Se actualiza `fecha_hitos_catalogo` desde `hitos_catalogo` (desactivando códigos retirados).
3. Por cada tabla origen se leen las fechas y se hace `updateOrCreate` en `expediente_fecha_hitos`.
4. Se consulta en *Hitos materializados* (panel Obras).

---

## 4. Cómo crear o modificar reglas

**Comparar dos fases del mismo expediente** (ej. el documento debe incorporarse como máximo 30 días tras el replanteo):
`tabla1 = documentacionexpedientes`, `campo1 = fechaincorporacion`, `tabla2 = ActasDeReplanteo`, `campo2 = Fecha_Inicio_Acta_Replanteo`, `condicion = max_days_between`, `plazo_dias = 30`.

**Comparar con hoy**: `tabla2 = today` (o `campo2 = today`) con `before_today`, `after_today`, etc.

**Comparar con la normativa**: `tabla2 = NormativaPPAC`, `campo2 = fecha_limite_*`, condición `before_or_equal` normalmente. Genera validación al guardar y avisos nocturnos.

**Bloquear el guardado**: `tipo = error`. **Solo avisar al usuario**: `warning`. **Avisar al equipo**: `notification`.

---

## 5. Puesta en marcha

1. Migraciones (ejecutar **solo estas** con `--path`, primero con `--pretend`):
   - `database/migrations/2026_10_28_100000_add_hito_columns_to_avisos_and_normativa_tipos.php` (columnas `codigo_hito`, `fecha_hito` en `Avisos` y tipos 10/11)
   - `database/migrations/2026_10_28_110000_add_normativa_ppac_rules_to_date_validation_rules.php` (las 5 reglas de normativa)
   - `database/migrations/2026_10_27_100000_make_normativa_ppac_unique_per_year_and_plan.php` (unicidad por año y plan)
2. Probar: `php artisan normativa-ppac:avisos --simular` (no escribe nada) y después sin `--simular`; con `--expediente=ID` para un caso concreto.
3. Cron del servidor: `* * * * * php artisan schedule:run`.
4. Cola: si `async_notifications = true`, debe haber un worker (`php artisan queue:work`) para las notificaciones del motor por eventos. Los avisos nocturnos de normativa se crean directamente.

## 6. Configuración (`config/date_rules.php`)

| Clave | Función |
|-------|---------|
| `async_notifications`, `notifications_queue` | Notificaciones del motor por eventos por cola |
| `observed_models` | Modelos cuyos guardados se validan |
| `hitos_catalogo` | Catálogo de hitos materializados |
| `normativa_avisos.anios_revision` | Años revisados por el proceso nocturno (3) |
| `normativa_avisos.aviso_dias` | Preaviso por defecto si la regla no define `aviso_dias` (30) |
| `normativa_avisos.max_dias_vencido` | Días tras el vencimiento durante los que se sigue avisando (30) |
| `normativa_avisos.tipo_preaviso` / `tipo_vencido` | Códigos en `TiposAvisos` (10 / 11) |

## 7. Limitaciones conocidas

- La validación al guardar solo se ejecuta en los modelos de `observed_models`; `Proyectos` y `ActasRecepcionObra` no están incluidos, por lo que sus reglas de normativa solo generan avisos nocturnos (no validan al guardar) hasta que se añadan.
- Los campos reales de las reglas `NORMATIVA_*` (p. ej. `Proyectos.fecha_aprobacion_ayto`) son una propuesta inicial y deben validarse con negocio.
- No hay regla de justificación por falta de un campo fecha claro.
- Los hitos materializados no disparan avisos; solo sirven de consulta.
- La tabla `alertas` (CRUD antiguo) está en desuso y no la usa el motor.
- Un expediente sin normativa aplicable (ni de su plan ni general del año) no genera avisos.

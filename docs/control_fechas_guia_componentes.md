# Control de fechas: guía por componentes y secuencia de trabajo

Este documento explica **cada pieza** del control de fechas: Resources, páginas, traits, DTO, enums, servicios, observer, job, modelos, comandos y configuración. Para cada una indica qué hace, quién la llama, a quién llama y **en qué paso de la secuencia** interviene.

Documentos relacionados:
- [sistema_control_fechas_hitos_avisos.md](./sistema_control_fechas_hitos_avisos.md): visión funcional (reglas, normativa PPAC, hitos y puesta en marcha).
- [secuencia_comprobacion_reglas_fechas.md](./secuencia_comprobacion_reglas_fechas.md): detalle interno del evaluador y de la normativa, y cómo depurar.
- [catalogo_hitos_fechas_obras.md](./catalogo_hitos_fechas_obras.md): listado de hitos.

---

## 1. El proceso completo en una página

Cada vez que se intenta **grabar una fecha** en una tabla vigilada, se aplican las reglas de `date_validation_rules` de **esa tabla** más las **globales** (`tabla1 = *`). La comprobación se hace en dos momentos: al salir del campo (en pantalla) y al guardar (en el modelo).

```
 ┌─────────────── PANTALLA (Filament) ───────────────┐      ┌──────────────── MODELO (Eloquent) ────────────────┐
 │                                                   │      │                                                   │
 │ P1  Usuario sale de un campo fecha                │      │ P4  $modelo->save()                               │
 │      DateFieldRules::apply()  → afterStateUpdated │      │      evento saving → DateRuleObserver::saving      │
 │ P2  NotifiesAndValidatesDates                     │      │ P5   DateFieldDetector: ¿qué fechas cambiaron?     │
 │      ::validateDateFieldOnBlur($campo)            │      │ P6   DateRuleEvaluator::evaluate  (1ª evaluación)  │
 │        borrador del modelo con el formulario      │      │ P7   ¿regla tipo error disparada?                  │
 │        DateRuleEvaluator::evaluate (solo campo)   │      │        sí → ValidationException → NO se guarda     │
 │ P3     error → campo en rojo                      │      │      INSERT / UPDATE                               │
 │        aviso → notificación en pantalla           │      │ P8   evento saved → DateRuleObserver::saved        │
 │        (no escribe nada en BD)                    │      │        DateRuleEvaluator::evaluate (2ª evaluación) │
 │                                                   │      │ P9     DateRuleExecutionLogger → date_rule_executions
 │  Usuario pulsa Guardar ───────────────────────────┼─────▶│ P10    DateRuleNotifier → Job → CustomNotification │
 └───────────────────────────────────────────────────┘      └───────────────────────────────────────────────────┘

 Proceso aparte, cada noche 03:00:
 P11  normativa-ppac:avisos → NormativaPpacAvisosService → NormativaPpacHitosService → Avisos + notificaciones
```

| Paso | Qué ocurre | Componente principal |
|------|------------|----------------------|
| P1 | El campo fecha avisa a la página al perder el foco | `DateFieldRules` |
| P2 | Se construye un modelo borrador con el formulario y se evalúan las reglas del campo | `NotifiesAndValidatesDates` + `DateRuleEvaluator` |
| P3 | Se marca el error en el campo o se muestra un aviso | `NotifiesAndValidatesDates` |
| P4 | El modelo se guarda (Filament, RelationManager, servicio o código) | Eloquent |
| P5 | Se detectan los campos fecha modificados | `DateFieldDetector` |
| P6 | Se evalúan las reglas aplicables | `DateRuleEvaluator` → `DateRuleEvaluationResult` |
| P7 | Si alguna regla `error` se dispara, se cancela el guardado | `DateRuleObserver` |
| P8 | Ya guardado, se vuelve a evaluar con el estado final | `DateRuleObserver` |
| P9 | Se registran las reglas disparadas | `DateRuleExecutionLogger` |
| P10 | Se notifica a usuario o equipo | `DateRuleNotifier` + `DispatchDateRuleNotificationsJob` |
| P11 | Avisos nocturnos de plazos de normativa | `NormativaPpacAvisosService` |

P1–P3 son opcionales: solo existen en los formularios preparados (ver sección 9). **P4–P10 ocurren siempre** que se guarda un modelo de `observed_models`, venga de donde venga el guardado, salvo que el código use `withoutEvents()` o `saveQuietly()`.

---

## 2. Configuración y arranque

### `config/date_rules.php`
**Papel:** decide qué modelos se vigilan y cómo se entregan las notificaciones. **Paso:** previo a todo.

| Clave | Uso |
|-------|-----|
| `observed_models` | Modelos a los que se engancha el observer. Hoy: `Expediente`, `DocumentoExpediente`, `DatosDeInicioDeObras`, `DatosEjecucionObras`, `ObraCedida`, `Justificacion_Obra`, `Proyecto`, `actadereplanteo`, `actaderecepcion` |
| `async_notifications` | `true`: notificaciones por cola (P10 con job). `false`: se crean al momento |
| `notifications_queue` | Cola del job (`default`) |
| `hitos_catalogo` | Catálogo de hitos materializados (proceso aparte, sección 11) |
| `normativa_avisos` | Parámetros del proceso nocturno (P11) |

### `app/Providers/AppServiceProvider.php`
**Papel:** en `boot()` recorre `observed_models` y ejecuta `Modelo::observe(DateRuleObserver::class)`. **Paso:** arranque de la aplicación. Sin este registro no existen P4–P10.

> Si se añade un modelo a `observed_models` y la configuración está cacheada, hay que ejecutar `php artisan config:clear`.

---

## 3. Datos: tablas y modelos

### `DateValidationRule` → tabla `date_validation_rules`
**Papel:** cada fila es una regla, es decir, una comparación de fechas. **Paso:** se lee en P2, P6, P8 y P11.
- Conexión `Obras`. Casts: `condicion`, `tipo`, `accion` y `operacion` a sus enums; `activa` y `dispara_si_cumple` a booleanos.
- `scopeActive()` filtra `activa = 1`.
- Relaciones: `faseRelacionada()`, `estadoRelacionado()` y `executions()`.
- Se mantiene desde **Admin → Validación de fechas → Reglas de fechas**.

Campos clave: `tabla1/campo1` es la fecha que se controla. `tabla2/campo2` es la fecha con la que se compara: otra tabla, `today`/`now` o `NormativaPPAC`. Los demás son `condicion`, `plazo_dias`, `aviso_dias`, `tipo`, `accion`, `operacion`, `fase`, `estado`, `dispara_si_cumple`, los mensajes y `activa`. La explicación de cada uno está en [sistema_control_fechas_hitos_avisos.md §2.1](./sistema_control_fechas_hitos_avisos.md).

### `DateRuleExecution` → tabla `date_rule_executions`
**Papel:** auditoría de las reglas disparadas al guardar. **Paso:** se escribe en P9.
- Guarda regla, modelo e id, expediente, tabla, campo, valores comparados, severidad, acción, mensaje y `fingerprint`.
- Se consulta en **Admin → Validación de fechas → Ejecuciones de reglas**.

### Otras tablas que intervienen
| Tabla / modelo | Paso | Para qué |
|----------------|------|----------|
| `custom_notifications` (`CustomNotification`) | P10, P11 | Notificaciones de la campanita (`type = date-rule` en P10) |
| `NormativaPPAC` (`NormativaPpac`) | P6, P8, P11 | Fechas límite por año y plan |
| `PlazosObraActivos` | P6, P8, P11 | Prórrogas que amplían el límite de la normativa |
| `Avisos`, `TiposAvisos` | P11 | Registro de avisos nocturnos (tipos 10 y 11) |
| Tablas de negocio (`ActasDeReplanteo`, `Proyectos`…) | P6, P8 | Origen de las fechas de comparación de otra tabla |

### Migraciones
- `2026_03_17_120000_create_date_validation_rules_table.php`
- `2026_03_17_120100_create_date_rule_executions_table.php`
- `2026_06_18_130200_add_mensajes_to_date_validation_rules_table.php` (mensajes por etapa)
- `2026_10_28_110000_add_normativa_ppac_rules_to_date_validation_rules.php` (las 5 reglas `NORMATIVA_*`)
- `2026_06_18_130000_create_fecha_hitos_catalogo_table.php` (hitos, proceso aparte)

---

## 4. Enums (`app/Enums`)

Dan tipo a las columnas de la regla. Todos son `string`, con `options()` para los `Select` del formulario de reglas.

| Enum | Valores | Métodos | Dónde decide |
|------|---------|---------|--------------|
| `DateRuleCondition` | `is_date`, `after`, `after_or_equal`, `before`, `before_or_equal`, `after_today`, `after_or_equal_today`, `before_today`, `before_or_equal_today`, `max_days_between`, `min_days_between`, `deadline_not_expired`, `days_to_deadline_lte` | `requiresSecondDate()`: si es `true` y falta una de las dos fechas, la regla se omite. `requiresDaysValue()` | P6 (`evaluateCondition`) |
| `DateRuleType` | `warning`, `error`, `notification` | `isBlocking()`: solo `error` | P3, P7 (bloqueo) y P10 (destinatarios) |
| `DateRuleAction` | `none`, `notify`, `generate_document`, `request_document` | — | P10: `notify` fuerza la notificación. Las acciones de documento **no están implementadas** |
| `DateRuleOperation` | `create`, `update`, `both` | `fromModelState(bool $exists)`: registro nuevo → `create`, existente → `update` | P2, P6 (filtro de reglas por operación) |

---

## 5. DTO

### `App\DTOs\DateRuleEvaluationResult`
**Papel:** resultado inmutable (`final`, propiedades `readonly`) de evaluar **una regla sobre un campo**. **Paso:** lo crea P6/P8 y lo consumen P3, P7, P9 y P10.

| Propiedad | Significado |
|-----------|-------------|
| `rule` | La `DateValidationRule` evaluada |
| `field` | Campo del modelo evaluado (`campo1`, o cada campo cambiado si la regla usa `*`) |
| `passed` | La condición se cumple |
| `triggered` | La regla **salta**. Vale `passed` si `dispara_si_cumple = 1`, y `!passed` si vale `0` |
| `messageStage` | `preaviso`, `cumplida` o `incumplida` |
| `type` | `DateRuleType` de la regla |
| `message` | Texto elegido según la etapa |
| `leftValue` / `rightValue` | Valores comparados, en crudo (se guardan en la auditoría) |

Regla práctica: **solo importan los resultados con `triggered = true`**. El bloqueo, el log y las notificaciones descartan el resto.

---

## 6. Servicios (`app/Services/DateRules`)

Todos son clases `final`. Se resuelven por el contenedor, inyectados en el observer o con `app(...)` en el trait.

### 6.1 `DateFieldDetector` — **P5**
**Pregunta que responde:** ¿qué atributos modificados del modelo son fechas?

| Método | Qué hace |
|--------|----------|
| `dirtyDateFields(Model): array` | Recorre `getDirty()` y devuelve los atributos que son fecha |
| `isDateField(Model, string): bool` | Aplica la regla de detección |

Regla de detección, en orden:
1. `created_at` y `updated_at` (si el modelo usa timestamps) **nunca** son fecha de negocio.
2. Si el atributo tiene **cast de fecha** (`date`, `datetime`, `immutable_*`, `date:formato`, `datetime:formato`, `timestamp`), es fecha, se llame como se llame (`FecProrroga`, `FechaCesion`…).
3. Si no tiene cast, es fecha si su nombre **contiene "fecha"** (respaldo para columnas antiguas sin cast).

**Lo llaman:** `DateRuleObserver::saving()` y `NotifiesAndValidatesDates::persistWithDateControls()`.
**Por qué importa:** si una columna no tiene cast de fecha y su nombre no contiene "fecha", **sus reglas nunca se evalúan al guardar**. Para que se controle, hay que añadirle un cast de fecha en el modelo.
**Tests:** `tests/Unit/Services/DateRules/DateFieldDetectorTest.php`.

### 6.2 `DateRuleEvaluator` — **P2, P6, P8 (núcleo)**
**Pregunta que responde:** dadas estas fechas modificadas, ¿qué reglas se cumplen y cuáles saltan?

| Método | Entrada | Salida |
|--------|---------|--------|
| `evaluate(Model $modelo, array $camposCambiados, DateRuleOperation $op)` | Modelo (guardado o borrador), campos fecha cambiados, operación | `Collection<DateRuleEvaluationResult>` |
| `blockingErrorsByField(Collection $resultados)` | Resultados | `['campo' => ['mensaje', …]]`, solo con `triggered` y tipo `error` |

Secuencia interna de `evaluate()`:
1. Normaliza la tabla del modelo y los campos: minúsculas y sin prefijo `dbo.`.
2. Carga las reglas `active()` cuya `operacion` sea `both` o la actual.
3. Para cada regla:
   1. **¿Tabla?** `tabla1` igual a la tabla del modelo, o `*`. Así se aplican "las reglas de esa tabla y las generales".
   2. **¿Campo?** `campo1` debe estar entre los cambiados; con `*` valen todos los cambiados.
   3. **¿Fase/estado?** Si la regla los define, el modelo debe coincidir (`fase`/`cod_fase`, `estado`/`cod_estado`/`codigo_estado_obra`).
   4. **Valor izquierdo:** el atributo del modelo. Si `tabla1` es otra tabla, se busca en ella por `expediente_id`.
   5. **Valor derecho**, por este orden:
      - `today`/`now` (y variantes) → fecha del sistema;
      - `NormativaPPAC` → `NormativaPpacHitosService::fechaLimite()`;
      - misma tabla → atributo del modelo, **incluidos los valores aún no guardados**;
      - otra tabla → consulta por `expediente_id` en esa tabla (`->first()`, sin orden).
   6. Pasa ambos valores a `Carbon`. Si la condición necesita dos fechas y falta una, **se omite** sin error.
   7. Calcula `passed`, `triggered`, la etapa y el mensaje, y crea el DTO.

**Lo llaman:** `DateRuleObserver` (P6 y P8) y el trait `NotifiesAndValidatesDates` (P2, `persistWithDateControls` y `applyDateControlsForManualChanges`).
**Llama a:** `DateValidationRule`, `Expediente`, `NormativaPpacHitosService` y `DB::connection(...)->table(...)` para las otras tablas.
**No escribe nada:** es una función de cálculo pura sobre la BD.

### 6.3 `DateRuleExecutionLogger` — **P9**
| Método | Qué hace |
|--------|----------|
| `log(Model, Collection $resultados)` | Por cada resultado con `triggered`, calcula una huella `sha1(regla, modelo, id, campo, etapa, mensaje, fecha de hoy)` y hace `DateRuleExecution::firstOrCreate` |

La huella evita duplicar **el mismo aviso el mismo día** para el mismo registro.
**Lo llaman:** `DateRuleObserver::saved()` y el trait (`persistWithDateControls` y `applyDateControlsForManualChanges`). No se llama en P2, porque la validación en pantalla no deja rastro.

### 6.4 `DateRuleNotifier` — **P10**
| Método | Qué hace |
|--------|----------|
| `notify(Model, Collection $resultados, ?int $actorId)` | Filtra los disparados con `accion = notify` o `tipo = notification`, resuelve destinatarios y título, y entrega |

Destinatarios por tipo:

| Tipo | Destinatario |
|------|--------------|
| `warning` | El usuario que guarda |
| `error` | El usuario que guarda. En la práctica no llega, porque el `error` bloquea en P7 antes de `saved` |
| `notification` | Todos los usuarios del equipo (`team_id` del modelo o del `Expediente`) |

Entrega: con `async_notifications = true`, `DispatchDateRuleNotificationsJob` en la cola `notifications_queue`; si no, crea las `CustomNotification` al momento.

> Un `warning` **sin** `accion = notify` no genera notificación en la campanita. Solo queda en el log y, si el campo usa validación en vivo, aparece como aviso en pantalla en P3.

### 6.5 `NormativaPpacAvisosService` — **P11**
| Método | Qué hace |
|--------|----------|
| `ejecutar(bool $simular, ?string $expedienteId, ?Carbon $hoy)` | Recorre los expedientes de los últimos `anios_revision` años, calcula si cada hito de normativa está en preaviso o vencido, registra en `Avisos` y notifica. Cierra los avisos de los hitos ya resueltos |

No usa `DateRuleEvaluator`, el log ni `DateRuleNotifier`. Detalle en [secuencia_comprobacion_reglas_fechas.md §7](./secuencia_comprobacion_reglas_fechas.md).

### 6.6 `App\Services\DateHitos\NormativaPpacHitosService` — **P6, P8, P11**
| Método | Qué hace |
|--------|----------|
| `esNormativa(?string $tabla)` | Indica si `tabla2` apunta a la normativa |
| `normativaDe(Expediente)` | Normativa del año y plan del expediente, o la general del año |
| `fechaLimite(Expediente, string $campo, ?NormativaPpac)` | Fecha límite aplicando la variante D/A y las prórrogas de `PlazosObraActivos` |
| `reglas()` | Reglas activas con `tabla2 = NormativaPPAC` |
| `hitosDe(Expediente)` | Por cada regla: límite, fecha real y si está resuelto |
| `etapa($fecha, $hoy, $avisoDias, $maxDiasVencido)` | `preaviso`, `vencido` o `null` |

El evaluador lo usa al guardar, con `fechaLimite`. El proceso nocturno lo usa con `hitosDe` y `etapa`.

---

## 7. Observer

### `App\Observers\DateRuleObserver` — **P4 a P10**
Es la **barrera obligatoria**: actúa con cualquier guardado del modelo, sea un formulario, un RelationManager, una acción, un servicio o tinker.

```
saving(Model)                                         saved(Model)
 1. DateFieldDetector::dirtyDateFields()   P5          1. recupera los campos del paso 2 y los borra
    └─ vacío → return (no hace nada)                   2. evaluate(..., UPDATE)            P8
 2. guarda campos en memoria (spl_object_id)           3. DateRuleExecutionLogger::log()   P9
 3. evaluate(modelo, campos, create|update)  P6        4. DateRuleNotifier::notify()       P10
 4. blockingErrorsByField()
    └─ errores → throw ValidationException   P7  (no se guarda)
```

¿Por qué se evalúa dos veces? En `saving` se puede **bloquear**, pero los datos aún no están en la BD. En `saved` el estado ya es definitivo para **registrar y notificar**.

**Dependencias inyectadas:** `DateRuleEvaluator`, `DateRuleNotifier`, `DateRuleExecutionLogger` y `DateFieldDetector`.

**Cómo se ve el error:** en un formulario de Filament, la `ValidationException` aparece como error del formulario o en una notificación. Si la clave del error coincide con el nombre del campo en `data.*`, se marca el campo.

**Cuándo NO actúa:**
- con `Modelo::withoutEvents()` o `saveQuietly()`. Ocurre a propósito en `AprobacionExpedienteDocumentService` y `SubsanacionResolucionService`, y en `persistWithDateControls`, que ya hace sus propios controles;
- con `DB::table(...)->update()` o SQL directo;
- en modelos que no están en `observed_models`.

---

## 8. Job

### `App\Jobs\DispatchDateRuleNotificationsJob` — **P10 (diferido)**
- `ShouldQueue`. Recibe los mensajes ya preparados por `DateRuleNotifier` (destinatarios, título, mensaje y nivel).
- `handle()` crea una `CustomNotification` (`type = date-rule`) por destinatario.
- **Necesita un worker** (`php artisan queue:work`). Sin worker, las notificaciones se quedan en `jobs` y no llegan a la campanita.

---

## 9. Capa Filament

### 9.1 `App\Filament\Support\DateFieldRules` — **P1**
```php
DateFieldRules::apply(DateTimePicker::make('Fecha_Inicio_Acta_Replanteo')->label('Inicio'));
```
- Añade `->live(onBlur: true)` y un `afterStateUpdated` que llama a `$livewire->validateDateFieldOnBlur($nombreCampo)` si la página tiene el trait.
- Como en Filament v4 los `afterStateUpdated` se **acumulan**, no pisa otros callbacks del campo.
- Solo admite `DateTimePicker`. Los `DatePicker` (por ejemplo, en Documentos de expediente) no se pueden envolver tal como está.
- Si la página no usa el trait, el campo solo queda en `live` y no valida nada.

### 9.2 Trait `App\Filament\Support\Concerns\NotifiesAndValidatesDates` — **P2, P3 y guardados manuales**
Se usa en las páginas `Create*` y `Edit*` de Filament (componentes Livewire).

| Método | Paso | Qué hace |
|--------|------|----------|
| `validateDateFieldOnBlur(string $campo)` | P2, P3 | Construye un **borrador** del modelo (registro actual + estado del formulario, o modelo nuevo si es alta), evalúa **solo ese campo** y, si hay error, hace `addError('data.campo')` y lanza `ValidationException`. Si no lo hay, limpia el error. Los `warning`/`notification` disparados se muestran como notificación "Control de fechas". **No escribe en BD** |
| `persistWithDateControls(Model)` | P5–P10 manual | Para acciones propias: detecta las fechas, evalúa, bloquea si hay errores, hace `saveQuietly()` (sin observer, para no duplicar), vuelve a evaluar, registra y notifica |
| `applyDateControlsForManualChanges(Model, array $campos, ?op)` | P6–P10 manual | Para cambios que no pasan por `save()` (por ejemplo `DB::table()->update()`): evalúa los campos indicados, bloquea, registra y notifica |
| `notifyCurrentUser(...)` / `notifyTeam(...)` | — | Envían notificaciones con `NotificationService::sendByTargets` |
| `resolveTeamIdFromModel(Model)` | — | `team_id` del modelo o del expediente |
| `buildDraftModelFromFormState()` (privado) | P2 | Usa `$this->form->getState()` y `getRecord()` o `getResource()::getModel()` |

> Al construir el borrador, `getState()` valida el formulario. Si otro campo obligatorio está vacío, la validación de Filament puede saltar antes que la de fechas.

### 9.3 `NotifiesAndValidatesDatesUsageExample`
Clase de referencia, no se ejecuta. Muestra cómo usar `persistWithDateControls`, `notifyCurrentUser` y `notifyTeam` en una página.

### 9.4 Resources y páginas cubiertas
Panel **Obras** (`app/Filament/Obras/Resources`, compartido con el panel *planes*):

| Resource | Modelo → tabla | Observer (P4–P10) | Campos con `DateFieldRules` (P1) | Páginas con el trait (P2) |
|----------|----------------|:-----------------:|:--------------------------------:|---------------------------|
| `DatosDeInicioDeObrasResource` | `DatosDeInicioDeObras` → `DatosInicioDeObras` | ✔ | 11 de 13 | `CreateDatosDeInicioDeObra`, `EditDatosDeInicioDeObra` |
| `ProyectoResource` | `Proyecto` → `Proyectos` | ✔ | 15 | `CreateProyecto`, `EditProyecto` |
| `ActasdereplanteoResource` | `actadereplanteo` → `ActasDeReplanteo` | ✔ | 3 | `CreateActadereplanteo`, `EditActadereplanteo` |
| `ActaderecepcionResource` | `actaderecepcion` → `ActasRecepcionObra` | ✔ | 19 | `CreateActaderecepcion`, `EditActaderecepcion` |
| `ObraCedidaResource` | `ObraCedida` → `ObrasCedidas` | ✔ | 6 | `CreateObraCedida`, `EditObraCedida` |
| `DocumentoexpedienteResource` (Obras, Ayuntamientos y Admin) | `DocumentoExpediente` → `documentacionexpedientes` | ✔ | 0 (usa `DatePicker`) | Create/Edit de los tres paneles (trait presente, sin campos en vivo) |
| `DatosEjecucionObrasResource` + RelationManagers (`ActaReplanteo`, `ActaRecepcion`, `Prorrogas`, `Certificaciones`) | `DatosEjecucionObras` y los modelos de cada relación | ✔ (si el modelo de la relación está observado) | 0 | — (los modales de un RelationManager no son páginas con el trait) |

En un RelationManager **no hay validación en vivo**, pero el observer sigue bloqueando al guardar el modal, porque `actadereplanteo` y `actaderecepcion` están observados.

Panel **Admin** (`app/Filament/Resources`), gestión del sistema:

| Resource | Para qué |
|----------|----------|
| `DateValidationRuleResource` (*Validación de fechas → Reglas de fechas*) | Alta, edición y activación de reglas. Los `Select` usan los `options()` de los enums |
| `DateRuleExecutionResource` (*Validación de fechas → Ejecuciones de reglas*) | Consulta de la auditoría `date_rule_executions` (P9) |

---

## 10. Comandos y programación

| Comando | Clase | Llama a | Programación |
|---------|-------|---------|--------------|
| `normativa-ppac:avisos {--expediente=} {--simular}` | `AvisosNormativaPpacCommand` | `NormativaPpacAvisosService::ejecutar()` (P11) | `routes/console.php`: diario a las 03:00, `withoutOverlapping`. Requiere `schedule:run` cada minuto |
| `date-hitos:sincronizar {--expediente=} {--limpiar}` | `SincronizarFechasHitosCommand` | `SincronizadorFechasHitosService` | Manual |

---

## 11. Piezas fuera del flujo de validación

| Pieza | Situación |
|-------|-----------|
| `SincronizadorFechasHitosService`, `ConsultaExpedienteFechaHitosService`, `FechaHitoCatalogo`, página `HitosFechasMaterializados` | Proyección de hitos por expediente para consulta. No valida ni avisa |
| `app/Helpers/ControlDeFechasHelper.php` | **Código heredado sin uso**: lee la tabla antigua `reglas_validacion_fechas` y nadie lo llama. **No** aplica `date_validation_rules`. Se conserva por decisión del equipo |

---

## 12. Ejemplo completo: guardar un acta de replanteo

Reglas activas implicadas (tabla `ActasDeReplanteo` y globales):
- `GLOBAL_TODOS_LOS_CAMPOS_FECHA_VALIDOS` (`*`, `is_date`, error)
- `GLOBAL_FECHA_DEBE_SER_ANTERIOR_O_IGUAL_A_HOY` (`*`, `before_or_equal_today`, error)
- `AR_INICIO_ANTES_FIN` (inicio ≤ fin, error)
- `EXPEDIENTE_PLAZO_REPLANTEO_NO_VENCIDO` (180 días, warning)
- `EXPEDIENTE_AVISO_15_DIAS_FIN_PLAZO` (`days_to_deadline_lte`, notification)

Secuencia cuando el usuario edita `Fecha_Inicio_Acta_Replanteo` en *Actas de replanteo → Editar*:

1. **P1** — Sale del campo. `DateFieldRules` llama a `validateDateFieldOnBlur('Fecha_Inicio_Acta_Replanteo')`.
2. **P2** — El trait crea un borrador: el acta actual con los valores del formulario. `DateRuleEvaluator::evaluate(borrador, ['Fecha_Inicio_Acta_Replanteo'], UPDATE)`:
   - las reglas globales encajan por `tabla1 = *` y las del acta por su tabla;
   - `AR_INICIO_ANTES_FIN` compara con `Fecha_Final_Acta_Replanteo` del **propio borrador**, aunque no esté guardado.
3. **P3** — Si inicio > fin o la fecha es futura → error rojo en el campo. Si solo salta el warning de plazo → notificación amarilla "Control de fechas".
4. **P4** — Pulsa *Guardar*. Filament hace `save()` y salta `DateRuleObserver::saving`.
5. **P5** — `DateFieldDetector` detecta `Fecha_Inicio_Acta_Replanteo`, que tiene cast `datetime`.
6. **P6–P7** — Se repite la evaluación sobre el modelo real. Si hay error, `ValidationException` y no se guarda. Es la barrera definitiva aunque el usuario ignore el aviso de P3.
7. **UPDATE** en `ActasDeReplanteo`.
8. **P8** — `saved`: segunda evaluación.
9. **P9** — Las reglas disparadas (por ejemplo, el warning de plazo) se registran en `date_rule_executions`, sin repetirse en el mismo día.
10. **P10** — `EXPEDIENTE_AVISO_15_DIAS_FIN_PLAZO` es de tipo `notification`: el job crea una notificación en la campanita para **todos los usuarios del equipo** del expediente.

Si el mismo acta se guarda desde el **RelationManager** de *Datos de ejecución*, no hay P1–P3, pero P4–P10 ocurren igual.

---

## 13. Reglas activas (BD `Obras_test`, 26/11/2026)

| Id | Regla | Fecha controlada | Se compara con | Condición | Tipo |
|----|-------|------------------|----------------|-----------|------|
| 17 | GLOBAL_TODOS_LOS_CAMPOS_FECHA_VALIDOS | `*.*` | — | is_date | error |
| 20 | GLOBAL_FECHA_DEBE_SER_ANTERIOR_O_IGUAL_A_HOY | `*.*` | hoy | before_or_equal_today | error |
| 3 | DOC_FECHA_VALIDA | documentacionexpedientes.fechaincorporacion | — | is_date | warning |
| 4 | DOC_POSTERIOR_A_REPLANTEO | documentacionexpedientes.fechaincorporacion | ActasDeReplanteo.Fecha_Inicio_Acta_Replanteo | after_or_equal | error |
| 5 | DOC_MAX_30_DIAS_DESDE_REPLANTEO | documentacionexpedientes.fechaincorporacion | ActasDeReplanteo.Fecha_Inicio_Acta_Replanteo | max_days_between (30) | warning |
| 6 | EXPEDIENTE_PLAZO_NO_VENCIDO | Datos_Ejecucion_Obras.Fecha_Inicio_Acta_Replanteo | — | deadline_not_expired (180) | warning |
| 7 | EXPEDIENTE_AVISO_15_DIAS_FIN_PLAZO | ActasDeReplanteo.Fecha_Inicio_Acta_Replanteo | — | days_to_deadline_lte (180) | notification |
| 8 | INICIO_PETICION_AR_ANTES_INICIO_AR | DatosInicioDeObras.fecha_pet_acta_replanteo | ActasDeReplanteo.Fecha_Inicio_Acta_Replanteo | before_or_equal | error |
| 9 | AR_INICIO_ANTES_FIN | ActasDeReplanteo.Fecha_Inicio_Acta_Replanteo | ActasDeReplanteo.Fecha_Final_Acta_Replanteo | before_or_equal | error |
| 10 | RECEPCION_PROVISIONAL_ANTES_DEFINITIVA | ActasRecepcionObra.Fecha_Acta_RecProv | ActasRecepcionObra.Fecha_Acta_Rec | before_or_equal | error |
| 11 | PROYECTO_ENTREGA_ANTES_RECEPCION | Proyectos.fecha_entrega_proyecto | Proyectos.fecha_recepcion_proyecto | before_or_equal | warning |
| 12 | PROYECTO_REMISION_ANTES_APROBACION | Proyectos.fecha_remision_ayto | Proyectos.fecha_aprobacion_ayto | before_or_equal | warning |
| 13 | CESION_REMISION_ANTES_CESION | ObrasCedidas.FechaRemisionAyto | ObrasCedidas.FechaCesion | before_or_equal | warning |
| 14 | EXPEDIENTE_PLAZO_REPLANTEO_NO_VENCIDO | ActasDeReplanteo.Fecha_Inicio_Acta_Replanteo | — | deadline_not_expired (180) | warning |
| 15 | PROYECTO_RECEPCION_REGISTRADA | Proyectos.fecha_recepcion_proyecto | — | is_date | notification |
| 16 | RECEPCION_PROVISIONAL_REGISTRADA | ActasRecepcionObra.Fecha_Acta_RecProv | — | is_date | notification |
| 21 | INICIO_PETICION_AR_ANTERIOR_A_ACTA_AR | DatosInicioDeObras.fecha_pet_acta_replanteo | DatosInicioDeObras.fecha_acta_replanteo_previo | before | error |
| 22 | INICIO_ACTA_AR_POSTERIOR_A_PETICION_AR | DatosInicioDeObras.fecha_acta_replanteo_previo | DatosInicioDeObras.fecha_pet_acta_replanteo | after | error |
| 23–27 | NORMATIVA_PROYECTO, _DOCUMENTACION, _CONTRATACION, _CESION, _EJECUCION | Proyectos / ObrasCedidas / ActasRecepcionObra | NormativaPPAC.fecha_limite_* | before_or_equal | warning |

Todas tienen `operacion = both`. Las de tipo `notification` (7, 15 y 16) tienen `dispara_si_cumple = 1`; el resto, `0`. Inactivas: 18 `GLOBAL_FECHA_DEBE_SER_POSTERIOR_A_HOY` y 19 `GLOBAL_FECHA_DEBE_SER_POSTERIOR_O_IGUAL_A_HOY`.

Observaciones:
- Las reglas 17 y 20 afectan a **todas las fechas** de las 9 tablas observadas:
  - la 20 impide grabar fechas futuras, también las previstas (por ejemplo `fecha_prev_comienzo_obra`);
  - la 17 impide **vaciar** una fecha, porque con valor vacío `is_date` no se cumple y salta.
- La regla 8 tiene `tabla1 = DatosInicioDeObras`, que es también la tabla de `Justificacion_Obra`: se aplica a ambos modelos.
- La regla 6 controla la columna `Fecha_Inicio_Acta_Replanteo` de `Datos_Ejecucion_Obras`, no la de `ActasDeReplanteo`. Solo salta cuando se modifica ese campo en Datos de ejecución.

---

## 14. Cómo añadir el control de fechas a un recurso nuevo

1. **Modelo:** las columnas fecha deben tener cast de fecha (`'date'` o `'datetime'`) o contener "fecha" en el nombre. Si no, `DateFieldDetector` no las ve.
2. **Configuración:** añadir el modelo a `observed_models` en `config/date_rules.php` y ejecutar `php artisan config:clear`. Con esto el guardado ya queda protegido (P4–P10).
3. **Reglas:** crear las reglas en *Reglas de fechas* con `tabla1` = nombre real de la tabla (sin `dbo.`, sin distinguir mayúsculas). Las globales (`*`) se aplican solas.
4. **Validación en vivo (opcional, recomendada):**
   - en el Resource: `DateFieldRules::apply(DateTimePicker::make('campo')->...)`;
   - en las páginas `Create*` y `Edit*`: `use NotifiesAndValidatesDates;`.
5. **Acciones propias** que modifiquen fechas sin `save()` normal: usar `persistWithDateControls()` o `applyDateControlsForManualChanges()`.
6. **Comprobar:** guardar una fecha que incumpla una regla `error` (debe bloquear) y revisar *Ejecuciones de reglas*.

---

## 15. Limitaciones conocidas

- La búsqueda en otra tabla usa `->first()` sin orden. Si hay varias filas por expediente (prórrogas, certificaciones…), el valor comparado puede no ser el esperado.
- No hay validación en vivo en los modales de RelationManager ni en los `DatePicker` (solo `DateTimePicker`).
- Las acciones `generate_document` y `request_document` de las reglas no hacen nada todavía.
- Los guardados con `withoutEvents()`, `saveQuietly()` o SQL directo no pasan por el observer.
- Las notificaciones diferidas dependen del worker de colas, y los avisos nocturnos de `schedule:run`.

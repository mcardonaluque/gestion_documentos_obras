# Control de fechas: servicios, llamadas y secuencia de comprobación

Complementa a [sistema_control_fechas_hitos_avisos.md](./sistema_control_fechas_hitos_avisos.md). Aquí se detalla **qué clases y métodos se ejecutan, en qué orden y quién llama a quién**, para cualquier regla de fechas, sea cual sea su tipo o su origen.

---

## 1. Puntos de entrada (qué dispara la comprobación)

Hay cuatro formas de que se comprueben reglas. Todas acaban en las mismas clases (`DateRuleEvaluator`, `DateRuleExecutionLogger`, `DateRuleNotifier`), salvo la nocturna de normativa, que usa su propio servicio.

| # | Entrada | Quién la activa | Evalúa con | Puede bloquear |
|---|---------|-----------------|------------|----------------|
| E1 | **Al salir del campo** (`onBlur`) | El usuario, en un formulario con `DateFieldRules::apply()` | `validateDateFieldOnBlur()` | Sí (muestra error en el campo) |
| E2 | **Al guardar un modelo observado** | Eloquent (`saving` / `saved`) vía `DateRuleObserver` | `DateRuleObserver` | Sí (`ValidationException`) |
| E3 | **Guardado manual desde una página** | Código de la página con el trait `NotifiesAndValidatesDates` | `persistWithDateControls()` / `applyDateControlsForManualChanges()` | Sí |
| E4 | **Comando nocturno de normativa** | Scheduler a las 03:00 (`normativa-ppac:avisos`) | `NormativaPpacAvisosService` | No (solo avisa) |

Requisitos de cada entrada:
- **E1** necesita que el campo esté envuelto con `DateFieldRules::apply(...)` y que la página use el trait `NotifiesAndValidatesDates`.
- **E2** necesita que el modelo esté en `config/date_rules.php → observed_models`. `AppServiceProvider::boot()` registra `DateRuleObserver` en cada uno.
- **E3** se llama explícitamente desde la página (acciones personalizadas que no pasan por `save()` normal).
- **E4** necesita el cron `php artisan schedule:run` cada minuto en el servidor.

---

## 2. Secuencia general E1: validación al salir del campo

```
Usuario sale del campo fecha
 └─ DateFieldRules::apply()  → afterStateUpdated()
     └─ $livewire->validateDateFieldOnBlur($campo)                 [trait NotifiesAndValidatesDates]
         1. buildDraftModelFromFormState()      construye un modelo "borrador" con el estado del formulario
         2. DateRuleOperation::fromModelState() decide create/update según $model->exists
         3. DateRuleEvaluator::evaluate($borrador, [$campo], $operacion)      ← núcleo (sección 4)
         4. DateRuleEvaluator::blockingErrorsByField($resultados)             solo reglas tipo error disparadas
             ├─ hay errores → addError('data.campo') + ValidationException   (el campo queda marcado)
             └─ no hay      → resetValidation('data.campo')
         5. Reglas disparadas no bloqueantes → Filament Notification "Control de fechas" (aviso inmediato en pantalla)
```
No escribe nada en base de datos: ni log ni notificaciones persistentes. Es solo una comprobación en vivo.

---

## 3. Secuencia general E2: guardado de un modelo observado

```
$modelo->save()   (o create / update desde Filament)
 │
 ├─ evento saving → DateRuleObserver::saving($modelo)
 │    1. resolveDirtyFields()        campos modificados cuyo nombre contiene "fecha" o termina en "_at"
 │         └─ ninguno → termina (no se evalúa nada)
 │    2. guarda los campos en $dirtyDateFieldsByObject[spl_object_id]
 │    3. DateRuleEvaluator::evaluate($modelo, $campos, fromModelState($modelo->exists))
 │    4. DateRuleEvaluator::blockingErrorsByField($resultados)
 │         └─ errores → throw ValidationException   →  NO se guarda; el usuario ve el error
 │
 ├─ (INSERT / UPDATE en base de datos)
 │
 └─ evento saved → DateRuleObserver::saved($modelo)
      1. recupera y borra los campos guardados en el paso 2
      2. DateRuleEvaluator::evaluate(..., DateRuleOperation::UPDATE)   ← segunda evaluación, con datos ya guardados
      3. DateRuleExecutionLogger::log($modelo, $resultados)            ← auditoría en date_rule_executions
      4. DateRuleNotifier::notify($modelo, $resultados, Auth::id())    ← notificaciones (sección 6)
```
Se evalúa **dos veces**: antes (para poder bloquear con datos que aún no están en BD) y después (para registrar y notificar con el estado final, p. ej. cuando una regla compara con otra tabla ya actualizada).

## 3.1 Secuencia E3: guardado manual desde una página

- `persistWithDateControls($modelo)`: evalúa → si hay errores lanza `ValidationException` → `saveQuietly()` (sin disparar el observer, para no duplicar) → evalúa de nuevo → `DateRuleExecutionLogger::log` → `DateRuleNotifier::notify`.
- `applyDateControlsForManualChanges($modelo, $campos)`: para cambios hechos sin `save()` del modelo (por ejemplo `DB::table()->update()`). Evalúa → bloquea si hay errores → log → notify.

---

## 4. Núcleo: `DateRuleEvaluator::evaluate(Model, array $camposCambiados, DateRuleOperation)`

Es el único sitio donde se interpretan las reglas. Pasos, en orden:

1. **Normalizar** el nombre de la tabla del modelo y de los campos cambiados (minúsculas, sin prefijo `dbo.`).
2. **Cargar reglas**: `DateValidationRule::active()` con `operacion` ∈ {`both`, la operación actual}.
3. **Por cada regla**:
   1. `ruleMatchesTable(tabla1, tablaModelo)`: coincide la tabla o `tabla1 = *`. Si no, se salta.
   2. `resolveTargetFields(campo1, ...)`: el `campo1` de la regla debe estar entre los campos cambiados (o `*` = todos los cambiados). Si no, se salta.
   3. `matchesPhaseAndState()`: si la regla define `fase` o `estado`, el modelo debe coincidir. Si no, se salta.
   4. **Por cada campo objetivo**:
      - **Valor izquierdo**: `resolveFieldValue(modelo, tabla1, campo)`.
        - Misma tabla que el modelo → `getAttribute()`.
        - Otra tabla → `resolveExpedienteId()` (`expediente_id` / `Expediente` / `n_exp`) y consulta `DB::table(tabla)->where('expediente_id', ...)`.
      - **Valor derecho**: `resolveRightValue(modelo, tabla2, campo2)`, que prueba por este orden:
        1. `resolveSystemDateValue()`: si `tabla2` o `campo2` es `today`, `@today`, `current_date`, `now`, `@now` o `current_timestamp` → fecha del sistema.
        2. **`tabla2 = NormativaPPAC`** → `resolveNormativaValue()` → `NormativaPpacHitosService::fechaLimite()` (sección 5).
        3. Cualquier otra tabla → `resolveFieldValue()`, como el valor izquierdo.
      - `toDate()` convierte ambos a `Carbon` (o `null` si no es fecha válida).
      - Si la condición exige segunda fecha (`requiresSecondDate()`) y falta alguna de las dos → **se omite** (no hay comparación posible).
      - `evaluateCondition(condicion, izq, der, plazo_dias, aviso_dias)` → `passed` (la condición se cumple o no).
      - `triggered = dispara_si_cumple ? passed : !passed`.
      - `resolveMessageStage()`: `preaviso` si la condición es `days_to_deadline_lte` y se cumple; si no `cumplida` o `incumplida`.
      - `resolveRuleMessage()`: elige `mensaje_preventivo`, `mensaje_cumplida` o `mensaje_incumplida` según la etapa; si está vacío usa `mensaje`.
      - Se añade un `DateRuleEvaluationResult` (regla, campo, passed, triggered, etapa, tipo, mensaje, valores).
4. Devuelve la colección de resultados.

`blockingErrorsByField($resultados)` filtra los resultados con `triggered = true` y `tipo = error` (`DateRuleType::isBlocking()`) y los agrupa por campo.

---

## 5. Resolución contra la normativa PPAC (`tabla2 = NormativaPPAC`)

```
DateRuleEvaluator::resolveNormativaValue($modelo, $campo2)
 1. resolveExpedienteId($modelo)
 2. Expediente::where('expediente_id', ...)->first()
 3. NormativaPpacHitosService::fechaLimite($expediente, $campo2)
     a. normativaDe()  →  NormativaPpac::resolveFor($expediente->ao_ejecucion, $expediente->codigo_plan)
                          (normativa del plan; si no existe, la general del año con Codigo_Plan nulo)
     b. campoSegunRedaccion()   si campo2 acaba en D/A: usa D si Proyecto.SubvencionEconRedaccion, A si no
     c. lee la fecha de la normativa
     d. prórrogas: según el campo, mira PlazoObraActivo (fase activa) y, si su fecha_fin es posterior, la usa
```
El resultado es la fecha límite que se compara con `campo1` en el paso 3.4 del evaluador. Si no hay normativa o la fecha está vacía devuelve `null` y la regla se omite.

---

## 6. Registro y notificación (tras evaluar, entradas E2/E3)

### 6.1 `DateRuleExecutionLogger::log($modelo, $resultados)`
Por cada resultado con `triggered = true`:
- calcula una **huella** `sha1(regla | modelo | id | campo | etapa | mensaje | fecha de hoy)`;
- `DateRuleExecution::firstOrCreate(['fingerprint' => ...], [...])`: guarda regla, modelo, `expediente_id`, tabla, campo, valores comparados, severidad, acción y mensaje.
- La huella evita duplicar el mismo registro el mismo día.

### 6.2 `DateRuleNotifier::notify($modelo, $resultados, $actorId)`
1. Filtra resultados con `triggered = true` y (`accion = notify` **o** `tipo = notification`).
2. Resuelve `actorId` (usuario autenticado) y los usuarios del equipo del expediente (`resolveTeamUserIds()` → `team_id` del modelo o del `Expediente`).
3. `resolveRecipientsByType()` por resultado:
   - `warning` → solo el usuario que guarda;
   - `error` → solo el usuario que guarda;
   - `notification` → todos los usuarios del equipo.
4. Construye título (`resolveTitle()`: preaviso / cumplida / según tipo) y mensaje (`[Modelo #id] texto`).
5. Entrega:
   - `async_notifications = true` → `DispatchDateRuleNotificationsJob::dispatch($mensajes)->onQueue(...)`; el job, en el worker de colas, crea las filas;
   - `false` → `dispatchNow()` crea las filas de inmediato.
6. Cada fila es un `CustomNotification` (`type = date-rule`, `notifiable = User`, `data = {title, message, type}`) que aparece en la **campanita**.

---

## 7. Secuencia E4: comando nocturno de normativa

```
Scheduler (routes/console.php) 03:00 → php artisan normativa-ppac:avisos [--simular] [--expediente=ID]
 └─ AvisosNormativaPpacCommand::handle()
     └─ NormativaPpacAvisosService::ejecutar($simular, $expedienteId)
         0. asegurarTiposAviso()              crea TiposAvisos 10 y 11 si faltan (no en --simular)
         1. Expediente::where(ao_ejecucion >= año actual - (anios_revision - 1)) en bloques de 200
            (con proyecto, assignedUsers y team.users precargados)
         2. por cada expediente → procesar():
             └─ NormativaPpacHitosService::hitosDe($expediente)
                 a. normativaDe()           sin normativa → colección vacía
                 b. reglas()                DateValidationRule activas con tabla2 = NormativaPPAC
                 c. por cada regla:
                     - fechaLimite($expediente, regla.campo2)       (sección 5)
                     - fechaReal($expediente, regla)                 consulta tabla1.campo1 del expediente
                     - devuelve {regla, fecha_limite, resuelto = (fecha real existe)}
             por cada hito:
               - resuelto → cerrarAvisos(): UPDATE Avisos SET FecSolucion = ahora WHERE expediente + codigo_hito + FecSolucion IS NULL
               - no resuelto → NormativaPpacHitosService::etapa(limite, hoy, aviso_dias, max_dias_vencido)
                     'preaviso' | 'vencido' | null
                     null → nada
                     si existe ya un Aviso (expediente + TipoAviso + codigo_hito + fecha_hito) → nada
                     --simular → solo cuenta
                     si no → registrarYNotificar()
         3. registrarYNotificar(), en una transacción:
             a. Aviso::create({Referencia, TipoAviso 10/11, Codigo_Plan, numero_obra, subreferencia,
                               ao_ejecucion, Usuario='SISTEMA', expediente_id, team_id, codigo_hito, fecha_hito})
             b. destinatarios() = tramitadores asignados (assignedUsers) ∪ usuarios del equipo (team.users)
             c. NotificationService::sendByTargets(titulo, mensaje, tipo, userIds, datos)  → CustomNotification
 └─ imprime resumen: expedientes, avisos, notificaciones, avisos cerrados
```
Diferencias con E1–E3: aquí **no** interviene `DateRuleEvaluator`, el log `date_rule_executions` ni `DateRuleNotifier`. La regla solo aporta `tabla1/campo1`, `tabla2/campo2` y `aviso_dias`. La condición no se evalúa: la lógica es "¿existe la fecha real? y si no, ¿cuánto falta para el límite?". El registro se hace en `Avisos` y las notificaciones se crean directamente, sin pasar por la cola.

---

## 8. Hitos materializados (independiente de las reglas)

```
php artisan date-hitos:sincronizar [--expediente=ID] [--limpiar]
 └─ SincronizarFechasHitosCommand
     └─ SincronizadorFechasHitosService
         sincronizarCatalogoDesdeConfig()   hitos_catalogo → fecha_hitos_catalogo (desactiva códigos retirados)
         sincronizarTodo() / sincronizarExpediente()
           └─ sincronizarPorMapa() → agruparPorTabla() → sincronizarTabla() → guardarHito()
                  updateOrCreate en expediente_fecha_hitos (expediente_id + codigo_hito + source_record_id)
Consulta: ConsultaExpedienteFechaHitosService  ←  página "Hitos materializados" (panel Obras)
```
No se ejecuta al guardar ni por el scheduler: hay que lanzarlo a mano o programarlo. No evalúa reglas ni genera avisos.

---

## 9. Resumen de llamadas por entrada

| Entrada | Evaluator | Logger (`date_rule_executions`) | Notifier / Job | `Avisos` | Notificación en campanita |
|---------|:---------:|:------:|:------:|:--------:|:-------------------------:|
| E1 onBlur | ✔ | – | – | – | Solo mensaje en pantalla (Filament) |
| E2 observer | ✔ (×2) | ✔ | ✔ (cola o directo) | – | ✔ (`type = date-rule`) |
| E3 manual | ✔ (×1–2) | ✔ | ✔ | – | ✔ |
| E4 nocturno | – (usa `NormativaPpacHitosService`) | – | – | ✔ | ✔ (`NotificationService`) |

## 10. Dependencias de infraestructura

| Pieza | Necesaria para |
|-------|----------------|
| `observed_models` + `AppServiceProvider` | E2 |
| `DateFieldRules` + trait `NotifiesAndValidatesDates` | E1, E3 |
| Worker de colas (`queue:work`) | Entrega de notificaciones de E2/E3 si `async_notifications = true` |
| Cron `schedule:run` cada minuto | E4 |
| Tablas `date_validation_rules`, `date_rule_executions`, `Avisos`, `TiposAvisos`, `NormativaPPAC`, `PlazosObraActivos`, `expediente_user_assignments` | Según entrada |

## 11. Cómo depurar una regla que "no salta"

1. ¿Está `activa` y su `operacion` es `both` o la de la acción?
2. E2: ¿el modelo está en `observed_models`? ¿cambió un campo cuyo nombre contiene `fecha`? ¿`tabla1` coincide con el **nombre de tabla del modelo** (sin `dbo.`, sin importar mayúsculas)? ¿`campo1` coincide con el nombre del atributo?
3. ¿Existen ambas fechas? Si falta alguna y la condición necesita dos, se omite en silencio.
4. ¿`dispara_si_cumple` es el adecuado? (`0` = salta cuando **no** se cumple; `1` = salta cuando **sí** se cumple.)
5. ¿`fase`/`estado` de la regla coinciden con los del modelo?
6. Para `tabla2 = NormativaPPAC`: ¿hay normativa para el año y plan del expediente y tiene rellena la fecha `campo2`?
7. Comprobar `date_rule_executions` (si el log registró la ejecución) y `Avisos` (E4). Para E4, `php artisan normativa-ppac:avisos --simular --expediente=ID`.
8. Para notificaciones de E2/E3 en cola: ¿hay worker en marcha? ¿hay filas en `jobs`/`failed_jobs`?

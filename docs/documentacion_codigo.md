# Documentación del código

> Catálogo de clases del proyecto **gestion_documentos_obras** (Laravel 12 + Filament 4): qué hace cada clase, cómo se enlaza con el resto y quién la invoca. Complementa a `docs/arquitectura_tecnica_proyecto.md`.

## Visión general

Aplicación de gestión de expedientes de obras de una diputación, construida sobre:

- **Laravel 12** (`bootstrap/app.php` registra rutas web, comandos de `app/Console/Commands` y el planificador en `routes/console.php`).
- **Filament 4** con cuatro paneles: `admin` (administración, solo super_admin), `obras` (panel principal de técnicos), `planes` (variante de planes provinciales) y `ayuntamientos` (multi-tenant por `Team`).
- **Dos conexiones de BD**: la por defecto de Laravel (usuarios, notificaciones, reglas de fechas, catálogos nuevos) y la conexión `Obras` (SQL Server heredado: expedientes, obras, importes, actas…).

Flujos transversales principales:

1. **Motor de reglas de fechas**: `DateRuleObserver` (registrado en `AppServiceProvider` sobre los modelos de `config/date_rules.php`) evalúa `DateValidationRule` mediante `DateRuleEvaluator`, audita con `DateRuleExecutionLogger` y notifica con `DateRuleNotifier` (en cola vía `DispatchDateRuleNotificationsJob`).
2. **Hitos y avisos PPAC**: el comando `normativa-ppac:avisos` (programado a diario a las 03:00) usa `NormativaPpacAvisosService` + `NormativaPpacHitosService` para generar avisos y notificaciones; `date-hitos:sincronizar` materializa fechas con `SincronizadorFechasHitosService`.
3. **Impresión de documentos**: `WordTemplatePrintService` orquesta plantillas Word→PDF (resolución de ruta, sincronización y resolución de variables); `ImpresionPlantillaSqlServerService` cubre las plantillas heredadas alimentadas por procedimientos almacenados de SQL Server.
4. **Integración con el Tramitador**: `TramitadorApiClient` (+ `TramitadorAuthService`) habla con la API externa; `TramitadorMock` la simula en desarrollo cuando `TRAMITADOR_MOCK=true`.
5. **Notificaciones internas**: el evento `SystemEventOccurred` → listener `HandleSystemEventNotifications` → `DispatchNotificationJob` → `NotificationService`, que persiste `CustomNotification` (campanita de Filament).

### Rutas web (`routes/web.php`)

| Ruta | Controlador | Propósito |
|---|---|---|
| `/` | — | Redirección a `/obras/login`. |
| `/documentos-expediente/{documento}/pdf[/download]` | `DocumentoPdfController@show/@download` | Previsualización/descarga del PDF de un documento (auth). |
| `/documentos-expediente/previsualizacion/{archivo}` | `DocumentoPdfController@showTemporary` | Vista previa de PDF recién subido (auth + URL firmada). |
| `/informes/expedientes/imprimir` | `ImprimirInformeExpedientesController` | Versión imprimible del informe agrupado de expedientes (auth). |

### Planificador (`routes/console.php`)

| Comando | Frecuencia |
|---|---|
| `normativa-ppac:avisos` | Diario a las 03:00 (`withoutOverlapping`). |

---

## Núcleo: servicios, DTOs, eventos y comandos

### app\Services

- `ExpedienteWorkFlowService` — Workflow de documentos de expediente: al guardar un documento actualiza el estado del `Expediente` según la definición del `DocumentoGenerico`, sube/descarga ficheros al Tramitador y emite el evento de sistema. Llama a `TramitadorApiClient`; emite `SystemEventOccurred`.
- `NotificationService` — Envío de notificaciones internas persistentes (campanita Filament); resuelve destinatarios por usuario, equipo o difusión global. Crea `CustomNotification` de tipo `GenericDatabaseNotification`; usado por `CreateNotification` (página Filament), trait `NotifiesAndValidatesDates`, `DispatchNotificationJob`, `NormativaPpacAvisosService` y `ProrrogaDeadlineNotifier`.
- `PendienteContratacionObraQueryService` — Aplica filtros de búsqueda y el criterio contratada/sin contratar (`EstadoContratacionObra`) a consultas de obras. Usado por `PendienteContratacionObraResource`.

#### app\Services\Assignments

- `ExpedienteAssignmentVisibilityService` — Política única de visibilidad de expedientes por asignación de usuario (`ExpedienteUserAssignment`); restringe consultas al conjunto asignado salvo acceso global. Usado por el trait `HasAssignedExpedienteVisibility` y el widget `UltimasObrasTableWidget`.

#### app\Services\Database

- `EjecutorProcedimientosAlmacenadosSqlServer` — Ejecuta procedimientos almacenados autorizados (enum `ProcedimientoAlmacenadoObras`) en la conexión SQL Server «Obras» con bindings seguros. Usado por `ImpresionPlantillaSqlServerService`.

#### app\Services\DateHitos

- `ConsultaExpedienteFechaHitosService` — Consulta filtrable de la tabla materializada `expediente_fecha_hitos` (joins con catálogo y teams). Usado por la página Filament `HitosFechasMaterializados`.
- `NormativaPpacHitosService` — Resuelve fechas límite de la normativa PPAC por expediente y las reglas que las comparan; las prórrogas (`PlazoObraActivo`) sustituyen a la fecha de normativa. Usa `NormativaPpac` y `DateValidationRule`; consumido por `DateRuleEvaluator` y `NormativaPpacAvisosService`.
- `SincronizadorFechasHitosService` — Sincroniza `FechaHitoCatalogo` desde `config/date_rules.php` y vuelca fechas reales en `ExpedienteFechaHito`. Invocado por el comando `SincronizarFechasHitosCommand`.

#### app\Services\DateRules

- `DateRuleEvaluator` — Evalúa reglas de fechas (`DateValidationRule`) sobre modelos Eloquent, incluida comparación con NormativaPPAC; produce `DateRuleEvaluationResult` y errores bloqueantes por campo. Invocado por `DateRuleObserver` y el trait Filament `NotifiesAndValidatesDates`.
- `DateRuleExecutionLogger` — Auditoría de reglas disparadas: persiste `DateRuleExecution` con fingerprint diario antiduplicados. Invocado por `DateRuleObserver` y `NotifiesAndValidatesDates`.
- `DateRuleNotifier` — Notifica resultados disparados a actor/equipo creando `CustomNotification`; en diferido vía `DispatchDateRuleNotificationsJob` si `date_rules.async_notifications`. Invocado por `DateRuleObserver` y `NotifiesAndValidatesDates`.
- `NormativaPpacAvisosService` — Genera/cierra avisos (`Aviso`, `TiposAviso`) por hitos PPAC y notifica a tramitadores asignados y usuarios del ayuntamiento. Usa `NormativaPpacHitosService` y `NotificationService`; invocado por `AvisosNormativaPpacCommand`.

#### app\Services\Importes

- `ImportesDistributionCalculator` — Reparte importes/porcentajes por organismo y fase (`ImportesStage`) manteniendo coherencia importe↔porcentaje; calcula totales. Usado por el componente `ImportesManagement` y el widget Livewire `ManageImportesWidget`.
- `ImportesManagementRepository` — Carga/guarda `ImportesDeObras` (totales) e `ImportesPorOrganismo` (reparto DIP/AYT) usando `ImportesOrganismoRowData`; reparto por defecto 50/50. Usado por `ImportesManagement`, `ManageImportesWidget`, `DatosDeInicioDeObrasResource` y `EditDatosDeInicioDeObra`.

#### app\Services\Informes

- `InformeExpedientesService` — Genera el informe agrupado de expedientes (consulta a Expedientes/TablaDeEstados/teams/ImportesDeObras) devolviendo `InformeExpedientesResultadoData`. Usado por la página Filament `InformeExpedientesAgrupado` y `ImprimirInformeExpedientesController`.

#### app\Services\Prorrogas

- `ProrrogaDeadlineNotifier` — Notifica la fecha máxima para solicitar prórroga del plazo activo (vía `ProrrogaRulesService` + `NotificationService`, antiduplicado diario); destinatarios: asignados (carreteras) o equipo. Invocado desde la página Filament `ViewExpediente`.
- `ProrrogaRulesService` — Reglas de negocio de admisión de prórrogas: ventana de solicitud por normativa PPAC, validación (máx. mitad del plazo base, plazos de petición), fecha de justificación (+3 meses). Usado por `RegistrarProrrogaAction`, `SolicitudesProrrogaRelationManager`, `ExpedienteResourceBase`, widgets `DocumentosTable`/`ExpedientesTable` y `ProrrogaDeadlineNotifier`. Devuelve `ProrrogaValidationResult`.
- `SolicitudProrrogaService` — Prepara y valida solicitudes de prórroga de ayuntamientos: normaliza fechas/días contra `PlazoObraActivo` y el máximo de `NormativaPpac`; valida restricciones de fase. Usado por `SolicitudesProrrogaRelationManager` (panel Ayuntamientos).

#### app\Services\Templates

- `ImpresionPlantillaSqlServerService` — Genera PDFs de documentos heredados (RegistroModelos/RegistroDatos) con datos de procedimientos SQL Server. Usa `TechnicalAssistanceTemplateRepository`, `EjecutorProcedimientosAlmacenadosSqlServer` y `WordTemplatePrintService`; consumido por `PeticionActaReplanteoDocumentService` y `TechnicalAssistanceDocumentService`.
- `PeticionActaReplanteoDocumentService` — Configura la petición de acta de replanteo previo (plantilla, procedimiento `DatosListados`, mapeo de variables WD_*). Delega en `ImpresionPlantillaSqlServerService`; usado por `DatosDeInicioDeObrasResource`.
- `TechnicalAssistanceDocumentService` — Configura la petición de ayuda técnica (variables heredadas VB, tipo de ayuda y dirección técnica). Delega en `ImpresionPlantillaSqlServerService`; usado por `DatosDeInicioDeObrasResource`.
- `TechnicalAssistanceTemplateRepository` — Catálogo documental heredado SQL Server: localiza plantillas en `RegistroModelos` y variables asociadas. Usado por `ImpresionPlantillaSqlServerService`.
- `TemplateContextFactory` — Fabrica el contexto base de impresión precargando relaciones del modelo. Usado por `WordTemplatePrintService`.
- `TemplatePathResolver` — Resuelve la ruta física de una plantilla (`DocumentoGenerico`) probando ubicaciones candidatas. Usado por `WordTemplatePrintService` y `WordTemplateVariableSynchronizer`.
- `TemplateVariableValueResolver` — Resuelve el valor final de cada `DocumentoGenericoVariable` (auto/record/system/fixed) y sugiere rutas para nombres WD_*. Usado por `WordTemplatePrintService` y `WordTemplateVariableSynchronizer`.
- `WordTemplatePlaceholderExtractor` — Extrae placeholders de plantillas Word con PhpWord `TemplateProcessor`, normalizados a mayúsculas. Usado por `WordTemplatePrintService` y `WordTemplateVariableSynchronizer`.
- `WordTemplatePrintService` — Orquestador de impresión Word→DOCX/PDF: localiza plantilla, sincroniza variables, resuelve valores y convierte a PDF; devuelve `TemplateRenderResult`. Usa los servicios Templates anteriores; consumido por el trait `HasWordTemplatePrinting` e `ImpresionPlantillaSqlServerService`.
- `WordTemplateVariableSynchronizer` — Sincroniza variables almacenadas con el contenido real de la plantilla (altas, conservación de config, desactivación). Usado por `DocumentoGenericoResource` y `WordTemplatePrintService`.

#### app\Services\Tramitador

- `TramitadorApiClient` — Cliente HTTP de bajo nivel autenticado (Bearer) contra la API del tramitador: get/post/postFile/request configurable. Usa `TramitadorAuthService`; consumido por `ExpedienteWorkFlowService` y `TramitadorApiOperationService`; interceptado por `TramitadorMock`.
- `TramitadorApiOperationService` — Ejecuta funciones del catálogo `tramitador_api_operaciones` validando parámetros (`tramitador_api_parametros`) y componiendo ruta/query/body. Usa `TramitadorReglasCatalogo` y `TramitadorApiClient`.
- `TramitadorApiResponseReader` — Interpreta respuestas `{status, message, data}` extrayendo los parámetros de salida activos por `ruta_json`, con soporte de listas anidadas. Lee `TramitadorApiOperacion`/`TramitadorApiParametro`. Sin consumidores directos actuales en `app/`.
- `TramitadorAuthService` — Canjea el token fijo de `config/tramitador.php` en `/login` y lo cachea 50 min; permite invalidarlo. Usado por `TramitadorApiClient`.
- `TramitadorReglasCatalogo` — Traduce `reglas_validacion` del catálogo (texto Laravel o JSON declarativo con orígenes whitelisted) a reglas de validación Laravel. Usado por `TramitadorApiOperationService`.

#### app\Services\Tramitador\Mock

- `TramitadorMock` — Registra respuestas simuladas con `Http::fake()` (catálogo de operaciones + fixtures JSON); devuelve 401/404/405 como la API real. Despacha a los demás mocks; activado por `TramitadorMockServiceProvider` y tests.
- `TramitadorMockFixtures` — Carga fixtures JSON de `resources/mock/tramitador/{CODIGO}/{id}.json` con fallback a `default.json` y búsqueda por CSV. Usado por todos los mocks.
- `CreacionExpedienteMock` — Simula CREAEXP reflejando `ref_emisor`.
- `DocumentoMock` — Simula OBDOC filtrando bloques (original/carátula/metadatos) según la query.
- `ListadoExpedientesMock` — Simula LEXP: filtros, orden por fecha de apertura desc y límite de 100.
- `ModificacionEstadoExpedienteMock` — Simula MODESEXP: solo cierra expedientes abiertos sin trámites pendientes (consulta fixtures INFNIMB/INFROF/INFNOT).
- `ModificacionExpedienteMock` — Simula MODEXP con resultados 200/409/422; expone `ESTADO_ABIERTO` reutilizado por otros mocks.
- `NotificacionExpedienteMock` — Simula NOTEXP (alta de notificación, 409 si cerrado).
- `PeticionExpedienteMock` — Simula ICDEXP/NIMEXP/ROFEXP devolviendo `id_peticion` con prefijo por operación.

### app\DTOs

- `DateRuleEvaluationResult` — Resultado inmutable de evaluar una regla de fechas sobre un campo. Producido por `DateRuleEvaluator`; consumido por `DateRuleExecutionLogger` y `DateRuleNotifier`.
- `ImportesOrganismoRowData` — Fila inmutable de reparto de importes por organismo (porcentajes/importes de cada fase). Construido/consumido por `ImportesManagementRepository`.
- `Informes\InformeExpedientesFilaData` — Fila del informe agrupado (obra, estado, municipio, año, importe). Creado por `InformeExpedientesService`; agrupado en `InformeExpedientesGrupoData`.
- `Informes\InformeExpedientesFiltrosData` — Filtros normalizados del informe (años, estado, equipo, agrupación) con `fromArray()`/`toQueryParams()`. Construido en `InformeExpedientesAgrupado` e `ImprimirInformeExpedientesController`; consumido por `InformeExpedientesService`.
- `Informes\InformeExpedientesGrupoData` — Grupo de filas con clave/etiqueta y totales por grupo. Creado por `InformeExpedientesService`.
- `Informes\InformeExpedientesResultadoData` — Resultado completo del informe (filtros, grupos, totales, fecha). Producido por `InformeExpedientesService`; consumido por la página Filament y la vista de impresión.
- `Prorrogas\ProrrogaValidationResult` — Resultado de validación de prórroga (valid, message, maxAllowedDays). Devuelto por `ProrrogaRulesService::validateRequest()`.
- `Templates\DefinicionPlantillaWordHeredada` — Identifica una plantilla del catálogo heredado (CodPro, NumApli, NumMod, nombre, ruta). Creado por `TechnicalAssistanceTemplateRepository`; usado por `ImpresionPlantillaSqlServerService`.
- `Templates\TemplateRenderResult` — Resultado de renderizado (ruta temporal, nombre de descarga, formato, variables). Producido por `WordTemplatePrintService`.

### app\Data

- `ObraGeneralData` — DTO simple de datos generales de obra con factoría `fromObra()`. Sin consumidores directos actuales (clase auxiliar/legado).

### app\Actions

- `Prorrogas\RegistrarProrrogaAction` — Alta transaccional de prórroga: calcula NumSec, valida con `ProrrogaRulesService`, persiste `Prorroga` y actualiza `PlazoObraActivo` (incl. justificación +3 meses). Usado por `ProrrogasRelationManager` de `DatosEjecucionObras` (panel Obras).

### app\Jobs

- `DispatchDateRuleNotificationsJob` — Job en cola que crea en lote `CustomNotification` para avisos de reglas de fechas ya preparados. Despachado por `DateRuleNotifier` (cola configurable en `date_rules.notifications_queue`).
- `DispatchNotificationJob` — Job en cola que delega el envío de notificaciones internas en `NotificationService::sendByTargets()`. Despachado por el listener `HandleSystemEventNotifications`.

### app\Events y app\Listeners

- `SystemEventOccurred` (evento) — Evento de sistema genérico con código de evento, expediente y payload; punto de entrada del pipeline de notificaciones. Emitido por `ExpedienteWorkFlowService`.
- `HandleSystemEventNotifications` (listener, `ShouldQueue`) — Escucha `SystemEventOccurred`: registra auditoría en `EventLog` y despacha `DispatchNotificationJob`. Registrado en `EventServiceProvider`.

### app\Observers

- `DateRuleObserver` — Observer transversal de modelos: en `saving` evalúa reglas bloqueantes (lanza `ValidationException`); en `saved` registra auditoría y notifica. Usa `DateRuleEvaluator`, `DateRuleExecutionLogger`, `DateRuleNotifier`; registrado en `AppServiceProvider` sobre los modelos de `config/date_rules.php` (`observed_models`).

### app\Notifications

- `GenericDatabaseNotification` — Notificación genérica de base de datos (canal `database`, mail opcional) compatible con Filament. Referenciada como `type` por `NotificationService` al crear `CustomNotification`.

### app\Console\Commands

- `AvisosNormativaPpacCommand` — Comando `normativa-ppac:avisos {--expediente=} {--simular}`: genera avisos/notificaciones de hitos PPAC vía `NormativaPpacAvisosService`. Programado diariamente a las 03:00 en `routes/console.php` (`withoutOverlapping`).
- `SincronizarFechasHitosCommand` — Comando `date-hitos:sincronizar {--expediente=} {--limpiar}`: sincroniza catálogo e hitos vía `SincronizadorFechasHitosService`. Ejecución manual.
- `UpdatePasswordsToBcrypt` — Comando `app:migrar-usuarios-a-users`: migra usuarios heredados (tabla `Usuarios`, conexión Obras) al modelo `User` con team 0. Ejecución manual puntual.

### app\Helpers

- `ControlDeFechasHelper` — Validación de fechas según la tabla `reglas_validacion_fechas` lanzando `ValidationException`. Sin consumidores actuales (reemplazado por los servicios DateRules).
- `ControlesVariosHelper` — Archivo vacío (sin clase).
- `GestiondeEstadosHelper` — Esqueleto para transiciones de estado documental; métodos vacíos. Sin consumidores actuales (la lógica real vive en `ExpedienteWorkFlowService`).
- `GetDatosGenerales` — Datos descriptivos de obra/expediente (plan, municipio, estado, forma de ejecución) con caché por petición. Usado por el widget Filament `DocumentosTable`.
- `GetMunicipio` — Archivo vacío (sin clase).

---

## Capa HTTP, Policies, Providers, Enums y Forms

### app\Http\Controllers

| Clase | Propósito | Enlaces clave |
|---|---|---|
| `Controller` | Controlador base abstracto de Laravel 12. | Lo extienden todos los controladores. |
| `DocumentoPdfController` | Sirve/descarga la vista previa de PDFs de documentos de expediente (URL externa, ruta Windows o relativa). | Rutas `documentos.pdf.preview`, `documentos.pdf.download` y `documentos.pdf.temporal`; modelo `DocumentoExpediente`. |
| `Informes\ImprimirInformeExpedientesController` | Controlador invocable que genera la versión imprimible del informe agrupado de expedientes. | Ruta `informes.expedientes.imprimir`; valida con `ImprimirInformeExpedientesRequest`, autoriza con `InformeExpedientesPolicy`, llama a `InformeExpedientesService`. |
| `AlertaController`, `AvisoController`, `ImportesDeObrasController`, `ImportesPorOrganismoController`, `OrganismoController`, `TablaDeCarreteraController`, `ZonaController` | Controladores de scaffolding con métodos vacíos (sin rutas registradas). | La gestión real de esos modelos se hace desde los recursos Filament. |

### app\Http\Middleware

| Clase | Propósito | Enlaces clave |
|---|---|---|
| `CleanTenantUrl` | Colapsa secuencias repetidas de `%20` en la URI y redirige a la URL limpia. | Middleware del panel Obras y tenantMiddleware del panel Ayuntamientos. |
| `EnsureSuperAdminForAdminPanel` | Aborta con 403 si el usuario no tiene el rol `super_admin` (Spatie). | authMiddleware del panel Admin. |

### app\Http\Requests

| Clase | Propósito | Enlaces clave |
|---|---|---|
| `Informes\ImprimirInformeExpedientesRequest` | Valida filtros del informe imprimible: rango de años (2000-2100, desde≤hasta), estado, team y agrupación contra `InformeExpedientesAgrupacion::options()`. | `ImprimirInformeExpedientesController`; autorización en `InformeExpedientesPolicy`. |
| `Store/UpdateImportesDeObrasRequest`, `Store/UpdateImportesPorOrganismoRequest`, `Store/UpdateTablaDeCarreteraRequest` | Form requests de scaffolding (authorize=false, rules vacías). | Solo referenciados por sus controladores scaffold (sin rutas). |

### app\Policies

| Clase | Propósito | Enlaces clave |
|---|---|---|
| `DatosDeInicioDeObrasPolicy` | Policy Shield de `DatosDeInicioDeObras` (permisos `*_datos::de::inicio::de::obras`). | `DatosDeInicioDeObrasResource` (paneles Obras/Planes). |
| `ExpedienteUserAssignmentPolicy` | Protege `ExpedienteUserAssignment`; `before()` da acceso total a super_admin, el resto exige rol Abogado. | Recurso de asignaciones de expedientes. |
| `ImportesDeObrasPolicy` | Policy Shield de `ImportesDeObras` (permisos `*_importes::deobras`). | `ImportesDeObrasResource`. |
| `ImportesPorOrganismoPolicy` | Policy Shield de `ImportesPorOrganismo`. | `ImportesPorOrganismoResource`. |
| `InformeExpedientesPolicy` | Controla acceso a informes (no protege un modelo): super_admin o permiso `page_InformeExpedientesAgrupado`. | `ImprimirInformeExpedientesController` y página `InformeExpedientesAgrupado`. |
| `RolePolicy` | Protege el `Role` de Spatie; `before()` deniega fuera del panel `admin`, dentro delega en permisos `*_role` de Shield. | `RoleResource` de Shield en el panel Admin. |
| `TablaDeCarreteraPolicy` | Todas las habilidades devuelven false (catálogo de solo lectura interna). | Sin CRUD expuesto. |
| `UserPolicy` | Concede todo a cualquier usuario autenticado (el control real lo aplican Shield y `EnsureSuperAdminForAdminPanel`). | `UserResource` del panel Admin. |

### app\Providers

| Clase | Propósito | Enlaces clave |
|---|---|---|
| `AppServiceProvider` | Arranque global: assets Filament, trazas de autenticación, log SQL en debug, registro de `DateRuleObserver` en los modelos de `date_rules.observed_models`. | `bootstrap/providers.php`. |
| `EventServiceProvider` | Mapea `SystemEventOccurred` → `HandleSystemEventNotifications`. | `bootstrap/providers.php`. |
| `TramitadorMockServiceProvider` | Activa `TramitadorMock::register()` cuando `tramitador.mock.enabled` (TRAMITADOR_MOCK) es true. | `bootstrap/providers.php`; `config/tramitador.php`. |
| `Filament\AdminPanelProvider` | Configura el panel `admin` (/admin), por defecto, reservado a super_admins. | `UserResource` + `RoleResource` (Shield), dashboard admin, notificaciones BD, middleware `EnsureSuperAdminForAdminPanel`. |
| `Filament\AyuntamientosPanelProvider` | Configura el panel `ayuntamientos` (/ayuntamientos), multi-tenant por `Team`. | Recursos Expediente/Documentoexpediente del espacio Ayuntamientos; navegación por fases documentales; `CleanTenantUrl` como tenantMiddleware. |
| `Filament\ObrasPanelProvider` | Configura el panel principal `obras` (/obras) para técnicos de la diputación. | Recursos de expedientes, documentos, importes, inicio/ejecución; páginas `InformeExpedientesAgrupado` e `HitosFechasMaterializados`; notificaciones BD con sondeo 30s. |
| `Filament\PlanesPanelProvider` | Configura el panel `planes` (/planes), variante centrada en planes provinciales. | Reutiliza recursos del espacio Obras; navegación superior por áreas. |

### app\Enums

| Enum | Propósito | Usado por |
|---|---|---|
| `DateRuleAction` | Acción al cumplirse una regla de fechas (nada, notificar, generar/pedir documento). | `DateValidationRule`, `DateValidationRuleResource`, `DateRuleNotifier`. |
| `DateRuleCondition` | Condición temporal de las reglas de fechas (comparaciones, plazos en días). | `DateValidationRule`, `DateRuleEvaluator`. |
| `DateRuleOperation` | Operación (alta/actualización/ambas) en la que aplica una regla; `fromModelState()` deriva de la existencia del modelo. | `DateRuleObserver`, `DateRuleEvaluator`. |
| `DateRuleType` | Severidad de la regla (warning/error/notification); `isBlocking()` solo para ERROR. | `DateRuleEvaluationResult`, `DateRuleNotifier`. |
| `EstadoContratacionObra` | Obra contratada vs. pendiente de contratación. | `PendienteContratacionObraResource`, `PendienteContratacionObraQueryService`. |
| `ImportesStage` | Fase económica de la obra (inicio, cesión, contratación, ejecución). | `ImportesManagement`, `ManageImportesWidget`, `ImportesDistributionCalculator`. |
| `InformeExpedientesAgrupacion` | Criterio de agrupación del informe de expedientes (estado/municipio/año). | Pipeline completo de informes. |
| `ProcedimientoAlmacenadoObras` | Lista blanca de procedimientos almacenados permitidos en la conexión Obras. | `EjecutorProcedimientosAlmacenadosSqlServer` y servicios de plantillas. |
| `ProrrogaAlcance` | Ámbito de la prórroga (proyecto/memoria, documentación, ambas). | Relation managers de prórrogas. |
| `ProrrogaOrigen` | Origen de la prórroga (a solicitud / de oficio). | Relation managers de prórrogas. |
| `ProrrogaTipo` | Tipo de prórroga según plazo afectado. | `RegistrarProrrogaAction` y relation managers de prórrogas. |
| `TemplateVariableSourceType` | Origen de variables de plantillas Word (auto, registro, sistema, fijo). | `TemplateVariableValueResolver`, `WordTemplatePrintService`. |

### app\Forms\Components

| Clase | Propósito | Enlaces clave |
|---|---|---|
| `ImportesInfo` | Fieldset solo lectura con los importes de la obra (aprobado, a contratar, adjudicación, baja, ejecutado…). | `DatosDeInicioDeObrasResource`; relación `importes()` del registro. |
| `ImportesManagement` | Fieldset para distribuir importes entre organismos por fase, con recálculo en vivo y validación de suma 100 %. | `DatosDeInicioDeObrasResource`; `ImportesDistributionCalculator`, `ImportesManagementRepository`, enum `ImportesStage`. |
| `ObraGeneralInfo` | Fieldset solo lectura con datos generales de la obra (plan, nº obra, ubicación, zona, estado…). | `DatosDeInicioDeObrasResource`, `DatosEjecucionObrasResource`, `CertificacionesResource`. |
| `PlazosObraInfo` | Fieldset que renderiza una tabla HTML con los plazos activos de la obra (fase, fechas, días, fuente, estado). | Modelo `PlazoObraActivo`; `DatosDeInicioDeObrasResource` y `ExpedienteResourceBase`. |

### app\Livewire

| Clase | Propósito | Enlaces clave |
|---|---|---|
| `Importes\ManageImportesWidget` | Componente Livewire independiente para gestionar la distribución de importes por organismo y fase, con recálculo reactivo y guardado validado. | `ImportesDistributionCalculator`, `ImportesManagementRepository`, enum `ImportesStage`; vista `livewire.importes.manage-importes-widget`. |

---

## Modelos Eloquent (`app\Models`)

La mayoría usa la conexión **`Obras`** (SQL Server heredado) con claves primarias string no autoincrementales (habitualmente `expediente_id`).

| Modelo | Entidad / Tabla | Relaciones clave | Notas |
|---|---|---|---|
| `actaderecepcion` | Acta de recepción de obra — `ActasRecepcionObra` | belongsTo `DatosEjecucionObras` (ejecucion) | PK string `expediente_id`; ~19 casts datetime. |
| `actadereplanteo` | Acta de comprobación de replanteo — `ActasDeReplanteo` | belongsTo `DatosEjecucionObras` (ejecucion) | PK string `expediente_id`. |
| `alerta` | Configuración de alertas por fase/estado | belongsTo `TablaDeEstados`, `FaseDocumento`, `Team` | PK autoincremental. |
| `Aviso` | Aviso del sistema sobre hitos/incidencias — `Avisos` | belongsTo `TiposAviso`, `Team` | dateFormat `Ymd H:i:s`. |
| `AyudaTecnica` | Ayuda técnica (redacción/dirección) — `Ayuda_Tecnica` | belongsTo `Departamentos` ×2, `DatosDeInicioDeObras`, `TablaDeMunicipio` | Hook `creating` copia datos de la obra. |
| `certificaciones` | Certificación económica — `CertificacionesDeObras` | belongsTo `DatosDeInicioDeObras`, `DatosEjecucionObras`, `tiposjustificante`, `TablaDeEstados`, `Planes`, `Team`; hasOne `ImportesDeObras`; hasMany `ImportesPorOrganismo` | Sin timestamps. |
| `Contratista` | Contratista adjudicatario — `TBContratistas` | hasMany `PendienteContratacionObra`; belongsTo `TipoContratista`, `TbMunicipio` ×2, `TbProvincias` ×2 | PK `Codigo_contratista`; accessors con caché. |
| `CustomNotification` | Notificación personalizada — `notifications` (extiende DatabaseNotification) | morphTo notifiable (`User`, `Team`) | PK UUID; scope `unread`; accessors título/mensaje/icono/color. |
| `DateRuleExecution` | Ejecución de regla de fechas — `date_rule_executions` | belongsTo `DateValidationRule` | Fingerprint antiduplicados. |
| `DateValidationRule` | Regla configurable de validación entre fechas — `date_validation_rules` | hasMany `DateRuleExecution`; belongsTo `FaseDocumento`, `TablaDeEstados` | Casts a enums DateRule*; scope `active`. |
| `datosadicionales` | Datos adicionales de obra/expediente | belongsTo `Expediente`, `TablaDeEstados`, `Team` | PK string `expediente_id`. |
| `DatosDeInicioDeObras` | Ficha maestra de inicio de obra — `DatosInicioDeObras` | belongsTo `Expediente`, `TablaDeMunicipio`, `TablaDeCarretera`, `TablaDeEstados`, `Planes`, `FormaEjecucion`, `TipoActuacion` ×2, `Team`; hasOne `AyudaTecnica`, `ImportesDeObras`, `Planseguridadysalud`, `Proyecto`, `DatosEjecucionObras`; hasMany `ImportesPorOrganismo`, `DocumentoExpediente`, `certificaciones` | Route key `expediente_id`; accessors ubicacion/obra/plan/aportacion. |
| `DatosEjecucionObras` | Fase de ejecución de obra — `Datos_Ejecucion_Obras` | belongsTo `Team`, `Expediente`, `Planes`, `DatosDeInicioDeObras`; hasOne `ImportesDeObras`, `Planseguridadysalud`, `actadereplanteo`, `actaderecepcion`; hasMany `ImportesPorOrganismo`, `certificaciones`, `Prorroga` | PK string `expediente_id`; sin timestamps. |
| `Departamentos` | Departamento de la diputación — `TablaDeDepartamentos` | Referenciado desde `AyudaTecnica` | PK `CODIGO_DPTO`. |
| `DestinoDeDocumentos` | Destinos/procedencias de documentos — `DestinosDeDocumentos` | hasMany `DocumentoGenerico`, `DocumentoExpediente` | Sin timestamps. |
| `DocumentoExpediente` | Documento de un expediente — `dbo.documentacionexpedientes` | belongsTo `Expediente`, `DatosDeInicioDeObras`, `DocumentoGenerico`, `Team`, `DestinoDeDocumentos` ×2, `TBestadosdeDocumentos`, `Planes` | PK `idDocumento`; métodos de negocio PDF y secuencia documental. |
| `DocumentoExpedienteUser` | Pivote documento–usuario | — | Modelo mínimo; conexión por defecto. |
| `DocumentoGenerico` | Catálogo de tipos de documentos/plantillas — `documento_genericos` | belongsTo `FaseDocumento` ×2, `DestinoDeDocumentos`, `TipoDocumento`, `TablaDeEstados`; hasMany `DocumentoExpediente`, `DocumentoGenericoVariable` | Scopes forPhase/forPhases; helpers de fase. |
| `DocumentoGenericoVariable` | Variable WD_* de plantilla — `documento_generico_variables` | belongsTo `DocumentoGenerico` | Enum `TemplateVariableSourceType`. |
| `EventLog` | Registro de auditoría de eventos | — | Conexión por defecto; cast `payload` array. |
| `Expediente` | Expediente administrativo (eje de agregación) — `Expedientes` | hasOne `DatosDeInicioDeObras`, `DatosEjecucionObras`, `Proyecto`, `Justificacion_Obra`, `ObraCedida`, `ImportesDeObras`; hasMany `DocumentoExpediente`, `ImportesPorOrganismo`, `Prorroga`; belongsTo `Team`, `TablaDeEstados`, `FormaEjecucion`, `TablaDeMunicipio`, `Planes`; belongsToMany `User` (assignedUsers) | PK string `expediente_id`. |
| `ExpedienteFechaHito` | Fecha de hito materializada — `expediente_fecha_hitos` | — | dateFormat `Ymd H:i:s`. |
| `ExpedienteUserAssignment` | Asignación expediente–usuario tramitador — `expediente_user_assignments` | belongsTo `Expediente`, `User` ×2, `Team` | — |
| `FasedeProyecto` | Fase de tramitación de proyecto — `dbo.FasesdeProyectos` | belongsTo `Expediente`, `Proyecto`, `Team`, `DatosDeInicioDeObras`, `TablaDeDepartamento` ×2 | PK `expediente_id`. |
| `FaseDocumento` | Catálogo de fases documentales — `fase_documentos` | hasMany `DocumentoGenerico`; belongsTo `Team` | PK string `cod_fase`. |
| `FechaHitoCatalogo` | Catálogo de hitos de fechas — `fecha_hitos_catalogo` | — | Sincronizado desde `config/date_rules.php`. |
| `FirmanteGenerico` | Firmante genérico de documentos — `fiemante_generico` (sic) | belongsTo `Usuario`, `Team` | — |
| `FormaEjecucion` | Formas de ejecución — `FormasDeEjecucion` | Referenciado desde `Expediente` y `DatosDeInicioDeObras` | PK string `COD_CONTRATA`. |
| `ImportesDeObras` | Importes económicos globales — `ImportesDeObras` | belongsTo `DatosDeInicioDeObras` (obra), `Team` | Global scope que excluye expediente nulo. |
| `ImportesPorOrganismo` | Importes por organismo financiador | belongsTo `DatosDeInicioDeObras`, `DatosEjecucionObras`, `Team`, `Expediente`; belongsToMany `certificaciones` | — |
| `Justificacion_Obra` | Vista de justificación de obra (reutiliza `DatosInicioDeObras`) | belongsTo `Expediente`, `TablaDeMunicipio`, `TablaDeCarretera`, `Team` | PK string `Expediente`. |
| `NormativaPpac` | Normativa anual del plan provincial — `NormativaPPAC` | hasMany `PlazoObraActivo`; belongsTo `Planes` | Métodos de negocio: resolveFor, assignToPlans, cálculo de fechas límite (+2/+4 meses). |
| `ObraCedida` | Obra cedida a otra administración — `ObrasCedidas` | belongsTo `Expediente`, `DatosDeInicioDeObras`, `Team`; hasOne `ImportesDeObras`; hasMany `ImportesPorOrganismo` | — |
| `ObrasPendienteProyecto` | Obra pendiente de proyecto — vista SQL `V_ObrasPendientesProyecto` | belongsTo `Expediente`, `TablaDeMunicipio`, `TablaDeDepartamento` | Solo lectura; conexión por defecto. |
| `organismo` | Organismo financiador/gestor — `TablaDeOrganismos` | hasMany `Proyecto` ×2 (red/dir) | PK string `codigo_organismo`. |
| `PendienteContratacionObra` | Obra pendiente de contratación — `PendienteContratacionObras` | belongsTo `Contratista` | Casts decimal:2 y datetime. |
| `Permission` | Permiso Spatie extendido | Hereda de Spatie | — |
| `Planes` | Plan provincial de obras — `Planes` | hasMany `DatosDeInicioDeObras` ×3, `DatosEjecucionObras`, `certificaciones`, `Expediente` | PK string `codigo_plan`; accessor codigo_descripcion. |
| `Planseguridadysalud` | Plan de seguridad y salud — `PlanSeguridadYSalud` | belongsTo `Expediente`, `DatosDeInicioDeObras`, `DatosEjecucionObras`, `Team` | — |
| `PlazoObraActivo` | Plazo operativo vigente por expediente/fase — `PlazosObraActivos` | belongsTo `NormativaPpac`, `Expediente` | — |
| `PorcentajesDeFinanciacion` | Cofinanciación por plan/año — `dbo.Porcentajes_de_Financiacion` | — | PK `Codigo_Plan`. |
| `PorcentajesProyectos` | Porcentajes económicos por año de proyecto | — | PK `AoProyecto`; `defaultsForAoProyecto` (GG, BI, IVA…). |
| `Prorroga` | Prórroga de plazo de obra — `Prorrogas` | belongsTo `DatosEjecucionObras`, `Expediente`; hasMany `PlazoObraActivo` | PK int `NumSec` no autoincremental; trazabilidad CSV. |
| `Proyecto` | Proyecto técnico de obra — `Proyectos` | belongsTo `Expediente`, `Team`, `TablaDeDepartamento` ×3, `TablaDeMunicipio`, `DatosDeInicioDeObras`, `TecnicoObra`, `organismo` ×2, `TablaDeEstados`, `Planes`; hasMany `FasedeProyecto` | — |
| `Role` | Rol Spatie extendido | belongsToMany `User` (members); belongsTo `Team` | — |
| `SolicitudProrroga` | Solicitud de prórroga — `SolicitudesProrrogas` | belongsTo `Expediente`, `Team` | — |
| `subcontrataciones` | Subcontratación en obra — `SubContrataciones` | belongsTo `Expediente`, `Team` | — |
| `Subvenciones` | Subvención de obra — `Subvenciones` | — | PK string `Expediente`. |
| `TablaDeCarretera` | Carreteras provinciales — `TablaDeCarrteras` (sic) | hasMany `DatosDeInicioDeObras` | PK string `CodCar`. |
| `TablaDeDepartamento` | Departamento/servicio — `TablaDeDepartamentos` | hasMany `Proyecto` ×3, `Usuario` | PK `CODIGO_DPTO`. |
| `TablaDeEstados` | Estados de tramitación — `TablaDeEstados` | hasMany `DatosDeInicioDeObras`, `Expediente`, `DocumentoGenerico`, `Proyecto` | PK string `cod_estado`; flags por módulo. |
| `TablaDeMunicipio` | Municipios provinciales — `TablaDeMunicipios` | hasMany `DatosDeInicioDeObras`; belongsTo `Zona`, `Team` | PK integer `codigo_municipio`. |
| `TBestadosdeDocumentos` | Estados de documentos — `TBEstadodeDocumentos` | hasMany `DocumentoExpediente` | — |
| `TbMunicipio` | Municipios nacionales (contratistas) — `TbMunicipios` | belongsTo `TbProvincias`; hasMany `Contratista` | — |
| `TbProvincias` | Provincias nacionales — `TbProvincias` | hasMany `TbMunicipio` | PK string `PR`. |
| `Team` | Tenant multi-tenant de Filament (ayuntamiento/ámbito) — `teams` | belongsToMany `User` ×2; hasMany `DatosDeInicioDeObras`, `DatosEjecucionObras`, `Expediente`, `ImportesPorOrganismo`, `ImportesDeObras`, `alerta`, `Proyecto`; morphMany `CustomNotification` | Route key `name`. |
| `TecnicoObra` | Técnico/director de obra — `TecnicosObras` | hasMany `Proyecto` | PK `CodTec`. |
| `TipoActuacion` | Tipos de actuación — `TiposDeActuacionesDeLosPlanes` | hasMany `DatosDeInicioDeObras` | — |
| `TipoContratista` | Tipos de contratista — `TiposDeContratistas` | hasMany `Contratista` | — |
| `TipoDocumento` | Tipos generales de documento — `tipo_documentos` | hasMany `DocumentoExpediente`, `DocumentoGenerico` | — |
| `TiposAviso` | Tipos de avisos — `TiposAvisos` | hasMany `Aviso` | — |
| `tiposjustificante` | Tipos de justificante — `TiposJustificante` | hasMany `certificaciones` | — |
| `TramitadorApiOperacion` | Operación de la API del tramitador — `tramitador_api_operaciones` | hasMany `TramitadorApiParametro` | — |
| `TramitadorApiParametro` | Parámetro de operación API — `tramitador_api_parametros` | belongsTo `TramitadorApiOperacion` | — |
| `User` | Usuario de aplicación (Filament + Spatie + tenants) — `users` | belongsToMany `Team`, `Expediente` (assignedExpedientes); belongsTo `TablaDeDepartamento`; morphMany `CustomNotification` | Cast password hashed; lógica de acceso por panel. |
| `Usuario` | Usuario interno de la diputación (autenticable, heredado) | belongsTo `TablaDeDepartamento`; morphOne `User` | Conexión por defecto; tenant = departamento. |
| `UsuarioAyto` | Usuario de ayuntamiento (heredado) | belongsTo `TablaDeMunicipio`; morphOne `User` | Conexión por defecto. |
| `Zona` | Zona geográfica de municipios — `zonas` | hasMany `TablaDeMunicipio` | PK string `CODIGO`. |

---

## Paneles Filament: Administración, Ayuntamientos y Compartido

### Panel de Administración (`app\Filament\Resources`)

| Resource | Modelo y propósito | Páginas / RM | Particularidades |
|---|---|---|---|
| `OrganismoResource` | `organismo` — catálogo de organismos (denominación, abreviatura, texto de decreto, agrupación, orden). | List/Create/Edit (en `OrganismoResource/Pages`). | Único recurso con archivo suelto en la raíz de Resources. |
| `AlertaResource` | `alerta` — alertas configurables: tablas/campos comparados, condición, mensaje, fase, estado y acción (stop/continue/notificación). | List/Create/Edit. | Grupo «Notificaciones»; Shield vía `getPermissionPrefixes()`. |
| `AvisoResource` | `Aviso` — avisos generados sobre obras (referencia, plan, obra, fecha solución). | List/Create/Edit. | Grupo «Notificaciones»; tenant por `team`; select relacional con `TiposAviso`. |
| `AyudaTecnicaResource` | `AyudaTecnica` — ayudas técnicas y subvenciones de redacción/dirección por obra y municipio. | List/Create/Edit. | Selects relacionales a municipios y departamentos. |
| `DateRuleExecutionResource` | `DateRuleExecution` — histórico de ejecuciones de reglas de fechas. | Solo List. | Grupo «Validacion de fechas»; solo lectura; filtros por severidad/cumplimiento/disparo. |
| `DateValidationRuleResource` | `DateValidationRule` — reglas de validación entre campos fecha (comodín `*`, fechas de sistema `today`/`now`). | List/Create/Edit. | Grupo «Validacion de fechas»; enums `DateRuleCondition/Type/Action/Operation`; mensajes por etapa. |
| `DestinoDeDocumentosResource` | `DestinoDeDocumentos` — catálogo de destinos/procedencias de documentos. | List/Create/Edit. | Grupo «Documentación». |
| `DocumentoexpedienteResource` | `DocumentoExpediente` — CRUD completo de documentos de expediente (plan, referencia, fechas, CSV, registro, estado). | List/Create/Edit. | Grupo «Documentación»; Create/Edit usan trait `NotifiesAndValidatesDates`; precarga `estados`. |
| `DocumentoGenericoResource` | `DocumentoGenerico` — catálogo de documentos genéricos/plantillas (fase, tipo, origen/destino, E/S). | List/Create/Edit + `VariablesRelationManager`. | Acción «Sincronizar variables» con `WordTemplateVariableSynchronizer`. |
| `ExpedienteFechaHitoResource` | `ExpedienteFechaHito` — hitos de fecha materializados por expediente. | Solo List. | Grupo «Validacion de fechas»; solo lectura. |
| `ExpedientesResource` | `Expediente` — administración de expedientes de obras. | List/Create/Edit. | Grupo «Documentación»; tenant `team`; trait `CommonFilters`. |
| `FaseDocumentoResource` | `FaseDocumento` — catálogo de fases de documentos. | List/Create/Edit. | `$isScopedToTenant = false`; Shield. |
| `FechaHitoCatalogoResource` | `FechaHitoCatalogo` — catálogo de hitos (tabla/campo origen, fase, obligatorio, repetible). | List/Create/Edit. | Grupo «Validacion de fechas». |
| `NormativaPpacResource` | `NormativaPpac` — normativa PPAC: fechas límite por año y plan. | List/Create/Edit. | Grupo «Administracion de prorrogas»; validación de unicidad año/plan; autocalcula año fin de plan. |
| `PermissionResource` | `Permission` (spatie). | List/Create/Edit. | Oculto de navegación; soporte mínimo para Shield. |
| `PlazoObraActivoResource` | `PlazoObraActivo` — plazos de obra activos (fase, fechas, normativa aplicable). | List/Create/Edit. | Grupo «Administracion de prorrogas». |
| `PorcentajesDeFinanciacionResource` | `PorcentajesDeFinanciacion` — fórmulas y porcentajes de financiación por plan/organismo/año. | List/Create/Edit. | Grupo «Parametros». |
| `PorcentajesProyectosResource` | `PorcentajesProyectos` — porcentajes base anuales (GG, BI, CC, IV…) para cálculos económicos. | List/Create/Edit. | Grupo «Parametros». |
| `RoleResource` | `Role` — roles de usuario. | Páginas de Filament Shield. | Extiende el RoleResource de Shield; visible solo en panel `admin`; tenant por `members`. |
| `TablaDeCarreteraResource` | `TablaDeCarretera` — catálogo de carreteras. | List/Create/Edit. | Esqueleto: formulario/tabla sin campos. |
| `TablaDeDepartamentoResource` | `TablaDeDepartamento` — departamentos provinciales (denominación, jefaturas, datos de firma). | List/Create/Edit. | — |
| `TablaDeEstadosResource` | `TablaDeEstados` — estados de expediente. | List/Create/Edit. | Grupo «Catálogos». |
| `TablaDeMunicipioResource` | `TablaDeMunicipio` — municipios (códigos MAP, comarca, zona, población, alcalde…). | List/Create/Edit. | — |
| `TBEstadosdeDocumentosResource` | `TBestadosdeDocumentos` — estados de documentos. | List/Create/Edit. | Etiqueta «Estados de Documentos». |
| `TecnicoObraResource` | `TecnicoObra` — técnicos redactores/directores de obra. | List/Create/Edit. | — |
| `TipoContratistaResource` | `TipoContratista` — tipos de contratista. | List/Create/Edit. | `recordTitleAttribute = 'Denominacion'`. |
| `TipoDocumentoResource` | `TipoDocumento` — tipos de documentos. | List/Create/Edit. | Grupo «Documentación». |
| `TiposAvisoResource` | `TiposAviso` — tipos de avisos. | List/Create/Edit. | Grupo «Notificaciones». |
| `TramitadorApiOperacionResource` | `TramitadorApiOperacion` — funciones del API del tramitador (código, método HTTP, ruta). | List/Create/Edit + `ParametrosRelationManager`. | Grupo «Tramitador»; slug `tramitador-api-operaciones`. |
| `UserResource` | `User` — usuarios de la aplicación. | List/Create/Edit. | Grupo «Usuarios»; asigna roles spatie; hashea password. |

Relation managers del panel admin:

- `VariablesRelationManager` (DocumentoGenericos) — relación `variables` de `DocumentoGenerico`: origen, ruta lógica, formato, obligatoria/activa.
- `ParametrosRelationManager` (TramitadorApiOperaciones) — relación `parametros`: calcula el orden automáticamente al crear (path→query→header→body) y valida unicidad dirección+ubicación+nombre.

### Panel de Ayuntamientos (`app\Filament\Ayuntamientos`)

| Entrada | Detalle |
|---|---|
| `Pages\Dashboard` | Dashboard del panel con título «Planes de Obras Provinciales». |
| `DocumentoexpedienteResource` | Hereda de `DocumentoexpedienteResourceBase` (compartido). Páginas: List, Create/Edit (trait `NotifiesAndValidatesDates`), View (acción «Cancelar»). |
| `ExpedienteResource` | Hereda de `ExpedienteResourceBase`. Solo registra List y View (consulta y subida de documentación); Create/Edit existen pero no están registradas. |
| `SolicitudesProrrogaRelationManager` | Relación `solicitudesProrroga`; `createForExpediente()` valida ventana/tipo con `ProrrogaRulesService` + `SolicitudProrrogaService`, crea la prórroga sobre la obra en ejecución y adjunta el PDF de solicitud como documento. |
| `DocumentosexpedientesRelationManager` | Relación `documentosexpedientes` (versión básica, namespace legado). |
| `ViewDocumentoexpediente` | Página legada que apunta al recurso del panel Obras; no registrada. |

### Compartido (`app\Filament\Shared`)

| Entrada | Detalle |
|---|---|
| `ExpedienteResourceBase` | Abstracta; modelo `Expediente`; tenant `team`. Traits `HasAssignedExpedienteVisibility` y `CommonFilters`. Acciones de tabla: «Subir Documento» (valida plazo de justificación con `ProrrogaRulesService`, autocalcula secuencia, emite `SystemEventOccurred`) y «Documentos» (slide-over). Registra DocumentosRelationManager + SolicitudesProrroga (+ ImportesPorOrganismo solo en panel ayuntamientos). Restringe la consulta al municipio del tenant. |
| `DocumentoexpedienteResourceBase` | Abstracta; modelo `DocumentoExpediente`; tenant `team`. Autocompleta datos del expediente al seleccionarlo (`applyExpedienteDefaults`); filtra el listado por fase documental vía parámetro `?fase`. |
| `DocumentosRelationManager` | Relación `documentos` del expediente, compartida entre paneles; editable; precarga datos del expediente propietario. |


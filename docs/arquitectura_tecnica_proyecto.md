# Arquitectura tecnica del proyecto

## Objetivo

Proyecto Laravel + Filament para gestion de expedientes de obras, con:

- gestion documental y generacion de plantillas
- control de reglas de fechas
- notificaciones y trazabilidad de ejecucion

## Evolucion del motor de fechas (implementado)

Se ha incorporado una arquitectura de dos capas para fechas:

1. Catalogo de hitos funcionales por configuracion
2. Proyeccion unificada por expediente para consulta y validacion

### Componentes nuevos

- Tabla `fecha_hitos_catalogo`: metadatos de cada hito (codigo, tabla/campo origen, fase, obligatoriedad).
- Tabla `expediente_fecha_hitos`: fechas materializadas por expediente e hito.
- Modelo `FechaHitoCatalogo`.
- Modelo `ExpedienteFechaHito`.
- Servicio `SincronizadorFechasHitosService`.
- Comando `date-hitos:sincronizar`.

### Fuente de verdad de catalogo

El catalogo funcional se define en `config/date_rules.php` dentro de `hitos_catalogo`.

El comando de sincronizacion:

1. sincroniza el catalogo persistente desde config
2. marca como inactivos hitos que ya no esten en config
3. materializa fechas en la tabla unificada leyendo tablas origen

## Motor de reglas con mensajes por etapa (implementado)

La tabla `date_validation_rules` se amplia con:

- `mensaje_preventivo`
- `mensaje_cumplida`
- `mensaje_incumplida`

La evaluacion ahora clasifica cada resultado en una etapa:

- `preaviso`: para reglas de vencimiento cercano (condicion `days_to_deadline_lte` cuando pasa)
- `cumplida`
- `incumplida`

### Resolucion de mensaje

Orden de prioridad:

1. mensaje especifico de etapa
2. mensaje base (`mensaje`)

Esto permite usar textos distintos antes del vencimiento y al cumplirse o incumplirse la regla.

### Notificaciones y deduplicacion

- El notificador adapta el titulo segun etapa (`Preaviso...`, `Regla cumplida`, etc.).
- El logger incluye la etapa en el fingerprint diario para no mezclar eventos de distinta naturaleza.

## Flujo operativo recomendado

1. Definir o ajustar hitos en `config/date_rules.php`.
2. Ejecutar migraciones.
3. Ejecutar sincronizacion de hitos:

    `php artisan date-hitos:sincronizar`

4. Configurar reglas en Filament (`DateValidationRuleResource`) incluyendo mensajes por etapa.
5. Validar flujo de notificaciones en un expediente real.

## Comandos utiles

- Sincronizacion global:

    `php artisan date-hitos:sincronizar`

- Sincronizacion de un expediente:

    `php artisan date-hitos:sincronizar --expediente=ID_EXPEDIENTE`

- Limpieza previa de proyeccion y recarga:

    `php artisan date-hitos:sincronizar --limpiar`

## Notas tecnicas

- El sincronizador detecta columnas disponibles por `INFORMATION_SCHEMA.COLUMNS` para evitar errores cuando una tabla no tenga `team_id` u otros campos opcionales.
- Solo se procesan tablas con columna `expediente_id`.
- Si un campo fecha viene vacio o no parseable, se omite sin romper la ejecucion.

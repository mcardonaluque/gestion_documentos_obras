# Catalogo de hitos de fechas por obra

## Objetivo

Definir un catalogo funcional de hitos de fechas para:

1. centralizar nombres de hitos
2. mapear origen real (tabla/campo)
3. materializar la vista unificada por expediente en `expediente_fecha_hitos`

## Estado actual

- El catalogo vive en `config/date_rules.php` (`hitos_catalogo`).
- Se persiste en `fecha_hitos_catalogo` mediante sincronizacion.
- La proyeccion unificada se rellena con `php artisan date-hitos:sincronizar`.

## Convenciones

- `codigo_hito`: clave estable para reglas y reportes.
- `fase`: inicio, proyecto, ejecucion, recepcion, cesion, documental.
- `obligatorio`: si el hito se espera en flujo normal.
- `repetible`: si puede existir mas de una fecha por expediente.

## Catalogo funcional base

| codigo_hito                    | descripcion                                 | tabla_origen                 | campo_origen                      | fase       | obligatorio | repetible |
| ------------------------------ | ------------------------------------------- | ---------------------------- | --------------------------------- | ---------- | ----------- | --------- |
| notif_ayto                     | Notificacion al ayuntamiento                | DatosInicioDeObras           | fecha_notificacion_ayto           | inicio     | si          | no        |
| pet_acta_replanteo_previo      | Peticion de acta de replanteo previo        | DatosInicioDeObras           | fecha_pet_acta_replanteo          | inicio     | si          | no        |
| acta_replanteo_previo          | Fecha de acta de replanteo previo           | DatosInicioDeObras           | fecha_acta_replanteo_previo       | inicio     | no          | no        |
| pet_ayuda_tecnica              | Peticion de ayuda tecnica                   | DatosInicioDeObras           | fecha_rem_pet_ayuda               | inicio     | no          | no        |
| envio_fiscalizacion            | Envio a fiscalizacion                       | DatosInicioDeObras           | fecha_envio_fiscalizacion         | inicio     | no          | no        |
| fecha_fiscalizacion            | Fecha de fiscalizacion                      | DatosInicioDeObras           | fecha_fiscalizacion               | inicio     | no          | no        |
| prev_comienzo_obra             | Fecha prevista de comienzo de obra          | DatosInicioDeObras           | fecha_prev_comienzo_obra          | inicio     | no          | no        |
| prev_fin_obra                  | Fecha prevista de terminacion de obra       | DatosInicioDeObras           | fecha_prev_term_obra              | inicio     | no          | no        |
| com_patronato                  | Fecha de comunicacion a patronato           | DatosInicioDeObras           | FechaComPatronato                 | inicio     | no          | no        |
| ingreso_ayto                   | Fecha de ingreso de ayuntamiento            | DatosInicioDeObras           | FechaIngresoAyto                  | inicio     | no          | no        |
| medios_materiales_ayto         | Fecha de medios materiales del ayuntamiento | DatosInicioDeObras           | FechaMediosMatAyto                | inicio     | no          | no        |
| entrega_proyecto               | Entrega de proyecto                         | Proyectos                    | fecha_entrega_proyecto            | proyecto   | no          | no        |
| recepcion_proyecto             | Recepcion de proyecto                       | Proyectos                    | fecha_recepcion_proyecto          | proyecto   | no          | no        |
| remision_proyecto_ayto         | Remision de proyecto al ayuntamiento        | Proyectos                    | fecha_remision_ayto               | proyecto   | no          | no        |
| aprobacion_proyecto_ayto       | Aprobacion de proyecto por ayuntamiento     | Proyectos                    | fecha_aprobacion_ayto             | proyecto   | no          | no        |
| pet_rectificacion_proyecto     | Peticion de rectificacion de proyecto       | Proyectos                    | fecha_pet_rectificacion           | proyecto   | no          | no        |
| entrega_rectificacion_proyecto | Entrega de rectificacion de proyecto        | Proyectos                    | fecha_ent_rectificacion           | proyecto   | no          | no        |
| pet_reforma_proyecto           | Peticion de reforma de proyecto             | Proyectos                    | fecha_pet_reforma                 | proyecto   | no          | no        |
| entrega_reforma_proyecto       | Entrega de reforma de proyecto              | Proyectos                    | fecha_ent_reforma                 | proyecto   | no          | no        |
| comision_informes_proyecto     | Comision de informes de proyecto            | Proyectos                    | fecha_c_infor                     | proyecto   | no          | no        |
| comision_gobierno_proyecto     | Comision de gobierno de proyecto            | Proyectos                    | fecha_c_gob                       | proyecto   | no          | no        |
| pit_ref_proyecto               | PIT referencia proyecto                     | Proyectos                    | fecha_pit_ref                     | proyecto   | no          | no        |
| eit_ref_proyecto               | EIT referencia proyecto                     | Proyectos                    | fecha_eit_ref                     | proyecto   | no          | no        |
| ci_ref_proyecto                | CI referencia proyecto                      | Proyectos                    | fecha_ci_ref                      | proyecto   | no          | no        |
| cg_ref_proyecto                | CG referencia proyecto                      | Proyectos                    | fecha_cg_ref                      | proyecto   | no          | no        |
| dto_proyecto                   | Fecha DTO de proyecto                       | Proyectos                    | fecha_dto                         | proyecto   | no          | no        |
| acta_replanteo_inicio          | Inicio del acta de replanteo                | ActasDeReplanteo             | Fecha_Inicio_Acta_Replanteo       | ejecucion  | si          | no        |
| acta_replanteo_fin             | Fin del acta de replanteo                   | ActasDeReplanteo             | Fecha_Final_Acta_Replanteo        | ejecucion  | no          | no        |
| acta_replanteo_prorroga        | Prorroga del acta de replanteo              | ActasDeReplanteo             | Fecha_Prorroga_Acta_Replanteo     | ejecucion  | no          | si        |
| cert_documento                 | Fecha del documento de certificacion        | CertificacionesDeObras       | fecha_documento                   | ejecucion  | no          | si        |
| cert_firma_contratista         | Fecha de firma de contratista               | CertificacionesDeObras       | fecha_firma_cont                  | ejecucion  | no          | si        |
| cert_factura                   | Fecha de factura de certificacion           | CertificacionesDeObras       | Fecha_Fact                        | ejecucion  | no          | si        |
| cert_envio_admin               | Fecha de envio a administracion             | CertificacionesDeObras       | fecha_EnvioAdmin                  | ejecucion  | no          | si        |
| cert_revision_admin            | Fecha de revision administrativa            | CertificacionesDeObras       | fecha_admin                       | ejecucion  | no          | si        |
| cert_devolucion                | Fecha de devolucion de certificacion        | CertificacionesDeObras       | fecha_devolucion                  | ejecucion  | no          | si        |
| cert_rectificacion             | Fecha de rectificacion de certificacion     | CertificacionesDeObras       | fecha_rectificacion               | ejecucion  | no          | si        |
| cert_envio_dipu                | Fecha de envio a diputacion                 | CertificacionesDeObras       | fecha_env_dipu                    | ejecucion  | no          | si        |
| cert_envio_secretaria          | Fecha de envio a secretaria                 | CertificacionesDeObras       | fecha_env_secret                  | ejecucion  | no          | si        |
| cert_recepcion                 | Fecha de recepcion de certificacion         | CertificacionesDeObras       | fecha_recepcion                   | ejecucion  | no          | si        |
| cert_propuesta                 | Fecha de propuesta de certificacion         | CertificacionesDeObras       | fecha_propuesta                   | ejecucion  | no          | si        |
| cert_decreto                   | Fecha de decreto de certificacion           | CertificacionesDeObras       | fecha_decreto                     | ejecucion  | no          | si        |
| cert_devuelta_para             | Fecha devuelta para subsanacion             | CertificacionesDeObras       | fecha_DevoPara                    | ejecucion  | no          | si        |
| cert_recepcion_rectif          | Fecha recepcion de rectificacion            | CertificacionesDeObras       | fecha_RecepRectif                 | ejecucion  | no          | si        |
| acta_recepcion_provisional     | Fecha acta de recepcion provisional         | ActasRecepcionObra           | Fecha_Acta_RecProv                | recepcion  | si          | no        |
| acta_recepcion_definitiva      | Fecha acta de recepcion                     | ActasRecepcionObra           | Fecha_Acta_Rec                    | recepcion  | no          | no        |
| recepcion_com_inf_1            | Fecha comunicacion informes 1               | ActasRecepcionObra           | Fecha_Com_Inf                     | recepcion  | no          | no        |
| recepcion_edicto_boe           | Fecha edicto BOE                            | ActasRecepcionObra           | Fecha_Edicto_BOE                  | recepcion  | no          | no        |
| recepcion_boe                  | Fecha BOE                                   | ActasRecepcionObra           | Fecha_BOE                         | recepcion  | no          | no        |
| recepcion_certif_no_reclam     | Fecha certificado sin reclamacion           | ActasRecepcionObra           | Fecha_Certif_NO_Reclam            | recepcion  | no          | no        |
| recepcion_com_inf_2            | Fecha comunicacion informes 2               | ActasRecepcionObra           | Fecha_Com_Inf_2                   | recepcion  | no          | no        |
| recepcion_com_gob              | Fecha comunicacion a gobierno               | ActasRecepcionObra           | Fecha_Com_Gob                     | recepcion  | no          | no        |
| recepcion_com_contratista      | Fecha comunicacion a contratista            | ActasRecepcionObra           | Fecha_Comun_Contrat               | recepcion  | no          | no        |
| recepcion_certif_liquidacion   | Fecha certificado de liquidacion            | ActasRecepcionObra           | Fecha_Certif_Liquid               | recepcion  | no          | no        |
| recepcion_remision_interv      | Fecha remision a intervencion               | ActasRecepcionObra           | Fecha_Rem_Interv                  | recepcion  | no          | no        |
| recepcion_remision_map         | Fecha remision MAP                          | ActasRecepcionObra           | Fecha_Rem_MAP                     | recepcion  | no          | no        |
| recepcion_paralizacion         | Fecha de paralizacion temporal              | ActasRecepcionObra           | Fecha_Paralizacion_Temporal       | recepcion  | no          | si        |
| recepcion_aprob_paralizacion   | Fecha aprobacion paralizacion               | ActasRecepcionObra           | Fecha_Aprob_Paralizacion_Temporal | recepcion  | no          | si        |
| recepcion_inicio_paralizacion  | Fecha inicio paralizacion                   | ActasRecepcionObra           | Fecha_Inicio_Paralizacion         | recepcion  | no          | si        |
| recepcion_fin_paralizacion     | Fecha fin paralizacion                      | ActasRecepcionObra           | Fecha_Final_Paralizacion          | recepcion  | no          | si        |
| recepcion_aviso_fin            | Fecha aviso finalizacion                    | ActasRecepcionObra           | Fecha_Aviso_Finalizacion          | recepcion  | no          | no        |
| recepcion_aviso_fin_map        | Fecha aviso finalizacion MAP                | ActasRecepcionObra           | Fecha_Aviso_FinalizacionMAP       | recepcion  | no          | no        |
| recepcion_medicion             | Fecha de medicion                           | ActasRecepcionObra           | Fecha_Medicion                    | recepcion  | no          | no        |
| cesion_remision_ayto           | Fecha remision ayuntamiento para cesion     | ObrasCedidas                 | FechaRemisionAyto                 | cesion     | no          | no        |
| cesion_recepcion_certi         | Fecha recepcion certificacion para cesion   | ObrasCedidas                 | FechaRecepcionCerti               | cesion     | no          | no        |
| cesion_fecha                   | Fecha de cesion                             | ObrasCedidas                 | FechaCesion                       | cesion     | si          | no        |
| cesion_adjudicacion            | Fecha de adjudicacion en cesion             | ObrasCedidas                 | FechaAdjudicacion                 | cesion     | no          | no        |
| cesion_contrato                | Fecha de contrato en cesion                 | ObrasCedidas                 | FechaContrato                     | cesion     | no          | no        |
| cesion_remision_interv         | Fecha de remision a intervencion en cesion  | ObrasCedidas                 | FechaRemisionInterv               | cesion     | no          | no        |
| doc_incorporacion              | Fecha de incorporacion de documento         | dbo.documentacionexpedientes | fechaincorporacion                | documental | no          | si        |
| doc_help                       | Fecha help del documento                    | dbo.documentacionexpedientes | fechaHelp                         | documental | no          | si        |

## Reglas recomendadas para arranque

1. `fecha_pet_acta_replanteo <= Fecha_Inicio_Acta_Replanteo`
2. `Fecha_Inicio_Acta_Replanteo <= Fecha_Final_Acta_Replanteo`
3. `Fecha_Acta_RecProv <= Fecha_Acta_Rec`
4. `fecha_entrega_proyecto <= fecha_recepcion_proyecto`
5. `fecha_remision_ayto <= fecha_aprobacion_ayto`
6. `fecha_envio_fiscalizacion <= fecha_fiscalizacion`
7. `FechaRemisionAyto <= FechaCesion`
8. `fecha_documento <= fecha_recepcion` (por certificacion)

## Integracion con reglas y mensajes

En `date_validation_rules` se recomienda:

1. mantener un `mensaje` base
2. definir `mensaje_preventivo` para alertas antes de vencimiento
3. definir `mensaje_cumplida` para feedback positivo
4. definir `mensaje_incumplida` para incidencias

De este modo la comunicacion se adapta al estado real de la regla.

## Operativa de mantenimiento

1. editar `hitos_catalogo` en config
2. ejecutar `php artisan date-hitos:sincronizar`
3. revisar altas/cambios en `fecha_hitos_catalogo`
4. validar muestra en `expediente_fecha_hitos`

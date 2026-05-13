-- ============================================
-- Script de copia selectiva de datos
-- De: GUADIX.OBRAS (PRODUCCION)
-- A: NAYADE.OBRAS_TEST (TEST)
-- ============================================
-- Este script solo copia columnas que existen en AMBAS bases de datos
-- Las columnas nuevas en TEST mantendran sus valores por defecto o NULL
-- ============================================

USE [OBRAS_TEST];
GO

-- ============================================
-- Tabla: AutorizacionesProyectos
-- Columnas comunes: 9
-- ============================================
PRINT 'Copiando datos de tabla: AutorizacionesProyectos';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[AutorizacionesProyectos];
GO

INSERT INTO [dbo].[AutorizacionesProyectos] ([CODIGO_MUNICIPIO], [AO_PROYECTO], [NUMERO_PROYECTO], [NumAuto], [FechaSolicitud], [FechaAuto], [CodOrg], [NumDel], [Autorizado])
SELECT [CODIGO_MUNICIPIO], [AO_PROYECTO], [NUMERO_PROYECTO], [NumAuto], [FechaSolicitud], [FechaAuto], [CodOrg], [NumDel], [Autorizado]
FROM [GUADIX].[OBRAS].[dbo].[AutorizacionesProyectos];
GO

DECLARE @rowcountAutorizacionesProyectos INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla AutorizacionesProyectos' + ': ' + CAST(@rowcountAutorizacionesProyectos AS VARCHAR(10));
GO

-- ============================================
-- Tabla: Avisos
-- Columnas comunes: 9
-- ============================================
PRINT 'Copiando datos de tabla: Avisos';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[Avisos];
GO

INSERT INTO [dbo].[Avisos] ([Referencia], [TipoAviso], [Codigo_Plan], [numero_obra], [subreferencia], [ao_ejecucion], [Usuario], [FecSolucion], [borrado])
SELECT [Referencia], [TipoAviso], [Codigo_Plan], [numero_obra], [subreferencia], [ao_ejecucion], [Usuario], [FecSolucion], [borrado]
FROM [GUADIX].[OBRAS].[dbo].[Avisos];
GO

DECLARE @rowcountAvisos INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla Avisos' + ': ' + CAST(@rowcountAvisos AS VARCHAR(10));
GO

-- ============================================
-- Tabla: Ayuda_Tecnica
-- Columnas comunes: 18
-- ============================================
PRINT 'Copiando datos de tabla: Ayuda_Tecnica';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[Ayuda_Tecnica];
GO

INSERT INTO [dbo].[Ayuda_Tecnica] ([Codigo_Plan], [numero_obra], [subreferencia], [ao_ejecucion], [departamento], [codigo_municipio], [ao_proyecto], [numero_proyecto], [dpto_redactor], [departamento_direccion], [pasado], [SubvencionEconomicaR], [SubvencionEconomicaD], [AyuTecRed], [AyuTecDir], [team_id], [created_at], [updated_at])
SELECT [Codigo_Plan], [numero_obra], [subreferencia], [ao_ejecucion], [departamento], [codigo_municipio], [ao_proyecto], [numero_proyecto], [dpto_redactor], [departamento_direccion], [pasado], [SubvencionEconomicaR], [SubvencionEconomicaD], [AyuTecRed], [AyuTecDir], [team_id], [created_at], [updated_at]
FROM [GUADIX].[OBRAS].[dbo].[Ayuda_Tecnica];
GO

DECLARE @rowcountAyuda_Tecnica INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla Ayuda_Tecnica' + ': ' + CAST(@rowcountAyuda_Tecnica AS VARCHAR(10));
GO

-- ============================================
-- Tabla: CambiosDestino
-- Columnas comunes: 6
-- ============================================
PRINT 'Copiando datos de tabla: CambiosDestino';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[CambiosDestino];
GO

INSERT INTO [dbo].[CambiosDestino] ([AoCambio], [NumCambio], [MotivoCambio], [FechaInicio], [FechaAprob], [NumDecreto])
SELECT [AoCambio], [NumCambio], [MotivoCambio], [FechaInicio], [FechaAprob], [NumDecreto]
FROM [GUADIX].[OBRAS].[dbo].[CambiosDestino];
GO

DECLARE @rowcountCambiosDestino INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla CambiosDestino' + ': ' + CAST(@rowcountCambiosDestino AS VARCHAR(10));
GO

-- ============================================
-- Tabla: CambiosDestinoAltas
-- Columnas comunes: 6
-- ============================================
PRINT 'Copiando datos de tabla: CambiosDestinoAltas';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[CambiosDestinoAltas];
GO

INSERT INTO [dbo].[CambiosDestinoAltas] ([AoCambio], [NumCambio], [PlanObra], [NumObra], [Subref], [AoPlan])
SELECT [AoCambio], [NumCambio], [PlanObra], [NumObra], [Subref], [AoPlan]
FROM [GUADIX].[OBRAS].[dbo].[CambiosDestinoAltas];
GO

DECLARE @rowcountCambiosDestinoAltas INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla CambiosDestinoAltas' + ': ' + CAST(@rowcountCambiosDestinoAltas AS VARCHAR(10));
GO

-- ============================================
-- Tabla: CambiosDestinoBajas
-- Columnas comunes: 6
-- ============================================
PRINT 'Copiando datos de tabla: CambiosDestinoBajas';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[CambiosDestinoBajas];
GO

INSERT INTO [dbo].[CambiosDestinoBajas] ([AoCambio], [NumCambio], [PlanObra], [NumObra], [Subref], [AoPlan])
SELECT [AoCambio], [NumCambio], [PlanObra], [NumObra], [Subref], [AoPlan]
FROM [GUADIX].[OBRAS].[dbo].[CambiosDestinoBajas];
GO

DECLARE @rowcountCambiosDestinoBajas INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla CambiosDestinoBajas' + ': ' + CAST(@rowcountCambiosDestinoBajas AS VARCHAR(10));
GO

-- ============================================
-- Tabla: CertificacionesDeObras
-- Columnas comunes: 44
-- ============================================
PRINT 'Copiando datos de tabla: CertificacionesDeObras';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[CertificacionesDeObras];
GO

INSERT INTO [dbo].[CertificacionesDeObras] ([Codigo_plan], [Numero_obra], [Subreferencia], [ao_ejecucion], [numero_certificacion], [tipo_justificante], [importe_certificacion_Pts], [mes_certificacion], [ao_certificacion], [fecha_documento], [fecha_firma_cont], [Numero_fact], [Fecha_Fact], [fecha_EnvioAdmin], [fecha_admin], [fecha_devolucion], [fecha_rectificacion], [fecha_env_dipu], [fecha_env_secret], [partida_presup], [fecha_recepcion], [fecha_propuesta], [numero_propuesta], [fecha_decreto], [numero_decreto], [tipo_aprobacion], [ultima_certif], [estado_certif], [ImporteDescontadoPenalidades_Pts], [NumSecProrConPenal], [Observaciones], [importe_certificacion], [importe_certificacion_sinIVA], [importe_certificacion_IVA], [porcentajeIVA], [importe_certificacion_ajusteIVA], [ImporteDescontadoPenalidades], [fecha_DevoPara], [fecha_RecepRectif], [ImpCertAdjudicado], [ImpCertModificado], [CSV], [CSVC], [cert_final])
SELECT [Codigo_plan], [Numero_obra], [Subreferencia], [ao_ejecucion], [numero_certificacion], [tipo_justificante], [importe_certificacion_Pts], [mes_certificacion], [ao_certificacion], [fecha_documento], [fecha_firma_cont], [Numero_fact], [Fecha_Fact], [fecha_EnvioAdmin], [fecha_admin], [fecha_devolucion], [fecha_rectificacion], [fecha_env_dipu], [fecha_env_secret], [partida_presup], [fecha_recepcion], [fecha_propuesta], [numero_propuesta], [fecha_decreto], [numero_decreto], [tipo_aprobacion], [ultima_certif], [estado_certif], [ImporteDescontadoPenalidades_Pts], [NumSecProrConPenal], [Observaciones], [importe_certificacion], [importe_certificacion_sinIVA], [importe_certificacion_IVA], [porcentajeIVA], [importe_certificacion_ajusteIVA], [ImporteDescontadoPenalidades], [fecha_DevoPara], [fecha_RecepRectif], [ImpCertAdjudicado], [ImpCertModificado], [CSV], [CSVC], [cert_final]
FROM [GUADIX].[OBRAS].[dbo].[CertificacionesDeObras];
GO

DECLARE @rowcountCertificacionesDeObras INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla CertificacionesDeObras' + ': ' + CAST(@rowcountCertificacionesDeObras AS VARCHAR(10));
GO

-- ============================================
-- Tabla: ClasificacionProyectos
-- Columnas comunes: 115
-- ============================================
PRINT 'Copiando datos de tabla: ClasificacionProyectos';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[ClasificacionProyectos];
GO

INSERT INTO [dbo].[ClasificacionProyectos] ([MUNICIPIO], [AO_PROYECTO], [NUMERO_PROYECTO], [NUMERO_FASE], [AO_FASE], [A0D], [A1D], [A2D], [A3D], [A4D], [A5D], [A6D], [A7D], [A8D], [A9D], [B0D], [B1D], [B2D], [B3D], [B4D], [B5D], [B6D], [B7D], [B8D], [B9D], [C0D], [C1D], [C2D], [C3D], [C4D], [C5D], [C6D], [C7D], [C8D], [C9D], [D0D], [D1D], [D2D], [D3D], [D4D], [D5D], [D6D], [D7D], [D8D], [D9D], [E0D], [E1D], [E2D], [E3D], [E4D], [E5D], [E6D], [E7D], [E8D], [E9D], [F0D], [F1D], [F2D], [F3D], [F4D], [F5D], [F6D], [F7D], [F8D], [F9D], [G0D], [G1D], [G2D], [G3D], [G4D], [G5D], [G6D], [G7D], [G8D], [G9D], [H0D], [H1D], [H2D], [H3D], [H4D], [H5D], [H6D], [H7D], [H8D], [H9D], [I0D], [I1D], [I2D], [I3D], [I4D], [I5D], [I6D], [I7D], [I8D], [I9D], [J0D], [J1D], [J2D], [J3D], [J4D], [J5D], [J6D], [J7D], [J8D], [J9D], [K0D], [K1D], [K2D], [K3D], [K4D], [K5D], [K6D], [K7D], [K8D], [K9D])
SELECT [MUNICIPIO], [AO_PROYECTO], [NUMERO_PROYECTO], [NUMERO_FASE], [AO_FASE], [A0D], [A1D], [A2D], [A3D], [A4D], [A5D], [A6D], [A7D], [A8D], [A9D], [B0D], [B1D], [B2D], [B3D], [B4D], [B5D], [B6D], [B7D], [B8D], [B9D], [C0D], [C1D], [C2D], [C3D], [C4D], [C5D], [C6D], [C7D], [C8D], [C9D], [D0D], [D1D], [D2D], [D3D], [D4D], [D5D], [D6D], [D7D], [D8D], [D9D], [E0D], [E1D], [E2D], [E3D], [E4D], [E5D], [E6D], [E7D], [E8D], [E9D], [F0D], [F1D], [F2D], [F3D], [F4D], [F5D], [F6D], [F7D], [F8D], [F9D], [G0D], [G1D], [G2D], [G3D], [G4D], [G5D], [G6D], [G7D], [G8D], [G9D], [H0D], [H1D], [H2D], [H3D], [H4D], [H5D], [H6D], [H7D], [H8D], [H9D], [I0D], [I1D], [I2D], [I3D], [I4D], [I5D], [I6D], [I7D], [I8D], [I9D], [J0D], [J1D], [J2D], [J3D], [J4D], [J5D], [J6D], [J7D], [J8D], [J9D], [K0D], [K1D], [K2D], [K3D], [K4D], [K5D], [K6D], [K7D], [K8D], [K9D]
FROM [GUADIX].[OBRAS].[dbo].[ClasificacionProyectos];
GO

DECLARE @rowcountClasificacionProyectos INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla ClasificacionProyectos' + ': ' + CAST(@rowcountClasificacionProyectos AS VARCHAR(10));
GO

-- ============================================
-- Tabla: CodigosProyectosModificados
-- Columnas comunes: 7
-- ============================================
PRINT 'Copiando datos de tabla: CodigosProyectosModificados';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[CodigosProyectosModificados];
GO

INSERT INTO [dbo].[CodigosProyectosModificados] ([CodMunicipioNue], [AoProyectoNue], [NumProyectoNue], [departamento], [codigo_municipio], [ao_proyecto], [numero_proyecto])
SELECT [CodMunicipioNue], [AoProyectoNue], [NumProyectoNue], [departamento], [codigo_municipio], [ao_proyecto], [numero_proyecto]
FROM [GUADIX].[OBRAS].[dbo].[CodigosProyectosModificados];
GO

DECLARE @rowcountCodigosProyectosModificados INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla CodigosProyectosModificados' + ': ' + CAST(@rowcountCodigosProyectosModificados AS VARCHAR(10));
GO

-- ============================================
-- Tabla: Datos_Ejecucion_Obras
-- Columnas comunes: 43
-- ============================================
PRINT 'Copiando datos de tabla: Datos_Ejecucion_Obras';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[Datos_Ejecucion_Obras];
GO

INSERT INTO [dbo].[Datos_Ejecucion_Obras] ([Codigo_Plan], [numero_obra], [subreferencia], [ao_ejecucion], [Fecha_Inicio_Acta_Replanteo], [Fecha_Final_Acta_Replanteo], [Fecha_Prorroga_Acta_Replanteo], [Indicador_Impresion_AR], [Indicador_Recepcion_AR], [TipoActaRecepcion], [Fecha_Acta_RecProv], [Lugar_Acta_Rec], [Fecha_Com_Inf], [Fecha_Edicto_BOE], [Fecha_BOE], [Num_BOE], [Plazo_Reclam], [Fecha_Certif_NO_Reclam], [Fecha_Com_Inf_2], [Fecha_Com_Gob], [Fecha_Comun_Contrat], [Fecha_Certif_Liquid], [Fecha_Rem_Interv], [Fecha_Rem_MAP], [Admin_ActaRecepcion], [Dir_ActaRecepcion], [Alcalde_ActaRecepcion], [Cont_ActaRecepcion], [Interv_ActaRecepcion], [Dipu_ActaRecepcion], [Texto], [Fecha_Paralizacion_Temporal], [Motivo_Paralizacion], [Fecha_Aprob_Paralizacion_Temporal], [Fecha_Inicio_Paralizacion], [Fecha_Final_Paralizacion], [Fecha_Acta_Rec], [Fecha_Aviso_Finalizacion], [Fecha_Aviso_FinalizacionMAP], [Fecha_Medicion], [team_id], [created_at], [updated_at])
SELECT [Codigo_Plan], [numero_obra], [subreferencia], [ao_ejecucion], [Fecha_Inicio_Acta_Replanteo], [Fecha_Final_Acta_Replanteo], [Fecha_Prorroga_Acta_Replanteo], [Indicador_Impresion_AR], [Indicador_Recepcion_AR], [TipoActaRecepcion], [Fecha_Acta_RecProv], [Lugar_Acta_Rec], [Fecha_Com_Inf], [Fecha_Edicto_BOE], [Fecha_BOE], [Num_BOE], [Plazo_Reclam], [Fecha_Certif_NO_Reclam], [Fecha_Com_Inf_2], [Fecha_Com_Gob], [Fecha_Comun_Contrat], [Fecha_Certif_Liquid], [Fecha_Rem_Interv], [Fecha_Rem_MAP], [Admin_ActaRecepcion], [Dir_ActaRecepcion], [Alcalde_ActaRecepcion], [Cont_ActaRecepcion], [Interv_ActaRecepcion], [Dipu_ActaRecepcion], [Texto], [Fecha_Paralizacion_Temporal], [Motivo_Paralizacion], [Fecha_Aprob_Paralizacion_Temporal], [Fecha_Inicio_Paralizacion], [Fecha_Final_Paralizacion], [Fecha_Acta_Rec], [Fecha_Aviso_Finalizacion], [Fecha_Aviso_FinalizacionMAP], [Fecha_Medicion], [team_id], [created_at], [updated_at]
FROM [GUADIX].[OBRAS].[dbo].[Datos_Ejecucion_Obras];
GO

DECLARE @rowcountDatos_Ejecucion_Obras INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla Datos_Ejecucion_Obras' + ': ' + CAST(@rowcountDatos_Ejecucion_Obras AS VARCHAR(10));
GO

-- ============================================
-- Tabla: Datos_Estadisticos
-- Columnas comunes: 28
-- ============================================
PRINT 'Copiando datos de tabla: Datos_Estadisticos';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[Datos_Estadisticos];
GO

INSERT INTO [dbo].[Datos_Estadisticos] ([NombreSeleccion], [DptoCreador], [AgComarca], [AgZona], [AgMunicipio], [AgCarretera], [AgGPlan], [AgPlan], [AgAo], [AgEstado], [SeComarca], [Sezona], [SeMunicipio], [SeCarretera], [SeGPlan], [SePlan], [SeAo], [SeAo2], [SeEstado], [CaImpAprobado], [CaImpContratar], [CaImpAdjudicado], [CaImpBaja], [CaImpEjecutado], [Porcentaje], [Organismo], [Permiso], [TituloSeleccion])
SELECT [NombreSeleccion], [DptoCreador], [AgComarca], [AgZona], [AgMunicipio], [AgCarretera], [AgGPlan], [AgPlan], [AgAo], [AgEstado], [SeComarca], [Sezona], [SeMunicipio], [SeCarretera], [SeGPlan], [SePlan], [SeAo], [SeAo2], [SeEstado], [CaImpAprobado], [CaImpContratar], [CaImpAdjudicado], [CaImpBaja], [CaImpEjecutado], [Porcentaje], [Organismo], [Permiso], [TituloSeleccion]
FROM [GUADIX].[OBRAS].[dbo].[Datos_Estadisticos];
GO

DECLARE @rowcountDatos_Estadisticos INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla Datos_Estadisticos' + ': ' + CAST(@rowcountDatos_Estadisticos AS VARCHAR(10));
GO

-- ============================================
-- Tabla: Datosadicionales
-- Columnas comunes: 24
-- ============================================
PRINT 'Copiando datos de tabla: Datosadicionales';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[Datosadicionales];
GO

INSERT INTO [dbo].[Datosadicionales] ([Codigo_Plan], [numero_obra], [subreferencia], [ao_ejecucion], [tipo_movimiento], [numero_movimiento], [Importe_Pts], [Partida], [FechaEmision], [FechaEnvio], [FechaFiscalizacion], [FechaInformativa], [PuntoInformativa], [FechaComision], [PuntoComision], [FechaDecreto], [NumDecreto], [Observaciones], [NumCertificacion], [Importe], [Estado], [team_id], [created_at], [updated_at])
SELECT [Codigo_Plan], [numero_obra], [subreferencia], [ao_ejecucion], [tipo_movimiento], [numero_movimiento], [Importe_Pts], [Partida], [FechaEmision], [FechaEnvio], [FechaFiscalizacion], [FechaInformativa], [PuntoInformativa], [FechaComision], [PuntoComision], [FechaDecreto], [NumDecreto], [Observaciones], [NumCertificacion], [Importe], [Estado], [team_id], [created_at], [updated_at]
FROM [GUADIX].[OBRAS].[dbo].[Datosadicionales];
GO

DECLARE @rowcountDatosadicionales INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla Datosadicionales' + ': ' + CAST(@rowcountDatosadicionales AS VARCHAR(10));
GO

-- ============================================
-- Tabla: DatosInicioDeObras
-- Columnas comunes: 48
-- ============================================
PRINT 'Copiando datos de tabla: DatosInicioDeObras';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[DatosInicioDeObras];
GO

INSERT INTO [dbo].[DatosInicioDeObras] ([Codigo_Plan], [numero_obra], [subreferencia], [ao_ejecucion], [nombre_obra1], [nombre_obra2], [nombre_obra3], [municipio], [carretera], [disponibilidad_terreno], [fecha_pet_acta_replanteo], [fecha_acta_replanteo_previo], [fecha_notificacion_ayto], [peticion_ayuda_tec], [fecha_rem_pet_ayuda], [fecha_rec_pet_ayuda], [forma_ejecucion], [fecha_prev_comienzo_obra], [fecha_prev_term_obra], [fecha_prev_prorroga], [fecha_aprobacion_plan], [fecha_envio_fiscalizacion], [fecha_fiscalizacion], [codigo_estado_obra], [comentario], [NumCertif], [NumLiquid], [CompApAyto], [TipoObra], [FechaMediosMatAyto], [PartidaPresupuesto], [Mapper], [LicenciaObra], [Clasificacion], [TipoActuacion], [FechaComPatronato], [Aceptacion], [FechaIngresoAyto], [Longitud], [NombreObraNueva], [EstadoServicioTecnico], [EstadoServicioAdministrativo], [FechaEntregaPrev], [TipoPrograma], [IdConcertacion], [team_id], [created_at], [updated_at])
SELECT [Codigo_Plan], [numero_obra], [subreferencia], [ao_ejecucion], [nombre_obra1], [nombre_obra2], [nombre_obra3], [municipio], [carretera], [disponibilidad_terreno], [fecha_pet_acta_replanteo], [fecha_acta_replanteo_previo], [fecha_notificacion_ayto], [peticion_ayuda_tec], [fecha_rem_pet_ayuda], [fecha_rec_pet_ayuda], [forma_ejecucion], [fecha_prev_comienzo_obra], [fecha_prev_term_obra], [fecha_prev_prorroga], [fecha_aprobacion_plan], [fecha_envio_fiscalizacion], [fecha_fiscalizacion], [codigo_estado_obra], [comentario], [NumCertif], [NumLiquid], [CompApAyto], [TipoObra], [FechaMediosMatAyto], [PartidaPresupuesto], [Mapper], [LicenciaObra], [Clasificacion], [TipoActuacion], [FechaComPatronato], [Aceptacion], [FechaIngresoAyto], [Longitud], [NombreObraNueva], [EstadoServicioTecnico], [EstadoServicioAdministrativo], [FechaEntregaPrev], [TipoPrograma], [IdConcertacion], [team_id], [created_at], [updated_at]
FROM [GUADIX].[OBRAS].[dbo].[DatosInicioDeObras];
GO

DECLARE @rowcountDatosInicioDeObras INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla DatosInicioDeObras' + ': ' + CAST(@rowcountDatosInicioDeObras AS VARCHAR(10));
GO

-- ============================================
-- Tabla: Decretos
-- Columnas comunes: 15
-- ============================================
PRINT 'Copiando datos de tabla: Decretos';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[Decretos];
GO

INSERT INTO [dbo].[Decretos] ([Codigo_Plan], [Numero_Obra], [SubReferencia], [Ao_Ejecucion], [AoContratacion], [TipoExpediente], [NumExpediente], [identificacion], [Estado], [FecEnvio], [HoraEnvio], [FecDecreto], [NumDecreto], [Servicio], [obra])
SELECT [Codigo_Plan], [Numero_Obra], [SubReferencia], [Ao_Ejecucion], [AoContratacion], [TipoExpediente], [NumExpediente], [identificacion], [Estado], [FecEnvio], [HoraEnvio], [FecDecreto], [NumDecreto], [Servicio], [obra]
FROM [GUADIX].[OBRAS].[dbo].[Decretos];
GO

DECLARE @rowcountDecretos INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla Decretos' + ': ' + CAST(@rowcountDecretos AS VARCHAR(10));
GO

-- ============================================
-- Tabla: DestinosDeDocumentos
-- Columnas comunes: 2
-- ============================================
PRINT 'Copiando datos de tabla: DestinosDeDocumentos';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[DestinosDeDocumentos];
GO

INSERT INTO [dbo].[DestinosDeDocumentos] ([id], [destino])
SELECT [id], [destino]
FROM [GUADIX].[OBRAS].[dbo].[DestinosDeDocumentos];
GO

DECLARE @rowcountDestinosDeDocumentos INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla DestinosDeDocumentos' + ': ' + CAST(@rowcountDestinosDeDocumentos AS VARCHAR(10));
GO

-- ============================================
-- Tabla: documentacionexpedientes
-- Columnas comunes: 16
-- ============================================
PRINT 'Copiando datos de tabla: documentacionexpedientes';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[documentacionexpedientes];
GO

INSERT INTO [dbo].[documentacionexpedientes] ([idDocumento], [referencia], [subreferencia], [ao_ejecucion], [fechaincorporacion], [fechaHelp], [csv], [nregistro], [nsecuencia], [estado], [descripcion], [team_id], [destino], [procedencia], [created_at], [updated_at])
SELECT [idDocumento], [referencia], [subreferencia], [ao_ejecucion], [fechaincorporacion], [fechaHelp], [csv], [nregistro], [nsecuencia], [estado], [descripcion], [team_id], [destino], [procedencia], [created_at], [updated_at]
FROM [GUADIX].[OBRAS].[dbo].[documentacionexpedientes];
GO

DECLARE @rowcountdocumentacionexpedientes INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla documentacionexpedientes' + ': ' + CAST(@rowcountdocumentacionexpedientes AS VARCHAR(10));
GO

-- ============================================
-- Tabla: documento_expedientes
-- Columnas comunes: 12
-- ============================================
PRINT 'Copiando datos de tabla: documento_expedientes';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[documento_expedientes];
GO

INSERT INTO [dbo].[documento_expedientes] ([id], [n_exp], [cod_documento], [fecha_incorporacion], [n_sec_doc], [csv], [descripcion], [destino], [procedencia], [created_at], [updated_at], [team_id])
SELECT [id], [n_exp], [cod_documento], [fecha_incorporacion], [n_sec_doc], [csv], [descripcion], [destino], [procedencia], [created_at], [updated_at], [team_id]
FROM [GUADIX].[OBRAS].[dbo].[documento_expedientes];
GO

DECLARE @rowcountdocumento_expedientes INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla documento_expedientes' + ': ' + CAST(@rowcountdocumento_expedientes AS VARCHAR(10));
GO

-- ============================================
-- Tabla: documento_genericos
-- Columnas comunes: 17
-- ============================================
PRINT 'Copiando datos de tabla: documento_genericos';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[documento_genericos];
GO

INSERT INTO [dbo].[documento_genericos] ([id], [created_at], [updated_at], [cod_documento], [nombre], [descripcion], [fase_doc], [fase_siguiente], [cod_tipo_doc], [con_plantilla], [plantilla], [ruta_plantilla], [cod_estado], [cod_destino], [entrada_salida], [cod_firmante], [obligatorio])
SELECT [id], [created_at], [updated_at], [cod_documento], [nombre], [descripcion], [fase_doc], [fase_siguiente], [cod_tipo_doc], [con_plantilla], [plantilla], [ruta_plantilla], [cod_estado], [cod_destino], [entrada_salida], [cod_firmante], [obligatorio]
FROM [GUADIX].[OBRAS].[dbo].[documento_genericos];
GO

DECLARE @rowcountdocumento_genericos INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla documento_genericos' + ': ' + CAST(@rowcountdocumento_genericos AS VARCHAR(10));
GO

-- ============================================
-- Tabla: Documentos_de_fases_de_proyectos
-- Columnas comunes: 12
-- ============================================
PRINT 'Copiando datos de tabla: Documentos_de_fases_de_proyectos';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[Documentos_de_fases_de_proyectos];
GO

INSERT INTO [dbo].[Documentos_de_fases_de_proyectos] ([codigo_plan], [numero_obra], [subreferencia], [ao_ejecucion], [cod_municipio], [ao_proyecto], [numero_proyecto], [ao_fase], [numero_fase], [documento], [csv], [numero_doc])
SELECT [codigo_plan], [numero_obra], [subreferencia], [ao_ejecucion], [cod_municipio], [ao_proyecto], [numero_proyecto], [ao_fase], [numero_fase], [documento], [csv], [numero_doc]
FROM [GUADIX].[OBRAS].[dbo].[Documentos_de_fases_de_proyectos];
GO

DECLARE @rowcountDocumentos_de_fases_de_proyectos INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla Documentos_de_fases_de_proyectos' + ': ' + CAST(@rowcountDocumentos_de_fases_de_proyectos AS VARCHAR(10));
GO

-- ============================================
-- Tabla: Documentos_de_fases_de_proyectos2
-- Columnas comunes: 12
-- ============================================
PRINT 'Copiando datos de tabla: Documentos_de_fases_de_proyectos2';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[Documentos_de_fases_de_proyectos2];
GO

INSERT INTO [dbo].[Documentos_de_fases_de_proyectos2] ([codigo_plan], [numero_obra], [subreferencia], [ao_ejecucion], [cod_municipio], [ao_proyecto], [numero_proyecto], [ao_fase], [numero_fase], [documento], [csv], [numero_doc])
SELECT [codigo_plan], [numero_obra], [subreferencia], [ao_ejecucion], [cod_municipio], [ao_proyecto], [numero_proyecto], [ao_fase], [numero_fase], [documento], [csv], [numero_doc]
FROM [GUADIX].[OBRAS].[dbo].[Documentos_de_fases_de_proyectos2];
GO

DECLARE @rowcountDocumentos_de_fases_de_proyectos2 INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla Documentos_de_fases_de_proyectos2' + ': ' + CAST(@rowcountDocumentos_de_fases_de_proyectos2 AS VARCHAR(10));
GO

-- ============================================
-- Tabla: Empresas_Estudio_Geo
-- Columnas comunes: 11
-- ============================================
PRINT 'Copiando datos de tabla: Empresas_Estudio_Geo';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[Empresas_Estudio_Geo];
GO

INSERT INTO [dbo].[Empresas_Estudio_Geo] ([EMPRESA], [DIRECCION], [MUNICIPIO], [CP], [TELEFONO], [FAX], [Repr# Legal], [Nombre], [Ape1], [Ape2], [Directo# Labo])
SELECT [EMPRESA], [DIRECCION], [MUNICIPIO], [CP], [TELEFONO], [FAX], [Repr# Legal], [Nombre], [Ape1], [Ape2], [Directo# Labo]
FROM [GUADIX].[OBRAS].[dbo].[Empresas_Estudio_Geo];
GO

DECLARE @rowcountEmpresas_Estudio_Geo INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla Empresas_Estudio_Geo' + ': ' + CAST(@rowcountEmpresas_Estudio_Geo AS VARCHAR(10));
GO

-- ============================================
-- Tabla: ExcluirOrganismos
-- Columnas comunes: 5
-- ============================================
PRINT 'Copiando datos de tabla: ExcluirOrganismos';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[ExcluirOrganismos];
GO

INSERT INTO [dbo].[ExcluirOrganismos] ([Codigo_Plan], [numero_obra], [subreferencia], [ao_ejecucion], [organismo])
SELECT [Codigo_Plan], [numero_obra], [subreferencia], [ao_ejecucion], [organismo]
FROM [GUADIX].[OBRAS].[dbo].[ExcluirOrganismos];
GO

DECLARE @rowcountExcluirOrganismos INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla ExcluirOrganismos' + ': ' + CAST(@rowcountExcluirOrganismos AS VARCHAR(10));
GO

-- ============================================
-- Tabla: expedientes
-- Columnas comunes: 11
-- ============================================
PRINT 'Copiando datos de tabla: expedientes';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[expedientes];
GO

INSERT INTO [dbo].[expedientes] ([id], [ao_ejecucion], [referencia], [subreferencia], [nombre_obra], [cod_estado], [cod_estado_help], [forma_ejecucion], [team_id], [created_at], [updated_at])
SELECT [id], [ao_ejecucion], [referencia], [subreferencia], [nombre_obra], [cod_estado], [cod_estado_help], [forma_ejecucion], [team_id], [created_at], [updated_at]
FROM [GUADIX].[OBRAS].[dbo].[expedientes];
GO

DECLARE @rowcountexpedientes INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla expedientes' + ': ' + CAST(@rowcountexpedientes AS VARCHAR(10));
GO

-- ============================================
-- Tabla: fase_documentos
-- Columnas comunes: 5
-- ============================================
PRINT 'Copiando datos de tabla: fase_documentos';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[fase_documentos];
GO

INSERT INTO [dbo].[fase_documentos] ([created_at], [updated_at], [cod_fase], [nombre], [descripcion])
SELECT [created_at], [updated_at], [cod_fase], [nombre], [descripcion]
FROM [GUADIX].[OBRAS].[dbo].[fase_documentos];
GO

DECLARE @rowcountfase_documentos INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla fase_documentos' + ': ' + CAST(@rowcountfase_documentos AS VARCHAR(10));
GO

-- ============================================
-- Tabla: FasesDeProyectos
-- Columnas comunes: 91
-- ============================================
PRINT 'Copiando datos de tabla: FasesDeProyectos';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[FasesDeProyectos];
GO

INSERT INTO [dbo].[FasesDeProyectos] ([MUNICIPIO], [AO_PROYECTO], [NUMERO_PROYECTO], [NUMERO_FASE], [AO_FASE], [servicio_gestor], [importe_fase_Pts], [Codigo_Plan], [referencia], [subreferencia], [ao_ejecucion_obra], [carretera], [plazo], [UnidadPlazo], [nro_ejemplares], [grupo], [subgrupo], [categoria], [revision], [formula], [formula2], [formula3], [formula4], [organismo_direccion], [servicio_direccion], [director_tecnico_obra], [ColegioOficialDireccion], [SubvencionEconDireccion], [RedactorPlanSS], [presu_gral_ejecucion_material_Pts], [por_gastos_generales], [importe_gastos_generales_Pts], [por_beneficio_industriales], [importe_beneficio_industriales_Pts], [por_control_calidad], [importe_control_calidad_Pts], [Por_iva], [iva_Pts], [por_subcontrata], [subcontrata_Pts], [honorarios_dir_Pts], [honorarios_red_Pts], [fecha_rem_fase], [fecha_ent_fase], [fecha_remision_ayto], [fecha_aprobacion_ayto], [fecha_remision_junta], [fecha_visado_junta], [fecha_pet_inf_tecnico_contrata], [fecha_ent_inf_tecnico_contrata], [pliego_clausulas_particulares], [fecha_pet_desglose], [fecha_ent_desglose], [fecha_pet_rectificacion], [fecha_ent_rectificacion], [fecha_pet_reforma], [fecha_ent_reforma], [fecha_pit_ref], [fecha_eit_ref], [fecha_ci_ref], [fecha_cg_ref], [fecha_pet_actual_precios], [fecha_ent_actual_precios], [fecha_envio_fiscalizacion], [fecha_com_inf], [fecha_com_gob], [Punto], [fecha_fiscalizacion], [fecha_remision_contratacion], [fecha_dto], [nro_dto], [estado_fase], [clase_exp], [tipo_proc], [forma_cont], [Requiere_PlanSyS], [importe_fase], [presu_gral_ejecucion_material], [importe_gastos_generales], [importe_beneficio_industriales], [importe_control_calidad], [iva], [subcontrata], [honorarios_dir], [HD_ExcluidoIVA], [honorarios_red], [HR_ExcluidoIVA], [importePlanSyS], [CriteriosAdjudicacion], [num_reg], [fec_reg])
SELECT [MUNICIPIO], [AO_PROYECTO], [NUMERO_PROYECTO], [NUMERO_FASE], [AO_FASE], [servicio_gestor], [importe_fase_Pts], [Codigo_Plan], [referencia], [subreferencia], [ao_ejecucion_obra], [carretera], [plazo], [UnidadPlazo], [nro_ejemplares], [grupo], [subgrupo], [categoria], [revision], [formula], [formula2], [formula3], [formula4], [organismo_direccion], [servicio_direccion], [director_tecnico_obra], [ColegioOficialDireccion], [SubvencionEconDireccion], [RedactorPlanSS], [presu_gral_ejecucion_material_Pts], [por_gastos_generales], [importe_gastos_generales_Pts], [por_beneficio_industriales], [importe_beneficio_industriales_Pts], [por_control_calidad], [importe_control_calidad_Pts], [Por_iva], [iva_Pts], [por_subcontrata], [subcontrata_Pts], [honorarios_dir_Pts], [honorarios_red_Pts], [fecha_rem_fase], [fecha_ent_fase], [fecha_remision_ayto], [fecha_aprobacion_ayto], [fecha_remision_junta], [fecha_visado_junta], [fecha_pet_inf_tecnico_contrata], [fecha_ent_inf_tecnico_contrata], [pliego_clausulas_particulares], [fecha_pet_desglose], [fecha_ent_desglose], [fecha_pet_rectificacion], [fecha_ent_rectificacion], [fecha_pet_reforma], [fecha_ent_reforma], [fecha_pit_ref], [fecha_eit_ref], [fecha_ci_ref], [fecha_cg_ref], [fecha_pet_actual_precios], [fecha_ent_actual_precios], [fecha_envio_fiscalizacion], [fecha_com_inf], [fecha_com_gob], [Punto], [fecha_fiscalizacion], [fecha_remision_contratacion], [fecha_dto], [nro_dto], [estado_fase], [clase_exp], [tipo_proc], [forma_cont], [Requiere_PlanSyS], [importe_fase], [presu_gral_ejecucion_material], [importe_gastos_generales], [importe_beneficio_industriales], [importe_control_calidad], [iva], [subcontrata], [honorarios_dir], [HD_ExcluidoIVA], [honorarios_red], [HR_ExcluidoIVA], [importePlanSyS], [CriteriosAdjudicacion], [num_reg], [fec_reg]
FROM [GUADIX].[OBRAS].[dbo].[FasesDeProyectos];
GO

DECLARE @rowcountFasesDeProyectos INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla FasesDeProyectos' + ': ' + CAST(@rowcountFasesDeProyectos AS VARCHAR(10));
GO

-- ============================================
-- Tabla: firmante_documentos
-- Columnas comunes: 8
-- ============================================
PRINT 'Copiando datos de tabla: firmante_documentos';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[firmante_documentos];
GO

INSERT INTO [dbo].[firmante_documentos] ([id], [created_at], [updated_at], [id_doc], [id_firmante], [tipo_firmante], [n_exp], [fecha_firma])
SELECT [id], [created_at], [updated_at], [id_doc], [id_firmante], [tipo_firmante], [n_exp], [fecha_firma]
FROM [GUADIX].[OBRAS].[dbo].[firmante_documentos];
GO

DECLARE @rowcountfirmante_documentos INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla firmante_documentos' + ': ' + CAST(@rowcountfirmante_documentos AS VARCHAR(10));
GO

-- ============================================
-- Tabla: firmante_genericos
-- Columnas comunes: 7
-- ============================================
PRINT 'Copiando datos de tabla: firmante_genericos';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[firmante_genericos];
GO

INSERT INTO [dbo].[firmante_genericos] ([id], [created_at], [updated_at], [cod_firmante], [nombre], [descripcion], [codigo_dpto])
SELECT [id], [created_at], [updated_at], [cod_firmante], [nombre], [descripcion], [codigo_dpto]
FROM [GUADIX].[OBRAS].[dbo].[firmante_genericos];
GO

DECLARE @rowcountfirmante_genericos INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla firmante_genericos' + ': ' + CAST(@rowcountfirmante_genericos AS VARCHAR(10));
GO

-- ============================================
-- Tabla: firmantesdocumentosexpediente
-- Columnas comunes: 8
-- ============================================
PRINT 'Copiando datos de tabla: firmantesdocumentosexpediente';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[firmantesdocumentosexpediente];
GO

INSERT INTO [dbo].[firmantesdocumentosexpediente] ([IdDocumento], [idFirmante], [cod_plan], [referencia], [subreferencia], [ao_ejecuciion], [n_expediente], [fechafirma])
SELECT [IdDocumento], [idFirmante], [cod_plan], [referencia], [subreferencia], [ao_ejecuciion], [n_expediente], [fechafirma]
FROM [GUADIX].[OBRAS].[dbo].[firmantesdocumentosexpediente];
GO

DECLARE @rowcountfirmantesdocumentosexpediente INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla firmantesdocumentosexpediente' + ': ' + CAST(@rowcountfirmantesdocumentosexpediente AS VARCHAR(10));
GO

-- ============================================
-- Tabla: FormasDeEjecucion
-- Columnas comunes: 2
-- ============================================
PRINT 'Copiando datos de tabla: FormasDeEjecucion';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[FormasDeEjecucion];
GO

INSERT INTO [dbo].[FormasDeEjecucion] ([COD_CONTRATA], [DEN_CONTRATA])
SELECT [COD_CONTRATA], [DEN_CONTRATA]
FROM [GUADIX].[OBRAS].[dbo].[FormasDeEjecucion];
GO

DECLARE @rowcountFormasDeEjecucion INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla FormasDeEjecucion' + ': ' + CAST(@rowcountFormasDeEjecucion AS VARCHAR(10));
GO

-- ============================================
-- Tabla: FormulasRevision
-- Columnas comunes: 2
-- ============================================
PRINT 'Copiando datos de tabla: FormulasRevision';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[FormulasRevision];
GO

INSERT INTO [dbo].[FormulasRevision] ([NFormula], [TFormula])
SELECT [NFormula], [TFormula]
FROM [GUADIX].[OBRAS].[dbo].[FormulasRevision];
GO

DECLARE @rowcountFormulasRevision INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla FormulasRevision' + ': ' + CAST(@rowcountFormulasRevision AS VARCHAR(10));
GO

-- ============================================
-- Tabla: GruposClasificacion
-- Columnas comunes: 3
-- ============================================
PRINT 'Copiando datos de tabla: GruposClasificacion';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[GruposClasificacion];
GO

INSERT INTO [dbo].[GruposClasificacion] ([Grupo], [Maximo], [Descripción])
SELECT [Grupo], [Maximo], [Descripción]
FROM [GUADIX].[OBRAS].[dbo].[GruposClasificacion];
GO

DECLARE @rowcountGruposClasificacion INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla GruposClasificacion' + ': ' + CAST(@rowcountGruposClasificacion AS VARCHAR(10));
GO

-- ============================================
-- Tabla: GruposPlanes
-- Columnas comunes: 4
-- ============================================
PRINT 'Copiando datos de tabla: GruposPlanes';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[GruposPlanes];
GO

INSERT INTO [dbo].[GruposPlanes] ([codigo_plan], [denominacion_plan], [codigo_depar_reservado], [ultima_obra])
SELECT [codigo_plan], [denominacion_plan], [codigo_depar_reservado], [ultima_obra]
FROM [GUADIX].[OBRAS].[dbo].[GruposPlanes];
GO

DECLARE @rowcountGruposPlanes INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla GruposPlanes' + ': ' + CAST(@rowcountGruposPlanes AS VARCHAR(10));
GO

-- ============================================
-- Tabla: Honorarios
-- Columnas comunes: 20
-- ============================================
PRINT 'Copiando datos de tabla: Honorarios';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[Honorarios];
GO

INSERT INTO [dbo].[Honorarios] ([Codigo_Plan], [numero_obra], [subreferencia], [ao_ejecucion], [importe_dire_Pts], [A_quien_dire], [NumDecreto_dire], [FechaDecreto_dire], [NumActa_dire], [FechaComision_dire], [Observaciones_dire], [importe_reda_Pts], [A_quien_reda], [NumDecreto_reda], [FechaDecreto_reda], [NumActa_reda], [FechaComision_reda], [Observaciones_reda], [importe_dire], [importe_reda])
SELECT [Codigo_Plan], [numero_obra], [subreferencia], [ao_ejecucion], [importe_dire_Pts], [A_quien_dire], [NumDecreto_dire], [FechaDecreto_dire], [NumActa_dire], [FechaComision_dire], [Observaciones_dire], [importe_reda_Pts], [A_quien_reda], [NumDecreto_reda], [FechaDecreto_reda], [NumActa_reda], [FechaComision_reda], [Observaciones_reda], [importe_dire], [importe_reda]
FROM [GUADIX].[OBRAS].[dbo].[Honorarios];
GO

DECLARE @rowcountHonorarios INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla Honorarios' + ': ' + CAST(@rowcountHonorarios AS VARCHAR(10));
GO

-- ============================================
-- Tabla: Importes_financiacion
-- Columnas comunes: 7
-- ============================================
PRINT 'Copiando datos de tabla: Importes_financiacion';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[Importes_financiacion];
GO

INSERT INTO [dbo].[Importes_financiacion] ([Codigo_Plan], [Ao_ejecucion], [Organismo], [Porcentaje], [ImportePts], [Excluyente], [Importe])
SELECT [Codigo_Plan], [Ao_ejecucion], [Organismo], [Porcentaje], [ImportePts], [Excluyente], [Importe]
FROM [GUADIX].[OBRAS].[dbo].[Importes_financiacion];
GO

DECLARE @rowcountImportes_financiacion INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla Importes_financiacion' + ': ' + CAST(@rowcountImportes_financiacion AS VARCHAR(10));
GO

-- ============================================
-- Tabla: Importes_financiacion1
-- Columnas comunes: 7
-- ============================================
PRINT 'Copiando datos de tabla: Importes_financiacion1';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[Importes_financiacion1];
GO

INSERT INTO [dbo].[Importes_financiacion1] ([Codigo_Plan], [Ao_ejecucion], [Organismo], [Porcentaje], [ImportePts], [Excluyente], [Importe])
SELECT [Codigo_Plan], [Ao_ejecucion], [Organismo], [Porcentaje], [ImportePts], [Excluyente], [Importe]
FROM [GUADIX].[OBRAS].[dbo].[Importes_financiacion1];
GO

DECLARE @rowcountImportes_financiacion1 INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla Importes_financiacion1' + ': ' + CAST(@rowcountImportes_financiacion1 AS VARCHAR(10));
GO

-- ============================================
-- Tabla: ImportesDeObras
-- Columnas comunes: 27
-- ============================================
PRINT 'Copiando datos de tabla: ImportesDeObras';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[ImportesDeObras];
GO

INSERT INTO [dbo].[ImportesDeObras] ([Codigo_Plan], [numero_obra], [subreferencia], [ao_ejecucion], [importe_aprobado_Pts], [importe_remanente_Pts], [importe_a_contratar_Pts], [importe_baja_contratacion_Pts], [importe_adjudicacion_Pts], [importe_ejecutado_Pts], [importe_ejecutado_decreto_Pts], [importe_PenalidadesProrrogas_Pts], [importe_Descontado_PenalidadesProrrogas_Pts], [importe_aprobado], [importe_remanente], [importe_a_contratar], [importe_baja_contratacion], [importe_adjudicacion], [importe_ejecutado], [importe_ejecutado_decreto], [importe_PenalidadesProrrogas], [importe_Descontado_PenalidadesProrrogas], [importe_Modificado], [importe_regularizacion_iva], [team_id], [created_at], [updated_at])
SELECT [Codigo_Plan], [numero_obra], [subreferencia], [ao_ejecucion], [importe_aprobado_Pts], [importe_remanente_Pts], [importe_a_contratar_Pts], [importe_baja_contratacion_Pts], [importe_adjudicacion_Pts], [importe_ejecutado_Pts], [importe_ejecutado_decreto_Pts], [importe_PenalidadesProrrogas_Pts], [importe_Descontado_PenalidadesProrrogas_Pts], [importe_aprobado], [importe_remanente], [importe_a_contratar], [importe_baja_contratacion], [importe_adjudicacion], [importe_ejecutado], [importe_ejecutado_decreto], [importe_PenalidadesProrrogas], [importe_Descontado_PenalidadesProrrogas], [importe_Modificado], [importe_regularizacion_iva], [team_id], [created_at], [updated_at]
FROM [GUADIX].[OBRAS].[dbo].[ImportesDeObras];
GO

DECLARE @rowcountImportesDeObras INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla ImportesDeObras' + ': ' + CAST(@rowcountImportesDeObras AS VARCHAR(10));
GO

-- ============================================
-- Tabla: ImportesPorOrganismo
-- Columnas comunes: 31
-- ============================================
PRINT 'Copiando datos de tabla: ImportesPorOrganismo';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[ImportesPorOrganismo];
GO

INSERT INTO [dbo].[ImportesPorOrganismo] ([Codigo_Plan], [numero_obra], [subreferencia], [ao_ejecucion], [organismo], [Porc_imp_aprobado], [importe_aprobado_Pts], [importe_remanente_Pts], [Porc_imp_contratar], [importe_a_contratar_Pts], [Porc_imp_baja], [importe_baja_contratacion_Pts], [Porc_imp_adjudicado], [importe_adjudicacion_Pts], [Porc_imp_ejecutado], [importe_ejecutado_Pts], [importe_ejecutado_decreto_Pts], [importe_aprobado], [importe_remanente], [importe_a_contratar], [importe_baja_contratacion], [importe_adjudicacion], [importe_ejecutado], [importe_ejecutado_decreto], [Prioridad], [AgrupaOrg], [Porc_ModContratos], [importe_ModContratos], [team_id], [created_at], [updated_at])
SELECT [Codigo_Plan], [numero_obra], [subreferencia], [ao_ejecucion], [organismo], [Porc_imp_aprobado], [importe_aprobado_Pts], [importe_remanente_Pts], [Porc_imp_contratar], [importe_a_contratar_Pts], [Porc_imp_baja], [importe_baja_contratacion_Pts], [Porc_imp_adjudicado], [importe_adjudicacion_Pts], [Porc_imp_ejecutado], [importe_ejecutado_Pts], [importe_ejecutado_decreto_Pts], [importe_aprobado], [importe_remanente], [importe_a_contratar], [importe_baja_contratacion], [importe_adjudicacion], [importe_ejecutado], [importe_ejecutado_decreto], [Prioridad], [AgrupaOrg], [Porc_ModContratos], [importe_ModContratos], [team_id], [created_at], [updated_at]
FROM [GUADIX].[OBRAS].[dbo].[ImportesPorOrganismo];
GO

DECLARE @rowcountImportesPorOrganismo INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla ImportesPorOrganismo' + ': ' + CAST(@rowcountImportesPorOrganismo AS VARCHAR(10));
GO

-- ============================================
-- Tabla: IndicadoresDeObras
-- Columnas comunes: 10
-- ============================================
PRINT 'Copiando datos de tabla: IndicadoresDeObras';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[IndicadoresDeObras];
GO

INSERT INTO [dbo].[IndicadoresDeObras] ([codigo_plan], [ao_plan], [numero_obra], [subreferencia], [tipo_indicador], [indicador_fisico], [tipo_resultado], [Resultados], [CosteNoElegible_Pts], [CosteNoElegible])
SELECT [codigo_plan], [ao_plan], [numero_obra], [subreferencia], [tipo_indicador], [indicador_fisico], [tipo_resultado], [Resultados], [CosteNoElegible_Pts], [CosteNoElegible]
FROM [GUADIX].[OBRAS].[dbo].[IndicadoresDeObras];
GO

DECLARE @rowcountIndicadoresDeObras INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla IndicadoresDeObras' + ': ' + CAST(@rowcountIndicadoresDeObras AS VARCHAR(10));
GO

-- ============================================
-- Tabla: Justificacion_Obras
-- Columnas comunes: 15
-- ============================================
PRINT 'Copiando datos de tabla: Justificacion_Obras';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[Justificacion_Obras];
GO

INSERT INTO [dbo].[Justificacion_Obras] ([Codigo_Plan], [numero_obra], [subreferencia], [ao_ejecucion], [Expediente], [created_at], [update_at], [Estado_just], [Estado_obra], [Fec_inicio_just], [Fec_Fin_just], [devolucion], [Importe_devolver], [motivo], [team_id])
SELECT [Codigo_Plan], [numero_obra], [subreferencia], [ao_ejecucion], [Expediente], [created_at], [update_at], [Estado_just], [Estado_obra], [Fec_inicio_just], [Fec_Fin_just], [devolucion], [Importe_devolver], [motivo], [team_id]
FROM [GUADIX].[OBRAS].[dbo].[Justificacion_Obras];
GO

DECLARE @rowcountJustificacion_Obras INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla Justificacion_Obras' + ': ' + CAST(@rowcountJustificacion_Obras AS VARCHAR(10));
GO

-- ============================================
-- Tabla: MesaContratacionCargos
-- Columnas comunes: 12
-- ============================================
PRINT 'Copiando datos de tabla: MesaContratacionCargos';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[MesaContratacionCargos];
GO

INSERT INTO [dbo].[MesaContratacionCargos] ([CodigoCargo], [NombreCargo], [DenCargo], [Cod_Dpto], [Presidente], [Secretario], [Vocal1], [Vocal2], [Vocal3], [Tratamiento], [Estado], [Asesor])
SELECT [CodigoCargo], [NombreCargo], [DenCargo], [Cod_Dpto], [Presidente], [Secretario], [Vocal1], [Vocal2], [Vocal3], [Tratamiento], [Estado], [Asesor]
FROM [GUADIX].[OBRAS].[dbo].[MesaContratacionCargos];
GO

DECLARE @rowcountMesaContratacionCargos INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla MesaContratacionCargos' + ': ' + CAST(@rowcountMesaContratacionCargos AS VARCHAR(10));
GO

-- ============================================
-- Tabla: Obras_En_Ejecucion
-- Columnas comunes: 11
-- ============================================
PRINT 'Copiando datos de tabla: Obras_En_Ejecucion';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[Obras_En_Ejecucion];
GO

INSERT INTO [dbo].[Obras_En_Ejecucion] ([Codigo_Plan], [numero_obra], [subreferencia], [ao_ejecucion], [nombre_municipio], [nombre_obra1], [nombre_obra2], [nombre_obra3], [importe_aprobado], [importe_adjudicacion], [importe_ejecutado])
SELECT [Codigo_Plan], [numero_obra], [subreferencia], [ao_ejecucion], [nombre_municipio], [nombre_obra1], [nombre_obra2], [nombre_obra3], [importe_aprobado], [importe_adjudicacion], [importe_ejecutado]
FROM [GUADIX].[OBRAS].[dbo].[Obras_En_Ejecucion];
GO

DECLARE @rowcountObras_En_Ejecucion INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla Obras_En_Ejecucion' + ': ' + CAST(@rowcountObras_En_Ejecucion AS VARCHAR(10));
GO

-- ============================================
-- Tabla: Obras_En_Ejecucion_Organismo
-- Columnas comunes: 11
-- ============================================
PRINT 'Copiando datos de tabla: Obras_En_Ejecucion_Organismo';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[Obras_En_Ejecucion_Organismo];
GO

INSERT INTO [dbo].[Obras_En_Ejecucion_Organismo] ([Codigo_Plan], [numero_obra], [subreferencia], [ao_ejecucion], [nombre_municipio], [nombre_obra1], [nombre_obra2], [nombre_obra3], [importe_aprobado], [importe_adjudicacion], [importe_ejecutado])
SELECT [Codigo_Plan], [numero_obra], [subreferencia], [ao_ejecucion], [nombre_municipio], [nombre_obra1], [nombre_obra2], [nombre_obra3], [importe_aprobado], [importe_adjudicacion], [importe_ejecutado]
FROM [GUADIX].[OBRAS].[dbo].[Obras_En_Ejecucion_Organismo];
GO

DECLARE @rowcountObras_En_Ejecucion_Organismo INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla Obras_En_Ejecucion_Organismo' + ': ' + CAST(@rowcountObras_En_Ejecucion_Organismo AS VARCHAR(10));
GO

-- ============================================
-- Tabla: ObrasCedidas
-- Columnas comunes: 23
-- ============================================
PRINT 'Copiando datos de tabla: ObrasCedidas';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[ObrasCedidas];
GO

INSERT INTO [dbo].[ObrasCedidas] ([Codigo_Plan], [numero_obra], [subreferencia], [ao_ejecucion], [FechaRemisionAyto], [FechaRecepcionCerti], [FechaCesion], [FechaAdjudicacion], [ImporteAdjudicacion_Pts], [NombreContratista], [DomicilioContratista], [CPostalContratista], [Ciudad], [CodMunContratista], [NIFContratista], [FechaContrato], [FechaRemisionInterv], [ImporteAdjudicacion], [TelefContratista], [NuevaLey], [team_id], [created_at], [updated_at])
SELECT [Codigo_Plan], [numero_obra], [subreferencia], [ao_ejecucion], [FechaRemisionAyto], [FechaRecepcionCerti], [FechaCesion], [FechaAdjudicacion], [ImporteAdjudicacion_Pts], [NombreContratista], [DomicilioContratista], [CPostalContratista], [Ciudad], [CodMunContratista], [NIFContratista], [FechaContrato], [FechaRemisionInterv], [ImporteAdjudicacion], [TelefContratista], [NuevaLey], [team_id], [created_at], [updated_at]
FROM [GUADIX].[OBRAS].[dbo].[ObrasCedidas];
GO

DECLARE @rowcountObrasCedidas INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla ObrasCedidas' + ': ' + CAST(@rowcountObrasCedidas AS VARCHAR(10));
GO

-- ============================================
-- Tabla: ObrasRelacionadas
-- Columnas comunes: 12
-- ============================================
PRINT 'Copiando datos de tabla: ObrasRelacionadas';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[ObrasRelacionadas];
GO

INSERT INTO [dbo].[ObrasRelacionadas] ([PlanA], [NumeroObraA], [SubreferenciaA], [AoA], [PlanN], [NumeroObraN], [SubreferenciaN], [AoN], [MotivoRelacion], [Comentario], [FechaProceso], [IdUsuario])
SELECT [PlanA], [NumeroObraA], [SubreferenciaA], [AoA], [PlanN], [NumeroObraN], [SubreferenciaN], [AoN], [MotivoRelacion], [Comentario], [FechaProceso], [IdUsuario]
FROM [GUADIX].[OBRAS].[dbo].[ObrasRelacionadas];
GO

DECLARE @rowcountObrasRelacionadas INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla ObrasRelacionadas' + ': ' + CAST(@rowcountObrasRelacionadas AS VARCHAR(10));
GO

-- ============================================
-- Tabla: PartidosJudiciales
-- Columnas comunes: 2
-- ============================================
PRINT 'Copiando datos de tabla: PartidosJudiciales';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[PartidosJudiciales];
GO

INSERT INTO [dbo].[PartidosJudiciales] ([Cod_PJ], [Nombre_PJ])
SELECT [Cod_PJ], [Nombre_PJ]
FROM [GUADIX].[OBRAS].[dbo].[PartidosJudiciales];
GO

DECLARE @rowcountPartidosJudiciales INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla PartidosJudiciales' + ': ' + CAST(@rowcountPartidosJudiciales AS VARCHAR(10));
GO

-- ============================================
-- Tabla: Planes
-- Columnas comunes: 18
-- ============================================
PRINT 'Copiando datos de tabla: Planes';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[Planes];
GO

INSERT INTO [dbo].[Planes] ([codigo_plan], [denominacion_plan], [Tipo_plan], [codigo_depar_reservado], [Tipo_de_desagregado], [sufijo], [sufijo2], [grupo], [denominacion_eje], [tipo_telecel], [num_objetivo], [organismo_gestor], [Adicional], [Plan_original], [orden_grupo], [codigo_depar_reservado_antiguo], [Prevision], [Plurianual])
SELECT [codigo_plan], [denominacion_plan], [Tipo_plan], [codigo_depar_reservado], [Tipo_de_desagregado], [sufijo], [sufijo2], [grupo], [denominacion_eje], [tipo_telecel], [num_objetivo], [organismo_gestor], [Adicional], [Plan_original], [orden_grupo], [codigo_depar_reservado_antiguo], [Prevision], [Plurianual]
FROM [GUADIX].[OBRAS].[dbo].[Planes];
GO

DECLARE @rowcountPlanes INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla Planes' + ': ' + CAST(@rowcountPlanes AS VARCHAR(10));
GO

-- ============================================
-- Tabla: PlanesAsignaciones
-- Columnas comunes: 9
-- ============================================
PRINT 'Copiando datos de tabla: PlanesAsignaciones';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[PlanesAsignaciones];
GO

INSERT INTO [dbo].[PlanesAsignaciones] ([CodigoPlan], [Plan], [DepActual], [DepartamentoActual], [DepNuevo], [DepartamentoNuevo], [TotalExpedientes], [TotalActivos], [TotalTerminados])
SELECT [CodigoPlan], [Plan], [DepActual], [DepartamentoActual], [DepNuevo], [DepartamentoNuevo], [TotalExpedientes], [TotalActivos], [TotalTerminados]
FROM [GUADIX].[OBRAS].[dbo].[PlanesAsignaciones];
GO

DECLARE @rowcountPlanesAsignaciones INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla PlanesAsignaciones' + ': ' + CAST(@rowcountPlanesAsignaciones AS VARCHAR(10));
GO

-- ============================================
-- Tabla: PlanSeguridadYSalud
-- Columnas comunes: 44
-- ============================================
PRINT 'Copiando datos de tabla: PlanSeguridadYSalud';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[PlanSeguridadYSalud];
GO

INSERT INTO [dbo].[PlanSeguridadYSalud] ([Codigo_Plan], [Numero_obra], [Subreferencia], [ao_ejecucion], [FecPlanSyS], [FecPeticionInfTec], [FecRecepcionInfTec], [FecDevolucionInfTec], [FecPropuesta], [NumDecreto], [FecDecreto], [FecComunicacionCont], [FecComunicacionTrabajo], [FecRecepAprobAyto], [Coordinador], [observaciones], [FecSolicitudPSA], [FecRequerimientoPSA], [FecReclaAprob], [FecRecepDev], [FecRecInfJefe], [FecReciboSol], [FecReciboReq], [FecPetInfCoor], [FecDevInfCoor], [FecDevPlanCoor], [FecDevPlanCoorLista], [FecRecepPlanCoor], [FecRecepPlanCoorLista], [FecRemCoor], [FecRecepContrato], [FecRecepAprobCoor], [FecRemAprobCoor], [FecRecepAvisoCoor], [FecSolAprobAyto], [FecEnvInfTecAyto], [CSVPSYS], [CSVPGR], [NumRegistroPSYS], [FecRecepPGR], [FecRecepPSYS], [NumRegistroPGR], [InfPSYS], [InfPGR])
SELECT [Codigo_Plan], [Numero_obra], [Subreferencia], [ao_ejecucion], [FecPlanSyS], [FecPeticionInfTec], [FecRecepcionInfTec], [FecDevolucionInfTec], [FecPropuesta], [NumDecreto], [FecDecreto], [FecComunicacionCont], [FecComunicacionTrabajo], [FecRecepAprobAyto], [Coordinador], [observaciones], [FecSolicitudPSA], [FecRequerimientoPSA], [FecReclaAprob], [FecRecepDev], [FecRecInfJefe], [FecReciboSol], [FecReciboReq], [FecPetInfCoor], [FecDevInfCoor], [FecDevPlanCoor], [FecDevPlanCoorLista], [FecRecepPlanCoor], [FecRecepPlanCoorLista], [FecRemCoor], [FecRecepContrato], [FecRecepAprobCoor], [FecRemAprobCoor], [FecRecepAvisoCoor], [FecSolAprobAyto], [FecEnvInfTecAyto], [CSVPSYS], [CSVPGR], [NumRegistroPSYS], [FecRecepPGR], [FecRecepPSYS], [NumRegistroPGR], [InfPSYS], [InfPGR]
FROM [GUADIX].[OBRAS].[dbo].[PlanSeguridadYSalud];
GO

DECLARE @rowcountPlanSeguridadYSalud INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla PlanSeguridadYSalud' + ': ' + CAST(@rowcountPlanSeguridadYSalud AS VARCHAR(10));
GO

-- ============================================
-- Tabla: Pliegos
-- Columnas comunes: 23
-- ============================================
PRINT 'Copiando datos de tabla: Pliegos';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[Pliegos];
GO

INSERT INTO [dbo].[Pliegos] ([AoPliego], [DepPliego], [NumPliego], [FecPliego], [clase_exp], [tipo_proc], [forma_cont], [NomPliego], [ImpPliego], [plazo], [UnidadPlazo], [revision], [formula], [formula2], [formula3], [formula4], [TramAnticipada], [Observaciones], [FechaAlta], [IdUsuario], [FechaBaja], [IdUsuarioBaja], [Estado])
SELECT [AoPliego], [DepPliego], [NumPliego], [FecPliego], [clase_exp], [tipo_proc], [forma_cont], [NomPliego], [ImpPliego], [plazo], [UnidadPlazo], [revision], [formula], [formula2], [formula3], [formula4], [TramAnticipada], [Observaciones], [FechaAlta], [IdUsuario], [FechaBaja], [IdUsuarioBaja], [Estado]
FROM [GUADIX].[OBRAS].[dbo].[Pliegos];
GO

DECLARE @rowcountPliegos INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla Pliegos' + ': ' + CAST(@rowcountPliegos AS VARCHAR(10));
GO

-- ============================================
-- Tabla: PliegosClasificacion
-- Columnas comunes: 113
-- ============================================
PRINT 'Copiando datos de tabla: PliegosClasificacion';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[PliegosClasificacion];
GO

INSERT INTO [dbo].[PliegosClasificacion] ([AoPliego], [DepPliego], [NumPliego], [A0D], [A1D], [A2D], [A3D], [A4D], [A5D], [A6D], [A7D], [A8D], [A9D], [B0D], [B1D], [B2D], [B3D], [B4D], [B5D], [B6D], [B7D], [B8D], [B9D], [C0D], [C1D], [C2D], [C3D], [C4D], [C5D], [C6D], [C7D], [C8D], [C9D], [D0D], [D1D], [D2D], [D3D], [D4D], [D5D], [D6D], [D7D], [D8D], [D9D], [E0D], [E1D], [E2D], [E3D], [E4D], [E5D], [E6D], [E7D], [E8D], [E9D], [F0D], [F1D], [F2D], [F3D], [F4D], [F5D], [F6D], [F7D], [F8D], [F9D], [G0D], [G1D], [G2D], [G3D], [G4D], [G5D], [G6D], [G7D], [G8D], [G9D], [H0D], [H1D], [H2D], [H3D], [H4D], [H5D], [H6D], [H7D], [H8D], [H9D], [I0D], [I1D], [I2D], [I3D], [I4D], [I5D], [I6D], [I7D], [I8D], [I9D], [J0D], [J1D], [J2D], [J3D], [J4D], [J5D], [J6D], [J7D], [J8D], [J9D], [K0D], [K1D], [K2D], [K3D], [K4D], [K5D], [K6D], [K7D], [K8D], [K9D])
SELECT [AoPliego], [DepPliego], [NumPliego], [A0D], [A1D], [A2D], [A3D], [A4D], [A5D], [A6D], [A7D], [A8D], [A9D], [B0D], [B1D], [B2D], [B3D], [B4D], [B5D], [B6D], [B7D], [B8D], [B9D], [C0D], [C1D], [C2D], [C3D], [C4D], [C5D], [C6D], [C7D], [C8D], [C9D], [D0D], [D1D], [D2D], [D3D], [D4D], [D5D], [D6D], [D7D], [D8D], [D9D], [E0D], [E1D], [E2D], [E3D], [E4D], [E5D], [E6D], [E7D], [E8D], [E9D], [F0D], [F1D], [F2D], [F3D], [F4D], [F5D], [F6D], [F7D], [F8D], [F9D], [G0D], [G1D], [G2D], [G3D], [G4D], [G5D], [G6D], [G7D], [G8D], [G9D], [H0D], [H1D], [H2D], [H3D], [H4D], [H5D], [H6D], [H7D], [H8D], [H9D], [I0D], [I1D], [I2D], [I3D], [I4D], [I5D], [I6D], [I7D], [I8D], [I9D], [J0D], [J1D], [J2D], [J3D], [J4D], [J5D], [J6D], [J7D], [J8D], [J9D], [K0D], [K1D], [K2D], [K3D], [K4D], [K5D], [K6D], [K7D], [K8D], [K9D]
FROM [GUADIX].[OBRAS].[dbo].[PliegosClasificacion];
GO

DECLARE @rowcountPliegosClasificacion INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla PliegosClasificacion' + ': ' + CAST(@rowcountPliegosClasificacion AS VARCHAR(10));
GO

-- ============================================
-- Tabla: PliegosObras
-- Columnas comunes: 7
-- ============================================
PRINT 'Copiando datos de tabla: PliegosObras';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[PliegosObras];
GO

INSERT INTO [dbo].[PliegosObras] ([AoPliego], [DepPliego], [NumPliego], [Codigo_Plan], [referencia], [subreferencia], [ao_ejecucion_obra])
SELECT [AoPliego], [DepPliego], [NumPliego], [Codigo_Plan], [referencia], [subreferencia], [ao_ejecucion_obra]
FROM [GUADIX].[OBRAS].[dbo].[PliegosObras];
GO

DECLARE @rowcountPliegosObras INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla PliegosObras' + ': ' + CAST(@rowcountPliegosObras AS VARCHAR(10));
GO

-- ============================================
-- Tabla: programas
-- Columnas comunes: 18
-- ============================================
PRINT 'Copiando datos de tabla: programas';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[programas];
GO

INSERT INTO [dbo].[programas] ([codigo_plan], [denominacion_plan], [Tipo_plan], [codigo_depar_reservado], [Tipo_de_desagregado], [sufijo], [sufijo2], [grupo], [denominacion_eje], [tipo_telecel], [num_objetivo], [organismo_gestor], [Adicional], [Plan_original], [orden_grupo], [codigo_depar_reservado_antiguo], [Prevision], [Plurianual])
SELECT [codigo_plan], [denominacion_plan], [Tipo_plan], [codigo_depar_reservado], [Tipo_de_desagregado], [sufijo], [sufijo2], [grupo], [denominacion_eje], [tipo_telecel], [num_objetivo], [organismo_gestor], [Adicional], [Plan_original], [orden_grupo], [codigo_depar_reservado_antiguo], [Prevision], [Plurianual]
FROM [GUADIX].[OBRAS].[dbo].[programas];
GO

DECLARE @rowcountprogramas INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla programas' + ': ' + CAST(@rowcountprogramas AS VARCHAR(10));
GO

-- ============================================
-- Tabla: Prorrogas
-- Columnas comunes: 15
-- ============================================
PRINT 'Copiando datos de tabla: Prorrogas';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[Prorrogas];
GO

INSERT INTO [dbo].[Prorrogas] ([PlanObra], [NumObra], [SubRef], [AoObra], [NumSec], [FecPeticionProrrogaDeDip], [FecSolicitudProrrogaDeCont], [FecProrroga], [FecSolicitudInfTec], [FecInfTec], [FecComInf], [FecComGob], [FecDecreto], [NumDecreto], [MotivoProrroga])
SELECT [PlanObra], [NumObra], [SubRef], [AoObra], [NumSec], [FecPeticionProrrogaDeDip], [FecSolicitudProrrogaDeCont], [FecProrroga], [FecSolicitudInfTec], [FecInfTec], [FecComInf], [FecComGob], [FecDecreto], [NumDecreto], [MotivoProrroga]
FROM [GUADIX].[OBRAS].[dbo].[Prorrogas];
GO

DECLARE @rowcountProrrogas INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla Prorrogas' + ': ' + CAST(@rowcountProrrogas AS VARCHAR(10));
GO

-- ============================================
-- Tabla: ProrrogasConPenalidades
-- Columnas comunes: 33
-- ============================================
PRINT 'Copiando datos de tabla: ProrrogasConPenalidades';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[ProrrogasConPenalidades];
GO

INSERT INTO [dbo].[ProrrogasConPenalidades] ([PlanObra], [NumObra], [SubRef], [AoObra], [NumSec], [Fec_Inicio_Expte], [Fec_Notif_Cont], [Fec_Acuse_Recibo], [Fec_Fin_Plazo_Alegacion], [Fec_Alegacion], [Fec_Peticion_Inf_Tec], [Fec_Recepcion_Inf_Tec], [ImposicionPenalidades], [Fec_Inicio_Penalidades], [Fec_Fin_Penalidades], [Fec_ComGob], [Punto_ComGob], [NumeroDias], [ImporteTotal_Pts], [ImporteDescontado_Pts], [Estado], [Fec_Notif_Cont_RR], [Fec_Acuse_Recibo_RR], [Fec_Fin_Plazo_RR], [Fec_RR], [Fec_Peticion_Inf_Tec_RR], [Fec_Recepcion_Inf_Tec_RR], [Fec_Resolucion_RR], [Resolucion_RR], [Fec_Notif_Cont_Resol_RR], [Fec_Aprob_Dev_Desc_Penal], [ImporteTotal], [ImporteDescontado])
SELECT [PlanObra], [NumObra], [SubRef], [AoObra], [NumSec], [Fec_Inicio_Expte], [Fec_Notif_Cont], [Fec_Acuse_Recibo], [Fec_Fin_Plazo_Alegacion], [Fec_Alegacion], [Fec_Peticion_Inf_Tec], [Fec_Recepcion_Inf_Tec], [ImposicionPenalidades], [Fec_Inicio_Penalidades], [Fec_Fin_Penalidades], [Fec_ComGob], [Punto_ComGob], [NumeroDias], [ImporteTotal_Pts], [ImporteDescontado_Pts], [Estado], [Fec_Notif_Cont_RR], [Fec_Acuse_Recibo_RR], [Fec_Fin_Plazo_RR], [Fec_RR], [Fec_Peticion_Inf_Tec_RR], [Fec_Recepcion_Inf_Tec_RR], [Fec_Resolucion_RR], [Resolucion_RR], [Fec_Notif_Cont_Resol_RR], [Fec_Aprob_Dev_Desc_Penal], [ImporteTotal], [ImporteDescontado]
FROM [GUADIX].[OBRAS].[dbo].[ProrrogasConPenalidades];
GO

DECLARE @rowcountProrrogasConPenalidades INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla ProrrogasConPenalidades' + ': ' + CAST(@rowcountProrrogasConPenalidades AS VARCHAR(10));
GO

-- ============================================
-- Tabla: Proyectos
-- Columnas comunes: 70
-- ============================================
PRINT 'Copiando datos de tabla: Proyectos';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[Proyectos];
GO

INSERT INTO [dbo].[Proyectos] ([CODIGO_MUNICIPIO], [AO_PROYECTO], [NUMERO_PROYECTO], [Servicio_Gestor], [Compartido], [den_proyecto], [importe_proyecto_Pts], [organismo_redactor], [Servicio_redactor], [autor], [ColegioOficial], [SubvencionEconRedaccion], [carretera], [plazo], [UnidadPlazo], [nro_ejemplares], [grupo], [subgrupo], [categoria], [revision], [formula], [formula2], [formula3], [formula4], [presu_gral_ejecucion_material_Pts], [por_gastos_generales], [importe_gastos_generales_Pts], [por_beneficio_industriales], [importe_beneficio_industriales_Pts], [por_control_calidad], [importe_control_calidad_Pts], [por_iva], [iva_Pts], [por_subcontrata], [subcontrata_Pts], [honorarios_dir_Pts], [honorarios_red_Pts], [fecha_entrega_proyecto], [fecha_recepcion_proyecto], [fecha_remision_ayto], [fecha_aprobacion_ayto], [fecha_pet_rectificacion], [fecha_ent_rectificacion], [fecha_pet_reforma], [fecha_ent_reforma], [fecha_c_infor], [fecha_c_gob], [Punto], [fecha_pit_ref], [fecha_eit_ref], [fecha_ci_ref], [fecha_cg_ref], [fecha_dto], [nro_dto], [observaciones], [estado_proyecto], [Requiere_PlanSyS], [importe_proyecto], [presu_gral_ejecucion_material], [importe_gastos_generales], [importe_beneficio_industriales], [importe_control_calidad], [iva], [subcontrata], [honorarios_dir], [HD_ExcluidoIVA], [honorarios_red], [HR_ExcluidoIVA], [importePlanSyS], [Requiere_TramAmbiental])
SELECT [CODIGO_MUNICIPIO], [AO_PROYECTO], [NUMERO_PROYECTO], [Servicio_Gestor], [Compartido], [den_proyecto], [importe_proyecto_Pts], [organismo_redactor], [Servicio_redactor], [autor], [ColegioOficial], [SubvencionEconRedaccion], [carretera], [plazo], [UnidadPlazo], [nro_ejemplares], [grupo], [subgrupo], [categoria], [revision], [formula], [formula2], [formula3], [formula4], [presu_gral_ejecucion_material_Pts], [por_gastos_generales], [importe_gastos_generales_Pts], [por_beneficio_industriales], [importe_beneficio_industriales_Pts], [por_control_calidad], [importe_control_calidad_Pts], [por_iva], [iva_Pts], [por_subcontrata], [subcontrata_Pts], [honorarios_dir_Pts], [honorarios_red_Pts], [fecha_entrega_proyecto], [fecha_recepcion_proyecto], [fecha_remision_ayto], [fecha_aprobacion_ayto], [fecha_pet_rectificacion], [fecha_ent_rectificacion], [fecha_pet_reforma], [fecha_ent_reforma], [fecha_c_infor], [fecha_c_gob], [Punto], [fecha_pit_ref], [fecha_eit_ref], [fecha_ci_ref], [fecha_cg_ref], [fecha_dto], [nro_dto], [observaciones], [estado_proyecto], [Requiere_PlanSyS], [importe_proyecto], [presu_gral_ejecucion_material], [importe_gastos_generales], [importe_beneficio_industriales], [importe_control_calidad], [iva], [subcontrata], [honorarios_dir], [HD_ExcluidoIVA], [honorarios_red], [HR_ExcluidoIVA], [importePlanSyS], [Requiere_TramAmbiental]
FROM [GUADIX].[OBRAS].[dbo].[Proyectos];
GO

DECLARE @rowcountProyectos INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla Proyectos' + ': ' + CAST(@rowcountProyectos AS VARCHAR(10));
GO

-- ============================================
-- Tabla: RelacionesObras
-- Columnas comunes: 2
-- ============================================
PRINT 'Copiando datos de tabla: RelacionesObras';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[RelacionesObras];
GO

INSERT INTO [dbo].[RelacionesObras] ([CodRelacion], [MotivoRelacion])
SELECT [CodRelacion], [MotivoRelacion]
FROM [GUADIX].[OBRAS].[dbo].[RelacionesObras];
GO

DECLARE @rowcountRelacionesObras INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla RelacionesObras' + ': ' + CAST(@rowcountRelacionesObras AS VARCHAR(10));
GO

-- ============================================
-- Tabla: SubContrataciones
-- Columnas comunes: 9
-- ============================================
PRINT 'Copiando datos de tabla: SubContrataciones';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[SubContrataciones];
GO

INSERT INTO [dbo].[SubContrataciones] ([Codigo_Plan], [numero_obra], [subreferencia], [ao_ejecucion], [num_secuencia], [Subcontratista], [Importe_Pts], [Fecha_SubContrata], [Importe])
SELECT [Codigo_Plan], [numero_obra], [subreferencia], [ao_ejecucion], [num_secuencia], [Subcontratista], [Importe_Pts], [Fecha_SubContrata], [Importe]
FROM [GUADIX].[OBRAS].[dbo].[SubContrataciones];
GO

DECLARE @rowcountSubContrataciones INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla SubContrataciones' + ': ' + CAST(@rowcountSubContrataciones AS VARCHAR(10));
GO

-- ============================================
-- Tabla: SubcontratacionesCertif
-- Columnas comunes: 8
-- ============================================
PRINT 'Copiando datos de tabla: SubcontratacionesCertif';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[SubcontratacionesCertif];
GO

INSERT INTO [dbo].[SubcontratacionesCertif] ([Codigo_Plan], [numero_obra], [subreferencia], [ao_ejecucion], [num_certif], [Importe_Pts], [fecha], [Importe])
SELECT [Codigo_Plan], [numero_obra], [subreferencia], [ao_ejecucion], [num_certif], [Importe_Pts], [fecha], [Importe]
FROM [GUADIX].[OBRAS].[dbo].[SubcontratacionesCertif];
GO

DECLARE @rowcountSubcontratacionesCertif INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla SubcontratacionesCertif' + ': ' + CAST(@rowcountSubcontratacionesCertif AS VARCHAR(10));
GO

-- ============================================
-- Tabla: Subvenciones
-- Columnas comunes: 27
-- ============================================
PRINT 'Copiando datos de tabla: Subvenciones';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[Subvenciones];
GO

INSERT INTO [dbo].[Subvenciones] ([TipoExp], [PeticionCont], [PlanObra], [NumObra], [SubRef], [AoObra], [servicio_gestor], [FecAprob], [Punto], [FecRemisionCont], [FecDecreto], [TipoAprob], [NumDecreto], [CodClaseExp], [CodProcedimiento], [CodFormaContrata], [ActaReplanteo], [ActaRecepcion], [organismo_direccion], [CodDptoDirector], [NombreDirector], [ColegioOficialDireccion], [SubvencionEconDireccion], [Plazo], [RequiereExpCont], [PlanSys], [CriteriosAdjudicacion])
SELECT [TipoExp], [PeticionCont], [PlanObra], [NumObra], [SubRef], [AoObra], [servicio_gestor], [FecAprob], [Punto], [FecRemisionCont], [FecDecreto], [TipoAprob], [NumDecreto], [CodClaseExp], [CodProcedimiento], [CodFormaContrata], [ActaReplanteo], [ActaRecepcion], [organismo_direccion], [CodDptoDirector], [NombreDirector], [ColegioOficialDireccion], [SubvencionEconDireccion], [Plazo], [RequiereExpCont], [PlanSys], [CriteriosAdjudicacion]
FROM [GUADIX].[OBRAS].[dbo].[Subvenciones];
GO

DECLARE @rowcountSubvenciones INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla Subvenciones' + ': ' + CAST(@rowcountSubvenciones AS VARCHAR(10));
GO

-- ============================================
-- Tabla: zonas
-- Columnas comunes: 2
-- ============================================
PRINT 'Copiando datos de tabla: zonas';
GO

-- OPCION ACTIVA: Eliminar todos los datos existentes y copiar de PRODUCCION
DELETE FROM [dbo].[zonas];
GO

INSERT INTO [dbo].[zonas] ([CODIGO], [ZONA])
SELECT [CODIGO], [ZONA]
FROM [GUADIX].[OBRAS].[dbo].[zonas];
GO

DECLARE @rowcountzonas INT = @@ROWCOUNT;
PRINT 'Registros copiados en tabla zonas' + ': ' + CAST(@rowcountzonas AS VARCHAR(10));
GO

-- ============================================
-- FIN DEL SCRIPT
-- ============================================
PRINT 'Proceso completado';
GO

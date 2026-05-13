/*
Script de implementación para OBRAS_TEST

Este código lo generó una herramienta.
Los cambios en este archivo pueden provocar un comportamiento incorrecto y se perderán si
el código se vuelve a generar.
*/

GO
SET ANSI_NULLS, ANSI_PADDING, ANSI_WARNINGS, ARITHABORT, CONCAT_NULL_YIELDS_NULL, QUOTED_IDENTIFIER ON;

SET NUMERIC_ROUNDABORT OFF;


GO
:setvar DatabaseName "OBRAS_TEST"
:setvar DefaultFilePrefix "OBRAS_TEST"
:setvar DefaultDataPath "K:\SQL_SERVER_2016\MSSQL13.MSSQLSERVER\MSSQL\DATA\"
:setvar DefaultLogPath "L:\SQL_SERVER_2016\MSSQLSERVER_LOG\"

GO
:on error exit
GO
/*
Detecte el modo SQLCMD y deshabilite la ejecución de scripts si no se admite el modo SQLCMD.
Para volver a habilitar el script después de habilitar el modo SQLCMD, ejecute lo siguiente:
ESTABLECER NOEXEC DESACTIVADO; 
*/
:setvar __IsSqlCmdEnabled "True"
GO
IF N'$(__IsSqlCmdEnabled)' NOT LIKE N'True'
    BEGIN
        PRINT N'El modo SQLCMD debe estar habilitado para ejecutar correctamente este script.';
        SET NOEXEC ON;
    END


GO
USE [$(DatabaseName)];


GO
PRINT N'Quitando Índice [dbo].[failed_jobs].[failed_jobs_uuid_unique]...';


GO
DROP INDEX [failed_jobs_uuid_unique]
    ON [dbo].[failed_jobs];


GO
PRINT N'Quitando Índice [dbo].[jobs].[jobs_queue_index]...';


GO
DROP INDEX [jobs_queue_index]
    ON [dbo].[jobs];


GO
PRINT N'Quitando Índice [dbo].[sessions].[sessions_last_activity_index]...';


GO
DROP INDEX [sessions_last_activity_index]
    ON [dbo].[sessions];


GO
PRINT N'Quitando Índice [dbo].[sessions].[sessions_user_id_index]...';


GO
DROP INDEX [sessions_user_id_index]
    ON [dbo].[sessions];


GO
PRINT N'Quitando Índice [dbo].[users].[users_email_unique]...';


GO
DROP INDEX [users_email_unique]
    ON [dbo].[users];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_Ayuda_Tecnica_pasado]...';


GO
ALTER TABLE [dbo].[Ayuda_Tecnica] DROP CONSTRAINT [DF_Ayuda_Tecnica_pasado];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_Datos_Ejecucion_Obras_Indicador_Recepcion_AR]...';


GO
ALTER TABLE [dbo].[Datos_Ejecucion_Obras] DROP CONSTRAINT [DF_Datos_Ejecucion_Obras_Indicador_Recepcion_AR];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_Datos_Ejecucion_Obras_Indicador_Impresion_AR]...';


GO
ALTER TABLE [dbo].[Datos_Ejecucion_Obras] DROP CONSTRAINT [DF_Datos_Ejecucion_Obras_Indicador_Impresion_AR];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_Datosadicionales_Estado]...';


GO
ALTER TABLE [dbo].[Datosadicionales] DROP CONSTRAINT [DF_Datosadicionales_Estado];


GO
PRINT N'Quitando Restricción DEFAULT restricción sin nombre en [dbo].[documentacionexpedientes]...';


GO
ALTER TABLE [dbo].[documentacionexpedientes] DROP CONSTRAINT [DF__documenta__nregi__3D9CADE1];


GO
PRINT N'Quitando Restricción DEFAULT restricción sin nombre en [dbo].[documentacionexpedientes]...';


GO
ALTER TABLE [dbo].[documentacionexpedientes] DROP CONSTRAINT [DF__documenta__nsecu__3E90D21A];


GO
PRINT N'Quitando Restricción DEFAULT restricción sin nombre en [dbo].[documentacionexpedientes]...';


GO
ALTER TABLE [dbo].[documentacionexpedientes] DROP CONSTRAINT [DF__documenta__descr__3F84F653];


GO
PRINT N'Quitando Restricción DEFAULT restricción sin nombre en [dbo].[documentacionexpedientes]...';


GO
ALTER TABLE [dbo].[documentacionexpedientes] DROP CONSTRAINT [DF__documenta__subre__3AC04136];


GO
PRINT N'Quitando Restricción DEFAULT restricción sin nombre en [dbo].[documentacionexpedientes]...';


GO
ALTER TABLE [dbo].[documentacionexpedientes] DROP CONSTRAINT [DF__documenta__fecha__3BB4656F];


GO
PRINT N'Quitando Restricción DEFAULT restricción sin nombre en [dbo].[documentacionexpedientes]...';


GO
ALTER TABLE [dbo].[documentacionexpedientes] DROP CONSTRAINT [DF__documentaci__csv__3CA889A8];


GO
PRINT N'Quitando Restricción DEFAULT restricción sin nombre en [dbo].[failed_jobs]...';


GO
ALTER TABLE [dbo].[failed_jobs] DROP CONSTRAINT [DF__failed_jo__faile__35FB8C19];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_FasesDeProyectos_HR_ExcluidoIVA]...';


GO
ALTER TABLE [dbo].[FasesDeProyectos] DROP CONSTRAINT [DF_FasesDeProyectos_HR_ExcluidoIVA];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_FasesDeProyectos_UnidadPlazo]...';


GO
ALTER TABLE [dbo].[FasesDeProyectos] DROP CONSTRAINT [DF_FasesDeProyectos_UnidadPlazo];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_FasesDeProyectos_SubvencionEconDireccion]...';


GO
ALTER TABLE [dbo].[FasesDeProyectos] DROP CONSTRAINT [DF_FasesDeProyectos_SubvencionEconDireccion];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_FasesDeProyectos_HD_ExcIVA]...';


GO
ALTER TABLE [dbo].[FasesDeProyectos] DROP CONSTRAINT [DF_FasesDeProyectos_HD_ExcIVA];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_FasesDeProyectos_Requiere_PlanSyS]...';


GO
ALTER TABLE [dbo].[FasesDeProyectos] DROP CONSTRAINT [DF_FasesDeProyectos_Requiere_PlanSyS];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesDeObras_importe_regularizacion_iva]...';


GO
ALTER TABLE [dbo].[ImportesDeObras] DROP CONSTRAINT [DF_ImportesDeObras_importe_regularizacion_iva];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesDeObras_importe_Descontado_PenalidadesProrrogas_Pts]...';


GO
ALTER TABLE [dbo].[ImportesDeObras] DROP CONSTRAINT [DF_ImportesDeObras_importe_Descontado_PenalidadesProrrogas_Pts];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesDeObras_importe_a_contratar_Pts]...';


GO
ALTER TABLE [dbo].[ImportesDeObras] DROP CONSTRAINT [DF_ImportesDeObras_importe_a_contratar_Pts];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesDeObras_importe_baja_contratacion_Pts]...';


GO
ALTER TABLE [dbo].[ImportesDeObras] DROP CONSTRAINT [DF_ImportesDeObras_importe_baja_contratacion_Pts];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesDeObras_importe_adjudicacion_Pts]...';


GO
ALTER TABLE [dbo].[ImportesDeObras] DROP CONSTRAINT [DF_ImportesDeObras_importe_adjudicacion_Pts];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesDeObras_importe_ejecutado_Pts]...';


GO
ALTER TABLE [dbo].[ImportesDeObras] DROP CONSTRAINT [DF_ImportesDeObras_importe_ejecutado_Pts];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesDeObras_importe_ejecutado_decreto_Pts]...';


GO
ALTER TABLE [dbo].[ImportesDeObras] DROP CONSTRAINT [DF_ImportesDeObras_importe_ejecutado_decreto_Pts];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesDeObras_importe_PenalidadesProrrogas_Pts]...';


GO
ALTER TABLE [dbo].[ImportesDeObras] DROP CONSTRAINT [DF_ImportesDeObras_importe_PenalidadesProrrogas_Pts];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesDeObras_importe_aprobado_Pts]...';


GO
ALTER TABLE [dbo].[ImportesDeObras] DROP CONSTRAINT [DF_ImportesDeObras_importe_aprobado_Pts];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesDeObras_importe_remanente_Pts]...';


GO
ALTER TABLE [dbo].[ImportesDeObras] DROP CONSTRAINT [DF_ImportesDeObras_importe_remanente_Pts];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesDeObras_importe_Descontado_PenalidadesProrrogas]...';


GO
ALTER TABLE [dbo].[ImportesDeObras] DROP CONSTRAINT [DF_ImportesDeObras_importe_Descontado_PenalidadesProrrogas];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesDeObras_importe_adjudicacion]...';


GO
ALTER TABLE [dbo].[ImportesDeObras] DROP CONSTRAINT [DF_ImportesDeObras_importe_adjudicacion];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesDeObras_importe_ejecutado]...';


GO
ALTER TABLE [dbo].[ImportesDeObras] DROP CONSTRAINT [DF_ImportesDeObras_importe_ejecutado];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesDeObras_importe_aprobado]...';


GO
ALTER TABLE [dbo].[ImportesDeObras] DROP CONSTRAINT [DF_ImportesDeObras_importe_aprobado];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesDeObras_importe_remanente]...';


GO
ALTER TABLE [dbo].[ImportesDeObras] DROP CONSTRAINT [DF_ImportesDeObras_importe_remanente];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesDeObras_importe_a_contratar]...';


GO
ALTER TABLE [dbo].[ImportesDeObras] DROP CONSTRAINT [DF_ImportesDeObras_importe_a_contratar];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesDeObras_importe_baja_contratacion]...';


GO
ALTER TABLE [dbo].[ImportesDeObras] DROP CONSTRAINT [DF_ImportesDeObras_importe_baja_contratacion];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesDeObras_importe_ejecutado_decreto]...';


GO
ALTER TABLE [dbo].[ImportesDeObras] DROP CONSTRAINT [DF_ImportesDeObras_importe_ejecutado_decreto];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesDeObras_importe_PenalidadesProrrogas]...';


GO
ALTER TABLE [dbo].[ImportesDeObras] DROP CONSTRAINT [DF_ImportesDeObras_importe_PenalidadesProrrogas];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesPorOrganismo_importe_adjudicacion]...';


GO
ALTER TABLE [dbo].[ImportesPorOrganismo] DROP CONSTRAINT [DF_ImportesPorOrganismo_importe_adjudicacion];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesPorOrganismo_importe_aprobado_Pts]...';


GO
ALTER TABLE [dbo].[ImportesPorOrganismo] DROP CONSTRAINT [DF_ImportesPorOrganismo_importe_aprobado_Pts];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesPorOrganismo_importe_ejecutado]...';


GO
ALTER TABLE [dbo].[ImportesPorOrganismo] DROP CONSTRAINT [DF_ImportesPorOrganismo_importe_ejecutado];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesPorOrganismo_Porc_imp_aprobado]...';


GO
ALTER TABLE [dbo].[ImportesPorOrganismo] DROP CONSTRAINT [DF_ImportesPorOrganismo_Porc_imp_aprobado];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesPorOrganismo_importe_ejecutado_decreto]...';


GO
ALTER TABLE [dbo].[ImportesPorOrganismo] DROP CONSTRAINT [DF_ImportesPorOrganismo_importe_ejecutado_decreto];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesPorOrganismo_importe_remanente_Pts]...';


GO
ALTER TABLE [dbo].[ImportesPorOrganismo] DROP CONSTRAINT [DF_ImportesPorOrganismo_importe_remanente_Pts];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesPorOrganismo_Porc_imp_contratar]...';


GO
ALTER TABLE [dbo].[ImportesPorOrganismo] DROP CONSTRAINT [DF_ImportesPorOrganismo_Porc_imp_contratar];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesPorOrganismo_Prioridad]...';


GO
ALTER TABLE [dbo].[ImportesPorOrganismo] DROP CONSTRAINT [DF_ImportesPorOrganismo_Prioridad];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesPorOrganismo_importe_a_contratar_Pts]...';


GO
ALTER TABLE [dbo].[ImportesPorOrganismo] DROP CONSTRAINT [DF_ImportesPorOrganismo_importe_a_contratar_Pts];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesPorOrganismo_AgrupaOrg]...';


GO
ALTER TABLE [dbo].[ImportesPorOrganismo] DROP CONSTRAINT [DF_ImportesPorOrganismo_AgrupaOrg];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesPorOrganismo_importe_baja_contratacion_Pts]...';


GO
ALTER TABLE [dbo].[ImportesPorOrganismo] DROP CONSTRAINT [DF_ImportesPorOrganismo_importe_baja_contratacion_Pts];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesPorOrganismo_Porc_imp_adjudicado]...';


GO
ALTER TABLE [dbo].[ImportesPorOrganismo] DROP CONSTRAINT [DF_ImportesPorOrganismo_Porc_imp_adjudicado];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesPorOrganismo_Porc_imp_baja]...';


GO
ALTER TABLE [dbo].[ImportesPorOrganismo] DROP CONSTRAINT [DF_ImportesPorOrganismo_Porc_imp_baja];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesPorOrganismo_importe_adjudicacion_Pts]...';


GO
ALTER TABLE [dbo].[ImportesPorOrganismo] DROP CONSTRAINT [DF_ImportesPorOrganismo_importe_adjudicacion_Pts];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesPorOrganismo_importe_baja_contratacion]...';


GO
ALTER TABLE [dbo].[ImportesPorOrganismo] DROP CONSTRAINT [DF_ImportesPorOrganismo_importe_baja_contratacion];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesPorOrganismo_importe_ejecutado_Pts]...';


GO
ALTER TABLE [dbo].[ImportesPorOrganismo] DROP CONSTRAINT [DF_ImportesPorOrganismo_importe_ejecutado_Pts];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesPorOrganismo_Porc_imp_ejecutado]...';


GO
ALTER TABLE [dbo].[ImportesPorOrganismo] DROP CONSTRAINT [DF_ImportesPorOrganismo_Porc_imp_ejecutado];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesPorOrganismo_importe_a_contratar]...';


GO
ALTER TABLE [dbo].[ImportesPorOrganismo] DROP CONSTRAINT [DF_ImportesPorOrganismo_importe_a_contratar];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesPorOrganismo_importe_remanente]...';


GO
ALTER TABLE [dbo].[ImportesPorOrganismo] DROP CONSTRAINT [DF_ImportesPorOrganismo_importe_remanente];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesPorOrganismo_importe_aprobado]...';


GO
ALTER TABLE [dbo].[ImportesPorOrganismo] DROP CONSTRAINT [DF_ImportesPorOrganismo_importe_aprobado];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesPorOrganismo_importe_ejecutado_decreto_Pts]...';


GO
ALTER TABLE [dbo].[ImportesPorOrganismo] DROP CONSTRAINT [DF_ImportesPorOrganismo_importe_ejecutado_decreto_Pts];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesPorOrganismo_Porc_ModContratos]...';


GO
ALTER TABLE [dbo].[ImportesPorOrganismo] DROP CONSTRAINT [DF_ImportesPorOrganismo_Porc_ModContratos];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ImportesPorOrganismo_importe_ModContratos]...';


GO
ALTER TABLE [dbo].[ImportesPorOrganismo] DROP CONSTRAINT [DF_ImportesPorOrganismo_importe_ModContratos];


GO
PRINT N'Quitando Restricción DEFAULT [dbo].[DF_ObrasCedidas_NuevaLey]...';


GO
ALTER TABLE [dbo].[ObrasCedidas] DROP CONSTRAINT [DF_ObrasCedidas_NuevaLey];


GO
PRINT N'Quitando Restricción DEFAULT restricción sin nombre en [dbo].[users]...';


GO
ALTER TABLE [dbo].[users] DROP CONSTRAINT [DF__users__is_active__6E94EAF7];


GO
PRINT N'Quitando Restricción DEFAULT restricción sin nombre en [dbo].[firmantesdocumentosexpediente]...';


GO
ALTER TABLE [dbo].[firmantesdocumentosexpediente] DROP CONSTRAINT [DF__firmantes__subre__61DA0E57];


GO
PRINT N'Quitando Restricción DEFAULT restricción sin nombre en [dbo].[firmantesdocumentosexpediente]...';


GO
ALTER TABLE [dbo].[firmantesdocumentosexpediente] DROP CONSTRAINT [DF__firmantes__n_exp__62CE3290];


GO
PRINT N'Quitando Restricción DEFAULT restricción sin nombre en [dbo].[firmantesdocumentosexpediente]...';


GO
ALTER TABLE [dbo].[firmantesdocumentosexpediente] DROP CONSTRAINT [DF__firmantes__fecha__63C256C9];


GO
PRINT N'Quitando Clave externa [dbo].[FK_ExpedienteDocumento]...';


GO
ALTER TABLE [dbo].[documentacionexpedientes] DROP CONSTRAINT [FK_ExpedienteDocumento];


GO
PRINT N'Quitando Clave externa [dbo].[documento_generico_variables_documento_generico_id_foreign]...';


GO
ALTER TABLE [dbo].[documento_generico_variables] DROP CONSTRAINT [documento_generico_variables_documento_generico_id_foreign];


GO
PRINT N'Quitando Clave externa [dbo].[team_user_team_id_foreign]...';


GO
ALTER TABLE [dbo].[team_user] DROP CONSTRAINT [team_user_team_id_foreign];


GO
PRINT N'Quitando Clave externa [dbo].[expediente_user_assignments_team_id_foreign]...';


GO
ALTER TABLE [dbo].[expediente_user_assignments] DROP CONSTRAINT [expediente_user_assignments_team_id_foreign];


GO
PRINT N'Quitando Clave externa [dbo].[team_user_user_id_foreign]...';


GO
ALTER TABLE [dbo].[team_user] DROP CONSTRAINT [team_user_user_id_foreign];


GO
PRINT N'Quitando Clave externa [dbo].[expediente_user_assignments_assigned_by_foreign]...';


GO
ALTER TABLE [dbo].[expediente_user_assignments] DROP CONSTRAINT [expediente_user_assignments_assigned_by_foreign];


GO
PRINT N'Quitando Clave externa [dbo].[expediente_user_assignments_user_id_foreign]...';


GO
ALTER TABLE [dbo].[expediente_user_assignments] DROP CONSTRAINT [expediente_user_assignments_user_id_foreign];


GO
PRINT N'Quitando Clave externa [dbo].[FK_Justificacion_Obras_Justificacion_Obras]...';


GO
ALTER TABLE [dbo].[Justificacion_Obras] DROP CONSTRAINT [FK_Justificacion_Obras_Justificacion_Obras];


GO
PRINT N'Quitando Clave externa [dbo].[FK_Avisos]...';


GO
ALTER TABLE [dbo].[Avisos] DROP CONSTRAINT [FK_Avisos];


GO
PRINT N'Quitando Clave principal [dbo].[PK_Avisos]...';


GO
ALTER TABLE [dbo].[Avisos] DROP CONSTRAINT [PK_Avisos];


GO
PRINT N'Quitando Clave principal [dbo].[PK_documentacionexpedientes]...';


GO
ALTER TABLE [dbo].[documentacionexpedientes] DROP CONSTRAINT [PK_documentacionexpedientes];


GO
PRINT N'Quitando Clave principal [dbo].[PK_documento_expedientes]...';


GO
ALTER TABLE [dbo].[documento_expedientes] DROP CONSTRAINT [PK_documento_expedientes];


GO
PRINT N'Quitando Clave principal [dbo].[PK_fase_documentos]...';


GO
ALTER TABLE [dbo].[fase_documentos] DROP CONSTRAINT [PK_fase_documentos];


GO
PRINT N'Quitando Clave principal [dbo].[PK_firmante_genericos]...';


GO
ALTER TABLE [dbo].[firmante_genericos] DROP CONSTRAINT [PK_firmante_genericos];


GO
PRINT N'Quitando Clave principal restricción sin nombre en [dbo].[firmantesdocumentosexpediente]...';


GO
ALTER TABLE [dbo].[firmantesdocumentosexpediente] DROP CONSTRAINT [PK__firmante__4ADB6B7A5FF1C5E5];


GO
PRINT N'Quitando Clave principal [dbo].[job_batches_id_primary]...';


GO
ALTER TABLE [dbo].[job_batches] DROP CONSTRAINT [job_batches_id_primary];


GO
PRINT N'Quitando Clave principal [dbo].[PK_Justificacion_Obras]...';


GO
ALTER TABLE [dbo].[Justificacion_Obras] DROP CONSTRAINT [PK_Justificacion_Obras];


GO
PRINT N'Quitando Clave principal [dbo].[password_reset_tokens_email_primary]...';


GO
ALTER TABLE [dbo].[password_reset_tokens] DROP CONSTRAINT [password_reset_tokens_email_primary];


GO
PRINT N'Quitando Clave principal [dbo].[sessions_id_primary]...';


GO
ALTER TABLE [dbo].[sessions] DROP CONSTRAINT [sessions_id_primary];


GO
PRINT N'Quitando Clave principal [dbo].[PK_Carreteras]...';


GO
ALTER TABLE [dbo].[TablaDeCarreteras] DROP CONSTRAINT [PK_Carreteras];


GO
PRINT N'Quitando Clave principal [dbo].[PK_zonas]...';


GO
ALTER TABLE [dbo].[zonas] DROP CONSTRAINT [PK_zonas];


GO
PRINT N'Quitando Usuario [ausuariosbd]...';


GO
DROP USER [ausuariosbd];


GO
PRINT N'Quitando Usuario [sde_lec]...';


GO
DROP USER [sde_lec];


GO
PRINT N'Quitando Usuario [tecno]...';


GO
DROP USER [tecno];


GO
PRINT N'Quitando Usuario [usuConsumo]...';


GO
DROP USER [usuConsumo];


GO
PRINT N'Quitando Usuario [usuPlanesProvLectura]...';


GO
DROP USER [usuPlanesProvLectura];


GO
PRINT N'Desenlazando la autorización [adminbd2]...';


GO
ALTER AUTHORIZATION
    ON SCHEMA::[adminbd2]
    TO [dbo];


GO
PRINT N'Desenlazando la autorización [Internet]...';


GO
ALTER AUTHORIZATION
    ON SCHEMA::[Internet]
    TO [dbo];


GO
PRINT N'Desenlazando la autorización [Obras]...';


GO
ALTER AUTHORIZATION
    ON SCHEMA::[Obras]
    TO [dbo];


GO
PRINT N'Desenlazando la autorización [PPActuate]...';


GO
ALTER AUTHORIZATION
    ON SCHEMA::[PPActuate]
    TO [dbo];


GO
PRINT N'Desenlazando la autorización [usu_vinculo]...';


GO
ALTER AUTHORIZATION
    ON SCHEMA::[usu_vinculo]
    TO [dbo];


GO
PRINT N'Desenlazando la autorización [usuchequeo]...';


GO
ALTER AUTHORIZATION
    ON SCHEMA::[usuchequeo]
    TO [dbo];


GO
PRINT N'Desenlazando la autorización [usuidemap]...';


GO
ALTER AUTHORIZATION
    ON SCHEMA::[usuidemap]
    TO [dbo];


GO
PRINT N'Desenlazando la autorización [usuplanes]...';


GO
ALTER AUTHORIZATION
    ON SCHEMA::[usuplanes]
    TO [dbo];


GO
PRINT N'Quitando Usuario [adminbd2]...';


GO
DROP USER [adminbd2];


GO
PRINT N'Quitando Usuario [Internet]...';


GO
DROP USER [Internet];


GO
PRINT N'Quitando Usuario [Obras]...';


GO
DROP USER [Obras];


GO
PRINT N'Quitando Usuario [PPActuate]...';


GO
DROP USER [PPActuate];


GO
PRINT N'Quitando Usuario [usu_vinculo]...';


GO
DROP USER [usu_vinculo];


GO
PRINT N'Quitando Usuario [usuchequeo]...';


GO
DROP USER [usuchequeo];


GO
PRINT N'Quitando Usuario [usuidemap]...';


GO
DROP USER [usuidemap];


GO
PRINT N'Quitando Usuario [usuplanes]...';


GO
DROP USER [usuplanes];


GO
PRINT N'Creando Usuario [ausuariosbd]...';


GO
CREATE USER [ausuariosbd] WITHOUT LOGIN;


GO
REVOKE CONNECT TO [ausuariosbd];


GO
PRINT N'Creando Usuario [sde_lec]...';


GO
CREATE USER [sde_lec] WITHOUT LOGIN;


GO
REVOKE CONNECT TO [sde_lec];


GO
PRINT N'Creando Usuario [tecno]...';


GO
CREATE USER [tecno] WITHOUT LOGIN;


GO
REVOKE CONNECT TO [tecno];


GO
PRINT N'Creando Usuario [usuConsumo]...';


GO
CREATE USER [usuConsumo] WITHOUT LOGIN;


GO
REVOKE CONNECT TO [usuConsumo];


GO
PRINT N'Creando Usuario [usuPlanesProvLectura]...';


GO
CREATE USER [usuPlanesProvLectura] WITHOUT LOGIN;


GO
REVOKE CONNECT TO [usuPlanesProvLectura];


GO
PRINT N'Creando Pertenencia a roles [db_datareader] para [ausuariosbd]...';


GO
EXECUTE sp_addrolemember @rolename = N'db_datareader', @membername = N'ausuariosbd';


GO
PRINT N'Creando Pertenencia a roles [db_datawriter] para [ausuariosbd]...';


GO
EXECUTE sp_addrolemember @rolename = N'db_datawriter', @membername = N'ausuariosbd';


GO
PRINT N'Creando Pertenencia a roles [db_owner] para [sde_lec]...';


GO
EXECUTE sp_addrolemember @rolename = N'db_owner', @membername = N'sde_lec';


GO
PRINT N'Creando Pertenencia a roles [db_datareader] para [sde_lec]...';


GO
EXECUTE sp_addrolemember @rolename = N'db_datareader', @membername = N'sde_lec';


GO
PRINT N'Creando Pertenencia a roles [db_datawriter] para [sde_lec]...';


GO
EXECUTE sp_addrolemember @rolename = N'db_datawriter', @membername = N'sde_lec';


GO
PRINT N'Creando Pertenencia a roles [db_owner] para [tecno]...';


GO
EXECUTE sp_addrolemember @rolename = N'db_owner', @membername = N'tecno';


GO
PRINT N'Creando Pertenencia a roles [db_datareader] para [usuConsumo]...';


GO
EXECUTE sp_addrolemember @rolename = N'db_datareader', @membername = N'usuConsumo';


GO
PRINT N'Creando Pertenencia a roles [db_datareader] para [usuPlanesProvLectura]...';


GO
EXECUTE sp_addrolemember @rolename = N'db_datareader', @membername = N'usuPlanesProvLectura';


GO
PRINT N'Creando Usuario [adminbd2]...';


GO
CREATE USER [adminbd2] WITHOUT LOGIN
    WITH DEFAULT_SCHEMA = [adminbd2];


GO
REVOKE CONNECT TO [adminbd2];


GO
PRINT N'Reenlazando la autorización [adminbd2]...';


GO
ALTER AUTHORIZATION
    ON SCHEMA::[adminbd2]
    TO [adminbd2];


GO
PRINT N'Creando Pertenencia a roles [db_owner] para [adminbd2]...';


GO
EXECUTE sp_addrolemember @rolename = N'db_owner', @membername = N'adminbd2';


GO
PRINT N'Creando Usuario [Internet]...';


GO
CREATE USER [Internet] WITHOUT LOGIN
    WITH DEFAULT_SCHEMA = [Internet];


GO
REVOKE CONNECT TO [Internet];


GO
PRINT N'Reenlazando la autorización [Internet]...';


GO
ALTER AUTHORIZATION
    ON SCHEMA::[Internet]
    TO [Internet];


GO
PRINT N'Creando Pertenencia a roles [db_datareader] para [Internet]...';


GO
EXECUTE sp_addrolemember @rolename = N'db_datareader', @membername = N'Internet';


GO
PRINT N'Creando Usuario [Obras]...';


GO
CREATE USER [Obras] WITHOUT LOGIN
    WITH DEFAULT_SCHEMA = [Obras];


GO
REVOKE CONNECT TO [Obras];


GO
PRINT N'Reenlazando la autorización [Obras]...';


GO
ALTER AUTHORIZATION
    ON SCHEMA::[Obras]
    TO [Obras];


GO
PRINT N'Creando Pertenencia a roles [db_owner] para [Obras]...';


GO
EXECUTE sp_addrolemember @rolename = N'db_owner', @membername = N'Obras';


GO
PRINT N'Creando Usuario [PPActuate]...';


GO
CREATE USER [PPActuate] WITHOUT LOGIN
    WITH DEFAULT_SCHEMA = [PPActuate];


GO
REVOKE CONNECT TO [PPActuate];


GO
PRINT N'Reenlazando la autorización [PPActuate]...';


GO
ALTER AUTHORIZATION
    ON SCHEMA::[PPActuate]
    TO [PPActuate];


GO
PRINT N'Creando Pertenencia a roles [db_datareader] para [PPActuate]...';


GO
EXECUTE sp_addrolemember @rolename = N'db_datareader', @membername = N'PPActuate';


GO
PRINT N'Creando Usuario [usu_vinculo]...';


GO
CREATE USER [usu_vinculo] WITHOUT LOGIN
    WITH DEFAULT_SCHEMA = [usu_vinculo];


GO
REVOKE CONNECT TO [usu_vinculo];


GO
PRINT N'Reenlazando la autorización [usu_vinculo]...';


GO
ALTER AUTHORIZATION
    ON SCHEMA::[usu_vinculo]
    TO [usu_vinculo];


GO
PRINT N'Creando Pertenencia a roles [db_datareader] para [usu_vinculo]...';


GO
EXECUTE sp_addrolemember @rolename = N'db_datareader', @membername = N'usu_vinculo';


GO
PRINT N'Creando Usuario [usuchequeo]...';


GO
CREATE USER [usuchequeo] WITHOUT LOGIN
    WITH DEFAULT_SCHEMA = [usuchequeo];


GO
REVOKE CONNECT TO [usuchequeo];


GO
PRINT N'Reenlazando la autorización [usuchequeo]...';


GO
ALTER AUTHORIZATION
    ON SCHEMA::[usuchequeo]
    TO [usuchequeo];


GO
PRINT N'Creando Pertenencia a roles [db_datareader] para [usuchequeo]...';


GO
EXECUTE sp_addrolemember @rolename = N'db_datareader', @membername = N'usuchequeo';


GO
PRINT N'Creando Usuario [usuidemap]...';


GO
CREATE USER [usuidemap] WITHOUT LOGIN
    WITH DEFAULT_SCHEMA = [usuidemap];


GO
REVOKE CONNECT TO [usuidemap];


GO
PRINT N'Reenlazando la autorización [usuidemap]...';


GO
ALTER AUTHORIZATION
    ON SCHEMA::[usuidemap]
    TO [usuidemap];


GO
PRINT N'Creando Pertenencia a roles [db_datareader] para [usuidemap]...';


GO
EXECUTE sp_addrolemember @rolename = N'db_datareader', @membername = N'usuidemap';


GO
PRINT N'Creando Usuario [usuplanes]...';


GO
CREATE USER [usuplanes] WITHOUT LOGIN
    WITH DEFAULT_SCHEMA = [usuplanes];


GO
REVOKE CONNECT TO [usuplanes];


GO
PRINT N'Reenlazando la autorización [usuplanes]...';


GO
ALTER AUTHORIZATION
    ON SCHEMA::[usuplanes]
    TO [usuplanes];


GO
PRINT N'Creando Pertenencia a roles [db_datareader] para [usuplanes]...';


GO
EXECUTE sp_addrolemember @rolename = N'db_datareader', @membername = N'usuplanes';


GO
PRINT N'Creando Pertenencia a roles [db_datawriter] para [usuplanes]...';


GO
EXECUTE sp_addrolemember @rolename = N'db_datawriter', @membername = N'usuplanes';


GO
PRINT N'Modificando Tabla [dbo].[Avisos]...';


GO
ALTER TABLE [dbo].[Avisos] DROP COLUMN [created_at], COLUMN [expediente_id], COLUMN [id], COLUMN [team_id], COLUMN [updated_at];


GO
/*
Se está quitando la columna [dbo].[Ayuda_Tecnica].[expediente_id]; puede que se pierdan datos.
*/
GO
PRINT N'Iniciando recompilación de la tabla [dbo].[Ayuda_Tecnica]...';


GO
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

SET XACT_ABORT ON;

CREATE TABLE [dbo].[tmp_ms_xx_Ayuda_Tecnica] (
    [Codigo_Plan]            CHAR (7)      NOT NULL,
    [numero_obra]            SMALLINT      NOT NULL,
    [subreferencia]          TINYINT       NOT NULL,
    [ao_ejecucion]           SMALLINT      NOT NULL,
    [departamento]           SMALLINT      NULL,
    [codigo_municipio]       SMALLINT      NULL,
    [ao_proyecto]            SMALLINT      NULL,
    [numero_proyecto]        SMALLINT      NULL,
    [dpto_redactor]          SMALLINT      NULL,
    [departamento_direccion] SMALLINT      NULL,
    [pasado]                 BIT           CONSTRAINT [DF_Ayuda_Tecnica_pasado] DEFAULT (0) NOT NULL,
    [SubvencionEconomicaR]   CHAR (2)      NULL,
    [SubvencionEconomicaD]   CHAR (2)      NULL,
    [AyuTecRed]              CHAR (2)      NULL,
    [AyuTecDir]              CHAR (2)      NULL,
    [Expediente]             NVARCHAR (50) NULL,
    [team_id]                BIGINT        NULL,
    [created_at]             DATETIME2 (7) NULL,
    [updated_at]             DATETIME2 (7) NULL,
    CONSTRAINT [tmp_ms_xx_constraint_PK_Ayuda_Tecnica1] PRIMARY KEY NONCLUSTERED ([Codigo_Plan] ASC, [numero_obra] ASC, [subreferencia] ASC, [ao_ejecucion] ASC) WITH (FILLFACTOR = 90)
);

IF EXISTS (SELECT TOP 1 1 
           FROM   [dbo].[Ayuda_Tecnica])
    BEGIN
        INSERT INTO [dbo].[tmp_ms_xx_Ayuda_Tecnica] ([Codigo_Plan], [numero_obra], [subreferencia], [ao_ejecucion], [departamento], [codigo_municipio], [ao_proyecto], [numero_proyecto], [dpto_redactor], [departamento_direccion], [pasado], [SubvencionEconomicaR], [SubvencionEconomicaD], [AyuTecRed], [AyuTecDir], [team_id], [created_at], [updated_at])
        SELECT [Codigo_Plan],
               [numero_obra],
               [subreferencia],
               [ao_ejecucion],
               [departamento],
               [codigo_municipio],
               [ao_proyecto],
               [numero_proyecto],
               [dpto_redactor],
               [departamento_direccion],
               [pasado],
               [SubvencionEconomicaR],
               [SubvencionEconomicaD],
               [AyuTecRed],
               [AyuTecDir],
               [team_id],
               [created_at],
               [updated_at]
        FROM   [dbo].[Ayuda_Tecnica];
    END

DROP TABLE [dbo].[Ayuda_Tecnica];

EXECUTE sp_rename N'[dbo].[tmp_ms_xx_Ayuda_Tecnica]', N'Ayuda_Tecnica';

EXECUTE sp_rename N'[dbo].[tmp_ms_xx_constraint_PK_Ayuda_Tecnica1]', N'PK_Ayuda_Tecnica', N'OBJECT';

COMMIT TRANSACTION;

SET TRANSACTION ISOLATION LEVEL READ COMMITTED;


GO
PRINT N'Modificando Tabla [dbo].[Certificaciones_obras_org]...';


GO
ALTER TABLE [dbo].[Certificaciones_obras_org] DROP COLUMN [created_at], COLUMN [Expediente], COLUMN [team_id], COLUMN [updated_at];


GO
PRINT N'Modificando Tabla [dbo].[CertificacionesDeObras]...';


GO
ALTER TABLE [dbo].[CertificacionesDeObras] DROP COLUMN [created_at], COLUMN [expediente_id], COLUMN [team_id], COLUMN [updated_at];


GO
/*
Se está quitando la columna [dbo].[Datos_Ejecucion_Obras].[expediente_id]; puede que se pierdan datos.
*/
GO
PRINT N'Iniciando recompilación de la tabla [dbo].[Datos_Ejecucion_Obras]...';


GO
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

SET XACT_ABORT ON;

CREATE TABLE [dbo].[tmp_ms_xx_Datos_Ejecucion_Obras] (
    [Codigo_Plan]                       CHAR (7)      NOT NULL,
    [numero_obra]                       SMALLINT      NOT NULL,
    [subreferencia]                     TINYINT       NOT NULL,
    [ao_ejecucion]                      SMALLINT      NOT NULL,
    [Fecha_Inicio_Acta_Replanteo]       SMALLDATETIME NULL,
    [Fecha_Final_Acta_Replanteo]        SMALLDATETIME NULL,
    [Fecha_Prorroga_Acta_Replanteo]     SMALLDATETIME NULL,
    [Indicador_Impresion_AR]            BIT           CONSTRAINT [DF_Datos_Ejecucion_Obras_Indicador_Impresion_AR] DEFAULT (0) NOT NULL,
    [Indicador_Recepcion_AR]            BIT           CONSTRAINT [DF_Datos_Ejecucion_Obras_Indicador_Recepcion_AR] DEFAULT (0) NOT NULL,
    [TipoActaRecepcion]                 CHAR (1)      NULL,
    [Fecha_Acta_RecProv]                SMALLDATETIME NULL,
    [Lugar_Acta_Rec]                    VARCHAR (25)  NULL,
    [Fecha_Com_Inf]                     SMALLDATETIME NULL,
    [Fecha_Edicto_BOE]                  SMALLDATETIME NULL,
    [Fecha_BOE]                         SMALLDATETIME NULL,
    [Num_BOE]                           CHAR (3)      NULL,
    [Plazo_Reclam]                      SMALLINT      NULL,
    [Fecha_Certif_NO_Reclam]            SMALLDATETIME NULL,
    [Fecha_Com_Inf_2]                   SMALLDATETIME NULL,
    [Fecha_Com_Gob]                     SMALLDATETIME NULL,
    [Fecha_Comun_Contrat]               SMALLDATETIME NULL,
    [Fecha_Certif_Liquid]               SMALLDATETIME NULL,
    [Fecha_Rem_Interv]                  SMALLDATETIME NULL,
    [Fecha_Rem_MAP]                     SMALLDATETIME NULL,
    [Admin_ActaRecepcion]               VARCHAR (60)  NULL,
    [Dir_ActaRecepcion]                 VARCHAR (60)  NULL,
    [Alcalde_ActaRecepcion]             VARCHAR (60)  NULL,
    [Cont_ActaRecepcion]                VARCHAR (60)  NULL,
    [Interv_ActaRecepcion]              VARCHAR (60)  NULL,
    [Dipu_ActaRecepcion]                VARCHAR (60)  NULL,
    [Texto]                             TEXT          NULL,
    [Fecha_Paralizacion_Temporal]       SMALLDATETIME NULL,
    [Motivo_Paralizacion]               VARCHAR (200) NULL,
    [Fecha_Aprob_Paralizacion_Temporal] SMALLDATETIME NULL,
    [Fecha_Inicio_Paralizacion]         SMALLDATETIME NULL,
    [Fecha_Final_Paralizacion]          SMALLDATETIME NULL,
    [Fecha_Acta_Rec]                    SMALLDATETIME NULL,
    [Fecha_Aviso_Finalizacion]          SMALLDATETIME NULL,
    [Fecha_Aviso_FinalizacionMAP]       SMALLDATETIME NULL,
    [Fecha_Medicion]                    SMALLDATETIME NULL,
    [Expediente]                        NVARCHAR (50) NULL,
    [team_id]                           BIGINT        NULL,
    [created_at]                        DATETIME2 (7) NULL,
    [updated_at]                        DATETIME2 (7) NULL,
    CONSTRAINT [tmp_ms_xx_constraint_PK_Datos_Ejecucion_Obras1] PRIMARY KEY NONCLUSTERED ([Codigo_Plan] ASC, [numero_obra] ASC, [subreferencia] ASC, [ao_ejecucion] ASC) WITH (FILLFACTOR = 90)
);

IF EXISTS (SELECT TOP 1 1 
           FROM   [dbo].[Datos_Ejecucion_Obras])
    BEGIN
        INSERT INTO [dbo].[tmp_ms_xx_Datos_Ejecucion_Obras] ([Codigo_Plan], [numero_obra], [subreferencia], [ao_ejecucion], [Fecha_Inicio_Acta_Replanteo], [Fecha_Final_Acta_Replanteo], [Fecha_Prorroga_Acta_Replanteo], [Indicador_Impresion_AR], [Indicador_Recepcion_AR], [TipoActaRecepcion], [Fecha_Acta_RecProv], [Lugar_Acta_Rec], [Fecha_Com_Inf], [Fecha_Edicto_BOE], [Fecha_BOE], [Num_BOE], [Plazo_Reclam], [Fecha_Certif_NO_Reclam], [Fecha_Com_Inf_2], [Fecha_Com_Gob], [Fecha_Comun_Contrat], [Fecha_Certif_Liquid], [Fecha_Rem_Interv], [Fecha_Rem_MAP], [Admin_ActaRecepcion], [Dir_ActaRecepcion], [Alcalde_ActaRecepcion], [Cont_ActaRecepcion], [Interv_ActaRecepcion], [Dipu_ActaRecepcion], [Texto], [Fecha_Paralizacion_Temporal], [Motivo_Paralizacion], [Fecha_Aprob_Paralizacion_Temporal], [Fecha_Inicio_Paralizacion], [Fecha_Final_Paralizacion], [Fecha_Acta_Rec], [Fecha_Aviso_Finalizacion], [Fecha_Aviso_FinalizacionMAP], [Fecha_Medicion], [team_id], [created_at], [updated_at])
        SELECT [Codigo_Plan],
               [numero_obra],
               [subreferencia],
               [ao_ejecucion],
               [Fecha_Inicio_Acta_Replanteo],
               [Fecha_Final_Acta_Replanteo],
               [Fecha_Prorroga_Acta_Replanteo],
               [Indicador_Impresion_AR],
               [Indicador_Recepcion_AR],
               [TipoActaRecepcion],
               [Fecha_Acta_RecProv],
               [Lugar_Acta_Rec],
               [Fecha_Com_Inf],
               [Fecha_Edicto_BOE],
               [Fecha_BOE],
               [Num_BOE],
               [Plazo_Reclam],
               [Fecha_Certif_NO_Reclam],
               [Fecha_Com_Inf_2],
               [Fecha_Com_Gob],
               [Fecha_Comun_Contrat],
               [Fecha_Certif_Liquid],
               [Fecha_Rem_Interv],
               [Fecha_Rem_MAP],
               [Admin_ActaRecepcion],
               [Dir_ActaRecepcion],
               [Alcalde_ActaRecepcion],
               [Cont_ActaRecepcion],
               [Interv_ActaRecepcion],
               [Dipu_ActaRecepcion],
               [Texto],
               [Fecha_Paralizacion_Temporal],
               [Motivo_Paralizacion],
               [Fecha_Aprob_Paralizacion_Temporal],
               [Fecha_Inicio_Paralizacion],
               [Fecha_Final_Paralizacion],
               [Fecha_Acta_Rec],
               [Fecha_Aviso_Finalizacion],
               [Fecha_Aviso_FinalizacionMAP],
               [Fecha_Medicion],
               [team_id],
               [created_at],
               [updated_at]
        FROM   [dbo].[Datos_Ejecucion_Obras];
    END

DROP TABLE [dbo].[Datos_Ejecucion_Obras];

EXECUTE sp_rename N'[dbo].[tmp_ms_xx_Datos_Ejecucion_Obras]', N'Datos_Ejecucion_Obras';

EXECUTE sp_rename N'[dbo].[tmp_ms_xx_constraint_PK_Datos_Ejecucion_Obras1]', N'PK_Datos_Ejecucion_Obras', N'OBJECT';

COMMIT TRANSACTION;

SET TRANSACTION ISOLATION LEVEL READ COMMITTED;


GO
/*
Se está quitando la columna [dbo].[Datosadicionales].[expediente_id]; puede que se pierdan datos.
*/
GO
PRINT N'Iniciando recompilación de la tabla [dbo].[Datosadicionales]...';


GO
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

SET XACT_ABORT ON;

CREATE TABLE [dbo].[tmp_ms_xx_Datosadicionales] (
    [Codigo_Plan]        CHAR (7)      NOT NULL,
    [numero_obra]        SMALLINT      NOT NULL,
    [subreferencia]      TINYINT       NOT NULL,
    [ao_ejecucion]       SMALLINT      NOT NULL,
    [tipo_movimiento]    CHAR (2)      NOT NULL,
    [numero_movimiento]  TINYINT       NOT NULL,
    [Importe_Pts]        INT           NULL,
    [Partida]            VARCHAR (25)  NULL,
    [FechaEmision]       SMALLDATETIME NULL,
    [FechaEnvio]         SMALLDATETIME NULL,
    [FechaFiscalizacion] SMALLDATETIME NULL,
    [FechaInformativa]   SMALLDATETIME NULL,
    [PuntoInformativa]   VARCHAR (20)  NULL,
    [FechaComision]      SMALLDATETIME NULL,
    [PuntoComision]      VARCHAR (20)  NULL,
    [FechaDecreto]       SMALLDATETIME NULL,
    [NumDecreto]         INT           NULL,
    [Observaciones]      VARCHAR (250) NULL,
    [NumCertificacion]   INT           NULL,
    [Importe]            FLOAT (53)    NULL,
    [Estado]             CHAR (3)      CONSTRAINT [DF_Datosadicionales_Estado] DEFAULT (NULL) NULL,
    [Expediente]         NVARCHAR (50) NULL,
    [team_id]            BIGINT        NULL,
    [created_at]         DATETIME2 (7) NULL,
    [updated_at]         DATETIME2 (7) NULL,
    CONSTRAINT [tmp_ms_xx_constraint_PK_Datosadicionales1] PRIMARY KEY NONCLUSTERED ([Codigo_Plan] ASC, [numero_obra] ASC, [subreferencia] ASC, [ao_ejecucion] ASC, [tipo_movimiento] ASC, [numero_movimiento] ASC) WITH (FILLFACTOR = 90)
);

IF EXISTS (SELECT TOP 1 1 
           FROM   [dbo].[Datosadicionales])
    BEGIN
        INSERT INTO [dbo].[tmp_ms_xx_Datosadicionales] ([Codigo_Plan], [numero_obra], [subreferencia], [ao_ejecucion], [tipo_movimiento], [numero_movimiento], [Importe_Pts], [Partida], [FechaEmision], [FechaEnvio], [FechaFiscalizacion], [FechaInformativa], [PuntoInformativa], [FechaComision], [PuntoComision], [FechaDecreto], [NumDecreto], [Observaciones], [NumCertificacion], [Importe], [Estado], [team_id], [created_at], [updated_at])
        SELECT [Codigo_Plan],
               [numero_obra],
               [subreferencia],
               [ao_ejecucion],
               [tipo_movimiento],
               [numero_movimiento],
               [Importe_Pts],
               [Partida],
               [FechaEmision],
               [FechaEnvio],
               [FechaFiscalizacion],
               [FechaInformativa],
               [PuntoInformativa],
               [FechaComision],
               [PuntoComision],
               [FechaDecreto],
               [NumDecreto],
               [Observaciones],
               [NumCertificacion],
               [Importe],
               [Estado],
               [team_id],
               [created_at],
               [updated_at]
        FROM   [dbo].[Datosadicionales];
    END

DROP TABLE [dbo].[Datosadicionales];

EXECUTE sp_rename N'[dbo].[tmp_ms_xx_Datosadicionales]', N'Datosadicionales';

EXECUTE sp_rename N'[dbo].[tmp_ms_xx_constraint_PK_Datosadicionales1]', N'PK_Datosadicionales', N'OBJECT';

COMMIT TRANSACTION;

SET TRANSACTION ISOLATION LEVEL READ COMMITTED;


GO
PRINT N'Modificando Tabla [dbo].[DatosInicioDeObras]...';


GO
ALTER TABLE [dbo].[DatosInicioDeObras] DROP COLUMN [expediente_id];


GO
ALTER TABLE [dbo].[DatosInicioDeObras]
    ADD [Expediente] NVARCHAR (50) NULL;


GO
PRINT N'Creando Clave principal [dbo].[PK_DatosInicioDeObras]...';


GO
ALTER TABLE [dbo].[DatosInicioDeObras]
    ADD CONSTRAINT [PK_DatosInicioDeObras] PRIMARY KEY NONCLUSTERED ([Codigo_Plan] ASC, [numero_obra] ASC, [subreferencia] ASC, [ao_ejecucion] ASC) WITH (FILLFACTOR = 90);


GO
/*
El tipo de la columna destino en la tabla [dbo].[DestinosDeDocumentos] es  NCHAR (100) NOT NULL, pero se va a cambiar a  NCHAR (30) NULL. Si la columna contiene datos no compatibles con el tipo  NCHAR (30) NULL, podrían producirse pérdidas de datos y errores en la implementación.
*/
GO
PRINT N'Iniciando recompilación de la tabla [dbo].[DestinosDeDocumentos]...';


GO
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

SET XACT_ABORT ON;

CREATE TABLE [dbo].[tmp_ms_xx_DestinosDeDocumentos] (
    [id]      INT        NOT NULL,
    [destino] NCHAR (30) NULL
);

IF EXISTS (SELECT TOP 1 1 
           FROM   [dbo].[DestinosDeDocumentos])
    BEGIN
        INSERT INTO [dbo].[tmp_ms_xx_DestinosDeDocumentos] ([id], [destino])
        SELECT [id],
               [destino]
        FROM   [dbo].[DestinosDeDocumentos];
    END

DROP TABLE [dbo].[DestinosDeDocumentos];

EXECUTE sp_rename N'[dbo].[tmp_ms_xx_DestinosDeDocumentos]', N'DestinosDeDocumentos';

COMMIT TRANSACTION;

SET TRANSACTION ISOLATION LEVEL READ COMMITTED;


GO
/*
Se está quitando la columna [dbo].[documentacionexpedientes].[archivo]; puede que se pierdan datos.

Se está quitando la columna [dbo].[documentacionexpedientes].[cod_documento]; puede que se pierdan datos.

Se está quitando la columna [dbo].[documentacionexpedientes].[Codigo_Plan]; puede que se pierdan datos.

Se está quitando la columna [dbo].[documentacionexpedientes].[expediente_id]; puede que se pierdan datos.

Se está quitando la columna [dbo].[documentacionexpedientes].[notificado]; puede que se pierdan datos.

Se está quitando la columna [dbo].[documentacionexpedientes].[remitidopor]; puede que se pierdan datos.

Debe agregarse la columna [dbo].[documentacionexpedientes].[cod_plan] de la tabla [dbo].[documentacionexpedientes], pero esta columna no tiene un valor predeterminado y no admite valores NULL. Si la tabla contiene datos, el script ALTER no funcionará. Para evitar esta incidencia, agregue un valor predeterminado a la columna, márquela de modo que permita valores NULL o habilite la generación de valores predeterminados inteligentes como opción de implementación.

Debe agregarse la columna [dbo].[documentacionexpedientes].[coddcoumento] de la tabla [dbo].[documentacionexpedientes], pero esta columna no tiene un valor predeterminado y no admite valores NULL. Si la tabla contiene datos, el script ALTER no funcionará. Para evitar esta incidencia, agregue un valor predeterminado a la columna, márquela de modo que permita valores NULL o habilite la generación de valores predeterminados inteligentes como opción de implementación.
*/
GO
PRINT N'Iniciando recompilación de la tabla [dbo].[documentacionexpedientes]...';


GO
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

SET XACT_ABORT ON;

CREATE TABLE [dbo].[tmp_ms_xx_documentacionexpedientes] (
    [idDocumento]        INT           NOT NULL,
    [cod_plan]           VARCHAR (45)  NOT NULL,
    [referencia]         INT           NOT NULL,
    [subreferencia]      INT           NULL,
    [ao_ejecucion]       INT           NOT NULL,
    [fechaincorporacion] DATE          NOT NULL,
    [fechaHelp]          DATE          NULL,
    [coddcoumento]       INT           NOT NULL,
    [csv]                VARCHAR (50)  NULL,
    [nregistro]          VARCHAR (45)  NULL,
    [nsecuencia]         INT           NULL,
    [estado]             INT           NOT NULL,
    [descripcion]        VARCHAR (255) NULL,
    [team_id]            BIGINT        NULL,
    [destino]            SMALLINT      NULL,
    [procedencia]        SMALLINT      NULL,
    [Expediente]         NVARCHAR (50) NULL,
    [created_at]         DATETIME2 (7) NULL,
    [updated_at]         DATETIME2 (7) NULL
);

IF EXISTS (SELECT TOP 1 1 
           FROM   [dbo].[documentacionexpedientes])
    BEGIN
        INSERT INTO [dbo].[tmp_ms_xx_documentacionexpedientes] ([idDocumento], [referencia], [subreferencia], [ao_ejecucion], [fechaincorporacion], [fechaHelp], [csv], [nregistro], [nsecuencia], [estado], [descripcion], [team_id], [destino], [procedencia], [created_at], [updated_at])
        SELECT [idDocumento],
               [referencia],
               [subreferencia],
               [ao_ejecucion],
               [fechaincorporacion],
               [fechaHelp],
               [csv],
               [nregistro],
               [nsecuencia],
               [estado],
               [descripcion],
               [team_id],
               [destino],
               [procedencia],
               [created_at],
               [updated_at]
        FROM   [dbo].[documentacionexpedientes];
    END

DROP TABLE [dbo].[documentacionexpedientes];

EXECUTE sp_rename N'[dbo].[tmp_ms_xx_documentacionexpedientes]', N'documentacionexpedientes';

COMMIT TRANSACTION;

SET TRANSACTION ISOLATION LEVEL READ COMMITTED;


GO
PRINT N'Modificando Tabla [dbo].[documento_expedientes]...';


GO
ALTER TABLE [dbo].[documento_expedientes] ALTER COLUMN [created_at] SMALLDATETIME NULL;

ALTER TABLE [dbo].[documento_expedientes] ALTER COLUMN [fecha_incorporacion] SMALLDATETIME NOT NULL;

ALTER TABLE [dbo].[documento_expedientes] ALTER COLUMN [updated_at] SMALLDATETIME NULL;


GO
/*
Se está quitando la columna [dbo].[documento_genericos].[cod_origen]; puede que se pierdan datos.

La columna cod_documento de la tabla [dbo].[documento_genericos] debe cambiarse de NULL a NOT NULL. Si la tabla contiene datos, puede que no funcione el script ALTER. Para evitar esta incidencia, debe agregar valores en todas las filas de esta columna, marcar la columna de modo que permita valores NULL o habilitar la generación de valores predeterminados inteligentes como opción de implementación.

El tipo de la columna cod_documento en la tabla [dbo].[documento_genericos] es  VARCHAR (5) NULL, pero se va a cambiar a  INT NOT NULL. Si la columna contiene datos no compatibles con el tipo  INT NOT NULL, podrían producirse pérdidas de datos y errores en la implementación.

El tipo de la columna cod_estado en la tabla [dbo].[documento_genericos] es  CHAR (3) NOT NULL, pero se va a cambiar a  INT NOT NULL. Si la columna contiene datos no compatibles con el tipo  INT NOT NULL, podrían producirse pérdidas de datos y errores en la implementación.

El tipo de la columna created_at en la tabla [dbo].[documento_genericos] es  DATETIME NULL, pero se va a cambiar a  SMALLDATETIME NULL. Si la columna contiene datos no compatibles con el tipo  SMALLDATETIME NULL, podrían producirse pérdidas de datos y errores en la implementación.

El tipo de la columna updated_at en la tabla [dbo].[documento_genericos] es  DATETIME NULL, pero se va a cambiar a  SMALLDATETIME NULL. Si la columna contiene datos no compatibles con el tipo  SMALLDATETIME NULL, podrían producirse pérdidas de datos y errores en la implementación.
*/
GO
PRINT N'Iniciando recompilación de la tabla [dbo].[documento_genericos]...';


GO
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

SET XACT_ABORT ON;

CREATE TABLE [dbo].[tmp_ms_xx_documento_genericos] (
    [id]             BIGINT         NOT NULL,
    [created_at]     SMALLDATETIME  NULL,
    [updated_at]     SMALLDATETIME  NULL,
    [cod_documento]  INT            NOT NULL,
    [nombre]         NVARCHAR (255) NOT NULL,
    [descripcion]    NVARCHAR (MAX) NOT NULL,
    [fase_doc]       NVARCHAR (255) NOT NULL,
    [fase_siguiente] NVARCHAR (255) NOT NULL,
    [cod_tipo_doc]   BIGINT         NOT NULL,
    [con_plantilla]  BIT            NOT NULL,
    [plantilla]      NVARCHAR (255) NULL,
    [ruta_plantilla] NVARCHAR (255) NULL,
    [cod_estado]     INT            NOT NULL,
    [cod_destino]    INT            NOT NULL,
    [entrada_salida] NVARCHAR (8)   NULL,
    [cod_firmante]   INT            NULL,
    [obligatorio]    BIT            NOT NULL
);

IF EXISTS (SELECT TOP 1 1 
           FROM   [dbo].[documento_genericos])
    BEGIN
        INSERT INTO [dbo].[tmp_ms_xx_documento_genericos] ([created_at], [updated_at], [cod_documento], [nombre], [descripcion], [fase_doc], [fase_siguiente], [cod_tipo_doc], [con_plantilla], [plantilla], [ruta_plantilla], [cod_estado], [cod_destino], [entrada_salida], [cod_firmante], [obligatorio], [id])
        SELECT [created_at],
               [updated_at],
               [cod_documento],
               [nombre],
               [descripcion],
               [fase_doc],
               [fase_siguiente],
               [cod_tipo_doc],
               [con_plantilla],
               [plantilla],
               [ruta_plantilla],
               [cod_estado],
               [cod_destino],
               [entrada_salida],
               [cod_firmante],
               [obligatorio],
               [id]
        FROM   [dbo].[documento_genericos];
    END

DROP TABLE [dbo].[documento_genericos];

EXECUTE sp_rename N'[dbo].[tmp_ms_xx_documento_genericos]', N'documento_genericos';

COMMIT TRANSACTION;

SET TRANSACTION ISOLATION LEVEL READ COMMITTED;


GO
/*
Se está quitando la columna [dbo].[Documentos_de_fases_de_proyectos].[created_at]; puede que se pierdan datos.

Se está quitando la columna [dbo].[Documentos_de_fases_de_proyectos].[expediente_id]; puede que se pierdan datos.

Se está quitando la columna [dbo].[Documentos_de_fases_de_proyectos].[team_id]; puede que se pierdan datos.

Se está quitando la columna [dbo].[Documentos_de_fases_de_proyectos].[updated_at]; puede que se pierdan datos.
*/
GO
PRINT N'Iniciando recompilación de la tabla [dbo].[Documentos_de_fases_de_proyectos]...';


GO
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

SET XACT_ABORT ON;

CREATE TABLE [dbo].[tmp_ms_xx_Documentos_de_fases_de_proyectos] (
    [codigo_plan]     NCHAR (7)  NOT NULL,
    [numero_obra]     SMALLINT   NOT NULL,
    [subreferencia]   SMALLINT   NOT NULL,
    [ao_ejecucion]    SMALLINT   NOT NULL,
    [cod_municipio]   SMALLINT   NOT NULL,
    [ao_proyecto]     SMALLINT   NOT NULL,
    [numero_proyecto] SMALLINT   NOT NULL,
    [ao_fase]         SMALLINT   NOT NULL,
    [numero_fase]     SMALLINT   NOT NULL,
    [documento]       NCHAR (50) NOT NULL,
    [csv]             NCHAR (50) NOT NULL,
    [numero_doc]      SMALLINT   NULL
);

IF EXISTS (SELECT TOP 1 1 
           FROM   [dbo].[Documentos_de_fases_de_proyectos])
    BEGIN
        INSERT INTO [dbo].[tmp_ms_xx_Documentos_de_fases_de_proyectos] ([codigo_plan], [numero_obra], [subreferencia], [ao_ejecucion], [cod_municipio], [ao_proyecto], [numero_proyecto], [ao_fase], [numero_fase], [documento], [csv], [numero_doc])
        SELECT [codigo_plan],
               [numero_obra],
               [subreferencia],
               [ao_ejecucion],
               [cod_municipio],
               [ao_proyecto],
               [numero_proyecto],
               [ao_fase],
               [numero_fase],
               [documento],
               [csv],
               [numero_doc]
        FROM   [dbo].[Documentos_de_fases_de_proyectos];
    END

DROP TABLE [dbo].[Documentos_de_fases_de_proyectos];

EXECUTE sp_rename N'[dbo].[tmp_ms_xx_Documentos_de_fases_de_proyectos]', N'Documentos_de_fases_de_proyectos';

COMMIT TRANSACTION;

SET TRANSACTION ISOLATION LEVEL READ COMMITTED;


GO
/*
El tipo de la columna failed_at en la tabla [dbo].[failed_jobs] es  DATETIME NOT NULL, pero se va a cambiar a  SMALLDATETIME NOT NULL. Si la columna contiene datos no compatibles con el tipo  SMALLDATETIME NOT NULL, podrían producirse pérdidas de datos y errores en la implementación.
*/
GO
PRINT N'Iniciando recompilación de la tabla [dbo].[failed_jobs]...';


GO
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

SET XACT_ABORT ON;

CREATE TABLE [dbo].[tmp_ms_xx_failed_jobs] (
    [id]         BIGINT         NOT NULL,
    [uuid]       NVARCHAR (255) NOT NULL,
    [connection] NVARCHAR (MAX) NOT NULL,
    [queue]      NVARCHAR (MAX) NOT NULL,
    [payload]    NVARCHAR (MAX) NOT NULL,
    [exception]  NVARCHAR (MAX) NOT NULL,
    [failed_at]  SMALLDATETIME  NOT NULL
);

IF EXISTS (SELECT TOP 1 1 
           FROM   [dbo].[failed_jobs])
    BEGIN
        INSERT INTO [dbo].[tmp_ms_xx_failed_jobs] ([id], [uuid], [connection], [queue], [payload], [exception], [failed_at])
        SELECT [id],
               [uuid],
               [connection],
               [queue],
               [payload],
               [exception],
               [failed_at]
        FROM   [dbo].[failed_jobs];
    END

DROP TABLE [dbo].[failed_jobs];

EXECUTE sp_rename N'[dbo].[tmp_ms_xx_failed_jobs]', N'failed_jobs';

COMMIT TRANSACTION;

SET TRANSACTION ISOLATION LEVEL READ COMMITTED;


GO
PRINT N'Modificando Tabla [dbo].[fase_documentos]...';


GO
ALTER TABLE [dbo].[fase_documentos] ALTER COLUMN [created_at] SMALLDATETIME NULL;

ALTER TABLE [dbo].[fase_documentos] ALTER COLUMN [updated_at] SMALLDATETIME NULL;


GO
/*
Se está quitando la columna [dbo].[FasesDeProyectos].[created_at]; puede que se pierdan datos.

Se está quitando la columna [dbo].[FasesDeProyectos].[expediente_id]; puede que se pierdan datos.

Se está quitando la columna [dbo].[FasesDeProyectos].[team_id]; puede que se pierdan datos.

Se está quitando la columna [dbo].[FasesDeProyectos].[updated_at]; puede que se pierdan datos.
*/
GO
PRINT N'Iniciando recompilación de la tabla [dbo].[FasesDeProyectos]...';


GO
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

SET XACT_ABORT ON;

CREATE TABLE [dbo].[tmp_ms_xx_FasesDeProyectos] (
    [MUNICIPIO]                          SMALLINT      NOT NULL,
    [AO_PROYECTO]                        SMALLINT      NOT NULL,
    [NUMERO_PROYECTO]                    SMALLINT      NOT NULL,
    [NUMERO_FASE]                        SMALLINT      NOT NULL,
    [AO_FASE]                            SMALLINT      NOT NULL,
    [servicio_gestor]                    SMALLINT      NULL,
    [importe_fase_Pts]                   FLOAT (53)    NULL,
    [Codigo_Plan]                        CHAR (7)      NULL,
    [referencia]                         SMALLINT      NULL,
    [subreferencia]                      TINYINT       NULL,
    [ao_ejecucion_obra]                  SMALLINT      NULL,
    [carretera]                          CHAR (15)     NULL,
    [plazo]                              SMALLINT      NULL,
    [UnidadPlazo]                        CHAR (1)      CONSTRAINT [DF_FasesDeProyectos_UnidadPlazo] DEFAULT ('m') NULL,
    [nro_ejemplares]                     SMALLINT      NULL,
    [grupo]                              NVARCHAR (1)  NULL,
    [subgrupo]                           NVARCHAR (9)  NULL,
    [categoria]                          NVARCHAR (1)  NULL,
    [revision]                           CHAR (2)      NULL,
    [formula]                            TINYINT       NULL,
    [formula2]                           TINYINT       NULL,
    [formula3]                           TINYINT       NULL,
    [formula4]                           TINYINT       NULL,
    [organismo_direccion]                CHAR (2)      NULL,
    [servicio_direccion]                 SMALLINT      NULL,
    [director_tecnico_obra]              NVARCHAR (60) NULL,
    [ColegioOficialDireccion]            CHAR (2)      NULL,
    [SubvencionEconDireccion]            BIT           CONSTRAINT [DF_FasesDeProyectos_SubvencionEconDireccion] DEFAULT (0) NOT NULL,
    [RedactorPlanSS]                     NVARCHAR (60) NULL,
    [presu_gral_ejecucion_material_Pts]  FLOAT (53)    NULL,
    [por_gastos_generales]               FLOAT (53)    NULL,
    [importe_gastos_generales_Pts]       FLOAT (53)    NULL,
    [por_beneficio_industriales]         FLOAT (53)    NULL,
    [importe_beneficio_industriales_Pts] FLOAT (53)    NULL,
    [por_control_calidad]                FLOAT (53)    NULL,
    [importe_control_calidad_Pts]        FLOAT (53)    NULL,
    [Por_iva]                            FLOAT (53)    NULL,
    [iva_Pts]                            FLOAT (53)    NULL,
    [por_subcontrata]                    FLOAT (53)    NULL,
    [subcontrata_Pts]                    FLOAT (53)    NULL,
    [honorarios_dir_Pts]                 FLOAT (53)    NULL,
    [honorarios_red_Pts]                 FLOAT (53)    NULL,
    [fecha_rem_fase]                     SMALLDATETIME NULL,
    [fecha_ent_fase]                     SMALLDATETIME NULL,
    [fecha_remision_ayto]                SMALLDATETIME NULL,
    [fecha_aprobacion_ayto]              SMALLDATETIME NULL,
    [fecha_remision_junta]               SMALLDATETIME NULL,
    [fecha_visado_junta]                 SMALLDATETIME NULL,
    [fecha_pet_inf_tecnico_contrata]     SMALLDATETIME NULL,
    [fecha_ent_inf_tecnico_contrata]     SMALLDATETIME NULL,
    [pliego_clausulas_particulares]      SMALLDATETIME NULL,
    [fecha_pet_desglose]                 SMALLDATETIME NULL,
    [fecha_ent_desglose]                 SMALLDATETIME NULL,
    [fecha_pet_rectificacion]            SMALLDATETIME NULL,
    [fecha_ent_rectificacion]            SMALLDATETIME NULL,
    [fecha_pet_reforma]                  SMALLDATETIME NULL,
    [fecha_ent_reforma]                  SMALLDATETIME NULL,
    [fecha_pit_ref]                      SMALLDATETIME NULL,
    [fecha_eit_ref]                      SMALLDATETIME NULL,
    [fecha_ci_ref]                       SMALLDATETIME NULL,
    [fecha_cg_ref]                       SMALLDATETIME NULL,
    [fecha_pet_actual_precios]           SMALLDATETIME NULL,
    [fecha_ent_actual_precios]           SMALLDATETIME NULL,
    [fecha_envio_fiscalizacion]          SMALLDATETIME NULL,
    [fecha_com_inf]                      SMALLDATETIME NULL,
    [fecha_com_gob]                      SMALLDATETIME NULL,
    [Punto]                              CHAR (10)     NULL,
    [fecha_fiscalizacion]                SMALLDATETIME NULL,
    [fecha_remision_contratacion]        SMALLDATETIME NULL,
    [fecha_dto]                          SMALLDATETIME NULL,
    [nro_dto]                            SMALLINT      NULL,
    [estado_fase]                        CHAR (3)      NULL,
    [clase_exp]                          CHAR (2)      NULL,
    [tipo_proc]                          CHAR (2)      NULL,
    [forma_cont]                         CHAR (2)      NULL,
    [Requiere_PlanSyS]                   BIT           CONSTRAINT [DF_FasesDeProyectos_Requiere_PlanSyS] DEFAULT (1) NOT NULL,
    [importe_fase]                       FLOAT (53)    NULL,
    [presu_gral_ejecucion_material]      FLOAT (53)    NULL,
    [importe_gastos_generales]           FLOAT (53)    NULL,
    [importe_beneficio_industriales]     FLOAT (53)    NULL,
    [importe_control_calidad]            FLOAT (53)    NULL,
    [iva]                                FLOAT (53)    NULL,
    [subcontrata]                        FLOAT (53)    NULL,
    [honorarios_dir]                     FLOAT (53)    NULL,
    [HD_ExcluidoIVA]                     BIT           CONSTRAINT [DF_FasesDeProyectos_HD_ExcIVA] DEFAULT (0) NOT NULL,
    [honorarios_red]                     FLOAT (53)    NULL,
    [HR_ExcluidoIVA]                     BIT           CONSTRAINT [DF_FasesDeProyectos_HR_ExcluidoIVA] DEFAULT (0) NOT NULL,
    [importePlanSyS]                     FLOAT (53)    NULL,
    [CriteriosAdjudicacion]              VARCHAR (50)  NULL,
    [num_reg]                            NCHAR (50)    NULL,
    [fec_reg]                            SMALLDATETIME NULL,
    CONSTRAINT [tmp_ms_xx_constraint_PK_FasesDeProyectos_11] PRIMARY KEY NONCLUSTERED ([MUNICIPIO] ASC, [AO_PROYECTO] ASC, [NUMERO_PROYECTO] ASC, [NUMERO_FASE] ASC, [AO_FASE] ASC) WITH (FILLFACTOR = 90)
);

IF EXISTS (SELECT TOP 1 1 
           FROM   [dbo].[FasesDeProyectos])
    BEGIN
        INSERT INTO [dbo].[tmp_ms_xx_FasesDeProyectos] ([MUNICIPIO], [AO_PROYECTO], [NUMERO_PROYECTO], [NUMERO_FASE], [AO_FASE], [servicio_gestor], [importe_fase_Pts], [Codigo_Plan], [referencia], [subreferencia], [ao_ejecucion_obra], [carretera], [plazo], [UnidadPlazo], [nro_ejemplares], [grupo], [subgrupo], [categoria], [revision], [formula], [formula2], [formula3], [formula4], [organismo_direccion], [servicio_direccion], [director_tecnico_obra], [ColegioOficialDireccion], [SubvencionEconDireccion], [RedactorPlanSS], [presu_gral_ejecucion_material_Pts], [por_gastos_generales], [importe_gastos_generales_Pts], [por_beneficio_industriales], [importe_beneficio_industriales_Pts], [por_control_calidad], [importe_control_calidad_Pts], [Por_iva], [iva_Pts], [por_subcontrata], [subcontrata_Pts], [honorarios_dir_Pts], [honorarios_red_Pts], [fecha_rem_fase], [fecha_ent_fase], [fecha_remision_ayto], [fecha_aprobacion_ayto], [fecha_remision_junta], [fecha_visado_junta], [fecha_pet_inf_tecnico_contrata], [fecha_ent_inf_tecnico_contrata], [pliego_clausulas_particulares], [fecha_pet_desglose], [fecha_ent_desglose], [fecha_pet_rectificacion], [fecha_ent_rectificacion], [fecha_pet_reforma], [fecha_ent_reforma], [fecha_pit_ref], [fecha_eit_ref], [fecha_ci_ref], [fecha_cg_ref], [fecha_pet_actual_precios], [fecha_ent_actual_precios], [fecha_envio_fiscalizacion], [fecha_com_inf], [fecha_com_gob], [Punto], [fecha_fiscalizacion], [fecha_remision_contratacion], [fecha_dto], [nro_dto], [estado_fase], [clase_exp], [tipo_proc], [forma_cont], [Requiere_PlanSyS], [importe_fase], [presu_gral_ejecucion_material], [importe_gastos_generales], [importe_beneficio_industriales], [importe_control_calidad], [iva], [subcontrata], [honorarios_dir], [HD_ExcluidoIVA], [honorarios_red], [HR_ExcluidoIVA], [importePlanSyS], [CriteriosAdjudicacion], [fec_reg], [num_reg])
        SELECT [MUNICIPIO],
               [AO_PROYECTO],
               [NUMERO_PROYECTO],
               [NUMERO_FASE],
               [AO_FASE],
               [servicio_gestor],
               [importe_fase_Pts],
               [Codigo_Plan],
               [referencia],
               [subreferencia],
               [ao_ejecucion_obra],
               [carretera],
               [plazo],
               [UnidadPlazo],
               [nro_ejemplares],
               [grupo],
               [subgrupo],
               [categoria],
               [revision],
               [formula],
               [formula2],
               [formula3],
               [formula4],
               [organismo_direccion],
               [servicio_direccion],
               [director_tecnico_obra],
               [ColegioOficialDireccion],
               [SubvencionEconDireccion],
               [RedactorPlanSS],
               [presu_gral_ejecucion_material_Pts],
               [por_gastos_generales],
               [importe_gastos_generales_Pts],
               [por_beneficio_industriales],
               [importe_beneficio_industriales_Pts],
               [por_control_calidad],
               [importe_control_calidad_Pts],
               [Por_iva],
               [iva_Pts],
               [por_subcontrata],
               [subcontrata_Pts],
               [honorarios_dir_Pts],
               [honorarios_red_Pts],
               [fecha_rem_fase],
               [fecha_ent_fase],
               [fecha_remision_ayto],
               [fecha_aprobacion_ayto],
               [fecha_remision_junta],
               [fecha_visado_junta],
               [fecha_pet_inf_tecnico_contrata],
               [fecha_ent_inf_tecnico_contrata],
               [pliego_clausulas_particulares],
               [fecha_pet_desglose],
               [fecha_ent_desglose],
               [fecha_pet_rectificacion],
               [fecha_ent_rectificacion],
               [fecha_pet_reforma],
               [fecha_ent_reforma],
               [fecha_pit_ref],
               [fecha_eit_ref],
               [fecha_ci_ref],
               [fecha_cg_ref],
               [fecha_pet_actual_precios],
               [fecha_ent_actual_precios],
               [fecha_envio_fiscalizacion],
               [fecha_com_inf],
               [fecha_com_gob],
               [Punto],
               [fecha_fiscalizacion],
               [fecha_remision_contratacion],
               [fecha_dto],
               [nro_dto],
               [estado_fase],
               [clase_exp],
               [tipo_proc],
               [forma_cont],
               [Requiere_PlanSyS],
               [importe_fase],
               [presu_gral_ejecucion_material],
               [importe_gastos_generales],
               [importe_beneficio_industriales],
               [importe_control_calidad],
               [iva],
               [subcontrata],
               [honorarios_dir],
               [HD_ExcluidoIVA],
               [honorarios_red],
               [HR_ExcluidoIVA],
               [importePlanSyS],
               [CriteriosAdjudicacion],
               [fec_reg],
               [num_reg]
        FROM   [dbo].[FasesDeProyectos];
    END

DROP TABLE [dbo].[FasesDeProyectos];

EXECUTE sp_rename N'[dbo].[tmp_ms_xx_FasesDeProyectos]', N'FasesDeProyectos';

EXECUTE sp_rename N'[dbo].[tmp_ms_xx_constraint_PK_FasesDeProyectos_11]', N'PK_FasesDeProyectos_1', N'OBJECT';

COMMIT TRANSACTION;

SET TRANSACTION ISOLATION LEVEL READ COMMITTED;


GO
PRINT N'Modificando Tabla [dbo].[firmante_documentos]...';


GO
ALTER TABLE [dbo].[firmante_documentos] ALTER COLUMN [created_at] SMALLDATETIME NULL;

ALTER TABLE [dbo].[firmante_documentos] ALTER COLUMN [fecha_firma] SMALLDATETIME NOT NULL;

ALTER TABLE [dbo].[firmante_documentos] ALTER COLUMN [updated_at] SMALLDATETIME NULL;


GO
PRINT N'Modificando Tabla [dbo].[firmante_genericos]...';


GO
ALTER TABLE [dbo].[firmante_genericos] ALTER COLUMN [created_at] SMALLDATETIME NULL;

ALTER TABLE [dbo].[firmante_genericos] ALTER COLUMN [updated_at] SMALLDATETIME NULL;


GO
PRINT N'Modificando Tabla [dbo].[Honorarios]...';


GO
ALTER TABLE [dbo].[Honorarios] DROP COLUMN [created_at], COLUMN [Expediente], COLUMN [team_id], COLUMN [updated_at];


GO
/*
Se está quitando la columna [dbo].[ImportesDeObras].[expediente_id]; puede que se pierdan datos.
*/
GO
PRINT N'Iniciando recompilación de la tabla [dbo].[ImportesDeObras]...';


GO
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

SET XACT_ABORT ON;

CREATE TABLE [dbo].[tmp_ms_xx_ImportesDeObras] (
    [Codigo_Plan]                                 CHAR (7)      NOT NULL,
    [numero_obra]                                 INT           NOT NULL,
    [subreferencia]                               TINYINT       NOT NULL,
    [ao_ejecucion]                                SMALLINT      NOT NULL,
    [importe_aprobado_Pts]                        FLOAT (53)    CONSTRAINT [DF_ImportesDeObras_importe_aprobado_Pts] DEFAULT (0) NULL,
    [importe_remanente_Pts]                       FLOAT (53)    CONSTRAINT [DF_ImportesDeObras_importe_remanente_Pts] DEFAULT (0) NULL,
    [importe_a_contratar_Pts]                     FLOAT (53)    CONSTRAINT [DF_ImportesDeObras_importe_a_contratar_Pts] DEFAULT (0) NULL,
    [importe_baja_contratacion_Pts]               FLOAT (53)    CONSTRAINT [DF_ImportesDeObras_importe_baja_contratacion_Pts] DEFAULT (0) NULL,
    [importe_adjudicacion_Pts]                    FLOAT (53)    CONSTRAINT [DF_ImportesDeObras_importe_adjudicacion_Pts] DEFAULT (0) NULL,
    [importe_ejecutado_Pts]                       FLOAT (53)    CONSTRAINT [DF_ImportesDeObras_importe_ejecutado_Pts] DEFAULT (0) NULL,
    [importe_ejecutado_decreto_Pts]               FLOAT (53)    CONSTRAINT [DF_ImportesDeObras_importe_ejecutado_decreto_Pts] DEFAULT (0) NULL,
    [importe_PenalidadesProrrogas_Pts]            FLOAT (53)    CONSTRAINT [DF_ImportesDeObras_importe_PenalidadesProrrogas_Pts] DEFAULT (0) NULL,
    [importe_Descontado_PenalidadesProrrogas_Pts] FLOAT (53)    CONSTRAINT [DF_ImportesDeObras_importe_Descontado_PenalidadesProrrogas_Pts] DEFAULT (0) NULL,
    [importe_aprobado]                            FLOAT (53)    CONSTRAINT [DF_ImportesDeObras_importe_aprobado] DEFAULT (0) NULL,
    [importe_remanente]                           FLOAT (53)    CONSTRAINT [DF_ImportesDeObras_importe_remanente] DEFAULT (0) NULL,
    [importe_a_contratar]                         FLOAT (53)    CONSTRAINT [DF_ImportesDeObras_importe_a_contratar] DEFAULT (0) NULL,
    [importe_baja_contratacion]                   FLOAT (53)    CONSTRAINT [DF_ImportesDeObras_importe_baja_contratacion] DEFAULT (0) NULL,
    [importe_adjudicacion]                        FLOAT (53)    CONSTRAINT [DF_ImportesDeObras_importe_adjudicacion] DEFAULT (0) NULL,
    [importe_ejecutado]                           FLOAT (53)    CONSTRAINT [DF_ImportesDeObras_importe_ejecutado] DEFAULT (0) NULL,
    [importe_ejecutado_decreto]                   FLOAT (53)    CONSTRAINT [DF_ImportesDeObras_importe_ejecutado_decreto] DEFAULT (0) NULL,
    [importe_PenalidadesProrrogas]                FLOAT (53)    CONSTRAINT [DF_ImportesDeObras_importe_PenalidadesProrrogas] DEFAULT (0) NULL,
    [importe_Descontado_PenalidadesProrrogas]     FLOAT (53)    CONSTRAINT [DF_ImportesDeObras_importe_Descontado_PenalidadesProrrogas] DEFAULT (0) NULL,
    [importe_Modificado]                          FLOAT (53)    NULL,
    [importe_regularizacion_iva]                  FLOAT (53)    CONSTRAINT [DF_ImportesDeObras_importe_regularizacion_iva] DEFAULT (0) NULL,
    [Expediente]                                  NVARCHAR (50) NULL,
    [team_id]                                     BIGINT        NULL,
    [created_at]                                  DATETIME2 (7) NULL,
    [updated_at]                                  DATETIME2 (7) NULL,
    CONSTRAINT [tmp_ms_xx_constraint_PK_ImportesDeObras1] PRIMARY KEY NONCLUSTERED ([Codigo_Plan] ASC, [numero_obra] ASC, [subreferencia] ASC, [ao_ejecucion] ASC) WITH (FILLFACTOR = 90)
);

IF EXISTS (SELECT TOP 1 1 
           FROM   [dbo].[ImportesDeObras])
    BEGIN
        INSERT INTO [dbo].[tmp_ms_xx_ImportesDeObras] ([Codigo_Plan], [numero_obra], [subreferencia], [ao_ejecucion], [importe_aprobado_Pts], [importe_remanente_Pts], [importe_a_contratar_Pts], [importe_baja_contratacion_Pts], [importe_adjudicacion_Pts], [importe_ejecutado_Pts], [importe_ejecutado_decreto_Pts], [importe_PenalidadesProrrogas_Pts], [importe_Descontado_PenalidadesProrrogas_Pts], [importe_aprobado], [importe_remanente], [importe_a_contratar], [importe_baja_contratacion], [importe_adjudicacion], [importe_ejecutado], [importe_ejecutado_decreto], [importe_PenalidadesProrrogas], [importe_Descontado_PenalidadesProrrogas], [importe_Modificado], [importe_regularizacion_iva], [team_id], [created_at], [updated_at])
        SELECT [Codigo_Plan],
               [numero_obra],
               [subreferencia],
               [ao_ejecucion],
               [importe_aprobado_Pts],
               [importe_remanente_Pts],
               [importe_a_contratar_Pts],
               [importe_baja_contratacion_Pts],
               [importe_adjudicacion_Pts],
               [importe_ejecutado_Pts],
               [importe_ejecutado_decreto_Pts],
               [importe_PenalidadesProrrogas_Pts],
               [importe_Descontado_PenalidadesProrrogas_Pts],
               [importe_aprobado],
               [importe_remanente],
               [importe_a_contratar],
               [importe_baja_contratacion],
               [importe_adjudicacion],
               [importe_ejecutado],
               [importe_ejecutado_decreto],
               [importe_PenalidadesProrrogas],
               [importe_Descontado_PenalidadesProrrogas],
               [importe_Modificado],
               [importe_regularizacion_iva],
               [team_id],
               [created_at],
               [updated_at]
        FROM   [dbo].[ImportesDeObras];
    END

DROP TABLE [dbo].[ImportesDeObras];

EXECUTE sp_rename N'[dbo].[tmp_ms_xx_ImportesDeObras]', N'ImportesDeObras';

EXECUTE sp_rename N'[dbo].[tmp_ms_xx_constraint_PK_ImportesDeObras1]', N'PK_ImportesDeObras', N'OBJECT';

COMMIT TRANSACTION;

SET TRANSACTION ISOLATION LEVEL READ COMMITTED;


GO
/*
Se está quitando la columna [dbo].[ImportesPorOrganismo].[expediente_id]; puede que se pierdan datos.
*/
GO
PRINT N'Iniciando recompilación de la tabla [dbo].[ImportesPorOrganismo]...';


GO
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

SET XACT_ABORT ON;

CREATE TABLE [dbo].[tmp_ms_xx_ImportesPorOrganismo] (
    [Codigo_Plan]                   CHAR (7)      NOT NULL,
    [numero_obra]                   SMALLINT      NOT NULL,
    [subreferencia]                 TINYINT       NOT NULL,
    [ao_ejecucion]                  SMALLINT      NOT NULL,
    [organismo]                     CHAR (8)      NOT NULL,
    [Porc_imp_aprobado]             FLOAT (53)    CONSTRAINT [DF_ImportesPorOrganismo_Porc_imp_aprobado] DEFAULT ((0)) NULL,
    [importe_aprobado_Pts]          FLOAT (53)    CONSTRAINT [DF_ImportesPorOrganismo_importe_aprobado_Pts] DEFAULT ((0)) NULL,
    [importe_remanente_Pts]         FLOAT (53)    CONSTRAINT [DF_ImportesPorOrganismo_importe_remanente_Pts] DEFAULT ((0)) NULL,
    [Porc_imp_contratar]            FLOAT (53)    CONSTRAINT [DF_ImportesPorOrganismo_Porc_imp_contratar] DEFAULT ((0)) NULL,
    [importe_a_contratar_Pts]       FLOAT (53)    CONSTRAINT [DF_ImportesPorOrganismo_importe_a_contratar_Pts] DEFAULT ((0)) NULL,
    [Porc_imp_baja]                 FLOAT (53)    CONSTRAINT [DF_ImportesPorOrganismo_Porc_imp_baja] DEFAULT ((0)) NULL,
    [importe_baja_contratacion_Pts] FLOAT (53)    CONSTRAINT [DF_ImportesPorOrganismo_importe_baja_contratacion_Pts] DEFAULT ((0)) NULL,
    [Porc_imp_adjudicado]           FLOAT (53)    CONSTRAINT [DF_ImportesPorOrganismo_Porc_imp_adjudicado] DEFAULT ((0)) NULL,
    [importe_adjudicacion_Pts]      FLOAT (53)    CONSTRAINT [DF_ImportesPorOrganismo_importe_adjudicacion_Pts] DEFAULT ((0)) NULL,
    [Porc_imp_ejecutado]            FLOAT (53)    CONSTRAINT [DF_ImportesPorOrganismo_Porc_imp_ejecutado] DEFAULT ((0)) NULL,
    [importe_ejecutado_Pts]         FLOAT (53)    CONSTRAINT [DF_ImportesPorOrganismo_importe_ejecutado_Pts] DEFAULT ((0)) NULL,
    [importe_ejecutado_decreto_Pts] FLOAT (53)    CONSTRAINT [DF_ImportesPorOrganismo_importe_ejecutado_decreto_Pts] DEFAULT ((0)) NULL,
    [importe_aprobado]              FLOAT (53)    CONSTRAINT [DF_ImportesPorOrganismo_importe_aprobado] DEFAULT ((0)) NULL,
    [importe_remanente]             FLOAT (53)    CONSTRAINT [DF_ImportesPorOrganismo_importe_remanente] DEFAULT ((0)) NULL,
    [importe_a_contratar]           FLOAT (53)    CONSTRAINT [DF_ImportesPorOrganismo_importe_a_contratar] DEFAULT ((0)) NULL,
    [importe_baja_contratacion]     FLOAT (53)    CONSTRAINT [DF_ImportesPorOrganismo_importe_baja_contratacion] DEFAULT ((0)) NULL,
    [importe_adjudicacion]          FLOAT (53)    CONSTRAINT [DF_ImportesPorOrganismo_importe_adjudicacion] DEFAULT ((0)) NULL,
    [importe_ejecutado]             FLOAT (53)    CONSTRAINT [DF_ImportesPorOrganismo_importe_ejecutado] DEFAULT ((0)) NULL,
    [importe_ejecutado_decreto]     FLOAT (53)    CONSTRAINT [DF_ImportesPorOrganismo_importe_ejecutado_decreto] DEFAULT ((0)) NULL,
    [Prioridad]                     TINYINT       CONSTRAINT [DF_ImportesPorOrganismo_Prioridad] DEFAULT ((0)) NOT NULL,
    [AgrupaOrg]                     TINYINT       CONSTRAINT [DF_ImportesPorOrganismo_AgrupaOrg] DEFAULT ((0)) NOT NULL,
    [Porc_ModContratos]             FLOAT (53)    CONSTRAINT [DF_ImportesPorOrganismo_Porc_ModContratos] DEFAULT ((0)) NULL,
    [importe_ModContratos]          FLOAT (53)    CONSTRAINT [DF_ImportesPorOrganismo_importe_ModContratos] DEFAULT ((0)) NULL,
    [Expediente]                    NVARCHAR (50) NULL,
    [team_id]                       BIGINT        NULL,
    [created_at]                    DATETIME2 (7) NULL,
    [updated_at]                    DATETIME2 (7) NULL,
    CONSTRAINT [tmp_ms_xx_constraint_PK_ImportesPorOrganismo1] PRIMARY KEY NONCLUSTERED ([Codigo_Plan] ASC, [numero_obra] ASC, [subreferencia] ASC, [ao_ejecucion] ASC, [organismo] ASC) WITH (FILLFACTOR = 90)
);

IF EXISTS (SELECT TOP 1 1 
           FROM   [dbo].[ImportesPorOrganismo])
    BEGIN
        INSERT INTO [dbo].[tmp_ms_xx_ImportesPorOrganismo] ([Codigo_Plan], [numero_obra], [subreferencia], [ao_ejecucion], [organismo], [Porc_imp_aprobado], [importe_aprobado_Pts], [importe_remanente_Pts], [Porc_imp_contratar], [importe_a_contratar_Pts], [Porc_imp_baja], [importe_baja_contratacion_Pts], [Porc_imp_adjudicado], [importe_adjudicacion_Pts], [Porc_imp_ejecutado], [importe_ejecutado_Pts], [importe_ejecutado_decreto_Pts], [importe_aprobado], [importe_remanente], [importe_a_contratar], [importe_baja_contratacion], [importe_adjudicacion], [importe_ejecutado], [importe_ejecutado_decreto], [Prioridad], [AgrupaOrg], [Porc_ModContratos], [importe_ModContratos], [team_id], [created_at], [updated_at])
        SELECT [Codigo_Plan],
               [numero_obra],
               [subreferencia],
               [ao_ejecucion],
               [organismo],
               [Porc_imp_aprobado],
               [importe_aprobado_Pts],
               [importe_remanente_Pts],
               [Porc_imp_contratar],
               [importe_a_contratar_Pts],
               [Porc_imp_baja],
               [importe_baja_contratacion_Pts],
               [Porc_imp_adjudicado],
               [importe_adjudicacion_Pts],
               [Porc_imp_ejecutado],
               [importe_ejecutado_Pts],
               [importe_ejecutado_decreto_Pts],
               [importe_aprobado],
               [importe_remanente],
               [importe_a_contratar],
               [importe_baja_contratacion],
               [importe_adjudicacion],
               [importe_ejecutado],
               [importe_ejecutado_decreto],
               [Prioridad],
               [AgrupaOrg],
               [Porc_ModContratos],
               [importe_ModContratos],
               [team_id],
               [created_at],
               [updated_at]
        FROM   [dbo].[ImportesPorOrganismo];
    END

DROP TABLE [dbo].[ImportesPorOrganismo];

EXECUTE sp_rename N'[dbo].[tmp_ms_xx_ImportesPorOrganismo]', N'ImportesPorOrganismo';

EXECUTE sp_rename N'[dbo].[tmp_ms_xx_constraint_PK_ImportesPorOrganismo1]', N'PK_ImportesPorOrganismo', N'OBJECT';

COMMIT TRANSACTION;

SET TRANSACTION ISOLATION LEVEL READ COMMITTED;


GO
PRINT N'Iniciando recompilación de la tabla [dbo].[jobs]...';


GO
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

SET XACT_ABORT ON;

CREATE TABLE [dbo].[tmp_ms_xx_jobs] (
    [id]           BIGINT         NOT NULL,
    [queue]        NVARCHAR (255) NOT NULL,
    [payload]      NVARCHAR (MAX) NOT NULL,
    [attempts]     TINYINT        NOT NULL,
    [reserved_at]  INT            NULL,
    [available_at] INT            NOT NULL,
    [created_at]   INT            NOT NULL
);

IF EXISTS (SELECT TOP 1 1 
           FROM   [dbo].[jobs])
    BEGIN
        INSERT INTO [dbo].[tmp_ms_xx_jobs] ([id], [queue], [payload], [attempts], [reserved_at], [available_at], [created_at])
        SELECT [id],
               [queue],
               [payload],
               [attempts],
               [reserved_at],
               [available_at],
               [created_at]
        FROM   [dbo].[jobs];
    END

DROP TABLE [dbo].[jobs];

EXECUTE sp_rename N'[dbo].[tmp_ms_xx_jobs]', N'jobs';

COMMIT TRANSACTION;

SET TRANSACTION ISOLATION LEVEL READ COMMITTED;


GO
PRINT N'Modificando Tabla [dbo].[Justificacion_Obras]...';


GO
ALTER TABLE [dbo].[Justificacion_Obras] ALTER COLUMN [created_at] SMALLDATETIME NULL;

ALTER TABLE [dbo].[Justificacion_Obras] ALTER COLUMN [Fec_Fin_just] SMALLDATETIME NULL;

ALTER TABLE [dbo].[Justificacion_Obras] ALTER COLUMN [Fec_inicio_just] SMALLDATETIME NULL;

ALTER TABLE [dbo].[Justificacion_Obras] ALTER COLUMN [update_at] SMALLDATETIME NULL;


GO
PRINT N'Iniciando recompilación de la tabla [dbo].[migrations]...';


GO
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

SET XACT_ABORT ON;

CREATE TABLE [dbo].[tmp_ms_xx_migrations] (
    [id]        INT            NOT NULL,
    [migration] NVARCHAR (255) NOT NULL,
    [batch]     INT            NOT NULL
);

IF EXISTS (SELECT TOP 1 1 
           FROM   [dbo].[migrations])
    BEGIN
        INSERT INTO [dbo].[tmp_ms_xx_migrations] ([id], [migration], [batch])
        SELECT [id],
               [migration],
               [batch]
        FROM   [dbo].[migrations];
    END

DROP TABLE [dbo].[migrations];

EXECUTE sp_rename N'[dbo].[tmp_ms_xx_migrations]', N'migrations';

COMMIT TRANSACTION;

SET TRANSACTION ISOLATION LEVEL READ COMMITTED;


GO
PRINT N'Modificando Tabla [dbo].[model_has_permissions]...';


GO
ALTER TABLE [dbo].[model_has_permissions] ALTER COLUMN [model_type] NVARCHAR (255) NOT NULL;


GO
PRINT N'Modificando Tabla [dbo].[model_has_roles]...';


GO
ALTER TABLE [dbo].[model_has_roles] ALTER COLUMN [model_type] NVARCHAR (255) NOT NULL;


GO
PRINT N'Modificando Tabla [dbo].[Obras_En_Ejecucion]...';


GO
ALTER TABLE [dbo].[Obras_En_Ejecucion] DROP COLUMN [team_id];


GO
/*
Se está quitando la columna [dbo].[ObrasCedidas].[expediente_id]; puede que se pierdan datos.
*/
GO
PRINT N'Iniciando recompilación de la tabla [dbo].[ObrasCedidas]...';


GO
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

SET XACT_ABORT ON;

CREATE TABLE [dbo].[tmp_ms_xx_ObrasCedidas] (
    [Codigo_Plan]             CHAR (7)      NOT NULL,
    [numero_obra]             SMALLINT      NOT NULL,
    [subreferencia]           TINYINT       NOT NULL,
    [ao_ejecucion]            SMALLINT      NOT NULL,
    [FechaRemisionAyto]       SMALLDATETIME NULL,
    [FechaRecepcionCerti]     SMALLDATETIME NULL,
    [FechaCesion]             SMALLDATETIME NULL,
    [FechaAdjudicacion]       SMALLDATETIME NULL,
    [ImporteAdjudicacion_Pts] FLOAT (53)    NULL,
    [NombreContratista]       VARCHAR (70)  NULL,
    [DomicilioContratista]    VARCHAR (80)  NULL,
    [CPostalContratista]      INT           NULL,
    [Ciudad]                  VARCHAR (50)  NULL,
    [CodMunContratista]       SMALLINT      NULL,
    [NIFContratista]          VARCHAR (15)  NULL,
    [FechaContrato]           SMALLDATETIME NULL,
    [FechaRemisionInterv]     SMALLDATETIME NULL,
    [ImporteAdjudicacion]     FLOAT (53)    NULL,
    [TelefContratista]        VARCHAR (50)  NULL,
    [NuevaLey]                BIT           CONSTRAINT [DF_ObrasCedidas_NuevaLey] DEFAULT (0) NOT NULL,
    [Expediente]              NVARCHAR (50) NULL,
    [team_id]                 BIGINT        NULL,
    [created_at]              DATETIME2 (7) NULL,
    [updated_at]              DATETIME2 (7) NULL,
    CONSTRAINT [tmp_ms_xx_constraint_PK_ObrasCedidas1] PRIMARY KEY NONCLUSTERED ([Codigo_Plan] ASC, [numero_obra] ASC, [subreferencia] ASC, [ao_ejecucion] ASC) WITH (FILLFACTOR = 90)
);

IF EXISTS (SELECT TOP 1 1 
           FROM   [dbo].[ObrasCedidas])
    BEGIN
        INSERT INTO [dbo].[tmp_ms_xx_ObrasCedidas] ([Codigo_Plan], [numero_obra], [subreferencia], [ao_ejecucion], [FechaRemisionAyto], [FechaRecepcionCerti], [FechaCesion], [FechaAdjudicacion], [ImporteAdjudicacion_Pts], [NombreContratista], [DomicilioContratista], [CPostalContratista], [Ciudad], [CodMunContratista], [NIFContratista], [FechaContrato], [FechaRemisionInterv], [ImporteAdjudicacion], [TelefContratista], [NuevaLey], [team_id], [created_at], [updated_at])
        SELECT [Codigo_Plan],
               [numero_obra],
               [subreferencia],
               [ao_ejecucion],
               [FechaRemisionAyto],
               [FechaRecepcionCerti],
               [FechaCesion],
               [FechaAdjudicacion],
               [ImporteAdjudicacion_Pts],
               [NombreContratista],
               [DomicilioContratista],
               [CPostalContratista],
               [Ciudad],
               [CodMunContratista],
               [NIFContratista],
               [FechaContrato],
               [FechaRemisionInterv],
               [ImporteAdjudicacion],
               [TelefContratista],
               [NuevaLey],
               [team_id],
               [created_at],
               [updated_at]
        FROM   [dbo].[ObrasCedidas];
    END

DROP TABLE [dbo].[ObrasCedidas];

EXECUTE sp_rename N'[dbo].[tmp_ms_xx_ObrasCedidas]', N'ObrasCedidas';

EXECUTE sp_rename N'[dbo].[tmp_ms_xx_constraint_PK_ObrasCedidas1]', N'PK_ObrasCedidas', N'OBJECT';

COMMIT TRANSACTION;

SET TRANSACTION ISOLATION LEVEL READ COMMITTED;


GO
PRINT N'Modificando Tabla [dbo].[ObrasRelacionadas]...';


GO
ALTER TABLE [dbo].[ObrasRelacionadas] DROP COLUMN [created_at], COLUMN [Expediente], COLUMN [team_id], COLUMN [updated_at];


GO
PRINT N'Modificando Tabla [dbo].[password_reset_tokens]...';


GO
ALTER TABLE [dbo].[password_reset_tokens] ALTER COLUMN [created_at] SMALLDATETIME NULL;


GO
/*
Se está quitando la columna [dbo].[PlanSeguridadYSalud].[created_at]; puede que se pierdan datos.

Se está quitando la columna [dbo].[PlanSeguridadYSalud].[expediente_id]; puede que se pierdan datos.

Se está quitando la columna [dbo].[PlanSeguridadYSalud].[team_id]; puede que se pierdan datos.

Se está quitando la columna [dbo].[PlanSeguridadYSalud].[updated_at]; puede que se pierdan datos.
*/
GO
PRINT N'Iniciando recompilación de la tabla [dbo].[PlanSeguridadYSalud]...';


GO
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

SET XACT_ABORT ON;

CREATE TABLE [dbo].[tmp_ms_xx_PlanSeguridadYSalud] (
    [Codigo_Plan]            CHAR (7)      NOT NULL,
    [Numero_obra]            SMALLINT      NOT NULL,
    [Subreferencia]          TINYINT       NOT NULL,
    [ao_ejecucion]           SMALLINT      NOT NULL,
    [FecPlanSyS]             SMALLDATETIME NULL,
    [FecPeticionInfTec]      SMALLDATETIME NULL,
    [FecRecepcionInfTec]     SMALLDATETIME NULL,
    [FecDevolucionInfTec]    SMALLDATETIME NULL,
    [FecPropuesta]           SMALLDATETIME NULL,
    [NumDecreto]             VARCHAR (10)  NULL,
    [FecDecreto]             SMALLDATETIME NULL,
    [FecComunicacionCont]    SMALLDATETIME NULL,
    [FecComunicacionTrabajo] SMALLDATETIME NULL,
    [FecRecepAprobAyto]      SMALLDATETIME NULL,
    [Coordinador]            NVARCHAR (50) NULL,
    [observaciones]          VARCHAR (250) NULL,
    [FecSolicitudPSA]        SMALLDATETIME NULL,
    [FecRequerimientoPSA]    SMALLDATETIME NULL,
    [FecReclaAprob]          SMALLDATETIME NULL,
    [FecRecepDev]            SMALLDATETIME NULL,
    [FecRecInfJefe]          SMALLDATETIME NULL,
    [FecReciboSol]           SMALLDATETIME NULL,
    [FecReciboReq]           SMALLDATETIME NULL,
    [FecPetInfCoor]          SMALLDATETIME NULL,
    [FecDevInfCoor]          SMALLDATETIME NULL,
    [FecDevPlanCoor]         SMALLDATETIME NULL,
    [FecDevPlanCoorLista]    VARCHAR (255) NULL,
    [FecRecepPlanCoor]       SMALLDATETIME NULL,
    [FecRecepPlanCoorLista]  VARCHAR (255) NULL,
    [FecRemCoor]             SMALLDATETIME NULL,
    [FecRecepContrato]       SMALLDATETIME NULL,
    [FecRecepAprobCoor]      SMALLDATETIME NULL,
    [FecRemAprobCoor]        SMALLDATETIME NULL,
    [FecRecepAvisoCoor]      SMALLDATETIME NULL,
    [FecSolAprobAyto]        SMALLDATETIME NULL,
    [FecEnvInfTecAyto]       SMALLDATETIME NULL,
    [CSVPSYS]                VARCHAR (50)  NULL,
    [CSVPGR]                 VARCHAR (50)  NULL,
    [NumRegistroPSYS]        VARCHAR (50)  NULL,
    [FecRecepPGR]            SMALLDATETIME NULL,
    [FecRecepPSYS]           SMALLDATETIME NULL,
    [NumRegistroPGR]         VARCHAR (50)  NULL,
    [InfPSYS]                VARCHAR (50)  NULL,
    [InfPGR]                 VARCHAR (50)  NULL,
    CONSTRAINT [tmp_ms_xx_constraint_PK_PlanSeguridadYSalud1] PRIMARY KEY NONCLUSTERED ([Codigo_Plan] ASC, [Numero_obra] ASC, [Subreferencia] ASC, [ao_ejecucion] ASC) WITH (FILLFACTOR = 90)
);

IF EXISTS (SELECT TOP 1 1 
           FROM   [dbo].[PlanSeguridadYSalud])
    BEGIN
        INSERT INTO [dbo].[tmp_ms_xx_PlanSeguridadYSalud] ([Codigo_Plan], [Numero_obra], [Subreferencia], [ao_ejecucion], [FecPlanSyS], [FecPeticionInfTec], [FecRecepcionInfTec], [FecDevolucionInfTec], [FecPropuesta], [NumDecreto], [FecDecreto], [FecComunicacionCont], [FecComunicacionTrabajo], [FecRecepAprobAyto], [Coordinador], [observaciones], [FecSolicitudPSA], [FecRequerimientoPSA], [FecReclaAprob], [FecRecepDev], [FecRecInfJefe], [FecReciboSol], [FecReciboReq], [FecPetInfCoor], [FecDevInfCoor], [FecDevPlanCoor], [FecDevPlanCoorLista], [FecRecepPlanCoor], [FecRecepPlanCoorLista], [FecRemCoor], [FecRecepContrato], [FecRecepAprobCoor], [FecRemAprobCoor], [FecRecepAvisoCoor], [FecSolAprobAyto], [FecEnvInfTecAyto], [CSVPSYS], [CSVPGR], [NumRegistroPSYS], [NumRegistroPGR], [FecRecepPSYS], [FecRecepPGR], [InfPSYS], [InfPGR])
        SELECT [Codigo_Plan],
               [Numero_obra],
               [Subreferencia],
               [ao_ejecucion],
               [FecPlanSyS],
               [FecPeticionInfTec],
               [FecRecepcionInfTec],
               [FecDevolucionInfTec],
               [FecPropuesta],
               [NumDecreto],
               [FecDecreto],
               [FecComunicacionCont],
               [FecComunicacionTrabajo],
               [FecRecepAprobAyto],
               [Coordinador],
               [observaciones],
               [FecSolicitudPSA],
               [FecRequerimientoPSA],
               [FecReclaAprob],
               [FecRecepDev],
               [FecRecInfJefe],
               [FecReciboSol],
               [FecReciboReq],
               [FecPetInfCoor],
               [FecDevInfCoor],
               [FecDevPlanCoor],
               [FecDevPlanCoorLista],
               [FecRecepPlanCoor],
               [FecRecepPlanCoorLista],
               [FecRemCoor],
               [FecRecepContrato],
               [FecRecepAprobCoor],
               [FecRemAprobCoor],
               [FecRecepAvisoCoor],
               [FecSolAprobAyto],
               [FecEnvInfTecAyto],
               [CSVPSYS],
               [CSVPGR],
               [NumRegistroPSYS],
               [NumRegistroPGR],
               [FecRecepPSYS],
               [FecRecepPGR],
               [InfPSYS],
               [InfPGR]
        FROM   [dbo].[PlanSeguridadYSalud];
    END

DROP TABLE [dbo].[PlanSeguridadYSalud];

EXECUTE sp_rename N'[dbo].[tmp_ms_xx_PlanSeguridadYSalud]', N'PlanSeguridadYSalud';

EXECUTE sp_rename N'[dbo].[tmp_ms_xx_constraint_PK_PlanSeguridadYSalud1]', N'PK_PlanSeguridadYSalud', N'OBJECT';

COMMIT TRANSACTION;

SET TRANSACTION ISOLATION LEVEL READ COMMITTED;


GO
PRINT N'Modificando Tabla [dbo].[Prorrogas]...';


GO
ALTER TABLE [dbo].[Prorrogas] DROP COLUMN [created_at], COLUMN [expediente_id], COLUMN [team_id], COLUMN [updated_at];


GO
PRINT N'Modificando Tabla [dbo].[ProrrogasConPenalidades]...';


GO
ALTER TABLE [dbo].[ProrrogasConPenalidades] DROP COLUMN [created_at], COLUMN [Expediente], COLUMN [team_id], COLUMN [updated_at];


GO
PRINT N'Modificando Tabla [dbo].[Proyectos]...';


GO
ALTER TABLE [dbo].[Proyectos] DROP COLUMN [ao_ejecucion], COLUMN [Codigo_Plan], COLUMN [created_at], COLUMN [director_tecnico], COLUMN [expediente_id], COLUMN [organismo_direccion], COLUMN [referencia], COLUMN [servicio_direccion], COLUMN [subreferencia], COLUMN [team_id], COLUMN [updated_at];


GO
PRINT N'Modificando Tabla [dbo].[roles]...';


GO
ALTER TABLE [dbo].[roles] ALTER COLUMN [created_at] SMALLDATETIME NULL;

ALTER TABLE [dbo].[roles] ALTER COLUMN [guard_name] NVARCHAR (255) NOT NULL;

ALTER TABLE [dbo].[roles] ALTER COLUMN [id] BIGINT NOT NULL;

ALTER TABLE [dbo].[roles] ALTER COLUMN [name] NVARCHAR (255) NOT NULL;

ALTER TABLE [dbo].[roles] ALTER COLUMN [updated_at] SMALLDATETIME NULL;


GO
PRINT N'Modificando Tabla [dbo].[SubContrataciones]...';


GO
ALTER TABLE [dbo].[SubContrataciones] DROP COLUMN [created_at], COLUMN [Expediente], COLUMN [team_id], COLUMN [updated_at];


GO
PRINT N'Modificando Tabla [dbo].[Subvenciones]...';


GO
ALTER TABLE [dbo].[Subvenciones] DROP COLUMN [created_at], COLUMN [expediente_id], COLUMN [team_id], COLUMN [updated_at];


GO
/*
El tipo de la columna created_at en la tabla [dbo].[teams] es  DATETIME NULL, pero se va a cambiar a  SMALLDATETIME NULL. Si la columna contiene datos no compatibles con el tipo  SMALLDATETIME NULL, podrían producirse pérdidas de datos y errores en la implementación.

El tipo de la columna updated_at en la tabla [dbo].[teams] es  DATETIME NULL, pero se va a cambiar a  SMALLDATETIME NULL. Si la columna contiene datos no compatibles con el tipo  SMALLDATETIME NULL, podrían producirse pérdidas de datos y errores en la implementación.
*/
GO
PRINT N'Iniciando recompilación de la tabla [dbo].[teams]...';


GO
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

SET XACT_ABORT ON;

CREATE TABLE [dbo].[tmp_ms_xx_teams] (
    [id]         BIGINT         NOT NULL,
    [name]       NVARCHAR (255) NOT NULL,
    [created_at] SMALLDATETIME  NULL,
    [updated_at] SMALLDATETIME  NULL,
    [slug]       NCHAR (50)     NULL,
    [domain]     NCHAR (50)     NULL
);

IF EXISTS (SELECT TOP 1 1 
           FROM   [dbo].[teams])
    BEGIN
        INSERT INTO [dbo].[tmp_ms_xx_teams] ([id], [name], [created_at], [updated_at], [slug], [domain])
        SELECT [id],
               [name],
               [created_at],
               [updated_at],
               [slug],
               [domain]
        FROM   [dbo].[teams];
    END

DROP TABLE [dbo].[teams];

EXECUTE sp_rename N'[dbo].[tmp_ms_xx_teams]', N'teams';

COMMIT TRANSACTION;

SET TRANSACTION ISOLATION LEVEL READ COMMITTED;


GO
PRINT N'Modificando Tabla [dbo].[TiposAvisos]...';


GO
ALTER TABLE [dbo].[TiposAvisos]
    ADD [solucionable] BIT NULL;


GO
/*
Se está quitando la columna [dbo].[users].[app_authentication_recovery_codes]; puede que se pierdan datos.

Se está quitando la columna [dbo].[users].[app_authentication_secret]; puede que se pierdan datos.

Se está quitando la columna [dbo].[users].[first_password_set_at]; puede que se pierdan datos.

Se está quitando la columna [dbo].[users].[interno]; puede que se pierdan datos.

Se está quitando la columna [dbo].[users].[is_active]; puede que se pierdan datos.

El tipo de la columna created_at en la tabla [dbo].[users] es  DATETIME2 (0) NULL, pero se va a cambiar a  SMALLDATETIME NULL. Si la columna contiene datos no compatibles con el tipo  SMALLDATETIME NULL, podrían producirse pérdidas de datos y errores en la implementación.

El tipo de la columna email_verified_at en la tabla [dbo].[users] es  DATETIME NULL, pero se va a cambiar a  SMALLDATETIME NULL. Si la columna contiene datos no compatibles con el tipo  SMALLDATETIME NULL, podrían producirse pérdidas de datos y errores en la implementación.

El tipo de la columna updated_at en la tabla [dbo].[users] es  DATETIME2 (0) NULL, pero se va a cambiar a  SMALLDATETIME NULL. Si la columna contiene datos no compatibles con el tipo  SMALLDATETIME NULL, podrían producirse pérdidas de datos y errores en la implementación.
*/
GO
PRINT N'Iniciando recompilación de la tabla [dbo].[users]...';


GO
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

SET XACT_ABORT ON;

CREATE TABLE [dbo].[tmp_ms_xx_users] (
    [id]                BIGINT         NOT NULL,
    [name]              NVARCHAR (255) NOT NULL,
    [email]             NVARCHAR (255) NOT NULL,
    [email_verified_at] SMALLDATETIME  NULL,
    [password]          NVARCHAR (255) NOT NULL,
    [remember_token]    NVARCHAR (100) NULL,
    [created_at]        SMALLDATETIME  NULL,
    [updated_at]        SMALLDATETIME  NULL,
    [departamento]      SMALLINT       NULL,
    [nombre_persona]    VARCHAR (60)   NULL,
    [inicales]          CHAR (5)       NULL,
    [Tfno]              NCHAR (10)     NULL,
    [id_usuario]        CHAR (13)      NULL
);

IF EXISTS (SELECT TOP 1 1 
           FROM   [dbo].[users])
    BEGIN
        INSERT INTO [dbo].[tmp_ms_xx_users] ([id], [name], [email], [email_verified_at], [password], [remember_token], [created_at], [updated_at], [departamento], [nombre_persona], [inicales], [Tfno], [id_usuario])
        SELECT [id],
               [name],
               [email],
               [email_verified_at],
               [password],
               [remember_token],
               [created_at],
               [updated_at],
               [departamento],
               [nombre_persona],
               [inicales],
               [Tfno],
               [id_usuario]
        FROM   [dbo].[users];
    END

DROP TABLE [dbo].[users];

EXECUTE sp_rename N'[dbo].[tmp_ms_xx_users]', N'users';

COMMIT TRANSACTION;

SET TRANSACTION ISOLATION LEVEL READ COMMITTED;


GO
PRINT N'Creando Tabla [dbo].[expedientes]...';


GO
CREATE TABLE [dbo].[expedientes] (
    [id]              BIGINT         NOT NULL,
    [cod_programa]    NVARCHAR (255) NOT NULL,
    [ao_ejecucion]    INT            NOT NULL,
    [referencia]      INT            NOT NULL,
    [subreferencia]   INT            NOT NULL,
    [nombre_obra]     NVARCHAR (255) NOT NULL,
    [cod_estado]      NCHAR (3)      NOT NULL,
    [cod_estado_help] NVARCHAR (255) NOT NULL,
    [forma_ejecucion] NCHAR (3)      NULL,
    [team_id]         BIGINT         NULL,
    [Expediente]      NVARCHAR (50)  NULL,
    [created_at]      DATETIME2 (7)  NULL,
    [updated_at]      DATETIME2 (7)  NULL
);


GO
PRINT N'Creando Tabla [dbo].[reglas_validacion_fechas]...';


GO
CREATE TABLE [dbo].[reglas_validacion_fechas] (
    [nombre]             NVARCHAR (255) NULL,
    [modelo]             NVARCHAR (50)  NULL,
    [campo]              NVARCHAR (50)  NULL,
    [modelo_relacionado] NVARCHAR (50)  NULL,
    [campo_relacionado]  NVARCHAR (50)  NULL,
    [tipo_validacion]    NCHAR (10)     NULL,
    [metodo_relacionado] NVARCHAR (50)  NULL,
    [mensaje_error]      NVARCHAR (255) NULL,
    [activa]             BIT            NULL,
    [periodo]            SMALLINT       NULL,
    [id]                 INT            NOT NULL,
    CONSTRAINT [id_pk] PRIMARY KEY CLUSTERED ([id] ASC)
);


GO
PRINT N'Creando Restricción DEFAULT [dbo].[DF_DatosInicioDeObras_Mapper]...';


GO
ALTER TABLE [dbo].[DatosInicioDeObras]
    ADD CONSTRAINT [DF_DatosInicioDeObras_Mapper] DEFAULT (0) FOR [Mapper];


GO
PRINT N'Actualizando Vista [dbo].[CS_OBRASpENDIENTES]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[CS_OBRASpENDIENTES]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [PMuñoz].[Vista_Listados]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[PMuñoz].[Vista_Listados]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [dbo].[VIEW3]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[VIEW3]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [Maite].[BuscarObrasPtes]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[Maite].[BuscarObrasPtes]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [jarobles].[Act Certif 1]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[jarobles].[Act Certif 1]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [mjrosado].[Cons_g0master701]...';


GO
EXECUTE sp_refreshsqlmodule N'[mjrosado].[Cons_g0master701]';


GO
PRINT N'Actualizando Vista [dbo].[FormarFechas]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[FormarFechas]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [jarobles].[Cons_g0MASTER220]...';


GO
EXECUTE sp_refreshsqlmodule N'[jarobles].[Cons_g0MASTER220]';


GO
PRINT N'Actualizando Vista [DIPUTACION\TPEREZ].[Cons_g0TPEREZ]...';


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\TPEREZ].[Cons_g0TPEREZ]';


GO
PRINT N'Actualizando Vista [diana].[Cons_g0master690]...';


GO
EXECUTE sp_refreshsqlmodule N'[diana].[Cons_g0master690]';


GO
PRINT N'Actualizando Vista [DIPUTACION\NJIMENEZ].[Cons_inverNJIMENEZ]...';


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\NJIMENEZ].[Cons_inverNJIMENEZ]';


GO
PRINT N'Actualizando Vista [MCarmen].[cons_inver_inicial]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[MCarmen].[cons_inver_inicial]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [DIPUTACION\MGONZALEZ].[Cons_g0MGONZALEZ]...';


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\MGONZALEZ].[Cons_g0MGONZALEZ]';


GO
PRINT N'Actualizando Vista [PMuñoz].[Cons_g0master701]...';


GO
EXECUTE sp_refreshsqlmodule N'[PMuñoz].[Cons_g0master701]';


GO
PRINT N'Actualizando Vista [dbo].[CertificacionesPARoDEV]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[CertificacionesPARoDEV]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [jarobles].[Cons_g0MGONZALEZ]...';


GO
EXECUTE sp_refreshsqlmodule N'[jarobles].[Cons_g0MGONZALEZ]';


GO
PRINT N'Actualizando Vista [DIPUTACION\JOrdoñez].[Cons_g0JORDOÑEZ]...';


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\JOrdoñez].[Cons_g0JORDOÑEZ]';


GO
PRINT N'Actualizando Vista [PMuñoz].[Cons_g0master220]...';


GO
EXECUTE sp_refreshsqlmodule N'[PMuñoz].[Cons_g0master220]';


GO
PRINT N'Actualizando Vista [DIPUTACION\GGilabert].[Cons_g0MGONZALEZ]...';


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\GGilabert].[Cons_g0MGONZALEZ]';


GO
PRINT N'Actualizando Vista [aida].[Cons_g0master690]...';


GO
EXECUTE sp_refreshsqlmodule N'[aida].[Cons_g0master690]';


GO
PRINT N'Actualizando Vista [DIPUTACION\MLSERRANO].[Cons_g0usu704]...';


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\MLSERRANO].[Cons_g0usu704]';


GO
PRINT N'Actualizando Vista [mjrosado].[Cons_inverMJRosado]...';


GO
EXECUTE sp_refreshsqlmodule N'[mjrosado].[Cons_inverMJRosado]';


GO
PRINT N'Actualizando Vista [jarobles].[Act Certif 2]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[jarobles].[Act Certif 2]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [MCarmen].[Cos_g_inicial]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[MCarmen].[Cos_g_inicial]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [DIPUTACION\APEREZ].[Cons_inverAPEREZ]...';


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\APEREZ].[Cons_inverAPEREZ]';


GO
PRINT N'Actualizando Vista [DIPUTACION\FTORRES].[Cons_g0JAROBLES]...';


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\FTORRES].[Cons_g0JAROBLES]';


GO
PRINT N'Actualizando Vista [jarobles].[Act Certif 3]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[jarobles].[Act Certif 3]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [mjrosado].[VI_InversionesEfectuadas]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[mjrosado].[VI_InversionesEfectuadas]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [DIPUTACION\MPiniella].[Cons_inverMPiniella]...';


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\MPiniella].[Cons_inverMPiniella]';


GO
PRINT N'Actualizando Vista [jarobles].[Cons_g0master701]...';


GO
EXECUTE sp_refreshsqlmodule N'[jarobles].[Cons_g0master701]';


GO
PRINT N'Actualizando Vista [PMuñoz].[Cons_g0master690]...';


GO
EXECUTE sp_refreshsqlmodule N'[PMuñoz].[Cons_g0master690]';


GO
PRINT N'Actualizando Vista [DIPUTACION\MCABRA].[Cons_g0MGONZALEZ]...';


GO
SET QUOTED_IDENTIFIER ON;

SET ANSI_NULLS OFF;


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\MCABRA].[Cons_g0MGONZALEZ]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [diana].[Cons_g0master701]...';


GO
EXECUTE sp_refreshsqlmodule N'[diana].[Cons_g0master701]';


GO
PRINT N'Actualizando Vista [jarobles].[Cons_g0LOLA]...';


GO
EXECUTE sp_refreshsqlmodule N'[jarobles].[Cons_g0LOLA]';


GO
PRINT N'Actualizando Vista [DIPUTACION\TPEREZ].[Cons_g0MGONZALEZ]...';


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\TPEREZ].[Cons_g0MGONZALEZ]';


GO
PRINT N'Actualizando Vista [jarobles].[Actualizar Certificaciones]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[jarobles].[Actualizar Certificaciones]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [mjrosado].[Cons_g0MGONZALEZ]...';


GO
EXECUTE sp_refreshsqlmodule N'[mjrosado].[Cons_g0MGONZALEZ]';


GO
PRINT N'Actualizando Vista [aida].[Cons_g0master701]...';


GO
EXECUTE sp_refreshsqlmodule N'[aida].[Cons_g0master701]';


GO
PRINT N'Actualizando Vista [DIPUTACION\MGONZALEZ].[Cons_g0APEREZ]...';


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\MGONZALEZ].[Cons_g0APEREZ]';


GO
PRINT N'Actualizando Vista [DIPUTACION\VJIMENEZ].[Cons_g0VJIMENEZ]...';


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\VJIMENEZ].[Cons_g0VJIMENEZ]';


GO
PRINT N'Actualizando Vista [jarobles].[Cons_g0TPEREZ]...';


GO
EXECUTE sp_refreshsqlmodule N'[jarobles].[Cons_g0TPEREZ]';


GO
PRINT N'Actualizando Vista [DIPUTACION\JAPALOMO].[Cons_g0JAPALOMO]...';


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\JAPALOMO].[Cons_g0JAPALOMO]';


GO
PRINT N'Actualizando Vista [DIPUTACION\JOrdoñez].[Cons_g0MGONZALEZ]...';


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\JOrdoñez].[Cons_g0MGONZALEZ]';


GO
PRINT N'Actualizando Vista [DIPUTACION\GGilabert].[Cons_g0GGILABERT]...';


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\GGilabert].[Cons_g0GGILABERT]';


GO
PRINT N'Actualizando Vista [dbo].[Cons_g0AVILLALOBOS]...';


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[Cons_g0AVILLALOBOS]';


GO
PRINT N'Actualizando Vista [dbo].[Obras_Terminadas_2001_rellena]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[Obras_Terminadas_2001_rellena]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [DIPUTACION\MLLOPEZ].[Cons_g0LOLA]...';


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\MLLOPEZ].[Cons_g0LOLA]';


GO
PRINT N'Actualizando Vista [PMuñoz].[Cons_inverpmuñoz]...';


GO
EXECUTE sp_refreshsqlmodule N'[PMuñoz].[Cons_inverpmuñoz]';


GO
PRINT N'Actualizando Vista [jarobles].[Cons_g0APEREZ]...';


GO
EXECUTE sp_refreshsqlmodule N'[jarobles].[Cons_g0APEREZ]';


GO
PRINT N'Actualizando Vista [PMuñoz].[Cons_g0master590]...';


GO
EXECUTE sp_refreshsqlmodule N'[PMuñoz].[Cons_g0master590]';


GO
PRINT N'Actualizando Vista [jarobles].[Cons_g0FTORRES]...';


GO
EXECUTE sp_refreshsqlmodule N'[jarobles].[Cons_g0FTORRES]';


GO
PRINT N'Actualizando Vista [DIPUTACION\LVIZUETE].[Cons_g0VJIMENEZ]...';


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\LVIZUETE].[Cons_g0VJIMENEZ]';


GO
PRINT N'Actualizando Vista [jarobles].[Actualizar AUX]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[jarobles].[Actualizar AUX]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [DIPUTACION\APEREZ].[Cons_g0APEREZ]...';


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\APEREZ].[Cons_g0APEREZ]';


GO
PRINT N'Actualizando Vista [dbo].[prueba_CertifAporb]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[prueba_CertifAporb]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [jarobles].[VIEW10]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[jarobles].[VIEW10]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [DIPUTACION\MCMARQUES].[Cons_g0MCMARQUES]...';


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\MCMARQUES].[Cons_g0MCMARQUES]';


GO
PRINT N'Actualizando Vista [diana].[Cons_g0master590]...';


GO
EXECUTE sp_refreshsqlmodule N'[diana].[Cons_g0master590]';


GO
PRINT N'Actualizando Vista [jarobles].[Listado FTorres DIP]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[jarobles].[Listado FTorres DIP]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [MCarmen].[Temp_opp]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[MCarmen].[Temp_opp]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [mjrosado].[DatosInicioDeObras_VIEW]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[mjrosado].[DatosInicioDeObras_VIEW]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [dbo].[SIT_Obras1]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[SIT_Obras1]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [PMuñoz].[TEMPORAL]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[PMuñoz].[TEMPORAL]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [dbo].[VIEW2]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[VIEW2]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [MCarmen].[cons_mamarin28]...';


GO
EXECUTE sp_refreshsqlmodule N'[MCarmen].[cons_mamarin28]';


GO
PRINT N'Actualizando Vista [Maite].[cons_maite47]...';


GO
EXECUTE sp_refreshsqlmodule N'[Maite].[cons_maite47]';


GO
PRINT N'Actualizando Vista [MCarmen].[cons_mcarmen41]...';


GO
EXECUTE sp_refreshsqlmodule N'[MCarmen].[cons_mcarmen41]';


GO
PRINT N'Actualizando Vista [mjrosado].[VI_Obras_En_Ejecucion_Organismo]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[mjrosado].[VI_Obras_En_Ejecucion_Organismo]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [Carmen].[cons_CARMEN9]...';


GO
EXECUTE sp_refreshsqlmodule N'[Carmen].[cons_CARMEN9]';


GO
PRINT N'Actualizando Vista [DIPUTACION\VJIMENEZ].[Cons_g0FTORRES]...';


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\VJIMENEZ].[Cons_g0FTORRES]';


GO
PRINT N'Actualizando Vista [dbo].[SIT_obras_inicial]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[SIT_obras_inicial]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [DIPUTACION\MLValbuena].[cons_MLValbuena19]...';


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\MLValbuena].[cons_MLValbuena19]';


GO
PRINT N'Actualizando Vista [MCarmen].[cons_LOLA]...';


GO
EXECUTE sp_refreshsqlmodule N'[MCarmen].[cons_LOLA]';


GO
PRINT N'Actualizando Vista [aida].[VIEW_BAjas1]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[aida].[VIEW_BAjas1]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [DIPUTACION\FTORRES].[Cons_g0master590]...';


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\FTORRES].[Cons_g0master590]';


GO
PRINT N'Actualizando Vista [DIPUTACION\LVIZUETE].[Cons_g0APEREZ]...';


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\LVIZUETE].[Cons_g0APEREZ]';


GO
PRINT N'Actualizando Vista [dbo].[SIT_Obras]...';


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[SIT_Obras]';


GO
PRINT N'Actualizando Vista [PMuñoz].[Total_Caracteres_Nombre_Obras]...';


GO
EXECUTE sp_refreshsqlmodule N'[PMuñoz].[Total_Caracteres_Nombre_Obras]';


GO
PRINT N'Actualizando Vista [DIPUTACION\MJVELASCO].[Cons_g0MCMARQUES]...';


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\MJVELASCO].[Cons_g0MCMARQUES]';


GO
PRINT N'Actualizando Vista [dbo].[VI_Obras_En_Ejecucion]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[VI_Obras_En_Ejecucion]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [jarobles].[Listado Obras FTorres 14_03_03]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[jarobles].[Listado Obras FTorres 14_03_03]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [DIPUTACION\NGarcia].[cons_NGarcia24]...';


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\NGarcia].[cons_NGarcia24]';


GO
PRINT N'Actualizando Vista [Maite].[cons_Maite41]...';


GO
EXECUTE sp_refreshsqlmodule N'[Maite].[cons_Maite41]';


GO
PRINT N'Actualizando Vista [MCarmen].[cons_maite46]...';


GO
EXECUTE sp_refreshsqlmodule N'[MCarmen].[cons_maite46]';


GO
PRINT N'Actualizando Vista [DIPUTACION\MCABRA].[Cons_g0MCABRA]...';


GO
SET QUOTED_IDENTIFIER ON;

SET ANSI_NULLS OFF;


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\MCABRA].[Cons_g0MCABRA]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [MCarmen].[SIT_ObrasBase]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[MCarmen].[SIT_ObrasBase]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [Maite].[cons_maite25]...';


GO
EXECUTE sp_refreshsqlmodule N'[Maite].[cons_maite25]';


GO
PRINT N'Actualizando Vista [PMuñoz].[NOMBRE_NUEVA_OBRA]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[PMuñoz].[NOMBRE_NUEVA_OBRA]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [DIPUTACION\FTORRES].[Cons_g0FTORRES]...';


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\FTORRES].[Cons_g0FTORRES]';


GO
PRINT N'Actualizando Vista [DIPUTACION\LVIZUETE].[Cons_g0LOLA]...';


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\LVIZUETE].[Cons_g0LOLA]';


GO
PRINT N'Actualizando Vista [MCarmen].[cons_Mcarmen16]...';


GO
EXECUTE sp_refreshsqlmodule N'[MCarmen].[cons_Mcarmen16]';


GO
PRINT N'Actualizando Vista [MCarmen].[cons_Mcarmen28]...';


GO
EXECUTE sp_refreshsqlmodule N'[MCarmen].[cons_Mcarmen28]';


GO
PRINT N'Actualizando Vista [julia].[Cons_g0master590]...';


GO
EXECUTE sp_refreshsqlmodule N'[julia].[Cons_g0master590]';


GO
PRINT N'Actualizando Vista [Maite].[cons_maite11]...';


GO
EXECUTE sp_refreshsqlmodule N'[Maite].[cons_maite11]';


GO
PRINT N'Actualizando Vista [mjrosado].[TEMPProyectosPendientes]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[mjrosado].[TEMPProyectosPendientes]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [DIPUTACION\MCMARQUES].[Cons_g0LOLA]...';


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\MCMARQUES].[Cons_g0LOLA]';


GO
PRINT N'Actualizando Vista [jarobles].[JARP_Consultas]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[jarobles].[JARP_Consultas]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [mjrosado].[Cons_g0LOLA]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[mjrosado].[Cons_g0LOLA]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [MCarmen].[Cons_SIT_Obras_MC]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[MCarmen].[Cons_SIT_Obras_MC]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [dbo].[SIT_Obras_ant]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[SIT_Obras_ant]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [julia].[Cons_g0FTORRES]...';


GO
EXECUTE sp_refreshsqlmodule N'[julia].[Cons_g0FTORRES]';


GO
PRINT N'Actualizando Vista [aida].[VIEW_BAJAS2]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[aida].[VIEW_BAJAS2]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [dbo].[VIEW1]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[VIEW1]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [dbo].[VIEW4]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[VIEW4]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [dbo].[VISTA_PROCALM_PROYECTOS]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[VISTA_PROCALM_PROYECTOS]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [DIPUTACION\GrupoPP].[cons_grupopp9]...';


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\GrupoPP].[cons_grupopp9]';


GO
PRINT N'Actualizando Vista [DIPUTACION\LVIZUETE].[Cons_g0CVIZUETE]...';


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\LVIZUETE].[Cons_g0CVIZUETE]';


GO
PRINT N'Actualizando Vista [Maite].[cons_maite48]...';


GO
EXECUTE sp_refreshsqlmodule N'[Maite].[cons_maite48]';


GO
PRINT N'Actualizando Vista [mjrosado].[AdjudicadasCedidas]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[mjrosado].[AdjudicadasCedidas]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [jarobles].[ObrasNOContratadas]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[jarobles].[ObrasNOContratadas]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [MCarmen].[cons_Mcarmen55]...';


GO
EXECUTE sp_refreshsqlmodule N'[MCarmen].[cons_Mcarmen55]';


GO
PRINT N'Actualizando Vista [aida].[VIEWpatri]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[aida].[VIEWpatri]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [MCarmen].[cons_mcarmen27]...';


GO
EXECUTE sp_refreshsqlmodule N'[MCarmen].[cons_mcarmen27]';


GO
PRINT N'Actualizando Vista [mjrosado].[VIEW7]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[mjrosado].[VIEW7]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [Maite].[CS_SIT_Obras]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[Maite].[CS_SIT_Obras]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [DIPUTACION\MACesar].[Cons_g0MACESAR]...';


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\MACesar].[Cons_g0MACESAR]';


GO
PRINT N'Actualizando Vista [DIPUTACION\MAMarin].[cons_Mmorea7]...';


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\MAMarin].[cons_Mmorea7]';


GO
PRINT N'Actualizando Vista [dbo].[temp_obras]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[temp_obras]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [MCarmen].[cons_mcarmen47]...';


GO
EXECUTE sp_refreshsqlmodule N'[MCarmen].[cons_mcarmen47]';


GO
PRINT N'Actualizando Vista [MCarmen].[temp_obrasMC]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[MCarmen].[temp_obrasMC]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [MCarmen].[cons_mamarin49]...';


GO
EXECUTE sp_refreshsqlmodule N'[MCarmen].[cons_mamarin49]';


GO
PRINT N'Actualizando Vista [jarobles].[Cons_g0EVAZQUEZ]...';


GO
EXECUTE sp_refreshsqlmodule N'[jarobles].[Cons_g0EVAZQUEZ]';


GO
PRINT N'Actualizando Vista [dbo].[Sit_Obras_simple]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[Sit_Obras_simple]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [MCarmen].[cons_mcarmen7]...';


GO
EXECUTE sp_refreshsqlmodule N'[MCarmen].[cons_mcarmen7]';


GO
PRINT N'Actualizando Vista [dbo].[SIT_Obras_web]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[SIT_Obras_web]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [PMuñoz].[CLASIFICACION]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[PMuñoz].[CLASIFICACION]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [Maite].[cons_Maite]...';


GO
EXECUTE sp_refreshsqlmodule N'[Maite].[cons_Maite]';


GO
PRINT N'Actualizando Vista [jarobles].[cons_DCEANO]...';


GO
EXECUTE sp_refreshsqlmodule N'[jarobles].[cons_DCEANO]';


GO
PRINT N'Actualizando Vista [julia].[cons_master590]...';


GO
EXECUTE sp_refreshsqlmodule N'[julia].[cons_master590]';


GO
PRINT N'Actualizando Vista [aida].[cons_LOLA]...';


GO
EXECUTE sp_refreshsqlmodule N'[aida].[cons_LOLA]';


GO
PRINT N'Actualizando Vista [jarobles].[cons_LOLA]...';


GO
EXECUTE sp_refreshsqlmodule N'[jarobles].[cons_LOLA]';


GO
PRINT N'Actualizando Vista [Maite].[cons_LOLA]...';


GO
EXECUTE sp_refreshsqlmodule N'[Maite].[cons_LOLA]';


GO
PRINT N'Actualizando Vista [DIPUTACION\MAMarin].[cons_LOLA]...';


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\MAMarin].[cons_LOLA]';


GO
PRINT N'Actualizando Vista [aida].[cons_master590]...';


GO
EXECUTE sp_refreshsqlmodule N'[aida].[cons_master590]';


GO
PRINT N'Actualizando Vista [Marisa].[cons_LOLA]...';


GO
EXECUTE sp_refreshsqlmodule N'[Marisa].[cons_LOLA]';


GO
PRINT N'Actualizando Vista [DIPUTACION\GrupoPP].[cons_grupopp]...';


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\GrupoPP].[cons_grupopp]';


GO
PRINT N'Actualizando Vista [jarobles].[ImpAprob=ImpContratar]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[jarobles].[ImpAprob=ImpContratar]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [Carmen].[cons_LOLA]...';


GO
EXECUTE sp_refreshsqlmodule N'[Carmen].[cons_LOLA]';


GO
PRINT N'Actualizando Vista [jarobles].[cons_JAROBLES]...';


GO
EXECUTE sp_refreshsqlmodule N'[jarobles].[cons_JAROBLES]';


GO
PRINT N'Actualizando Vista [aida].[cons_grupopp]...';


GO
EXECUTE sp_refreshsqlmodule N'[aida].[cons_grupopp]';


GO
PRINT N'Actualizando Vista [DIPUTACION\NGarcia].[cons_NGARCIA]...';


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\NGarcia].[cons_NGARCIA]';


GO
PRINT N'Actualizando Vista [DIPUTACION\FPROMAN].[cons_FPROMAN]...';


GO
EXECUTE sp_refreshsqlmodule N'[DIPUTACION\FPROMAN].[cons_FPROMAN]';


GO
PRINT N'Actualizando Vista [aida].[Cons_g0LOLA]...';


GO
EXECUTE sp_refreshsqlmodule N'[aida].[Cons_g0LOLA]';


GO
PRINT N'Actualizando Vista [jarobles].[cons_ImportesPorObra]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[jarobles].[cons_ImportesPorObra]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [dbo].[Cons_g0LOLA]...';


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[Cons_g0LOLA]';


GO
PRINT N'Actualizando Vista [jarobles].[Prueba_JARobles]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[jarobles].[Prueba_JARobles]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Vista [jarobles].[TOTALESORGANISMOS]...';


GO
EXECUTE sp_refreshsqlmodule N'[jarobles].[TOTALESORGANISMOS]';


GO
PRINT N'Actualizando Vista [mjrosado].[ObrasCedidasPruebaMJ]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[mjrosado].[ObrasCedidasPruebaMJ]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Creando Vista [dbo].[cons_LOLA]...';


GO
create view cons_LOLA as SELECT DISTINCT ('29' + substring(ltrim(str(1000+[TabladeMunicipios].codigo_municipio)),2,3)) as CODIGO, [TabladeMunicipios].nombre_municipio,Sum(cast([Importesdeobras].importe_aprobado as float)) AS IMP_APROBADO  FROM (((obras..[DatosIniciodeobras] INNER JOIN tablas..[TabladeMunicipios] AS TabladeMunicipios ON ([DatosIniciodeobras].municipio = [TabladeMunicipios].codigo_municipio) INNER JOIN tablas..Planes as Planes ON [DatosIniciodeobras].codigo_plan = Planes.codigo_plan) INNER JOIN [Importesdeobras] ON ([DatosIniciodeobras].ao_ejecucion = [Importesdeobras].ao_ejecucion) AND ([DatosIniciodeobras].subreferencia = [Importesdeobras].subreferencia) AND ([DatosIniciodeobras].numero_obra = [Importesdeobras].numero_obra) AND ([DatosIniciodeobras].codigo_plan = [Importesdeobras].codigo_plan))) WHERE  [DatosIniciodeobras].ao_ejecucion >= 2022 and [DatosIniciodeobras].ao_ejecucion <= 2023  GROUP BY ('29' + substring(ltrim(str(1000+[TabladeMunicipios].codigo_municipio)),2,3)),  [TabladeMunicipios].nombre_municipio
GO
PRINT N'Creando Vista [DIPUTACION\MCardona].[CS_EJE_SumaCertifAntMCARDONA]...';


GO
CREATE VIEW [DIPUTACION\MCardona].CS_EJE_SumaCertifAntMCARDONA as SELECT  Codigo_Plan as Xplan, Numero_obra as Xobra, Subreferencia as Xsubref, ao_ejecucion as Xao, organismo as Xorg, 0 AS SumaCertifAnt, 1 AS XNUM_Certif FROM obras..ImportesPorOrganismo as ImportesPorOrganismo WHERE ((ImportesPorOrganismo.codigo_Plan='PC') AND (Numero_obra=55) AND (Subreferencia=0) AND (ao_ejecucion=2013))
GO
PRINT N'Actualizando Vista [mjrosado].[cons_cert2Semestral]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[mjrosado].[cons_cert2Semestral]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Modificando Procedimiento [dbo].[PA_PROYECTOS_M_DatosFaseOLD]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
ALTER PROCEDURE [PA_PROYECTOS_M_DatosFaseOLD] 
/*
ESTE PROCEDIMIENTO REALIZA EL ALTA, LA BAJA O MODIFICACION DE UN REGISTRO DE Fases de Proyectos
VALORES QUE DEVUELVE:

	20	TERMINACION CORRECTA
	21 	NO EXISTE EL PROYECTO SOBRE EL QUE SE QUIERE DAR MODIFICAR
	22 	NO EXISTE LA FASE A MODIFICAR
	23	ERROR EN LA ACTUALIZACION DEL REGISTRO DE FASE
	24	ERROR AL ACTUALIZAR ESTADO DEL PROYECTO
	25	ERROR AL ACTUALIZAR ESTADO OBRA
	26	ERROR AL ACTUALIZAR AYUDA_TECNICA
	27	ERROR EN LA ACTUALIZACION DE PENDIENTE CONTRATACION
	28	ERROR
	29	ERROR
	30	DATOS PLIEGO INCORRECTO

*/

/* VARIABLES PARA LA FASE
	CLAVE*/
@V_MUNICIPIO as smallint,
@V_AO_PROYECTO as smallint, 
@V_AO_FASE AS SMALLINT,
@V_NUMERO_PROYECTO AS SMALLINT,
@V_NUMERO_FASE AS SMALLINT ,
	/* FECHAS*/

@V_FECHA_REM_FASE as smalldatetime = null,
@V_fecha_ENT_FASE as smalldatetime = null,
@V_fecha_REMISION_ayto as smalldatetime = null,
@V_fecha_aprobacion_ayto as smalldatetime = null,
@V_fecha_REMISION_JUNTA as smalldatetime = null,
@V_fecha_VISADO_JUNTA as smalldatetime = null,
@V_fecha_PET_INF_TECNICO_CONTRATA as smalldatetime = null,
@V_fecha_ENT_INF_TECNICO_CONTRATA as smalldatetime = null,
@V_PLIEGO_CLAUSULAS_PARTICULARES as smalldatetime = null,
@V_fecha_PET_DESGLOSE as smalldatetime = null,
@V_fecha_ENT_DESGLOSE as smalldatetime = null,
@V_fecha_pet_rectificacion as smalldatetime = null,
@V_fecha_ent_rectificacion as smalldatetime = null,
@V_fecha_pet_reforma as smalldatetime = null,
@V_fecha_ent_reforma as smalldatetime = null,
@V_fecha_pit_ref as smalldatetime = null,
@V_fecha_eit_ref as smalldatetime = null,
@V_fecha_ci_ref as smalldatetime = null,
@V_fecha_cg_ref as smalldatetime = null,
@V_FECHA_PET_ACTUAL_PRECIOS AS smalldatetime = null, 
@V_FECHA_ENT_ACTUAL_PRECIOS AS smalldatetime = null,
@V_FECHA_ENVIO_FISCALIZACION AS smalldatetime = null,
@V_fecha_COM_INF as smalldatetime = null,
@V_fecha_COM_GOB as smalldatetime = null,
@V_FISCALIZACION AS smalldatetime = null,
@V_FECHA_REMISION_CONTRATACION AS smalldatetime = null,
@V_fecha_dto as smalldatetime = null, 
	/* IMPORTES*/

@V_IMPORTE_FASE as float,
@V_presu_gral_ejecucion_material as float,
@V_por_gastos_generales as float,
@V_importe_gastos_generales as float,
@V_por_beneficio_industriales as float,
@V_importe_beneficio_industriales as float,
@V_por_control_calidad as float,
@V_importe_control_calidad as float,
@v_por_iva as float,
@V_iva as float,
@V_por_subcontrata as float,
@V_subcontrata as float,
@V_honorarios_dir as float,
@V_honorarios_red as float,

/*RESTO DATOS*/
@V_servicio_gestor  AS SMALLINT,
@V_CODIGO_PLAN AS CHAR(7) ,
@V_REFERENCIA AS SMALLINT,
@V_SUBREFERENCIA AS TINYINT,
@V_AO_EJECUCION_OBRA AS SMALLINT ,
@V_CARRETERA AS CHAR(5) ,
@V_PLAZO AS smallint ,
@V_UNIDAD_PLAZO AS CHAR(1) = 'm',
@V_NRO_EJEMPLARES AS SMALLINT  ,
@V_REVISION AS char(2) ,
@V_FORMULA AS TINYINT ,
@V_FORMULA2 AS TINYINT,
@V_FORMULA3 AS TINYINT,
@V_FORMULA4 AS TINYINT,
@V_ORGANISMO_DIRECCION AS CHAR(2),

@V_SERVICIO_DIRECCION AS varchar(150)  ,
@V_DIRECTOR_TECNICO_OBRA AS VARCHAR(150),
@V_COLEGIOOFICIALDIRECCION  AS VARCHAR(80),
@V_SUBVENCIONECONDIRECCION AS BIT,
@V_nro_dto as smallint ,
@V_CLASE_EXP AS CHAR(2)=null ,
@V_TIPO_PROC AS CHAR(2)=null ,
@V_FORMA_CONT AS CHAR(2)=null ,
@V_Requiere_PlanSyS as bit,
@V_EnvioContratacion as integer output

AS

DECLARE @V_EXISTE            AS INTEGER
DECLARE @V_EXISTE_FASE AS INTEGER
DECLARE @V_EXISTE_PTE   AS INTEGER
declare @v_existe_pteactivo as integer
declare @v_numorden as integer
DECLARE @V_ERROR            AS INTEGER
DECLARE @V_ESTADO_PROY1 AS CHAR(3)
DECLARE @V_ESTADO_OBRA1 AS CHAR(3)
DECLARE @V_ESTADO_FASE1 AS CHAR(3)
DECLARE @V_FORMAEJEC       AS CHAR(3) 
DECLARE @V_SED AS CHAR(2)
DECLARE @V_SER AS bit
DECLARE @V_SER2 AS CHAR(2)
DECLARE @V_ORGANISMO_REDACTOR AS CHAR(2)
DECLARE @V_SERVICIO_REDACTOR AS SMALLINT
declare @v_servicio_dir as smallint
declare @v_colofid as char(2)
DECLARE @V_AUTOR AS CHAR(60)
declare @v_PETAYUDA_NUEVA AS CHAR(2)
DECLARE @V_PETAYUDA_ANTES AS CHAR(2)
/******************************************************************************************************************************************** */	
				/*COMPRUEBA SI EXISTE EL PROYECTO Y LA  FASE Y PTE CONTRATACION*/
/******************************************************************************************************************************************** */

set @v_existe_fase = 0
SET @V_EXISTE = 0
SET @V_EXISTE_PTE = 0
set @v_existe_pteactivo =0
set @v_enviocontratacion=0

IF @v_CLASE_EXP<>null and @v_FORMA_CONT<>null and @v_TIPO_PROC<>null
begin
/* COMPRUEBA EXISTE EL REGISTRO EN LA TABLA PLAZOS*/
select  @v_existe = count(1) from CONTRATA..PLAZOS 
WHERE (CodClaseExp = @v_CLASE_EXP) AND
(CodFormaContra = @v_FORMA_CONT) AND 
(CodProcedimiento = @v_TIPO_PROC) 
if @v_existe = 0 
begin
	RETURN 30				/*DATOS PLIEGO INCORRECTO*/
END
end

/* carga el servicio director y el colegio oficial de dirección si existen*/

if rtrim(@v_servicio_direccion) <> ' ' 
begin
	select @v_servicio_dir = codigo_dpto from tablas..tabladedepartamentos where denominacion = @v_servicio_direccion
	set @v_error = @@error
	if @v_error <> 0
	begin
		RETURN 28			/* ERROR al leer el servicio director*/
	end
end
if rtrim(@v_colegiooficialdireccion) <> ' '
begin
	select @v_colofid = codigo_redactor from tablas..colegiosoficiales where organismo_redactor = @v_colegiooficialdireccion
	set @v_error = @@error
	if @v_error <> 0
	begin
		RETURN 29			/* ERROR al leer el colegio*/
	end
end
ELSE
BEGIN
	SET @V_COLOFID = NULL
END
/* COMPRUEBA EXISTA EL PROYECTO*/
select  @v_existe = count(1) from proyectos 
WHERE (CODIGO_MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto) 
if @v_existe = 0 
begin
	RETURN 21				/*NO EXISTE EL PROYECTO DEL QUE SE QUIERE MODIFICAR LA FASE*/
END


/* COMPRUEBA QUE EXISTE LA FASE*/
select @v_existe_fase = count(1) from fasesdeproyectos
WHERE (MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto) AND 
(AO_FASE= @V_AO_FASE) AND
(NUMERO_FASE = @V_NUMERO_FASE)
IF @V_EXISTE_FASE= 0 
begin
	RETURN 22				/*NO EXISTE LA FASE QUE SE QUIERE MODIFICAR*/
end



/*COMPRUEBA SI EXISTE pendienteCONTRATACION  */
select @v_existe_PTE = count(1) ,@v_numorden=max(peticioncont) from  CONTRATA..PENDIENTECONTRATACIONOBRAS
where  planOBRA= @V_CODIGO_PLAN and
NUMobra = @V_REFERENCIA and
subref = @V_SUBREFERENCIA and
aoOBRA = @V_AO_EJECUCION_OBRA 



/*COMPRUEBA SI EXISTE pendienteCONTRATACION y esta pendiente de apertura*/
select @v_existe_PTEactivo = count(1)  from  CONTRATA..PENDIENTECONTRATACIONOBRAS
where  planOBRA= @V_CODIGO_PLAN and
NUMobra = @V_REFERENCIA and
subref = @V_SUBREFERENCIA and
aoOBRA = @V_AO_EJECUCION_OBRA and
estado is null

/* LEE EL ESTADO DE LA OBRA EN INICIO Y FORMA DE EJECUCION Y PETICION DE AYUDA*/
select @v_estado_obra1= codigo_estado_obra, @V_FORMAEJEC = FORMA_EJECUCION,@V_PETAYUDA_ANTES=PETICION_AYUDA_TEC  from datosiniciodeobras
where  codigo_plan= @V_CODIGO_PLAN and
numero_obra = @V_REFERENCIA and
subreferencia = @V_SUBREFERENCIA and
ao_ejecucion = @V_AO_EJECUCION_OBRA

/*LEE EL ESTADO DEL PROYECTO, REDACTOR Y AUTOR*/
SELECT @V_ESTADO_PROY1 = ESTADO_PROYECTO , @V_ORGANISMO_REDACTOR= ORGANISMO_REDACTOR, @V_SERVICIO_REDACTOR =SERVICIO_REDACTOR, @V_AUTOR=AUTOR,@v_ser=subvencioneconredaccion FROM PROYECTOS
WHERE (CODIGO_MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto)

/* LEE EL ESTADO DE LA FASE*/
SELECT @V_ESTADO_FASE1 =ESTADO_FASE FROM FASESDEPROYECTOS
WHERE (MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto) AND 
(AO_FASE= @V_AO_FASE) AND
(NUMERO_FASE = @V_NUMERO_FASE)

/* ******************************************************************************************************************************************* */	
/*  COMPROBACIONES ANTERIORES A INICIAR LA TRANSACCION (PARA MODIFICACIONES)                  	   */
/* ******************************************************************************************************************************************* */

/* ACTUALIZACION DE LOS ESTADOS*/
IF (@V_ESTADO_OBRA1 = 'DES' ) AND (@V_FORMAEJEC = 'DIP')  AND @V_FECHA_REMISION_CONTRATACION <> '  '
BEGIN

	SET @V_ESTADO_FASE1='TE'
	SET @V_ESTADO_PROY1='TE'
	SET @V_ESTADO_OBRA1='PA'
END
IF (@V_ESTADO_OBRA1 = 'TPR' OR @V_ESTADO_OBRA1 = 'PA') AND (@V_FORMAEJEC = 'DIP')  AND @V_FECHA_REMISION_CONTRATACION <> '  '
BEGIN

	SET @V_ESTADO_FASE1='TE'
	SET @V_ESTADO_PROY1='TE'
	SET @V_ESTADO_OBRA1='PA'
END
IF (@V_ESTADO_OBRA1 = 'TPR' OR @V_ESTADO_OBRA1 = 'PA') AND (@V_FORMAEJEC = 'DIP') AND @V_FECHA_REMISION_CONTRATACION is null
BEGIN
	
	SET @V_ESTADO_FASE1= 'TFA'
	SET @V_ESTADO_PROY1='TPR'
	SET @V_ESTADO_OBRA1='TPR'
END
IF (@V_ESTADO_OBRA1 = 'TPR' OR @V_ESTADO_OBRA1 = 'PA') AND @V_FORMAEJEC <>  'DIP'  AND (@V_FECHA_COM_GOB <> '  ' OR  @V_FECHA_DTO <>'  ')
BEGIN

	SET @V_ESTADO_FASE1= 'TE'
	SET @V_ESTADO_PROY1='TE'
	SET @V_ESTADO_OBRA1='PA'
END	
IF (@V_ESTADO_OBRA1 = 'TPR' OR @V_ESTADO_OBRA1 = 'PA') AND @V_FORMAEJEC <>  'DIP'  AND (@V_FECHA_COM_GOB = NULL AND   @V_FECHA_DTO = NULL)
BEGIN

	SET @V_ESTADO_FASE1= 'TFA'
	SET @V_ESTADO_PROY1='TPR'
	SET @V_ESTADO_OBRA1='TPR'
END	
/* ******************************************************************************************************************************************* */	
/*   COMPRUEBA EL NUEVO VALOR DE PETICION DE AYUDA TECNICA */
/* ******************************************************************************************************************************************* */	
set @v_petayuda_nueva = 'NO'
IF @V_ORGANISMO_REDACTOR = 'DP' OR @V_ORGANISMO_DIRECCION='DP' OR @V_SUBVENCIONECONDIRECCION <> 0  or @v_ser <> 0 
begin
	set @v_petayuda_nueva='SI'
end

 /* ******************************************************************************************************************************************* */	
/*  EMPIEZA LA ACTUALIZACION                 	   */
/* ******************************************************************************************************************************************* */

/* ACTUALIZO EL REGISTRO DE FASE*/
BEGIN TRANSACTION
UPDATE FASESDEPROYECTOS
SET
Servicio_gestor=@V_servicio_gestor,  
CODIGO_PLAN=@V_CODIGO_PLAN,
REFERENCIA =@V_REFERENCIA,
SUBREFERENCIA  =@V_SUBREFERENCIA,
AO_EJECUCION_OBRA =@V_AO_EJECUCION_OBRA,
CARRETERA =@V_CARRETERA,
PLAZO = @V_PLAZO,
UNIDADPLAZO = @V_UNIDAD_PLAZO,
NRO_EJEMPLARES = @V_NRO_EJEMPLARES,

REVISION  = @V_REVISION,
FORMULA = @V_FORMULA,
FORMULA2 = @V_FORMULA2,
FORMULA3 = @V_FORMULA3,
FORMULA4 = @V_FORMULA4,
ORGANISMO_DIRECCION = @V_ORGANISMO_DIRECCION,
SERVICIO_DIRECCION  = @V_SERVICIO_DIR,
DIRECTOR_TECNICO_OBRA  = @V_DIRECTOR_TECNICO_OBRA,
COLEGIOOFICIALDIRECCION   = @V_COLOFID,
SUBVENCIONECONDIRECCION = @V_SUBVENCIONECONDIRECCION,
nro_dto  = @V_nro_dto,
estado_FASE  = @V_ESTADO_FASE1,
CLASE_EXP = @V_CLASE_EXP,
TIPO_PROC   = @V_TIPO_PROC,
FORMA_CONT    = @V_FORMA_CONT,
Requiere_PlanSyS     = @V_Requiere_PlanSyS,

/*IMPORTES*/

IMPORTE_FASE  = @V_IMPORTE_FASE ,
presu_gral_ejecucion_material  = @V_presu_gral_ejecucion_material,
por_gastos_generales  = @V_por_gastos_generales,
importe_gastos_generales  = @V_importe_gastos_generales,
por_beneficio_industriales  = @V_por_beneficio_industriales,
importe_beneficio_industriales  = @V_importe_beneficio_industriales,
por_control_calidad  = @V_por_control_calidad,
importe_control_calidad  = @V_importe_control_calidad,
por_iva =@V_por_iva,
Iva  =@V_iva,
por_subcontrata  = @V_por_subcontrata,
subcontrata  = @V_subcontrata,
honorarios_dir  = @V_honorarios_dir,
honorarios_red  = @V_honorarios_red,

/* FECHAS*/

FECHA_REM_FASE  = @V_FECHA_REM_FASE,
fecha_ENT_FASE = @V_fecha_ENT_FASE,
fecha_REMISION_ayto  =  @V_fecha_REMISION_ayto,
fecha_aprobacion_ayto  =  @V_fecha_aprobacion_ayto,
fecha_REMISION_JUNTA  =  @V_fecha_REMISION_JUNTA,
FECHA_VISADO_JUNTA =   @V_FECHA_VISADO_JUNTA,
fecha_PET_INF_TECNICO_CONTRATA  =   @V_fecha_PET_INF_TECNICO_CONTRATA,
fecha_ENT_INF_TECNICO_CONTRATA   =   @V_fecha_ENT_INF_TECNICO_CONTRATA,
PLIEGO_CLAUSULAS_PARTICULARES   =   @V_PLIEGO_CLAUSULAS_PARTICULARES,
fecha_PET_DESGLOSE  =   @V_fecha_PET_DESGLOSE,
fecha_ENT_DESGLOSE   =   @V_fecha_ENT_DESGLOSE,
fecha_pet_rectificacion   =  @V_fecha_pet_rectificacion,
fecha_ent_rectificacion   =   @V_fecha_ent_rectificacion,
fecha_pet_reforma   =   @V_fecha_pet_reforma,
fecha_ent_reforma  =    @V_fecha_ent_reforma,
fecha_pit_ref   =   @V_fecha_pit_ref,
fecha_eit_ref   =   @V_fecha_eit_ref,
fecha_ci_ref    =   @V_fecha_ci_ref,
fecha_cg_ref   =   @V_fecha_cg_ref,
FECHA_PET_ACTUAL_PRECIOS   =  @V_FECHA_PET_ACTUAL_PRECIOS,
FECHA_ENT_ACTUAL_PRECIOS   =   @V_FECHA_ENT_ACTUAL_PRECIOS,
FECHA_ENVIO_FISCALIZACION  = @V_FECHA_ENVIO_FISCALIZACION,
fecha_COM_INF   = @V_fecha_COM_INF,
fecha_COM_GOB  =  @V_fecha_COM_GOB,
fecha_FISCALIZACION  =   @V_FISCALIZACION,
FECHA_REMISION_CONTRATACION   =  @V_FECHA_REMISION_CONTRATACION,
fecha_dto   =   @V_fecha_dto

WHERE (MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto) AND 
(AO_FASE= @V_AO_FASE) AND
(NUMERO_FASE = @V_NUMERO_FASE)
set @v_error = @@error
if @v_error <> 0
begin
	ROLLBACK
	RETURN 23			/* ERROR EN LA ACTUALIZACION DEL REGISTRO DE FASE*/
end

/* ACTUALIZO EL ESTADO DE PROYECTO*/
UPDATE PROYECTOS
SET
ESTADO_PROYECTO=@V_ESTADO_PROY1
WHERE (codigo_MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto) 
set @v_error = @@error
if @v_error <> 0
begin 
	ROLLBACK	
	return 24		/*ERROR AL ACTUALIZAR ESTADO DEL PROYECTO*/			
end

/* ACTUALIZA ESTADO OBRA y peticion ayuda tecnica*/

UPDATE DATOSINICIODEOBRAS
SET
CODIGO_ESTADO_obra=@V_ESTADO_OBRA1,
peticion_ayuda_tec=@v_petayuda_nueva
where  codigo_plan= @V_CODIGO_PLAN and
numero_obra = @V_REFERENCIA and
subreferencia = @V_SUBREFERENCIA and
ao_ejecucion = @V_AO_EJECUCION_OBRA
set @v_error = @@error
if @v_error <> 0
begin 
	ROLLBACK	
	return 25		/*ERROR AL ACTUALIZAR ESTADO OBRA*/			
end

/*ACTUALIZO LA AYUDA TECNICA POR SI HUBO CAMBIOS*/
IF @V_SUBVENCIONECONDIRECCION = 0 
BEGIN
	SET @V_SED=null
END
ELSE
BEGIN
	SET @V_SED='SI'
END
IF @V_SER = 0 
BEGIN
	SET @V_SER2=null
END
ELSE
BEGIN
	SET @V_SER2='SI'
END
if @v_petayuda_nueva='SI' and @v_petayuda_antes= 'SI'
begin 
	UPDATE AYUDA_TECNICA
	SET
	DEPARTAMENTO_DIRECCION= @V_SERVICIO_DIR,
	DPTO_REDACTOR=@V_SERVICIO_REDACTOR,
	SUBVENCIONECONOMICAR = @V_SER2,
	SUBVENCIONECONOMICAD = @V_SED,
	PASADO=1
	where  codigo_plan= @V_CODIGO_PLAN and
	numero_obra = @V_REFERENCIA and
	subreferencia = @V_SUBREFERENCIA and
	ao_ejecucion = @V_AO_EJECUCION_OBRA
	set @v_error = @@error
	if @v_error <> 0	begin 
		ROLLBACK	
		return 26		/*ERROR AL ACTUALIZAR AYUDA_TECNICA*/			
	end
end
if @v_petayuda_nueva = 'NO' and @v_petayuda_antes = 'SI'
begin
	delete from ayuda_tecnica
	where  codigo_plan= @V_CODIGO_PLAN and
	numero_obra = @V_REFERENCIA and
	subreferencia = @V_SUBREFERENCIA and
	ao_ejecucion = @V_AO_EJECUCION_OBRA
	set @v_error = @@error
	if @v_error <> 0	begin 
		ROLLBACK	
		return 26		/*ERROR AL ACTUALIZAR AYUDA_TECNICA*/			
	end
end
if @v_petayuda_nueva = 'SI' and @v_petayuda_antes = 'NO'
begin
	INSERT INTO ayuda_tecnica
		   (CODIGO_PLAN,NUMERO_OBRA,SUBREFERENCIA,AO_EJECUCION,	departamento, codigo_municipio,ao_proyecto,numero_proyecto,DPTO_REDACTOR,DEPARTAMENTO_DIRECCION,PASADO,SUBVENCIONECONOMICAR,SUBVENCIONECONOMICAD)
	VALUES (@V_CODIGO_PLAN, @V_REFERENCIA,@V_SUBREFERENCIA,@V_AO_EJECUCION_OBRA,0,0,0,0,@V_SERVICIO_REDACTOR,@V_SERVICIO_DIR,1,@V_SER2,@V_SED)
	set @v_error = @@error
	if @v_error <> 0	begin 
		ROLLBACK	
		return 26		/*ERROR AL ACTUALIZAR AYUDA_TECNICA*/			
	end
end
/* GRABA PENDIENTE CONTRATACION O ACTUALIZA REGISTRO EXISTENTE*/
set @v_enviocontratacion=0
if @V_FORMAEJEC <> 'DIP' 
begin
	commit
	return 20
end
if @v_estado_obra1 = 'TPR' and @V_EXISTE_PTEACTIVO > 0
BEGIN
	DELETE CONTRATA..PENDIENTECONTRATACIONOBRAS
	WHERE
	planOBRA= @V_CODIGO_PLAN and
	numobra = @V_REFERENCIA and
	subref = @V_SUBREFERENCIA and
	AOOBRA = @V_AO_EJECUCION_OBRA AND
	TIPOEXP='OB' and 
	peticioncont=@v_numorden
	set @v_enviocontratacion=3
	COMMIT
	RETURN 20
END

if @v_estado_obra1 <> 'PA'
begin
	commit
	return 20
end
IF @V_EXISTE_PTEactivo  > 0 
BEGIN
	UPDATE CONTRATA..PENDIENTECONTRATACIONOBRAS
	SET
	ampliacion=0,
	servicio_redactor=@v_servicio_redactor,
	autor=@v_autor,
	servicio_gestor=@v_servicio_gestor,
	servicio_direccion=@v_servicio_dir,
	codclaseexp= @V_CLASE_EXP,
	codprocedimiento = @V_TIPO_PROC    ,
	CodFormaContrata    = @V_FORMA_CONT,
	importelicitacion=@v_importe_fase,
	fecharecepcion=getdate (),
	esTADO=NULL
	WHERE
	planOBRA= @V_CODIGO_PLAN and
	numobra = @V_REFERENCIA and
	subref = @V_SUBREFERENCIA and
	AOOBRA = @V_AO_EJECUCION_OBRA AND
	TIPOEXP='OB' and 
	peticioncont=@v_numorden
	set @v_enviocontratacion=1
end
if @v_existe_pteactivo = 0 
begin
	IF @V_EXISTE_PTE = 0 
	BEGIN
		SET @V_NUMORDEN=0
	END
	set @v_numorden=@v_numorden +1
	INSERT INTO contrata..pendientecontratacionobras
 		 (tipoexp,peticioncont,planobra,numobra,subref,aoobra,ampliacion,servicio_redactor,servicio_gestor,servicio_direccion,
		autor,importelicitacion,codclaseexp,codprocedimiento,codformacontrata,fecharecepcion,estado)	
	VALUES ('OB',@v_numorden,@V_CODIGO_PLAN, @V_REFERENCIA , @V_SUBREFERENCIA, @V_AO_EJECUCION_OBRA ,0,@V_SERVICIO_REDACTOR,
	@V_SERVICIO_GESTOR,@V_SERVICIO_DIR,@V_AUTOR,@V_IMPORTE_FASE,@V_CLASE_EXP, @V_TIPO_PROC   ,
	@V_FORMA_CONT,getdate (),NULL)
	set @v_enviocontratacion=2
END
set @v_error = @@error
if @v_error <> 0
begin	ROLLBACK	
	RETURN 27			/*ERROR EN PENDIENTE DE CONTRATACION*/
end


COMMIT
RETURN 20			/*PROCESO REALIZADO CORRECTAMENTE*/
GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Modificando Procedimiento [dbo].[PA_PROYECTOS_ABM_DatosFaseOLD]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
ALTER PROCEDURE [PA_PROYECTOS_ABM_DatosFaseOLD] 
/*
ESTE PROCEDIMIENTO REALIZA EL ALTA, LA BAJA O MODIFICACION DE UN REGISTRO DE Fases de Proyectos
VALORES QUE DEVUELVE:

	0	TERMINACION CORRECTA
	1	ERROR EN LA ACTUALIZACION DE LA AYUDA TECNICA
	2	ERROR AL ACTUALIZAR ESTADO DE INICIO DE OBRA o de proyecto
	3	ERROR al leer el servicio director O EL COLEGIO OFICIAL DE DIRECCION
	4 	NO EXISTE EL PROYECTO, NO SE PUEDE DAR DE ALTA LA FASE
	5 	YA EXISTE LA FASE QUE SE QUIERE DAR DE ALTA
	6 	
	7 		
 	8 	ERROR AL ASIGNAR UN NUEVO NÚMERO DE FASE
	9	ERROR EN EL ALTA DE LA FASE
	10	ERROR EN LA ACTUALIZACION DEL REGISTRO


	
*/
@v_accion as char (1)  = 'A',
/* VARIABLES PARA LA FASE
	CLAVE*/
@V_MUNICIPIO as smallint,
@V_AO_PROYECTO as smallint, 
@V_AO_FASE AS SMALLINT,
@V_NUMERO_PROYECTO AS SMALLINT=0,
@V_NUMERO_FASE AS SMALLINT = 0,
	/* FECHAS*/

@V_FECHA_REM_FASE as smalldatetime = null,
@V_fecha_ENT_FASE as smalldatetime = null,
@V_fecha_REMISION_ayto as smalldatetime = null,
@V_fecha_aprobacion_ayto as smalldatetime = null,
@V_fecha_REMISION_JUNTA as smalldatetime = null,
@V_fecha_VISADO_JUNTA as smalldatetime = null,
@V_fecha_PET_INF_TECNICO_CONTRATA as smalldatetime = null,
@V_fecha_ENT_INF_TECNICO_CONTRATA as smalldatetime = null,
@V_PLIEGO_CLAUSULAS_PARTICULARES as smalldatetime = null,
@V_fecha_PET_DESGLOSE as smalldatetime = null,
@V_fecha_ENT_DESGLOSE as smalldatetime = null,
@V_fecha_pet_rectificacion as smalldatetime = null,
@V_fecha_ent_rectificacion as smalldatetime = null,
@V_fecha_pet_reforma as smalldatetime = null,
@V_fecha_ent_reforma as smalldatetime = null,
@V_fecha_pit_ref as smalldatetime = null,
@V_fecha_eit_ref as smalldatetime = null,
@V_fecha_ci_ref as smalldatetime = null,
@V_fecha_cg_ref as smalldatetime = null,
@V_FECHA_PET_ACTUAL_PRECIOS AS smalldatetime = null, 
@V_FECHA_ENT_ACTUAL_PRECIOS AS smalldatetime = null,
@V_FECHA_ENVIO_FISCALIZACION AS smalldatetime = null,
@V_fecha_COM_INF as smalldatetime = null,
@V_fecha_COM_GOB as smalldatetime = null,
@V_FISCALIZACION AS smalldatetime = null,
@V_FECHA_REMISION_CONTRATACION AS smalldatetime = null,
@V_fecha_dto as smalldatetime = null,
	/* IMPORTES*/
@V_IMPORTE_FASE as float,
@V_presu_gral_ejecucion_material as float,
@V_por_gastos_generales as float,
@V_importe_gastos_generales as float,
@V_por_beneficio_industriales as float,
@V_importe_beneficio_industriales as float,
@V_por_control_calidad as float,
@V_importe_control_calidad as float,
@v_por_iva as float,
@V_iva as float,
@V_por_subcontrata as float,
@V_subcontrata as float,
@V_honorarios_dir as float,
@V_honorarios_red as float,

/*RESTO DATOS*/
@V_servicio_gestor  AS SMALLINT,
@V_CODIGO_PLAN AS CHAR(7) ,
@V_REFERENCIA AS SMALLINT,
@V_SUBREFERENCIA AS TINYINT,
@V_AO_EJECUCION_OBRA AS SMALLINT ,
@V_CARRETERA AS CHAR(5) ,
@V_PLAZO AS smallint ,
@V_UNIDAD_PLAZO as char(1),
@V_NRO_EJEMPLARES AS SMALLINT  ,
@V_REVISION AS char(2) ,
@V_FORMULA AS TINYINT ,
@V_FORMULA2 AS TINYINT,
@V_FORMULA3 AS TINYINT,
@V_FORMULA4 AS TINYINT,
@V_ORGANISMO_DIRECCION AS CHAR(2),
@V_SERVICIO_DIRECCION AS varchar(150)  ,
@V_DIRECTOR_TECNICO_OBRA AS VARCHAR(150),
@V_COLEGIOOFICIALDIRECCION  AS varcHAR(150),
@V_SUBVENCIONECONDIRECCION AS BIT,
@V_nro_dto as smallint ,
@V_estado_FASE as CHAR(3),
@V_CLASE_EXP AS CHAR(2) ,
@V_TIPO_PROC AS CHAR(2) ,
@V_FORMA_CONT AS CHAR(2) ,
@V_Requiere_PlanSyS as bit,

/* PARAMETROS DE SALIDA************************************************************************
    DEVUELVE EL NUMERO DE PROYECTO Y FASE SOBRE LOS QUE SE HA ACTUADO */
@V_NUM_FASE AS SMALLINT OUTPUT
AS

DECLARE @V_EXISTE          AS INTEGER
DECLARE @V_EXISTE_FASE AS INTEGER
DECLARE @V_ERROR          AS INTEGER
DECLARE @V_ESTADO_PROY1 AS CHAR(3)
DECLARE @V_ESTADO_OBRA1 AS CHAR(3)
DECLARE @V_ESTADO_FASE1 AS CHAR(3)
DECLARE @V_ESTADO_PROY2 AS CHAR(3)
DECLARE @V_ESTADO_OBRA2 AS CHAR(3)
DECLARE @V_ESTADO_FASE2  AS CHAR(3)
DECLARE @V_SERVDIR AS SMALLINT
DECLARE @V_COLOFID AS CHAR(2)
DECLARE @SUBVENCION AS CHAR(2)
DECLARE @SUBVENCIONR AS CHAR(2)
DECLARE @V_AYUDA AS CHAR(2)
DECLARE @V_SUBVECONR  AS BIT
DECLARE @V_ORGRED AS CHAR(2)
DECLARE @V_SERVRED AS SMALLINT

/*********************************************************************************************************************************************************** */	
/*COMPRUEBA SI EXISTE EL PROYECTO Y LA  FASE Y CARGA EL ORGANISMO,SERVICIO Y SUBVENCION DEL REDACTOR*/
/*********************************************************************************************************************************************************** */
set @v_existe_fase = 0

select  @v_existe = count(1) from proyectos 
WHERE (CODIGO_MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto) 


select  @v_SUBVECONR = SUBVENCIONECONREDACCION, @V_ORGRED = ORGANISMO_REDACTOR, @V_SERVRED=SERVICIO_REDACTOR from proyectos 
WHERE (CODIGO_MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto) 

select @v_existe_fase = count(1) from fasesdeproyectos
where
CODIGO_PLAN=@V_CODIGO_PLAN and
REFERENCIA =@V_REFERENCIA and
SUBREFERENCIA  =@V_SUBREFERENCIA and
AO_EJECUCION_OBRA =@V_AO_EJECUCION_OBRA

SET @V_NUM_FASE= @V_NUMERO_FASE
/* ******************************************************************************************************************************************* */	
		/*INICIO DE LOS PROCESOS SOLICITADOS SEGUN LA ACCION TRANSFERIDA*/
/* ******************************************************************************************************************************************* */

/* *****************************************************************************************************************************
 carga si se requiere ayuda técnica
****************************************************************************************************************************/
set @v_ayuda = 'NO'

if @v_organismo_DIRECCION = 'DP'  or @V_SUBVENCIONECONDIRECCION  = 1 or @V_SUBVECONR = 1 OR @V_ORGRED= 'DP'
begin
	set @v_ayuda = 'SI'
end

/* ******************************************************************************************************************************************* */	
/*  COMPROBACIONES ANTERIORES A INICIAR LA TRANSACCION (PARA ALTA Y MODIFICACIONES)                    */
/* ******************************************************************************************************************************************* */

if @v_existe = 0 
begin
	RETURN 4				/*NO EXISTE EL PROYECTO, NO SE PUEDE DAR DE ALTA LA FASE*/
end
iF @V_EXISTE_FASE = 1 
BEGIN
	RETURN 5				/* YA EXISTE LA FASE QUE SE QUIERE DAR DE ALTA*/
END

/*  GRABA UN REGISTRO SOLO CON LA CLAVE */

/* ******************************************************************************************************************************************* */	
/* carga el servicio director y el colegio oficial de dirección si existen*/
/* ******************************************************************************************************************************************* */	
if rtrim(@v_servicio_direccion) <> ' ' 
begin
	select @v_servdir = codigo_dpto from tablas..tabladedepartamentos where denominacion = @v_servicio_direccion
	set @v_error = @@error
	if @v_error <> 0
	begin
		RETURN 3		/* ERROR al leer el servicio director*/
	end
end
ELSE
BEGIN
	SET @V_SERVDIR = NULL
END
if rtrim(@v_colegiooficialdireccion) <> ' '
begin
	select @v_colofid = codigo_redactor from tablas..colegiosoficiales where organismo_redactor = @v_colegiooficialdireccion
	set @v_error = @@error
	if @v_error <> 0
	begin
		RETURN 3	/* ERROR al leer el colegio*/
	end
end
ELSE
BEGIN
	SET @V_COLOFID = NULL
END
/* ******************************************************************************************************************************************* */	
				/*CALCULA EL NUMERO DE FASE QUE CORRESPONDE AL ALTA*/
/* ****************************************************************************************************************************************** */
SELECT @V_NUMERO_FASE = MAX(NUMERO_FASE) FROM FASESDEProyectos
GROUP BY MUNICIPIO,     AO_PROYECTO, NUMERO_PROYECTO
HAVING (MUNICIPIO = @v_municipio)  AND (AO_PROYECTO = @v_ao_proyecto) AND (NUMERO_PROYECTO = @V_NUMERO_PROYECTO)
if @V_NUMERO_FASE is null 
begin
	set @V_NUMERO_FASE = 0
end
set @V_NUMERO_FASE = @V_NUMERO_FASE + 1
set @V_NUM_FASE = @V_NUMERO_FASE
set @v_error = @@error
IF @V_ERROR <> 0 
BEGIN
	RETURN 8			/* ERROR AL ASIGNAR UN NUEVO NÚMERO DE FASE*/
END

/* ******************************************************************************************************************************************** 
		 AÑADE UN REGISTRO A FASESDEPROYECTO CON LA CLAVE SOLAMENTE
	
******************************************************************************************************************************************** */	
INSERT INTO FASESDEPROYECTOS
		   (MUNICIPIO, AO_PROYECTO, NUMERO_PROYECTO,AO_FASE,NUMERO_FASE)
VALUES (@V_MUNICIPIO, @V_AO_PROYECTO,@V_NUMERO_PROYECTO,@V_AO_FASE, @V_NUMERO_FASE)
set @v_error = @@error
if @v_error <> 0
begin
	RETURN 9			/*ERROR EN EL ALTA DE LA FASE*/
end


	
/* FINALIZA EL ALTA Y COMIENZA LA ACTUALIZACION*/

/*************************************************************************************************************************************************
	comprueba el estado que le corresponde a la fase
************************************************************************************************************************************************** */
UPDATE FASESDEPROYECTOS
SET
Servicio_gestor=@V_servicio_gestor,  
CODIGO_PLAN=@V_CODIGO_PLAN,
REFERENCIA =@V_REFERENCIA,
SUBREFERENCIA  =@V_SUBREFERENCIA,
AO_EJECUCION_OBRA =@V_AO_EJECUCION_OBRA,
CARRETERA =@V_CARRETERA,
PLAZO = @V_PLAZO,
UNIDADPLAZO = @V_UNIDAD_PLAZO,
NRO_EJEMPLARES = @V_NRO_EJEMPLARES,

REVISION  = @V_REVISION,
FORMULA = @V_FORMULA,
FORMULA2 = @V_FORMULA2,
FORMULA3 = @V_FORMULA3,
FORMULA4 = @V_FORMULA4,
ORGANISMO_DIRECCION = @V_ORGANISMO_DIRECCION,
SERVICIO_DIRECCION  = @V_SERVDIR,
DIRECTOR_TECNICO_OBRA  = @V_DIRECTOR_TECNICO_OBRA,
COLEGIOOFICIALDIRECCION   = @V_COLOFID,
SUBVENCIONECONDIRECCION = @V_SUBVENCIONECONDIRECCION,
nro_dto  = @V_nro_dto,
estado_FASE  = 'TFA',
CLASE_EXP = @V_CLASE_EXP,
TIPO_PROC   = @V_TIPO_PROC,
FORMA_CONT    = @V_FORMA_CONT,
Requiere_PlanSyS     = @V_Requiere_PlanSyS,

/*IMPORTES*/

IMPORTE_FASE  = @V_IMPORTE_FASE ,
presu_gral_ejecucion_material  = @V_presu_gral_ejecucion_material,
por_gastos_generales  = @V_por_gastos_generales,
importe_gastos_generales  = @V_importe_gastos_generales,
por_beneficio_industriales  = @V_por_beneficio_industriales,
importe_beneficio_industriales  = @V_importe_beneficio_industriales,
por_control_calidad  = @V_por_control_calidad,
importe_control_calidad  = @V_importe_control_calidad,
por_iva =@V_por_iva,
Iva  =@V_iva,
por_subcontrata  = @V_por_subcontrata,
subcontrata  = @V_subcontrata,
honorarios_dir  = @V_honorarios_dir,
honorarios_red  = @V_honorarios_red,

/* FECHAS*/

FECHA_REM_FASE  = @V_FECHA_REM_FASE,
fecha_ENT_FASE = @V_fecha_ENT_FASE,
fecha_REMISION_ayto  =  @V_fecha_REMISION_ayto,
fecha_aprobacion_ayto  =  @V_fecha_aprobacion_ayto,
fecha_REMISION_JUNTA  =  @V_fecha_REMISION_JUNTA,
FECHA_VISADO_JUNTA =   @V_FECHA_VISADO_JUNTA,
fecha_PET_INF_TECNICO_CONTRATA  =   @V_fecha_PET_INF_TECNICO_CONTRATA,
fecha_ENT_INF_TECNICO_CONTRATA   =   @V_fecha_ENT_INF_TECNICO_CONTRATA,
PLIEGO_CLAUSULAS_PARTICULARES   =   @V_PLIEGO_CLAUSULAS_PARTICULARES,
fecha_PET_DESGLOSE  =   @V_fecha_PET_DESGLOSE,
fecha_ENT_DESGLOSE   =   @V_fecha_ENT_DESGLOSE,
fecha_pet_rectificacion   =  @V_fecha_pet_rectificacion,
fecha_ent_rectificacion   =   @V_fecha_ent_rectificacion,
fecha_pet_reforma   =   @V_fecha_pet_reforma,
fecha_ent_reforma  =    @V_fecha_ent_reforma,
fecha_pit_ref   =   @V_fecha_pit_ref,
fecha_eit_ref   =   @V_fecha_eit_ref,
fecha_ci_ref    =   @V_fecha_ci_ref,
fecha_cg_ref   =   @V_fecha_cg_ref,
FECHA_PET_ACTUAL_PRECIOS   =  @V_FECHA_PET_ACTUAL_PRECIOS,
FECHA_ENT_ACTUAL_PRECIOS   =   @V_FECHA_ENT_ACTUAL_PRECIOS,
FECHA_ENVIO_FISCALIZACION  = @V_FECHA_ENVIO_FISCALIZACION,
fecha_COM_INF   = @V_fecha_COM_INF,
fecha_COM_GOB  =  @V_fecha_COM_GOB,
fecha_FISCALIZACION  =   @V_FISCALIZACION,
FECHA_REMISION_CONTRATACION   =  @V_FECHA_REMISION_CONTRATACION,
fecha_dto   =   @V_fecha_dto

WHERE (MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto) AND 
(AO_FASE= @V_AO_FASE) AND
(NUMERO_FASE = @V_NUMERO_FASE)

set @v_error = @@error
if @v_error <> 0
begin
	RETURN 10			/* ERROR EN LA ACTUALIZACION DEL REGISTRO*/
end
/*********************************************************************************************************************************************/	
					/*ACTUALIZA o BORRA LA  AYUDATECNICA*/
/*********************************************************************************************************************************************/	
if @v_SUBVENCIONECONDIRECCION = 1 
begin
	set @SUBVENCION = 'SI'
end
else
begin
	SET @SUBVENCION = 'NO'
end
if @v_SUBVECONR = 1 
begin
	set @SUBVENCIONR = 'SI'
end
else
begin
	SET @SUBVENCIONR = 'NO'
end


if @v_ayuda = 'SI'
begin
UPDATE Ayuda_Tecnica
	SET Pasado = 1,
	  SubvencionEconomicaR = @subvencionR,
	  SubvencionEconomicaD = @subvencion,
               DEPARTAMENTO_DIRECCION=@V_SERVDIR,
	  DPTO_REDACTOR =@V_SERVRED		
		
		
	WHERE	codigo_plan = @V_CODIGO_PLAN
	and 		numero_obra = @V_REFERENCIA
	and		subreferencia = @V_SUBREFERENCIA
	and		ao_ejecucion = @V_AO_EJECUCION_OBRA
end
else
begin
	delete from ayuda_tecnica
	WHERE	codigo_plan = @V_CODIGO_PLAN
	and 		numero_obra = @V_REFERENCIA
	and		subreferencia = @V_SUBREFERENCIA
	and		ao_ejecucion = @V_AO_EJECUCION_OBRA
end
	
set @v_error = @@error


if @v_error <> 0
BEGIN
	
	RETURN 1
END

/*********************************************************************************************************************************************/	
				/*ACTUALIZA EL CAMPO codigo_estado_obra DE DATOSINICIODEOBRAS*/
/*********************************************************************************************************************************************/	
UPDATE DatosInicioDeObras
	SET Codigo_estado_obra = 'TPR',
	        PETICION_AYUDA_TEC= @V_AYUDA	
	WHERE	codigo_plan = @V_CODIGO_PLAN
	and 		numero_obra = @V_REFERENCIA
	and		subreferencia = @V_SUBREFERENCIA
	and		ao_ejecucion = @V_AO_EJECUCION_OBRA
set @v_error = @@error

if @v_error <> 0
BEGIN
	
	RETURN 2
END

/*********************************************************************************************************************************************/	
				/*ACTUALIZA EL CAMPO codigo_estado DE proyectos*/
/*********************************************************************************************************************************************/	
UPDATE  proyectos
SET estado_proyecto = 'TPR'
WHERE (CODIGO_MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto) 

set @v_error = @@error

if @v_error <> 0
BEGIN
	
	RETURN 2
END

/*********************************************************************************************************************************************/	
				/*FINALIZA CORRECTAMENTE EL ALTA Y TERMINA LA TRANSACCION*/
/*********************************************************************************************************************************************/	

RETURN 0			/*PROCESO REALIZADO CORRECTAMENTE*/
GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Modificando Procedimiento [diana].[PA_PROYECTOS_A_CreaUnProyectoCompletoNuevo]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
ALTER PROCEDURE  [diana].[PA_PROYECTOS_A_CreaUnProyectoCompletoNuevo] 
/* VARIABLES PARA EL PROYECTO*/

@V_CODIGO_MUNICIPIO as smallint,
@V_AO_PROYECTO as smallint, 
@V_SERVICIO_GESTOR AS SMALLINT,
@V_den_proyecto as varchar(150),
@V_importe_proyecto as float, 
@V_importe_fase as float, 
@V_organismo_redactor as char(2) ,
@V_SERVICIO_redactor AS varchar(150),
@V_autor as char(50),
@V_COLEGIOOFICIAL AS varchar(150),
@V_SUBVENCION_economicaR as bit = 0,
@V_SUBVENCION_ECONOMICAD AS BIT = 0 ,
@V_SERVICIO_DIRECCION AS SMALLINT= null,
@V_carretera as char(6),
@V_plazo as int,
@V_Unidad_Plazo as char(1) = 'm' ,
@V_nro_ejemplares as smallint, 
@V_revision as char(2),
@V_formula as int,
@V_formula2 as int,
@V_formula3 as int,
@V_formula4 as int, 
@V_presu_gral_ejecucion_material as float, 
@V_por_gastos_generales as float,  
@V_importe_gastos_generales as float, 
@V_por_beneficio_industriales as float, 
@V_importe_beneficio_industriales as float, 
@V_por_control_calidad as float,  
@V_importe_control_calidad as float,  
@V_por_iva as float, 
@V_iva as float, 
@V_por_subcontrata as float, 
@V_subcontrata as float, 
@V_honorarios_dir as float, 
@V_honorarios_red as float,  
@V_plan_ss as float,
@V_IVA_Honor_Direcc as bit,
@V_IVA_Honor_Redacc as bit,
@V_presu_gral_ejecucion_material_fase as float, 
@V_por_gastos_generales_fase as float,  
@V_importe_gastos_generales_fase as float, 
@V_por_beneficio_industriales_fase as float, 
@V_importe_beneficio_industriales_fase as float, 
@V_por_control_calidad_fase as float,  
@V_importe_control_calidad_fase as float,  
@V_por_iva_fase as float, 
@V_iva_fase as float, 
@V_por_subcontrata_fase as float, 
@V_subcontrata_fase as float, 
@V_honorarios_dir_fase as float, 
@V_honorarios_red_fase as float,  
@V_plan_ss_fase as float,
@V_IVA_Honor_Direcc_fase as bit,
@V_IVA_Honor_Redacc_fase as bit,
@V_fecha_entrega_proyecto as smalldatetime = NULL,
@V_fecha_recepcion_proyecto as smalldatetime = NULL,
@V_fecha_remision_ayto as smalldatetime = NULL,
@V_fecha_aprobacion_ayto as smalldatetime = NULL, 
@V_fecha_pet_rectificacion as smalldatetime = NULL,
@V_fecha_ent_rectificacion as smalldatetime = NULL,
@V_fecha_pet_reforma as smalldatetime = NULL,
@V_fecha_ent_reforma as smalldatetime = NULL,
@V_fecha_c_infor as smalldatetime = NULL,
@V_fecha_c_gob as smalldatetime = NULL,
@V_fecha_pit_ref as smalldatetime = NULL,
@V_fecha_eit_ref as smalldatetime = NULL,
@V_fecha_ci_ref as smalldatetime = NULL, 
@V_fecha_cg_ref as smalldatetime = NULL,
@V_fecha_dto as smalldatetime = NULL,
@V_nro_dto as float,
@V_observaciones as varchar(240), 
@V_estado_proyecto as CHAR(3),
@V_Requiere_PlanSyS as bit ,
@V_Requiere_TratMed as bit ,
@v_compartido as bit = 0,
/*  VARIABLES ADICIONALES PARA CREAR  LA FASE 1 DEL PROYECTO*/
@V_plan as char(7),
@v_numobra as smallint,
@v_subref as int,
@v_aoplan as smallint,
@V_NUM_PROYECTO as smallint output
 AS
/*	Graba un nuevo registro en Proyectos, Graba un registro de FasesProyectos asociado a la obra.
	Actualiza AyudaTecnica para grabar el estado_proyecto a true (existe proyecto asociado).
	Actualiza el Estado_Obra en DatosInicioObra  a TPR (Trámite proyecto)
	Valores devueltos:
		0 -- Funcionamento correcto
		1 -- Fallo en la búsqueda del número de proyecto
		2 -- Fallo del Insert de Proyectos
		3 -- Fallo del Insert en FasesProyectos
		4 -- Fallo del Update de AyudaTecnica
		5 -- Fallo del Update de DatosInicioObras
		6 -- Ya existe el proyecto asociado a la obra
		8 -- Error al cargar el servicio redactor o colegio
*/

declare @v_error as int
declare @v_numero_proyecto as int
declare @v_existe as int
declare @subvencion as char(2)
declare @v_servred as smallint
declare @v_colofir as char(2)
DECLARE @v_ayuda as char(2)
DECLARE @V_ESTADO_OBRA1 AS CHAR(3)
DECLARE @V_ESTADO_FASE1 AS CHAR(3)
DECLARE @V_FORMAEJEC       AS CHAR(3) 

/* *****************************************************************************************************************************
 carga si se requiere ayuda técnica
****************************************************************************************************************************/
set @v_ayuda = 'NO'

if @v_organismo_redactor = 'DP'  or @V_SUBVENCION_economicaR  = 1 or @V_SUBVENCION_economicad = 1
begin
	set @v_ayuda = 'SI'
end
/* *****************************************************************************************************************************
 carga el código del servicio redactor y el colegio oficial de redacción si existen
****************************************************************************************************************************/
if rtrim(@v_servicio_redactor) <> ' ' 
begin
	select @v_servred = codigo_dpto from tablas..tabladedepartamentos where denominacion = @v_servicio_redactor
	set @v_error = @@error
	if @v_error <> 0
	begin
		RETURN 8			/* ERROR al leer el servicio redactorr*/
	end
end
else 
begin
 	set @v_servred = null
end
if rtrim(@v_colegiooficial) <> ' '
begin
	select @v_colofiR = codigo_redactor from tablas..colegiosoficiales where organismo_redactor = @v_colegiooficial
	set @v_error = @@error
	if @v_error <> 0
	begin
		RETURN 8			/* ERROR al leer el colegio*/
	end
end
ELSE
BEGIN
	SET @V_COLOFIR = NULL
END
/*********************************************************************************************************************************************/	
				/*COMPRUEBA QUE NO EXISTA*/
/*********************************************************************************************************************************************/

SELECT @v_existe = count (codigo_plan) FROM FasesDeProyectos WHERE
Codigo_Plan = @V_PLAN AND referencia = @V_NUMOBRA AND subreferencia = @V_SUBREF AND  ao_ejecucion_obra = @V_AOPLAN
IF @v_existe > 0 
BEGIN  return 6
end



/*********************************************************************************************************************************************/	
				/*CALCULA EL NUEVO NUMERO DE PROYECTO*/
/*********************************************************************************************************************************************/
SELECT @v_numero_proyecto = MAX(NUMERO_PROYECTO) FROM Proyectos
GROUP BY CODIGO_MUNICIPIO,     AO_PROYECTO
HAVING (CODIGO_MUNICIPIO = @v_codigo_municipio)  AND (AO_PROYECTO = @v_ao_proyecto)
if @v_numero_proyecto is null 
begin
 set @v_numero_proyecto=0
end

set @v_numero_proyecto = @v_numero_proyecto + 1


set @v_num_proyecto=@v_numero_proyecto
set @v_error = @@error


if @v_error <> 0
begin
	return 1
end


BEGIN TRANSACTION
/*********************************************************************************************************************************************/	
				/* ALTA DE UN REGISTRO EN PROYECTOS*/
/*********************************************************************************************************************************************/	

INSERT INTO [Proyectos]
    ( CODIGO_MUNICIPIO, AO_PROYECTO, 
    NUMERO_PROYECTO,SERVICIO_GESTOR, den_proyecto, importe_proyecto, 
    organismo_redactor,servicio_redactor, autor, colegiooficial,subvencioneconredaccion,carretera, plazo, unidadplazo, nro_ejemplares, 
    revision, formula, formula2, formula3, formula4, 
    presu_gral_ejecucion_material, por_gastos_generales, 
    importe_gastos_generales, por_beneficio_industriales, 
    importe_beneficio_industriales, por_control_calidad, 
    importe_control_calidad, por_iva, iva, por_subcontrata, 
    subcontrata, honorarios_dir, honorarios_red, 
    importeplansys, HD_ExcluidoIVA, HR_ExcluidoIVA,
    fecha_entrega_proyecto, fecha_recepcion_proyecto, 
    fecha_remision_ayto, fecha_aprobacion_ayto, 
    fecha_pet_rectificacion, fecha_ent_rectificacion, 
    fecha_pet_reforma, fecha_ent_reforma, fecha_c_infor, 
    fecha_c_gob, fecha_pit_ref, fecha_eit_ref, fecha_ci_ref, 
    fecha_cg_ref, fecha_dto, nro_dto, observaciones, 
    estado_proyecto, Requiere_PlanSyS,Requiere_TramAmbiental,compartido)
    	
values
    (@V_CODIGO_MUNICIPIO,@V_AO_PROYECTO, 
    @V_NUMERO_PROYECTO, @V_SERVICIO_GESTOR, @V_den_proyecto,@V_importe_proyecto, 
    @V_organismo_redactor, @v_servred,@V_autor, @v_colofiR, @v_subvencion_economicaR, @V_carretera, @V_plazo, @V_unidad_plazo, @V_nro_ejemplares, 
    @V_revision, @V_formula, @V_formula2, @V_formula3, @V_formula4, 
    @V_presu_gral_ejecucion_material, @V_por_gastos_generales, 
    @V_importe_gastos_generales, @V_por_beneficio_industriales, 
    @V_importe_beneficio_industriales, @V_por_control_calidad, 
    @V_importe_control_calidad, @V_por_iva, @V_iva, @V_por_subcontrata, 
    @V_subcontrata, @V_honorarios_dir, @V_honorarios_red, 
    @V_plan_ss, @V_IVA_Honor_Direcc, @V_IVA_Honor_Redacc,
    @V_fecha_entrega_proyecto, @V_fecha_recepcion_proyecto, 
    @V_fecha_remision_ayto, @V_fecha_aprobacion_ayto, 
    @V_fecha_pet_rectificacion, @V_fecha_ent_rectificacion, 
    @V_fecha_pet_reforma, @V_fecha_ent_reforma, @V_fecha_c_infor, 
   @V_fecha_c_gob, @V_fecha_pit_ref, @V_fecha_eit_ref, @V_fecha_ci_ref, 
   @V_fecha_cg_ref, @V_fecha_dto, @V_nro_dto, @V_observaciones, 
    'TPR', @V_Requiere_PlanSyS,@V_Requiere_TratMed,@v_compartido)

select * from proyectos where  (CODIGO_MUNICIPIO = @v_codigo_municipio and  AO_PROYECTO = @v_ao_proyecto and  NUMERO_PROYECTO = @v_numero_proyecto)

set @v_error = @@error


if @v_error <> 0
BEGIN
	ROLLBACK
	RETURN 2
END

/*********************************************************************************************************************************************/	
					/* ALTA DE LA FASE NUMERO 1*/
/*********************************************************************************************************************************************/	

/* LEE EL ESTADO DE LA OBRA EN INICIO Y FORMA DE EJECUCION Y PETICION DE AYUDA*/
select @v_estado_obra1= codigo_estado_obra, @V_FORMAEJEC = FORMA_EJECUCION
from datosiniciodeobras
where  codigo_plan= @V_PLAN and
numero_obra = @V_numobra and
subreferencia = @V_SUBREF and
ao_ejecucion = @V_aoplan


set @V_ESTADO_FASE1 = 'TFA'

/* ******************************************************************************************************************************************* */	
/*  COMPROBACIONES ANTERIORES A INICIAR LA TRANSACCION                 	   */
/* ******************************************************************************************************************************************* */

/* ACTUALIZACION DE LOS ESTADOS*/
IF (@V_ESTADO_OBRA1 = 'DES' ) AND (@V_FORMAEJEC = 'DIP')
BEGIN

	SET @V_ESTADO_FASE1='TE'
	SET @V_ESTADO_OBRA1='PA'
END
IF (@V_ESTADO_OBRA1 = 'TPR' OR @V_ESTADO_OBRA1 = 'PA') AND (@V_FORMAEJEC = 'DIP')
BEGIN

	SET @V_ESTADO_FASE1='TE'
	SET @V_ESTADO_OBRA1='PA'
END
IF (@V_ESTADO_OBRA1 = 'TPR' OR @V_ESTADO_OBRA1 = 'PA') AND (@V_FORMAEJEC = 'DIP')
BEGIN
	
	SET @V_ESTADO_FASE1= 'TFA'
	SET @V_ESTADO_OBRA1='TPR'
END
IF (@V_ESTADO_OBRA1 = 'TPR' OR @V_ESTADO_OBRA1 = 'PA') AND @V_FORMAEJEC <>  'DIP'  AND (@V_FECHA_C_GOB <> '  ' OR  @V_FECHA_DTO <>'  ')
BEGIN

	SET @V_ESTADO_FASE1= 'TE'
	SET @V_ESTADO_OBRA1='PA'
END	
IF (@V_ESTADO_OBRA1 = 'TPR' OR @V_ESTADO_OBRA1 = 'PA') AND @V_FORMAEJEC <>  'DIP'  AND (@V_FECHA_C_GOB = NULL AND   @V_FECHA_DTO = NULL)
BEGIN

	SET @V_ESTADO_FASE1= 'TFA'
	SET @V_ESTADO_OBRA1='TPR'
END	


INSERT INTO FasesDeProyectos 
 	 (MUNICIPIO, AO_PROYECTO, NUMERO_PROYECTO, NUMERO_FASE, AO_FASE, importe_fase,
	Codigo_Plan, referencia, subreferencia, ao_ejecucion_obra, carretera, plazo, unidadplazo, nro_ejemplares, revision, formula, formula2, formula3, formula4, 
	organismo_direccion, Servicio_direccion, director_tecnico_obra, colegiooficialdireccion,subvencionecondireccion,
  	 presu_gral_ejecucion_material, por_gastos_generales,  importe_gastos_generales, por_beneficio_industriales, 
  	 importe_beneficio_industriales, por_control_calidad,  importe_control_calidad, Por_iva, iva, por_subcontrata, 
 	 subcontrata, honorarios_dir, honorarios_red, importeplansys, HD_ExcluidoIVA, HR_ExcluidoIVA, fecha_rem_fase,   fecha_ent_fase, fecha_remision_ayto, 
	 fecha_aprobacion_ayto,  fecha_pet_rectificacion, fecha_ent_rectificacion,  fecha_pet_reforma, fecha_ent_reforma,  fecha_pit_ref, fecha_eit_ref,
	 fecha_cg_ref,  fecha_ci_ref,  fecha_com_gob,   fecha_dto,  nro_dto,  fecha_com_inf,estado_fase, Requiere_PlanSyS)
VALUES 
	 (@V_CODIGO_MUNICIPIO,@V_AO_PROYECTO, 
  	 @V_NUMERO_PROYECTO, 1, @V_AO_PROYECTO,@V_importe_fase, @V_PLAN,@V_NUMOBRA,@V_SUBREF,@V_AOPLAN,
    	 @V_carretera, @V_plazo, @V_unidad_plazo, @V_nro_ejemplares, @V_revision, @V_formula, @V_formula2, @V_formula3, @V_formula4, 
	@V_organismo_redactor, @V_SERVRED,@V_autor,@v_colofir,@v_subvencion_economicad,
    	@V_presu_gral_ejecucion_material_fase, @V_por_gastos_generales_fase, @V_importe_gastos_generales_fase, @V_por_beneficio_industriales_fase, 
	@V_importe_beneficio_industriales_fase, @V_por_control_calidad_fase, @V_importe_control_calidad_fase, @V_por_iva_fase, @V_iva_fase, @V_por_subcontrata_fase, 
    	@V_subcontrata_fase, @V_honorarios_dir_fase, @V_honorarios_red_fase, @V_plan_ss_fase, @V_IVA_Honor_Direcc_fase, @V_IVA_Honor_Redacc_fase,
	@V_fecha_entrega_proyecto, @V_fecha_recepcion_proyecto, @V_fecha_remision_ayto, @V_fecha_aprobacion_ayto, @V_fecha_pet_rectificacion, @V_fecha_ent_rectificacion, 
    	@V_fecha_pet_reforma, @V_fecha_ent_reforma,  @V_fecha_pit_ref, @V_fecha_eit_ref, @V_fecha_cg_ref, @V_fecha_ci_ref, @V_fecha_c_gob,
	@V_fecha_dto, @V_nro_dto, @V_fecha_c_infor , @V_ESTADO_FASE1, @V_Requiere_PlanSyS)
	
 
set @v_error = @@error


if @v_error <> 0
BEGIN
	ROLLBACK
	RETURN 3
END

/*********************************************************************************************************************************************/	
					/*ACTUALIZA o BORRA LA  AYUDATECNICA*/
/*********************************************************************************************************************************************/	
if @v_subvencion_economicaR = 1 
begin
	set @SUBVENCION = 'SI'
end
else
begin
	SET @SUBVENCION = 'NO'
end
if @v_ayuda = 'SI'
begin
UPDATE Ayuda_Tecnica
	SET Pasado = 1,
	        SubvencionEconomicaR = @subvencion	
		
		
	WHERE	codigo_plan = @V_PLAN
	and 		numero_obra = @V_NUMOBRA
	and		subreferencia = @V_SUBREF
	and		ao_ejecucion = @V_AOPLAN
end
else
begin
	delete from ayuda_tecnica
	WHERE	codigo_plan = @V_PLAN
	and 		numero_obra = @V_NUMOBRA
	and		subreferencia = @V_SUBREF
	and		ao_ejecucion = @V_AOPLAN
end
	
set @v_error = @@error


if @v_error <> 0
BEGIN
	ROLLBACK
	RETURN 4
END

/*********************************************************************************************************************************************/	
				/*ACTUALIZA EL CAMPO codigo_estado_obra DE DATOSINICIODEOBRAS*/
/*********************************************************************************************************************************************/	
UPDATE DatosInicioDeObras
	SET Codigo_estado_obra = @V_ESTADO_OBRA1,
	        PETICION_AYUDA_TEC= @V_AYUDA	
	       
	WHERE	codigo_plan = @V_PLAN
	and 		numero_obra = @V_NUMOBRA
	and		subreferencia = @V_SUBREF
	and		ao_ejecucion = @V_AOPLAN
	
set @v_error = @@error

if @v_error <> 0
BEGIN
	ROLLBACK
	RETURN 5
END

/*********************************************************************************************************************************************/	
				/*FINALIZA CORRECTAMENTE EL ALTA Y TERMINA LA TRANSACCION*/
/*********************************************************************************************************************************************/	


COMMIT
RETURN 0
GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Modificando Procedimiento [dbo].[PA_PROYECTOS_A_CreaUnProyectoCompleto]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
ALTER PROCEDURE  [dbo].[PA_PROYECTOS_A_CreaUnProyectoCompleto] 
/* VARIABLES PARA EL PROYECTO*/

@V_CODIGO_MUNICIPIO as smallint,
@V_AO_PROYECTO as smallint, 
@V_SERVICIO_GESTOR AS SMALLINT,
@V_den_proyecto1 as varchar(500),
@V_importe_proyecto as float, 
@V_importe_fase as float, 
@V_organismo_redactor as char(2) ,
@V_SERVICIO_redactor AS varchar(150),
@V_autor as char(50),
@V_COLEGIOOFICIAL AS varchar(150),
@V_SUBVENCION_economicaR as bit = 0,
@V_SUBVENCION_ECONOMICAD AS BIT = 0 ,
@V_SERVICIO_DIRECCION AS SMALLINT= null,
@V_carretera as char(15),
@V_plazo as int,
@V_Unidad_Plazo as char(1) = 'm' ,
@V_nro_ejemplares as smallint, 
@V_revision as char(2),
@V_formula as int,
@V_formula2 as int,
@V_formula3 as int,
@V_formula4 as int, 
@V_presu_gral_ejecucion_material as float, 
@V_por_gastos_generales as float,  
@V_importe_gastos_generales as float, 
@V_por_beneficio_industriales as float, 
@V_importe_beneficio_industriales as float, 
@V_por_control_calidad as float,  
@V_importe_control_calidad as float,  
@V_por_iva as float, 
@V_iva as float, 
@V_por_subcontrata as float, 
@V_subcontrata as float, 
@V_honorarios_dir as float, 
@V_honorarios_red as float,  
@V_plan_ss as float,
@V_IVA_Honor_Direcc as bit,
@V_IVA_Honor_Redacc as bit,
@V_presu_gral_ejecucion_material_fase as float, 
@V_por_gastos_generales_fase as float,  
@V_importe_gastos_generales_fase as float, 
@V_por_beneficio_industriales_fase as float, 
@V_importe_beneficio_industriales_fase as float, 
@V_por_control_calidad_fase as float,  
@V_importe_control_calidad_fase as float,  
@V_por_iva_fase as float, 
@V_iva_fase as float, 
@V_por_subcontrata_fase as float, 
@V_subcontrata_fase as float, 
@V_honorarios_dir_fase as float, 
@V_honorarios_red_fase as float,  
@V_plan_ss_fase as float,
@V_IVA_Honor_Direcc_fase as bit,
@V_IVA_Honor_Redacc_fase as bit,
@V_fecha_entrega_proyecto as smalldatetime = NULL,
@V_fecha_recepcion_proyecto as smalldatetime = NULL,
@V_fecha_remision_ayto as smalldatetime = NULL,
@V_fecha_aprobacion_ayto as smalldatetime = NULL, 
@V_fecha_pet_rectificacion as smalldatetime = NULL,
@V_fecha_ent_rectificacion as smalldatetime = NULL,
@V_fecha_pet_reforma as smalldatetime = NULL,
@V_fecha_ent_reforma as smalldatetime = NULL,
@V_fecha_c_infor as smalldatetime = NULL,
@V_fecha_c_gob as smalldatetime = NULL,
@V_fecha_pit_ref as smalldatetime = NULL,
@V_fecha_eit_ref as smalldatetime = NULL,
@V_fecha_ci_ref as smalldatetime = NULL, 
@V_fecha_cg_ref as smalldatetime = NULL,
@V_fecha_dto as smalldatetime = NULL,
@V_nro_dto as float,
@V_observaciones as varchar(240), 
@V_estado_proyecto as CHAR(3),
@V_Requiere_PlanSyS as bit ,
@V_Requiere_TratMed as bit ,
@v_compartido as bit = 0,
/*  VARIABLES ADICIONALES PARA CREAR  LA FASE 1 DEL PROYECTO*/
@V_plan as char(7),
@v_numobra as smallint,
@v_subref as int,
@v_aoplan as smallint,
@V_NUM_PROYECTO as smallint output
 AS
/*	Graba un nuevo registro en Proyectos, Graba un registro de FasesProyectos asociado a la obra.
	Actualiza AyudaTecnica para grabar el estado_proyecto a true (existe proyecto asociado).
	Actualiza el Estado_Obra en DatosInicioObra  a TPR (Trámite proyecto)
	Valores devueltos:
		0 -- Funcionamento correcto
		1 -- Fallo en la búsqueda del número de proyecto
		2 -- Fallo del Insert de Proyectos
		3 -- Fallo del Insert en FasesProyectos
		4 -- Fallo del Update de AyudaTecnica
		5 -- Fallo del Update de DatosInicioObras
		6 -- Ya existe el proyecto asociado a la obra
		8 -- Error al cargar el servicio redactor o colegio
*/

declare @v_error as int
declare @v_numero_proyecto as int
declare @v_existe as int
declare @subvencion as char(2)
declare @v_servred as smallint
declare @v_colofir as char(2)
DECLARE @v_ayuda as char(2)


/* *****************************************************************************************************************************
 carga si se requiere ayuda técnica
****************************************************************************************************************************/
set @v_ayuda = 'NO'

if @v_organismo_redactor = 'DP'  or @V_SUBVENCION_economicaR  = 1 or @V_SUBVENCION_economicad = 1
begin
	set @v_ayuda = 'SI'
end
/* *****************************************************************************************************************************
 carga el código del servicio redactor y el colegio oficial de redacción si existen
****************************************************************************************************************************/
if rtrim(@v_servicio_redactor) <> ' ' 
begin
	select @v_servred = codigo_dpto from tablas..tabladedepartamentos where denominacion = @v_servicio_redactor
	set @v_error = @@error
	if @v_error <> 0
	begin
		RETURN 8			/* ERROR al leer el servicio redactorr*/
	end
end
else 
begin
 	set @v_servred = null
end
if rtrim(@v_colegiooficial) <> ' '
begin
	select @v_colofiR = codigo_redactor from tablas..colegiosoficiales where organismo_redactor = @v_colegiooficial
	set @v_error = @@error
	if @v_error <> 0
	begin
		RETURN 8			/* ERROR al leer el colegio*/
	end
end
ELSE
BEGIN
	SET @V_COLOFIR = NULL
END
/*********************************************************************************************************************************************/	
				/*COMPRUEBA QUE NO EXISTA*/
/*********************************************************************************************************************************************/

SELECT @v_existe = count (codigo_plan) FROM FasesDeProyectos WHERE
Codigo_Plan = @V_PLAN AND referencia = @V_NUMOBRA AND subreferencia = @V_SUBREF AND  ao_ejecucion_obra = @V_AOPLAN
IF @v_existe > 0 
BEGIN  return 6
end



/*********************************************************************************************************************************************/	
				/*CALCULA EL NUEVO NUMERO DE PROYECTO*/
/*********************************************************************************************************************************************/
SELECT @v_numero_proyecto = MAX(NUMERO_PROYECTO) FROM Proyectos
GROUP BY CODIGO_MUNICIPIO,     AO_PROYECTO
HAVING (CODIGO_MUNICIPIO = @v_codigo_municipio)  AND (AO_PROYECTO = @v_ao_proyecto)
if @v_numero_proyecto is null 
begin
 set @v_numero_proyecto=0
end

set @v_numero_proyecto = @v_numero_proyecto + 1


set @v_num_proyecto=@v_numero_proyecto
set @v_error = @@error


if @v_error <> 0
begin
	return 1
end


BEGIN TRANSACTION
/*********************************************************************************************************************************************/	
				/* ALTA DE UN REGISTRO EN PROYECTOS*/
/*********************************************************************************************************************************************/	

INSERT INTO [Proyectos]
    ( CODIGO_MUNICIPIO, AO_PROYECTO, 
    NUMERO_PROYECTO,SERVICIO_GESTOR, den_proyecto, importe_proyecto, 
    organismo_redactor,servicio_redactor, autor, colegiooficial,subvencioneconredaccion,carretera, plazo, unidadplazo, nro_ejemplares, 
    revision, formula, formula2, formula3, formula4, 
    presu_gral_ejecucion_material, por_gastos_generales, 
    importe_gastos_generales, por_beneficio_industriales, 
    importe_beneficio_industriales, por_control_calidad, 
    importe_control_calidad, por_iva, iva, por_subcontrata, 
    subcontrata, honorarios_dir, honorarios_red, 
    importeplansys, HD_ExcluidoIVA, HR_ExcluidoIVA,
    fecha_entrega_proyecto, fecha_recepcion_proyecto, 
    fecha_remision_ayto, fecha_aprobacion_ayto, 
    fecha_pet_rectificacion, fecha_ent_rectificacion, 
    fecha_pet_reforma, fecha_ent_reforma, fecha_c_infor, 
    fecha_c_gob, fecha_pit_ref, fecha_eit_ref, fecha_ci_ref, 
    fecha_cg_ref, fecha_dto, nro_dto, observaciones, 
    estado_proyecto, Requiere_PlanSyS,Requiere_TramAmbiental,compartido)
    	
values
    (@V_CODIGO_MUNICIPIO,@V_AO_PROYECTO, 
    @V_NUMERO_PROYECTO, @V_SERVICIO_GESTOR, @V_den_proyecto1,@V_importe_proyecto, 
    @V_organismo_redactor, @v_servred,@V_autor, @v_colofiR, @v_subvencion_economicaR, @V_carretera, @V_plazo, @V_unidad_plazo, @V_nro_ejemplares, 
    @V_revision, @V_formula, @V_formula2, @V_formula3, @V_formula4, 
    @V_presu_gral_ejecucion_material, @V_por_gastos_generales, 
    @V_importe_gastos_generales, @V_por_beneficio_industriales, 
    @V_importe_beneficio_industriales, @V_por_control_calidad, 
    @V_importe_control_calidad, @V_por_iva, @V_iva, @V_por_subcontrata, 
    @V_subcontrata, @V_honorarios_dir, @V_honorarios_red, 
    @V_plan_ss, @V_IVA_Honor_Direcc, @V_IVA_Honor_Redacc,
    @V_fecha_entrega_proyecto, @V_fecha_recepcion_proyecto, 
    @V_fecha_remision_ayto, @V_fecha_aprobacion_ayto, 
    @V_fecha_pet_rectificacion, @V_fecha_ent_rectificacion, 
    @V_fecha_pet_reforma, @V_fecha_ent_reforma, @V_fecha_c_infor, 
   @V_fecha_c_gob, @V_fecha_pit_ref, @V_fecha_eit_ref, @V_fecha_ci_ref, 
   @V_fecha_cg_ref, @V_fecha_dto, @V_nro_dto, @V_observaciones, 
    'TPR', @V_Requiere_PlanSyS,@V_Requiere_TratMed,@v_compartido)

select * from proyectos where  (CODIGO_MUNICIPIO = @v_codigo_municipio and  AO_PROYECTO = @v_ao_proyecto and  NUMERO_PROYECTO = @v_numero_proyecto)

set @v_error = @@error


if @v_error <> 0
BEGIN
	ROLLBACK
	RETURN 2
END

/*********************************************************************************************************************************************/	
					/* ALTA DE LA FASE NUMERO 1*/
/*********************************************************************************************************************************************/	

INSERT INTO FasesDeProyectos 
 	 (MUNICIPIO, AO_PROYECTO, NUMERO_PROYECTO, NUMERO_FASE, AO_FASE, importe_fase,
	Codigo_Plan, referencia, subreferencia, ao_ejecucion_obra, carretera, plazo, unidadplazo, nro_ejemplares, revision, formula, formula2, formula3, formula4, 
	organismo_direccion, Servicio_direccion, director_tecnico_obra, colegiooficialdireccion,subvencionecondireccion,
  	 presu_gral_ejecucion_material, por_gastos_generales,  importe_gastos_generales, por_beneficio_industriales, 
  	 importe_beneficio_industriales, por_control_calidad,  importe_control_calidad, Por_iva, iva, por_subcontrata, 
 	 subcontrata, honorarios_dir, honorarios_red, importeplansys, HD_ExcluidoIVA, HR_ExcluidoIVA, fecha_rem_fase,   fecha_ent_fase, fecha_remision_ayto, 
	 fecha_aprobacion_ayto,  fecha_pet_rectificacion, fecha_ent_rectificacion,  fecha_pet_reforma, fecha_ent_reforma,  fecha_pit_ref, fecha_eit_ref,
	 fecha_cg_ref,  fecha_ci_ref,  fecha_com_gob,   fecha_dto,  nro_dto,  fecha_com_inf,estado_fase, Requiere_PlanSyS)
VALUES 
	 (@V_CODIGO_MUNICIPIO,@V_AO_PROYECTO, 
  	 @V_NUMERO_PROYECTO, 1, @V_AO_PROYECTO,@V_importe_fase, @V_PLAN,@V_NUMOBRA,@V_SUBREF,@V_AOPLAN,
    	 @V_carretera, @V_plazo, @V_unidad_plazo, @V_nro_ejemplares, @V_revision, @V_formula, @V_formula2, @V_formula3, @V_formula4, 
	@V_organismo_redactor, @V_SERVRED,@V_autor,@v_colofir,@v_subvencion_economicad,
    	@V_presu_gral_ejecucion_material_fase, @V_por_gastos_generales_fase, @V_importe_gastos_generales_fase, @V_por_beneficio_industriales_fase, 
	@V_importe_beneficio_industriales_fase, @V_por_control_calidad_fase, @V_importe_control_calidad_fase, @V_por_iva_fase, @V_iva_fase, @V_por_subcontrata_fase, 
    	@V_subcontrata_fase, @V_honorarios_dir_fase, @V_honorarios_red_fase, @V_plan_ss_fase, @V_IVA_Honor_Direcc_fase, @V_IVA_Honor_Redacc_fase,
	@V_fecha_entrega_proyecto, @V_fecha_recepcion_proyecto, @V_fecha_remision_ayto, @V_fecha_aprobacion_ayto, @V_fecha_pet_rectificacion, @V_fecha_ent_rectificacion, 
    	@V_fecha_pet_reforma, @V_fecha_ent_reforma,  @V_fecha_pit_ref, @V_fecha_eit_ref, @V_fecha_cg_ref, @V_fecha_ci_ref, @V_fecha_c_gob,
	@V_fecha_dto, @V_nro_dto, @V_fecha_c_infor , 'TFA', @V_Requiere_PlanSyS)
	
 
set @v_error = @@error


if @v_error <> 0
BEGIN
	ROLLBACK
	RETURN 3
END

/*********************************************************************************************************************************************/	
					/*ACTUALIZA o BORRA LA  AYUDATECNICA*/
/*********************************************************************************************************************************************/	
if @v_subvencion_economicaR = 1 
begin
	set @SUBVENCION = 'SI'
end
else
begin
	SET @SUBVENCION = 'NO'
end
if @v_ayuda = 'SI'
begin
UPDATE Ayuda_Tecnica
	SET Pasado = 1,
	        SubvencionEconomicaR = @subvencion	
		
		
	WHERE	codigo_plan = @V_PLAN
	and 		numero_obra = @V_NUMOBRA
	and		subreferencia = @V_SUBREF
	and		ao_ejecucion = @V_AOPLAN
end
else
begin
	delete from ayuda_tecnica
	WHERE	codigo_plan = @V_PLAN
	and 		numero_obra = @V_NUMOBRA
	and		subreferencia = @V_SUBREF
	and		ao_ejecucion = @V_AOPLAN
end
	
set @v_error = @@error


if @v_error <> 0
BEGIN
	ROLLBACK
	RETURN 4
END

/*********************************************************************************************************************************************/	
				/*ACTUALIZA EL CAMPO codigo_estado_obra DE DATOSINICIODEOBRAS*/
/*********************************************************************************************************************************************/	
UPDATE DatosInicioDeObras
	SET Codigo_estado_obra = 'TPR',
	        PETICION_AYUDA_TEC= @V_AYUDA	
	       
	WHERE	codigo_plan = @V_PLAN
	and 		numero_obra = @V_NUMOBRA
	and		subreferencia = @V_SUBREF
	and		ao_ejecucion = @V_AOPLAN
	
set @v_error = @@error

if @v_error <> 0
BEGIN
	ROLLBACK
	RETURN 5
END

/*********************************************************************************************************************************************/
				/* ACTUALIZA el Importe a contratar en la tabla importes*/
/*********************************************************************************************************************************************/
UPDATE ImportesDeObras
	SET importe_a_contratar = @V_importe_Proyecto
	WHERE	codigo_plan = @V_PLAN
	and 		numero_obra = @V_NUMOBRA
	and		subreferencia = @V_SUBREF
	and		ao_ejecucion = @V_AOPLAN
set @v_error = @@error

if @v_error <> 0
BEGIN
	ROLLBACK
	RETURN 6
END

/*********************************************************************************************************************************************/	
				/*FINALIZA CORRECTAMENTE EL ALTA Y TERMINA LA TRANSACCION*/
/*********************************************************************************************************************************************/	

COMMIT
RETURN 0
GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Modificando Procedimiento [aida].[PA_PROYECTOS_M_FinalizaTramiteProyecto]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
ALTER PROCEDURE [aida].[PA_PROYECTOS_M_FinalizaTramiteProyecto] 
/*
ESTE PROCEDIMIENTO FINALIZA LA TRAMITACION DE LA FASE DE PROYECTO Y REMITE A CONTRATACION SI ES DIP, 
EN TODO CASO MODIFICA EL ESTADO Y LO DEJA PA
	20	TERMINACION CORRECTA
	21 	NO EXISTE EL PROYECTO SOBRE EL QUE SE QUIERE DAR MODIFICAR
	22 	NO EXISTE LA FASE A MODIFICAR
	23	ERROR EN LA ACTUALIZACION DEL REGISTRO DE FASE
	24	ERROR AL ACTUALIZAR ESTADO DEL PROYECTO
	25	ERROR AL ACTUALIZAR ESTADO OBRA
	26	ERROR AL ACTUALIZAR AYUDA_TECNICA
	27	ERROR EN LA ACTUALIZACION DE PENDIENTE CONTRATACION
	28	ERROR
	29	ERROR

*/

/* VARIABLES PARA LA FASE
	CLAVE*/
@V_MUNICIPIO as smallint,
@V_AO_PROYECTO as smallint, 
@V_AO_FASE AS SMALLINT,
@V_NUMERO_PROYECTO AS SMALLINT,
@V_NUMERO_FASE AS SMALLINT ,
	/* FECHAS*/

@V_FECHA_REM_FASE as smalldatetime = null,
@V_fecha_ENT_FASE as smalldatetime = null,
@V_fecha_REMISION_ayto as smalldatetime = null,
@V_fecha_aprobacion_ayto as smalldatetime = null,
@V_fecha_REMISION_JUNTA as smalldatetime = null,
@V_fecha_VISADO_JUNTA as smalldatetime = null,
@V_fecha_PET_INF_TECNICO_CONTRATA as smalldatetime = null,
@V_fecha_ENT_INF_TECNICO_CONTRATA as smalldatetime = null,
@V_PLIEGO_CLAUSULAS_PARTICULARES as smalldatetime = null,
@V_fecha_PET_DESGLOSE as smalldatetime = null,
@V_fecha_ENT_DESGLOSE as smalldatetime = null,
@V_fecha_pet_rectificacion as smalldatetime = null,
@V_fecha_ent_rectificacion as smalldatetime = null,
@V_fecha_pet_reforma as smalldatetime = null,
@V_fecha_ent_reforma as smalldatetime = null,
@V_fecha_pit_ref as smalldatetime = null,
@V_fecha_eit_ref as smalldatetime = null,
@V_fecha_ci_ref as smalldatetime = null,
@V_fecha_cg_ref as smalldatetime = null,
@V_FECHA_PET_ACTUAL_PRECIOS AS smalldatetime = null, 
@V_FECHA_ENT_ACTUAL_PRECIOS AS smalldatetime = null,
@V_FECHA_ENVIO_FISCALIZACION AS smalldatetime = null,
@V_fecha_COM_INF as smalldatetime = null,
@V_fecha_COM_GOB as smalldatetime = null,
@V_FISCALIZACION AS smalldatetime = null,
@V_FECHA_REMISION_CONTRATACION AS smalldatetime = null,
@V_fecha_dto as smalldatetime = null, 
	/* IMPORTES*/

@V_IMPORTE_FASE as float,
@V_presu_gral_ejecucion_material as float,
@V_por_gastos_generales as float,
@V_importe_gastos_generales as float,
@V_por_beneficio_industriales as float,
@V_importe_beneficio_industriales as float,
@V_por_control_calidad as float,
@V_importe_control_calidad as float,
@v_por_iva as float,
@V_iva as float,
@V_por_subcontrata as float,
@V_subcontrata as float,
@V_honorarios_dir as float,
@V_honorarios_red as float,

/*RESTO DATOS*/
@V_servicio_gestor  AS SMALLINT,
@V_CODIGO_PLAN AS CHAR(7) ,
@V_REFERENCIA AS SMALLINT,
@V_SUBREFERENCIA AS TINYINT,
@V_AO_EJECUCION_OBRA AS SMALLINT ,
@V_CARRETERA AS CHAR(5) ,
@V_PLAZO AS smallint ,
@V_NRO_EJEMPLARES AS SMALLINT  ,
@V_REVISION AS char(2) ,
@V_FORMULA AS TINYINT ,
@V_FORMULA2 AS TINYINT,
@V_FORMULA3 AS TINYINT,
@V_FORMULA4 AS TINYINT,
@V_ORGANISMO_DIRECCION AS CHAR(2),

@V_SERVICIO_DIRECCION AS varchar(150)  ,
@V_DIRECTOR_TECNICO_OBRA AS VARCHAR(150),
@V_COLEGIOOFICIALDIRECCION  AS VARCHAR(80),
@V_SUBVENCIONECONDIRECCION AS BIT,
@V_nro_dto as smallint ,
@V_CLASE_EXP AS CHAR(2) ,
@V_TIPO_PROC AS CHAR(2) ,
@V_FORMA_CONT AS CHAR(2) ,
@V_Requiere_PlanSyS as bit,
@V_EnvioContratacion as integer output

AS

DECLARE @V_EXISTE            AS INTEGER
DECLARE @V_EXISTE_FASE AS INTEGER
DECLARE @V_EXISTE_PTE   AS INTEGER
declare @v_existe_pteactivo as integer
declare @v_numorden as integer
DECLARE @V_ERROR            AS INTEGER
DECLARE @V_ESTADO_PROY1 AS CHAR(3)
DECLARE @V_ESTADO_OBRA1 AS CHAR(3)
DECLARE @V_ESTADO_FASE1 AS CHAR(3)
DECLARE @V_FORMAEJEC       AS CHAR(3) 
DECLARE @V_SED AS CHAR(2)
DECLARE @V_SER AS bit
DECLARE @V_SER2 AS CHAR(2)
DECLARE @V_ORGANISMO_REDACTOR AS CHAR(2)
DECLARE @V_SERVICIO_REDACTOR AS SMALLINT
declare @v_servicio_dir as smallint
declare @v_colofid as char(2)
DECLARE @V_AUTOR AS CHAR(60)
declare @v_PETAYUDA_NUEVA AS CHAR(2)
DECLARE @V_PETAYUDA_ANTES AS CHAR(2)
/******************************************************************************************************************************************** */	
				/*COMPRUEBA SI EXISTE EL PROYECTO Y LA  FASE Y PTE CONTRATACION*/
/******************************************************************************************************************************************** */

set @v_existe_fase = 0
SET @V_EXISTE = 0
SET @V_EXISTE_PTE = 0
set @v_existe_pteactivo =0
set @v_enviocontratacion=0


/* carga el servicio director y el colegio oficial de dirección si existen*/

if rtrim(@v_servicio_direccion) <> ' ' 
begin
	select @v_servicio_dir = codigo_dpto from tablas..tabladedepartamentos where denominacion = @v_servicio_direccion
	set @v_error = @@error
	if @v_error <> 0
	begin
		RETURN 28			/* ERROR al leer el servicio director*/
	end
end
if rtrim(@v_colegiooficialdireccion) <> ' '
begin
	select @v_colofid = codigo_redactor from tablas..colegiosoficiales where organismo_redactor = @v_colegiooficialdireccion
	set @v_error = @@error
	if @v_error <> 0
	begin
		RETURN 29			/* ERROR al leer el colegio*/
	end
end
ELSE
BEGIN
	SET @V_COLOFID = NULL
END
/* COMPRUEBA EXISTA EL PROYECTO*/
select  @v_existe = count(1) from proyectos 
WHERE (CODIGO_MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto) 
if @v_existe = 0 
begin
	RETURN 21				/*NO EXISTE EL PROYECTO DEL QUE SE QUIERE MODIFICAR LA FASE*/
END


/* COMPRUEBA QUE EXISTE LA FASE*/
select @v_existe_fase = count(1) from fasesdeproyectos
WHERE (MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto) AND 
(AO_FASE= @V_AO_FASE) AND
(NUMERO_FASE = @V_NUMERO_FASE)
IF @V_EXISTE_FASE= 0 
begin
	RETURN 22				/*NO EXISTE LA FASE QUE SE QUIERE MODIFICAR*/
end



/*COMPRUEBA SI EXISTE pendienteCONTRATACION  */
select @v_existe_PTE = count(1) ,@v_numorden=max(peticioncont) from  CONTRATA..PENDIENTECONTRATACIONOBRAS
where  planOBRA= @V_CODIGO_PLAN and
NUMobra = @V_REFERENCIA and
subref = @V_SUBREFERENCIA and
aoOBRA = @V_AO_EJECUCION_OBRA 



/*COMPRUEBA SI EXISTE pendienteCONTRATACION y esta pendiente de apertura*/
select @v_existe_PTEactivo = count(1)  from  CONTRATA..PENDIENTECONTRATACIONOBRAS
where  planOBRA= @V_CODIGO_PLAN and
NUMobra = @V_REFERENCIA and
subref = @V_SUBREFERENCIA and
aoOBRA = @V_AO_EJECUCION_OBRA and
estado is null

/* LEE EL ESTADO DE LA OBRA EN INICIO Y FORMA DE EJECUCION Y PETICION DE AYUDA*/
select @v_estado_obra1= codigo_estado_obra, @V_FORMAEJEC = FORMA_EJECUCION,@V_PETAYUDA_ANTES=PETICION_AYUDA_TEC  from datosiniciodeobras
where  codigo_plan= @V_CODIGO_PLAN and
numero_obra = @V_REFERENCIA and
subreferencia = @V_SUBREFERENCIA and
ao_ejecucion = @V_AO_EJECUCION_OBRA

/*LEE EL ESTADO DEL PROYECTO, REDACTOR Y AUTOR*/
SELECT @V_ESTADO_PROY1 = ESTADO_PROYECTO , @V_ORGANISMO_REDACTOR= ORGANISMO_REDACTOR, @V_SERVICIO_REDACTOR =SERVICIO_REDACTOR, @V_AUTOR=AUTOR,@v_ser=subvencioneconredaccion FROM PROYECTOS
WHERE (CODIGO_MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto)

/* LEE EL ESTADO DE LA FASE*/
SELECT @V_ESTADO_FASE1 =ESTADO_FASE FROM FASESDEPROYECTOS
WHERE (MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto) AND 
(AO_FASE= @V_AO_FASE) AND
(NUMERO_FASE = @V_NUMERO_FASE)

/* ******************************************************************************************************************************************* */	
/*  COMPROBACIONES ANTERIORES A INICIAR LA TRANSACCION (PARA MODIFICACIONES)                  	   */
/* ******************************************************************************************************************************************* */

/* ACTUALIZACION DE LOS ESTADOS*/
IF (@V_ESTADO_OBRA1 = 'TPR' OR @V_ESTADO_OBRA1 = 'PA') AND (@V_FORMAEJEC = 'DIP')  AND @V_FECHA_REMISION_CONTRATACION <> '  '
BEGIN

	SET @V_ESTADO_FASE1='TE'
	SET @V_ESTADO_PROY1='TE'
	SET @V_ESTADO_OBRA1='PA'
END
IF (@V_ESTADO_OBRA1 = 'TPR' OR @V_ESTADO_OBRA1 = 'PA') AND (@V_FORMAEJEC = 'DIP') AND @V_FECHA_REMISION_CONTRATACION is null
BEGIN
	
	SET @V_ESTADO_FASE1= 'TFA'
	SET @V_ESTADO_PROY1='TPR'
	SET @V_ESTADO_OBRA1='TPR'
END
IF (@V_ESTADO_OBRA1 = 'TPR' OR @V_ESTADO_OBRA1 = 'PA') AND @V_FORMAEJEC <>  'DIP'  AND (@V_FECHA_COM_GOB <> '  ' OR  @V_FECHA_DTO <>'  ')
BEGIN

	SET @V_ESTADO_FASE1= 'TE'
	SET @V_ESTADO_PROY1='TE'
	SET @V_ESTADO_OBRA1='PA'
END	
IF (@V_ESTADO_OBRA1 = 'TPR' OR @V_ESTADO_OBRA1 = 'PA') AND @V_FORMAEJEC <>  'DIP'  AND (@V_FECHA_COM_GOB = NULL AND   @V_FECHA_DTO = NULL)
BEGIN

	SET @V_ESTADO_FASE1= 'TFA'
	SET @V_ESTADO_PROY1='TPR'
	SET @V_ESTADO_OBRA1='TPR'
END	
/* ******************************************************************************************************************************************* */	
/*   COMPRUEBA EL NUEVO VALOR DE PETICION DE AYUDA TECNICA */
/* ******************************************************************************************************************************************* */	
set @v_petayuda_nueva = 'NO'
IF @V_ORGANISMO_REDACTOR = 'DP' OR @V_ORGANISMO_DIRECCION='DP' OR @V_SUBVENCIONECONDIRECCION <> 0  or @v_ser <> 0 
begin
	set @v_petayuda_nueva='SI'
end

 /* ******************************************************************************************************************************************* */	
/*  EMPIEZA LA ACTUALIZACION                 	   */
/* ******************************************************************************************************************************************* */

/* ACTUALIZO EL REGISTRO DE FASE*/
BEGIN TRANSACTION
UPDATE FASESDEPROYECTOS
SET
Servicio_gestor=@V_servicio_gestor,  
CODIGO_PLAN=@V_CODIGO_PLAN,
REFERENCIA =@V_REFERENCIA,
SUBREFERENCIA  =@V_SUBREFERENCIA,
AO_EJECUCION_OBRA =@V_AO_EJECUCION_OBRA,
CARRETERA =@V_CARRETERA,
PLAZO = @V_PLAZO,
NRO_EJEMPLARES = @V_NRO_EJEMPLARES,

REVISION  = @V_REVISION,
FORMULA = @V_FORMULA,
FORMULA2 = @V_FORMULA2,
FORMULA3 = @V_FORMULA3,
FORMULA4 = @V_FORMULA4,
ORGANISMO_DIRECCION = @V_ORGANISMO_DIRECCION,
SERVICIO_DIRECCION  = @V_SERVICIO_DIR,
DIRECTOR_TECNICO_OBRA  = @V_DIRECTOR_TECNICO_OBRA,
COLEGIOOFICIALDIRECCION   = @V_COLOFID,
SUBVENCIONECONDIRECCION = @V_SUBVENCIONECONDIRECCION,
nro_dto  = @V_nro_dto,
estado_FASE  = @V_ESTADO_FASE1,
CLASE_EXP = @V_CLASE_EXP,
TIPO_PROC   = @V_TIPO_PROC,
FORMA_CONT    = @V_FORMA_CONT,
Requiere_PlanSyS     = @V_Requiere_PlanSyS,

/*IMPORTES*/

IMPORTE_FASE  = @V_IMPORTE_FASE ,
presu_gral_ejecucion_material  = @V_presu_gral_ejecucion_material,
por_gastos_generales  = @V_por_gastos_generales,
importe_gastos_generales  = @V_importe_gastos_generales,
por_beneficio_industriales  = @V_por_beneficio_industriales,
importe_beneficio_industriales  = @V_importe_beneficio_industriales,
por_control_calidad  = @V_por_control_calidad,
importe_control_calidad  = @V_importe_control_calidad,
por_iva =@V_por_iva,
Iva  =@V_iva,
por_subcontrata  = @V_por_subcontrata,
subcontrata  = @V_subcontrata,
honorarios_dir  = @V_honorarios_dir,
honorarios_red  = @V_honorarios_red,

/* FECHAS*/

FECHA_REM_FASE  = @V_FECHA_REM_FASE,
fecha_ENT_FASE = @V_fecha_ENT_FASE,
fecha_REMISION_ayto  =  @V_fecha_REMISION_ayto,
fecha_aprobacion_ayto  =  @V_fecha_aprobacion_ayto,
fecha_REMISION_JUNTA  =  @V_fecha_REMISION_JUNTA,
FECHA_VISADO_JUNTA =   @V_FECHA_VISADO_JUNTA,
fecha_PET_INF_TECNICO_CONTRATA  =   @V_fecha_PET_INF_TECNICO_CONTRATA,
fecha_ENT_INF_TECNICO_CONTRATA   =   @V_fecha_ENT_INF_TECNICO_CONTRATA,
PLIEGO_CLAUSULAS_PARTICULARES   =   @V_PLIEGO_CLAUSULAS_PARTICULARES,
fecha_PET_DESGLOSE  =   @V_fecha_PET_DESGLOSE,
fecha_ENT_DESGLOSE   =   @V_fecha_ENT_DESGLOSE,
fecha_pet_rectificacion   =  @V_fecha_pet_rectificacion,
fecha_ent_rectificacion   =   @V_fecha_ent_rectificacion,
fecha_pet_reforma   =   @V_fecha_pet_reforma,
fecha_ent_reforma  =    @V_fecha_ent_reforma,
fecha_pit_ref   =   @V_fecha_pit_ref,
fecha_eit_ref   =   @V_fecha_eit_ref,
fecha_ci_ref    =   @V_fecha_ci_ref,
fecha_cg_ref   =   @V_fecha_cg_ref,
FECHA_PET_ACTUAL_PRECIOS   =  @V_FECHA_PET_ACTUAL_PRECIOS,
FECHA_ENT_ACTUAL_PRECIOS   =   @V_FECHA_ENT_ACTUAL_PRECIOS,
FECHA_ENVIO_FISCALIZACION  = @V_FECHA_ENVIO_FISCALIZACION,
fecha_COM_INF   = @V_fecha_COM_INF,
fecha_COM_GOB  =  @V_fecha_COM_GOB,
fecha_FISCALIZACION  =   @V_FISCALIZACION,
FECHA_REMISION_CONTRATACION   =  @V_FECHA_REMISION_CONTRATACION,
fecha_dto   =   @V_fecha_dto

WHERE (MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto) AND 
(AO_FASE= @V_AO_FASE) AND
(NUMERO_FASE = @V_NUMERO_FASE)
set @v_error = @@error
if @v_error <> 0
begin
	ROLLBACK
	RETURN 23			/* ERROR EN LA ACTUALIZACION DEL REGISTRO DE FASE*/
end

/* ACTUALIZO EL ESTADO DE PROYECTO*/
UPDATE PROYECTOS
SET
ESTADO_PROYECTO=@V_ESTADO_PROY1
WHERE (codigo_MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto) 
set @v_error = @@error
if @v_error <> 0
begin 
	ROLLBACK	
	return 24		/*ERROR AL ACTUALIZAR ESTADO DEL PROYECTO*/			
end

/* ACTUALIZA ESTADO OBRA y peticion ayuda tecnica*/

UPDATE DATOSINICIODEOBRAS
SET
CODIGO_ESTADO_obra=@V_ESTADO_OBRA1,
peticion_ayuda_tec=@v_petayuda_nueva
where  codigo_plan= @V_CODIGO_PLAN and
numero_obra = @V_REFERENCIA and
subreferencia = @V_SUBREFERENCIA and
ao_ejecucion = @V_AO_EJECUCION_OBRA
set @v_error = @@error
if @v_error <> 0
begin 
	ROLLBACK	
	return 25		/*ERROR AL ACTUALIZAR ESTADO OBRA*/			
end

/*ACTUALIZO LA AYUDA TECNICA POR SI HUBO CAMBIOS*/
IF @V_SUBVENCIONECONDIRECCION = 0 
BEGIN
	SET @V_SED='NO'
END
ELSE
BEGIN
	SET @V_SED='SI'
END
IF @V_SER = 0 
BEGIN
	SET @V_SER2='NO'
END
ELSE
BEGIN
	SET @V_SER2='SI'
END
if @v_petayuda_nueva='SI' and @v_petayuda_antes= 'SI'
begin 
	UPDATE AYUDA_TECNICA
	SET
	DEPARTAMENTO_DIRECCION= @V_SERVICIO_DIR,
	DPTO_REDACTOR=@V_SERVICIO_REDACTOR,
	SUBVENCIONECONOMICAR = @V_SER2,
	SUBVENCIONECONOMICAD = @V_SED,
	PASADO=1
	where  codigo_plan= @V_CODIGO_PLAN and
	numero_obra = @V_REFERENCIA and
	subreferencia = @V_SUBREFERENCIA and
	ao_ejecucion = @V_AO_EJECUCION_OBRA
	set @v_error = @@error
	if @v_error <> 0	begin 
		ROLLBACK	
		return 26		/*ERROR AL ACTUALIZAR AYUDA_TECNICA*/			
	end
end
if @v_petayuda_nueva = 'NO' and @v_petayuda_antes = 'SI'
begin
	delete from ayuda_tecnica
	where  codigo_plan= @V_CODIGO_PLAN and
	numero_obra = @V_REFERENCIA and
	subreferencia = @V_SUBREFERENCIA and
	ao_ejecucion = @V_AO_EJECUCION_OBRA
	set @v_error = @@error
	if @v_error <> 0	begin 
		ROLLBACK	
		return 26		/*ERROR AL ACTUALIZAR AYUDA_TECNICA*/			
	end
end
if @v_petayuda_nueva = 'SI' and @v_petayuda_antes = 'NO'
begin
	INSERT INTO ayuda_tecnica
		   (CODIGO_PLAN,NUMERO_OBRA,SUBREFERENCIA,AO_EJECUCION,	departamento, codigo_municipio,ao_proyecto,numero_proyecto,DPTO_REDACTOR,DEPARTAMENTO_DIRECCION,PASADO,SUBVENCIONECONOMICAR,SUBVENCIONECONOMICAD)
	VALUES (@V_CODIGO_PLAN, @V_REFERENCIA,@V_SUBREFERENCIA,@V_AO_EJECUCION_OBRA,0,0,0,0,@V_SERVICIO_REDACTOR,@V_SERVICIO_DIR,1,@V_SER2,@V_SED)
	set @v_error = @@error
	if @v_error <> 0	begin 
		ROLLBACK	
		return 26		/*ERROR AL ACTUALIZAR AYUDA_TECNICA*/			
	end
end
/* GRABA PENDIENTE CONTRATACION O ACTUALIZA REGISTRO EXISTENTE*/
set @v_enviocontratacion=0
if @V_FORMAEJEC <> 'DIP' 
begin
	commit
	return 20
end
if @v_estado_obra1 = 'TPR' and @V_EXISTE_PTEACTIVO > 0
BEGIN
	DELETE CONTRATA..PENDIENTECONTRATACIONOBRAS
	WHERE
	planOBRA= @V_CODIGO_PLAN and
	numobra = @V_REFERENCIA and
	subref = @V_SUBREFERENCIA and
	AOOBRA = @V_AO_EJECUCION_OBRA AND
	TIPOEXP='OB' and 
	peticioncont=@v_numorden
	set @v_enviocontratacion=3
	COMMIT
	RETURN 20
END

if @v_estado_obra1 <> 'PA'
begin
	commit
	return 20
end
IF @V_EXISTE_PTEactivo  > 0 
BEGIN
	UPDATE CONTRATA..PENDIENTECONTRATACIONOBRAS
	SET
	ampliacion=0,
	servicio_redactor=@v_servicio_redactor,
	autor=@v_autor,
	servicio_gestor=@v_servicio_gestor,
	servicio_direccion=@v_servicio_dir,
	codclaseexp= @V_CLASE_EXP,
	codprocedimiento = @V_TIPO_PROC    ,
	CodFormaContrata    = @V_FORMA_CONT,
	importelicitacion=@v_importe_fase,
	fecharecepcion=getdate (),
	esTADO=NULL
	WHERE
	planOBRA= @V_CODIGO_PLAN and
	numobra = @V_REFERENCIA and
	subref = @V_SUBREFERENCIA and
	AOOBRA = @V_AO_EJECUCION_OBRA AND
	TIPOEXP='OB' and 
	peticioncont=@v_numorden
	set @v_enviocontratacion=1
end
if @v_existe_pteactivo = 0 
begin
	IF @V_EXISTE_PTE = 0 
	BEGIN
		SET @V_NUMORDEN=0
	END
	set @v_numorden=@v_numorden +1
	INSERT INTO contrata..pendientecontratacionobras
 		 (tipoexp,peticioncont,planobra,numobra,subref,aoobra,ampliacion,servicio_redactor,servicio_gestor,servicio_direccion,
		autor,importelicitacion,codclaseexp,codprocedimiento,codformacontrata,fecharecepcion,estado)	
	VALUES ('OB',@v_numorden,@V_CODIGO_PLAN, @V_REFERENCIA , @V_SUBREFERENCIA, @V_AO_EJECUCION_OBRA ,0,@V_SERVICIO_REDACTOR,
	@V_SERVICIO_GESTOR,@V_SERVICIO_DIR,@V_AUTOR,@V_IMPORTE_FASE,@V_CLASE_EXP, @V_TIPO_PROC   ,
	@V_FORMA_CONT,getdate (),NULL)
	set @v_enviocontratacion=2
END
set @v_error = @@error
if @v_error <> 0
begin	ROLLBACK	
	RETURN 27			/*ERROR EN PENDIENTE DE CONTRATACION*/
end


COMMIT
RETURN 20			/*PROCESO REALIZADO CORRECTAMENTE*/
GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Modificando Procedimiento [diana].[PA_PROYECTOS_A_NuevaFaseNuevo]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
ALTER PROCEDURE  [diana].[PA_PROYECTOS_A_NuevaFaseNuevo] 

/*  VARIABLES ADICIONALES PARA CREAR  LA FASE NUEVA DEL PROYECTO*/
@V_CODIGO_MUNICIPIO as smallint,
@V_AO_PROYECTO as smallint, 
@V_NUM_PROYECTO as smallint,
@V_SERVICIO_GESTOR AS SMALLINT,
@V_importe_fase as float, 
@V_plan as char(7),
@v_numobra as smallint,
@v_subref as int,
@v_aoplan as smallint,
@V_plazo as int,
@V_Unidad_Plazo as char(1) = 'm' ,
@V_nro_ejemplares as smallint, 
@V_revision as char(2),
@V_formula as int,
@V_formula2 as int,
@V_formula3 as int,
@V_formula4 as int, 
@V_COLEGIOOFICIAL AS varchar(150), 	-- POR DEFECTO ASUME LO QUE TENGA EL PROYECTO
@V_autor as char(50),
@V_plan_ss as float,
@V_IVA_Honor_Direcc as bit,
@V_presu_gral_ejecucion_material_fase as float, 
@V_por_gastos_generales_fase as float,  
@V_importe_gastos_generales_fase as float, 
@V_por_beneficio_industriales_fase as float, 
@V_importe_beneficio_industriales_fase as float, 
@V_por_control_calidad_fase as float,  
@V_importe_control_calidad_fase as float,  
@V_por_iva_fase as float, 
@V_iva_fase as float, 
@V_por_subcontrata_fase as float, 
@V_subcontrata_fase as float, 
@V_honorarios_dir_fase as float, 
@V_honorarios_red_fase as float,  
@V_plan_ss_fase as float,
@V_IVA_Honor_Direcc_fase as bit,
@V_IVA_Honor_Redacc_fase as bit,
@V_fecha_entrega_proyecto as smalldatetime = NULL,
@V_fecha_recepcion_proyecto as smalldatetime = NULL,
@V_fecha_remision_ayto as smalldatetime = NULL,
@V_fecha_aprobacion_ayto as smalldatetime = NULL, 
@V_fecha_pet_rectificacion as smalldatetime = NULL,
@V_fecha_ent_rectificacion as smalldatetime = NULL,
@V_fecha_pet_reforma as smalldatetime = NULL,
@V_fecha_ent_reforma as smalldatetime = NULL,
@V_fecha_c_infor as smalldatetime = NULL,
@V_fecha_c_gob as smalldatetime = NULL,
@V_fecha_pit_ref as smalldatetime = NULL,
@V_fecha_eit_ref as smalldatetime = NULL,
@V_fecha_ci_ref as smalldatetime = NULL, 
@V_fecha_cg_ref as smalldatetime = NULL,
@V_fecha_dto as smalldatetime = NULL,
@V_nro_dto as float,
@V_Requiere_PlanSyS as bit,
@V_Requiere_TratMed as bit,

@V_NUMERO_FASE AS SMALLINT OUTPUT
 AS
/*	Graba un registro de FasesProyectos asociado a la obra.
	Actualiza AyudaTecnica para grabar el estado_proyecto a true (existe proyecto asociado).
	Actualiza el Estado_Obra en DatosInicioObra  a TPR (Trámite proyecto)
	Valores devueltos:
		0 -- Funcionamento correcto
		1 -- Error al leer la obra o no existe
		2 -- Estado de la obra incorrecto	
		3 -- Fallo del Insert en FasesProyectos
		4 -- Fallo del Update de AyudaTecnica
		5 -- Fallo del Update de DatosInicioObras
		6 -- No existe el proyecto asociado a la obra
		8 -- Error al cargar el   colegio oficial
*/

declare @v_error as int
declare @v_existe as int
declare @subvencion as bit
declare @v_servred as smallint
declare @v_colofiD as char(2)
DECLARE @v_ayuda as char(2)
declare @V_carretera as char(6)
declare @V_SERVICIO_DIRECCION AS SMALLINT
declare @V_SUBVENCION_ECONOMICAD AS char(2) 
declare @v_pasado as bit
declare @v_estado_obra as char(3)
declare @V_organismo_direccion as char(2)
DECLARE @V_ESTADO_OBRA1 AS CHAR(3)
DECLARE @V_ESTADO_FASE1 AS CHAR(3)
DECLARE @V_FORMAEJEC       AS CHAR(3) 

/* *************************************************************************************************************************
  LEE datos de la obra y comprueba que sean correctos
****************************************************************************************************************************/
SELECT 
   @v_carretera =    dbo.DatosInicioDeObras.carretera, 
   @v_ayuda = dbo.DatosInicioDeObras.peticion_ayuda_tec, 
   @v_servicio_direccion= dbo.Ayuda_Tecnica.departamento_direccion, 
   @v_estado_obra= codigo_estado_obra,	
   @v_pasado=dbo.Ayuda_Tecnica.pasado, 
   @V_SUBVENCION_ECONOMICAD=dbo.Ayuda_Tecnica.SubvencionEconomicaD
FROM dbo.DatosInicioDeObras LEFT OUTER JOIN
    dbo.Ayuda_Tecnica ON 
    dbo.DatosInicioDeObras.Codigo_Plan = dbo.Ayuda_Tecnica.Codigo_Plan
     AND 
    dbo.DatosInicioDeObras.numero_obra = dbo.Ayuda_Tecnica.numero_obra
     AND 
    dbo.DatosInicioDeObras.subreferencia = dbo.Ayuda_Tecnica.subreferencia
     AND 
    dbo.DatosInicioDeObras.ao_ejecucion = dbo.Ayuda_Tecnica.ao_ejecucion
WHERE	dbo.DatosInicioDeObras.codigo_plan = @V_PLAN
and 		dbo.DatosInicioDeObras.numero_obra = @V_NUMOBRA
and		dbo.DatosInicioDeObras.subreferencia = @V_SUBREF
and		dbo.DatosInicioDeObras.ao_ejecucion = @V_AOPLAN

if   @@rowcount = 0 
begin
	return 1
end

-- comprueba que esté pendiente de alta de proyecto 
if @v_estado_obra <> 'PPY'
begin
	return 2
end


/* *************************************************************************************************************************
  LEE PROYECTO PARA CONFIRMAR 	QUE EXISTE
****************************************************************************************************************************/
SELECT * FROM PROYECTOS
WHERE CODIGO_MUNICIPIO=@V_CODIGO_MUNICIPIO
AND NUMERO_PROYECTO = @V_NUM_PROYECTO 
AND AO_PROYECTO = @V_AO_PROYECTO
IF @@ROWCOUNT = 0
BEGIN
	RETURN 6
END
/* *************************************************************************************************************************
 procesa datos de la ayuda técnica 
****************************************************************************************************************************/
-- si tiene peticion de ayuda asume que dirige diputación sino ayuntamiento
if @v_ayuda = 'SI' 
begin
	set @v_organismo_direccion = 'DP'
end
ELSE
BEGIN
	SET @v_organismo_direccion = 'AY'
END
--  si tiene colegio oficial  lee el codigo
if rtrim(@v_colegiooficial) <> ' '
begin
	select @v_colofiD = codigo_redactor from tablas..colegiosoficiales where organismo_redactor = @v_colegiooficial
	set @v_error = @@error
	if @v_error <> 0
	begin
		RETURN 8					/* ERROR al leer el colegio*/ 
	END
	set @v_organismo_direccion = 'DP'
END
ELSE
BEGIN
	SET @V_COLOFID = NULL
END
-- si tiene ayuda economica de direccion
if @v_subvencion_economicad = 'SI'
begin
	set @SUBVENCION = 1
end
else
begin
	SET @SUBVENCION = 0
end

/*********************************************************************************************************************************************/	
				/*COMPRUEBA QUE NO EXISTA LA FASE*/
/*********************************************************************************************************************************************/

SELECT @v_existe = count (codigo_plan) FROM FasesDeProyectos WHERE
Codigo_Plan = @V_PLAN AND referencia = @V_NUMOBRA AND subreferencia = @V_SUBREF AND  ao_ejecucion_obra = @V_AOPLAN
IF @v_existe > 0 
BEGIN  return 6
end



/*********************************************************************************************************************************************/	
				/*CALCULA EL NUEVO NUMERO DE LA FASE DENTRO DEL PROYECTO*/
/*********************************************************************************************************************************************/
SELECT @v_numero_FASE = MAX(NUMERO_FASE) FROM FasesDeProyectos
GROUP BY MUNICIPIO,     AO_PROYECTO,NUMERO_PROYECTO
HAVING (MUNICIPIO = @v_codigo_municipio)
  AND (AO_PROYECTO = @v_ao_proyecto)
  AND  (NUMERO_PROYECTO = @v_NUM_proyecto)
if @v_numero_FASE is null 
begin
 set @v_numero_FASE=0
end
set @v_numero_FASE = @v_numero_FASE + 1
set @v_error = @@error
if @v_error <> 0
begin
	return 1
end

/* LEE EL ESTADO DE LA OBRA EN INICIO Y FORMA DE EJECUCION Y PETICION DE AYUDA*/
select @v_estado_obra1= codigo_estado_obra, @V_FORMAEJEC = FORMA_EJECUCION
from datosiniciodeobras
where  codigo_plan= @V_PLAN and
numero_obra = @V_numobra and
subreferencia = @V_SUBREF and
ao_ejecucion = @V_aoplan


set @V_ESTADO_FASE1 = 'TFA'

/* ******************************************************************************************************************************************* */	
/*  COMPROBACIONES ANTERIORES A INICIAR LA TRANSACCION                 	   */
/* ******************************************************************************************************************************************* */

/* ACTUALIZACION DE LOS ESTADOS*/
IF (@V_ESTADO_OBRA1 = 'DES' ) AND (@V_FORMAEJEC = 'DIP')
BEGIN

	SET @V_ESTADO_FASE1='TE'
	SET @V_ESTADO_OBRA1='PA'
END
IF (@V_ESTADO_OBRA1 = 'TPR' OR @V_ESTADO_OBRA1 = 'PA') AND (@V_FORMAEJEC = 'DIP')
BEGIN

	SET @V_ESTADO_FASE1='TE'
	SET @V_ESTADO_OBRA1='PA'
END
IF (@V_ESTADO_OBRA1 = 'TPR' OR @V_ESTADO_OBRA1 = 'PA') AND (@V_FORMAEJEC = 'DIP')
BEGIN
	
	SET @V_ESTADO_FASE1= 'TFA'
	SET @V_ESTADO_OBRA1='TPR'
END
IF (@V_ESTADO_OBRA1 = 'TPR' OR @V_ESTADO_OBRA1 = 'PA') AND @V_FORMAEJEC <>  'DIP'  AND (@V_FECHA_C_GOB <> '  ' OR  @V_FECHA_DTO <>'  ')
BEGIN

	SET @V_ESTADO_FASE1= 'TE'
	SET @V_ESTADO_OBRA1='PA'
END	
IF (@V_ESTADO_OBRA1 = 'TPR' OR @V_ESTADO_OBRA1 = 'PA') AND @V_FORMAEJEC <>  'DIP'  AND (@V_FECHA_C_GOB = NULL AND   @V_FECHA_DTO = NULL)
BEGIN

	SET @V_ESTADO_FASE1= 'TFA'
	SET @V_ESTADO_OBRA1='TPR'
END	



BEGIN TRANSACTION

INSERT INTO FasesDeProyectos 
 	 (MUNICIPIO, AO_PROYECTO, NUMERO_PROYECTO, NUMERO_FASE, AO_FASE, importe_fase,
	Codigo_Plan, referencia, subreferencia, ao_ejecucion_obra, carretera, plazo, unidadplazo, nro_ejemplares, revision, formula, formula2, formula3, formula4, 
	organismo_direccion, Servicio_direccion, director_tecnico_obra, colegiooficialdireccion,subvencionecondireccion,
  	 presu_gral_ejecucion_material, por_gastos_generales,  importe_gastos_generales, por_beneficio_industriales, 
  	 importe_beneficio_industriales, por_control_calidad,  importe_control_calidad, Por_iva, iva, por_subcontrata, 
 	 subcontrata, honorarios_dir, honorarios_red, importeplansys, HD_ExcluidoIVA, HR_ExcluidoIVA, fecha_rem_fase,   fecha_ent_fase, fecha_remision_ayto, 
	 fecha_aprobacion_ayto,  fecha_pet_rectificacion, fecha_ent_rectificacion,  fecha_pet_reforma, fecha_ent_reforma,  fecha_pit_ref, fecha_eit_ref,
	 fecha_cg_ref,  fecha_ci_ref,  fecha_com_gob,   fecha_dto,  nro_dto,  fecha_com_inf,estado_fase, Requiere_PlanSyS)
VALUES 
	 (@V_CODIGO_MUNICIPIO,@V_AO_PROYECTO, 
  	 @V_NUM_PROYECTO, @V_NUMERO_FASE, @V_AO_PROYECTO,@V_importe_fase, @V_PLAN,@V_NUMOBRA,@V_SUBREF,@V_AOPLAN,
    	 @V_carretera, @V_plazo, @V_unidad_plazo, @V_nro_ejemplares, @V_revision, @V_formula, @V_formula2, @V_formula3, @V_formula4, 
	@V_organismo_direccion, @V_SERVicio_direccion,@V_autor,@v_colofid,@Subvencion,
    	@V_presu_gral_ejecucion_material_fase, @V_por_gastos_generales_fase, @V_importe_gastos_generales_fase, @V_por_beneficio_industriales_fase, 
	@V_importe_beneficio_industriales_fase, @V_por_control_calidad_fase, @V_importe_control_calidad_fase, @V_por_iva_fase, @V_iva_fase, @V_por_subcontrata_fase, 
    	@V_subcontrata_fase, @V_honorarios_dir_fase, @V_honorarios_red_fase, @V_plan_ss_fase, @V_IVA_Honor_Direcc_fase, @V_IVA_Honor_Redacc_fase,
	@V_fecha_entrega_proyecto, @V_fecha_recepcion_proyecto, @V_fecha_remision_ayto, @V_fecha_aprobacion_ayto, @V_fecha_pet_rectificacion, @V_fecha_ent_rectificacion, 
    	@V_fecha_pet_reforma, @V_fecha_ent_reforma,  @V_fecha_pit_ref, @V_fecha_eit_ref, @V_fecha_cg_ref, @V_fecha_ci_ref, @V_fecha_c_gob,
	@V_fecha_dto, @V_nro_dto, @V_fecha_c_infor , @V_ESTADO_FASE1, @V_Requiere_PlanSyS)
	
 
set @v_error = @@error

if @v_error <> 0
BEGIN
	ROLLBACK
	RETURN 3
END

/*********************************************************************************************************************************************/	
					/*ACTUALIZA o BORRA LA  AYUDATECNICA*/
/*********************************************************************************************************************************************/	

if @v_ayuda = 'SI'
begin
UPDATE Ayuda_Tecnica
	SET Pasado = 1,
	SubvencionEconomicaD = @subvencion	
	WHERE	codigo_plan = @V_PLAN
	and 		numero_obra = @V_NUMOBRA
	and		subreferencia = @V_SUBREF
	and		ao_ejecucion = @V_AOPLAN
end
else
begin
	delete from ayuda_tecnica
	WHERE	codigo_plan = @V_PLAN
	and 		numero_obra = @V_NUMOBRA
	and		subreferencia = @V_SUBREF
	and		ao_ejecucion = @V_AOPLAN
end
	
set @v_error = @@error
if @v_error <> 0
BEGIN
	ROLLBACK
	RETURN 4
END

/*********************************************************************************************************************************************/	
				/*ACTUALIZA EL CAMPO codigo_estado_obra DE DATOSINICIODEOBRAS*/
/*********************************************************************************************************************************************/	

UPDATE DatosInicioDeObras
	SET Codigo_estado_obra =@V_ESTADO_OBRA1,
	PETICION_AYUDA_TEC= @V_AYUDA	
	       
	WHERE	codigo_plan = @V_PLAN
	and 		numero_obra = @V_NUMOBRA
	and		subreferencia = @V_SUBREF
	and		ao_ejecucion = @V_AOPLAN
	
set @v_error = @@error

if @v_error <> 0
BEGIN
	ROLLBACK
	RETURN 5
END

/*********************************************************************************************************************************************/	
				/*FINALIZA CORRECTAMENTE EL ALTA Y TERMINA LA TRANSACCION*/
/*********************************************************************************************************************************************/	


COMMIT
RETURN 0
GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Modificando Procedimiento [dbo].[dbo.PA_PROYECTOS_M_DatosFase]...';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER OFF;


GO
ALTER PROCEDURE [dbo.PA_PROYECTOS_M_DatosFase] 
/*
ESTE PROCEDIMIENTO REALIZA EL ALTA, LA BAJA O MODIFICACION DE UN REGISTRO DE Fases de Proyectos
VALORES QUE DEVUELVE:

	20	TERMINACION CORRECTA
	21 	NO EXISTE EL PROYECTO SOBRE EL QUE SE QUIERE DAR MODIFICAR
	22 	NO EXISTE LA FASE A MODIFICAR
	23	ERROR EN LA ACTUALIZACION DEL REGISTRO DE FASE
	24	ERROR AL ACTUALIZAR ESTADO DEL PROYECTO
	25	ERROR AL ACTUALIZAR ESTADO OBRA
	26	ERROR AL ACTUALIZAR AYUDA_TECNICA
	27	ERROR EN LA ACTUALIZACION DE PENDIENTE CONTRATACION
	28	ERROR
	29	ERROR
	30	DATOS PLIEGO INCORRECTO
	31	ERROR AL ACTUALIZAR EL EXPEDIENTE DE CONTRATACIÓN

*/

/* VARIABLES PARA LA FASE
	CLAVE*/
@V_MUNICIPIO as smallint,
@V_AO_PROYECTO as smallint, 
@V_AO_FASE AS SMALLINT,
@V_NUMERO_PROYECTO AS SMALLINT,
@V_NUMERO_FASE AS SMALLINT ,
	/* FECHAS*/

@V_FECHA_REM_FASE as smalldatetime = null,
@V_fecha_ENT_FASE as smalldatetime = null,
@V_fecha_REMISION_ayto as smalldatetime = null,
@V_fecha_aprobacion_ayto as smalldatetime = null,
@V_fecha_REMISION_JUNTA as smalldatetime = null,
@V_fecha_VISADO_JUNTA as smalldatetime = null,
@V_fecha_PET_INF_TECNICO_CONTRATA as smalldatetime = null,
@V_fecha_ENT_INF_TECNICO_CONTRATA as smalldatetime = null,
@V_PLIEGO_CLAUSULAS_PARTICULARES as smalldatetime = null,
@V_fecha_PET_DESGLOSE as smalldatetime = null,
@V_fecha_ENT_DESGLOSE as smalldatetime = null,
@V_fecha_pet_rectificacion as smalldatetime = null,
@V_fecha_ent_rectificacion as smalldatetime = null,
@V_fecha_pet_reforma as smalldatetime = null,
@V_fecha_ent_reforma as smalldatetime = null,
@V_fecha_pit_ref as smalldatetime = null,
@V_fecha_eit_ref as smalldatetime = null,
@V_fecha_ci_ref as smalldatetime = null,
@V_fecha_cg_ref as smalldatetime = null,
@V_FECHA_PET_ACTUAL_PRECIOS AS smalldatetime = null, 
@V_FECHA_ENT_ACTUAL_PRECIOS AS smalldatetime = null,
@V_FECHA_ENVIO_FISCALIZACION AS smalldatetime = null,
@V_fecha_COM_INF as smalldatetime = null,
@V_fecha_COM_GOB as smalldatetime = null,
@V_FISCALIZACION AS smalldatetime = null,
@V_FECHA_REMISION_CONTRATACION AS smalldatetime = null,
@V_fecha_dto as smalldatetime = null, 
	/* IMPORTES*/

@V_IMPORTE_FASE as float,
@V_presu_gral_ejecucion_material as float,
@V_por_gastos_generales as float,
@V_importe_gastos_generales as float,
@V_por_beneficio_industriales as float,
@V_importe_beneficio_industriales as float,
@V_por_control_calidad as float,
@V_importe_control_calidad as float,
@v_por_iva as float,
@V_iva as float,
@V_por_subcontrata as float,
@V_subcontrata as float,
@V_honorarios_dir as float,
@V_honorarios_red as float,
@V_plan_ss as float,
@V_IVA_Honor_Direcc as bit,
@V_IVA_Honor_Redacc as bit,


/*RESTO DATOS*/
@V_servicio_gestor  AS SMALLINT,
@V_CODIGO_PLAN AS CHAR(7) ,
@V_REFERENCIA AS SMALLINT,
@V_SUBREFERENCIA AS TINYINT,
@V_AO_EJECUCION_OBRA AS SMALLINT ,
@V_CARRETERA AS CHAR(15) ,
@V_PLAZO AS smallint ,
@V_UNIDAD_PLAZO AS CHAR(1) = 'm',
@V_NRO_EJEMPLARES AS SMALLINT  ,
@V_REVISION AS char(2) ,
@V_FORMULA AS TINYINT ,
@V_FORMULA2 AS TINYINT,
@V_FORMULA3 AS TINYINT,
@V_FORMULA4 AS TINYINT,
@V_ORGANISMO_DIRECCION AS CHAR(2),

@V_SERVICIO_DIRECCION AS varchar(150)  ,
@V_DIRECTOR_TECNICO_OBRA AS VARCHAR(150),
@V_COLEGIOOFICIALDIRECCION  AS VARCHAR(80),
@V_SUBVENCIONECONDIRECCION AS BIT,
@V_nro_dto as smallint ,
@V_CLASE_EXP AS CHAR(2)=null ,
@V_TIPO_PROC AS CHAR(2)=null ,
@V_FORMA_CONT AS CHAR(2)=null ,
@V_Requiere_PlanSyS as bit,
@V_EnvioContratacion as integer output

AS

DECLARE @V_EXISTE            AS INTEGER
DECLARE @V_EXISTE_FASE AS INTEGER
DECLARE @V_EXISTE_PTE   AS INTEGER
declare @v_existe_pteactivo as integer
declare @v_ExpActivo as integer
declare @v_numorden as integer
DECLARE @V_ERROR            AS INTEGER
DECLARE @V_ESTADO_PROY1 AS CHAR(3)
DECLARE @V_ESTADO_OBRA1 AS CHAR(3)
DECLARE @V_ESTADO_FASE1 AS CHAR(3)
DECLARE @V_FORMAEJEC       AS CHAR(3) 
DECLARE @V_SED AS CHAR(2)
DECLARE @V_SER AS bit
DECLARE @V_SER2 AS CHAR(2)
DECLARE @V_ORGANISMO_REDACTOR AS CHAR(2)
DECLARE @V_SERVICIO_REDACTOR AS SMALLINT
declare @v_servicio_dir as smallint
declare @v_colofid as char(2)
DECLARE @V_AUTOR AS CHAR(60)
declare @v_PETAYUDA_NUEVA AS CHAR(2)
DECLARE @V_PETAYUDA_ANTES AS CHAR(2)
/*Diana*/
DECLARE @V_IMPORTE_A_CONTRATAR AS FLOAT
DECLARE @v_existe_ObrasMultiples AS INTEGER
DECLARE @v_existe_ExpedienteContratacion AS INTEGER
DECLARE @AoContratacion AS INTEGER
DECLARE @TipoExpediente AS CHAR(2)
DECLARE @NumExpediente AS INTEGER

/******************************************************************************************************************************************** */	
				/*COMPRUEBA SI EXISTE EL PROYECTO Y LA  FASE Y PTE CONTRATACION*/
/******************************************************************************************************************************************** */

set @v_existe_fase = 0
SET @V_EXISTE = 0
SET @V_EXISTE_PTE = 0
set @v_existe_pteactivo =0
set @v_enviocontratacion=0
set @v_ExpActivo=0

IF @v_CLASE_EXP<>null and @v_FORMA_CONT<>null and @v_TIPO_PROC<>null
begin
/* COMPRUEBA EXISTE EL REGISTRO EN LA TABLA PLAZOS*/
select  @v_existe = count(1) from CONTRATA..PLAZOS 
WHERE (CodClaseExp = @v_CLASE_EXP) AND
(CodFormaContra = @v_FORMA_CONT) AND 
(CodProcedimiento = @v_TIPO_PROC) 
if @v_existe = 0 
begin
	RETURN 30				/*DATOS PLIEGO INCORRECTO*/
END
end

/* carga el servicio director y el colegio oficial de dirección si existen*/

if rtrim(@v_servicio_direccion) <> ' ' 
begin
	select @v_servicio_dir = codigo_dpto from tablas..tabladedepartamentos where denominacion = @v_servicio_direccion
	
	set @v_error = @@error
	if @v_error <> 0
	begin
		RETURN 28			/* ERROR al leer el servicio director*/
	end
end
if rtrim(@v_colegiooficialdireccion) <> ' '
begin
	select @v_colofid = codigo_redactor from tablas..colegiosoficiales where organismo_redactor = @v_colegiooficialdireccion
	set @v_error = @@error
	if @v_error <> 0
	begin
		RETURN 29			/* ERROR al leer el colegio*/
	end
end
ELSE
BEGIN
	SET @V_COLOFID = NULL
END
/* COMPRUEBA EXISTA EL PROYECTO*/
select  @v_existe = count(1) from proyectos 
WHERE (CODIGO_MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto) 
if @v_existe = 0 
begin
	RETURN 21				/*NO EXISTE EL PROYECTO DEL QUE SE QUIERE MODIFICAR LA FASE*/
END


/* COMPRUEBA QUE EXISTE LA FASE*/
select @v_existe_fase = count(1) from fasesdeproyectos
WHERE (MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto) AND 
(AO_FASE= @V_AO_FASE) AND
(NUMERO_FASE = @V_NUMERO_FASE)
IF @V_EXISTE_FASE= 0 
begin
	RETURN 22				/*NO EXISTE LA FASE QUE SE QUIERE MODIFICAR*/
end



/*COMPRUEBA SI EXISTE pendienteCONTRATACION  */
select @v_existe_PTE = count(1) ,@v_numorden=max(peticioncont) from  CONTRATA..PENDIENTECONTRATACIONOBRAS
where  planOBRA= @V_CODIGO_PLAN and
NUMobra = @V_REFERENCIA and
subref = @V_SUBREFERENCIA and
aoOBRA = @V_AO_EJECUCION_OBRA 



/*COMPRUEBA SI EXISTE pendienteCONTRATACION y esta pendiente de apertura*/
select @v_existe_PTEactivo = count(1)  from  CONTRATA..PENDIENTECONTRATACIONOBRAS
where  planOBRA= @V_CODIGO_PLAN and
NUMobra = @V_REFERENCIA and
subref = @V_SUBREFERENCIA and
aoOBRA = @V_AO_EJECUCION_OBRA and
estado is null

/* LEE EL ESTADO DE LA OBRA EN INICIO Y FORMA DE EJECUCION Y PETICION DE AYUDA*/
select @v_estado_obra1= codigo_estado_obra, @V_FORMAEJEC = FORMA_EJECUCION,@V_PETAYUDA_ANTES=PETICION_AYUDA_TEC  from datosiniciodeobras
where  codigo_plan= @V_CODIGO_PLAN and
numero_obra = @V_REFERENCIA and
subreferencia = @V_SUBREFERENCIA and
ao_ejecucion = @V_AO_EJECUCION_OBRA



/*COMPRUEBA SI EXISTE pendienteCONTRATACION y el estado es diferente de DES y  RES y tiene expediente iniciado */
select @v_ExpActivo = count(1)  from  CONTRATA..PENDIENTECONTRATACIONOBRAS
where  planOBRA= @V_CODIGO_PLAN and
NUMobra = @V_REFERENCIA and
subref = @V_SUBREFERENCIA and
aoOBRA = @V_AO_EJECUCION_OBRA and
(estado is not null AND estado<>'DES' AND estado<>'RES')


/*LEE EL ESTADO DEL PROYECTO, REDACTOR Y AUTOR*/
SELECT @V_ESTADO_PROY1 = ESTADO_PROYECTO , @V_ORGANISMO_REDACTOR= ORGANISMO_REDACTOR, @V_SERVICIO_REDACTOR =SERVICIO_REDACTOR, @V_AUTOR=AUTOR,@v_ser=subvencioneconredaccion FROM PROYECTOS
WHERE (CODIGO_MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto)

/* LEE EL ESTADO DE LA FASE*/
SELECT @V_ESTADO_FASE1 =ESTADO_FASE FROM FASESDEPROYECTOS
WHERE (MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto) AND 
(AO_FASE= @V_AO_FASE) AND
(NUMERO_FASE = @V_NUMERO_FASE)

/* DIANA -- LEE EL IMPORTE A CONTRATAR*/
SELECT @V_IMPORTE_A_CONTRATAR = IMPORTE_A_CONTRATAR FROM IMPORTESDEOBRAS
WHERE (CODIGO_PLAN = @V_CODIGO_PLAN) AND 
(NUMERO_OBRA = @V_REFERENCIA) AND 
(SUBREFERENCIA = @V_SUBREFERENCIA) AND
(AO_EJECUCION = @V_AO_EJECUCION_OBRA)


/* ******************************************************************************************************************************************* */	
/*  COMPROBACIONES ANTERIORES A INICIAR LA TRANSACCION (PARA MODIFICACIONES)                  	   */
/* ******************************************************************************************************************************************* */

/* ACTUALIZACION DE LOS ESTADOS*/
IF (@V_ESTADO_OBRA1 = 'DES' ) AND (@V_FORMAEJEC = 'DIP')  AND @V_FECHA_REMISION_CONTRATACION <> '  '
BEGIN

	SET @V_ESTADO_FASE1='TE'
	SET @V_ESTADO_PROY1='TE'
	SET @V_ESTADO_OBRA1='PA'
END
IF (@V_ESTADO_OBRA1 = 'TPR' OR @V_ESTADO_OBRA1 = 'PA') AND (@V_FORMAEJEC = 'DIP')  AND @V_FECHA_REMISION_CONTRATACION <> '  '
BEGIN

	SET @V_ESTADO_FASE1='TE'
	SET @V_ESTADO_PROY1='TE'
	SET @V_ESTADO_OBRA1='PA'
END
IF (@V_ESTADO_OBRA1 = 'TPR' OR @V_ESTADO_OBRA1 = 'PA') AND (@V_FORMAEJEC = 'DIP') AND @V_FECHA_REMISION_CONTRATACION is null
BEGIN
	
	SET @V_ESTADO_FASE1= 'TFA'
	SET @V_ESTADO_PROY1='TPR'
	SET @V_ESTADO_OBRA1='TPR'
END
IF (@V_ESTADO_OBRA1 = 'TPR' OR @V_ESTADO_OBRA1 = 'PA') AND @V_FORMAEJEC <>  'DIP'  AND (@V_FECHA_COM_GOB <> '  ' OR  @V_FECHA_DTO <>'  ')
BEGIN

	SET @V_ESTADO_FASE1= 'TE'
	SET @V_ESTADO_PROY1='TE'
	SET @V_ESTADO_OBRA1='PA'
END	
IF (@V_ESTADO_OBRA1 = 'TPR' OR @V_ESTADO_OBRA1 = 'PA') AND @V_FORMAEJEC <>  'DIP'  AND (@V_FECHA_COM_GOB = NULL AND   @V_FECHA_DTO = NULL)
BEGIN

	SET @V_ESTADO_FASE1= 'TFA'
	SET @V_ESTADO_PROY1='TPR'
	SET @V_ESTADO_OBRA1='TPR'
END	
/* ******************************************************************************************************************************************* */	
/*   COMPRUEBA EL NUEVO VALOR DE PETICION DE AYUDA TECNICA */
/* ******************************************************************************************************************************************* */	
set @v_petayuda_nueva = 'NO'
IF @V_ORGANISMO_REDACTOR = 'DP' OR @V_ORGANISMO_DIRECCION='DP' OR @V_SUBVENCIONECONDIRECCION <> 0  or @v_ser <> 0 
begin
	set @v_petayuda_nueva='SI'
end

 /* ******************************************************************************************************************************************* */	
/*  EMPIEZA LA ACTUALIZACION                 	   */
/* ******************************************************************************************************************************************* */

/* ACTUALIZO EL REGISTRO DE FASE*/
BEGIN TRANSACTION
UPDATE FASESDEPROYECTOS
SET
Servicio_gestor=@V_servicio_gestor,  
CODIGO_PLAN=@V_CODIGO_PLAN,
REFERENCIA =@V_REFERENCIA,
SUBREFERENCIA  =@V_SUBREFERENCIA,
AO_EJECUCION_OBRA =@V_AO_EJECUCION_OBRA,
CARRETERA =@V_CARRETERA,
PLAZO = @V_PLAZO,
UNIDADPLAZO = @V_UNIDAD_PLAZO,
NRO_EJEMPLARES = @V_NRO_EJEMPLARES,

REVISION  = @V_REVISION,
FORMULA = @V_FORMULA,
FORMULA2 = @V_FORMULA2,
FORMULA3 = @V_FORMULA3,
FORMULA4 = @V_FORMULA4,
ORGANISMO_DIRECCION = @V_ORGANISMO_DIRECCION,
SERVICIO_DIRECCION  = @V_SERVICIO_DIR,
DIRECTOR_TECNICO_OBRA  = @V_DIRECTOR_TECNICO_OBRA,
COLEGIOOFICIALDIRECCION   = @V_COLOFID,
SUBVENCIONECONDIRECCION = @V_SUBVENCIONECONDIRECCION,
nro_dto  = @V_nro_dto,
estado_FASE  = @V_ESTADO_FASE1,
CLASE_EXP = @V_CLASE_EXP,
TIPO_PROC   = @V_TIPO_PROC,
FORMA_CONT    = @V_FORMA_CONT,
Requiere_PlanSyS     = @V_Requiere_PlanSyS,

/*IMPORTES*/

IMPORTE_FASE  = @V_IMPORTE_FASE ,
presu_gral_ejecucion_material  = @V_presu_gral_ejecucion_material,
por_gastos_generales  = @V_por_gastos_generales,
importe_gastos_generales  = @V_importe_gastos_generales,
por_beneficio_industriales  = @V_por_beneficio_industriales,
importe_beneficio_industriales  = @V_importe_beneficio_industriales,
por_control_calidad  = @V_por_control_calidad,
importe_control_calidad  = @V_importe_control_calidad,
por_iva =@V_por_iva,
Iva  =@V_iva,
por_subcontrata  = @V_por_subcontrata,
subcontrata  = @V_subcontrata,
honorarios_dir  = @V_honorarios_dir,
honorarios_red  = @V_honorarios_red,
ImportePlanSyS = @V_plan_ss,
HD_ExcluidoIVA = @V_IVA_Honor_Direcc,
HR_ExcluidoIVA = @V_IVA_Honor_Redacc,

/* FECHAS*/

FECHA_REM_FASE  = @V_FECHA_REM_FASE,
fecha_ENT_FASE = @V_fecha_ENT_FASE,
fecha_REMISION_ayto  =  @V_fecha_REMISION_ayto,
fecha_aprobacion_ayto  =  @V_fecha_aprobacion_ayto,
fecha_REMISION_JUNTA  =  @V_fecha_REMISION_JUNTA,
FECHA_VISADO_JUNTA =   @V_FECHA_VISADO_JUNTA,
fecha_PET_INF_TECNICO_CONTRATA  =   @V_fecha_PET_INF_TECNICO_CONTRATA,
fecha_ENT_INF_TECNICO_CONTRATA   =   @V_fecha_ENT_INF_TECNICO_CONTRATA,
PLIEGO_CLAUSULAS_PARTICULARES   =   @V_PLIEGO_CLAUSULAS_PARTICULARES,
fecha_PET_DESGLOSE  =   @V_fecha_PET_DESGLOSE,
fecha_ENT_DESGLOSE   =   @V_fecha_ENT_DESGLOSE,
fecha_pet_rectificacion   =  @V_fecha_pet_rectificacion,
fecha_ent_rectificacion   =   @V_fecha_ent_rectificacion,
fecha_pet_reforma   =   @V_fecha_pet_reforma,
fecha_ent_reforma  =    @V_fecha_ent_reforma,
fecha_pit_ref   =   @V_fecha_pit_ref,
fecha_eit_ref   =   @V_fecha_eit_ref,
fecha_ci_ref    =   @V_fecha_ci_ref,
fecha_cg_ref   =   @V_fecha_cg_ref,
FECHA_PET_ACTUAL_PRECIOS   =  @V_FECHA_PET_ACTUAL_PRECIOS,
FECHA_ENT_ACTUAL_PRECIOS   =   @V_FECHA_ENT_ACTUAL_PRECIOS,
FECHA_ENVIO_FISCALIZACION  = @V_FECHA_ENVIO_FISCALIZACION,
fecha_COM_INF   = @V_fecha_COM_INF,
fecha_COM_GOB  =  @V_fecha_COM_GOB,
fecha_FISCALIZACION  =   @V_FISCALIZACION,
FECHA_REMISION_CONTRATACION   =  @V_FECHA_REMISION_CONTRATACION,
fecha_dto   =   @V_fecha_dto

WHERE (MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto) AND 
(AO_FASE= @V_AO_FASE) AND
(NUMERO_FASE = @V_NUMERO_FASE)
set @v_error = @@error
if @v_error <> 0
begin
	ROLLBACK
	RETURN 23			/* ERROR EN LA ACTUALIZACION DEL REGISTRO DE FASE*/
end

/* ACTUALIZO EL ESTADO DE PROYECTO*/
UPDATE PROYECTOS
SET
ESTADO_PROYECTO=@V_ESTADO_PROY1
WHERE (codigo_MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto) 
set @v_error = @@error
if @v_error <> 0
begin 
	ROLLBACK	
	return 24		/*ERROR AL ACTUALIZAR ESTADO DEL PROYECTO*/			
end

/* ACTUALIZA ESTADO OBRA y peticion ayuda tecnica*/

UPDATE DATOSINICIODEOBRAS
SET
CODIGO_ESTADO_obra=@V_ESTADO_OBRA1,
peticion_ayuda_tec=@v_petayuda_nueva
where  codigo_plan= @V_CODIGO_PLAN and
numero_obra = @V_REFERENCIA and
subreferencia = @V_SUBREFERENCIA and
ao_ejecucion = @V_AO_EJECUCION_OBRA
set @v_error = @@error
if @v_error <> 0
begin 
	ROLLBACK	
	return 25		/*ERROR AL ACTUALIZAR ESTADO OBRA*/			
end

/*ACTUALIZO LA AYUDA TECNICA POR SI HUBO CAMBIOS*/
IF @V_SUBVENCIONECONDIRECCION = 0 
BEGIN
	SET @V_SED=null
END
ELSE
BEGIN
	SET @V_SED='SI'
END
IF @V_SER = 0 
BEGIN
	SET @V_SER2=null
END
ELSE
BEGIN
	SET @V_SER2='SI'
END
if @v_petayuda_nueva='SI' and @v_petayuda_antes= 'SI'
begin 
	UPDATE AYUDA_TECNICA
	SET
	DEPARTAMENTO_DIRECCION= @V_SERVICIO_DIR,
	DPTO_REDACTOR=@V_SERVICIO_REDACTOR,
	SUBVENCIONECONOMICAR = @V_SER2,
	SUBVENCIONECONOMICAD = @V_SED,
	PASADO=1
	where  codigo_plan= @V_CODIGO_PLAN and
	numero_obra = @V_REFERENCIA and
	subreferencia = @V_SUBREFERENCIA and
	ao_ejecucion = @V_AO_EJECUCION_OBRA
	set @v_error = @@error
	if @v_error <> 0	begin 
		ROLLBACK	
		return 26		/*ERROR AL ACTUALIZAR AYUDA_TECNICA*/			
	end
end
if @v_petayuda_nueva = 'NO' and @v_petayuda_antes = 'SI'
begin
	delete from ayuda_tecnica
	where  codigo_plan= @V_CODIGO_PLAN and
	numero_obra = @V_REFERENCIA and
	subreferencia = @V_SUBREFERENCIA and
	ao_ejecucion = @V_AO_EJECUCION_OBRA
	set @v_error = @@error
	if @v_error <> 0	begin 
		ROLLBACK	
		return 26		/*ERROR AL ACTUALIZAR AYUDA_TECNICA*/			
	end
end
if @v_petayuda_nueva = 'SI' and @v_petayuda_antes = 'NO'
begin
	INSERT INTO ayuda_tecnica
		   (CODIGO_PLAN,NUMERO_OBRA,SUBREFERENCIA,AO_EJECUCION,	departamento, codigo_municipio,ao_proyecto,numero_proyecto,DPTO_REDACTOR,DEPARTAMENTO_DIRECCION,PASADO,SUBVENCIONECONOMICAR,SUBVENCIONECONOMICAD)
	VALUES (@V_CODIGO_PLAN, @V_REFERENCIA,@V_SUBREFERENCIA,@V_AO_EJECUCION_OBRA,0,0,0,0,@V_SERVICIO_REDACTOR,@V_SERVICIO_DIR,1,@V_SER2,@V_SED)
	set @v_error = @@error
	if @v_error <> 0	begin 
		ROLLBACK	
		return 26		/*ERROR AL ACTUALIZAR AYUDA_TECNICA*/			
	end
end
/* GRABA PENDIENTE CONTRATACION O ACTUALIZA REGISTRO EXISTENTE*/
set @v_enviocontratacion=0  
if @V_FORMAEJEC <> 'DIP' OR ( @V_FECHA_REMISION_CONTRATACION is null OR RTRIM(@V_FECHA_REMISION_CONTRATACION)='')
begin
	commit
	return 20
end

if @v_ExpActivo>0
begin
	commit
	return 20
end

if @v_estado_obra1 = 'TPR' and @V_EXISTE_PTEACTIVO > 0
BEGIN
	DELETE CONTRATA..PENDIENTECONTRATACIONOBRAS
	WHERE
	planOBRA= @V_CODIGO_PLAN and
	numobra = @V_REFERENCIA and
	subref = @V_SUBREFERENCIA and
	AOOBRA = @V_AO_EJECUCION_OBRA AND
	TIPOEXP='OB' and 
	peticioncont=@v_numorden
	set @v_enviocontratacion=3
	COMMIT
	RETURN 20
END

--if @v_estado_obra1 <> 'PA'

IF @V_EXISTE_PTEactivo  > 0 
BEGIN
	UPDATE CONTRATA..PENDIENTECONTRATACIONOBRAS
	SET
	ampliacion=0,
	--servicio_redactor=@v_servicio_redactor,
	--autor=@v_autor,
	servicio_gestor=@v_servicio_gestor,
	--servicio_direccion=@v_servicio_dir,
	codclaseexp= @V_CLASE_EXP,
	codprocedimiento = @V_TIPO_PROC    ,
	CodFormaContrata    = @V_FORMA_CONT,
	--importelicitacion=@v_importe_fase,
	--Diana
	importelicitacion=@V_IMPORTE_A_CONTRATAR,
	fecharecepcion=getdate (),
	esTADO=NULL
	WHERE
	planOBRA= @V_CODIGO_PLAN and
	numobra = @V_REFERENCIA and
	subref = @V_SUBREFERENCIA and
	AOOBRA = @V_AO_EJECUCION_OBRA AND
	TIPOEXP='OB' and 
	peticioncont=@v_numorden
	set @v_enviocontratacion=1
end
if @v_existe_pteactivo = 0 
begin
	IF @V_EXISTE_PTE = 0 
	BEGIN
		SET @V_NUMORDEN=0
	END
	set @v_numorden=@v_numorden +1
	/*INSERT INTO contrata..pendientecontratacionobras
 		 (tipoexp,peticioncont,planobra,numobra,subref,aoobra,ampliacion,servicio_redactor,servicio_gestor,servicio_direccion,
		autor,importelicitacion,codclaseexp,codprocedimiento,codformacontrata,fecharecepcion,estado)	
	VALUES ('OB',@v_numorden,@V_CODIGO_PLAN, @V_REFERENCIA , @V_SUBREFERENCIA, @V_AO_EJECUCION_OBRA ,0,@V_SERVICIO_REDACTOR,
	@V_SERVICIO_GESTOR,@V_SERVICIO_DIR,@V_AUTOR,@V_IMPORTE_FASE,@V_CLASE_EXP, @V_TIPO_PROC   ,
	@V_FORMA_CONT,getdate (),NULL)*/

	INSERT INTO contrata..pendientecontratacionobras
 		 (tipoexp,peticioncont,planobra,numobra,subref,aoobra,ampliacion,servicio_gestor,
		importelicitacion,codclaseexp,codprocedimiento,codformacontrata,fecharecepcion,estado)	
	VALUES ('OB',@v_numorden,@V_CODIGO_PLAN, @V_REFERENCIA , @V_SUBREFERENCIA, @V_AO_EJECUCION_OBRA ,0,
	@V_SERVICIO_GESTOR,@V_IMPORTE_A_CONTRATAR,@V_CLASE_EXP, @V_TIPO_PROC   ,
	@V_FORMA_CONT,getdate (),NULL)
	set @v_enviocontratacion=2
END
set @v_error = @@error
if @v_error <> 0
begin	ROLLBACK	
	RETURN 27			/*ERROR EN PENDIENTE DE CONTRATACION*/
end


--Diana--
/*COMPRUEBA SI EXISTE el registro en ObrasMultiples*/
/*select @v_existe_ObrasMultiples = count(1)  from  CONTRATA..OBRASMULTIPLES
where  	planOBRA= @V_CODIGO_PLAN and
	numobra = @V_REFERENCIA and
	subref = @V_SUBREFERENCIA and
	AOOBRA = @V_AO_EJECUCION_OBRA

IF @v_existe_ObrasMultiples>0 --Rellenamos las variables que nos faltan para leer de la tabla ExpedientesdeContratacion
begin
select @AoContratacion = AoContratacion, @TipoExpediente = TipoExpediente, @NumExpediente = NumExpediente  from  CONTRATA..OBRASMULTIPLES
where  	planOBRA= @V_CODIGO_PLAN and
	numobra = @V_REFERENCIA and
	subref = @V_SUBREFERENCIA and
	AOOBRA = @V_AO_EJECUCION_OBRA
end
*/

/*COMPRUEBA SI EXISTE el registro en ExpedienteContratacion*/
/*select @v_existe_ExpedienteContratacion = count(1)  from  CONTRATA..EXPEDIENTECONTRATACION
where  	AoContratacion = @AoContratacion and
	TipoExpediente = @TipoExpediente and
	NumExpediente = @NumExpediente

if @v_existe_ExpedienteContratacion>0  --Trasladamos el valor de Revisión de Precios
begin
	UPDATE CONTRATA..EXPEDIENTECONTRATACION
	SET	RevisionPrecios = @V_REVISION
	where  	AoContratacion = @AoContratacion and
		TipoExpediente = @TipoExpediente and
		NumExpediente = @NumExpediente
end
set @v_error = @@error
if @v_error <> 0
begin	ROLLBACK	
	RETURN 31			--ERROR EN EXPEDIENTES DE CONTRATACIÓN
end*/

COMMIT
RETURN 20			/*PROCESO REALIZADO CORRECTAMENTE*/
GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Modificando Procedimiento [dbo].[PA_PROYECTOS_ABM_DatosFase]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
ALTER PROCEDURE [PA_PROYECTOS_ABM_DatosFase] 
/*
ESTE PROCEDIMIENTO REALIZA EL ALTA, LA BAJA O MODIFICACION DE UN REGISTRO DE Fases de Proyectos
VALORES QUE DEVUELVE:

	0	TERMINACION CORRECTA
	1	ERROR EN LA ACTUALIZACION DE LA AYUDA TECNICA
	2	ERROR AL ACTUALIZAR ESTADO DE INICIO DE OBRA o de proyecto
	3	ERROR al leer el servicio director O EL COLEGIO OFICIAL DE DIRECCION
	4 	NO EXISTE EL PROYECTO, NO SE PUEDE DAR DE ALTA LA FASE
	5 	YA EXISTE LA FASE QUE SE QUIERE DAR DE ALTA
	6 	
	7 		
 	8 	ERROR AL ASIGNAR UN NUEVO NÚMERO DE FASE
	9	ERROR EN EL ALTA DE LA FASE
	10	ERROR EN LA ACTUALIZACION DEL REGISTRO


	
*/
@v_accion as char (1)  = 'A',
/* VARIABLES PARA LA FASE
	CLAVE*/
@V_MUNICIPIO as smallint,
@V_AO_PROYECTO as smallint, 
@V_AO_FASE AS SMALLINT,
@V_NUMERO_PROYECTO AS SMALLINT=0,
@V_NUMERO_FASE AS SMALLINT = 0,
	/* FECHAS*/

@V_FECHA_REM_FASE as smalldatetime = null,
@V_fecha_ENT_FASE as smalldatetime = null,
@V_fecha_REMISION_ayto as smalldatetime = null,
@V_fecha_aprobacion_ayto as smalldatetime = null,
@V_fecha_REMISION_JUNTA as smalldatetime = null,
@V_fecha_VISADO_JUNTA as smalldatetime = null,
@V_fecha_PET_INF_TECNICO_CONTRATA as smalldatetime = null,
@V_fecha_ENT_INF_TECNICO_CONTRATA as smalldatetime = null,
@V_PLIEGO_CLAUSULAS_PARTICULARES as smalldatetime = null,
@V_fecha_PET_DESGLOSE as smalldatetime = null,
@V_fecha_ENT_DESGLOSE as smalldatetime = null,
@V_fecha_pet_rectificacion as smalldatetime = null,
@V_fecha_ent_rectificacion as smalldatetime = null,
@V_fecha_pet_reforma as smalldatetime = null,
@V_fecha_ent_reforma as smalldatetime = null,
@V_fecha_pit_ref as smalldatetime = null,
@V_fecha_eit_ref as smalldatetime = null,
@V_fecha_ci_ref as smalldatetime = null,
@V_fecha_cg_ref as smalldatetime = null,
@V_FECHA_PET_ACTUAL_PRECIOS AS smalldatetime = null, 
@V_FECHA_ENT_ACTUAL_PRECIOS AS smalldatetime = null,
@V_FECHA_ENVIO_FISCALIZACION AS smalldatetime = null,
@V_fecha_COM_INF as smalldatetime = null,
@V_fecha_COM_GOB as smalldatetime = null,
@V_FISCALIZACION AS smalldatetime = null,
@V_FECHA_REMISION_CONTRATACION AS smalldatetime = null,
@V_fecha_dto as smalldatetime = null,
	/* IMPORTES*/
@V_IMPORTE_FASE as float,
@V_presu_gral_ejecucion_material as float,
@V_por_gastos_generales as float,
@V_importe_gastos_generales as float,
@V_por_beneficio_industriales as float,
@V_importe_beneficio_industriales as float,
@V_por_control_calidad as float,
@V_importe_control_calidad as float,
@v_por_iva as float,
@V_iva as float,
@V_por_subcontrata as float,
@V_subcontrata as float,
@V_honorarios_dir as float,
@V_honorarios_red as float,
@V_plan_ss as float,
@V_IVA_Honor_Direcc as bit,
@V_IVA_Honor_Redacc as bit,


/*RESTO DATOS*/
@V_servicio_gestor  AS SMALLINT,
@V_CODIGO_PLAN AS CHAR(7) ,
@V_REFERENCIA AS SMALLINT,
@V_SUBREFERENCIA AS TINYINT,
@V_AO_EJECUCION_OBRA AS SMALLINT ,
@V_CARRETERA AS CHAR(15) ,
@V_PLAZO AS smallint ,
@V_UNIDAD_PLAZO as char(1),
@V_NRO_EJEMPLARES AS SMALLINT  ,
@V_REVISION AS char(2) ,
@V_FORMULA AS TINYINT ,
@V_FORMULA2 AS TINYINT,
@V_FORMULA3 AS TINYINT,
@V_FORMULA4 AS TINYINT,
@V_ORGANISMO_DIRECCION AS CHAR(2),
@V_SERVICIO_DIRECCION AS varchar(150)  ,
@V_DIRECTOR_TECNICO_OBRA AS VARCHAR(150),
@V_COLEGIOOFICIALDIRECCION  AS varcHAR(150),
@V_SUBVENCIONECONDIRECCION AS BIT,
@V_nro_dto as smallint ,
@V_estado_FASE as CHAR(3),
@V_CLASE_EXP AS CHAR(2) ,
@V_TIPO_PROC AS CHAR(2) ,
@V_FORMA_CONT AS CHAR(2) ,
@V_Requiere_PlanSyS as bit,

/* PARAMETROS DE SALIDA************************************************************************
    DEVUELVE EL NUMERO DE PROYECTO Y FASE SOBRE LOS QUE SE HA ACTUADO */
@V_NUM_FASE AS SMALLINT OUTPUT
AS

DECLARE @V_EXISTE          AS INTEGER
DECLARE @V_EXISTE_FASE AS INTEGER
DECLARE @V_ERROR          AS INTEGER
DECLARE @V_ESTADO_PROY1 AS CHAR(3)
DECLARE @V_ESTADO_OBRA1 AS CHAR(3)
DECLARE @V_ESTADO_FASE1 AS CHAR(3)
DECLARE @V_ESTADO_PROY2 AS CHAR(3)
DECLARE @V_ESTADO_OBRA2 AS CHAR(3)
DECLARE @V_ESTADO_FASE2  AS CHAR(3)
DECLARE @V_SERVDIR AS SMALLINT
DECLARE @V_COLOFID AS CHAR(2)
DECLARE @SUBVENCION AS CHAR(2)
DECLARE @SUBVENCIONR AS CHAR(2)
DECLARE @V_AYUDA AS CHAR(2)
DECLARE @V_SUBVECONR  AS BIT
DECLARE @V_ORGRED AS CHAR(2)
DECLARE @V_SERVRED AS SMALLINT

/*********************************************************************************************************************************************************** */	
/*COMPRUEBA SI EXISTE EL PROYECTO Y LA  FASE Y CARGA EL ORGANISMO,SERVICIO Y SUBVENCION DEL REDACTOR*/
/*********************************************************************************************************************************************************** */
set @v_existe_fase = 0

select  @v_existe = count(1) from proyectos 
WHERE (CODIGO_MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto) 


select  @v_SUBVECONR = SUBVENCIONECONREDACCION, @V_ORGRED = ORGANISMO_REDACTOR, @V_SERVRED=SERVICIO_REDACTOR from proyectos 
WHERE (CODIGO_MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto) 

select @v_existe_fase = count(1) from fasesdeproyectos
where
CODIGO_PLAN=@V_CODIGO_PLAN and
REFERENCIA =@V_REFERENCIA and
SUBREFERENCIA  =@V_SUBREFERENCIA and
AO_EJECUCION_OBRA =@V_AO_EJECUCION_OBRA

SET @V_NUM_FASE= @V_NUMERO_FASE
/* ******************************************************************************************************************************************* */	
		/*INICIO DE LOS PROCESOS SOLICITADOS SEGUN LA ACCION TRANSFERIDA*/
/* ******************************************************************************************************************************************* */

/* *****************************************************************************************************************************
 carga si se requiere ayuda técnica
****************************************************************************************************************************/
set @v_ayuda = 'NO'

if @v_organismo_DIRECCION = 'DP'  or @V_SUBVENCIONECONDIRECCION  = 1 or @V_SUBVECONR = 1 OR @V_ORGRED= 'DP'
begin
	set @v_ayuda = 'SI'
end

/* ******************************************************************************************************************************************* */	
/*  COMPROBACIONES ANTERIORES A INICIAR LA TRANSACCION (PARA ALTA Y MODIFICACIONES)                    */
/* ******************************************************************************************************************************************* */

if @v_existe = 0 
begin
	RETURN 4				/*NO EXISTE EL PROYECTO, NO SE PUEDE DAR DE ALTA LA FASE*/
end
iF @V_EXISTE_FASE = 1 
BEGIN
	RETURN 5				/* YA EXISTE LA FASE QUE SE QUIERE DAR DE ALTA*/
END

/*  GRABA UN REGISTRO SOLO CON LA CLAVE */

/* ******************************************************************************************************************************************* */	
/* carga el servicio director y el colegio oficial de dirección si existen*/
/* ******************************************************************************************************************************************* */	
if rtrim(@v_servicio_direccion) <> ' ' 
begin
	select @v_servdir = codigo_dpto from tablas..tabladedepartamentos where denominacion = @v_servicio_direccion
	set @v_error = @@error
	if @v_error <> 0
	begin
		RETURN 3		/* ERROR al leer el servicio director*/
	end
end
ELSE
BEGIN
	SET @V_SERVDIR = NULL
END
if rtrim(@v_colegiooficialdireccion) <> ' '
begin
	select @v_colofid = codigo_redactor from tablas..colegiosoficiales where organismo_redactor = @v_colegiooficialdireccion
	set @v_error = @@error
	if @v_error <> 0
	begin
		RETURN 3	/* ERROR al leer el colegio*/
	end
end
ELSE
BEGIN
	SET @V_COLOFID = NULL
END
/* ******************************************************************************************************************************************* */	
				/*CALCULA EL NUMERO DE FASE QUE CORRESPONDE AL ALTA*/
/* ****************************************************************************************************************************************** */
SELECT @V_NUMERO_FASE = MAX(NUMERO_FASE) FROM FASESDEProyectos
GROUP BY MUNICIPIO,     AO_PROYECTO, NUMERO_PROYECTO
HAVING (MUNICIPIO = @v_municipio)  AND (AO_PROYECTO = @v_ao_proyecto) AND (NUMERO_PROYECTO = @V_NUMERO_PROYECTO)
if @V_NUMERO_FASE is null 
begin
	set @V_NUMERO_FASE = 0
end
set @V_NUMERO_FASE = @V_NUMERO_FASE + 1
set @V_NUM_FASE = @V_NUMERO_FASE
set @v_error = @@error
IF @V_ERROR <> 0 
BEGIN
	RETURN 8			/* ERROR AL ASIGNAR UN NUEVO NÚMERO DE FASE*/
END

/* ******************************************************************************************************************************************** 
		 AÑADE UN REGISTRO A FASESDEPROYECTO CON LA CLAVE SOLAMENTE
	
******************************************************************************************************************************************** */	
INSERT INTO FASESDEPROYECTOS
		   (MUNICIPIO, AO_PROYECTO, NUMERO_PROYECTO,AO_FASE,NUMERO_FASE)
VALUES (@V_MUNICIPIO, @V_AO_PROYECTO,@V_NUMERO_PROYECTO,@V_AO_FASE, @V_NUMERO_FASE)
set @v_error = @@error
if @v_error <> 0
begin
	RETURN 9			/*ERROR EN EL ALTA DE LA FASE*/
end


	
/* FINALIZA EL ALTA Y COMIENZA LA ACTUALIZACION*/

/*************************************************************************************************************************************************
	comprueba el estado que le corresponde a la fase
************************************************************************************************************************************************** */
UPDATE FASESDEPROYECTOS
SET
Servicio_gestor=@V_servicio_gestor,  
CODIGO_PLAN=@V_CODIGO_PLAN,
REFERENCIA =@V_REFERENCIA,
SUBREFERENCIA  =@V_SUBREFERENCIA,
AO_EJECUCION_OBRA =@V_AO_EJECUCION_OBRA,
CARRETERA =@V_CARRETERA,
PLAZO = @V_PLAZO,
UNIDADPLAZO = @V_UNIDAD_PLAZO,
NRO_EJEMPLARES = @V_NRO_EJEMPLARES,

REVISION  = @V_REVISION,
FORMULA = @V_FORMULA,
FORMULA2 = @V_FORMULA2,
FORMULA3 = @V_FORMULA3,
FORMULA4 = @V_FORMULA4,
ORGANISMO_DIRECCION = @V_ORGANISMO_DIRECCION,
SERVICIO_DIRECCION  = @V_SERVDIR,
DIRECTOR_TECNICO_OBRA  = @V_DIRECTOR_TECNICO_OBRA,
COLEGIOOFICIALDIRECCION   = @V_COLOFID,
SUBVENCIONECONDIRECCION = @V_SUBVENCIONECONDIRECCION,
nro_dto  = @V_nro_dto,
estado_FASE  = 'TFA',
CLASE_EXP = @V_CLASE_EXP,
TIPO_PROC   = @V_TIPO_PROC,
FORMA_CONT    = @V_FORMA_CONT,
Requiere_PlanSyS     = @V_Requiere_PlanSyS,

/*IMPORTES*/

IMPORTE_FASE  = @V_IMPORTE_FASE ,
presu_gral_ejecucion_material  = @V_presu_gral_ejecucion_material,
por_gastos_generales  = @V_por_gastos_generales,
importe_gastos_generales  = @V_importe_gastos_generales,
por_beneficio_industriales  = @V_por_beneficio_industriales,
importe_beneficio_industriales  = @V_importe_beneficio_industriales,
por_control_calidad  = @V_por_control_calidad,
importe_control_calidad  = @V_importe_control_calidad,
por_iva =@V_por_iva,
Iva  =@V_iva,
por_subcontrata  = @V_por_subcontrata,
subcontrata  = @V_subcontrata,
honorarios_dir  = @V_honorarios_dir,
honorarios_red  = @V_honorarios_red,
ImportePlanSyS = @V_plan_ss,
HD_ExcluidoIVA = @V_IVA_Honor_Direcc,
HR_ExcluidoIVA = @V_IVA_Honor_Redacc,


/* FECHAS*/

FECHA_REM_FASE  = @V_FECHA_REM_FASE,
fecha_ENT_FASE = @V_fecha_ENT_FASE,
fecha_REMISION_ayto  =  @V_fecha_REMISION_ayto,
fecha_aprobacion_ayto  =  @V_fecha_aprobacion_ayto,
fecha_REMISION_JUNTA  =  @V_fecha_REMISION_JUNTA,
FECHA_VISADO_JUNTA =   @V_FECHA_VISADO_JUNTA,
fecha_PET_INF_TECNICO_CONTRATA  =   @V_fecha_PET_INF_TECNICO_CONTRATA,
fecha_ENT_INF_TECNICO_CONTRATA   =   @V_fecha_ENT_INF_TECNICO_CONTRATA,
PLIEGO_CLAUSULAS_PARTICULARES   =   @V_PLIEGO_CLAUSULAS_PARTICULARES,
fecha_PET_DESGLOSE  =   @V_fecha_PET_DESGLOSE,
fecha_ENT_DESGLOSE   =   @V_fecha_ENT_DESGLOSE,
fecha_pet_rectificacion   =  @V_fecha_pet_rectificacion,
fecha_ent_rectificacion   =   @V_fecha_ent_rectificacion,
fecha_pet_reforma   =   @V_fecha_pet_reforma,
fecha_ent_reforma  =    @V_fecha_ent_reforma,
fecha_pit_ref   =   @V_fecha_pit_ref,
fecha_eit_ref   =   @V_fecha_eit_ref,
fecha_ci_ref    =   @V_fecha_ci_ref,
fecha_cg_ref   =   @V_fecha_cg_ref,
FECHA_PET_ACTUAL_PRECIOS   =  @V_FECHA_PET_ACTUAL_PRECIOS,
FECHA_ENT_ACTUAL_PRECIOS   =   @V_FECHA_ENT_ACTUAL_PRECIOS,
FECHA_ENVIO_FISCALIZACION  = @V_FECHA_ENVIO_FISCALIZACION,
fecha_COM_INF   = @V_fecha_COM_INF,
fecha_COM_GOB  =  @V_fecha_COM_GOB,
fecha_FISCALIZACION  =   @V_FISCALIZACION,
FECHA_REMISION_CONTRATACION   =  @V_FECHA_REMISION_CONTRATACION,
fecha_dto   =   @V_fecha_dto

WHERE (MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto) AND 
(AO_FASE= @V_AO_FASE) AND
(NUMERO_FASE = @V_NUMERO_FASE)

set @v_error = @@error
if @v_error <> 0
begin
	RETURN 10			/* ERROR EN LA ACTUALIZACION DEL REGISTRO*/
end
/*********************************************************************************************************************************************/	
					/*ACTUALIZA o BORRA LA  AYUDATECNICA*/
/*********************************************************************************************************************************************/	
if @v_SUBVENCIONECONDIRECCION = 1 
begin
	set @SUBVENCION = 'SI'
end
else
begin
	SET @SUBVENCION = 'NO'
end
if @v_SUBVECONR = 1 
begin
	set @SUBVENCIONR = 'SI'
end
else
begin
	SET @SUBVENCIONR = 'NO'
end


if @v_ayuda = 'SI'
begin
UPDATE Ayuda_Tecnica
	SET Pasado = 1,
	  SubvencionEconomicaR = @subvencionR,
	  SubvencionEconomicaD = @subvencion,
               DEPARTAMENTO_DIRECCION=@V_SERVDIR,
	  DPTO_REDACTOR =@V_SERVRED		
		
		
	WHERE	codigo_plan = @V_CODIGO_PLAN
	and 		numero_obra = @V_REFERENCIA
	and		subreferencia = @V_SUBREFERENCIA
	and		ao_ejecucion = @V_AO_EJECUCION_OBRA
end
else
begin
	delete from ayuda_tecnica
	WHERE	codigo_plan = @V_CODIGO_PLAN
	and 		numero_obra = @V_REFERENCIA
	and		subreferencia = @V_SUBREFERENCIA
	and		ao_ejecucion = @V_AO_EJECUCION_OBRA
end
	
set @v_error = @@error


if @v_error <> 0
BEGIN
	
	RETURN 1
END

/*********************************************************************************************************************************************/	
				/*ACTUALIZA EL CAMPO codigo_estado_obra DE DATOSINICIODEOBRAS*/
/*********************************************************************************************************************************************/	
UPDATE DatosInicioDeObras
	SET Codigo_estado_obra = 'TPR',
	        PETICION_AYUDA_TEC= @V_AYUDA	
	WHERE	codigo_plan = @V_CODIGO_PLAN
	and 		numero_obra = @V_REFERENCIA
	and		subreferencia = @V_SUBREFERENCIA
	and		ao_ejecucion = @V_AO_EJECUCION_OBRA
set @v_error = @@error

if @v_error <> 0
BEGIN
	
	RETURN 2
END

/*********************************************************************************************************************************************/	
				/*ACTUALIZA EL CAMPO codigo_estado DE proyectos*/
/*********************************************************************************************************************************************/	
UPDATE  proyectos
SET estado_proyecto = 'TPR'
WHERE (CODIGO_MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto) 

set @v_error = @@error

if @v_error <> 0
BEGIN
	
	RETURN 2
END

/*********************************************************************************************************************************************/	
				/*FINALIZA CORRECTAMENTE EL ALTA Y TERMINA LA TRANSACCION*/
/*********************************************************************************************************************************************/	

RETURN 0			/*PROCESO REALIZADO CORRECTAMENTE*/
GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Modificando Procedimiento [dbo].[PA_PROYECTOS_A_CreaUnProyectoCompletoOLD]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
ALTER PROCEDURE  [PA_PROYECTOS_A_CreaUnProyectoCompletoOLD] 
/* VARIABLES PARA EL PROYECTO*/

@V_CODIGO_MUNICIPIO as smallint,
@V_AO_PROYECTO as smallint, 
@V_SERVICIO_GESTOR AS SMALLINT,
@V_den_proyecto as varchar(150),
@V_importe_proyecto as float, 
@V_importe_fase as float, 
@V_organismo_redactor as char(2) ,
@V_SERVICIO_redactor AS varchar(150),
@V_autor as char(50),
@V_COLEGIOOFICIAL AS varchar(150),
@V_SUBVENCION_economicaR as bit = 0,
@V_SUBVENCION_ECONOMICAD AS BIT = 0 ,
@V_SERVICIO_DIRECCION AS SMALLINT= null,
@V_carretera as char(6),
@V_plazo as int,
@V_Unidad_Plazo as char(1) = 'm' ,
@V_nro_ejemplares as smallint, 
@V_revision as char(2),
@V_formula as int,
@V_formula2 as int,
@V_formula3 as int,
@V_formula4 as int, 
@V_presu_gral_ejecucion_material as float, 
@V_por_gastos_generales as float,  
@V_importe_gastos_generales as float, 
@V_por_beneficio_industriales as float, 
@V_importe_beneficio_industriales as float, 
@V_por_control_calidad as float,  
@V_importe_control_calidad as float,  
@V_por_iva as float, 
@V_iva as float, 
@V_por_subcontrata as float, 
@V_subcontrata as float, 
@V_honorarios_dir as float, 
@V_honorarios_red as float,  
@V_presu_gral_ejecucion_material_fase as float, 
@V_por_gastos_generales_fase as float,  
@V_importe_gastos_generales_fase as float, 
@V_por_beneficio_industriales_fase as float, 
@V_importe_beneficio_industriales_fase as float, 
@V_por_control_calidad_fase as float,  
@V_importe_control_calidad_fase as float,  
@V_por_iva_fase as float, 
@V_iva_fase as float, 
@V_por_subcontrata_fase as float, 
@V_subcontrata_fase as float, 
@V_honorarios_dir_fase as float, 
@V_honorarios_red_fase as float,  
@V_fecha_entrega_proyecto as smalldatetime = NULL,
@V_fecha_recepcion_proyecto as smalldatetime = NULL,
@V_fecha_remision_ayto as smalldatetime = NULL,
@V_fecha_aprobacion_ayto as smalldatetime = NULL, 
@V_fecha_pet_rectificacion as smalldatetime = NULL,
@V_fecha_ent_rectificacion as smalldatetime = NULL,
@V_fecha_pet_reforma as smalldatetime = NULL,
@V_fecha_ent_reforma as smalldatetime = NULL,
@V_fecha_c_infor as smalldatetime = NULL,
@V_fecha_c_gob as smalldatetime = NULL,
@V_fecha_pit_ref as smalldatetime = NULL,
@V_fecha_eit_ref as smalldatetime = NULL,
@V_fecha_ci_ref as smalldatetime = NULL, 
@V_fecha_cg_ref as smalldatetime = NULL,
@V_fecha_dto as smalldatetime = NULL,
@V_nro_dto as float,
@V_observaciones as varchar(240), 
@V_estado_proyecto as CHAR(3),
@V_Requiere_PlanSyS as bit ,
@V_Requiere_TratMed as bit ,
@v_compartido as bit = 0,
/*  VARIABLES ADICIONALES PARA CREAR  LA FASE 1 DEL PROYECTO*/
@V_plan as char(7),
@v_numobra as smallint,
@v_subref as int,
@v_aoplan as smallint,
@V_NUM_PROYECTO as smallint output
 AS
/*	Graba un nuevo registro en Proyectos, Graba un registro de FasesProyectos asociado a la obra.
	Actualiza AyudaTecnica para grabar el estado_proyecto a true (existe proyecto asociado).
	Actualiza el Estado_Obra en DatosInicioObra  a TPR (Trámite proyecto)
	Valores devueltos:
		0 -- Funcionamento correcto
		1 -- Fallo en la búsqueda del número de proyecto
		2 -- Fallo del Insert de Proyectos
		3 -- Fallo del Insert en FasesProyectos
		4 -- Fallo del Update de AyudaTecnica
		5 -- Fallo del Update de DatosInicioObras
		6 -- Ya existe el proyecto asociado a la obra
		8 -- Error al cargar el servicio redactor o colegio
*/

declare @v_error as int
declare @v_numero_proyecto as int
declare @v_existe as int
declare @subvencion as char(2)
declare @v_servred as smallint
declare @v_colofir as char(2)
DECLARE @v_ayuda as char(2)


/* *****************************************************************************************************************************
 carga si se requiere ayuda técnica
****************************************************************************************************************************/
set @v_ayuda = 'NO'

if @v_organismo_redactor = 'DP'  or @V_SUBVENCION_economicaR  = 1 or @V_SUBVENCION_economicad = 1
begin
	set @v_ayuda = 'SI'
end
/* *****************************************************************************************************************************
 carga el código del servicio redactor y el colegio oficial de redacción si existen
****************************************************************************************************************************/
if rtrim(@v_servicio_redactor) <> ' ' 
begin
	select @v_servred = codigo_dpto from tablas..tabladedepartamentos where denominacion = @v_servicio_redactor
	set @v_error = @@error
	if @v_error <> 0
	begin
		RETURN 8			/* ERROR al leer el servicio redactorr*/
	end
end
else 
begin
 	set @v_servred = null
end
if rtrim(@v_colegiooficial) <> ' '
begin
	select @v_colofiR = codigo_redactor from tablas..colegiosoficiales where organismo_redactor = @v_colegiooficial
	set @v_error = @@error
	if @v_error <> 0
	begin
		RETURN 8			/* ERROR al leer el colegio*/
	end
end
ELSE
BEGIN
	SET @V_COLOFIR = NULL
END
/*********************************************************************************************************************************************/	
				/*COMPRUEBA QUE NO EXISTA*/
/*********************************************************************************************************************************************/

SELECT @v_existe = count (codigo_plan) FROM FasesDeProyectos WHERE
Codigo_Plan = @V_PLAN AND referencia = @V_NUMOBRA AND subreferencia = @V_SUBREF AND  ao_ejecucion_obra = @V_AOPLAN
IF @v_existe > 0 
BEGIN  return 6
end



/*********************************************************************************************************************************************/	
				/*CALCULA EL NUEVO NUMERO DE PROYECTO*/
/*********************************************************************************************************************************************/
SELECT @v_numero_proyecto = MAX(NUMERO_PROYECTO) FROM Proyectos
GROUP BY CODIGO_MUNICIPIO,     AO_PROYECTO
HAVING (CODIGO_MUNICIPIO = @v_codigo_municipio)  AND (AO_PROYECTO = @v_ao_proyecto)
if @v_numero_proyecto is null 
begin
 set @v_numero_proyecto=0
end

set @v_numero_proyecto = @v_numero_proyecto + 1


set @v_num_proyecto=@v_numero_proyecto
set @v_error = @@error


if @v_error <> 0
begin
	return 1
end


BEGIN TRANSACTION
/*********************************************************************************************************************************************/	
				/* ALTA DE UN REGISTRO EN PROYECTOS*/
/*********************************************************************************************************************************************/	

INSERT INTO [Proyectos]
    ( CODIGO_MUNICIPIO, AO_PROYECTO, 
    NUMERO_PROYECTO,SERVICIO_GESTOR, den_proyecto, importe_proyecto, 
    organismo_redactor,servicio_redactor, autor, colegiooficial,subvencioneconredaccion,carretera, plazo, unidadplazo, nro_ejemplares, 
    revision, formula, formula2, formula3, formula4, 
    presu_gral_ejecucion_material, por_gastos_generales, 
    importe_gastos_generales, por_beneficio_industriales, 
    importe_beneficio_industriales, por_control_calidad, 
    importe_control_calidad, por_iva, iva, por_subcontrata, 
    subcontrata, honorarios_dir, honorarios_red, 
    fecha_entrega_proyecto, fecha_recepcion_proyecto, 
    fecha_remision_ayto, fecha_aprobacion_ayto, 
    fecha_pet_rectificacion, fecha_ent_rectificacion, 
    fecha_pet_reforma, fecha_ent_reforma, fecha_c_infor, 
    fecha_c_gob, fecha_pit_ref, fecha_eit_ref, fecha_ci_ref, 
    fecha_cg_ref, fecha_dto, nro_dto, observaciones, 
    estado_proyecto, Requiere_PlanSyS,Requiere_TramAmbiental,compartido)
    	
values
    (@V_CODIGO_MUNICIPIO,@V_AO_PROYECTO, 
    @V_NUMERO_PROYECTO, @V_SERVICIO_GESTOR, @V_den_proyecto,@V_importe_proyecto, 
    @V_organismo_redactor, @v_servred,@V_autor, @v_colofiR, @v_subvencion_economicaR, @V_carretera, @V_plazo, @V_unidad_plazo, @V_nro_ejemplares, 
    @V_revision, @V_formula, @V_formula2, @V_formula3, @V_formula4, 
    @V_presu_gral_ejecucion_material, @V_por_gastos_generales, 
    @V_importe_gastos_generales, @V_por_beneficio_industriales, 
    @V_importe_beneficio_industriales, @V_por_control_calidad, 
    @V_importe_control_calidad, @V_por_iva, @V_iva, @V_por_subcontrata, 
    @V_subcontrata, @V_honorarios_dir, @V_honorarios_red, 
    @V_fecha_entrega_proyecto, @V_fecha_recepcion_proyecto, 
    @V_fecha_remision_ayto, @V_fecha_aprobacion_ayto, 
    @V_fecha_pet_rectificacion, @V_fecha_ent_rectificacion, 
    @V_fecha_pet_reforma, @V_fecha_ent_reforma, @V_fecha_c_infor, 
   @V_fecha_c_gob, @V_fecha_pit_ref, @V_fecha_eit_ref, @V_fecha_ci_ref, 
   @V_fecha_cg_ref, @V_fecha_dto, @V_nro_dto, @V_observaciones, 
    'TPR', @V_Requiere_PlanSyS,@V_Requiere_TratMed,@v_compartido)

select * from proyectos where  (CODIGO_MUNICIPIO = @v_codigo_municipio and  AO_PROYECTO = @v_ao_proyecto and  NUMERO_PROYECTO = @v_numero_proyecto)

set @v_error = @@error


if @v_error <> 0
BEGIN
	ROLLBACK
	RETURN 2
END

/*********************************************************************************************************************************************/	
					/* ALTA DE LA FASE NUMERO 1*/
/*********************************************************************************************************************************************/	

INSERT INTO FasesDeProyectos 
 	 (MUNICIPIO, AO_PROYECTO, NUMERO_PROYECTO, NUMERO_FASE, AO_FASE, importe_fase,
	Codigo_Plan, referencia, subreferencia, ao_ejecucion_obra, carretera, plazo, unidadplazo, nro_ejemplares, revision, formula, formula2, formula3, formula4, 
	organismo_direccion, Servicio_direccion, director_tecnico_obra, colegiooficialdireccion,subvencionecondireccion,
  	 presu_gral_ejecucion_material, por_gastos_generales,  importe_gastos_generales, por_beneficio_industriales, 
  	 importe_beneficio_industriales, por_control_calidad,  importe_control_calidad, Por_iva, iva, por_subcontrata, 
 	 subcontrata, honorarios_dir, honorarios_red, fecha_rem_fase,   fecha_ent_fase, fecha_remision_ayto, fecha_aprobacion_ayto, 
	 fecha_pet_rectificacion, fecha_ent_rectificacion,  fecha_pet_reforma, fecha_ent_reforma,  fecha_pit_ref, fecha_eit_ref,
	 fecha_cg_ref,  fecha_ci_ref,  fecha_com_gob,   fecha_dto,  nro_dto,  fecha_com_inf,estado_fase, Requiere_PlanSyS)
VALUES 
	 (@V_CODIGO_MUNICIPIO,@V_AO_PROYECTO, 
  	 @V_NUMERO_PROYECTO, 1, @V_AO_PROYECTO,@V_importe_fase, @V_PLAN,@V_NUMOBRA,@V_SUBREF,@V_AOPLAN,
    	 @V_carretera, @V_plazo, @V_unidad_plazo, @V_nro_ejemplares, @V_revision, @V_formula, @V_formula2, @V_formula3, @V_formula4, 
	@V_organismo_redactor, @V_SERVRED,@V_autor,@v_colofir,@v_subvencion_economicad,
    	@V_presu_gral_ejecucion_material_fase, @V_por_gastos_generales_fase, @V_importe_gastos_generales_fase, @V_por_beneficio_industriales_fase, 
	@V_importe_beneficio_industriales_fase, @V_por_control_calidad_fase, @V_importe_control_calidad_fase, @V_por_iva_fase, @V_iva_fase, @V_por_subcontrata_fase, 
    	@V_subcontrata_fase, @V_honorarios_dir_fase, @V_honorarios_red_fase, @V_fecha_entrega_proyecto, @V_fecha_recepcion_proyecto, 
    	@V_fecha_remision_ayto, @V_fecha_aprobacion_ayto, @V_fecha_pet_rectificacion, @V_fecha_ent_rectificacion, 
    	@V_fecha_pet_reforma, @V_fecha_ent_reforma,  @V_fecha_pit_ref, @V_fecha_eit_ref, @V_fecha_cg_ref, @V_fecha_ci_ref, @V_fecha_c_gob,
	@V_fecha_dto, @V_nro_dto, @V_fecha_c_infor , 'TFA', @V_Requiere_PlanSyS)
	
 
set @v_error = @@error


if @v_error <> 0
BEGIN
	ROLLBACK
	RETURN 3
END

/*********************************************************************************************************************************************/	
					/*ACTUALIZA o BORRA LA  AYUDATECNICA*/
/*********************************************************************************************************************************************/	
if @v_subvencion_economicaR = 1 
begin
	set @SUBVENCION = 'SI'
end
else
begin
	SET @SUBVENCION = 'NO'
end
if @v_ayuda = 'SI'
begin
UPDATE Ayuda_Tecnica
	SET Pasado = 1,
	        SubvencionEconomicaR = @subvencion	
		
		
	WHERE	codigo_plan = @V_PLAN
	and 		numero_obra = @V_NUMOBRA
	and		subreferencia = @V_SUBREF
	and		ao_ejecucion = @V_AOPLAN
end
else
begin
	delete from ayuda_tecnica
	WHERE	codigo_plan = @V_PLAN
	and 		numero_obra = @V_NUMOBRA
	and		subreferencia = @V_SUBREF
	and		ao_ejecucion = @V_AOPLAN
end
	
set @v_error = @@error


if @v_error <> 0
BEGIN
	ROLLBACK
	RETURN 4
END

/*********************************************************************************************************************************************/	
				/*ACTUALIZA EL CAMPO codigo_estado_obra DE DATOSINICIODEOBRAS*/
/*********************************************************************************************************************************************/	
UPDATE DatosInicioDeObras
	SET Codigo_estado_obra = 'TPR',
	        PETICION_AYUDA_TEC= @V_AYUDA	
	       
	WHERE	codigo_plan = @V_PLAN
	and 		numero_obra = @V_NUMOBRA
	and		subreferencia = @V_SUBREF
	and		ao_ejecucion = @V_AOPLAN
	
set @v_error = @@error

if @v_error <> 0
BEGIN
	ROLLBACK
	RETURN 5
END

/*********************************************************************************************************************************************/	
				/*FINALIZA CORRECTAMENTE EL ALTA Y TERMINA LA TRANSACCION*/
/*********************************************************************************************************************************************/	


COMMIT
RETURN 0
GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Modificando Procedimiento [dbo].[PA_PROYECTOS_M_DatosFase]...';


GO
SET QUOTED_IDENTIFIER ON;

SET ANSI_NULLS OFF;


GO
ALTER PROCEDURE [PA_PROYECTOS_M_DatosFase] 
/*
ESTE PROCEDIMIENTO REALIZA EL ALTA, LA BAJA O MODIFICACION DE UN REGISTRO DE Fases de Proyectos
VALORES QUE DEVUELVE:

	20	TERMINACION CORRECTA
	21 	NO EXISTE EL PROYECTO SOBRE EL QUE SE QUIERE DAR MODIFICAR
	22 	NO EXISTE LA FASE A MODIFICAR
	23	ERROR EN LA ACTUALIZACION DEL REGISTRO DE FASE
	24	ERROR AL ACTUALIZAR ESTADO DEL PROYECTO
	25	ERROR AL ACTUALIZAR ESTADO OBRA
	26	ERROR AL ACTUALIZAR AYUDA_TECNICA
	27	ERROR EN LA ACTUALIZACION DE PENDIENTE CONTRATACION
	28	ERROR
	29	ERROR
	30	DATOS PLIEGO INCORRECTO
	31	ERROR AL ACTUALIZAR EL EXPEDIENTE DE CONTRATACIÓN

*/

/* VARIABLES PARA LA FASE
	CLAVE*/
@V_MUNICIPIO as smallint,
@V_AO_PROYECTO as smallint, 
@V_AO_FASE AS SMALLINT,
@V_NUMERO_PROYECTO AS SMALLINT,
@V_NUMERO_FASE AS SMALLINT ,
	/* FECHAS*/

@V_FECHA_REM_FASE as smalldatetime = null,
@V_fecha_ENT_FASE as smalldatetime = null,
@V_fecha_REMISION_ayto as smalldatetime = null,
@V_fecha_aprobacion_ayto as smalldatetime = null,
@V_fecha_REMISION_JUNTA as smalldatetime = null,
@V_fecha_VISADO_JUNTA as smalldatetime = null,
@V_fecha_PET_INF_TECNICO_CONTRATA as smalldatetime = null,
@V_fecha_ENT_INF_TECNICO_CONTRATA as smalldatetime = null,
@V_PLIEGO_CLAUSULAS_PARTICULARES as smalldatetime = null,
@V_fecha_PET_DESGLOSE as smalldatetime = null,
@V_fecha_ENT_DESGLOSE as smalldatetime = null,
@V_fecha_pet_rectificacion as smalldatetime = null,
@V_fecha_ent_rectificacion as smalldatetime = null,
@V_fecha_pet_reforma as smalldatetime = null,
@V_fecha_ent_reforma as smalldatetime = null,
@V_fecha_pit_ref as smalldatetime = null,
@V_fecha_eit_ref as smalldatetime = null,
@V_fecha_ci_ref as smalldatetime = null,
@V_fecha_cg_ref as smalldatetime = null,
@V_FECHA_PET_ACTUAL_PRECIOS AS smalldatetime = null, 
@V_FECHA_ENT_ACTUAL_PRECIOS AS smalldatetime = null,
@V_FECHA_ENVIO_FISCALIZACION AS smalldatetime = null,
@V_fecha_COM_INF as smalldatetime = null,
@V_fecha_COM_GOB as smalldatetime = null,
@V_FISCALIZACION AS smalldatetime = null,
@V_FECHA_REMISION_CONTRATACION AS smalldatetime = null,
@V_fecha_dto as smalldatetime = null, 
	/* IMPORTES*/

@V_IMPORTE_FASE as float,
@V_presu_gral_ejecucion_material as float,
@V_por_gastos_generales as float,
@V_importe_gastos_generales as float,
@V_por_beneficio_industriales as float,
@V_importe_beneficio_industriales as float,
@V_por_control_calidad as float,
@V_importe_control_calidad as float,
@v_por_iva as float,
@V_iva as float,
@V_por_subcontrata as float,
@V_subcontrata as float,
@V_honorarios_dir as float,
@V_honorarios_red as float,
@V_plan_ss as float,
@V_IVA_Honor_Direcc as bit,
@V_IVA_Honor_Redacc as bit,


/*RESTO DATOS*/
@V_servicio_gestor  AS SMALLINT,
@V_CODIGO_PLAN AS CHAR(7) ,
@V_REFERENCIA AS SMALLINT,
@V_SUBREFERENCIA AS TINYINT,
@V_AO_EJECUCION_OBRA AS SMALLINT ,
@V_CARRETERA AS CHAR(15) ,
@V_PLAZO AS smallint ,
@V_UNIDAD_PLAZO AS CHAR(1) = 'm',
@V_NRO_EJEMPLARES AS SMALLINT  ,
@V_REVISION AS char(2) ,
@V_FORMULA AS TINYINT ,
@V_FORMULA2 AS TINYINT,
@V_FORMULA3 AS TINYINT,
@V_FORMULA4 AS TINYINT,
@V_ORGANISMO_DIRECCION AS CHAR(2),

@V_SERVICIO_DIRECCION AS varchar(150)  ,
@V_DIRECTOR_TECNICO_OBRA AS VARCHAR(150),
@V_COLEGIOOFICIALDIRECCION  AS VARCHAR(80),
@V_SUBVENCIONECONDIRECCION AS BIT,
@V_nro_dto as smallint ,
@V_CLASE_EXP AS CHAR(2)=null ,
@V_TIPO_PROC AS CHAR(2)=null ,
@V_FORMA_CONT AS CHAR(2)=null ,
@V_Requiere_PlanSyS as bit,
@V_EnvioContratacion as integer output

AS

DECLARE @V_EXISTE            AS INTEGER
DECLARE @V_EXISTE_FASE AS INTEGER
DECLARE @V_EXISTE_PTE   AS INTEGER
declare @v_existe_pteactivo as integer
declare @v_ExpActivo as integer
declare @v_numorden as integer
DECLARE @V_ERROR            AS INTEGER
DECLARE @V_ESTADO_PROY1 AS CHAR(3)
DECLARE @V_ESTADO_OBRA1 AS CHAR(3)
DECLARE @V_ESTADO_FASE1 AS CHAR(3)
DECLARE @V_FORMAEJEC       AS CHAR(3) 
DECLARE @V_SED AS CHAR(2)
DECLARE @V_SER AS bit
DECLARE @V_SER2 AS CHAR(2)
DECLARE @V_ORGANISMO_REDACTOR AS CHAR(2)
DECLARE @V_SERVICIO_REDACTOR AS SMALLINT
declare @v_servicio_dir as smallint
declare @v_colofid as char(2)
DECLARE @V_AUTOR AS CHAR(60)
declare @v_PETAYUDA_NUEVA AS CHAR(2)
DECLARE @V_PETAYUDA_ANTES AS CHAR(2)
/*Diana*/
DECLARE @V_IMPORTE_A_CONTRATAR AS FLOAT
DECLARE @v_existe_ObrasMultiples AS INTEGER
DECLARE @v_existe_ExpedienteContratacion AS INTEGER
DECLARE @AoContratacion AS INTEGER
DECLARE @TipoExpediente AS CHAR(2)
DECLARE @NumExpediente AS INTEGER

/******************************************************************************************************************************************** */	
				/*COMPRUEBA SI EXISTE EL PROYECTO Y LA  FASE Y PTE CONTRATACION*/
/******************************************************************************************************************************************** */

set @v_existe_fase = 0
SET @V_EXISTE = 0
SET @V_EXISTE_PTE = 0
set @v_existe_pteactivo =0
set @v_enviocontratacion=0
set @v_ExpActivo=0

IF ((@v_CLASE_EXP<>null and RTRIM(@v_CLASE_EXP)<>'') and (@v_FORMA_CONT<>null and @v_FORMA_CONT<>'') and (@v_TIPO_PROC<>null and @v_TIPO_PROC<>''))
begin
/* COMPRUEBA EXISTE EL REGISTRO EN LA TABLA PLAZOS*/
select  @v_existe = count(1) from CONTRATA..PLAZOS 
WHERE (CodClaseExp = @v_CLASE_EXP) AND
(CodFormaContra = @v_FORMA_CONT) AND 
(CodProcedimiento = @v_TIPO_PROC) 
if @v_existe = 0 
begin
	RETURN 30				/*DATOS PLIEGO INCORRECTO*/
END
end

/* carga el servicio director y el colegio oficial de dirección si existen*/

if rtrim(@v_servicio_direccion) <> ' ' 
begin
	select @v_servicio_dir = codigo_dpto from tablas..tabladedepartamentos where denominacion = @v_servicio_direccion
	
	set @v_error = @@error
	if @v_error <> 0
	begin
		RETURN 28			/* ERROR al leer el servicio director*/
	end
end
if rtrim(@v_colegiooficialdireccion) <> ' '
begin
	select @v_colofid = codigo_redactor from tablas..colegiosoficiales where organismo_redactor = @v_colegiooficialdireccion
	set @v_error = @@error
	if @v_error <> 0
	begin
		RETURN 29			/* ERROR al leer el colegio*/
	end
end
ELSE
BEGIN
	SET @V_COLOFID = NULL
END
/* COMPRUEBA EXISTA EL PROYECTO*/
select  @v_existe = count(1) from proyectos 
WHERE (CODIGO_MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto) 
if @v_existe = 0 
begin
	RETURN 21				/*NO EXISTE EL PROYECTO DEL QUE SE QUIERE MODIFICAR LA FASE*/
END


/* COMPRUEBA QUE EXISTE LA FASE*/
select @v_existe_fase = count(1) from fasesdeproyectos
WHERE (MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto) AND 
(AO_FASE= @V_AO_FASE) AND
(NUMERO_FASE = @V_NUMERO_FASE)
IF @V_EXISTE_FASE= 0 
begin
	RETURN 22				/*NO EXISTE LA FASE QUE SE QUIERE MODIFICAR*/
end



/*COMPRUEBA SI EXISTE pendienteCONTRATACION  */
select @v_existe_PTE = count(1) ,@v_numorden=max(peticioncont) from  CONTRATA..PENDIENTECONTRATACIONOBRAS
where  planOBRA= @V_CODIGO_PLAN and
NUMobra = @V_REFERENCIA and
subref = @V_SUBREFERENCIA and
aoOBRA = @V_AO_EJECUCION_OBRA 



/*COMPRUEBA SI EXISTE pendienteCONTRATACION y esta pendiente de apertura*/
select @v_existe_PTEactivo = count(1)  from  CONTRATA..PENDIENTECONTRATACIONOBRAS
where  planOBRA= @V_CODIGO_PLAN and
NUMobra = @V_REFERENCIA and
subref = @V_SUBREFERENCIA and
aoOBRA = @V_AO_EJECUCION_OBRA and
estado is null

/* LEE EL ESTADO DE LA OBRA EN INICIO Y FORMA DE EJECUCION Y PETICION DE AYUDA*/
select @v_estado_obra1= codigo_estado_obra, @V_FORMAEJEC = FORMA_EJECUCION,@V_PETAYUDA_ANTES=PETICION_AYUDA_TEC  from datosiniciodeobras
where  codigo_plan= @V_CODIGO_PLAN and
numero_obra = @V_REFERENCIA and
subreferencia = @V_SUBREFERENCIA and
ao_ejecucion = @V_AO_EJECUCION_OBRA



/*COMPRUEBA SI EXISTE pendienteCONTRATACION y el estado es diferente de DES y  RES y tiene expediente iniciado */
select @v_ExpActivo = count(1)  from  CONTRATA..PENDIENTECONTRATACIONOBRAS
where  planOBRA= @V_CODIGO_PLAN and
NUMobra = @V_REFERENCIA and
subref = @V_SUBREFERENCIA and
aoOBRA = @V_AO_EJECUCION_OBRA and
(estado is not null AND estado<>'DES' AND estado<>'RES')


/*LEE EL ESTADO DEL PROYECTO, REDACTOR Y AUTOR*/
SELECT @V_ESTADO_PROY1 = ESTADO_PROYECTO , @V_ORGANISMO_REDACTOR= ORGANISMO_REDACTOR, @V_SERVICIO_REDACTOR =SERVICIO_REDACTOR, @V_AUTOR=AUTOR,@v_ser=subvencioneconredaccion FROM PROYECTOS
WHERE (CODIGO_MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto)

/* LEE EL ESTADO DE LA FASE*/
SELECT @V_ESTADO_FASE1 =ESTADO_FASE FROM FASESDEPROYECTOS
WHERE (MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto) AND 
(AO_FASE= @V_AO_FASE) AND
(NUMERO_FASE = @V_NUMERO_FASE)

/* DIANA -- LEE EL IMPORTE A CONTRATAR*/
SELECT @V_IMPORTE_A_CONTRATAR = IMPORTE_A_CONTRATAR FROM IMPORTESDEOBRAS
WHERE (CODIGO_PLAN = @V_CODIGO_PLAN) AND 
(NUMERO_OBRA = @V_REFERENCIA) AND 
(SUBREFERENCIA = @V_SUBREFERENCIA) AND
(AO_EJECUCION = @V_AO_EJECUCION_OBRA)


/* ******************************************************************************************************************************************* */	
/*  COMPROBACIONES ANTERIORES A INICIAR LA TRANSACCION (PARA MODIFICACIONES)                  	   */
/* ******************************************************************************************************************************************* */

/* ACTUALIZACION DE LOS ESTADOS*/
IF (@V_ESTADO_OBRA1 = 'DES' ) AND (@V_FORMAEJEC = 'DIP')  AND @V_FECHA_REMISION_CONTRATACION <> '  '
BEGIN

	SET @V_ESTADO_FASE1='TE'
	SET @V_ESTADO_PROY1='TE'
	SET @V_ESTADO_OBRA1='PA'
END
IF (@V_ESTADO_OBRA1 = 'TPR' OR @V_ESTADO_OBRA1 = 'PA') AND (@V_FORMAEJEC = 'DIP')  AND @V_FECHA_REMISION_CONTRATACION <> '  '
BEGIN

	SET @V_ESTADO_FASE1='TE'
	SET @V_ESTADO_PROY1='TE'
	SET @V_ESTADO_OBRA1='PA'
END
IF (@V_ESTADO_OBRA1 = 'TPR' OR @V_ESTADO_OBRA1 = 'PA') AND (@V_FORMAEJEC = 'DIP') AND @V_FECHA_REMISION_CONTRATACION is null
BEGIN
	
	SET @V_ESTADO_FASE1= 'TFA'
	SET @V_ESTADO_PROY1='TPR'
	SET @V_ESTADO_OBRA1='TPR'
END
IF (@V_ESTADO_OBRA1 = 'TPR' OR @V_ESTADO_OBRA1 = 'PA') AND @V_FORMAEJEC <>  'DIP'  AND (@V_FECHA_COM_GOB <> '  ' OR  @V_FECHA_DTO <>'  ')
BEGIN

	SET @V_ESTADO_FASE1= 'TE'
	SET @V_ESTADO_PROY1='TE'
	SET @V_ESTADO_OBRA1='PA'
END	
IF (@V_ESTADO_OBRA1 = 'TPR' OR @V_ESTADO_OBRA1 = 'PA') AND @V_FORMAEJEC <>  'DIP'  AND (@V_FECHA_COM_GOB = NULL AND   @V_FECHA_DTO = NULL)
BEGIN

	SET @V_ESTADO_FASE1= 'TFA'
	SET @V_ESTADO_PROY1='TPR'
	SET @V_ESTADO_OBRA1='TPR'
END	
/* ******************************************************************************************************************************************* */	
/*   COMPRUEBA EL NUEVO VALOR DE PETICION DE AYUDA TECNICA */
/* ******************************************************************************************************************************************* */	
set @v_petayuda_nueva = 'NO'
IF @V_ORGANISMO_REDACTOR = 'DP' OR @V_ORGANISMO_DIRECCION='DP' OR @V_SUBVENCIONECONDIRECCION <> 0  or @v_ser <> 0 
begin
	set @v_petayuda_nueva='SI'
end

 /* ******************************************************************************************************************************************* */	
/*  EMPIEZA LA ACTUALIZACION                 	   */
/* ******************************************************************************************************************************************* */

/* ACTUALIZO EL REGISTRO DE FASE*/
BEGIN TRANSACTION
UPDATE FASESDEPROYECTOS
SET
Servicio_gestor=@V_servicio_gestor,  
CODIGO_PLAN=@V_CODIGO_PLAN,
REFERENCIA =@V_REFERENCIA,
SUBREFERENCIA  =@V_SUBREFERENCIA,
AO_EJECUCION_OBRA =@V_AO_EJECUCION_OBRA,
CARRETERA =@V_CARRETERA,
PLAZO = @V_PLAZO,
UNIDADPLAZO = @V_UNIDAD_PLAZO,
NRO_EJEMPLARES = @V_NRO_EJEMPLARES,

REVISION  = @V_REVISION,
FORMULA = @V_FORMULA,
FORMULA2 = @V_FORMULA2,
FORMULA3 = @V_FORMULA3,
FORMULA4 = @V_FORMULA4,
ORGANISMO_DIRECCION = @V_ORGANISMO_DIRECCION,
SERVICIO_DIRECCION  = @V_SERVICIO_DIR,
DIRECTOR_TECNICO_OBRA  = @V_DIRECTOR_TECNICO_OBRA,
COLEGIOOFICIALDIRECCION   = @V_COLOFID,
SUBVENCIONECONDIRECCION = @V_SUBVENCIONECONDIRECCION,
nro_dto  = @V_nro_dto,
estado_FASE  = @V_ESTADO_FASE1,
CLASE_EXP = @V_CLASE_EXP,
TIPO_PROC   = @V_TIPO_PROC,
FORMA_CONT    = @V_FORMA_CONT,
Requiere_PlanSyS     = @V_Requiere_PlanSyS,

/*IMPORTES*/

IMPORTE_FASE  = @V_IMPORTE_FASE ,
presu_gral_ejecucion_material  = @V_presu_gral_ejecucion_material,
por_gastos_generales  = @V_por_gastos_generales,
importe_gastos_generales  = @V_importe_gastos_generales,
por_beneficio_industriales  = @V_por_beneficio_industriales,
importe_beneficio_industriales  = @V_importe_beneficio_industriales,
por_control_calidad  = @V_por_control_calidad,
importe_control_calidad  = @V_importe_control_calidad,
por_iva =@V_por_iva,
Iva  =@V_iva,
por_subcontrata  = @V_por_subcontrata,
subcontrata  = @V_subcontrata,
honorarios_dir  = @V_honorarios_dir,
honorarios_red  = @V_honorarios_red,
ImportePlanSyS = @V_plan_ss,
HD_ExcluidoIVA = @V_IVA_Honor_Direcc,
HR_ExcluidoIVA = @V_IVA_Honor_Redacc,

/* FECHAS*/

FECHA_REM_FASE  = @V_FECHA_REM_FASE,
fecha_ENT_FASE = @V_fecha_ENT_FASE,
fecha_REMISION_ayto  =  @V_fecha_REMISION_ayto,
fecha_aprobacion_ayto  =  @V_fecha_aprobacion_ayto,
fecha_REMISION_JUNTA  =  @V_fecha_REMISION_JUNTA,
FECHA_VISADO_JUNTA =   @V_FECHA_VISADO_JUNTA,
fecha_PET_INF_TECNICO_CONTRATA  =   @V_fecha_PET_INF_TECNICO_CONTRATA,
fecha_ENT_INF_TECNICO_CONTRATA   =   @V_fecha_ENT_INF_TECNICO_CONTRATA,
PLIEGO_CLAUSULAS_PARTICULARES   =   @V_PLIEGO_CLAUSULAS_PARTICULARES,
fecha_PET_DESGLOSE  =   @V_fecha_PET_DESGLOSE,
fecha_ENT_DESGLOSE   =   @V_fecha_ENT_DESGLOSE,
fecha_pet_rectificacion   =  @V_fecha_pet_rectificacion,
fecha_ent_rectificacion   =   @V_fecha_ent_rectificacion,
fecha_pet_reforma   =   @V_fecha_pet_reforma,
fecha_ent_reforma  =    @V_fecha_ent_reforma,
fecha_pit_ref   =   @V_fecha_pit_ref,
fecha_eit_ref   =   @V_fecha_eit_ref,
fecha_ci_ref    =   @V_fecha_ci_ref,
fecha_cg_ref   =   @V_fecha_cg_ref,
FECHA_PET_ACTUAL_PRECIOS   =  @V_FECHA_PET_ACTUAL_PRECIOS,
FECHA_ENT_ACTUAL_PRECIOS   =   @V_FECHA_ENT_ACTUAL_PRECIOS,
FECHA_ENVIO_FISCALIZACION  = @V_FECHA_ENVIO_FISCALIZACION,
fecha_COM_INF   = @V_fecha_COM_INF,
fecha_COM_GOB  =  @V_fecha_COM_GOB,
fecha_FISCALIZACION  =   @V_FISCALIZACION,
FECHA_REMISION_CONTRATACION   =  @V_FECHA_REMISION_CONTRATACION,
fecha_dto   =   @V_fecha_dto

WHERE (MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto) AND 
(AO_FASE= @V_AO_FASE) AND
(NUMERO_FASE = @V_NUMERO_FASE)
set @v_error = @@error
if @v_error <> 0
begin
	ROLLBACK
	RETURN 23			/* ERROR EN LA ACTUALIZACION DEL REGISTRO DE FASE*/
end

/* ACTUALIZO EL ESTADO DE PROYECTO*/
UPDATE PROYECTOS
SET
ESTADO_PROYECTO=@V_ESTADO_PROY1
WHERE (codigo_MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto) 
set @v_error = @@error
if @v_error <> 0
begin 
	ROLLBACK	
	return 24		/*ERROR AL ACTUALIZAR ESTADO DEL PROYECTO*/			
end

/* ACTUALIZA ESTADO OBRA y peticion ayuda tecnica*/

UPDATE DATOSINICIODEOBRAS
SET
CODIGO_ESTADO_obra=@V_ESTADO_OBRA1,
peticion_ayuda_tec=@v_petayuda_nueva
where  codigo_plan= @V_CODIGO_PLAN and
numero_obra = @V_REFERENCIA and
subreferencia = @V_SUBREFERENCIA and
ao_ejecucion = @V_AO_EJECUCION_OBRA
set @v_error = @@error
if @v_error <> 0
begin 
	ROLLBACK	
	return 25		/*ERROR AL ACTUALIZAR ESTADO OBRA*/			
end

/*ACTUALIZO LA AYUDA TECNICA POR SI HUBO CAMBIOS*/
IF @V_SUBVENCIONECONDIRECCION = 0 
BEGIN
	SET @V_SED=null
END
ELSE
BEGIN
	SET @V_SED='SI'
END
IF @V_SER = 0 
BEGIN
	SET @V_SER2=null
END
ELSE
BEGIN
	SET @V_SER2='SI'
END
if @v_petayuda_nueva='SI' and @v_petayuda_antes= 'SI'
begin 
	UPDATE AYUDA_TECNICA
	SET
	DEPARTAMENTO_DIRECCION= @V_SERVICIO_DIR,
	DPTO_REDACTOR=@V_SERVICIO_REDACTOR,
	SUBVENCIONECONOMICAR = @V_SER2,
	SUBVENCIONECONOMICAD = @V_SED,
	PASADO=1
	where  codigo_plan= @V_CODIGO_PLAN and
	numero_obra = @V_REFERENCIA and
	subreferencia = @V_SUBREFERENCIA and
	ao_ejecucion = @V_AO_EJECUCION_OBRA
	set @v_error = @@error
	if @v_error <> 0	begin 
		ROLLBACK	
		return 26		/*ERROR AL ACTUALIZAR AYUDA_TECNICA*/			
	end
end
if @v_petayuda_nueva = 'NO' and @v_petayuda_antes = 'SI'
begin
	delete from ayuda_tecnica
	where  codigo_plan= @V_CODIGO_PLAN and
	numero_obra = @V_REFERENCIA and
	subreferencia = @V_SUBREFERENCIA and
	ao_ejecucion = @V_AO_EJECUCION_OBRA
	set @v_error = @@error
	if @v_error <> 0	begin 
		ROLLBACK	
		return 26		/*ERROR AL ACTUALIZAR AYUDA_TECNICA*/			
	end
end
if @v_petayuda_nueva = 'SI' and @v_petayuda_antes = 'NO'
begin
	INSERT INTO ayuda_tecnica
		   (CODIGO_PLAN,NUMERO_OBRA,SUBREFERENCIA,AO_EJECUCION,	departamento, codigo_municipio,ao_proyecto,numero_proyecto,DPTO_REDACTOR,DEPARTAMENTO_DIRECCION,PASADO,SUBVENCIONECONOMICAR,SUBVENCIONECONOMICAD)
	VALUES (@V_CODIGO_PLAN, @V_REFERENCIA,@V_SUBREFERENCIA,@V_AO_EJECUCION_OBRA,0,0,0,0,@V_SERVICIO_REDACTOR,@V_SERVICIO_DIR,1,@V_SER2,@V_SED)
	set @v_error = @@error
	if @v_error <> 0	begin 
		ROLLBACK	
		return 26		/*ERROR AL ACTUALIZAR AYUDA_TECNICA*/			
	end
end
/* GRABA PENDIENTE CONTRATACION O ACTUALIZA REGISTRO EXISTENTE*/
set @v_enviocontratacion=0  
if @V_FORMAEJEC <> 'DIP' OR ( @V_FECHA_REMISION_CONTRATACION is null OR RTRIM(@V_FECHA_REMISION_CONTRATACION)='')
begin
	commit
	return 20
end

if @v_ExpActivo>0
begin
	commit
	return 20
end

if @v_estado_obra1 = 'TPR' and @V_EXISTE_PTEACTIVO > 0
BEGIN
	DELETE CONTRATA..PENDIENTECONTRATACIONOBRAS
	WHERE
	planOBRA= @V_CODIGO_PLAN and
	numobra = @V_REFERENCIA and
	subref = @V_SUBREFERENCIA and
	AOOBRA = @V_AO_EJECUCION_OBRA AND
	TIPOEXP='OB' and 
	peticioncont=@v_numorden
	set @v_enviocontratacion=3
	COMMIT
	RETURN 20
END

--if @v_estado_obra1 <> 'PA'

IF @V_EXISTE_PTEactivo  > 0 
BEGIN
	UPDATE CONTRATA..PENDIENTECONTRATACIONOBRAS
	SET
	ampliacion=0,
	--servicio_redactor=@v_servicio_redactor,
	--autor=@v_autor,
	servicio_gestor=@v_servicio_gestor,
	--servicio_direccion=@v_servicio_dir,
	codclaseexp= @V_CLASE_EXP,
	codprocedimiento = @V_TIPO_PROC    ,
	CodFormaContrata    = @V_FORMA_CONT,
	--importelicitacion=@v_importe_fase,
	--Diana
	importelicitacion=@V_IMPORTE_A_CONTRATAR,
	fecharecepcion=getdate (),
	esTADO=NULL,
	PorcentajeIVA = @v_por_iva
	WHERE
	planOBRA= @V_CODIGO_PLAN and
	numobra = @V_REFERENCIA and
	subref = @V_SUBREFERENCIA and
	AOOBRA = @V_AO_EJECUCION_OBRA AND
	TIPOEXP='OB' and 
	peticioncont=@v_numorden
	set @v_enviocontratacion=1
end
if @v_existe_pteactivo = 0 
begin
	IF @V_EXISTE_PTE = 0 
	BEGIN
		SET @V_NUMORDEN=0
	END
	set @v_numorden=@v_numorden +1
	/*INSERT INTO contrata..pendientecontratacionobras
 		 (tipoexp,peticioncont,planobra,numobra,subref,aoobra,ampliacion,servicio_redactor,servicio_gestor,servicio_direccion,
		autor,importelicitacion,codclaseexp,codprocedimiento,codformacontrata,fecharecepcion,estado)	
	VALUES ('OB',@v_numorden,@V_CODIGO_PLAN, @V_REFERENCIA , @V_SUBREFERENCIA, @V_AO_EJECUCION_OBRA ,0,@V_SERVICIO_REDACTOR,
	@V_SERVICIO_GESTOR,@V_SERVICIO_DIR,@V_AUTOR,@V_IMPORTE_FASE,@V_CLASE_EXP, @V_TIPO_PROC   ,
	@V_FORMA_CONT,getdate (),NULL)*/

	INSERT INTO contrata..pendientecontratacionobras
 		 (tipoexp,peticioncont,planobra,numobra,subref,aoobra,ampliacion,servicio_gestor,
		importelicitacion,codclaseexp,codprocedimiento,codformacontrata,fecharecepcion,estado,PorcentajeIVA)	
	VALUES ('OB',@v_numorden,@V_CODIGO_PLAN, @V_REFERENCIA , @V_SUBREFERENCIA, @V_AO_EJECUCION_OBRA ,0,
	@V_SERVICIO_GESTOR,@V_IMPORTE_A_CONTRATAR,@V_CLASE_EXP, @V_TIPO_PROC   ,
	@V_FORMA_CONT,getdate (),NULL, @v_por_iva)
	set @v_enviocontratacion=2
END
set @v_error = @@error
if @v_error <> 0
begin	ROLLBACK	
	RETURN 27			/*ERROR EN PENDIENTE DE CONTRATACION*/
end


--Diana--
/*COMPRUEBA SI EXISTE el registro en ObrasMultiples*/
/*select @v_existe_ObrasMultiples = count(1)  from  CONTRATA..OBRASMULTIPLES
where  	planOBRA= @V_CODIGO_PLAN and
	numobra = @V_REFERENCIA and
	subref = @V_SUBREFERENCIA and
	AOOBRA = @V_AO_EJECUCION_OBRA

IF @v_existe_ObrasMultiples>0 --Rellenamos las variables que nos faltan para leer de la tabla ExpedientesdeContratacion
begin
select @AoContratacion = AoContratacion, @TipoExpediente = TipoExpediente, @NumExpediente = NumExpediente  from  CONTRATA..OBRASMULTIPLES
where  	planOBRA= @V_CODIGO_PLAN and
	numobra = @V_REFERENCIA and
	subref = @V_SUBREFERENCIA and
	AOOBRA = @V_AO_EJECUCION_OBRA
end
*/

/*COMPRUEBA SI EXISTE el registro en ExpedienteContratacion*/
/*select @v_existe_ExpedienteContratacion = count(1)  from  CONTRATA..EXPEDIENTECONTRATACION
where  	AoContratacion = @AoContratacion and
	TipoExpediente = @TipoExpediente and
	NumExpediente = @NumExpediente

if @v_existe_ExpedienteContratacion>0  --Trasladamos el valor de Revisión de Precios
begin
	UPDATE CONTRATA..EXPEDIENTECONTRATACION
	SET	RevisionPrecios = @V_REVISION
	where  	AoContratacion = @AoContratacion and
		TipoExpediente = @TipoExpediente and
		NumExpediente = @NumExpediente
end
set @v_error = @@error
if @v_error <> 0
begin	ROLLBACK	
	RETURN 31			--ERROR EN EXPEDIENTES DE CONTRATACIÓN
end*/

COMMIT
RETURN 20			/*PROCESO REALIZADO CORRECTAMENTE*/
GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Modificando Procedimiento [dbo].[PA_R_DatosListados]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
ALTER PROCEDURE [dbo].[PA_R_DatosListados] 
@Plan as char(7),
@Num as smallint,
@SubRef as tinyint,
@AoEje as smallint

AS

/*Lee los datos necesarios para los listados del módulo de Inicio de Obras*/
SELECT dbo.DatosInicioDeObras.Codigo_Plan, 
    dbo.DatosInicioDeObras.numero_obra, 
    dbo.DatosInicioDeObras.subreferencia, 
    dbo.DatosInicioDeObras.ao_ejecucion, 
    dbo.DatosInicioDeObras.municipio, 
   Tablas.dbo.TablaDeMunicipios.nombre_municipio, 
	Tablas.dbo.TablaDeMunicipios.sede,
    dbo.DatosInicioDeObras.peticion_ayuda_tec, 
    DR.DENOMINACION, dbo.DatosInicioDeObras.nombre_obra1, 
    dbo.DatosInicioDeObras.nombre_obra2, 
    dbo.DatosInicioDeObras.nombre_obra3, 
    dbo.DatosInicioDeObras.peticion_ayuda_tec, 
    dbo.DatosInicioDeObras.fecha_rem_pet_ayuda, 
    dbo.DatosInicioDeObras.CompApAyto, 
    dbo.ImportesDeObras.importe_aprobado, 
    Tablas.dbo.Planes.denominacion_plan, 
    Tablas.dbo.Planes.codigo_depar_reservado, 
    Tablas.dbo.FormasDeEjecucion.DEN_CONTRATA, 
    dbo.Ayuda_Tecnica.dpto_redactor, 
    DD.DENOMINACION AS DenominacionDir, 
    DD.ARTICULO AS ArticuloDir, DD.JefeDpto AS JefeDptoDir, 
    DD.Jefatura AS JefaturaDir, 
    dbo.Ayuda_Tecnica.departamento_direccion, DRES.ARTICULO, 
    DRES.JefeDpto, DRES.Jefatura, DRES.JefaturaCompleta, 
    dbo.FasesDeProyectos.director_tecnico_obra, 
    dbo.DatosInicioDeObras.disponibilidad_terreno, 
    dbo.DatosInicioDeObras.fecha_pet_acta_replanteo, 
    dbo.DatosInicioDeObras.forma_ejecucion, 
    dbo.FasesDeProyectos.ColegioOficialDireccion, 
    Tablas.dbo.ColegiosOficiales.organismo_redactor, 
    dbo.FasesDeProyectos.organismo_direccion, 
    dbo.Proyectos.Servicio_Gestor, 
    dbo.Proyectos.Servicio_redactor, 
	dbo.Proyectos.carretera,
    Tablas.dbo.OrganismosRed_Dir.organismo_redactor AS OrgRedactor,
     Contrata.dbo.TbFormasContratacion.FormaContratacion, 
    dbo.FasesDeProyectos.forma_cont,
    dbo.proyectos.fecha_remision_ayto
FROM Tablas.dbo.ColegiosOficiales RIGHT OUTER JOIN
    dbo.FasesDeProyectos INNER JOIN
    dbo.Proyectos ON 
    dbo.FasesDeProyectos.MUNICIPIO = dbo.Proyectos.CODIGO_MUNICIPIO
     AND 
    dbo.FasesDeProyectos.AO_PROYECTO = dbo.Proyectos.AO_PROYECTO
     AND 
    dbo.FasesDeProyectos.NUMERO_PROYECTO = dbo.Proyectos.NUMERO_PROYECTO
     LEFT OUTER JOIN
    Contrata.dbo.TbFormasContratacion ON 
    dbo.FasesDeProyectos.forma_cont = Contrata.dbo.TbFormasContratacion.CodFormaContra
     LEFT OUTER JOIN
    Tablas.dbo.OrganismosRed_Dir ON 
    dbo.Proyectos.organismo_redactor = Tablas.dbo.OrganismosRed_Dir.codigo_redactor
     ON 
    Tablas.dbo.ColegiosOficiales.codigo_redactor = dbo.FasesDeProyectos.ColegioOficialDireccion
     RIGHT OUTER JOIN
    Tablas.dbo.TablaDeDepartamentos DRES INNER JOIN
    Tablas.dbo.Planes INNER JOIN
    dbo.DatosInicioDeObras ON 
    Tablas.dbo.Planes.codigo_plan = dbo.DatosInicioDeObras.Codigo_Plan
     ON 
    DRES.CODIGO_DPTO = Tablas.dbo.Planes.codigo_depar_reservado
     ON 
    dbo.FasesDeProyectos.Codigo_Plan = dbo.DatosInicioDeObras.Codigo_Plan
     AND 
    dbo.FasesDeProyectos.referencia = dbo.DatosInicioDeObras.numero_obra
     AND 
    dbo.FasesDeProyectos.subreferencia = dbo.DatosInicioDeObras.subreferencia
     AND 
    dbo.FasesDeProyectos.ao_ejecucion_obra = dbo.DatosInicioDeObras.ao_ejecucion
     LEFT OUTER JOIN
    dbo.Ayuda_Tecnica ON 
    Tablas.dbo.Planes.codigo_depar_reservado <> dbo.Ayuda_Tecnica.dpto_redactor
     AND 
    dbo.DatosInicioDeObras.Codigo_Plan = dbo.Ayuda_Tecnica.Codigo_Plan
     AND 
    dbo.DatosInicioDeObras.numero_obra = dbo.Ayuda_Tecnica.numero_obra
     AND 
    dbo.DatosInicioDeObras.ao_ejecucion = dbo.Ayuda_Tecnica.ao_ejecucion
     AND 
    dbo.DatosInicioDeObras.subreferencia = dbo.Ayuda_Tecnica.subreferencia
     LEFT OUTER JOIN
    Tablas.dbo.TablaDeMunicipios ON 
    dbo.DatosInicioDeObras.municipio = Tablas.dbo.TablaDeMunicipios.codigo_municipio
     LEFT OUTER JOIN
    Tablas.dbo.FormasDeEjecucion ON 
    dbo.DatosInicioDeObras.forma_ejecucion = Tablas.dbo.FormasDeEjecucion.COD_CONTRATA
     LEFT OUTER JOIN
    dbo.ImportesDeObras ON 
    dbo.DatosInicioDeObras.Codigo_Plan = dbo.ImportesDeObras.Codigo_Plan
     AND 
    dbo.DatosInicioDeObras.numero_obra = dbo.ImportesDeObras.numero_obra
     AND 
    dbo.DatosInicioDeObras.subreferencia = dbo.ImportesDeObras.subreferencia
     AND 
    dbo.DatosInicioDeObras.ao_ejecucion = dbo.ImportesDeObras.ao_ejecucion
     LEFT OUTER JOIN
    Tablas.dbo.TablaDeDepartamentos DD ON 
    dbo.Ayuda_Tecnica.departamento_direccion = DD.CODIGO_DPTO
     LEFT OUTER JOIN
    Tablas.dbo.TablaDeDepartamentos DR ON 
    dbo.Ayuda_Tecnica.dpto_redactor = DR.CODIGO_DPTO
WHERE  dbo.DatosInicioDeObras.Codigo_Plan=RTRIM(@Plan) AND
    dbo.DatosInicioDeObras.numero_obra = rtrim(@Num) AND 
    dbo.DatosInicioDeObras.subreferencia=rtrim(@SubRef) AND
    dbo.DatosInicioDeObras.ao_ejecucion=rtrim(@AoEje) 
--AND  (dbo.DatosInicioDeObras.peticion_ayuda_tec = 'SI') 









/*SELECT dbo.DatosInicioDeObras.Codigo_Plan, 
   dbo.DatosInicioDeObras.numero_obra, 
    dbo.DatosInicioDeObras.subreferencia, 
    dbo.DatosInicioDeObras.ao_ejecucion, 
    dbo.DatosInicioDeObras.municipio, 
    dbo.DatosInicioDeObras.carretera, 
    Tablas.dbo.TablaDeMunicipios.nombre_municipio, 
    dbo.DatosInicioDeObras.peticion_ayuda_tec, 
    DR.DENOMINACION, dbo.DatosInicioDeObras.nombre_obra1, 
    dbo.DatosInicioDeObras.nombre_obra2, 
    dbo.DatosInicioDeObras.nombre_obra3, 
    dbo.DatosInicioDeObras.peticion_ayuda_tec, 
    dbo.DatosInicioDeObras.fecha_rem_pet_ayuda, 
    dbo.ImportesDeObras.importe_aprobado, 
  dbo.DatosInicioDeObras.fecha_rem_pet_ayuda, 
    Tablas.dbo.Planes.denominacion_plan, 
    Tablas.dbo.Planes.codigo_depar_reservado, 
    Tablas.dbo.FormasDeEjecucion.DEN_CONTRATA, 
    dbo.Ayuda_Tecnica.dpto_redactor, 
    DD.DENOMINACION AS DenominacionDir, 
    DD.ARTICULO AS ArticuloDir, DD.JefeDpto AS JefeDptoDir, 
    DD.Jefatura as JefaturaDir, dbo.Ayuda_Tecnica.departamento_direccion, 
    DRES.ARTICULO, DRES.JefeDpto, DRES.Jefatura, DRES.JefaturaCompleta, 
    dbo.FasesDeProyectos.director_tecnico_obra,  
    dbo.DatosInicioDeObras.disponibilidad_terreno,
    dbo.DatosInicioDeObras.fecha_pet_acta_replanteo,
   dbo.DatosInicioDeObras.forma_ejecucion,
dbo.FasesDeProyectos.ColegioOficialDireccion, 
    Tablas.dbo.ColegiosOficiales.organismo_redactor,
dbo.FasesDeProyectos.organismo_direccion
FROM Tablas.dbo.TablaDeMunicipios RIGHT OUTER JOIN
    dbo.FasesDeProyectos INNER JOIN
    Tablas.dbo.ColegiosOficiales ON 
    dbo.FasesDeProyectos.ColegioOficialDireccion = Tablas.dbo.ColegiosOficiales.codigo_redactor
     RIGHT OUTER JOIN
    Tablas.dbo.TablaDeDepartamentos DRES INNER JOIN
    Tablas.dbo.Planes INNER JOIN
    dbo.DatosInicioDeObras ON 
    Tablas.dbo.Planes.codigo_plan = dbo.DatosInicioDeObras.Codigo_Plan
     ON 
    DRES.CODIGO_DPTO = Tablas.dbo.Planes.codigo_depar_reservado
     ON 
    dbo.FasesDeProyectos.Codigo_Plan = dbo.DatosInicioDeObras.Codigo_Plan
     AND 
    dbo.FasesDeProyectos.referencia = dbo.DatosInicioDeObras.numero_obra
     AND 
    dbo.FasesDeProyectos.subreferencia = dbo.DatosInicioDeObras.subreferencia
     AND 
    dbo.FasesDeProyectos.ao_ejecucion_obra = dbo.DatosInicioDeObras.ao_ejecucion
     LEFT OUTER JOIN
    dbo.Ayuda_Tecnica ON 
    Tablas.dbo.Planes.codigo_depar_reservado <> dbo.Ayuda_Tecnica.dpto_redactor
     AND 
    dbo.DatosInicioDeObras.Codigo_Plan = dbo.Ayuda_Tecnica.Codigo_Plan
     AND 
    dbo.DatosInicioDeObras.numero_obra = dbo.Ayuda_Tecnica.numero_obra
     AND 
    dbo.DatosInicioDeObras.ao_ejecucion = dbo.Ayuda_Tecnica.ao_ejecucion
     AND 
    dbo.DatosInicioDeObras.subreferencia = dbo.Ayuda_Tecnica.subreferencia
     ON 
    Tablas.dbo.TablaDeMunicipios.codigo_municipio = dbo.DatosInicioDeObras.municipio
     LEFT OUTER JOIN
    Tablas.dbo.FormasDeEjecucion ON 
    dbo.DatosInicioDeObras.forma_ejecucion = Tablas.dbo.FormasDeEjecucion.COD_CONTRATA
     LEFT OUTER JOIN
    dbo.ImportesDeObras ON 
    dbo.DatosInicioDeObras.Codigo_Plan = dbo.ImportesDeObras.Codigo_Plan
     AND 
    dbo.DatosInicioDeObras.numero_obra = dbo.ImportesDeObras.numero_obra
     AND 
    dbo.DatosInicioDeObras.subreferencia = dbo.ImportesDeObras.subreferencia
     AND 
    dbo.DatosInicioDeObras.ao_ejecucion = dbo.ImportesDeObras.ao_ejecucion
     LEFT OUTER JOIN
    Tablas.dbo.TablaDeDepartamentos DD ON 
    dbo.Ayuda_Tecnica.departamento_direccion = DD.CODIGO_DPTO
     LEFT OUTER JOIN
    Tablas.dbo.TablaDeDepartamentos DR ON 
    dbo.Ayuda_Tecnica.dpto_redactor = DR.CODIGO_DPTO*/
GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Modificando Procedimiento [dbo].[PA_PROYECTOS_M_DatosFaseUltimo]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
ALTER PROCEDURE [PA_PROYECTOS_M_DatosFaseUltimo] 
/*
ESTE PROCEDIMIENTO REALIZA EL ALTA, LA BAJA O MODIFICACION DE UN REGISTRO DE Fases de Proyectos
VALORES QUE DEVUELVE:

	20	TERMINACION CORRECTA
	21 	NO EXISTE EL PROYECTO SOBRE EL QUE SE QUIERE DAR MODIFICAR
	22 	NO EXISTE LA FASE A MODIFICAR
	23	ERROR EN LA ACTUALIZACION DEL REGISTRO DE FASE
	24	ERROR AL ACTUALIZAR ESTADO DEL PROYECTO
	25	ERROR AL ACTUALIZAR ESTADO OBRA
	26	ERROR AL ACTUALIZAR AYUDA_TECNICA
	27	ERROR EN LA ACTUALIZACION DE PENDIENTE CONTRATACION
	28	ERROR
	29	ERROR
	30	DATOS PLIEGO INCORRECTO

*/

/* VARIABLES PARA LA FASE
	CLAVE*/
@V_MUNICIPIO as smallint,
@V_AO_PROYECTO as smallint, 
@V_AO_FASE AS SMALLINT,
@V_NUMERO_PROYECTO AS SMALLINT,
@V_NUMERO_FASE AS SMALLINT ,
	/* FECHAS*/

@V_FECHA_REM_FASE as smalldatetime = null,
@V_fecha_ENT_FASE as smalldatetime = null,
@V_fecha_REMISION_ayto as smalldatetime = null,
@V_fecha_aprobacion_ayto as smalldatetime = null,
@V_fecha_REMISION_JUNTA as smalldatetime = null,
@V_fecha_VISADO_JUNTA as smalldatetime = null,
@V_fecha_PET_INF_TECNICO_CONTRATA as smalldatetime = null,
@V_fecha_ENT_INF_TECNICO_CONTRATA as smalldatetime = null,
@V_PLIEGO_CLAUSULAS_PARTICULARES as smalldatetime = null,
@V_fecha_PET_DESGLOSE as smalldatetime = null,
@V_fecha_ENT_DESGLOSE as smalldatetime = null,
@V_fecha_pet_rectificacion as smalldatetime = null,
@V_fecha_ent_rectificacion as smalldatetime = null,
@V_fecha_pet_reforma as smalldatetime = null,
@V_fecha_ent_reforma as smalldatetime = null,
@V_fecha_pit_ref as smalldatetime = null,
@V_fecha_eit_ref as smalldatetime = null,
@V_fecha_ci_ref as smalldatetime = null,
@V_fecha_cg_ref as smalldatetime = null,
@V_FECHA_PET_ACTUAL_PRECIOS AS smalldatetime = null, 
@V_FECHA_ENT_ACTUAL_PRECIOS AS smalldatetime = null,
@V_FECHA_ENVIO_FISCALIZACION AS smalldatetime = null,
@V_fecha_COM_INF as smalldatetime = null,
@V_fecha_COM_GOB as smalldatetime = null,
@V_FISCALIZACION AS smalldatetime = null,
@V_FECHA_REMISION_CONTRATACION AS smalldatetime = null,
@V_fecha_dto as smalldatetime = null, 
	/* IMPORTES*/

@V_IMPORTE_FASE as float,
@V_presu_gral_ejecucion_material as float,
@V_por_gastos_generales as float,
@V_importe_gastos_generales as float,
@V_por_beneficio_industriales as float,
@V_importe_beneficio_industriales as float,
@V_por_control_calidad as float,
@V_importe_control_calidad as float,
@v_por_iva as float,
@V_iva as float,
@V_por_subcontrata as float,
@V_subcontrata as float,
@V_honorarios_dir as float,
@V_honorarios_red as float,
@V_plan_ss as float,
@V_IVA_Honor_Direcc as bit,
@V_IVA_Honor_Redacc as bit,


/*RESTO DATOS*/
@V_servicio_gestor  AS SMALLINT,
@V_CODIGO_PLAN AS CHAR(7) ,
@V_REFERENCIA AS SMALLINT,
@V_SUBREFERENCIA AS TINYINT,
@V_AO_EJECUCION_OBRA AS SMALLINT ,
@V_CARRETERA AS CHAR(5) ,
@V_PLAZO AS smallint ,
@V_UNIDAD_PLAZO AS CHAR(1) = 'm',
@V_NRO_EJEMPLARES AS SMALLINT  ,
@V_REVISION AS char(2) ,
@V_FORMULA AS TINYINT ,
@V_FORMULA2 AS TINYINT,
@V_FORMULA3 AS TINYINT,
@V_FORMULA4 AS TINYINT,
@V_ORGANISMO_DIRECCION AS CHAR(2),

@V_SERVICIO_DIRECCION AS varchar(150)  ,
@V_DIRECTOR_TECNICO_OBRA AS VARCHAR(150),
@V_COLEGIOOFICIALDIRECCION  AS VARCHAR(80),
@V_SUBVENCIONECONDIRECCION AS BIT,
@V_nro_dto as smallint ,
@V_CLASE_EXP AS CHAR(2)=null ,
@V_TIPO_PROC AS CHAR(2)=null ,
@V_FORMA_CONT AS CHAR(2)=null ,
@V_Requiere_PlanSyS as bit,
@V_EnvioContratacion as integer output

AS

DECLARE @V_EXISTE            AS INTEGER
DECLARE @V_EXISTE_FASE AS INTEGER
DECLARE @V_EXISTE_PTE   AS INTEGER
declare @v_existe_pteactivo as integer
declare @v_ExpActivo as integer
declare @v_numorden as integer
DECLARE @V_ERROR            AS INTEGER
DECLARE @V_ESTADO_PROY1 AS CHAR(3)
DECLARE @V_ESTADO_OBRA1 AS CHAR(3)
DECLARE @V_ESTADO_FASE1 AS CHAR(3)
DECLARE @V_FORMAEJEC       AS CHAR(3) 
DECLARE @V_SED AS CHAR(2)
DECLARE @V_SER AS bit
DECLARE @V_SER2 AS CHAR(2)
DECLARE @V_ORGANISMO_REDACTOR AS CHAR(2)
DECLARE @V_SERVICIO_REDACTOR AS SMALLINT
declare @v_servicio_dir as smallint
declare @v_colofid as char(2)
DECLARE @V_AUTOR AS CHAR(60)
declare @v_PETAYUDA_NUEVA AS CHAR(2)
DECLARE @V_PETAYUDA_ANTES AS CHAR(2)
/******************************************************************************************************************************************** */	
				/*COMPRUEBA SI EXISTE EL PROYECTO Y LA  FASE Y PTE CONTRATACION*/
/******************************************************************************************************************************************** */

set @v_existe_fase = 0
SET @V_EXISTE = 0
SET @V_EXISTE_PTE = 0
set @v_existe_pteactivo =0
set @v_enviocontratacion=0
set @v_ExpActivo=0

IF @v_CLASE_EXP<>null and @v_FORMA_CONT<>null and @v_TIPO_PROC<>null
begin
/* COMPRUEBA EXISTE EL REGISTRO EN LA TABLA PLAZOS*/
select  @v_existe = count(1) from CONTRATA..PLAZOS 
WHERE (CodClaseExp = @v_CLASE_EXP) AND
(CodFormaContra = @v_FORMA_CONT) AND 
(CodProcedimiento = @v_TIPO_PROC) 
if @v_existe = 0 
begin
	RETURN 30				/*DATOS PLIEGO INCORRECTO*/
END
end

/* carga el servicio director y el colegio oficial de dirección si existen*/

if rtrim(@v_servicio_direccion) <> ' ' 
begin
	select @v_servicio_dir = codigo_dpto from tablas..tabladedepartamentos where denominacion = @v_servicio_direccion
	
	set @v_error = @@error
	if @v_error <> 0
	begin
		RETURN 28			/* ERROR al leer el servicio director*/
	end
end
if rtrim(@v_colegiooficialdireccion) <> ' '
begin
	select @v_colofid = codigo_redactor from tablas..colegiosoficiales where organismo_redactor = @v_colegiooficialdireccion
	set @v_error = @@error
	if @v_error <> 0
	begin
		RETURN 29			/* ERROR al leer el colegio*/
	end
end
ELSE
BEGIN
	SET @V_COLOFID = NULL
END
/* COMPRUEBA EXISTA EL PROYECTO*/
select  @v_existe = count(1) from proyectos 
WHERE (CODIGO_MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto) 
if @v_existe = 0 
begin
	RETURN 21				/*NO EXISTE EL PROYECTO DEL QUE SE QUIERE MODIFICAR LA FASE*/
END


/* COMPRUEBA QUE EXISTE LA FASE*/
select @v_existe_fase = count(1) from fasesdeproyectos
WHERE (MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto) AND 
(AO_FASE= @V_AO_FASE) AND
(NUMERO_FASE = @V_NUMERO_FASE)
IF @V_EXISTE_FASE= 0 
begin
	RETURN 22				/*NO EXISTE LA FASE QUE SE QUIERE MODIFICAR*/
end



/*COMPRUEBA SI EXISTE pendienteCONTRATACION  */
select @v_existe_PTE = count(1) ,@v_numorden=max(peticioncont) from  CONTRATA..PENDIENTECONTRATACIONOBRAS
where  planOBRA= @V_CODIGO_PLAN and
NUMobra = @V_REFERENCIA and
subref = @V_SUBREFERENCIA and
aoOBRA = @V_AO_EJECUCION_OBRA 



/*COMPRUEBA SI EXISTE pendienteCONTRATACION y esta pendiente de apertura*/
select @v_existe_PTEactivo = count(1)  from  CONTRATA..PENDIENTECONTRATACIONOBRAS
where  planOBRA= @V_CODIGO_PLAN and
NUMobra = @V_REFERENCIA and
subref = @V_SUBREFERENCIA and
aoOBRA = @V_AO_EJECUCION_OBRA and
estado is null

/* LEE EL ESTADO DE LA OBRA EN INICIO Y FORMA DE EJECUCION Y PETICION DE AYUDA*/
select @v_estado_obra1= codigo_estado_obra, @V_FORMAEJEC = FORMA_EJECUCION,@V_PETAYUDA_ANTES=PETICION_AYUDA_TEC  from datosiniciodeobras
where  codigo_plan= @V_CODIGO_PLAN and
numero_obra = @V_REFERENCIA and
subreferencia = @V_SUBREFERENCIA and
ao_ejecucion = @V_AO_EJECUCION_OBRA



/*COMPRUEBA SI EXISTE pendienteCONTRATACION y el estado es diferente de DES y  RES y tiene expediente iniciado */
select @v_ExpActivo = count(1)  from  CONTRATA..PENDIENTECONTRATACIONOBRAS
where  planOBRA= @V_CODIGO_PLAN and
NUMobra = @V_REFERENCIA and
subref = @V_SUBREFERENCIA and
aoOBRA = @V_AO_EJECUCION_OBRA and
(estado is not null AND estado<>'DES' AND estado<>'RES')


/*LEE EL ESTADO DEL PROYECTO, REDACTOR Y AUTOR*/
SELECT @V_ESTADO_PROY1 = ESTADO_PROYECTO , @V_ORGANISMO_REDACTOR= ORGANISMO_REDACTOR, @V_SERVICIO_REDACTOR =SERVICIO_REDACTOR, @V_AUTOR=AUTOR,@v_ser=subvencioneconredaccion FROM PROYECTOS
WHERE (CODIGO_MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto)

/* LEE EL ESTADO DE LA FASE*/
SELECT @V_ESTADO_FASE1 =ESTADO_FASE FROM FASESDEPROYECTOS
WHERE (MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto) AND 
(AO_FASE= @V_AO_FASE) AND
(NUMERO_FASE = @V_NUMERO_FASE)

/* ******************************************************************************************************************************************* */	
/*  COMPROBACIONES ANTERIORES A INICIAR LA TRANSACCION (PARA MODIFICACIONES)                  	   */
/* ******************************************************************************************************************************************* */

/* ACTUALIZACION DE LOS ESTADOS*/
IF (@V_ESTADO_OBRA1 = 'DES' ) AND (@V_FORMAEJEC = 'DIP')  AND @V_FECHA_REMISION_CONTRATACION <> '  '
BEGIN

	SET @V_ESTADO_FASE1='TE'
	SET @V_ESTADO_PROY1='TE'
	SET @V_ESTADO_OBRA1='PA'
END
IF (@V_ESTADO_OBRA1 = 'TPR' OR @V_ESTADO_OBRA1 = 'PA') AND (@V_FORMAEJEC = 'DIP')  AND @V_FECHA_REMISION_CONTRATACION <> '  '
BEGIN

	SET @V_ESTADO_FASE1='TE'
	SET @V_ESTADO_PROY1='TE'
	SET @V_ESTADO_OBRA1='PA'
END
IF (@V_ESTADO_OBRA1 = 'TPR' OR @V_ESTADO_OBRA1 = 'PA') AND (@V_FORMAEJEC = 'DIP') AND @V_FECHA_REMISION_CONTRATACION is null
BEGIN
	
	SET @V_ESTADO_FASE1= 'TFA'
	SET @V_ESTADO_PROY1='TPR'
	SET @V_ESTADO_OBRA1='TPR'
END
IF (@V_ESTADO_OBRA1 = 'TPR' OR @V_ESTADO_OBRA1 = 'PA') AND @V_FORMAEJEC <>  'DIP'  AND (@V_FECHA_COM_GOB <> '  ' OR  @V_FECHA_DTO <>'  ')
BEGIN

	SET @V_ESTADO_FASE1= 'TE'
	SET @V_ESTADO_PROY1='TE'
	SET @V_ESTADO_OBRA1='PA'
END	
IF (@V_ESTADO_OBRA1 = 'TPR' OR @V_ESTADO_OBRA1 = 'PA') AND @V_FORMAEJEC <>  'DIP'  AND (@V_FECHA_COM_GOB = NULL AND   @V_FECHA_DTO = NULL)
BEGIN

	SET @V_ESTADO_FASE1= 'TFA'
	SET @V_ESTADO_PROY1='TPR'
	SET @V_ESTADO_OBRA1='TPR'
END	
/* ******************************************************************************************************************************************* */	
/*   COMPRUEBA EL NUEVO VALOR DE PETICION DE AYUDA TECNICA */
/* ******************************************************************************************************************************************* */	
set @v_petayuda_nueva = 'NO'
IF @V_ORGANISMO_REDACTOR = 'DP' OR @V_ORGANISMO_DIRECCION='DP' OR @V_SUBVENCIONECONDIRECCION <> 0  or @v_ser <> 0 
begin
	set @v_petayuda_nueva='SI'
end

 /* ******************************************************************************************************************************************* */	
/*  EMPIEZA LA ACTUALIZACION                 	   */
/* ******************************************************************************************************************************************* */

/* ACTUALIZO EL REGISTRO DE FASE*/
BEGIN TRANSACTION
UPDATE FASESDEPROYECTOS
SET
Servicio_gestor=@V_servicio_gestor,  
CODIGO_PLAN=@V_CODIGO_PLAN,
REFERENCIA =@V_REFERENCIA,
SUBREFERENCIA  =@V_SUBREFERENCIA,
AO_EJECUCION_OBRA =@V_AO_EJECUCION_OBRA,
CARRETERA =@V_CARRETERA,
PLAZO = @V_PLAZO,
UNIDADPLAZO = @V_UNIDAD_PLAZO,
NRO_EJEMPLARES = @V_NRO_EJEMPLARES,

REVISION  = @V_REVISION,
FORMULA = @V_FORMULA,
FORMULA2 = @V_FORMULA2,
FORMULA3 = @V_FORMULA3,
FORMULA4 = @V_FORMULA4,
ORGANISMO_DIRECCION = @V_ORGANISMO_DIRECCION,
SERVICIO_DIRECCION  = @V_SERVICIO_DIR,
DIRECTOR_TECNICO_OBRA  = @V_DIRECTOR_TECNICO_OBRA,
COLEGIOOFICIALDIRECCION   = @V_COLOFID,
SUBVENCIONECONDIRECCION = @V_SUBVENCIONECONDIRECCION,
nro_dto  = @V_nro_dto,
estado_FASE  = @V_ESTADO_FASE1,
CLASE_EXP = @V_CLASE_EXP,
TIPO_PROC   = @V_TIPO_PROC,
FORMA_CONT    = @V_FORMA_CONT,
Requiere_PlanSyS     = @V_Requiere_PlanSyS,

/*IMPORTES*/

IMPORTE_FASE  = @V_IMPORTE_FASE ,
presu_gral_ejecucion_material  = @V_presu_gral_ejecucion_material,
por_gastos_generales  = @V_por_gastos_generales,
importe_gastos_generales  = @V_importe_gastos_generales,
por_beneficio_industriales  = @V_por_beneficio_industriales,
importe_beneficio_industriales  = @V_importe_beneficio_industriales,
por_control_calidad  = @V_por_control_calidad,
importe_control_calidad  = @V_importe_control_calidad,
por_iva =@V_por_iva,
Iva  =@V_iva,
por_subcontrata  = @V_por_subcontrata,
subcontrata  = @V_subcontrata,
honorarios_dir  = @V_honorarios_dir,
honorarios_red  = @V_honorarios_red,
ImportePlanSyS = @V_plan_ss,
HD_ExcluidoIVA = @V_IVA_Honor_Direcc,
HR_ExcluidoIVA = @V_IVA_Honor_Redacc,

/* FECHAS*/

FECHA_REM_FASE  = @V_FECHA_REM_FASE,
fecha_ENT_FASE = @V_fecha_ENT_FASE,
fecha_REMISION_ayto  =  @V_fecha_REMISION_ayto,
fecha_aprobacion_ayto  =  @V_fecha_aprobacion_ayto,
fecha_REMISION_JUNTA  =  @V_fecha_REMISION_JUNTA,
FECHA_VISADO_JUNTA =   @V_FECHA_VISADO_JUNTA,
fecha_PET_INF_TECNICO_CONTRATA  =   @V_fecha_PET_INF_TECNICO_CONTRATA,
fecha_ENT_INF_TECNICO_CONTRATA   =   @V_fecha_ENT_INF_TECNICO_CONTRATA,
PLIEGO_CLAUSULAS_PARTICULARES   =   @V_PLIEGO_CLAUSULAS_PARTICULARES,
fecha_PET_DESGLOSE  =   @V_fecha_PET_DESGLOSE,
fecha_ENT_DESGLOSE   =   @V_fecha_ENT_DESGLOSE,
fecha_pet_rectificacion   =  @V_fecha_pet_rectificacion,
fecha_ent_rectificacion   =   @V_fecha_ent_rectificacion,
fecha_pet_reforma   =   @V_fecha_pet_reforma,
fecha_ent_reforma  =    @V_fecha_ent_reforma,
fecha_pit_ref   =   @V_fecha_pit_ref,
fecha_eit_ref   =   @V_fecha_eit_ref,
fecha_ci_ref    =   @V_fecha_ci_ref,
fecha_cg_ref   =   @V_fecha_cg_ref,
FECHA_PET_ACTUAL_PRECIOS   =  @V_FECHA_PET_ACTUAL_PRECIOS,
FECHA_ENT_ACTUAL_PRECIOS   =   @V_FECHA_ENT_ACTUAL_PRECIOS,
FECHA_ENVIO_FISCALIZACION  = @V_FECHA_ENVIO_FISCALIZACION,
fecha_COM_INF   = @V_fecha_COM_INF,
fecha_COM_GOB  =  @V_fecha_COM_GOB,
fecha_FISCALIZACION  =   @V_FISCALIZACION,
FECHA_REMISION_CONTRATACION   =  @V_FECHA_REMISION_CONTRATACION,
fecha_dto   =   @V_fecha_dto

WHERE (MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto) AND 
(AO_FASE= @V_AO_FASE) AND
(NUMERO_FASE = @V_NUMERO_FASE)
set @v_error = @@error
if @v_error <> 0
begin
	ROLLBACK
	RETURN 23			/* ERROR EN LA ACTUALIZACION DEL REGISTRO DE FASE*/
end

/* ACTUALIZO EL ESTADO DE PROYECTO*/
UPDATE PROYECTOS
SET
ESTADO_PROYECTO=@V_ESTADO_PROY1
WHERE (codigo_MUNICIPIO =@v_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_numero_proyecto) 
set @v_error = @@error
if @v_error <> 0
begin 
	ROLLBACK	
	return 24		/*ERROR AL ACTUALIZAR ESTADO DEL PROYECTO*/			
end

/* ACTUALIZA ESTADO OBRA y peticion ayuda tecnica*/

UPDATE DATOSINICIODEOBRAS
SET
CODIGO_ESTADO_obra=@V_ESTADO_OBRA1,
peticion_ayuda_tec=@v_petayuda_nueva
where  codigo_plan= @V_CODIGO_PLAN and
numero_obra = @V_REFERENCIA and
subreferencia = @V_SUBREFERENCIA and
ao_ejecucion = @V_AO_EJECUCION_OBRA
set @v_error = @@error
if @v_error <> 0
begin 
	ROLLBACK	
	return 25		/*ERROR AL ACTUALIZAR ESTADO OBRA*/			
end

/*ACTUALIZO LA AYUDA TECNICA POR SI HUBO CAMBIOS*/
IF @V_SUBVENCIONECONDIRECCION = 0 
BEGIN
	SET @V_SED=null
END
ELSE
BEGIN
	SET @V_SED='SI'
END
IF @V_SER = 0 
BEGIN
	SET @V_SER2=null
END
ELSE
BEGIN
	SET @V_SER2='SI'
END
if @v_petayuda_nueva='SI' and @v_petayuda_antes= 'SI'
begin 
	UPDATE AYUDA_TECNICA
	SET
	DEPARTAMENTO_DIRECCION= @V_SERVICIO_DIR,
	DPTO_REDACTOR=@V_SERVICIO_REDACTOR,
	SUBVENCIONECONOMICAR = @V_SER2,
	SUBVENCIONECONOMICAD = @V_SED,
	PASADO=1
	where  codigo_plan= @V_CODIGO_PLAN and
	numero_obra = @V_REFERENCIA and
	subreferencia = @V_SUBREFERENCIA and
	ao_ejecucion = @V_AO_EJECUCION_OBRA
	set @v_error = @@error
	if @v_error <> 0	begin 
		ROLLBACK	
		return 26		/*ERROR AL ACTUALIZAR AYUDA_TECNICA*/			
	end
end
if @v_petayuda_nueva = 'NO' and @v_petayuda_antes = 'SI'
begin
	delete from ayuda_tecnica
	where  codigo_plan= @V_CODIGO_PLAN and
	numero_obra = @V_REFERENCIA and
	subreferencia = @V_SUBREFERENCIA and
	ao_ejecucion = @V_AO_EJECUCION_OBRA
	set @v_error = @@error
	if @v_error <> 0	begin 
		ROLLBACK	
		return 26		/*ERROR AL ACTUALIZAR AYUDA_TECNICA*/			
	end
end
if @v_petayuda_nueva = 'SI' and @v_petayuda_antes = 'NO'
begin
	INSERT INTO ayuda_tecnica
		   (CODIGO_PLAN,NUMERO_OBRA,SUBREFERENCIA,AO_EJECUCION,	departamento, codigo_municipio,ao_proyecto,numero_proyecto,DPTO_REDACTOR,DEPARTAMENTO_DIRECCION,PASADO,SUBVENCIONECONOMICAR,SUBVENCIONECONOMICAD)
	VALUES (@V_CODIGO_PLAN, @V_REFERENCIA,@V_SUBREFERENCIA,@V_AO_EJECUCION_OBRA,0,0,0,0,@V_SERVICIO_REDACTOR,@V_SERVICIO_DIR,1,@V_SER2,@V_SED)
	set @v_error = @@error
	if @v_error <> 0	begin 
		ROLLBACK	
		return 26		/*ERROR AL ACTUALIZAR AYUDA_TECNICA*/			
	end
end
/* GRABA PENDIENTE CONTRATACION O ACTUALIZA REGISTRO EXISTENTE*/
set @v_enviocontratacion=0  
if @V_FORMAEJEC <> 'DIP' OR ( @V_FECHA_REMISION_CONTRATACION is null OR RTRIM(@V_FECHA_REMISION_CONTRATACION)='')
begin
	commit
	return 20
end

if @v_ExpActivo>0
begin
	commit
	return 20
end

if @v_estado_obra1 = 'TPR' and @V_EXISTE_PTEACTIVO > 0
BEGIN
	DELETE CONTRATA..PENDIENTECONTRATACIONOBRAS
	WHERE
	planOBRA= @V_CODIGO_PLAN and
	numobra = @V_REFERENCIA and
	subref = @V_SUBREFERENCIA and
	AOOBRA = @V_AO_EJECUCION_OBRA AND
	TIPOEXP='OB' and 
	peticioncont=@v_numorden
	set @v_enviocontratacion=3
	COMMIT
	RETURN 20
END

--if @v_estado_obra1 <> 'PA'

IF @V_EXISTE_PTEactivo  > 0 
BEGIN
	UPDATE CONTRATA..PENDIENTECONTRATACIONOBRAS
	SET
	ampliacion=0,
	--servicio_redactor=@v_servicio_redactor,
	--autor=@v_autor,
	servicio_gestor=@v_servicio_gestor,
	--servicio_direccion=@v_servicio_dir,
	codclaseexp= @V_CLASE_EXP,
	codprocedimiento = @V_TIPO_PROC    ,
	CodFormaContrata    = @V_FORMA_CONT,
	importelicitacion=@v_importe_fase,
	fecharecepcion=getdate (),
	esTADO=NULL
	WHERE
	planOBRA= @V_CODIGO_PLAN and
	numobra = @V_REFERENCIA and
	subref = @V_SUBREFERENCIA and
	AOOBRA = @V_AO_EJECUCION_OBRA AND
	TIPOEXP='OB' and 
	peticioncont=@v_numorden
	set @v_enviocontratacion=1
end
if @v_existe_pteactivo = 0 
begin
	IF @V_EXISTE_PTE = 0 
	BEGIN
		SET @V_NUMORDEN=0
	END
	set @v_numorden=@v_numorden +1
	/*INSERT INTO contrata..pendientecontratacionobras
 		 (tipoexp,peticioncont,planobra,numobra,subref,aoobra,ampliacion,servicio_redactor,servicio_gestor,servicio_direccion,
		autor,importelicitacion,codclaseexp,codprocedimiento,codformacontrata,fecharecepcion,estado)	
	VALUES ('OB',@v_numorden,@V_CODIGO_PLAN, @V_REFERENCIA , @V_SUBREFERENCIA, @V_AO_EJECUCION_OBRA ,0,@V_SERVICIO_REDACTOR,
	@V_SERVICIO_GESTOR,@V_SERVICIO_DIR,@V_AUTOR,@V_IMPORTE_FASE,@V_CLASE_EXP, @V_TIPO_PROC   ,
	@V_FORMA_CONT,getdate (),NULL)*/

	INSERT INTO contrata..pendientecontratacionobras
 		 (tipoexp,peticioncont,planobra,numobra,subref,aoobra,ampliacion,servicio_gestor,
		importelicitacion,codclaseexp,codprocedimiento,codformacontrata,fecharecepcion,estado)	
	VALUES ('OB',@v_numorden,@V_CODIGO_PLAN, @V_REFERENCIA , @V_SUBREFERENCIA, @V_AO_EJECUCION_OBRA ,0,
	@V_SERVICIO_GESTOR,@V_IMPORTE_FASE,@V_CLASE_EXP, @V_TIPO_PROC   ,
	@V_FORMA_CONT,getdate (),NULL)
	set @v_enviocontratacion=2
END
set @v_error = @@error
if @v_error <> 0
begin	ROLLBACK	
	RETURN 27			/*ERROR EN PENDIENTE DE CONTRATACION*/
end


COMMIT
RETURN 20			/*PROCESO REALIZADO CORRECTAMENTE*/
GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Modificando Procedimiento [dbo].[PA_PROYECTOS_L_FaseSeleccionada]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
ALTER PROCEDURE [dbo].[PA_PROYECTOS_L_FaseSeleccionada] 
/***************************************************** Este procedimiento lee una fase de un proyecto y sus datos asociados ************************************************************
	
	
	LEE la fase seleccionada
	LEE CLASIFICACION

	LOS PARAMETROS QUE RECIBE SON LA CLAVE DEL PROYECTO
	Valores devueltos:
		0 -- Funcionamento correcto
		1 -- Fallo en la lectura de LA fase
	
		4 -- Fallo en el cálculo de totales de fases

*/
/* VARIABLES PARA EL PROYECTO*/

@V_CODIGO_MUNICIPIO as smallint,
@V_AO_PROYECTO as smallint, 
@V_NUM_PROYECTO AS SMALLINT,
@v_NUM_FASE AS SMALLINT,

@V_NumFases  as smallint =0 OUTPUT,
@V_TotalFases as float =0 output,
@V_NumClasif as smallint =0 OUTPUT


AS
DECLARE @V_ERROR AS INT
/* 	LEE LA FASE SELECCIONADA DEL  PROYECTO    	*/


SELECT FASESDEPROYECTOS.*, forma_ejecucion,  dbo.DatosInicioDeObras.nombre_obra1,dbo.DatosInicioDeObras.nombre_obra2,dbo.DatosInicioDeObras.nombre_obra3,dbo.DatosInicioDeObras.codigo_estado_obra,Tablas.dbo.TablaDeDepartamentos.DENOMINACION AS DENDPTODIR, Tablas.dbo.TablaDeDepartamentos.codigo_dpto,
    Tablas.dbo.ColegiosOficiales.organismo_redactor AS DENCOLOFID,CODIGO_DEPAR_RESERVADO,contrata.dbo.tbclasesexpedientes.claseexped as ClaseExp,Contrata.dbo.TbFormasContratacion.formacontratacion as formacontratacion,Contrata.dbo.TbtiposProcedimientos.procedimiento as procedimiento
FROM dbo.FasesDeProyectos
LEFT OUTER JOIN
    Tablas.dbo.ColegiosOficiales ON 
    dbo.FasesDeProyectos.ColegioOficialDireccion = Tablas.dbo.ColegiosOficiales.codigo_redactor
LEFT OUTER JOIN
    Tablas.dbo.TablaDeDepartamentos ON 
    dbo.FasesDeProyectos.servicio_direccion = Tablas.dbo.TablaDeDepartamentos.CODIGO_DPTO
LEFT OUTER JOIN
    Tablas.dbo.PLANES ON 
    dbo.FasesDeProyectos.CODIGO_PLAN = Tablas.dbo.PLANES.CODIGO_PLAN
LEFT OUTER JOIN
    Contrata.dbo.TbClasesExpedientes ON 
    dbo.FasesDeProyectos.Clase_exp = Contrata.dbo.tbclasesexpedientes.codclaseexped
LEFT OUTER JOIN
    Contrata.dbo.TbFormasContratacion ON 
    dbo.FasesDeProyectos.forma_cont = Contrata.dbo.TbFormasContratacion.codformacontra
LEFT OUTER JOIN
    Contrata.dbo.TbtiposProcedimientos ON 
    dbo.FasesDeProyectos.tipo_proc = Contrata.dbo.TbtiposProcedimientos.codprocedimiento
 LEFT OUTER JOIN
    dbo.DatosInicioDeObras ON 
    obras.dbo.DatosInicioDeObras.codigo_plan = fasesdeproyectos.codigo_plan
     AND 
    obras.dbo.DatosInicioDeObras.numero_obra = fasesdeproyectos.referencia
     AND 
    obras.dbo.DatosInicioDeObras.subreferencia = fasesdeproyectos.subreferencia
     AND 
    obras.dbo.DatosInicioDeObras.ao_ejecucion = fasesdeproyectos.ao_ejecucion_obra

WHERE 
 FasesDeProyectos.MUNICIPIO = @V_CODIGO_MUNICIPIO AND
 FasesDeProyectos.AO_PROYECTO = @V_AO_PROYECTO AND
 FasesDeProyectos.NUMERO_PROYECTO = @V_NUM_PROYECTO AND 
 FasesDeProyectos.NUMERO_FASE = @V_NUM_FASE 

SELECT ClasificacionProyectos.*
FROM    ClasificacionProyectos
WHERE 
ClasificacionProyectos.MUNICIPIO = @V_CODIGO_MUNICIPIO AND
ClasificacionProyectos.AO_PROYECTO = @V_AO_PROYECTO AND
ClasificacionProyectos.NUMERO_PROYECTO = @V_NUM_PROYECTO AND 
ClasificacionProyectos.NUMERO_FASE = @V_NUM_FASE 

--SELECT dbo.ImportesPorOrganismo.organismo AS [Org], 
 -- dbo.ImportesPorOrganismo.Porc_imp_aprobado AS [%Aprob], 
  --  dbo.ImportesPorOrganismo.importe_aprobado as [Aprobado], 
/*    dbo.ImportesPorOrganismo.importe_remanente as [Remanente], */
--  dbo.ImportesPorOrganismo.Porc_imp_contratar as [% a Contratar], 
--    dbo.ImportesPorOrganismo.importe_a_contratar as [A contratar]
--FROM dbo.ImportesPorOrganismo RIGHT OUTER JOIN
--    dbo.FasesDeProyectos ON 
--    dbo.ImportesPorOrganismo.subreferencia = dbo.FasesDeProyectos.subreferencia
--     AND 
--    dbo.ImportesPorOrganismo.Codigo_Plan = dbo.FasesDeProyectos.Codigo_Plan
--   AND 
--  dbo.ImportesPorOrganismo.numero_obra = dbo.FasesDeProyectos.referencia
--   AND 
--  dbo.ImportesPorOrganismo.ao_ejecucion = dbo.FasesDeProyectos.ao_ejecucion_obra
--wheRE
-- FasesDeProyectos.MUNICIPIO = @V_CODIGO_MUNICIPIO AND
-- FasesDeProyectos.AO_PROYECTO = @V_AO_PROYECTO AND
-- FasesDeProyectos.NUMERO_PROYECTO = @V_NUM_PROYECTO AND 
-- FasesDeProyectos.NUMERO_FASE = @V_NUM_FASE 
select Importesdeobras.*
FROM dbo.Importesdeobras RIGHT OUTER JOIN
    dbo.FasesDeProyectos ON 
    dbo.Importesdeobras.subreferencia = dbo.FasesDeProyectos.subreferencia
     AND 
    dbo.Importesdeobras.codigo_plan = dbo.FasesDeProyectos.Codigo_Plan
   AND 
  dbo.Importesdeobras.numero_obra = dbo.FasesDeProyectos.referencia
   AND 
  dbo.Importesdeobras.ao_ejecucion = dbo.FasesDeProyectos.ao_ejecucion_obra
wheRE
 FasesDeProyectos.MUNICIPIO = @V_CODIGO_MUNICIPIO AND
 FasesDeProyectos.AO_PROYECTO = @V_AO_PROYECTO AND
 FasesDeProyectos.NUMERO_PROYECTO = @V_NUM_PROYECTO AND 
 FasesDeProyectos.NUMERO_FASE = @V_NUM_FASE 

set @v_error = @@error
if @v_error <> 0
begin
	return 1
end
/* 	LEE LAS CLASIFICACIONES DEL  PROYECTO    	*/



SELECT @v_numclasif= COUNT(NUMERO_FASE)  
FROM CLASIFICACIONPROYECTOS
GROUP BY MUNICIPIO, AO_PROYECTO, 
    NUMERO_PROYECTO,numero_fase
HAVING
MUNICIPIO = @V_CODIGO_MUNICIPIO AND
AO_PROYECTO = @V_AO_PROYECTO AND
NUMERO_PROYECTO = @V_NUM_PROYECTO and 
numero_fase = @v_num_fase

set @v_error = @@error


if @v_error <> 0
begin
	return 3
end

/*' calcula total de fases y numero de fases*/

SELECT @v_totalfases= SUM(importe_fase)
FROM FasesDeProyectos
GROUP BY MUNICIPIO, AO_PROYECTO, 
    NUMERO_PROYECTO
HAVING
MUNICIPIO = @V_CODIGO_MUNICIPIO AND
AO_PROYECTO = @V_AO_PROYECTO AND
NUMERO_PROYECTO = @V_NUM_PROYECTO

SELECT @v_numfases= COUNT(NUMERO_FASE)  
FROM FasesDeProyectos
GROUP BY MUNICIPIO, AO_PROYECTO, 
    NUMERO_PROYECTO
HAVING
MUNICIPIO = @V_CODIGO_MUNICIPIO AND
AO_PROYECTO = @V_AO_PROYECTO AND
NUMERO_PROYECTO = @V_NUM_PROYECTO


if @v_error <> 0
begin
	return  4

end

RETURN 0
GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Modificando Procedimiento [dbo].[PA_PROYECTOS_L_ProyectoSeleccionado]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
ALTER PROCEDURE [PA_PROYECTOS_L_ProyectoSeleccionado] 
/***************************************************** Este procedimiento lee un proyecto y sus datos asociados ************************************************************
	
	LEE REGISTRO EN TABLA DE PROYECTOS
	LEE LAS FASES QUE CONTENGA (FASESDEPROYECTOS)
	LEE CLASIFICACION

	LOS PARAMETROS QUE RECIBE SON LA CLAVE DEL PROYECTO
	Valores devueltos:
		0 -- Funcionamento correcto
		1 -- Fallo en la búsqueda del proyecto
		2 -- Fallo en la lectura de fases
		3 -- Fallo en la lectura de clasificacion
		4 -- Fallo en el cáculo de totales de fases

*/
/* VARIABLES PARA EL PROYECTO*/

@V_CODIGO_MUNICIPIO as smallint,
@V_AO_PROYECTO as smallint, 
@V_NUM_PROYECTO AS SMALLINT,
@V_NumFases  as smallint OUTPUT,
@V_TotalFases as float output


AS
DECLARE @V_ERROR AS INT



/* 	LEE EL PROYECTO    	*/

SELECT Proyectos.*, TDptoGestor.DENOMINACION AS DenDepto, 
    TDptoRedactor.DENOMINACION AS DenDeptoRed, 
    Tablas.dbo.TablaDeMunicipios.nombre_municipio AS nombre_municipio,
     Tablas.dbo.OrganismosRed_Dir.organismo_redactor AS DenOrgRed,
     Tablas.dbo.ColegiosOficiales.organismo_redactor AS DenColOfi,
     Tablas.dbo.TablaDeEstados.estado as DenEstado
FROM Tablas.dbo.TablaDeDepartamentos TDptoGestor RIGHT OUTER
     JOIN
    Tablas.dbo.TablaDeDepartamentos TDptoRedactor RIGHT OUTER
     JOIN
    Proyectos LEFT OUTER JOIN
    Tablas.dbo.ColegiosOficiales ON 
    Proyectos.ColegioOficial = Tablas.dbo.ColegiosOficiales.codigo_redactor
     LEFT OUTER JOIN
    Tablas.dbo.OrganismosRed_Dir ON 
    Proyectos.organismo_redactor = Tablas.dbo.OrganismosRed_Dir.codigo_redactor
     LEFT OUTER JOIN
    Tablas.dbo.TablaDeMunicipios ON 
    Proyectos.CODIGO_MUNICIPIO = Tablas.dbo.TablaDeMunicipios.codigo_municipio
    LEFT OUTER JOIN
    Tablas.dbo.TablaDeEstados ON 
    Proyectos.estado_proyecto = Tablas.dbo.TablaDeEstados.cod_estado
     ON 
    TDptoRedactor.CODIGO_DPTO = Proyectos.Servicio_redactor ON
     TDptoGestor.CODIGO_DPTO = Proyectos.Servicio_Gestor


WHERE
proyectos.CODIGO_MUNICIPIO = @V_CODIGO_MUNICIPIO AND
AO_PROYECTO = @V_AO_PROYECTO AND
NUMERO_PROYECTO = @V_NUM_PROYECTO

set @v_error = @@error

if @v_error <> 0
begin
	return 1
end


/* 	LEE LAS FASES DEL  PROYECTO    	*/


SELECT FASESDEPROYECTOS.*, forma_ejecucion
FROM dbo.FasesDeProyectos LEFT OUTER JOIN
    dbo.DatosInicioDeObras ON 
    obras.dbo.DatosInicioDeObras.codigo_plan = fasesdeproyectos.codigo_plan
     AND 
    obras.dbo.DatosInicioDeObras.numero_obra = fasesdeproyectos.referencia
     AND 
    obras.dbo.DatosInicioDeObras.subreferencia = fasesdeproyectos.subreferencia
     AND 
    obras.dbo.DatosInicioDeObras.ao_ejecucion = fasesdeproyectos.ao_ejecucion_obra
WHERE
FASESDEPROYECTOS.MUNICIPIO = @V_CODIGO_MUNICIPIO AND
AO_PROYECTO = @V_AO_PROYECTO AND
NUMERO_PROYECTO = @V_NUM_PROYECTO

set @v_error = @@error


if @v_error <> 0
begin
	return 2
end
/* 	LEE LAS CLASIFICACIONES DEL  PROYECTO    	*/

SELECT * FROM CLASIFICACIONPROYECTOS WHERE

MUNICIPIO = @V_CODIGO_MUNICIPIO AND
AO_PROYECTO = @V_AO_PROYECTO AND
NUMERO_PROYECTO = @V_NUM_PROYECTO

set @v_error = @@error


if @v_error <> 0
begin
	return 3
end

/*' calcula total de fases y numero de fases*/

SELECT @v_totalfases= SUM(importe_fase)
FROM FasesDeProyectos
GROUP BY MUNICIPIO, AO_PROYECTO, 
    NUMERO_PROYECTO
HAVING
MUNICIPIO = @V_CODIGO_MUNICIPIO AND
AO_PROYECTO = @V_AO_PROYECTO AND
NUMERO_PROYECTO = @V_NUM_PROYECTO

SELECT @v_numfases= COUNT(NUMERO_FASE)  
FROM FasesDeProyectos
GROUP BY MUNICIPIO, AO_PROYECTO, 
    NUMERO_PROYECTO
HAVING
MUNICIPIO = @V_CODIGO_MUNICIPIO AND
AO_PROYECTO = @V_AO_PROYECTO AND
NUMERO_PROYECTO = @V_NUM_PROYECTO


if @v_error <> 0
begin
	return  4

end

RETURN 0
GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Modificando Procedimiento [dbo].[PA_PROYECTOS_ABM_DatosProyectoOLD]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
ALTER PROCEDURE [PA_PROYECTOS_ABM_DatosProyectoOLD] 
/*
ESTE PROCEDIMIENTO REALIZA EL ALTA, LA BAJA O MODIFICACION DE UN REGISTRO DE PROYECTO
VALORES QUE DEVUELVE:
	0 TERMINACION CORRECTA
	1 ERROR EN ALTA O MODIFICACION
	2 ERROR EN BAJA
	3 NO EXISTE REGISTRO QUE SE QUIERE DAR DE BAJA
	4 EXISTE REGISTRO A DAR DE ALTA
	5 NO EXISTE REGISTRO QUE SE QUIERE MODIFICAR
	6 EXISTEN FASES ASOCIADAS AL PROYECTO QUE SE QUIERE BORRAR
	7 ERROR AL ASIGNAR NUEVO NUMERO PROYECTO
	8 ERROR AL CARGAR LOS CODIGOS DE SERVICIO O COLEGIO
	9 ERROR AL MODIFICAR LA FASE
*/

@v_accion as char(1) = 'A',
/* VARIABLES PARA EL PROYECTO*/
/*CLAVE*/
@V_CODIGO_MUNICIPIO as smallint,
@V_AO_PROYECTO as smallint, 
@V_PROY AS INTEGER=0,
/*RESTO DATOS*/
@V_SERVICIO_GESTOR AS SMALLINT=0,
@V_den_proyecto as varchar(150) = ' ',
@V_importe_proyecto as float= 0, 
@V_organismo_redactor as char(2)='AY' ,
@V_revision as char(2) = 'NO',
@V_Requiere_PlanSyS as bit = 1,
@V_Requiere_TratMed as bit = 1,
@V_SERVICIO_redactor AS varchar(150) = ' ',
@V_autor as varchar(50)=' ',
@V_COLEGIOOFICIAL AS varchar(150) = ' ',
@V_carretera as char(5) = NULL,
@V_plazo as int=0,
@V_Unidad_Plazo as char(1) = 'm' ,
@V_nro_ejemplares as smallint=0, 
@V_formula as int=NULL,
@V_formula2 as int=NULL,
@V_formula3 as int=NULL,
@V_formula4 as int = NULL, 
@V_presu_gral_ejecucion_material as float=0, 
@V_por_gastos_generales as float=0,  
@V_importe_gastos_generales as float=0, 
@V_por_beneficio_industriales as float=0, 
@V_importe_beneficio_industriales as float=0, 
@V_por_control_calidad as float=0,  
@V_importe_control_calidad as float=0,  
@V_por_iva as float=0, 
@V_iva as float=0, 
@V_por_subcontrata as float=0, 
@V_subcontrata as float=0, 
@V_honorarios_dir as float=0, 
@V_honorarios_red as float=0,  
@V_fecha_entrega_proyecto as smalldatetime=NULL,
@V_fecha_recepcion_proyecto as smalldatetime=NULL, 
@V_fecha_remision_ayto as smalldatetime=NULL,
@V_fecha_aprobacion_ayto as smalldatetime=NULL, 
@V_fecha_pet_rectificacion as smalldatetime=NULL,
@V_fecha_ent_rectificacion as smalldatetime=NULL,
@V_fecha_pet_reforma as smalldatetime=NULL,
@V_fecha_ent_reforma as smalldatetime=NULL,
@V_fecha_c_infor as smalldatetime=NULL, 
@V_fecha_c_gob as smalldatetime=NULL,
@V_fecha_pit_ref as smalldatetime=NULL,
@V_fecha_eit_ref as smalldatetime=NULL,
@V_fecha_ci_ref as smalldatetime=NULL, 
@V_fecha_cg_ref as smalldatetime=NULL,
@V_fecha_dto as smalldatetime=NULL,
@V_nro_dto as float=NULL,
@V_observaciones as varchar(240)=NULL, 
@V_estado_proyecto as CHAR(3)='TPR',
@V_SUBVENCION AS BIT= 0,
@v_compartido as bit = 0,
@V_NUM_PROYECTO as integer  output

AS
declare @v_existe as integer
declare @v_existefase as integer
DECLARE @V_ERROR AS INTEGER
declare @v_numero_proyecto as integer
declare @v_colofir as char(2)
declare @v_servred as smallint

/* carga el código del servicio redactor y el colegio oficial de redacción si existen*/

if rtrim(@v_servicio_redactor) <> ' ' 
begin
	select @v_servred = codigo_dpto from tablas..tabladedepartamentos where denominacion = @v_servicio_redactor
	set @v_error = @@error
	if @v_error <> 0
	begin
		RETURN 8			/* ERROR al leer el servicio redactorr*/
	end
end
else 
begin
 	set @v_servred = null
end
if rtrim(@v_colegiooficial) <> ' '
begin
	select @v_colofiR = codigo_redactor from tablas..colegiosoficiales where organismo_redactor = @v_colegiooficial
	set @v_error = @@error
	if @v_error <> 0
	begin
		RETURN 8			/* ERROR al leer el colegio*/
	end
end
ELSE
BEGIN
	SET @V_COLOFIR = NULL
END
/******************************************************************************************************************************************
				COMPRUEBA SI EXISTE EL PROYECTO Y SI TIENE FASES
********************************************************************************************************************************************   */
set @v_existe=0
set @v_existefase =0
IF @V_PROY <> 0 
BEGIN
	SET @V_NUM_PROYECTO=@V_PROY
END	
select  @v_existe = count(1) from proyectos 
WHERE (CODIGO_MUNICIPIO =@v_CODIGO_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_proy) 

select @v_existefase = count(1) from fasesdeproyectos
WHERE (MUNICIPIO =@v_CODIGO_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_proy) 


IF RTRIM(@V_ESTADO_PROYECTO)='  '  OR @V_ESTADO_PROYECTO = NULL
BEGIN
	SET @V_ESTADO_PROYECTO = 'TPR'
END
/*******************************************************************************************************************************************
		INICIO DE LOS PROCESOS SOLICITADOS SEGUN LA ACCION TRANSFERIDA
********************************************************************************************************************************************
                                             EMPIEZA LA  BAJA DEL PROYECTO                                                                                
********************************************************************************************************************************************  */
if @v_accion = 'B' 
begin
             if @v_existe = 0 
	begin
		return 3
	end
	IF @V_EXISTEFASE > 0
	BEGIN
		RETURN 6
	END	
	delete from proyectos 
	WHERE (CODIGO_MUNICIPIO =@v_CODIGO_MUNICIPIO) AND
		 (AO_PROYECTO = @v_ao_PROYECTO) AND 
   		 (NUMERO_PROYECTO = @v_proy) 
 
	set @v_error = @@error
	if @v_error <> 0
	begin
		return 2
	end
	set @v_num_proyecto = @v_proy
	return 0
end
/*********************************************************************************************************************************************/	
/*                                                  TERMINA  LA  BAJA DEL PROYECTO                                                                                  */
/*********************************************************************************************************************************************/



/*********************************************************************************************************************************************/	
/*                                                   EMPIEZA EL ALTA DEL PROYECTO                                                                                  */
/*********************************************************************************************************************************************/

if @v_existe = 1 AND @V_ACCION= 'A' 
begin
	return 4
end
IF @V_EXISTE= 0 AND @V_ACCION='M'
begin
	return 5
end

/* SI ES ALTA GRABA UN REGISTRO SOLO CON LA CLAVE */
IF @V_ACCION='A' 
BEGIN
/*********************************************************************************************************************************************/	
				/*CALCULA EL NUEVO NUMERO DE PROYECTO*/
/*********************************************************************************************************************************************/
	SELECT @v_numero_proyecto = MAX(NUMERO_PROYECTO) FROM Proyectos
	GROUP BY CODIGO_MUNICIPIO,     AO_PROYECTO
	HAVING (CODIGO_MUNICIPIO = @v_codigo_municipio)  AND (AO_PROYECTO = @v_ao_proyecto)
	if @v_numero_proyecto is null 
	begin
 		set @v_numero_proyecto=0
	end
	set @v_numero_proyecto = @v_numero_proyecto + 1
	set @v_num_proyecto=@v_numero_proyecto
	set @v_error = @@error
	if @v_error <> 0 
	begin
		return 7
	END
/*
******************************************************************************************************************************************** 
		 AÑADE UN REGISTRO DE PROYECTO CON LA CLAVE SOLAMENTE
********************************************************************************************************************************************
*/	
	INSERT INTO Proyectos
 		   (CODIGO_MUNICIPIO, AO_PROYECTO, NUMERO_PROYECTO)
	VALUES (@V_CODIGO_MUNICIPIO, @V_AO_PROYECTO,@V_NUM_PROYECTO)
	set @v_error = @@error
	if @v_error <> 0
	begin
		return 1
	end
	SET @V_PROY=@V_NUM_PROYECTO
END
/*******************************************************************************************************************************************/	
/*                                      TERMINA EL ALTA Y CONTINUA CON LA MODIFICACION  DEL PROYECTO                      
********************************************************************************************************************************************
		MODIFICA UN REGISTRO DE PROYECTO (ACCION PARA ALTA O MODIFICACION
********************************************************************************************************************************************
*/	
UPDATE Proyectos
SET
SERVICIO_GESTOR = @V_SERVICIO_GESTOR, 
DEN_PROYECTO = @V_DEN_PROYECTO,
importe_proyecto = @V_importe_proyecto, 
organismo_redactor = @V_organismo_redactor,
servicio_redactor = @V_servRED,
autor = @V_autor, 
colegiooficiaL = @V_colofiR,
carretera = @V_carretera,
plazo = @V_plazo, 
unidadplazo = @V_unidad_plazo, 
nro_ejemplares = @V_nro_ejemplares, 
revision = @V_revision, 
formula = @V_formula,
formula2 = @V_formula2,
formula3 = @V_formula3, 
formula4 = @V_formula4, 
presu_gral_ejecucion_material = @V_presu_gral_ejecucion_material, 
por_gastos_generales=@V_por_gastos_generales, 
importe_gastos_generales = @V_importe_gastos_generales, 
por_beneficio_industriales = @V_por_beneficio_industriales, 
Importe_beneficio_industriales = @V_Importe_beneficio_industriales,
por_control_calidad = @V_por_control_calidad, 
importe_control_calidad = @V_importe_control_calidad, 
por_iva = @V_por_iva, 
iva = @V_iva, 
por_subcontrata = @V_por_subcontrata, 
Subcontrata = @V_Subcontrata, 
honorarios_dir = @V_honorarios_dir,
honorarios_red = @V_honorarios_red, 
fecha_entrega_proyecto = @V_fecha_entrega_proyecto, 
fecha_recepcion_proyecto = @V_fecha_recepcion_proyecto, 
fecha_remision_ayto = @V_fecha_remision_ayto, 
fecha_aprobacion_ayto = @V_fecha_aprobacion_ayto, 
fecha_pet_rectificacion = @V_fecha_pet_rectificacion, 
fecha_ent_rectificacion = @V_fecha_ent_rectificacion, 
fecha_pet_reforma = @V_fecha_pet_reforma,
fecha_ent_reforma = @V_fecha_ent_reforma,
fecha_c_infor = @V_fecha_c_infor, 
fecha_c_gob = @V_fecha_c_gob,
Fecha_pit_ref = @V_Fecha_pit_ref,
fecha_eit_ref = @V_fecha_eit_ref, 
fecha_ci_ref = @V_fecha_ci_ref, 
fecha_cg_ref = @V_fecha_cg_ref,
fecha_dto = @V_fecha_dto, 
nro_dto = @V_nro_dto, 
observaciones = @V_observaciones, 
estado_proyecto = @V_estado_proyecto, 
Requiere_PlanSyS = @V_Requiere_PlanSyS,
Requiere_TramAmbiental = @V_Requiere_TratMed,
SUBVENCIONECONREDACCION = @V_SUBVENCION,
compartido = @v_compartido

WHERE (CODIGO_MUNICIPIO =@v_CODIGO_MUNICIPIO) AND (AO_PROYECTO = @v_ao_PROYECTO) AND 
    (NUMERO_PROYECTO = @v_proy) 

set @v_error = @@error

if @v_error <> 0
begin
	return @@error
	return 1
end

/*                                                        
********************************************************************************************************************************************
		MODIFICA LAS FECHAS DE LAS FASES DEL PROYECTO
********************************************************************************************************************************************
*/	


UPDATE FasesDeProyectos
SET
fecha_rem_fase = @V_fecha_entrega_proyecto, 
fecha_ent_fase = @V_fecha_recepcion_proyecto, 
fecha_remision_ayto = @V_fecha_remision_ayto, 
fecha_aprobacion_ayto = @V_fecha_aprobacion_ayto, 
fecha_pet_rectificacion = @V_fecha_pet_rectificacion, 
fecha_ent_rectificacion = @V_fecha_ent_rectificacion, 
fecha_pet_reforma = @V_fecha_pet_reforma,
fecha_ent_reforma = @V_fecha_ent_reforma,
fecha_com_inf = @V_fecha_c_infor, 
fecha_com_gob = @V_fecha_c_gob,
Fecha_pit_ref = @V_Fecha_pit_ref,
fecha_eit_ref = @V_fecha_eit_ref, 
fecha_ci_ref = @V_fecha_ci_ref, 
fecha_cg_ref = @V_fecha_cg_ref,
fecha_dto = @V_fecha_dto,
Nro_dto = @V_nro_dto

WHERE (MUNICIPIO =@v_CODIGO_MUNICIPIO) AND (AO_PROYECTO = @v_ao_PROYECTO) AND 
    (NUMERO_PROYECTO = @v_proy) AND
    fecha_ent_fase = @V_fecha_recepcion_proyecto

set @v_error = @@error

if @v_error <> 0
begin
	return @@error
	return 9
end

UPDATE FasesDeProyectos
SET
revision = @V_revision, 
formula = @V_formula, 
formula2 = @V_formula2, 
formula3 = @V_formula3, 
formula4 = @V_formula4, 
plazo = @V_plazo, 
unidadplazo = @V_Unidad_Plazo

WHERE (MUNICIPIO =@v_CODIGO_MUNICIPIO) AND (AO_PROYECTO = @v_ao_PROYECTO) AND 
    (NUMERO_PROYECTO = @v_proy) AND  (NUMERO_FASE = 1) AND
    importe_fase = @V_importe_proyecto

set @v_error = @@error

if @v_error <> 0
begin
	return @@error
	return 9
end


return 0
GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Modificando Procedimiento [dbo].[PA_PROYECTOS_ABM_DatosProyectoNuevo]...';


GO
SET QUOTED_IDENTIFIER ON;

SET ANSI_NULLS OFF;


GO
ALTER PROCEDURE [PA_PROYECTOS_ABM_DatosProyectoNuevo] 
/*
ESTE PROCEDIMIENTO REALIZA EL ALTA, LA BAJA O MODIFICACION DE UN REGISTRO DE PROYECTO
VALORES QUE DEVUELVE:
	0 TERMINACION CORRECTA
	1 ERROR EN ALTA O MODIFICACION
	2 ERROR EN BAJA
	3 NO EXISTE REGISTRO QUE SE QUIERE DAR DE BAJA
	4 EXISTE REGISTRO A DAR DE ALTA
	5 NO EXISTE REGISTRO QUE SE QUIERE MODIFICAR
	6 EXISTEN FASES ASOCIADAS AL PROYECTO QUE SE QUIERE BORRAR
	7 ERROR AL ASIGNAR NUEVO NUMERO PROYECTO
	8 ERROR AL CARGAR LOS CODIGOS DE SERVICIO O COLEGIO
	9 ERROR AL MODIFICAR LA FASE
*/

@v_accion as char(1) = 'A',
/* VARIABLES PARA EL PROYECTO*/
/*CLAVE*/
@V_CODIGO_MUNICIPIO as smallint,
@V_AO_PROYECTO as smallint, 
@V_PROY AS INTEGER=0,
/*RESTO DATOS*/
@V_SERVICIO_GESTOR AS SMALLINT=0,
@V_den_proyecto as nvarchar(500) = ' ',
@V_importe_proyecto as float= 0, 
@V_organismo_redactor as char(2)='AY' ,
@V_revision as char(2) = 'NO',
@V_Requiere_PlanSyS as bit = 1,
@V_Requiere_TratMed as bit = 1,
@V_SERVICIO_redactor AS varchar(150) = ' ',
@V_autor as varchar(50)=' ',
@V_COLEGIOOFICIAL AS varchar(150) = ' ',
@V_CARRETERA as char(15) = NULL,
@V_plazo as int=0,
@V_Unidad_Plazo as char(1) = 'm' ,
@V_nro_ejemplares as smallint=0, 
@V_formula as int=NULL,
@V_formula2 as int=NULL,
@V_formula3 as int=NULL,
@V_formula4 as int = NULL, 
@V_presu_gral_ejecucion_material as float=0, 
@V_por_gastos_generales as float=0,  
@V_importe_gastos_generales as float=0, 
@V_por_beneficio_industriales as float=0, 
@V_importe_beneficio_industriales as float=0, 
@V_por_control_calidad as float=0,  
@V_importe_control_calidad as float=0,  
@V_por_iva as float=0, 
@V_iva as float=0, 
@V_por_subcontrata as float=0, 
@V_subcontrata as float=0, 
@V_honorarios_dir as float=0, 
@V_honorarios_red as float=0,  
@V_plan_ss as float,
@V_IVA_Honor_Direcc as bit,
@V_IVA_Honor_Redacc as bit,
@V_fecha_entrega_proyecto as smalldatetime=NULL,
@V_fecha_recepcion_proyecto as smalldatetime=NULL, 
@V_fecha_remision_ayto as smalldatetime=NULL,
@V_fecha_aprobacion_ayto as smalldatetime=NULL, 
@V_fecha_pet_rectificacion as smalldatetime=NULL,
@V_fecha_ent_rectificacion as smalldatetime=NULL,
@V_fecha_pet_reforma as smalldatetime=NULL,
@V_fecha_ent_reforma as smalldatetime=NULL,
@V_fecha_c_infor as smalldatetime=NULL, 
@V_fecha_c_gob as smalldatetime=NULL,
@V_fecha_pit_ref as smalldatetime=NULL,
@V_fecha_eit_ref as smalldatetime=NULL,
@V_fecha_ci_ref as smalldatetime=NULL, 
@V_fecha_cg_ref as smalldatetime=NULL,
@V_fecha_dto as smalldatetime=NULL,
@V_nro_dto as float=NULL,
@V_observaciones as varchar(1000)=NULL, 
@V_estado_proyecto as CHAR(3)='TPR',
@V_SUBVENCION AS BIT= 0,
@v_compartido as bit = 0,
@V_NUM_PROYECTO as integer  output

AS
declare @v_existe as integer
declare @v_existefase as integer
DECLARE @V_ERROR AS INTEGER
declare @v_numero_proyecto as integer
declare @v_colofir as char(2)
declare @v_servred as smallint

/* carga el código del servicio redactor y el colegio oficial de redacción si existen*/

if rtrim(@v_servicio_redactor) <> ' ' 
begin
	select @v_servred = codigo_dpto from tablas..tabladedepartamentos where denominacion = @v_servicio_redactor
	set @v_error = @@error
	if @v_error <> 0
	begin
		RETURN 8			/* ERROR al leer el servicio redactorr*/
	end
end
else 
begin
 	set @v_servred = null
end
if rtrim(@v_colegiooficial) <> ' '
begin
	select @v_colofiR = codigo_redactor from tablas..colegiosoficiales where organismo_redactor = @v_colegiooficial
	set @v_error = @@error
	if @v_error <> 0
	begin
		RETURN 8			/* ERROR al leer el colegio*/
	end
end
ELSE
BEGIN
	SET @V_COLOFIR = NULL
END
/******************************************************************************************************************************************
				COMPRUEBA SI EXISTE EL PROYECTO Y SI TIENE FASES
********************************************************************************************************************************************   */
set @v_existe=0
set @v_existefase =0
IF @V_PROY <> 0 
BEGIN
	SET @V_NUM_PROYECTO=@V_PROY
END	
select  @v_existe = count(1) from proyectos 
WHERE (CODIGO_MUNICIPIO =@v_CODIGO_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_proy) 

select @v_existefase = count(1) from fasesdeproyectos
WHERE (MUNICIPIO =@v_CODIGO_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_proy) 


IF RTRIM(@V_ESTADO_PROYECTO)='  '  OR @V_ESTADO_PROYECTO = NULL
BEGIN
	SET @V_ESTADO_PROYECTO = 'TPR'
END
/*******************************************************************************************************************************************
		INICIO DE LOS PROCESOS SOLICITADOS SEGUN LA ACCION TRANSFERIDA
********************************************************************************************************************************************
                                             EMPIEZA LA  BAJA DEL PROYECTO                                                                                
********************************************************************************************************************************************  */
if @v_accion = 'B' 
begin
             if @v_existe = 0 
	begin
		return 3
	end
	IF @V_EXISTEFASE > 0
	BEGIN
		RETURN 6
	END	
	delete from proyectos 
	WHERE (CODIGO_MUNICIPIO =@v_CODIGO_MUNICIPIO) AND
		 (AO_PROYECTO = @v_ao_PROYECTO) AND 
   		 (NUMERO_PROYECTO = @v_proy) 
 
	set @v_error = @@error
	if @v_error <> 0
	begin
		return 2
	end
	set @v_num_proyecto = @v_proy
	return 0
end
/*********************************************************************************************************************************************/	
/*                                                  TERMINA  LA  BAJA DEL PROYECTO                                                                                  */
/*********************************************************************************************************************************************/



/*********************************************************************************************************************************************/	
/*                                                   EMPIEZA EL ALTA DEL PROYECTO                                                                                  */
/*********************************************************************************************************************************************/

if @v_existe = 1 AND @V_ACCION= 'A' 
begin
	return 4
end
IF @V_EXISTE= 0 AND @V_ACCION='M'
begin
	return 5
end

/* SI ES ALTA GRABA UN REGISTRO SOLO CON LA CLAVE */
IF @V_ACCION='A' 
BEGIN
/*********************************************************************************************************************************************/	
				/*CALCULA EL NUEVO NUMERO DE PROYECTO*/
/*********************************************************************************************************************************************/
	SELECT @v_numero_proyecto = MAX(NUMERO_PROYECTO) FROM Proyectos
	GROUP BY CODIGO_MUNICIPIO,     AO_PROYECTO
	HAVING (CODIGO_MUNICIPIO = @v_codigo_municipio)  AND (AO_PROYECTO = @v_ao_proyecto)
	if @v_numero_proyecto is null 
	begin
 		set @v_numero_proyecto=0
	end
	set @v_numero_proyecto = @v_numero_proyecto + 1
	set @v_num_proyecto=@v_numero_proyecto
	set @v_error = @@error
	if @v_error <> 0 
	begin
		return 7
	END
/*
******************************************************************************************************************************************** 
		 AÑADE UN REGISTRO DE PROYECTO CON LA CLAVE SOLAMENTE
********************************************************************************************************************************************
*/	
	INSERT INTO Proyectos
 		   (CODIGO_MUNICIPIO, AO_PROYECTO, NUMERO_PROYECTO)
	VALUES (@V_CODIGO_MUNICIPIO, @V_AO_PROYECTO,@V_NUM_PROYECTO)
	set @v_error = @@error
	if @v_error <> 0
	begin
		return 1
	end
	SET @V_PROY=@V_NUM_PROYECTO
END
/*******************************************************************************************************************************************/	
/*                                      TERMINA EL ALTA Y CONTINUA CON LA MODIFICACION  DEL PROYECTO                      
********************************************************************************************************************************************
		MODIFICA UN REGISTRO DE PROYECTO (ACCION PARA ALTA O MODIFICACION
********************************************************************************************************************************************
*/	
UPDATE Proyectos
SET
SERVICIO_GESTOR = @V_SERVICIO_GESTOR, 
DEN_PROYECTO = @V_DEN_PROYECTO,
importe_proyecto = @V_importe_proyecto, 
organismo_redactor = @V_organismo_redactor,
servicio_redactor = @V_servRED,
autor = @V_autor, 
colegiooficiaL = @V_colofiR,
carretera = @V_carretera,
plazo = @V_plazo, 
unidadplazo = @V_unidad_plazo, 
nro_ejemplares = @V_nro_ejemplares, 
revision = @V_revision, 
formula = @V_formula,
formula2 = @V_formula2,
formula3 = @V_formula3, 
formula4 = @V_formula4, 
presu_gral_ejecucion_material = @V_presu_gral_ejecucion_material, 
por_gastos_generales=@V_por_gastos_generales, 
importe_gastos_generales = @V_importe_gastos_generales, 
por_beneficio_industriales = @V_por_beneficio_industriales, 
Importe_beneficio_industriales = @V_Importe_beneficio_industriales,
por_control_calidad = @V_por_control_calidad, 
importe_control_calidad = @V_importe_control_calidad, 
por_iva = @V_por_iva, 
iva = @V_iva, 
por_subcontrata = @V_por_subcontrata, 
Subcontrata = @V_Subcontrata, 
honorarios_dir = @V_honorarios_dir,
honorarios_red = @V_honorarios_red, 
ImportePlanSyS = @V_plan_ss,
HD_ExcluidoIVA = @V_IVA_Honor_Direcc,
HR_ExcluidoIVA = @V_IVA_Honor_Redacc,
fecha_entrega_proyecto = @V_fecha_entrega_proyecto, 
fecha_recepcion_proyecto = @V_fecha_recepcion_proyecto, 
fecha_remision_ayto = @V_fecha_remision_ayto, 
fecha_aprobacion_ayto = @V_fecha_aprobacion_ayto, 
fecha_pet_rectificacion = @V_fecha_pet_rectificacion, 
fecha_ent_rectificacion = @V_fecha_ent_rectificacion, 
fecha_pet_reforma = @V_fecha_pet_reforma,
fecha_ent_reforma = @V_fecha_ent_reforma,
fecha_c_infor = @V_fecha_c_infor, 
fecha_c_gob = @V_fecha_c_gob,
Fecha_pit_ref = @V_Fecha_pit_ref,
fecha_eit_ref = @V_fecha_eit_ref, 
fecha_ci_ref = @V_fecha_ci_ref, 
fecha_cg_ref = @V_fecha_cg_ref,
fecha_dto = @V_fecha_dto, 
nro_dto = @V_nro_dto, 
observaciones = @V_observaciones, 
estado_proyecto = @V_estado_proyecto, 
Requiere_PlanSyS = @V_Requiere_PlanSyS,
Requiere_TramAmbiental = @V_Requiere_TratMed,
SUBVENCIONECONREDACCION = @V_SUBVENCION,
compartido = @v_compartido

WHERE (CODIGO_MUNICIPIO =@v_CODIGO_MUNICIPIO) AND (AO_PROYECTO = @v_ao_PROYECTO) AND 
    (NUMERO_PROYECTO = @v_proy) 

set @v_error = @@error

if @v_error <> 0
begin
	return @@error
	return 1
end

/*                                                        
********************************************************************************************************************************************
		MODIFICA LAS FECHAS DE LAS FASES DEL PROYECTO
********************************************************************************************************************************************
*/	


UPDATE FasesDeProyectos
SET
fecha_rem_fase = @V_fecha_entrega_proyecto, 
fecha_ent_fase = @V_fecha_recepcion_proyecto, 
fecha_remision_ayto = @V_fecha_remision_ayto, 
fecha_aprobacion_ayto = @V_fecha_aprobacion_ayto, 
fecha_pet_rectificacion = @V_fecha_pet_rectificacion, 
fecha_ent_rectificacion = @V_fecha_ent_rectificacion, 
fecha_pet_reforma = @V_fecha_pet_reforma,
fecha_ent_reforma = @V_fecha_ent_reforma,
fecha_com_inf = @V_fecha_c_infor, 
fecha_com_gob = @V_fecha_c_gob,
Fecha_pit_ref = @V_Fecha_pit_ref,
fecha_eit_ref = @V_fecha_eit_ref, 
fecha_ci_ref = @V_fecha_ci_ref, 
fecha_cg_ref = @V_fecha_cg_ref,
fecha_dto = @V_fecha_dto,
Nro_dto = @V_nro_dto

WHERE (MUNICIPIO =@v_CODIGO_MUNICIPIO) AND (AO_PROYECTO = @v_ao_PROYECTO) AND 
    (NUMERO_PROYECTO = @v_proy) AND
    fecha_ent_fase = @V_fecha_recepcion_proyecto

set @v_error = @@error

if @v_error <> 0
begin
	return @@error
	return 9
end

UPDATE FasesDeProyectos
SET
revision = @V_revision, 
formula = @V_formula, 
formula2 = @V_formula2, 
formula3 = @V_formula3, 
formula4 = @V_formula4, 
plazo = @V_plazo, 
unidadplazo = @V_Unidad_Plazo

WHERE (MUNICIPIO =@v_CODIGO_MUNICIPIO) AND (AO_PROYECTO = @v_ao_PROYECTO) AND 
    (NUMERO_PROYECTO = @v_proy) AND  (NUMERO_FASE = 1) AND
    importe_fase = @V_importe_proyecto

set @v_error = @@error

if @v_error <> 0
begin
	return @@error
	return 9
end


return 0
GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Modificando Procedimiento [dbo].[PA_PROYECTOS_ABM_DatosProyecto]...';


GO
ALTER PROCEDURE [PA_PROYECTOS_ABM_DatosProyecto] 
/*
ESTE PROCEDIMIENTO REALIZA EL ALTA, LA BAJA O MODIFICACION DE UN REGISTRO DE PROYECTO
VALORES QUE DEVUELVE:
	0 TERMINACION CORRECTA
	1 ERROR EN ALTA O MODIFICACION
	2 ERROR EN BAJA
	3 NO EXISTE REGISTRO QUE SE QUIERE DAR DE BAJA
	4 EXISTE REGISTRO A DAR DE ALTA
	5 NO EXISTE REGISTRO QUE SE QUIERE MODIFICAR
	6 EXISTEN FASES ASOCIADAS AL PROYECTO QUE SE QUIERE BORRAR
	7 ERROR AL ASIGNAR NUEVO NUMERO PROYECTO
	8 ERROR AL CARGAR LOS CODIGOS DE SERVICIO O COLEGIO
	9 ERROR AL MODIFICAR LA FASE
*/

@v_accion as char(1) = 'A',
/* VARIABLES PARA EL PROYECTO*/
/*CLAVE*/
@V_CODIGO_MUNICIPIO as smallint,
@V_AO_PROYECTO as smallint, 
@V_PROY AS INTEGER=0,
/*RESTO DATOS*/
@V_SERVICIO_GESTOR AS SMALLINT=0,
@V_den_proyecto as varchar(150) = ' ',
@V_importe_proyecto as float= 0, 
@V_organismo_redactor as char(2)='AY' ,
@V_revision as char(2) = 'NO',
@V_Requiere_PlanSyS as bit = 1,
@V_Requiere_TratMed as bit = 1,
@V_SERVICIO_redactor AS varchar(150) = ' ',
@V_autor as varchar(50)=' ',
@V_COLEGIOOFICIAL AS varchar(150) = ' ',
@V_CARRETERA as char(15) = NULL,
@V_plazo as int=0,
@V_Unidad_Plazo as char(1) = 'm' ,
@V_nro_ejemplares as smallint=0, 
@V_formula as int=NULL,
@V_formula2 as int=NULL,
@V_formula3 as int=NULL,
@V_formula4 as int = NULL, 
@V_presu_gral_ejecucion_material as float=0, 
@V_por_gastos_generales as float=0,  
@V_importe_gastos_generales as float=0, 
@V_por_beneficio_industriales as float=0, 
@V_importe_beneficio_industriales as float=0, 
@V_por_control_calidad as float=0,  
@V_importe_control_calidad as float=0,  
@V_por_iva as float=0, 
@V_iva as float=0, 
@V_por_subcontrata as float=0, 
@V_subcontrata as float=0, 
@V_honorarios_dir as float=0, 
@V_honorarios_red as float=0,  
@V_plan_ss as float,
@V_IVA_Honor_Direcc as bit,
@V_IVA_Honor_Redacc as bit,
@V_fecha_entrega_proyecto as smalldatetime=NULL,
@V_fecha_recepcion_proyecto as smalldatetime=NULL, 
@V_fecha_remision_ayto as smalldatetime=NULL,
@V_fecha_aprobacion_ayto as smalldatetime=NULL, 
@V_fecha_pet_rectificacion as smalldatetime=NULL,
@V_fecha_ent_rectificacion as smalldatetime=NULL,
@V_fecha_pet_reforma as smalldatetime=NULL,
@V_fecha_ent_reforma as smalldatetime=NULL,
@V_fecha_c_infor as smalldatetime=NULL, 
@V_fecha_c_gob as smalldatetime=NULL,
@V_fecha_pit_ref as smalldatetime=NULL,
@V_fecha_eit_ref as smalldatetime=NULL,
@V_fecha_ci_ref as smalldatetime=NULL, 
@V_fecha_cg_ref as smalldatetime=NULL,
@V_fecha_dto as smalldatetime=NULL,
@V_nro_dto as float=NULL,
@V_observaciones as nvarchar(1000)=NULL, 
@V_estado_proyecto as CHAR(3)='TPR',
@V_SUBVENCION AS BIT= 0,
@v_compartido as bit = 0,
@V_NUM_PROYECTO as integer  output

AS
declare @v_existe as integer
declare @v_existefase as integer
DECLARE @V_ERROR AS INTEGER
declare @v_numero_proyecto as integer
declare @v_colofir as char(2)
declare @v_servred as smallint

/* carga el código del servicio redactor y el colegio oficial de redacción si existen*/

if rtrim(@v_servicio_redactor) <> ' ' 
begin
	select @v_servred = codigo_dpto from tablas..tabladedepartamentos where denominacion = @v_servicio_redactor
	set @v_error = @@error
	if @v_error <> 0
	begin
		RETURN 8			/* ERROR al leer el servicio redactorr*/
	end
end
else 
begin
 	set @v_servred = null
end
if rtrim(@v_colegiooficial) <> ' '
begin
	select @v_colofiR = codigo_redactor from tablas..colegiosoficiales where organismo_redactor = @v_colegiooficial
	set @v_error = @@error
	if @v_error <> 0
	begin
		RETURN 8			/* ERROR al leer el colegio*/
	end
end
ELSE
BEGIN
	SET @V_COLOFIR = NULL
END
/******************************************************************************************************************************************
				COMPRUEBA SI EXISTE EL PROYECTO Y SI TIENE FASES
********************************************************************************************************************************************   */
set @v_existe=0
set @v_existefase =0
IF @V_PROY <> 0 
BEGIN
	SET @V_NUM_PROYECTO=@V_PROY
END	
select  @v_existe = count(1) from proyectos 
WHERE (CODIGO_MUNICIPIO =@v_CODIGO_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_proy) 

select @v_existefase = count(1) from fasesdeproyectos
WHERE (MUNICIPIO =@v_CODIGO_MUNICIPIO) AND
(AO_PROYECTO = @v_ao_PROYECTO) AND 
(NUMERO_PROYECTO = @v_proy) 


IF RTRIM(@V_ESTADO_PROYECTO)='  '  OR @V_ESTADO_PROYECTO = NULL
BEGIN
	SET @V_ESTADO_PROYECTO = 'TPR'
END
/*******************************************************************************************************************************************
		INICIO DE LOS PROCESOS SOLICITADOS SEGUN LA ACCION TRANSFERIDA
********************************************************************************************************************************************
                                             EMPIEZA LA  BAJA DEL PROYECTO                                                                                
********************************************************************************************************************************************  */
if @v_accion = 'B' 
begin
             if @v_existe = 0 
	begin
		return 3
	end
	IF @V_EXISTEFASE > 0
	BEGIN
		RETURN 6
	END	
	delete from proyectos 
	WHERE (CODIGO_MUNICIPIO =@v_CODIGO_MUNICIPIO) AND
		 (AO_PROYECTO = @v_ao_PROYECTO) AND 
   		 (NUMERO_PROYECTO = @v_proy) 
 
	set @v_error = @@error
	if @v_error <> 0
	begin
		return 2
	end
	set @v_num_proyecto = @v_proy
	return 0
end
/*********************************************************************************************************************************************/	
/*                                                  TERMINA  LA  BAJA DEL PROYECTO                                                                                  */
/*********************************************************************************************************************************************/



/*********************************************************************************************************************************************/	
/*                                                   EMPIEZA EL ALTA DEL PROYECTO                                                                                  */
/*********************************************************************************************************************************************/

if @v_existe = 1 AND @V_ACCION= 'A' 
begin
	return 4
end
IF @V_EXISTE= 0 AND @V_ACCION='M'
begin
	return 5
end

/* SI ES ALTA GRABA UN REGISTRO SOLO CON LA CLAVE */
IF @V_ACCION='A' 
BEGIN
/*********************************************************************************************************************************************/	
				/*CALCULA EL NUEVO NUMERO DE PROYECTO*/
/*********************************************************************************************************************************************/
	SELECT @v_numero_proyecto = MAX(NUMERO_PROYECTO) FROM Proyectos
	GROUP BY CODIGO_MUNICIPIO,     AO_PROYECTO
	HAVING (CODIGO_MUNICIPIO = @v_codigo_municipio)  AND (AO_PROYECTO = @v_ao_proyecto)
	if @v_numero_proyecto is null 
	begin
 		set @v_numero_proyecto=0
	end
	set @v_numero_proyecto = @v_numero_proyecto + 1
	set @v_num_proyecto=@v_numero_proyecto
	set @v_error = @@error
	if @v_error <> 0 
	begin
		return 7
	END
/*
******************************************************************************************************************************************** 
		 AÑADE UN REGISTRO DE PROYECTO CON LA CLAVE SOLAMENTE
********************************************************************************************************************************************
*/	
	INSERT INTO Proyectos
 		   (CODIGO_MUNICIPIO, AO_PROYECTO, NUMERO_PROYECTO)
	VALUES (@V_CODIGO_MUNICIPIO, @V_AO_PROYECTO,@V_NUM_PROYECTO)
	set @v_error = @@error
	if @v_error <> 0
	begin
		return 1
	end
	SET @V_PROY=@V_NUM_PROYECTO
END
/*******************************************************************************************************************************************/	
/*                                      TERMINA EL ALTA Y CONTINUA CON LA MODIFICACION  DEL PROYECTO                      
********************************************************************************************************************************************
		MODIFICA UN REGISTRO DE PROYECTO (ACCION PARA ALTA O MODIFICACION
********************************************************************************************************************************************
*/	
UPDATE Proyectos
SET
SERVICIO_GESTOR = @V_SERVICIO_GESTOR, 
DEN_PROYECTO = @V_DEN_PROYECTO,
importe_proyecto = @V_importe_proyecto, 
organismo_redactor = @V_organismo_redactor,
servicio_redactor = @V_servRED,
autor = @V_autor, 
colegiooficiaL = @V_colofiR,
carretera = @V_carretera,
plazo = @V_plazo, 
unidadplazo = @V_unidad_plazo, 
nro_ejemplares = @V_nro_ejemplares, 
revision = @V_revision, 
formula = @V_formula,
formula2 = @V_formula2,
formula3 = @V_formula3, 
formula4 = @V_formula4, 
presu_gral_ejecucion_material = @V_presu_gral_ejecucion_material, 
por_gastos_generales=@V_por_gastos_generales, 
importe_gastos_generales = @V_importe_gastos_generales, 
por_beneficio_industriales = @V_por_beneficio_industriales, 
Importe_beneficio_industriales = @V_Importe_beneficio_industriales,
por_control_calidad = @V_por_control_calidad, 
importe_control_calidad = @V_importe_control_calidad, 
por_iva = @V_por_iva, 
iva = @V_iva, 
por_subcontrata = @V_por_subcontrata, 
Subcontrata = @V_Subcontrata, 
honorarios_dir = @V_honorarios_dir,
honorarios_red = @V_honorarios_red, 
ImportePlanSyS = @V_plan_ss,
HD_ExcluidoIVA = @V_IVA_Honor_Direcc,
HR_ExcluidoIVA = @V_IVA_Honor_Redacc,
fecha_entrega_proyecto = @V_fecha_entrega_proyecto, 
fecha_recepcion_proyecto = @V_fecha_recepcion_proyecto, 
fecha_remision_ayto = @V_fecha_remision_ayto, 
fecha_aprobacion_ayto = @V_fecha_aprobacion_ayto, 
fecha_pet_rectificacion = @V_fecha_pet_rectificacion, 
fecha_ent_rectificacion = @V_fecha_ent_rectificacion, 
fecha_pet_reforma = @V_fecha_pet_reforma,
fecha_ent_reforma = @V_fecha_ent_reforma,
fecha_c_infor = @V_fecha_c_infor, 
fecha_c_gob = @V_fecha_c_gob,
Fecha_pit_ref = @V_Fecha_pit_ref,
fecha_eit_ref = @V_fecha_eit_ref, 
fecha_ci_ref = @V_fecha_ci_ref, 
fecha_cg_ref = @V_fecha_cg_ref,
fecha_dto = @V_fecha_dto, 
nro_dto = @V_nro_dto, 
observaciones = @V_observaciones, 
estado_proyecto = @V_estado_proyecto, 
Requiere_PlanSyS = @V_Requiere_PlanSyS,
Requiere_TramAmbiental = @V_Requiere_TratMed,
SUBVENCIONECONREDACCION = @V_SUBVENCION,
compartido = @v_compartido

WHERE (CODIGO_MUNICIPIO =@v_CODIGO_MUNICIPIO) AND (AO_PROYECTO = @v_ao_PROYECTO) AND 
    (NUMERO_PROYECTO = @v_proy) 

set @v_error = @@error

if @v_error <> 0
begin
	return @@error
	return 1
end

/*                                                        
********************************************************************************************************************************************
		MODIFICA LAS FECHAS DE LAS FASES DEL PROYECTO
********************************************************************************************************************************************
*/	


UPDATE FasesDeProyectos
SET
fecha_rem_fase = @V_fecha_entrega_proyecto, 
fecha_ent_fase = @V_fecha_recepcion_proyecto, 
fecha_remision_ayto = @V_fecha_remision_ayto, 
fecha_aprobacion_ayto = @V_fecha_aprobacion_ayto, 
fecha_pet_rectificacion = @V_fecha_pet_rectificacion, 
fecha_ent_rectificacion = @V_fecha_ent_rectificacion, 
fecha_pet_reforma = @V_fecha_pet_reforma,
fecha_ent_reforma = @V_fecha_ent_reforma,
fecha_com_inf = @V_fecha_c_infor, 
fecha_com_gob = @V_fecha_c_gob,
Fecha_pit_ref = @V_Fecha_pit_ref,
fecha_eit_ref = @V_fecha_eit_ref, 
fecha_ci_ref = @V_fecha_ci_ref, 
fecha_cg_ref = @V_fecha_cg_ref,
fecha_dto = @V_fecha_dto,
Nro_dto = @V_nro_dto

WHERE (MUNICIPIO =@v_CODIGO_MUNICIPIO) AND (AO_PROYECTO = @v_ao_PROYECTO) AND 
    (NUMERO_PROYECTO = @v_proy) AND
    fecha_ent_fase = @V_fecha_recepcion_proyecto

set @v_error = @@error

if @v_error <> 0
begin
	return @@error
	return 9
end

UPDATE FasesDeProyectos
SET
revision = @V_revision, 
formula = @V_formula, 
formula2 = @V_formula2, 
formula3 = @V_formula3, 
formula4 = @V_formula4, 
plazo = @V_plazo, 
unidadplazo = @V_Unidad_Plazo

WHERE (MUNICIPIO =@v_CODIGO_MUNICIPIO) AND (AO_PROYECTO = @v_ao_PROYECTO) AND 
    (NUMERO_PROYECTO = @v_proy) AND  (NUMERO_FASE = 1) AND
    importe_fase = @V_importe_proyecto

set @v_error = @@error

if @v_error <> 0
begin
	return @@error
	return 9
end


return 0
GO
PRINT N'Modificando Procedimiento [aida].[PA_EDICTOS_A_AñadirObra]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
ALTER PROCEDURE [aida].[PA_EDICTOS_A_AñadirObra] 
/*
ESTE PROCEDIMIENTO GRABA EN PUBLICACIONES EXPEDIENTES 
PARA EL CASO DE QUE TENGA YA UN EDICTO Y DESEE AÑADIR UNA NUEVA OBRA, DEVUELVE:
 0 PROCESO CORRECTO
 6 ERROR AL GRABAR PUBLICACIONESEXPEDIENTES
 
*/
@BDPrueba char(2),
@V_WHERE AS VARCHAR(800),
@V_tipoexp as char(2) = 'OB',
@v_AoPubli as smallint , 
@v_CodPrensa as char(5) = 'BOP', 
@v_FechaConfeccion as SMALLDATETIME=NULL,
@v_PorcIva as float =0,
@v_Creador as char(7) = 'XXX',
@V_UltNroPublicacion AS SMALLINT =1 OUTPUT,
@v_UltNroDocumento AS SMALLINT =0 OUTPUT
AS
declare @v_añoactual as smallint
Declare @v_error as integer
Declare @v_sentencia as char
/* COMIENZA LA TRANSACCION DEL EDICTO*/
BEGIN TRANSACTION
/* GENERA UN REGISTRO POR EXPEDIENTE/PUBLICACION*/
if @BDPrueba='no'
BEGIN
SET @V_SENTENCIA = 'INSERT INTO PUBLICACIONESEXPEDIENTES (aocontratacion,tipoexpediente,numexpediente,AOPUBLI,codprensa,NUMPUBLI)
SELECT aocontratacion, tipoexpediente, NumExpediente,' + rtrim(@V_AÑOACTUAL) + ' AS AOPUBLI,"BOP" as CodPrensa,' + rtrim(@V_ULTNROPUBLICACION)+ ' AS NUMPUBLI 
FROM expedientecontratacion ' + rtrim(@V_WHERE) + ' AND EXPEDIENTECONTRATACION.TIPOEXPEDIENTE = "' + rtrim(@V_TIPOEXP) + '"'
EXECUTE (@V_SENTENCIA)
set @v_error = @@error
if @v_error <> 0
BEGIN
 ROLLBACK
 return 6  /* ERROR AL GRABAR PUBLICACIONESEXPEDIENTES*/
END
END
COMMIT
RETURN 0
GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Modificando Procedimiento [aida].[pruebapar]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
ALTER PROCEDURE [aida].[pruebapar]
@par1 as int = null,
@par2 as int


 AS


if @par1 is null
--if len(rtrim(@par1)) = 0
begin
	print 'el parámetro es nulo'
end
else
begin
	print 'el parámetro vale:' + rtrim(@par1)

end
GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Modificando Procedimiento [dbo].[PA_PROY_L_BusquedaProyectos1]...';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER OFF;


GO
ALTER PROCEDURE [dbo].[PA_PROY_L_BusquedaProyectos1]
/* ESTE PROCEDIMIENTO LEE TODOS LOS PROYECTOS QUE CUMPLEN LAS CONDICIONES ESPECIFICADAS EN LAS VARIABLES TRANSFERIDAS

    GENERA UN RESULTADO CON LOS DATOS ENCONTRADOS*/
@V_MUNICIPIO as CHAR(7) ,		/* CODIGO DEL  MUNICIPIO*/
@V_AO_PROYECTO as CHAR(7) , 	/* Año del proyecto*/
@V_NUM_PROYECTO AS CHAR(7) ,	/*Nº del proyecto*/
@V_SERVICIO_GESTOR as CHAR(7) ,	/*Servicio que lo gestiona*/
@V_plan as char(7)  ,
@v_numobra as CHAR(7) ,
@v_subref as CHAR(7),
@v_aoplan as CHAR(7) ,
@V_organismo_redactor as char(7) ,
@V_SERVICIO_REDACTOR as CHAR(7) ,
@v_autor as char(80) ,
@V_COLEGIO_OFICIALR AS CHAR(7) ,
@V_SUBVENCION_ECONOMICAR AS BIT = 0,
@V_organismo_direccion as char(7) ,
@V_SERVICIO_direccion as CHAR(7) ,
@v_DirTecnico as char(80),
@V_COLEGIO_OFICIALD AS CHAR(7) ,
@V_SUBVENCION_ECONOMICAD AS BIT = 0



 AS
DECLARE @V_ERROR AS INT
DECLARE @SENTENCIA AS VARCHAR (8000)
DECLARE @SENWHERE AS VARCHAR(8000)
/*Valores devueltos:
	0 -- Funcionamento correcto
	1 -- Fallo en la búsqueda

*/
 SET @SENWHERE = ' WHERE  ' 
 SET @SENTENCIA =  'SELECT min(Proyectos.Servicio_Gestor) as servicio_gestor, 
    Proyectos.CODIGO_MUNICIPIO, Proyectos.AO_PROYECTO, 
    Proyectos.NUMERO_PROYECTO, Proyectos.den_proyecto, 
    Proyectos.importe_proyecto, 
    MIN(FasesDeProyectos.Codigo_Plan) AS codigo_plan, 
    MIN(FasesDeProyectos.referencia) AS referencia, 
    MIN(FasesDeProyectos.subreferencia) AS subreferencia, 
    MIN(FasesDeProyectos.ao_ejecucion_obra) 
    AS ao_ejecucion_obra, MIN(FasesDeProyectos.importe_fase) 
    AS importe_fase, 
    MIN(Tablas.dbo.TablaDeMunicipios.nombre_municipio) 
    AS nombre_municipio, 
    max(FasesDeProyectos.numero_fase) as obras,
     max(cast(compartido as char(1))) as compartido
    
FROM FasesDeProyectos RIGHT OUTER JOIN
    Tablas.dbo.TablaDeMunicipios RIGHT OUTER JOIN
    Proyectos ON 
    Tablas.dbo.TablaDeMunicipios.codigo_municipio = Proyectos.CODIGO_MUNICIPIO
     ON 
    FasesDeProyectos.MUNICIPIO = Proyectos.CODIGO_MUNICIPIO
     AND 
    FasesDeProyectos.AO_PROYECTO = Proyectos.AO_PROYECTO AND
     FasesDeProyectos.NUMERO_PROYECTO = Proyectos.NUMERO_PROYECTO '

IF @V_SERVICIO_GESTOR <> '%' 
BEGIN
  SET @SENWHERE =  @SENWHERE + '  (proyectos.servicio_gestor  LIKE ' +   RTRIM(@V_servicio_gestor)  +  ' OR COMPARTIDO = 1) '
  SET @SENTENCIA =  @SENTENCIA + @SENWHERE
  SET @SENWHERE = ' AND ' 
END

IF @V_organismo_redactor <> '%'
BEGIN
  SET @SENWHERE = @SENWHERE + '  proyectos.organismo_redactor LIKE  ' +  RTRIM(@V_organismo_redactor) 
  SET @SENTENCIA =  @SENTENCIA + @SENWHERE
  SET @SENWHERE = ' AND ' 
END

/*IF @V_servicio_redactor <> '%'
BEGIN 
    SET @SENWHERE = @SENWHERE + '  proyectos.servicio_REDACTOR LIKE   ' +  RTRIM(@V_servicio_redactor) 
  SET @SENTENCIA =  @SENTENCIA + @SENWHERE
  SET @SENWHERE = ' AND ' 
END
*/

IF @V_AUTOR <> '%'
BEGIN
  SET @SENWHERE = @SENWHERE + '  PROYECTOS.AUTOR LIKE  ' + '''%'  +  RTRIM(@V_AUTOR) + '%'''  
  SET @SENTENCIA =  @SENTENCIA + @SENWHERE
  SET @SENWHERE = ' AND ' 
END

IF @V_organismo_DIRECCION <> '%'
BEGIN
  SET @SENWHERE = @SENWHERE +  '   FASESDEPROYECTOS.organismo_DIRECCION LIKE ' +  RTRIM(@V_organismo_direccion ) 
  SET @SENTENCIA =  @SENTENCIA + @SENWHERE
  SET @SENWHERE = ' AND ' 
END

IF @V_SERVICIO_DIRECCION <> '%'
BEGIN
   SET @SENWHERE = @SENWHERE + '  FASESDEPROYECTOS.SERVICIO_DIRECCION LIKE  ' +   RTRIM(@V_servicio_direccion) 
  SET @SENTENCIA =  @SENTENCIA + @SENWHERE
  SET @SENWHERE = ' AND ' 
END

IF @V_DIRTECNICO <> '%'
BEGIN
  SET @SENWHERE = @SENWHERE + '    FasesDeProyectos.DIRECTOR_TECNICO_OBRA LIKE  ' + '''%'  +  RTRIM(@v_DirTecnico) + '%'''  
  SET @SENTENCIA =  @SENTENCIA + @SENWHERE
  SET @SENWHERE = ' AND ' 
END

IF @V_Colegio_OficialR <> '%'
BEGIN
    SET @SENWHERE = @SENWHERE +  '  PROYECTOS.COLEGIOOFICIAL like   ' + rtrim(@V_Colegio_OficialR) 
  SET @SENTENCIA =  @SENTENCIA + @SENWHERE
  SET @SENWHERE = ' AND ' 
END

IF @V_AO_PROYECTO <> '%'
BEGIN
 
  SET @SENWHERE = @SENWHERE + '   (Proyectos.AO_PROYECTO  LIKE  ' + rtrim(@V_AO_PROYECTO)
  --ESTO ES LO NUEVO?????????????????????????
  SET @SENWHERE = @SENWHERE + ' OR '
  SET @SENWHERE = @SENWHERE + ' FasesDeProyectos.AO_FASE LIKE ' + rtrim(@V_AO_PROYECTO) + ')'
  SET @SENTENCIA =  @SENTENCIA + @SENWHERE
  SET @SENWHERE = ' AND ' 
END

IF @V_NUM_PROYECTO <> '%'
BEGIN
  SET @SENWHERE = @SENWHERE + '  Proyectos.NUMERO_PROYECTO LIKE  ' +  RTRIM(@V_NUM_PROYECTO)  
  SET @SENTENCIA =  @SENTENCIA + @SENWHERE
  SET @SENWHERE = ' AND ' 
END

IF @V_colegio_oficialD <> '%'
BEGIN
  SET @SENWHERE = @SENWHERE + '    FasesDeProyectos..COLEGIOOFICIALDIRECCION LIKE  ' +  RTRIM(@V_colegio_oficialD)
  SET @SENTENCIA =  @SENTENCIA + @SENWHERE
  SET @SENWHERE = ' AND ' 
END
IF @V_plan <> '%'
BEGIN
 SET @SENWHERE = @SENWHERE + '  FasesDeProyectos.CODIGO_PLAN LIKE  ' + '''%'  + RTRIM(@V_plan) + '%'''  
  SET @SENTENCIA =  @SENTENCIA + @SENWHERE
  SET @SENWHERE = ' AND ' 
END
IF @v_numobra <> '%'
BEGIN
 SET @SENWHERE = @SENWHERE + '  FasesDeProyectos.REFERENCIA  LIKE  ' +  RTRIM(@v_numobra) 
  SET @SENTENCIA =  @SENTENCIA + @SENWHERE
  SET @SENWHERE = ' AND ' 
END
IF @v_subref <> '%'
BEGIN
 SET @SENWHERE = @SENWHERE + '  FasesDeProyectos.SUBREFERENCIA LIKE  ' +  RTRIM(@v_subref)  
  SET @SENTENCIA =  @SENTENCIA + @SENWHERE
  SET @SENWHERE = ' AND ' 
END
IF @v_aoplan <> '%'
BEGIN
 SET @SENWHERE = @SENWHERE + '    FasesDeProyectos.AO_EJECUCION_OBRA  LIKE  ' +  RTRIM(@v_aoplan)  
  SET @SENTENCIA =  @SENTENCIA + @SENWHERE
  SET @SENWHERE = ' AND ' 
END
IF @V_municipio <> '%'
BEGIN
 SET @SENWHERE = @SENWHERE + '  PROYECTOS.CODIGO_MUNICIPIO LIKE  ' +   RTRIM(@V_municipio)  
  SET @SENTENCIA =  @SENTENCIA + @SENWHERE
  SET @SENWHERE = ' AND ' 

END


SET @SENTENCIA = @SENTENCIA + ' GROUP BY Proyectos.CODIGO_MUNICIPIO, 
    Proyectos.AO_PROYECTO, Proyectos.NUMERO_PROYECTO, 
    Proyectos.den_proyecto, 
    Proyectos.importe_proyecto order by proyectos.codigo_municipio, proyectos.ao_proyecto, proyectos.numero_proyecto	'

PRINT @SENTENCIA
EXECUTE (@SENTENCIA)


set @v_error = @@error

if @v_error <> 0
begin
	return 1
end


return 0
GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Modificando Procedimiento [dbo].[PA_PROYECTOS_L_ObrasPendientesProyecto]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
ALTER PROCEDURE [dbo].[PA_PROYECTOS_L_ObrasPendientesProyecto] 
@V_MUNICIPIO as CHAR(7) ,		/* CODIGO DEL  MUNICIPIO*/
@v_servicio_gestor as SMALLINT,
@V_plan as char(7)  ,
@v_numobra as char(7)  ,
@v_subref as char(7)  ,
@v_aoplan as char(7),
@V_organismo_redactor as char(7) ,
@V_SERVICIO_REDACTOR as CHAR(7) ,
@V_SUBVENCION_ECONOMICAR AS BIT = 0,
@V_organismo_direccion as char(7) ,
@V_SERVICIO_direccion as CHAR(7) ,
@V_SUBVENCION_ECONOMICAD AS BIT = 0


AS

declare @v_error as INT
DECLARE @V_SEN AS VARCHAR (8000) 


SET @V_SEN =  'SELECT DatosInicioDeObras.Codigo_Plan, 
    DatosInicioDeObras.numero_obra, 
    DatosInicioDeObras.subreferencia, 
    DatosInicioDeObras.ao_ejecucion, 
    DatosInicioDeObras.nombre_obra1, 
    DatosInicioDeObras.nombre_obra2, 
    DatosInicioDeObras.nombre_obra3, 
    DatosInicioDeObras.municipio,
    DatosInicioDeObras.carretera, 
    DatosInicioDeObras.peticion_ayuda_tec, 
    DatosInicioDeObras.forma_ejecucion, 
    DatosInicioDeObras.codigo_estado_obra, 
    Ayuda_Tecnica.dpto_redactor, 
    Ayuda_Tecnica.departamento_direccion, 
    Ayuda_Tecnica.pasado, 
    Ayuda_Tecnica.SubvencionEconomicaR, 
    Ayuda_Tecnica.SubvencionEconomicaD, 
    Tablas.dbo.TablaDeMunicipios.nombre_municipio, 
    ImportesDeObras.importe_aprobado,
    Tablas.dbo.Planes.codigo_depar_reservado
FROM Tablas.dbo.TablaDeMunicipios RIGHT OUTER JOIN
    DatosInicioDeObras LEFT OUTER JOIN
    ImportesDeObras ON 
    DatosInicioDeObras.Codigo_Plan = ImportesDeObras.Codigo_Plan
     AND 
    DatosInicioDeObras.numero_obra = ImportesDeObras.numero_obra
     AND 
    DatosInicioDeObras.subreferencia = ImportesDeObras.subreferencia
     AND 
    DatosInicioDeObras.ao_ejecucion = ImportesDeObras.ao_ejecucion
     ON 
    Tablas.dbo.TablaDeMunicipios.codigo_municipio = DatosInicioDeObras.municipio
     LEFT OUTER JOIN
    Ayuda_Tecnica ON 
    DatosInicioDeObras.Codigo_Plan = Ayuda_Tecnica.Codigo_Plan
     AND 
    DatosInicioDeObras.subreferencia = Ayuda_Tecnica.subreferencia
     AND 
    DatosInicioDeObras.ao_ejecucion = Ayuda_Tecnica.ao_ejecucion
     AND 
    DatosInicioDeObras.numero_obra = Ayuda_Tecnica.numero_obra 
    LEFT OUTER JOIN
    Tablas.dbo.Planes ON 
    DatosInicioDeObras.Codigo_Plan = Tablas.dbo.Planes.codigo_plan 

WHERE   DatosInicioDeObras.codigo_estado_obra =  ' + '''PPY'''  -- + ' AND Tablas.dbo.Planes.codigo_depar_reservado = '   +  RTRIM(@V_SERVICIO_GESTOR ) 


IF @V_MUNICIPIO <> '%' 
BEGIN
  SET @V_SEN = @V_SEN + '   AND DatosInicioDeObras.municipio LIKE ' +   RTRIM(@V_MUNICIPIO)
END

IF @V_PLAN <> '%' 
BEGIN
  SET @V_SEN = @V_SEN + '   AND DatosInicioDeObras.Codigo_Plan =' +  '''' + @V_PLAN + ''''
END

IF @V_NUMOBRA <> '%' 
BEGIN
  SET @V_SEN = @V_SEN + '  AND  DatosInicioDeObras.numero_obra = ' +  @V_NUMOBRA
END

IF @V_SUBREF <> '%' 
BEGIN
  SET @V_SEN = @V_SEN + '  AND  DatosInicioDeObras.subreferencia =  ' +  @V_SUBREF
END

IF @V_AOPLAN <> '%' 
BEGIN
  SET @V_SEN = @V_SEN + '  AND  DatosInicioDeObras.ao_ejecucion = ' + @V_AOPLAN
END

IF @V_organismo_redactor = 'DP' OR  @V_organismo_direccion = 'DP'
BEGIN
  SET @V_SEN = @V_SEN + '  AND  DatosInicioDeObras.peticion_ayuda_tec = "SI"'
END

IF @V_organismo_redactor = 'AY' OR  @V_organismo_direccion = 'AY' OR @V_organismo_redactor = 'JA' OR  @V_organismo_direccion = 'JA'
BEGIN
  SET @V_SEN = @V_SEN + '  AND  DatosInicioDeObras.peticion_ayuda_tec = "NO"'
END

IF @V_SERVICIO_REDACTOR <> '%' 
BEGIN
  SET @V_SEN = @V_SEN + '  AND  Ayuda_Tecnica.dpto_redactor LIKE ' + RTRIM(@V_SERVICIO_REDACTOR)
END

IF @V_SERVICIO_direccion <> '%' 
BEGIN
  SET @V_SEN = @V_SEN + '  AND  Ayuda_Tecnica.departamento_direccion LIKE ' + RTRIM(@V_SERVICIO_direccion)
END

IF @V_SUBVENCION_ECONOMICAR=1
BEGIN
  SET @V_SEN = @V_SEN + '  AND  Ayuda_Tecnica.SubvencionEconomicaR = "SI"'
END

IF @V_SUBVENCION_ECONOMICAD=1
BEGIN
  SET @V_SEN = @V_SEN + '  AND  Ayuda_Tecnica.SubvencionEconomicaD = "SI"'
END


SET @V_SEN = @V_SEN + ' order by   DatosInicioDeObras.municipio, DatosInicioDeObras.Codigo_Plan,     DatosInicioDeObras.ao_ejecucion,  DatosInicioDeObras.numero_obra'

--print @V_SEN

EXECUTE (@V_SEN)



set @v_error = @@error

if @v_error <> 0
begin
	return 1
end


return 0
GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Modificando Procedimiento [dbo].[PA_R_CargaComboDepart]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
ALTER PROCEDURE [dbo].[PA_R_CargaComboDepart] AS

/*Para cargar el combo de departamentos para el listado de Petición Informe Proyecto*/

/*Sólo deben salir:
	- Servicio de Arquitectura -->510
	- Servicio de Vías y Obras -->520
	- Servicio de Actividades Industriales -->601
	- Unidad de Disciplina Urbanística y Viaria, y suspensión de proyectos. -->521
*/

SELECT codigo_dpto as clave, denominacion
FROM tablas.dbo.TablaDeDepartamentos
WHERE (CODIGO_DPTO = 520 OR
    CODIGO_DPTO = 510 OR
    CODIGO_DPTO = 601 OR    
    CODIGO_DPTO = 521 OR
	CODIGO_DPTO = 1000)
GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Modificando Procedimiento [dbo].[PA_R_DatosMunicipios]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
ALTER PROCEDURE [dbo].[PA_R_DatosMunicipios] 
/*Lee los datos del municipio para los listados */

@NombreMun varchar (30)
AS

SELECT codigo_municipio, nombre_municipio, zona, cp, direccion
FROM Tablas.dbo.TablaDeMunicipios
WHERE nombre_municipio=rtrim(@NombreMun)
GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Modificando Procedimiento [dbo].[PA_R_JefeServAdmin]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
ALTER PROCEDURE [PA_R_JefeServAdmin] 
@CodServicio as int

as

Select Jefatura,denominacion,jefatura_adjunta,deno_abrev_listados,jefaturacompleta
from Tablas..tabladedepartamentos
where codigo_dpto=rtrim(@CodServicio)
GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Modificando Procedimiento [dbo].[PA_Tecnicos_ABM_ActualizaTecnicos]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
ALTER PROCEDURE [dbo].[PA_Tecnicos_ABM_ActualizaTecnicos] 

/* VALORES DE RETORNO
	0  FUNCIONAMIENTO CORRECTO
	1  NO EXISTE EL REGISTRO A DAR DE BAJA
	2  ERROR AL DAR DE BAJA 
	3  YA EXISTE EL REGISTRO A DAR DE ALTA
	4  NO EXISTE REGISTRO A MODIFICAR
	5  ERROR EN LA SELECCIÓN DEL SERVICIO
	6  ERROR EN LA SELECCIÓN DE LA PROFESIÓN
	7  ERROR EN LA SELECCIÓN DEL MUNICIPIO
	8  ERROR EN LA SELECCIÓN DEL AYUNTAMIENTO
	9  ERROR INSERTAR EN LA TABLA DE TECNICOS
           10  ERROR EN LA MODIFICACIÓN TABLA DE TECNICOS
*/


@BDprueba as char(2),
@v_accion as char(1),
@V_ORGANISMO as char(50)='%', 
@V_SERVICIO as char(400)='%',
@V_NOMBRETEC as char(50)='%', 
@V_EMPRESA as char(50)='%', 
@V_APE1TEC AS char(50)='%', 
@V_APE2TEC as char(50)='%', 
@V_SEXOTEC as char(1)='%', 
@V_DNITEC as char(15)='%', 
@V_PROFESION as char(50)='%', 
@V_ACTIVIDAD as char(50)='%', 
@V_DOMTEC as char(60)='%', 
@V_LOCTEC as char(50)='%', 
@V_CPTEC as char(10)='%', 
@V_MUNICIPIO as char(50)='%', 
@V_PROVINCIA as char(50)='%', 
@V_AYTO AS char(50)='%',
@V_TELTEC as char(20)='%', 
@V_MOVIL as char(20)='%', 
@V_FAX as char(20)='%', 
@V_EMAILTEC as char(50)='%',
@V_ANULADO as bit=0,
@V_OBSERVACIONES AS char(200),
@V_CODTEC  as smallint =0 output,
@V_DIRECTORLAB as char(100),
@PJuridica as bit=0,
@Zona char(1)

AS

declare @v_existe as integer
declare @v_orgtec as char(2)
declare @v_CodAct as smallint

declare @v_servtec as smallint
declare @v_codprofesion as smallint
declare @v_muntec as smallint
declare @v_provtec as smallint
declare @v_aytotec as smallint
set @v_existe=0 
--set @v_codtec=0

		    /************************************ ACTUALIZA BASE DE DATOS REAL ********************************/ 
IF @BDprueba='no'
BEGIN

/* COMPRUEBA SI EXISTE EL CODIGO DEL TECNICO */
select @v_existe=count(*) from Obras.dbo.TecnicosObras  where CodTec=@V_CODtec
                                        


	               /******************************************************* BAJA******************************************************************/
IF @v_accion = 'B' 
begin
		

             if @v_existe = 0 
	begin
		return 1         /*NO EXISTE EL REGISTRO A DAR DE BAJA*/
	end
	set @v_anulado = 1
	UPDATE Obras..TecnicosObras SET Anulado=@v_anulado WHERE (CodTec =@V_CODTEC) 

	if  @@error <> 0
	begin
		return 2        /*ERROR AL DAR DE BAJA*/
	end
	
	return 0
end
		/*************************************************** ALTA   Y  MODIFICACIONES********************************************/ 
IF @v_accion='A'
begin
	if @v_existe > 0
	begin
		return 3	       /*YA EXISTE EL REGISTRO A DAR DE ALTA*/
	end

	--SELECT @V_CODTEC=MAX(CodTec) from  Obras..TecnicosObras
	SELECT @V_CODTEC= isnull(max(CodTec), 0) + 1 from  Obras..TecnicosObras
	
	--set @V_CODTEC=@V_CODTEC + 1
end

IF @v_accion='M'
begin
	if @v_existe = 0
	begin
		return 4         /*NO EXISTE REGISTRO A MODIFICAR*/
	end
end

	SELECT @v_orgtec=codigo_redactor FROM Tablas..OrganismosRed_dir WHERE organismo_redactor=@V_ORGANISMO 
	
	SELECT @v_CodAct= CodAct  FROM tablas..TBActividadProfesional where Actividad=rtrim(@V_ACTIVIDAD)


	if @v_orgtec = 'DP'
		begin
			SELECT @V_SERVTEC=CODIGO_DPTO   FROM Tablas..TablaDeDepartamentos WHERE DENOMINACION=@V_SERVICIO and (tipo='T' or tipo='D' or tipo='A')
			if @@error <> 0
				begin
					return 5       /*ERROR EN LA SELECCIÓN DEL SERVICIO*/ 
				end 
		end

	SELECT @V_CODPROFESION=CodProfesion FROM Tablas..TbProfesiones WHERE Profesion=@V_PROFESION
		if @@error <>0
			begin
				return 6       /*ERROR EN LA SELECCIÓN DE LA PROFESIÓN*/   
			end 

	SELECT @V_PROVTEC=PR FROM Tablas..T_PROVINCIAS WHERE NOMBRE_PR=@V_PROVINCIA
	if @V_PROVTEC=29
		begin 
			SELECT @V_MUNTEC=codigo_municipio FROM Tablas..TablaDeMunicipios WHERE nombre_municipio=@V_MUNICIPIO
				if @@error <>0
				begin
					return 7       /*ERROR EN LA SELECCIÓN DEL MUNICIPIO*/   
				end 
		end
	else
		begin
			SELECT @V_MUNTEC=Codigo_Municipio FROM Tablas..TbMunicipios WHERE Municipio=@V_MUNICIPIO
				if @@error <>0
				begin
					return 7      /*ERROR EN LA SELECCIÓN DEL MUNICIPIO*/   
				end 
		end

	/*AYUNTAMIENTO*/
		
	select @v_aytotec=codigo_municipio  from Tablas.dbo.TablaDeMunicipios where  nombre_municipio=@v_ayto and tipo='MU'  
		if @@error <>0
			begin
				return 8       /*ERROR EN LA SELECCIÓN DEL AYUNTAMIENTO*/   
			end 

if @v_accion='A'
begin	
SET IDENTITY_INSERT Obras..TecnicosObras ON

	INSERT INTO Obras..TecnicosObras
 		   (CodTec,OrgTec,CodAyto,ServTec,NombreTec,Ape1Tec,Ape2Tec,Empresa,SexoTec,DniTec,CodProfesion,CodActividad,DomTec,LocTec,CpTec,MunTec,ProvTec,TelTec,Movil,Fax,EmailTec,Anulado,observaciones,directorlab,PJuridica,Zona)

	VALUES (@V_CODTEC,@V_ORGTEC,@v_aytotec,@V_SERVTEC,@V_NOMBRETEC,@V_APE1TEC,@V_APE2TEC,@V_EMPRESA,@V_SEXOTEC,@V_DNITEC,@V_CODPROFESION,@v_CodAct,@V_DOMTEC,

		  @V_LOCTEC,@V_CPTEC,@V_MUNTEC,@V_PROVTEC,@V_TELTEC,@V_MOVIL,@v_fax,@V_EMAILTEC,@V_ANULADO,@v_observaciones,@v_directorlab,@PJuridica,@Zona)

	if @@error <>0
				begin
					return 9       /*ERROR INSERTAR EN LA TABLA DE TECNICOS*/   
				end 
	return 0
end
if @v_accion='M'
begin
	UPDATE Obras..TecnicosObras
	 SET 	OrgTec=@V_ORGTEC,
		ServTec=@V_SERVTEC,
		NombreTec=@V_NOMBRETEC,
		Ape1Tec=@V_APE1TEC,
		Ape2Tec=@V_APE2TEC,
		Empresa=@V_EMPRESA,
		SexoTec=@V_SEXOTEC,
		DniTec=@V_DNITEC,
		CodProfesion=@V_CODPROFESION,
		codactividad=@v_CodAct,
		DomTec=@V_DOMTEC,
		LocTec=@V_LOCTEC,
		CpTec=@V_CPTEC,
		MunTec=@V_MUNTEC,
		ProvTec=@V_PROVTEC,
		TelTec=@V_TELTEC,
		Movil=@V_MOVIL,
		fax=@v_fax,
		EmailTec=@V_EMAILTEC,
		Anulado=@V_ANULADO ,
		observaciones=@v_observaciones,
		directorlab=@v_directorlab,
		PJuridica=@PJuridica,
		Zona=@Zona
		 WHERE (CodTec =@V_CODTEC) 
	if @@error <>0
				begin
					return 10       /*ERROR EN LA MODIFICACIÓN TABLA DE TECNICOS*/   
				end 

	return 0         /*TERMINACION CORRECTA*/
end				
END


			 /************************************ ACTUALIZA BASE DE DATOS DE PRUEBA ********************************/ 
IF @BDprueba='si'
BEGIN
   			

/* COMPRUEBA SI EXISTE EL CODIGO DEL TECNICO */
select @v_existe=count(*) from Obras_test.dbo.TecnicosObras  where CodTec=@V_CODtec
                                        


	               /******************************************************* BAJA******************************************************************/
IF @v_accion = 'B' 
begin
		

             if @v_existe = 0 
	begin
		return 1         /*NO EXISTE EL REGISTRO A DAR DE BAJA*/
	end
	set @v_anulado = 1
	UPDATE Obras_test.dbo.TecnicosObras SET Anulado=@v_anulado WHERE (CodTec =@V_CODTEC) 

	if  @@error <> 0
	begin
		return 2        /*ERROR AL DAR DE BAJA*/
	end
	
	return 0
end
		/*************************************************** ALTA   Y  MODIFICACIONES********************************************/ 
IF @v_accion='A'
begin
	if @v_existe > 0
	begin
		return 3	       /*YA EXISTE EL REGISTRO A DAR DE ALTA*/
	end
	
	SELECT @V_CODTEC=MAX(CodTec) from  Obras_test.dbo.TecnicosObras
	
	set @V_CODTEC=@V_CODTEC + 1
end

IF @v_accion='M'
begin
	if @v_existe = 0
	begin
		return 4         /*NO EXISTE REGISTRO A MODIFICAR*/
	end
end

	SELECT @v_orgtec=codigo_redactor FROM Tablas..OrganismosRed_dir WHERE organismo_redactor=@V_ORGANISMO 
	
	
	
	if @v_orgtec = 'DP'
		begin
		SELECT @V_SERVTEC=CODIGO_DPTO   FROM Tablas..TablaDeDepartamentos WHERE DENOMINACION=@V_SERVICIO and tipo='T' or tipo='D'
			if @@error <> 0
				begin
					return 5       /*ERROR EN LA SELECCIÓN DEL SERVICIO*/ 
				end 
		end
	SELECT @V_CODPROFESION=CodProfesion FROM Tablas..TbProfesiones WHERE Profesion=@V_PROFESION
		if @@error <>0
			begin
				return 6       /*ERROR EN LA SELECCIÓN DE LA PROFESIÓN*/   
			end 

	SELECT @V_PROVTEC=PR FROM Tablas..T_PROVINCIAS WHERE NOMBRE_PR=@V_PROVINCIA
	if @V_PROVTEC=29
		begin 
			SELECT @V_MUNTEC=codigo_municipio FROM Tablas..TablaDeMunicipios WHERE nombre_municipio=@V_MUNICIPIO
				if @@error <>0
				begin
					return 7       /*ERROR EN LA SELECCIÓN DEL MUNICIPIO*/   
				end 
		end
	else
		begin
			SELECT @V_MUNTEC=Codigo_Municipio FROM Tablas..TbMunicipios WHERE Municipio=@V_MUNICIPIO
				if @@error <>0
				begin
					return 7      /*ERROR EN LA SELECCIÓN DEL MUNICIPIO*/   
				end 
		end

	/*AYUNTAMIENTO*/
		
		select @v_aytotec=codigo_municipio  from Tablas.dbo.TablaDeMunicipios where tipo='MU'  and  nombre_municipio=@v_ayto
			if @@error <>0
				begin
					return 8       /*ERROR EN LA SELECCIÓN DEL AYUNTAMIENTO*/   
				end 

if @v_accion='A'
begin	
SET IDENTITY_INSERT Obras_Test.dbo.TecnicosObras ON
	INSERT INTO Obras_test.dbo.TecnicosObras
 		   (CodTec,OrgTec,CodAyto,ServTec,NombreTec,Ape1Tec,Ape2Tec,Empresa,SexoTec,DniTec,CodProfesion,DomTec,LocTec,CpTec,MunTec,ProvTec,TelTec,Movil,EmailTec,Anulado)
	VALUES (@V_CODTEC,@V_ORGTEC,@v_aytotec,@V_SERVTEC,@V_NOMBRETEC,@V_APE1TEC,@V_APE2TEC,@V_EMPRESA,@V_SEXOTEC,@V_DNITEC,@V_CODPROFESION,@V_DOMTEC,
		  @V_LOCTEC,@V_CPTEC,@V_MUNTEC,@V_PROVTEC,@V_TELTEC,@V_MOVIL,@V_EMAILTEC,@V_ANULADO)
	
	if @@error <>0
				begin
					return 9       /*ERROR INSERTAR EN LA TABLA DE TECNICOS*/   
				end 
	return 0
end
if @v_accion='M'
begin
	UPDATE Obras_test.dbo.TecnicosObras
	 SET 	OrgTec=@V_ORGTEC,
		ServTec=@V_SERVTEC,
		NombreTec=@V_NOMBRETEC,
		Ape1Tec=@V_APE1TEC,
		Ape2Tec=@V_APE2TEC,
		Empresa=@V_EMPRESA,
		SexoTec=@V_SEXOTEC,
		DniTec=@V_DNITEC,
		CodProfesion=@V_CODPROFESION,
		DomTec=@V_DOMTEC,
		LocTec=@V_LOCTEC,
		CpTec=@V_CPTEC,
		MunTec=@V_MUNTEC,
		ProvTec=@V_PROVTEC,
		TelTec=@V_TELTEC,
		Movil=@V_MOVIL,
		EmailTec=@V_EMAILTEC,
		Anulado=@V_ANULADO 
		 WHERE (CodTec =@V_CODTEC) 

	if @@error <>0
	begin
		return 10       /*ERROR EN LA MODIFICACIÓN TABLA DE TECNICOS*/   
	end 

	return 0         /*TERMINACION CORRECTA*/
		
end

end
GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Modificando Procedimiento [dbo].[PA_Tecnicos_R_TecnicosObras]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
ALTER PROCEDURE [dbo].[PA_Tecnicos_R_TecnicosObras] 
/* VALORES QUE DEVUELVE :
	0 FUNCIONAMIENTO CORRECTO
	1 ERROR EN TABLA DE TECNICOS
	2 ERROR EN TABLA AUXILIAR*/
	
@BDprueba as char(2)='%',
@v_organismo as char(55)='%',
@v_servicio as char(400)='%',
@v_codtec as char(6)='%',
@v_nombre as char(70)='%',
@v_apellido1 as char(50)='%',
@v_apellido2 as char(50)='%',
@v_empresa as char(50)='%',
@v_dni as char(15)='%',
@v_profesion as char(50)='%',
@v_municipio as char(30)='%',
@v_provincia as char(30)='%',
@v_ayto as char(30)='%',
@v_anulado as bit=0,
@PJuridica as bit=0,
@Zona as char(1)='%'


AS
declare @SENTENCIA AS VARCHAR(8000)
declare @WHERE AS VARCHAR(8000)
DECLARE @V_ERROR AS INT

SET @SENTENCIA=''
SET @WHERE =''

set @v_error = @@error
PRINT @v_error


			/***************** ACCEDE A BASE DE DATOS REAL ******************/
IF @BDprueba ='no'

BEGIN
-- 
--(RTRIM(ISNULL(dbo.TecnicosObras.NombreTec," "))) + " " +  (RTRIM( ISNULL(dbo.TecnicosObras.Ape1Tec," "))) + " " + (RTRIM(ISNULL(dbo.TecnicosObras.Ape2Tec," "))) as NombreTec, 
--"REJILLATECNICOS" AS Nombretabla,
SET @SENTENCIA='SELECT dbo.TecnicosObras.Codtec, 
Obras.dbo.TecnicosObras.NombreTec' + ' + ' +  'Obras.dbo.TecnicosObras.Ape1Tec' + ' + ' + 'Obras.dbo.TecnicosObras.Ape2Tec as NombreTec, 
  Obras.dbo.TecnicosObras.Empresa, 
  Tablas.dbo.TablaDeDepartamentos.DENOMINACION AS Departamento,
     Obras.dbo.TecnicosObras.CpTec AS CodigoPostal, 
    Obras.dbo.TecnicosObras.LocTec AS Localidad, 
   Obras.dbo.TecnicosObras.OrgTec AS Organismo, 
   Obras.dbo.TecnicosObras.DomTec AS Domicilio, 
    Obras.dbo.TecnicosObras.pROVTec,
    Tablas.dbo.T_PROVINCIAS.NOMBRE_PR As Provincia,   
    Obras.dbo.TecnicosObras.MunTec,
    Tablas.dbo.TbMunicipios.Municipio as Municipio, 
    Obras.dbo.TecnicosObras.CodAyto, 
    Tablas.dbo.TablaDeMunicipios.nombre_municipio as MunAyto,
    Obras.dbo.TecnicosObras.NombreTec AS Nombre, 
    Obras.dbo.TecnicosObras.Ape1Tec AS Apellido1, 
    Obras.dbo.TecnicosObras.Ape2Tec AS Apellido2, 
    Obras.dbo.TecnicosObras.DniTec AS DNI, 
    Obras.dbo.TecnicosObras.DomTec, Obras.dbo.TecnicosObras.CpTec, 
    Obras.dbo.TecnicosObras.LocTec, 
    Obras.dbo.TecnicosObras.TelTec, 
    Obras.dbo.TecnicosObras.TelTec2, 
    Obras.dbo.TecnicosObras.codactividad, 
    Tablas.dbo.OrganismosRed_Dir.organismo_redactor as organismo,
    Obras.dbo.TecnicosObras.Movil, Obras.dbo.TecnicosObras.EmailTec, 
    Obras.dbo.TecnicosObras.Fax,
    Obras.dbo.TecnicosObras.Observaciones,
    Obras.dbo.TecnicosObras.SexoTec, 
    Obras.dbo.TecnicosObras.DirectorLab, 
    Tablas.dbo.TbProfesiones.Profesion, 
    Obras.dbo.TecnicosObras.Anulado,
    Tablas.dbo.TablaDeDepartamentos.DENOMINACION as Servicio,
    Tablas.dbo.TBActividadProfesional.Actividad,
    Obras.dbo.tecnicosobras.PJuridica,
    Obras.dbo.tecnicosobras.zona

FROM Obras.dbo.TecnicosObras LEFT OUTER JOIN
    Tablas.dbo.TBActividadProfesional ON 
    Obras.dbo.TecnicosObras.CodActividad = Tablas.dbo.TBActividadProfesional.CodAct
     LEFT OUTER JOIN
    Tablas.dbo.TablaDeDepartamentos ON 
    Obras.dbo.TecnicosObras.ServTec = Tablas.dbo.TablaDeDepartamentos.CODIGO_DPTO
     LEFT OUTER JOIN
    Tablas.dbo.OrganismosRed_Dir ON 
    Obras.dbo.TecnicosObras.OrgTec = Tablas.dbo.OrganismosRed_Dir.codigo_redactor
     LEFT OUTER JOIN
    Tablas.dbo.TbMunicipios ON 
    Obras.dbo.TecnicosObras.ProvTec = Tablas.dbo.TbMunicipios.Codigo_Provincia
     AND 
    Obras.dbo.TecnicosObras.MunTec = Tablas.dbo.TbMunicipios.Codigo_Municipio
     LEFT OUTER JOIN
    Tablas.dbo.T_PROVINCIAS ON 
    Obras.dbo.TecnicosObras.ProvTec = Tablas.dbo.T_PROVINCIAS.PR LEFT
     OUTER JOIN
    Tablas.dbo.TablaDeMunicipios ON 
    Obras.dbo.TecnicosObras.CodAyto = Tablas.dbo.TablaDeMunicipios.codigo_municipio
     LEFT OUTER JOIN
    Tablas.dbo.TbProfesiones ON 
    Obras.dbo.TecnicosObras.Codprofesion = Tablas.dbo.TbProfesiones.CodProfesion '
	
	   
IF rtrim(@v_codtec)<>'%'
BEGIN
	IF @WHERE<>''
	BEGIN
		SET @WHERE=@WHERE +' and Obras..TecnicosObras.codTec =' +  rtrim(@v_codtec) 
	END
	ELSE
	BEGIN
		SET @WHERE=@WHERE +'  Obras..TecnicosObras.codTec ='  + rtrim(@v_codtec) 
	END
END


IF rtrim(@v_nombre)<>'%'
BEGIN
	IF @WHERE<>''
	BEGIN
		SET @WHERE=@WHERE  +' and Obras..TecnicosObras.nombreTec like ' + '''' + '%' + rtrim(@v_nombre) + '%' +''''
	END
	ELSE
	BEGIN
		SET @WHERE=@WHERE  +' Obras..TecnicosObras.nombreTec like ' + '''' + '%' + rtrim(@v_nombre) + '%' +''''
	END
END


IF rtrim(@v_apellido1) <>'%'
BEGIN
	IF @WHERE<>''
	BEGIN
		SET @WHERE=@WHERE  + ' and Obras..TecnicosObras.Ape1Tec like ' + '''' + '%' + rtrim(@v_apellido1) + '%' +''''
	END
	ELSE
	BEGIN
		SET @WHERE=@WHERE  + 'Obras..TecnicosObras.Ape1Tec like ' + '''' + '%' + rtrim(@v_apellido1) + '%' +''''
	END
END


IF rtrim(@v_apellido2) <>'%'
BEGIN
	IF @WHERE<>''
	BEGIN
		SET @WHERE=@WHERE  + ' and Obras..TecnicosObras.Ape2Tec like  ' + '''' + '%'+ rtrim(@v_apellido2) + '%' +''''
	END
	ELSE
	BEGIN
		SET @WHERE=@WHERE  + ' Obras..TecnicosObras.Ape2Tec like ' + '''' + '%' + rtrim(@v_apellido2) + '%' +''''
	END
END

IF rtrim(@v_empresa) <>'%'
BEGIN
	IF @WHERE<>''
	BEGIN
		SET @WHERE=@WHERE  + ' and Obras..TecnicosObras.Empresa like ' + '''' + '%' + rtrim(@v_empresa) + '%' +''''
	END
	ELSE
	BEGIN
		SET @WHERE=@WHERE  + ' Obras..TecnicosObras.Empresa like ' + '''' + '%' + rtrim(@v_empresa) + '%' +''''
	END
END

IF rtrim(@v_dni) <>'%'
BEGIN
	IF @WHERE<>''
	BEGIN
		SET @WHERE=@WHERE  + ' and Obras..TecnicosObras.DNITec like ' + '''' + '%' + rtrim(@v_dni) + '%' +''''
	END
	ELSE
	BEGIN
		SET @WHERE=@WHERE  + ' Obras..TecnicosObras.DNITec like ' + '''' + '%' + rtrim(@v_dni) + '%' +''''
	END
END

IF rtrim(@v_profesion) <>'%'
BEGIN
	IF @WHERE<>''
	BEGIN
		SET @WHERE=@WHERE  + ' and Tablas.dbo.TbProfesiones.Profesion like  ' + '''' + '%' + rtrim(@v_profesion) + '%' +''''
	END
	ELSE
	BEGIN
		SET @WHERE=@WHERE  + '  Tablas.dbo.TbProfesiones.Profesion like  ' + '''' + '%' + rtrim(@v_profesion)  + '%' +''''
	END
END

IF rtrim(@v_provincia) <>'%'
BEGIN
	IF @WHERE<>''
	BEGIN
		SET @WHERE=@WHERE  + ' and Tablas.dbo.T_PROVINCIAS.NOMBRE_PR like ' + '''' + '%' + rtrim(@v_provincia) + '%' +''''
	END
	ELSE
	BEGIN
		SET @WHERE=@WHERE  + '  Tablas.dbo.T_PROVINCIAS.NOMBRE_PR like ' + '''' + '%' + rtrim(@v_provincia)  + '%' +''''
	END
END

IF rtrim(@v_municipio) <>'%'
BEGIN
	IF @WHERE<>''
	BEGIN
 		SET @WHERE=@WHERE  + ' and  Tablas.dbo.TbMunicipios.Municipio like ' + '''' + '%' + rtrim(@v_municipio) + '%' +''''
	END
	ELSE
	BEGIN
		SET @WHERE=@WHERE  + '  Tablas.dbo.TbMunicipios.Municipio like ' + '''' + '%' + rtrim(@v_municipio) + '%' +''''
	END
END
IF rtrim(@v_ayto) <>'%'
BEGIN
	IF @WHERE<>''
	BEGIN
		SET @WHERE=@WHERE  + ' and Tablas.dbo.TablaDeMunicipios.nombre_municipio like ' + '''' + '%' + rtrim(@v_ayto)  + '%' +''''
	END
	ELSE
	BEGIN
		SET @WHERE=@WHERE  + '  Tablas.dbo.TablaDeMunicipios.nombre_municipio like ' + '''' + '%'  + rtrim(@v_ayto)  + '%' +''''
	END
END

IF @v_anulado<>0
BEGIN
	IF @WHERE<>''
	BEGIN
		SET @WHERE=@WHERE  +' and Obras..TecnicosObras.Anulado = ' + rtrim(@v_anulado) 
	END
	ELSE
	BEGIN
		SET @WHERE=@WHERE  +' Obras..TecnicosObras.Anulado = ' + rtrim(@v_anulado) 
	END
END

IF  rtrim(@v_organismo) <>'%'
BEGIN	
    IF @WHERE<>''
	BEGIN 
		SET @WHERE= @WHERE + ' and Tablas..TablaDeDepartamentos.DENOMINACION like ' + '''' + '%' + rtrim(@v_organismo)  + '%' +''''
    END
	ELSE
	BEGIN
	    SET @WHERE=@WHERE + ' Tablas..TablaDeDepartamentos.DENOMINACION like ' + '''' + '%' + rtrim(@v_organismo) + '%' + ''''
	END 
END
IF  rtrim(@v_servicio)<>'%'
BEGIN	
	IF @WHERE <> ''
	BEGIN
		SET @WHERE=@WHERE + ' and Tablas..TablaDeDepartamentos.DENOMINACION like ' + '''' + '%' + rtrim(@v_servicio) + '%' +'''' + ' and Temporal.PROV = 29'
	END
	ELSE
	BEGIN
		SET @WHERE=@WHERE + ' Tablas..TablaDeDepartamentos.DENOMINACION like ' + '''' + '%' + rtrim(@v_servicio) + '%' +'''' + ' and Temporal.PROV = 29'
	END
END

IF rtrim(@PJuridica)<> 0
BEGIN
 IF @WHERE<>''
	BEGIN 
		SET @WHERE=@WHERE  + ' and Obras..TecnicosObras.PJuridica = ' + rtrim(@PJuridica) 
	END
	ELSE
	BEGIN
		SET @WHERE=@WHERE  + ' Obras..TecnicosObras.PJuridica = ' + rtrim(@PJuridica)
	END
END

IF  rtrim(@zona) <>'%'
BEGIN	
	 IF @WHERE<>''
	 BEGIN 
		SET @WHERE=@WHERE + ' and obras..tecnicosobras.zona  like ' + '''' + '%' + rtrim(@zona) + '%' +''''
     END
	 ELSE
	 BEGIN
	     SET @WHERE=@WHERE + ' obras..tecnicosobras.zona  like ' + '''' + '%'  + rtrim(@zona) + '%' +''''
	 END 
END

IF @WHERE<>''
BEGIN
	SET @WHERE= 'WHERE ' + @WHERE +  ' order by CodTec, Empresa'
END

	SET @SENTENCIA= @SENTENCIA + @WHERE 
PRINT (@SENTENCIA)

EXECUTE(@SENTENCIA)

END
                          /************************************** BASE DE DATOS DE PRUEBA ***********************************/
IF @BDprueba ='si'
BEGIN
	
SET @SENTENCIA=''
SET @WHERE=''


SET @SENTENCIA='SELECT Obras_test.dbo.TecnicosObras.CodTec, 
	   Obras_test.dbo.TecnicosObras.OrgTec AS Organismo,
	   Obras_test.dbo.TecnicosObras.NombreTec' + ' + ' +  'Obras_test.dbo.TecnicosObras.Ape1Tec' + ' + ' + 'Obras_test.dbo.TecnicosObras.Ape2Tec as NombreTec,  
	   Obras_test.dbo.TecnicosObras.Empresa, 
	   Tablas_test.dbo.TablaDeDepartamentos.DENOMINACION AS Departamento, 
	   Obras_test.dbo.TecnicosObras.CpTec as CodigoPostal,
	   Obras_test.dbo.TecnicosObras.LocTec as Localidad, 
	     Obras_test.dbo.TecnicosObras.DomTec as Domicilio,	
	   Obras_test.dbo.Temp_Municipios.NOMBRE_PROV as Provincia,
                Obras_test.dbo.Temp_Municipios.MUNI as Municipio,
	    temporal.NOMBRE_PROV AS MunProvincia, 
	    temporal.MUNI AS MunAyto,
                Obras_test.dbo.TecnicosObras.NombreTec as Nombre,
                Obras_test.dbo.TecnicosObras.Ape1Tec as Apellido1, 
                Obras_test.dbo.TecnicosObras.Ape2Tec  as Apellido2,
                Obras_test.dbo.TecnicosObras.DniTec  as DNI,
                Obras_test.dbo.TecnicosObras.DomTec,
                Obras_test.dbo.TecnicosObras.CpTec,
	   Obras_test.dbo.TecnicosObras.LocTec,
	   Obras_test.dbo.TecnicosObras.TelTec,
	   Obras_test.dbo.TecnicosObras.TelTec2,
	   Obras_test.dbo.TecnicosObras.Movil,
	   Obras_test.dbo.TecnicosObras.EmailTec,
	   Obras_test.dbo.TecnicosObras.Fax,
   	   Obras_test.dbo.TecnicosObras.SexoTec,
	   Tablas_test.dbo.TbProfesiones.Profesion,
                Obras_test.dbo.TecnicosObras.Anulado,
	  Tablas_test.dbo.TablaDeDepartamentos.DENOMINACION as Servicio

FROM Tablas_test.dbo.OrganismosRed_Dir RIGHT OUTER JOIN
    	   Obras_test.dbo.Temp_Municipios temporal RIGHT OUTER JOIN
    	   Obras_test.dbo.TecnicosObras ON 
    temporal.COD_MUNI = 	   Obras_test.dbo.TecnicosObras.CodAyto AND 
     temporal.PROV = 29 ON 
    Tablas_test.dbo.OrganismosRed_Dir.codigo_redactor =  Obras_test.dbo.TecnicosObras.OrgTec
     LEFT OUTER JOIN
    	   Obras_test.dbo.Temp_Municipios ON 
    	   Obras_test.dbo.TecnicosObras.MunTec =  Obras_test.dbo.Temp_Municipios.COD_MUNI
     AND 
    	   Obras_test.dbo.TecnicosObras.ProvTec =  Obras_test.dbo.Temp_Municipios.PROV LEFT
     OUTER JOIN
    Tablas_test.dbo.TablaDeDepartamentos ON 
    	   Obras_test.dbo.TecnicosObras.ServTec = Tablas_test.dbo.TablaDeDepartamentos.CODIGO_DPTO
     LEFT OUTER JOIN
    Tablas_test.dbo.TbProfesiones ON 
    	   Obras_test.dbo.TecnicosObras.CodProfesion = Tablas_test.dbo.TbProfesiones.CodProfesion '

IF rtrim(@v_codtec)<>'%'
BEGIN
	IF @WHERE<>''
	BEGIN
		SET @WHERE=@WHERE +' and  Obras_test.dbo.TecnicosObras.codTec =' +  rtrim(@v_codtec) 
	END
	ELSE
	BEGIN
		SET @WHERE=@WHERE +'  Obras_test.dbo.TecnicosObras.codTec = ' + rtrim(@v_codtec) 
	END
END


IF rtrim(@v_nombre)<>'%'
BEGIN
	IF @WHERE<>''
	BEGIN
		SET @WHERE=@WHERE  +' and Obras_test.dbo.TecnicosObras.nombreTec like ' + '''' + '%' + rtrim(@v_nombre) + '%' +''''	END
	ELSE
	BEGIN
		SET @WHERE=@WHERE  +' Obras_test.dbo.TecnicosObras.nombreTec like ' + '''' + '%' + rtrim(@v_nombre) + '%' +''''
	END
END

IF rtrim(@v_apellido1) <>'%'BEGIN
	IF @WHERE<>''
	BEGIN
		SET @WHERE=@WHERE  + ' and Obras_test.dbo.TecnicosObras.Ape1Tec like '  + '''' + '%' + rtrim(@v_apellido1) + '%' +''''
	END
	ELSE
	BEGIN
		SET @WHERE=@WHERE  + ' Obras_test.dbo.TecnicosObras.Ape1Tec like ' + '''' + '%' + rtrim(@v_apellido1) + '%' +''''
	END
END


IF rtrim(@v_apellido2) <>'%'
BEGIN
	IF @WHERE<>''
	BEGIN
		SET @WHERE=@WHERE  + ' and Obras_test.dbo.TecnicosObras.Ape2Tec like '  + '''' + '%' + rtrim(@v_apellido2) + '%' +''''
	END
	ELSE
	BEGIN
		SET @WHERE=@WHERE  + ' Obras_test.dbo.TecnicosObras.Ape2Tec like '  + '''' + '%' + rtrim(@v_apellido2) + '%' +''''
	END
END

IF rtrim(@v_empresa) <>'%'
BEGIN
	IF @WHERE<>''
	BEGIN
		SET @WHERE=@WHERE  + ' and Obras_test.dbo.TecnicosObras.Empresa like '  + '''' + '%' + rtrim(@v_empresa) + '%' +''''
	END
	ELSE
	BEGIN
		SET @WHERE=@WHERE  + '  Obras_test.dbo.TecnicosObras.Empresa like '  + '''' + '%' + rtrim(@v_empresa) + '%' +''''
	END
END

IF rtrim(@v_dni) <>'%'
BEGIN
	IF @WHERE<>''
	BEGIN
		SET @WHERE=@WHERE  + ' and Obras_test.dbo..TecnicosObras.DNITec like '  + '''' + '%'+ rtrim(@v_dni)  + '%' +''''
	END
	ELSE
	BEGIN
		SET @WHERE=@WHERE  + ' Obras_test.dbo.TecnicosObras.DNITec like ' + '''' + '%' + rtrim(@v_dni) + '%' +''''
	END
END

IF rtrim(@v_profesion) <>'%'
BEGIN
	IF @WHERE<>''
	BEGIN
		SET @WHERE=@WHERE  + ' and Tablas.dbo.TbProfesiones.Profesion like ' + '''' + '%' + rtrim(@v_profesion) + '%' +''''
	END
	ELSE
	BEGIN
		SET @WHERE=@WHERE  + '  Tablas.dbo.TbProfesiones.Profesion like ' + '''' + '%' + rtrim(@v_profesion) + '%' +''''
	END
END

IF rtrim(@v_provincia) <>'%'BEGIN
	IF @WHERE<>''
	BEGIN
		SET @WHERE=@WHERE  + ' and Obras_test.dbo.Temp_Municipios.NOMBRE_PROV like '  + '''' + '%' + rtrim(@v_provincia) + '%' +''''
	END
	ELSE
	BEGIN
		SET @WHERE=@WHERE  + ' Obras_test.dbo.Temp_Municipios.NOMBRE_PROV like ' + '''' + '%' + rtrim(@v_provincia) + '%' +''''
	END
END

IF rtrim(@v_municipio) <>'%'
BEGIN
	IF @WHERE<>''
	BEGIN
		SET @WHERE=@WHERE  + ' and  Obras_test.dbo.Temp_Municipios.MUNI like ' + '%' + '''' + rtrim(@v_municipio)  + '%' +''''
	END
	ELSE
	BEGIN
		SET @WHERE=@WHERE  + '  Obras_test.dbo.Temp_Municipios.MUNI like ' + '''' + '%' + rtrim(@v_municipio)  + '%' +''''
	END
END

IF rtrim(@v_ayto) <>'%'
BEGIN
	IF @WHERE<>''
	BEGIN
		SET @WHERE=@WHERE  + ' and Temporal.MUNI like ' + '''' + '%' + rtrim(@v_ayto) + '%' +'''' + ' and Temporal.PROV = 29'
	END
	ELSE
	BEGIN
		SET @WHERE=@WHERE  + '  Temporal.MUNI like ' + '''' + '%' + rtrim(@v_ayto) + '%' +'''' + ' and Temporal.PROV = 29'
	END
END


IF rtrim(@v_anulado)<>0
BEGIN
	IF @WHERE<>''
	BEGIN
		SET @WHERE=@WHERE  +' and Obras_test.dbo.TecnicosObras.Anulado = ' + rtrim(@v_anulado) 
	END
	ELSE
	BEGIN
		SET @WHERE=@WHERE  +' Obras_test.dbo.TecnicosObras.Anulado = ' + rtrim(@v_anulado) 
	END
END
IF rtrim(@PJuridica)<> 0
BEGIN
 IF @WHERE<>''
	BEGIN 
		SET @WHERE=@WHERE  + ' and Obras..TecnicosObras.PJuridica = ' + rtrim(@PJuridica) 
	END
	ELSE
	BEGIN
		SET @WHERE=@WHERE  + ' Obras..TecnicosObras.PJuridica = ' + rtrim(@PJuridica)
	END
END

IF  rtrim(@v_organismo)<>'%'
BEGIN	
	IF @WHERE <> ''
	BEGIN
		SET @WHERE=@WHERE + ' and Tablas..TablaDeDepartamentos.DENOMINACION like ' + '''' + '%' + rtrim(@v_organismo) + '%' +'''' + ' and Temporal.PROV = 29'
	END
	ELSE
	BEGIN
		SET @WHERE=@WHERE + ' Tablas..TablaDeDepartamentos.DENOMINACION like ' + '''' + '%' +  rtrim(@v_organismo) + '%' +'''' + ' and Temporal.PROV = 29'
	END
END
IF  rtrim(@v_servicio)<>'%'
BEGIN	
	IF @WHERE <> ''
	BEGIN
		SET @WHERE=@WHERE + ' and Tablas..TablaDeDepartamentos.DENOMINACION like ' + '''' + '%' +  rtrim(@v_servicio)  + '%' +'''' + ' and Temporal.PROV = 29'
	END
	ELSE
	BEGIN
		SET @WHERE=@WHERE + ' Tablas..TablaDeDepartamentos.DENOMINACION like ' + '''' + '%' + rtrim(@v_servicio) + '%' +'''' + ' and Temporal.PROV = 29'
	END
END
IF @WHERE<>''
BEGIN
	SET @WHERE= ' WHERE ' + @WHERE +  ' order by CodTec, Empresa'
END

SET @SENTENCIA= @SENTENCIA + @WHERE 
PRINT (@SENTENCIA)
print @v_error
EXECUTE(@SENTENCIA)
END

if @v_error = 0
begin
	return  0

end
ELSE
BEGIN	
	RETURN 2
END
GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Modificando Procedimiento [PMuñoz].[pruebaPatri]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
ALTER PROCEDURE [PMuñoz].[pruebaPatri] 

@v_aoplan as smallint = 0
as
declare @fecha as varchar(20)

set @fecha= '31/10/'+ cast(@v_aoplan as varchar)
print @fecha
GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_I_ObrasAHistorico]...';


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_I_ObrasAHistorico]';


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_I_HistoricoAObras]...';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_I_HistoricoAObras]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_PROYECTOS_ActualizaAyuda]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_PROYECTOS_ActualizaAyuda]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [mjrosado].[PA_L_PTEPRO_ConOrg_OLD]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[mjrosado].[PA_L_PTEPRO_ConOrg_OLD]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_PROYECTOS_L_LeeObraPteProySelec]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_PROYECTOS_L_LeeObraPteProySelec]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_PROYECTOS_ABM_DatosFaseXX]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_PROYECTOS_ABM_DatosFaseXX]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_Obras_A_AltasMasivas]...';


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_Obras_A_AltasMasivas]';


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_PROYECTOS_A_NuevaFase]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_PROYECTOS_A_NuevaFase]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_PROYECTOS_M_DatosFase_1]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_PROYECTOS_M_DatosFase_1]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_L_SEGUIMIENTO_OBRAS]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_L_SEGUIMIENTO_OBRAS]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_PROYECTOS_M_DatosFaseXX]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_PROYECTOS_M_DatosFaseXX]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_PROYECTOS_B_DatosFase]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_PROYECTOS_B_DatosFase]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_PROYECTOS_A_CreaUnProyectoCompletoXX]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_PROYECTOS_A_CreaUnProyectoCompletoXX]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [mjrosado].[PA_L_PTEPRO_SinOrg_OLD]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[mjrosado].[PA_L_PTEPRO_SinOrg_OLD]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_I_PlanCompletoAHistorico]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_I_PlanCompletoAHistorico]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_PROYECTOS_A_CreaUnProyecto1Completo]...';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_PROYECTOS_A_CreaUnProyecto1Completo]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_L_FOBRAS_CERT]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_L_FOBRAS_CERT]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_L_ROBRAS_1]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_L_ROBRAS_1]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_L_FOBRAS_1]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_L_FOBRAS_1]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_L_OXMUNI_ConInv_1]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_L_OXMUNI_ConInv_1]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [aida].[PrimerEjercicio]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[aida].[PrimerEjercicio]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_L_LIBGEN_SinOrg]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_L_LIBGEN_SinOrg]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_L_LIBGEN_ConOrg]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_L_LIBGEN_ConOrg]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [aida].[SegundoEjercicio]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[aida].[SegundoEjercicio]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_L_OXMUNI_SinInv]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_L_OXMUNI_SinInv]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [PMuñoz].[PRUEBA_EXISTE_EXPEDIENTE]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[PMuñoz].[PRUEBA_EXISTE_EXPEDIENTE]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_R_PlanesCompletosAHistorico]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_R_PlanesCompletosAHistorico]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_L_APRPRO]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_L_APRPRO]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_L_FOBRAS]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_L_FOBRAS]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_L_REMCON]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_L_REMCON]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [aida].[PA_Listados_L_BreviarioSelDatos]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[aida].[PA_Listados_L_BreviarioSelDatos]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_L_PROY_PTE_FISCALIZAR]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_L_PROY_PTE_FISCALIZAR]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_R_Organismos]...';


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_R_Organismos]';


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_L_FOBRAS_WEB]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_L_FOBRAS_WEB]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_L_PTESAD]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_L_PTESAD]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_R_CSV]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_R_CSV]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_B_CSV]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_B_CSV]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_M_CSV]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_M_CSV]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_I_CSV]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_I_CSV]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_R_DatosRejillaFases]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_R_DatosRejillaFases]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_PROYECTOS_ABM_DatosProyectoXX]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_PROYECTOS_ABM_DatosProyectoXX]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_PROY_ABM_DatosProyectoNuevo]...';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_PROY_ABM_DatosProyectoNuevo]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_M_Fechas_Rem_Ayto]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_M_Fechas_Rem_Ayto]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [PMuñoz].[PA_PRUEBA_ALTAMASIVA_HISTORICO]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[PMuñoz].[PA_PRUEBA_ALTAMASIVA_HISTORICO]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [aida].[PA_AAI_VerImportesClavePrincipal]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[aida].[PA_AAI_VerImportesClavePrincipal]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_AAI_VerImportesClavePrincipal]...';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_AAI_VerImportesClavePrincipal]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_PROYECTOS_L_DatosImportes]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_PROYECTOS_L_DatosImportes]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_L_LIBGEN_CreaTablaExluir]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_L_LIBGEN_CreaTablaExluir]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualizando Procedimiento [dbo].[PA_L_FOBRAS_2]...';


GO
SET ANSI_NULLS ON;

SET QUOTED_IDENTIFIER OFF;


GO
EXECUTE sp_refreshsqlmodule N'[dbo].[PA_L_FOBRAS_2]';


GO
SET ANSI_NULLS, QUOTED_IDENTIFIER ON;


GO
PRINT N'Actualización completada.';


GO

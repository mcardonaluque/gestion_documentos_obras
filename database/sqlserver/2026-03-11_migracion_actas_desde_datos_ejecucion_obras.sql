/*
    Backfill SQL Server
    Origen: Datos_Ejecucion_Obras
    Destino: ActasDeReplanteo, ActasRecepcionObra
*/

SET NOCOUNT ON;
SET XACT_ABORT ON;

BEGIN TRY
    BEGIN TRAN;

    IF OBJECT_ID('dbo.ActasDeReplanteo', 'U') IS NULL
        THROW 50001, 'No existe dbo.ActasDeReplanteo. Ejecuta primero las migraciones Laravel.', 1;

    IF OBJECT_ID('dbo.ActasRecepcionObra', 'U') IS NULL
        THROW 50002, 'No existe dbo.ActasRecepcionObra. Ejecuta primero las migraciones Laravel.', 1;

    ;WITH src AS (
        SELECT
            e.expediente_id,
            e.team_id,
            e.Fecha_Inicio_Acta_Replanteo,
            e.Fecha_Final_Acta_Replanteo,
            e.Fecha_Prorroga_Acta_Replanteo,
            e.Indicador_Impresion_AR,
            e.Indicador_Recepcion_AR,
            e.TipoActaRecepcion,
            e.Fecha_Acta_RecProv,
            e.Lugar_Acta_Rec,
            e.Fecha_Com_Inf,
            e.Fecha_Edicto_BOE,
            e.Fecha_BOE,
            e.Num_BOE,
            e.Plazo_Reclam,
            e.Fecha_Certif_NO_Reclam,
            e.Fecha_Com_Inf_2,
            e.Fecha_Com_Gob,
            e.Fecha_Comun_Contrat,
            e.Fecha_Certif_Liquid,
            e.Fecha_Rem_Interv,
            e.Fecha_Rem_MAP,
            e.Admin_ActaRecepcion,
            e.Dir_ActaRecepcion,
            e.Alcalde_ActaRecepcion,
            e.Cont_ActaRecepcion,
            e.Interv_ActaRecepcion,
            e.Dipu_ActaRecepcion,
            e.Texto,
            e.Fecha_Paralizacion_Temporal,
            e.Motivo_Paralizacion,
            e.Fecha_Aprob_Paralizacion_Temporal,
            e.Fecha_Inicio_Paralizacion,
            e.Fecha_Final_Paralizacion,
            e.Fecha_Acta_Rec,
            e.Fecha_Aviso_Finalizacion,
            e.Fecha_Aviso_FinalizacionMAP,
            e.Fecha_Medicion
        FROM dbo.Datos_Ejecucion_Obras e
        WHERE e.expediente_id IS NOT NULL
    )
    MERGE dbo.ActasDeReplanteo AS tgt
    USING src AS s
        ON tgt.expediente_id = s.expediente_id
    WHEN MATCHED THEN UPDATE SET
        tgt.Fecha_Inicio_Acta_Replanteo = s.Fecha_Inicio_Acta_Replanteo,
        tgt.Fecha_Final_Acta_Replanteo = s.Fecha_Final_Acta_Replanteo,
        tgt.Fecha_Prorroga_Acta_Replanteo = s.Fecha_Prorroga_Acta_Replanteo,
        tgt.Indicador_Impresion_AR = s.Indicador_Impresion_AR,
        tgt.Indicador_Recepcion_AR = s.Indicador_Recepcion_AR,
        tgt.team_id = s.team_id,
        tgt.updated_at = SYSUTCDATETIME()
    WHEN NOT MATCHED BY TARGET THEN
        INSERT (
            expediente_id,
            Fecha_Inicio_Acta_Replanteo,
            Fecha_Final_Acta_Replanteo,
            Fecha_Prorroga_Acta_Replanteo,
            Indicador_Impresion_AR,
            Indicador_Recepcion_AR,
            team_id,
            created_at,
            updated_at
        )
        VALUES (
            s.expediente_id,
            s.Fecha_Inicio_Acta_Replanteo,
            s.Fecha_Final_Acta_Replanteo,
            s.Fecha_Prorroga_Acta_Replanteo,
            s.Indicador_Impresion_AR,
            s.Indicador_Recepcion_AR,
            s.team_id,
            SYSUTCDATETIME(),
            SYSUTCDATETIME()
        );

    ;WITH src AS (
        SELECT
            e.expediente_id,
            e.team_id,
            e.TipoActaRecepcion,
            e.Fecha_Acta_RecProv,
            e.Lugar_Acta_Rec,
            e.Fecha_Com_Inf,
            e.Fecha_Edicto_BOE,
            e.Fecha_BOE,
            e.Num_BOE,
            e.Plazo_Reclam,
            e.Fecha_Certif_NO_Reclam,
            e.Fecha_Com_Inf_2,
            e.Fecha_Com_Gob,
            e.Fecha_Comun_Contrat,
            e.Fecha_Certif_Liquid,
            e.Fecha_Rem_Interv,
            e.Fecha_Rem_MAP,
            e.Admin_ActaRecepcion,
            e.Dir_ActaRecepcion,
            e.Alcalde_ActaRecepcion,
            e.Cont_ActaRecepcion,
            e.Interv_ActaRecepcion,
            e.Dipu_ActaRecepcion,
            e.Texto,
            e.Fecha_Paralizacion_Temporal,
            e.Motivo_Paralizacion,
            e.Fecha_Aprob_Paralizacion_Temporal,
            e.Fecha_Inicio_Paralizacion,
            e.Fecha_Final_Paralizacion,
            e.Fecha_Acta_Rec,
            e.Fecha_Aviso_Finalizacion,
            e.Fecha_Aviso_FinalizacionMAP,
            e.Fecha_Medicion
        FROM dbo.Datos_Ejecucion_Obras e
        WHERE e.expediente_id IS NOT NULL
    )
    MERGE dbo.ActasRecepcionObra AS tgt
    USING src AS s
        ON tgt.expediente_id = s.expediente_id
    WHEN MATCHED THEN UPDATE SET
        tgt.TipoActaRecepcion = s.TipoActaRecepcion,
        tgt.Fecha_Acta_RecProv = s.Fecha_Acta_RecProv,
        tgt.Lugar_Acta_Rec = s.Lugar_Acta_Rec,
        tgt.Fecha_Com_Inf = s.Fecha_Com_Inf,
        tgt.Fecha_Edicto_BOE = s.Fecha_Edicto_BOE,
        tgt.Fecha_BOE = s.Fecha_BOE,
        tgt.Num_BOE = s.Num_BOE,
        tgt.Plazo_Reclam = s.Plazo_Reclam,
        tgt.Fecha_Certif_NO_Reclam = s.Fecha_Certif_NO_Reclam,
        tgt.Fecha_Com_Inf_2 = s.Fecha_Com_Inf_2,
        tgt.Fecha_Com_Gob = s.Fecha_Com_Gob,
        tgt.Fecha_Comun_Contrat = s.Fecha_Comun_Contrat,
        tgt.Fecha_Certif_Liquid = s.Fecha_Certif_Liquid,
        tgt.Fecha_Rem_Interv = s.Fecha_Rem_Interv,
        tgt.Fecha_Rem_MAP = s.Fecha_Rem_MAP,
        tgt.Admin_ActaRecepcion = s.Admin_ActaRecepcion,
        tgt.Dir_ActaRecepcion = s.Dir_ActaRecepcion,
        tgt.Alcalde_ActaRecepcion = s.Alcalde_ActaRecepcion,
        tgt.Cont_ActaRecepcion = s.Cont_ActaRecepcion,
        tgt.Interv_ActaRecepcion = s.Interv_ActaRecepcion,
        tgt.Dipu_ActaRecepcion = s.Dipu_ActaRecepcion,
        tgt.Texto = s.Texto,
        tgt.Fecha_Paralizacion_Temporal = s.Fecha_Paralizacion_Temporal,
        tgt.Motivo_Paralizacion = s.Motivo_Paralizacion,
        tgt.Fecha_Aprob_Paralizacion_Temporal = s.Fecha_Aprob_Paralizacion_Temporal,
        tgt.Fecha_Inicio_Paralizacion = s.Fecha_Inicio_Paralizacion,
        tgt.Fecha_Final_Paralizacion = s.Fecha_Final_Paralizacion,
        tgt.Fecha_Acta_Rec = s.Fecha_Acta_Rec,
        tgt.Fecha_Aviso_Finalizacion = s.Fecha_Aviso_Finalizacion,
        tgt.Fecha_Aviso_FinalizacionMAP = s.Fecha_Aviso_FinalizacionMAP,
        tgt.Fecha_Medicion = s.Fecha_Medicion,
        tgt.team_id = s.team_id,
        tgt.updated_at = SYSUTCDATETIME()
    WHEN NOT MATCHED BY TARGET THEN
        INSERT (
            expediente_id,
            TipoActaRecepcion,
            Fecha_Acta_RecProv,
            Lugar_Acta_Rec,
            Fecha_Com_Inf,
            Fecha_Edicto_BOE,
            Fecha_BOE,
            Num_BOE,
            Plazo_Reclam,
            Fecha_Certif_NO_Reclam,
            Fecha_Com_Inf_2,
            Fecha_Com_Gob,
            Fecha_Comun_Contrat,
            Fecha_Certif_Liquid,
            Fecha_Rem_Interv,
            Fecha_Rem_MAP,
            Admin_ActaRecepcion,
            Dir_ActaRecepcion,
            Alcalde_ActaRecepcion,
            Cont_ActaRecepcion,
            Interv_ActaRecepcion,
            Dipu_ActaRecepcion,
            Texto,
            Fecha_Paralizacion_Temporal,
            Motivo_Paralizacion,
            Fecha_Aprob_Paralizacion_Temporal,
            Fecha_Inicio_Paralizacion,
            Fecha_Final_Paralizacion,
            Fecha_Acta_Rec,
            Fecha_Aviso_Finalizacion,
            Fecha_Aviso_FinalizacionMAP,
            Fecha_Medicion,
            team_id,
            created_at,
            updated_at
        )
        VALUES (
            s.expediente_id,
            s.TipoActaRecepcion,
            s.Fecha_Acta_RecProv,
            s.Lugar_Acta_Rec,
            s.Fecha_Com_Inf,
            s.Fecha_Edicto_BOE,
            s.Fecha_BOE,
            s.Num_BOE,
            s.Plazo_Reclam,
            s.Fecha_Certif_NO_Reclam,
            s.Fecha_Com_Inf_2,
            s.Fecha_Com_Gob,
            s.Fecha_Comun_Contrat,
            s.Fecha_Certif_Liquid,
            s.Fecha_Rem_Interv,
            s.Fecha_Rem_MAP,
            s.Admin_ActaRecepcion,
            s.Dir_ActaRecepcion,
            s.Alcalde_ActaRecepcion,
            s.Cont_ActaRecepcion,
            s.Interv_ActaRecepcion,
            s.Dipu_ActaRecepcion,
            s.Texto,
            s.Fecha_Paralizacion_Temporal,
            s.Motivo_Paralizacion,
            s.Fecha_Aprob_Paralizacion_Temporal,
            s.Fecha_Inicio_Paralizacion,
            s.Fecha_Final_Paralizacion,
            s.Fecha_Acta_Rec,
            s.Fecha_Aviso_Finalizacion,
            s.Fecha_Aviso_FinalizacionMAP,
            s.Fecha_Medicion,
            s.team_id,
            SYSUTCDATETIME(),
            SYSUTCDATETIME()
        );

    COMMIT TRAN;
END TRY
BEGIN CATCH
    IF @@TRANCOUNT > 0
        ROLLBACK TRAN;

    THROW;
END CATCH;

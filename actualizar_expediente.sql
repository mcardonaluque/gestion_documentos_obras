USE OBRAS_TEST;
UPDATE ImportesDeObras set Expediente = LTRIM(RTRIM(Codigo_Plan)) + '_' +	CAST(numero_obra AS VARCHAR)+'_' + CAST(subreferencia AS VARCHAR) + '_' + CAST(ao_ejecucion AS VARCHAR);
UPDATE ImportesPorOrganismo set Expediente = LTRIM(RTRIM(Codigo_Plan)) + '_' +	CAST(numero_obra AS VARCHAR)+'_' + CAST(subreferencia AS VARCHAR) + '_' + CAST(ao_ejecucion AS VARCHAR);
UPDATE Avisos set Expediente = LTRIM(RTRIM(Codigo_Plan)) + '_' +	CAST(numero_obra AS VARCHAR)+'_' + CAST(subreferencia AS VARCHAR) + '_' + CAST(ao_ejecucion AS VARCHAR);
UPDATE DatosInicioDeObras set Expediente = LTRIM(RTRIM(Codigo_Plan)) + '_' +	CAST(numero_obra AS VARCHAR)+'_' + CAST(subreferencia AS VARCHAR) + '_' + CAST(ao_ejecucion AS VARCHAR);
UPDATE Datos_Ejecucion_Obras set Expediente = LTRIM(RTRIM(Codigo_Plan)) + '_' +	CAST(numero_obra AS VARCHAR)+'_' + CAST(subreferencia AS VARCHAR) + '_' + CAST(ao_ejecucion AS VARCHAR);
UPDATE documentacionexpedientes set expediente_id = LTRIM(RTRIM(Cod_plan)) + '_' +	CAST(referencia AS VARCHAR)+'_' + CAST(subreferencia AS VARCHAR) + '_' + CAST(ao_ejecucion AS VARCHAR);
UPDATE Ayuda_Tecnica set Expediente = LTRIM(RTRIM(Codigo_Plan)) + '_' +	CAST(numero_obra AS VARCHAR)+'_' + CAST(subreferencia AS VARCHAR) + '_' + CAST(ao_ejecucion AS VARCHAR);
UPDATE CertificacionesDeobras set Expediente = LTRIM(RTRIM(Codigo_Plan)) + '_' +	CAST(numero_obra AS VARCHAR)+'_' + CAST(subreferencia AS VARCHAR) + '_' + CAST(ao_ejecucion AS VARCHAR);
UPDATE FasesDeProyectos set Expediente = LTRIM(RTRIM(Codigo_plan)) + '_' +	CAST(referencia AS VARCHAR)+'_' + CAST(subreferencia AS VARCHAR) + '_' + CAST(ao_ejecucion_obra AS VARCHAR);
UPDATE Documentos_de_fases_de_proyectos set Expediente = LTRIM(RTRIM(Codigo_Plan)) + '_' +	CAST(numero_obra AS VARCHAR)+'_' + CAST(subreferencia AS VARCHAR) + '_' + CAST(ao_ejecucion AS VARCHAR);
UPDATE ObrasCedidas set Expediente = LTRIM(RTRIM(Codigo_Plan)) + '_' +	CAST(numero_obra AS VARCHAR)+'_' + CAST(subreferencia AS VARCHAR) + '_' + CAST(ao_ejecucion AS VARCHAR);
UPDATE Proyectos set Expediente = LTRIM(RTRIM(Codigo_Plan)) + '_' +	CAST(referencia AS VARCHAR)+'_' + CAST(subreferencia AS VARCHAR) + '_' + CAST(ao_ejecucion AS VARCHAR);
UPDATE Prorrogas set Expediente = LTRIM(RTRIM(PlanObra)) + '_' +	CAST(NumObra AS VARCHAR)+'_' + CAST(subref AS VARCHAR) + '_' + CAST(AoObra AS VARCHAR);
UPDATE Subvenciones set Expediente = LTRIM(RTRIM(PlanObra)) + '_' +	CAST(NumObra AS VARCHAR)+'_' + CAST(subref AS VARCHAR) + '_' + CAST(AoObra AS VARCHAR);
UPDATE PlanSeguridadYSalud set Expediente = LTRIM(RTRIM(Codigo_Plan)) + '_' +	CAST(numero_obra AS VARCHAR)+'_' + CAST(subreferencia AS VARCHAR) + '_' + CAST(ao_ejecucion AS VARCHAR);
UPDATE Documentos_de_fases_de_proyectos set Expediente = LTRIM(RTRIM(Codigo_Plan)) + '_' +	CAST(numero_obra AS VARCHAR)+'_' + CAST(subreferencia AS VARCHAR) + '_' + CAST(ao_ejecucion AS VARCHAR);


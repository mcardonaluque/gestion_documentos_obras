USE OBRAS_TEST;
UPDATE ImportesDeObras set Expediente = LTRIM(RTRIM(Codigo_Plan)) + '_' +	CAST(numero_obra AS VARCHAR)+'_' + CAST(subreferencia AS VARCHAR) + '_' + CAST(ao_ejecucion AS VARCHAR);
<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    protected $connection = 'Obras';

    public function up(): void
    {
        DB::connection('Obras')->statement(<<<'SQL'
            UPDATE firmantes_documentos
            SET subsanacion_id = solicitud.id
            FROM firmantes_documentos
            CROSS APPLY (
                SELECT TOP (1) id
                FROM subsanaciones
                WHERE subsanaciones.expediente_id = firmantes_documentos.expediente_id
                    AND subsanaciones.documento_id = firmantes_documentos.documento_id
                    AND ABS(DATEDIFF(MILLISECOND, subsanaciones.created_at, firmantes_documentos.created_at)) < 1000
                ORDER BY ABS(DATEDIFF(MILLISECOND, subsanaciones.created_at, firmantes_documentos.created_at))
            ) AS solicitud
            WHERE firmantes_documentos.subsanacion_id IS NULL
            SQL);
    }

    public function down(): void
    {
        //
    }
};

<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'Obras';

    public function up(): void
    {
        Schema::connection($this->connection)->rename(
            'modelos_cero_justificacion',
            'modelos_cero_aprobacion',
        );
        Schema::connection($this->connection)->rename(
            'modelo_cero_documentos',
            'modelo_cero_documentos_aprobacion',
        );
        Schema::connection($this->connection)->table(
            'modelo_cero_documentos_aprobacion',
            function (Blueprint $table): void {
                $table->renameColumn(
                    'modelo_cero_justificacion_id',
                    'modelo_cero_aprobacion_id',
                );
            },
        );
    }

    public function down(): void
    {
        Schema::connection($this->connection)->table(
            'modelo_cero_documentos_aprobacion',
            function (Blueprint $table): void {
                $table->renameColumn(
                    'modelo_cero_aprobacion_id',
                    'modelo_cero_justificacion_id',
                );
            },
        );
        Schema::connection($this->connection)->rename(
            'modelo_cero_documentos_aprobacion',
            'modelo_cero_documentos',
        );
        Schema::connection($this->connection)->rename(
            'modelos_cero_aprobacion',
            'modelos_cero_justificacion',
        );
    }
};

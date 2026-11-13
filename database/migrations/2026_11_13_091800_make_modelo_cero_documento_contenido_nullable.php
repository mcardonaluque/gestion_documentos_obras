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
        Schema::connection($this->connection)->table(
            'modelo_cero_documentos_aprobacion',
            function (Blueprint $table): void {
                $table->text('contenido')->nullable()->change();
            },
        );
    }

    public function down(): void
    {
        Schema::connection($this->connection)->table(
            'modelo_cero_documentos_aprobacion',
            function (Blueprint $table): void {
                $table->text('contenido')->nullable(false)->change();
            },
        );
    }
};

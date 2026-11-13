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
        Schema::connection($this->connection)->create('modelos_cero_justificacion', function (Blueprint $table): void {
            $table->id();
            $table->string('expediente_id', 255);
            $table->string('codigo_plan', 50)->nullable();
            $table->unsignedInteger('numero_obra')->nullable();
            $table->unsignedInteger('subreferencia')->nullable();
            $table->unsignedSmallInteger('ao_ejecucion')->nullable();
            $table->string('nombre_obra', 510)->nullable();
            $table->unsignedSmallInteger('anualidad');
            $table->string('secretario_nombre', 255);
            $table->string('entidad_local', 255);
            $table->string('programa_denominacion', 510)->nullable();
            $table->string('proyecto_actuacion', 510)->nullable();
            $table->string('referencia_actuacion', 255)->nullable();
            $table->unsignedBigInteger('team_id')->nullable();
            $table->timestamps();

            $table->unique('expediente_id');
            $table->index(['codigo_plan', 'ao_ejecucion']);
            $table->index(['numero_obra', 'subreferencia', 'ao_ejecucion'], 'modelo_cero_obra_index');
            $table->index('team_id');
        });

        Schema::connection($this->connection)->create('modelo_cero_documentos', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('modelo_cero_justificacion_id')
                ->constrained('modelos_cero_justificacion')
                ->cascadeOnDelete();
            $table->unsignedSmallInteger('orden');
            $table->string('referencia', 255)->nullable();
            $table->text('contenido')->nullable();
            $table->string('denominacion', 1000);
            $table->string('csv', 255);
            $table->timestamps();

            $table->unique(['modelo_cero_justificacion_id', 'orden'], 'modelo_cero_documentos_orden_unique');
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('modelo_cero_documentos');
        Schema::connection($this->connection)->dropIfExists('modelos_cero_justificacion');
    }
};

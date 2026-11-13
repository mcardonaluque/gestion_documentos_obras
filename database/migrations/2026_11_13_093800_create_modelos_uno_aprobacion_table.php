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
        Schema::connection($this->connection)->create('modelos_uno_aprobacion', function (Blueprint $table): void {
            $table->id();
            $table->string('expediente_id', 255)->unique();
            $table->string('codigo_plan', 50)->nullable();
            $table->unsignedInteger('numero_obra')->nullable();
            $table->unsignedInteger('subreferencia')->nullable();
            $table->unsignedSmallInteger('ao_ejecucion')->nullable();
            $table->string('nombre_obra', 1500)->nullable();
            $table->string('entidad_local', 255);
            $table->string('secretario_nombre', 255);
            $table->date('fecha_documento');
            $table->string('tipo_competencia', 20);
            $table->unsignedBigInteger('team_id')->nullable()->index();
            $table->timestamps();

            $table->index(['codigo_plan', 'ao_ejecucion']);
            $table->index(['numero_obra', 'subreferencia', 'ao_ejecucion'], 'modelo_uno_obra_index');
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('modelos_uno_aprobacion');
    }
};

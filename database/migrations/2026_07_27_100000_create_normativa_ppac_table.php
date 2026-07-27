<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('NormativaPPAC')) {
            return;
        }

        Schema::create('NormativaPPAC', function (Blueprint $table): void {
            $table->id();
            $table->unsignedSmallInteger('ao_plan')->unique();
            $table->unsignedSmallInteger('ao_fin_plan');
            $table->dateTime('fecha_publicacion_definitiva', 7);
            $table->dateTime('fecha_limite_terminacion_plan', 7);
            $table->dateTime('fecha_limite_justificacion_plan', 7);
            $table->dateTime('fecha_limite_presentacion_proyecto', 7);
            $table->dateTime('fecha_limite_presentacion_documentacion', 7);
            $table->dateTime('fecha_cesion_proyecto', 7)->nullable();
            $table->dateTime('fecha_cesion_documentacion', 7)->nullable();
            $table->unsignedSmallInteger('dias_prorroga_max_porcentaje')->default(50);
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->index(['ao_plan', 'ao_fin_plan'], 'idx_normativa_ppac_anualidad');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('NormativaPPAC');
    }
};

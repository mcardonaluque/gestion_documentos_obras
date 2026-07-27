<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('PlazosObraActivos')) {
            return;
        }

        Schema::create('PlazosObraActivos', function (Blueprint $table): void {
            $table->id();
            $table->string('expediente_id', 255);
            $table->foreignId('normativa_ppac_id')->constrained('NormativaPPAC')->cascadeOnDelete();
            $table->enum('fase', ['proyecto_memoria', 'documentacion', 'ejecucion', 'justificacion']);
            $table->dateTime('fecha_inicio', 7);
            $table->dateTime('fecha_fin', 7);
            $table->unsignedInteger('dias_base');
            $table->unsignedInteger('dias_prorroga_acumulados')->default(0);
            $table->boolean('activo')->default(true);
            $table->enum('fuente_ultima_actualizacion', ['normativa', 'prorroga', 'manual'])->default('normativa');
            $table->unsignedBigInteger('team_id')->nullable();
            $table->timestamps(7);

            $table->index(['expediente_id', 'fase', 'activo'], 'idx_plazos_activos_expediente_fase');
            $table->index(['normativa_ppac_id', 'fase'], 'idx_plazos_normativa_fase');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('PlazosObraActivos');
    }
};

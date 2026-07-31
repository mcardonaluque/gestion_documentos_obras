<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('SolicitudesProrrogas')) {
            return;
        }

        Schema::create('SolicitudesProrrogas', function (Blueprint $table): void {
            $table->id();
            $table->string('expediente_id', 255);
            $table->unsignedBigInteger('team_id')->nullable();
            $table->string('tipo_prorroga', 50);
            $table->string('alcance_prorroga', 50)->nullable();
            $table->string('origen_prorroga', 50)->default('solicitud');
            $table->date('fecha_limite_anterior')->nullable();
            $table->date('fecha_limite_nueva')->nullable();
            $table->unsignedInteger('dias_concedidos')->nullable();
            $table->text('motivo')->nullable();
            $table->text('observaciones_tramitacion')->nullable();
            $table->string('estado', 50)->default('pendiente');
            $table->timestamps();

            $table->index('expediente_id');
            $table->index('team_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('SolicitudesProrrogas');
    }
};

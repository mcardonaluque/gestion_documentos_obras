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
        Schema::create('date_validation_rules', function (Blueprint $table): void {
            $table->id();
            $table->string('nombre', 160);
            $table->string('descripcion', 500)->nullable();
            $table->string('tabla1', 120);
            $table->string('tabla2', 120)->nullable();
            $table->string('campo1', 120);
            $table->string('campo2', 120)->nullable();
            $table->string('condicion', 50);
            $table->text('mensaje');
            $table->string('tipo', 20)->default('error');
            $table->string('fase', 50)->nullable();
            $table->string('estado', 20)->nullable();
            $table->string('accion', 30)->default('none');
            $table->boolean('activa')->default(true);
            $table->string('operacion', 20)->default('both');
            $table->smallInteger('plazo_dias')->nullable();
            $table->smallInteger('aviso_dias')->nullable();
            $table->string('connection_name', 40)->default('Obras');
            $table->boolean('dispara_si_cumple')->default(false);
            $table->timestamps();

            $table->index(['activa', 'tabla1', 'campo1'], 'idx_date_rules_lookup');
            $table->index(['fase', 'estado'], 'idx_date_rules_phase_state');
            $table->index(['operacion', 'tipo'], 'idx_date_rules_operation_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('date_validation_rules');
    }
};

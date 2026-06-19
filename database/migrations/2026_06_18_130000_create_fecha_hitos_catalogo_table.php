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
        Schema::create('fecha_hitos_catalogo', function (Blueprint $table): void {
            $table->id();
            $table->string('codigo_hito', 80)->unique('uniq_fecha_hitos_catalogo_codigo');
            $table->string('descripcion', 200);
            $table->string('tabla_origen', 120);
            $table->string('campo_origen', 120);
            $table->string('fase', 50)->nullable();
            $table->boolean('obligatorio')->default(false);
            $table->boolean('repetible')->default(false);
            $table->boolean('activa')->default(true);
            $table->smallInteger('orden')->nullable();
            $table->timestamps();

            $table->index(['tabla_origen', 'campo_origen'], 'idx_fecha_hitos_catalogo_origen');
            $table->index(['fase', 'activa'], 'idx_fecha_hitos_catalogo_fase_activa');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fecha_hitos_catalogo');
    }
};

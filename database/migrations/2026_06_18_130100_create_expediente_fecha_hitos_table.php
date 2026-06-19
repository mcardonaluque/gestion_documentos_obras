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
        Schema::create('expediente_fecha_hitos', function (Blueprint $table): void {
            $table->id();
            $table->string('expediente_id', 120);
            $table->string('codigo_hito', 80);
            $table->dateTime('fecha');
            $table->string('tabla_origen', 120);
            $table->string('campo_origen', 120);
            $table->string('source_record_id', 120)->nullable();
            $table->unsignedBigInteger('team_id')->nullable();
            $table->timestamps();

            $table->unique(['expediente_id', 'codigo_hito', 'source_record_id'], 'uniq_expediente_fecha_hito');
            $table->index(['expediente_id', 'fecha'], 'idx_expediente_fecha_hitos_expediente_fecha');
            $table->index(['codigo_hito', 'fecha'], 'idx_expediente_fecha_hitos_hito_fecha');
            $table->index(['tabla_origen', 'campo_origen'], 'idx_expediente_fecha_hitos_origen');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expediente_fecha_hitos');
    }
};

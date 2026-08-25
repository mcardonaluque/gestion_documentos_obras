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
        Schema::create('tramitador_api_operaciones', function (Blueprint $table): void {
            $table->id();
            $table->string('codigo', 100)->unique();
            $table->string('nombre', 160);
            $table->string('descripcion', 1000)->nullable();
            $table->string('metodo_http', 10);
            $table->string('ruta', 500);
            $table->string('tipo_contenido', 30)->default('json');
            $table->unsignedSmallInteger('timeout_segundos')->nullable();
            $table->boolean('activa')->default(true);
            $table->timestamps();

            $table->index(['activa', 'metodo_http'], 'tramitador_api_operaciones_activa_metodo_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tramitador_api_operaciones');
    }
};

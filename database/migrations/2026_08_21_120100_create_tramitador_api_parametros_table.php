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
        Schema::create('tramitador_api_parametros', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('operacion_id')
                ->constrained('tramitador_api_operaciones')
                ->cascadeOnDelete();
            $table->string('direccion', 10);
            $table->string('ubicacion', 20);
            $table->string('nombre', 160);
            $table->string('etiqueta', 160)->nullable();
            $table->string('tipo_dato', 30)->default('string');
            $table->boolean('obligatorio')->default(false);
            $table->string('formato', 100)->nullable();
            $table->string('valor_por_defecto', 1000)->nullable();
            $table->text('reglas_validacion')->nullable();
            $table->string('ruta_json', 500)->nullable();
            $table->unsignedSmallInteger('orden')->default(0);
            $table->string('descripcion', 1000)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['operacion_id', 'direccion', 'ubicacion', 'nombre'], 'tramitador_api_parametros_unique');
            $table->index(['operacion_id', 'direccion', 'activo', 'orden'], 'tramitador_api_parametros_catalogo_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tramitador_api_parametros');
    }
};

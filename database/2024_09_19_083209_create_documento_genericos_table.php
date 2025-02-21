<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('documento_genericos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('cod_documento');
            $table->string('nombre');
            $table->longText('descripcion');
            $table->string('fase_doc');
            $table->string('fase_siguiente');
            //$table->integer ('cod_tipo_doc');
            $table->foreignId('cod_tipo_doc')->references('Id')->on('tipo_documentos');
            $table->foreign('fase_doc')->references('cod_fase')->on('fase_documentos')->constrained()->CasacdeonDelete();
            $table->foreign('fase_siguiente')->references('cod_fase')->on('fase_documentos')->constrained()->CasacdeonDelete();
            //$table->foreign('cod_tipo_doc')->references('IdTipo')->on('tipo_documentos');
            $table->boolean('con_plantilla');// si o no.
            $table->string('plantilla')->nullable();
            $table->string('ruta_plantilla')->nullable();
            $table->integer('cod_estado')->references('cod_estado')->on('TablaDeEstados');
            $table->integer('cod_destino')->references('cod_destino')->on('destinos_documentos');
            $table->enum('entrada_salida', ['E','S','C']);//ENTRADA, SALIDA, COORDINACION
            $table->integer('cod_firmante')->nullable();
            $table->boolean('obligatorio');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documento_genericos');
    }
};

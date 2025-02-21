<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    protected $connection='Tablas';
    public function up(): void
    {
        Schema::create('alertas', function (Blueprint $table) {
            
            $table->id();
            $table->string('nombre');
            $table->string('descripcion');
            $table->string('tabla1');
            $table->string('tabla2');
            $table->string('campotabla1');
            $table->string('campotabla2');
            $table->string('condicion');
            $table->string('mensaje');
            $table->boolean('activa');
            $table->string('operacion');// Modificar, Insertar
            $table->string('tipo');// Advertencia, Alerta, Error
            $table->string('fase');
            $table->string('estado',3);
            //$table->type('char', 'cod_estado')->length(3);
            $table->string('accion');// stop, continue.
            $table->timestamps();
           // $table->foreign('estado')->references('cod_estado')->on('TablaDeEstados');
            $table->foreign('fase')->references('cod_fase')->on('fase_documentos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alertas');
    }
};

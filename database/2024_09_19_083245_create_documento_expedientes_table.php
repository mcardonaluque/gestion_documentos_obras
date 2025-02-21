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
        Schema::create('documento_expedientes', function (Blueprint $table) {
            $table->id();
            //$table->foreignId('id_doc')->references(->constrained()->CasacdeonDelete();
            $table->string('n_exp');
            $table->foreign('n_exp')->references('n_exp')->on('expedientes')->constrained()->CasacdeonDelete();
            $table->biginteger('cod_documento');
            $table->foreign('cod_documento')->references('id')->on('documento_genericos')->constrained()->CasacdeonDelete();
            $table->dateTime('fecha_incorporacion');
            $table->integer('n_sec_doc');
            $table->string('csv');
            $table->string('descripcion');
            //$table->foreign('usuario_id');
            $table->smallInteger('destino');
            $table->smallInteger('procedencia');
            $table->foreign('destino')->references('codigo_dpto')->on('TablaDeDepartamentos');
            $table->foreign('procedencia')->references('codigo_dpto')->on('TablaDeDepartamentos');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documento_expedientes');
    }
};

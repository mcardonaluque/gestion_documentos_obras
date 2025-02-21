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
        Schema::create('firmante_documentos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('id_doc');
            $table->foreign('id_doc')->references('id')->on('documento_expedientes');
            $table->char('id_firmante',13);
            //$table->foreign('id_firmante')->references('Id_usuario')->on('Usuarios'); 
            $table->string('tipo_firmante');
            $table->string('n_exp');
            $table->foreign('n_exp')->references('n_exp')->on('expedientes')->constrained();
            $table->dateTime('fecha_firma');
        });
            
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('firmante_documentos');
    }
};

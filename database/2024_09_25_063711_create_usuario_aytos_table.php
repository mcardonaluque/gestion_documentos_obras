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
        Schema::create('usuario_aytos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->String('puesto');
            $table->boolean('firma');
            $table->smallInteger('ayto');
            $table->foreign('ayto')->references('codigo_municipio')->on('TablaDeMunicipios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario_aytos');
    }
    
};

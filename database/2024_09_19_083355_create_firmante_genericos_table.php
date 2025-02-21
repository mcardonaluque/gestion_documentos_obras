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
        Schema::create('firmante_genericos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->char('cod_firmante',6);
            $table->string('nombre');
            $table->string('descripcion');
            $table->smallinteger('codigo_dpto');
            $table->foreign ('codigo_dpto')->references('codigo_dpto')->on('TablaDeDepartamentos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('firmante_genericos');
    }
};

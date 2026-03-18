<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'Obras';

    public function up(): void
    {
        Schema::connection($this->connection)->create('ActasDeReplanteo', function (Blueprint $table): void {
            $table->string('expediente_id', 50)->primary();

            $table->dateTime('Fecha_Inicio_Acta_Replanteo')->nullable();
            $table->dateTime('Fecha_Final_Acta_Replanteo')->nullable();
            $table->dateTime('Fecha_Prorroga_Acta_Replanteo')->nullable();
            $table->boolean('Indicador_Impresion_AR')->nullable();
            $table->boolean('Indicador_Recepcion_AR')->nullable();
            $table->unsignedBigInteger('team_id')->nullable();

            $table->timestamps();

            $table->index('team_id');
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('ActasDeReplanteo');
    }
};

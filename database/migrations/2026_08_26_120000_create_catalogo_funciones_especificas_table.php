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
        Schema::connection($this->connection)->create('catalogo_funciones_especificas', function (Blueprint $table): void {
            $table->string('id_fun_especifica', 20)->primary();
            $table->string('id_fun_comun', 20);
            $table->string('descripcion', 200);
            $table->foreign('id_fun_comun')
                ->references('IDENTIFICADOR')
                ->on('catalogo_funciones_comunes');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('catalogo_funciones_especificas');
    }
};

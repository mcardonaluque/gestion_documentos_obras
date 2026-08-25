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
        Schema::connection($this->connection)->create('catalogo_funciones_comunes', function (Blueprint $table): void {
            $table->string('IDENTIFICADOR', 20)->primary();
            $table->string('DESCRIPCION', 200);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('catalogo_funciones_comunes');
    }
};

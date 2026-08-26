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
        Schema::connection($this->connection)->create('catalogo_duracion_procedimiento', function (Blueprint $table): void {
            $table->char('identificador', 2)->primary();
            $table->char('descripcion', 100);
            $table->char('meses_por_defecto', 10)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('catalogo_duracion_procedimiento');
    }
};

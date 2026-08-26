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
        Schema::connection($this->connection)->create('catalogo_tipos_documentales', function (Blueprint $table): void {
            $table->char('identificador', 4)->primary();
            $table->char('descripcion', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('catalogo_tipos_documentales');
    }
};

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
        Schema::table('dbo.documentacionexpedientes', function (Blueprint $table): void {
            $table->string('nombre_documento', 250)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('dbo.documentacionexpedientes', function (Blueprint $table): void {
            $table->dropColumn('nombre_documento');
        });
    }
};

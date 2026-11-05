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
        Schema::table('subsanaciones', function (Blueprint $table): void {
            $table->string('archivo_solicitud', 1000)->nullable();
            $table->string('tipo_documental_solicitud', 50)->nullable();
            $table->string('id_peticion_firma', 100)->nullable()->index();
            $table->timestamp('fecha_solicitud_firma')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('subsanaciones', function (Blueprint $table): void {
            $table->dropIndex(['id_peticion_firma']);
            $table->dropColumn([
                'archivo_solicitud',
                'tipo_documental_solicitud',
                'id_peticion_firma',
                'fecha_solicitud_firma',
            ]);
        });
    }
};

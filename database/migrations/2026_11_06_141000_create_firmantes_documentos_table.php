<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'Obras';

    public function up(): void
    {
        Schema::create('firmantes_documentos', function (Blueprint $table): void {
            $table->id();
            $table->string('id_usuario', 100);
            $table->string('expediente_id', 255);
            $table->string('documento_id', 255);
            $table->string('id_rol', 100);
            $table->string('tipo_firma', 1);
            $table->unsignedSmallInteger('orden');
            $table->dateTime('fecha_envio_firma')->nullable();
            $table->dateTime('fecha_firma')->nullable();
            $table->timestamps();

            $table->index(['expediente_id', 'documento_id', 'orden'], 'firmantes_documentos_cadena_index');
            $table->index('id_usuario');
            $table->index('documento_id');
        });

        if (! Schema::hasTable('subsanacion_firmantes')) {
            return;
        }

        DB::connection('Obras')->table('subsanacion_firmantes as firmante')
            ->join('subsanaciones as subsanacion', 'subsanacion.id', '=', 'firmante.subsanacion_id')
            ->join('users as usuario', 'usuario.id', '=', 'firmante.user_id')
            ->orderBy('firmante.id')
            ->select([
                'usuario.id_usuario',
                'subsanacion.expediente_id',
                'subsanacion.documento_id',
                'firmante.id_rol',
                'firmante.tipo',
                'firmante.orden',
                'firmante.created_at',
                'firmante.updated_at',
            ])
            ->each(function (object $firmante): void {
                DB::connection('Obras')->table('firmantes_documentos')->insert([
                    'id_usuario' => $firmante->id_usuario,
                    'expediente_id' => $firmante->expediente_id,
                    'documento_id' => $firmante->documento_id,
                    'id_rol' => $firmante->id_rol,
                    'tipo_firma' => $firmante->tipo,
                    'orden' => $firmante->orden,
                    'fecha_envio_firma' => null,
                    'fecha_firma' => null,
                    'created_at' => $firmante->created_at,
                    'updated_at' => $firmante->updated_at,
                ]);
            });

        Schema::drop('subsanacion_firmantes');
    }

    public function down(): void
    {
        Schema::dropIfExists('firmantes_documentos');
    }
};

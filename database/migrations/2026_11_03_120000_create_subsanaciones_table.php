<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabla de solicitudes de subsanación de documentos de expediente.
 *
 * Cada fila representa una solicitud de subsanación sobre un documento de
 * `documentacionexpedientes` y, cuando se resuelve, la referencia al documento
 * aportado como subsanación. Un expediente puede acumular varias solicitudes
 * sobre distintos documentos.
 *
 * No se declaran claves foráneas físicas: las tablas destino son heredadas
 * (`Expedientes`, `dbo.documentacionexpedientes`) y el resto del esquema del
 * proyecto sigue el mismo criterio (p. ej. SolicitudesProrrogas).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('subsanaciones')) {
            return;
        }

        Schema::create('subsanaciones', function (Blueprint $table): void {
            $table->id();

            // Identificación de la obra/expediente (desnormalizada como en el resto de tablas de obras).
            $table->string('expediente_id', 255);
            $table->string('Codigo_Plan', 50)->nullable();
            $table->unsignedInteger('numero_obra')->nullable();
            $table->unsignedInteger('subreferencia')->nullable();
            $table->unsignedSmallInteger('ao_ejecucion')->nullable();

            // Documento objeto de la subsanación (documentacionexpedientes.idDocumento).
            $table->string('documento_id', 255);
            // Documento aportado como subsanación; se rellena al resolverse.
            $table->string('documento_subsanacion_id', 255)->nullable();

            $table->text('motivo_subsanacion');
            // Plazo concedido en días naturales desde la fecha de solicitud.
            $table->unsignedInteger('plazo_subsanacion')->nullable();
            $table->date('fecha_solicitud_subsanacion');
            $table->date('fecha_subsanacion')->nullable();

            $table->unsignedBigInteger('team_id')->nullable();
            $table->timestamps();

            $table->index('expediente_id');
            $table->index('documento_id');
            $table->index('documento_subsanacion_id');
            $table->index('team_id');
            $table->index(
                ['Codigo_Plan', 'numero_obra', 'subreferencia', 'ao_ejecucion'],
                'subsanaciones_obra_index',
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subsanaciones');
    }
};

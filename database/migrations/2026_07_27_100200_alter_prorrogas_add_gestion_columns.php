<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('Prorrogas')) {
            return;
        }

        Schema::table('Prorrogas', function (Blueprint $table): void {
            if (! Schema::hasColumn('Prorrogas', 'tipo_prorroga')) {
                $table->enum('tipo_prorroga', ['normativa', 'ejecucion', 'justificacion'])
                    ->default('ejecucion');
            }

            if (! Schema::hasColumn('Prorrogas', 'alcance_prorroga')) {
                $table->enum('alcance_prorroga', ['proyecto_memoria', 'documentacion', 'ambas'])
                    ->nullable();
            }

            if (! Schema::hasColumn('Prorrogas', 'origen_prorroga')) {
                $table->enum('origen_prorroga', ['solicitud', 'oficio'])
                    ->default('solicitud');
            }

            if (! Schema::hasColumn('Prorrogas', 'fecha_notificacion_ayto')) {
                $table->date('fecha_notificacion_ayto')->nullable();
            }

            if (! Schema::hasColumn('Prorrogas', 'fecha_limite_anterior')) {
                $table->date('fecha_limite_anterior')->nullable();
            }

            if (! Schema::hasColumn('Prorrogas', 'fecha_limite_nueva')) {
                $table->date('fecha_limite_nueva')->nullable();
            }

            if (! Schema::hasColumn('Prorrogas', 'dias_concedidos')) {
                $table->unsignedSmallInteger('dias_concedidos')->nullable();
            }

            if (! Schema::hasColumn('Prorrogas', 'csv_informe_rof')) {
                $table->string('csv_informe_rof', 120)->nullable();
            }

            if (! Schema::hasColumn('Prorrogas', 'csv_propuesta')) {
                $table->string('csv_propuesta', 120)->nullable();
            }

            if (! Schema::hasColumn('Prorrogas', 'csv_decreto')) {
                $table->string('csv_decreto', 120)->nullable();
            }

            if (! Schema::hasColumn('Prorrogas', 'fecha_firma_informe_rof')) {
                $table->date('fecha_firma_informe_rof')->nullable();
            }

            if (! Schema::hasColumn('Prorrogas', 'fecha_firma_propuesta')) {
                $table->date('fecha_firma_propuesta')->nullable();
            }

            if (! Schema::hasColumn('Prorrogas', 'fecha_firma_decreto')) {
                $table->date('fecha_firma_decreto')->nullable();
            }

            if (! Schema::hasColumn('Prorrogas', 'observaciones_tramitacion')) {
                $table->text('observaciones_tramitacion')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('Prorrogas')) {
            return;
        }

        Schema::table('Prorrogas', function (Blueprint $table): void {
            $columns = [
                'tipo_prorroga',
                'alcance_prorroga',
                'origen_prorroga',
                'fecha_notificacion_ayto',
                'fecha_limite_anterior',
                'fecha_limite_nueva',
                'dias_concedidos',
                'csv_informe_rof',
                'csv_propuesta',
                'csv_decreto',
                'fecha_firma_informe_rof',
                'fecha_firma_propuesta',
                'fecha_firma_decreto',
                'observaciones_tramitacion',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('Prorrogas', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

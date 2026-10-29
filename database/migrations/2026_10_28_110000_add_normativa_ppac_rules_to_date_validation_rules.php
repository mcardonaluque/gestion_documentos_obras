<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /** nombre, descripcion, tabla1, campo1, campo2 (NormativaPPAC), mensaje de incumplimiento */
    private const REGLAS = [
        ['NORMATIVA_PROYECTO', 'La entrega del proyecto debe hacerse antes de la fecha limite de la normativa PPAC.', 'Proyectos', 'fecha_entrega_proyecto', 'fecha_limite_presentacion_proyectoD', 'El proyecto se ha entregado fuera del plazo de la normativa PPAC.'],
        ['NORMATIVA_DOCUMENTACION', 'La aprobacion del proyecto por el ayuntamiento debe hacerse antes de la fecha limite de documentacion de la normativa PPAC.', 'Proyectos', 'fecha_aprobacion_ayto', 'fecha_limite_presentacion_documentacionD', 'La documentacion se ha presentado fuera del plazo de la normativa PPAC.'],
        ['NORMATIVA_CONTRATACION', 'El contrato debe fecharse antes de la fecha limite de contratacion de la normativa PPAC.', 'ObrasCedidas', 'FechaContrato', 'fecha_limite_contratacion', 'La contratacion se ha realizado fuera del plazo de la normativa PPAC.'],
        ['NORMATIVA_CESION', 'La cesion debe realizarse antes de la fecha limite de cesion de la normativa PPAC.', 'ObrasCedidas', 'FechaCesion', 'fecha_limite_cesion_obra', 'La cesion de la obra se ha realizado fuera del plazo de la normativa PPAC.'],
        ['NORMATIVA_EJECUCION', 'La recepcion provisional debe hacerse antes de la fecha limite de terminacion de la normativa PPAC.', 'ActasRecepcionObra', 'Fecha_Acta_RecProv', 'fecha_limite_terminacion_plan', 'La obra se ha terminado fuera del plazo de la normativa PPAC.'],
    ];

    public function up(): void
    {
        $db = DB::connection('Obras');
        $ahora = now()->format('Ymd H:i:s');

        foreach (self::REGLAS as [$nombre, $descripcion, $tabla1, $campo1, $campo2, $incumplida]) {
            if ($db->table('date_validation_rules')->where('nombre', $nombre)->exists()) {
                continue;
            }

            $db->table('date_validation_rules')->insert([
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'tabla1' => $tabla1,
                'tabla2' => 'NormativaPPAC',
                'campo1' => $campo1,
                'campo2' => $campo2,
                'condicion' => 'before_or_equal',
                'mensaje' => $incumplida,
                'mensaje_incumplida' => $incumplida,
                'tipo' => 'warning',
                'accion' => 'notify',
                'activa' => 1,
                'operacion' => 'both',
                'aviso_dias' => 30,
                'connection_name' => 'Obras',
                'dispara_si_cumple' => 0,
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ]);
        }
    }

    public function down(): void
    {
        DB::connection('Obras')->table('date_validation_rules')
            ->where('tabla2', 'NormativaPPAC')
            ->whereIn('nombre', array_column(self::REGLAS, 0))
            ->delete();
    }
};

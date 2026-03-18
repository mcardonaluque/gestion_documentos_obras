<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\DateRuleAction;
use App\Enums\DateRuleCondition;
use App\Enums\DateRuleOperation;
use App\Enums\DateRuleType;
use App\Models\DateValidationRule;
use Illuminate\Database\Seeder;

final class DateValidationRuleSeeder extends Seeder
{
    public function run(): void
    {
        /** @var array<int, array<string, mixed>> $rules */
        $rules = [
            [
                'nombre' => 'DOC_FECHA_VALIDA',
                'descripcion' => 'Valida que la fecha de incorporacion del documento sea una fecha valida.',
                'tabla1' => 'dbo.documentacionexpedientes',
                'tabla2' => null,
                'campo1' => 'fechaincorporacion',
                'campo2' => null,
                'condicion' => DateRuleCondition::IS_DATE,
                'mensaje' => 'La fecha de incorporacion del documento no es valida.',
                'tipo' => DateRuleType::WARNING,
                'fase' => null,
                'estado' => null,
                'accion' => DateRuleAction::NONE,
                'activa' => true,
                'operacion' => DateRuleOperation::BOTH,
                'plazo_dias' => null,
                'aviso_dias' => null,
                'connection_name' => 'Obras',
                'dispara_si_cumple' => false,
            ],
            [
                'nombre' => 'DOC_POSTERIOR_A_REPLANTEO',
                'descripcion' => 'La fecha de incorporacion debe ser posterior o igual al inicio del acta de replanteo.',
                'tabla1' => 'dbo.documentacionexpedientes',
                'tabla2' => 'Datos_Ejecucion_Obras',
                'campo1' => 'fechaincorporacion',
                'campo2' => 'Fecha_Inicio_Acta_Replanteo',
                'condicion' => DateRuleCondition::AFTER_OR_EQUAL,
                'mensaje' => 'La fecha del documento no puede ser anterior al acta de replanteo.',
                'tipo' => DateRuleType::ERROR,
                'fase' => null,
                'estado' => null,
                'accion' => DateRuleAction::NONE,
                'activa' => true,
                'operacion' => DateRuleOperation::BOTH,
                'plazo_dias' => null,
                'aviso_dias' => null,
                'connection_name' => 'Obras',
                'dispara_si_cumple' => false,
            ],
            [
                'nombre' => 'DOC_MAX_30_DIAS_DESDE_REPLANTEO',
                'descripcion' => 'El documento debe incorporarse en un maximo de 30 dias desde el inicio del acta de replanteo.',
                'tabla1' => 'dbo.documentacionexpedientes',
                'tabla2' => 'Datos_Ejecucion_Obras',
                'campo1' => 'fechaincorporacion',
                'campo2' => 'Fecha_Inicio_Acta_Replanteo',
                'condicion' => DateRuleCondition::MAX_DAYS_BETWEEN,
                'mensaje' => 'Se ha superado el plazo de 30 dias para incorporar este documento.',
                'tipo' => DateRuleType::WARNING,
                'fase' => null,
                'estado' => null,
                'accion' => DateRuleAction::NONE,
                'activa' => true,
                'operacion' => DateRuleOperation::BOTH,
                'plazo_dias' => 30,
                'aviso_dias' => null,
                'connection_name' => 'Obras',
                'dispara_si_cumple' => false,
            ],
            [
                'nombre' => 'EXPEDIENTE_PLAZO_NO_VENCIDO',
                'descripcion' => 'Controla que no haya vencido el plazo general desde la fecha de replanteo.',
                'tabla1' => 'Datos_Ejecucion_Obras',
                'tabla2' => null,
                'campo1' => 'Fecha_Inicio_Acta_Replanteo',
                'campo2' => null,
                'condicion' => DateRuleCondition::DEADLINE_NOT_EXPIRED,
                'mensaje' => 'El plazo general del expediente esta vencido.',
                'tipo' => DateRuleType::WARNING,
                'fase' => null,
                'estado' => null,
                'accion' => DateRuleAction::NONE,
                'activa' => true,
                'operacion' => DateRuleOperation::BOTH,
                'plazo_dias' => 180,
                'aviso_dias' => null,
                'connection_name' => 'Obras',
                'dispara_si_cumple' => false,
            ],
            [
                'nombre' => 'EXPEDIENTE_AVISO_15_DIAS_FIN_PLAZO',
                'descripcion' => 'Notifica al team del expediente cuando faltan 15 dias o menos para vencer el plazo general.',
                'tabla1' => 'Datos_Ejecucion_Obras',
                'tabla2' => null,
                'campo1' => 'Fecha_Inicio_Acta_Replanteo',
                'campo2' => null,
                'condicion' => DateRuleCondition::DAYS_TO_DEADLINE_LTE,
                'mensaje' => 'Quedan 15 dias o menos para el vencimiento del expediente.',
                'tipo' => DateRuleType::NOTIFICATION,
                'fase' => null,
                'estado' => null,
                'accion' => DateRuleAction::NOTIFY,
                'activa' => true,
                'operacion' => DateRuleOperation::BOTH,
                'plazo_dias' => 180,
                'aviso_dias' => 15,
                'connection_name' => 'Obras',
                'dispara_si_cumple' => true,
            ],
        ];

        foreach ($rules as $rule) {
            DateValidationRule::query()->updateOrCreate(
                ['nombre' => (string) $rule['nombre']],
                $rule,
            );
        }
    }
}

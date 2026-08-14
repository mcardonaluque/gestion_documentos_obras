<?php

namespace Tests\Unit\Services;

use App\Models\Expediente;
use App\Models\NormativaPpac;
use App\Models\PlazoObraActivo;
use App\Services\Prorrogas\ProrrogaRulesService;
use Carbon\Carbon;
use Tests\TestCase;

class ProrrogaRulesServiceTest extends TestCase
{
    public function test_calcula_nuevo_limite_de_justificacion_sumando_tres_meses(): void
    {
        $service = new ProrrogaRulesService();

        $newExecutionLimit = Carbon::parse('2026-10-31');

        $this->assertSame('2027-01-31', $service->calculateJustificationDeadline($newExecutionLimit)->toDateString());
    }

    public function test_rechaza_la_subida_de_documentos_de_justificacion_fuera_de_plazo(): void
    {
        $service = new ProrrogaRulesService();
        $plazo = new PlazoObraActivo();
        $plazo->fase = 'justificacion';
        $plazo->fecha_fin = '2026-12-31';
        $plazo->activo = true;

        $this->assertFalse($service->allowJustificationUpload($plazo, Carbon::parse('2027-01-01')));
        $this->assertTrue($service->allowJustificationUpload($plazo, Carbon::parse('2026-12-31')));
    }

    public function test_calcula_el_plazo_de_solicitud_de_ejecucion_quince_dias_antes_del_fin_del_plan(): void
    {
        $service = new ProrrogaRulesService();
        $expediente = new Expediente();
        $expediente->expediente_id = 'EXP-1';
        $expediente->ao_ejecucion = 2026;
        $normativa = new NormativaPpac(['fecha_limite_terminacion_plan' => '2026-12-31']);
        $plazo = new PlazoObraActivo(['fase' => 'ejecucion']);
        $plazo->setRelation('normativa', $normativa);

        $window = $service->getRequestWindow($expediente, $plazo);

        $this->assertSame('ejecucion', $window['tipo']);
        $this->assertSame('2026-12-31', $window['fecha_limite']?->toDateString());
        $this->assertSame('2026-12-16', $window['fecha_maxima_solicitud']?->toDateString());
    }
}

<?php

namespace Tests\Unit\Services;

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
}

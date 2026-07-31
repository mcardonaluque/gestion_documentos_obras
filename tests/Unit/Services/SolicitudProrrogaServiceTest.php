<?php

namespace Tests\Unit\Services;

use App\Models\NormativaPpac;
use App\Models\PlazoObraActivo;
use App\Services\Prorrogas\SolicitudProrrogaService;
use Carbon\Carbon;
use Tests\TestCase;

class SolicitudProrrogaServiceTest extends TestCase
{
    public function test_calcula_el_plazo_a_partir_de_una_fecha_solicitada(): void
    {
        $service = new SolicitudProrrogaService();

        $result = $service->buildRequestData(
            new PlazoObraActivo(['dias_base' => 120, 'fecha_fin' => '2026-10-31']),
            new NormativaPpac(['dias_prorroga_max_porcentaje' => 50]),
            [
                'fecha_limite_solicitada' => '2026-12-30',
                'dias_solicitados' => null,
            ],
        );

        $this->assertTrue($result['valid']);
        $this->assertSame(61, $result['dias_solicitados']);
        $this->assertSame('2026-12-31', $result['fecha_limite_solicitada']->toDateString());
    }

    public function test_rechaza_una_solicitud_que_supera_el_maximo_normativo(): void
    {
        $service = new SolicitudProrrogaService();

        $result = $service->buildRequestData(
            new PlazoObraActivo(['dias_base' => 100, 'fecha_fin' => '2026-10-31']),
            new NormativaPpac(['dias_prorroga_max_porcentaje' => 20]),
            [
                'fecha_limite_solicitada' => null,
                'dias_solicitados' => 25,
            ],
        );

        $this->assertFalse($result['valid']);
        $this->assertStringContainsString('máximo permitido', $result['message']);
    }
}

<?php

namespace Tests\Unit\Services;

use App\Enums\ProrrogaTipo;
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

    public function test_cualquier_prorroga_exige_quince_dias_naturales_de_antelacion(): void
    {
        $service = new ProrrogaRulesService();
        $plazo = new PlazoObraActivo(['fase' => 'ejecucion', 'fecha_fin' => '2026-12-31', 'dias_base' => 120]);

        $enPlazo = $service->validateAdmission(ProrrogaTipo::EJECUCION, $plazo, null, Carbon::parse('2026-12-16'), Carbon::parse('2027-01-31'));
        $tarde = $service->validateAdmission(ProrrogaTipo::EJECUCION, $plazo, null, Carbon::parse('2026-12-17'), Carbon::parse('2027-01-31'));

        $this->assertTrue($enPlazo->valid);
        $this->assertFalse($tarde->valid);
        $this->assertStringContainsString('15 días naturales', (string) $tarde->message);
    }

    public function test_la_prorroga_de_ejecucion_no_excede_la_mitad_del_plazo_inicial(): void
    {
        $service = new ProrrogaRulesService();
        $plazo = new PlazoObraActivo(['fase' => 'ejecucion', 'fecha_fin' => '2026-12-31', 'dias_base' => 120]);
        $solicitud = Carbon::parse('2026-11-01');

        $mitad = $service->validateAdmission(ProrrogaTipo::EJECUCION, $plazo, null, $solicitud, Carbon::parse('2027-03-01'));
        $excede = $service->validateAdmission(ProrrogaTipo::EJECUCION, $plazo, null, $solicitud, Carbon::parse('2027-03-02'));

        $this->assertTrue($mitad->valid);
        $this->assertSame(60, $mitad->maxAllowedDays);
        $this->assertFalse($excede->valid);
        $this->assertStringContainsString('mitad del plazo inicialmente concedido', (string) $excede->message);
    }

    public function test_la_prorroga_de_justificacion_es_como_maximo_de_un_mes(): void
    {
        $service = new ProrrogaRulesService();
        $ejecucion = new PlazoObraActivo(['fase' => 'ejecucion', 'fecha_fin' => '2026-12-31']);
        $justificacion = new PlazoObraActivo(['fase' => 'justificacion', 'fecha_fin' => '2027-03-31']);
        $solicitud = Carbon::parse('2027-01-10');

        $unMes = $service->validateAdmission(ProrrogaTipo::JUSTIFICACION, $justificacion, $ejecucion, $solicitud, Carbon::parse('2027-04-30'));
        $excede = $service->validateAdmission(ProrrogaTipo::JUSTIFICACION, $justificacion, $ejecucion, $solicitud, Carbon::parse('2027-05-01'));

        $this->assertTrue($unMes->valid);
        $this->assertFalse($excede->valid);
        $this->assertStringContainsString('un mes', (string) $excede->message);
    }

    public function test_la_prorroga_de_justificacion_solo_se_pide_tras_vencer_la_ejecucion(): void
    {
        $service = new ProrrogaRulesService();
        $ejecucion = new PlazoObraActivo(['fase' => 'ejecucion', 'fecha_fin' => '2026-12-31']);
        $justificacion = new PlazoObraActivo(['fase' => 'justificacion', 'fecha_fin' => '2027-03-31']);
        $nuevaFecha = Carbon::parse('2027-04-15');

        $antes = $service->validateAdmission(ProrrogaTipo::JUSTIFICACION, $justificacion, $ejecucion, Carbon::parse('2026-12-31'), $nuevaFecha);
        $despues = $service->validateAdmission(ProrrogaTipo::JUSTIFICACION, $justificacion, $ejecucion, Carbon::parse('2027-01-01'), $nuevaFecha);
        $sinEjecucion = $service->validateAdmission(ProrrogaTipo::JUSTIFICACION, $justificacion, null, Carbon::parse('2027-01-01'), $nuevaFecha);

        $this->assertFalse($antes->valid);
        $this->assertStringContainsString('venza el plazo de ejecución', (string) $antes->message);
        $this->assertTrue($despues->valid);
        $this->assertFalse($sinEjecucion->valid);
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

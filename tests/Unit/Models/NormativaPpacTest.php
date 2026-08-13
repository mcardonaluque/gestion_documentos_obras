<?php

namespace Tests\Unit\Models;

use App\Filament\Resources\NormativaPpacs\NormativaPpacResource;
use App\Models\NormativaPpac;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Unique;
use Tests\TestCase;

final class NormativaPpacTest extends TestCase
{
    public function test_allows_mass_assigning_diputacion_and_ayuntamiento_deadline_fields(): void
    {
        $model = new NormativaPpac();

        $model->fill([
            'fecha_limite_contratacion' => '2026-08-11 00:00:00',
            'fecha_limite_justificacion' => '2026-08-11 00:00:00',
            'fecha_limite_presentacion_proyectoD' => '2026-08-11 00:00:00',
            'fecha_limite_presentacion_documentacionD' => '2026-08-11 00:00:00',
            'fecha_documentacionD_asiguiente' => '2026-08-11 00:00:00',
            'fecha_proyectoD_siguiente' => '2026-08-11 00:00:00',
            'fecha_limite_presentacion_proyectoA' => '2026-08-11 00:00:00',
            'fecha_limite_presentacion_documentacionA' => '2026-08-11 00:00:00',
            'fecha_documentacionA_asiguiente' => '2026-08-11 00:00:00',
            'fecha_proyectoA_siguiente' => '2026-08-11 00:00:00',
        ]);

        $this->assertSame('2026-08-11 00:00:00', $model->getAttribute('fecha_limite_contratacion')->toDateTimeString());
        $this->assertSame('2026-08-11 00:00:00', $model->getAttribute('fecha_limite_justificacion')->toDateTimeString());
        $this->assertSame('2026-08-11 00:00:00', $model->getAttribute('fecha_documentacionD_asiguiente')->toDateTimeString());
        $this->assertSame('2026-08-11 00:00:00', $model->getAttribute('fecha_documentacionA_asiguiente')->toDateTimeString());
        $this->assertSame('2026-08-11 00:00:00', $model->getAttribute('fecha_proyectoD_siguiente')->toDateTimeString());
        $this->assertSame('2026-08-11 00:00:00', $model->getAttribute('fecha_proyectoA_siguiente')->toDateTimeString());
    }

    public function test_calculates_derived_dates_from_publication_date(): void
    {
        $this->assertSame(2027, NormativaPpac::calculatePlanEndYear(2025));
        $this->assertSame('2026-10-11 00:00:00', NormativaPpac::calculateDocumentationDeadline('2026-08-11')->toDateTimeString());
        $this->assertSame('2026-12-11 00:00:00', NormativaPpac::calculateProjectDeadline('2026-08-11')->toDateTimeString());
    }

    public function test_calculates_end_year_and_documentation_deadline(): void
    {
        $this->assertSame(2027, NormativaPpac::calculatePlanEndYear(2025));
        $this->assertSame('2026-10-11 00:00:00', NormativaPpac::calculateDocumentationDeadline('2026-08-11 00:00:00')->toDateTimeString());
    }

    public function test_syncs_derived_fields_when_plan_or_publication_date_change(): void
    {
        $model = new NormativaPpac();
        $model->fill([
            'ao_plan' => 2025,
            'fecha_publicacion_definitiva' => '2026-08-11 00:00:00',
        ]);

        $model->syncDerivedFields();

        $this->assertSame(2027, $model->ao_fin_plan);
        $this->assertSame('2026-10-11 00:00:00', $model->fecha_limite_presentacion_documentacionD->toDateTimeString());
        $this->assertSame('2026-10-11 00:00:00', $model->fecha_limite_presentacion_documentacionA->toDateTimeString());
        $this->assertSame('2026-12-11 00:00:00', $model->fecha_proyectoD_siguiente->toDateTimeString());
        $this->assertSame('2026-12-11 00:00:00', $model->fecha_proyectoA_siguiente->toDateTimeString());
    }

    public function test_year_validation_rules_accept_values_like_2023_and_2025(): void
    {
        $validator = Validator::make(
            ['ao_plan' => 2023, 'ao_fin_plan' => 2025],
            [
                'ao_plan' => NormativaPpacResource::yearValidationRules(),
                'ao_fin_plan' => NormativaPpacResource::yearValidationRules(),
            ]
        );

        $this->assertFalse($validator->fails());
    }

    public function test_ao_fin_plan_validation_rules_do_not_require_uniqueness_for_ao_plan(): void
    {
        $rules = NormativaPpacResource::yearValidationRules();

        $this->assertContains('required', $rules);
        $this->assertContains('integer', $rules);
        $this->assertFalse(collect($rules)->contains(fn (mixed $rule): bool => $rule instanceof Unique));
    }

    public function test_builds_unique_ao_plan_validation_rules(): void
    {
        $rules = NormativaPpac::aoPlanValidationRules(7);

        $this->assertContains('required', $rules);
        $this->assertContains('integer', $rules);
        $this->assertTrue(collect($rules)->contains(fn (mixed $rule): bool => $rule instanceof Unique));
    }
}

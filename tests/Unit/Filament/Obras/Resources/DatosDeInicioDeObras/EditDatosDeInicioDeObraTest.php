<?php

declare(strict_types=1);

namespace Tests\Unit\Filament\Obras\Resources\DatosDeInicioDeObras;

use App\Filament\Obras\Resources\DatosDeInicioDeObras\Pages\EditDatosDeInicioDeObra;
use App\Filament\Obras\Resources\DatosDeInicioDeObras\DatosDeInicioDeObrasResource;
use App\Models\DatosDeInicioDeObras;
use App\Services\Importes\ImportesManagementRepository;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\Test;
use ReflectionMethod;
use Tests\TestCase;

final class EditDatosDeInicioDeObraTest extends TestCase
{
    #[Test]
    public function it_hydrates_importes_management_data_when_loading_the_edit_form(): void
    {
        $repository = $this->createMock(ImportesManagementRepository::class);

        $repository
            ->expects($this->once())
            ->method('load')
            ->with('EXP-001')
            ->willReturn([
                'master' => [
                    'approved_total' => 1200.0,
                    'contract_total' => 1100.0,
                    'awarded_total' => 1000.0,
                    'drop_total' => 100.0,
                    'executed_total' => 900.0,
                ],
                'rows' => [
                    [
                        'organismo' => 'DIP',
                        'approved_percent' => 50.0,
                        'approved_amount' => 600.0,
                        'contract_percent' => 50.0,
                        'contract_amount' => 550.0,
                        'awarded_percent' => 50.0,
                        'awarded_amount' => 500.0,
                        'drop_percent' => 10.0,
                        'drop_amount' => 50.0,
                        'executed_percent' => 50.0,
                        'executed_amount' => 450.0,
                    ],
                ],
                'stage_hint' => 'ejecucion',
            ]);

        $this->app->instance(ImportesManagementRepository::class, $repository);

        $record = new DatosDeInicioDeObras();
        $record->expediente_id = 'EXP-001';
        $record->carretera = 'Carretera de prueba';
        $record->setRelation('municipios', null);
        $record->setRelation('planes', null);
        $record->setRelation('ayuda', null);

        $page = $this->getMockBuilder(EditDatosDeInicioDeObra::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getRecord'])
            ->getMock();

        $page->method('getRecord')->willReturn($record);

        $method = new ReflectionMethod(EditDatosDeInicioDeObra::class, 'mutateFormDataBeforeFill');
        $method->setAccessible(true);

        $result = $method->invoke($page, [
            'expediente_id' => 'EXP-001',
            'CompApAyto' => 'NO',
        ]);

        self::assertSame('ejecucion', $result['stage']);
        self::assertSame(1200.0, $result['master']['approved_total']);
        self::assertSame('DIP', $result['rows'][0]['organismo']);
    }

    #[Test]
    public function it_rejects_a_new_acta_replanteo_petition_when_execution_is_not_dip(): void
    {
        $record = new DatosDeInicioDeObras();
        $record->setRawAttributes([
            'forma_ejecucion' => 'OTRO',
            'fecha_pet_acta_replanteo' => null,
        ], true);

        try {
            DatosDeInicioDeObrasResource::validateActaReplanteoPetition([
                'fecha_pet_acta_replanteo' => '2026-10-14',
            ], $record);
            self::fail('Expected the petition to be rejected for a non-DIP execution.');
        } catch (ValidationException $exception) {
            self::assertArrayHasKey('fecha_pet_acta_replanteo', $exception->errors());
        }
    }

    #[Test]
    public function it_allows_a_new_acta_replanteo_petition_for_dip_execution(): void
    {
        $record = new DatosDeInicioDeObras();
        $record->setRawAttributes([
            'forma_ejecucion' => 'DIP',
            'fecha_pet_acta_replanteo' => null,
        ], true);

        DatosDeInicioDeObrasResource::validateActaReplanteoPetition([
            'fecha_pet_acta_replanteo' => '2026-10-14',
        ], $record);

        self::assertTrue(DatosDeInicioDeObrasResource::allowsActaReplanteoPetition('DIP'));
    }

    #[Test]
    public function it_preserves_an_existing_petition_for_a_non_dip_record(): void
    {
        $record = new DatosDeInicioDeObras();
        $record->setRawAttributes([
            'forma_ejecucion' => 'OTRO',
            'fecha_pet_acta_replanteo' => '2026-10-14 00:00:00.000',
        ], true);

        DatosDeInicioDeObrasResource::validateActaReplanteoPetition([
            'fecha_pet_acta_replanteo' => '2026-10-14',
        ], $record);

        self::assertFalse(DatosDeInicioDeObrasResource::allowsActaReplanteoPetition('OTRO'));
    }
}

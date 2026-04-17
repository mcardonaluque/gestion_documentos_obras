<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\Importes\ManageImportesWidget;
use App\Services\Importes\ImportesManagementRepository;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class ManageImportesWidgetTest extends TestCase
{
    #[Test]
    public function it_blocks_save_when_percentages_do_not_sum_one_hundred(): void
    {
        $repository = $this->createMock(ImportesManagementRepository::class);

        $repository
            ->expects($this->once())
            ->method('load')
            ->with('EXP-INVALIDA')
            ->willReturn([
                'master' => [
                    'approved_total' => 1000.0,
                    'contract_total' => 1000.0,
                    'awarded_total' => 1000.0,
                    'drop_total' => 0.0,
                    'executed_total' => 1000.0,
                ],
                'rows' => [
                    [
                        'organismo' => 'DIP',
                        'approved_percent' => 60.0,
                        'approved_amount' => 600.0,
                        'contract_percent' => 60.0,
                        'contract_amount' => 600.0,
                        'awarded_percent' => 60.0,
                        'awarded_amount' => 600.0,
                        'drop_percent' => 0.0,
                        'drop_amount' => 0.0,
                        'executed_percent' => 60.0,
                        'executed_amount' => 600.0,
                    ],
                    [
                        'organismo' => 'AYT',
                        'approved_percent' => 30.0,
                        'approved_amount' => 300.0,
                        'contract_percent' => 30.0,
                        'contract_amount' => 300.0,
                        'awarded_percent' => 30.0,
                        'awarded_amount' => 300.0,
                        'drop_percent' => 0.0,
                        'drop_amount' => 0.0,
                        'executed_percent' => 30.0,
                        'executed_amount' => 300.0,
                    ],
                ],
                'stage_hint' => 'inicio',
            ]);

        $repository
            ->expects($this->never())
            ->method('save');

        $this->app->instance(ImportesManagementRepository::class, $repository);

        Livewire::test(ManageImportesWidget::class, [
            'expedienteId' => 'EXP-INVALIDA',
        ])->call('save');
    }

    #[Test]
    public function it_saves_when_percentage_totals_are_valid(): void
    {
        $repository = $this->createMock(ImportesManagementRepository::class);

        $repository
            ->expects($this->once())
            ->method('load')
            ->with('EXP-VALIDA')
            ->willReturn([
                'master' => [
                    'approved_total' => 1000.0,
                    'contract_total' => 1000.0,
                    'awarded_total' => 1000.0,
                    'drop_total' => 0.0,
                    'executed_total' => 1000.0,
                ],
                'rows' => [
                    [
                        'organismo' => 'DIP',
                        'approved_percent' => 50.0,
                        'approved_amount' => 500.0,
                        'contract_percent' => 50.0,
                        'contract_amount' => 500.0,
                        'awarded_percent' => 50.0,
                        'awarded_amount' => 500.0,
                        'drop_percent' => 0.0,
                        'drop_amount' => 0.0,
                        'executed_percent' => 50.0,
                        'executed_amount' => 500.0,
                    ],
                    [
                        'organismo' => 'AYT',
                        'approved_percent' => 50.0,
                        'approved_amount' => 500.0,
                        'contract_percent' => 50.0,
                        'contract_amount' => 500.0,
                        'awarded_percent' => 50.0,
                        'awarded_amount' => 500.0,
                        'drop_percent' => 0.0,
                        'drop_amount' => 0.0,
                        'executed_percent' => 50.0,
                        'executed_amount' => 500.0,
                    ],
                ],
                'stage_hint' => 'inicio',
            ]);

        $repository
            ->expects($this->once())
            ->method('save')
            ->with(
                'EXP-VALIDA',
                $this->isType('array'),
                $this->isType('array'),
            );

        $this->app->instance(ImportesManagementRepository::class, $repository);

        Livewire::test(ManageImportesWidget::class, [
            'expedienteId' => 'EXP-VALIDA',
        ])
            ->call('save')
            ->assertHasNoErrors();
    }
}

<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Importes;

use App\Enums\ImportesStage;
use App\Services\Importes\ImportesDistributionCalculator;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class ImportesDistributionCalculatorTest extends TestCase
{
    private ImportesDistributionCalculator $calculator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calculator = new ImportesDistributionCalculator();
    }

    #[Test]
    public function it_normalizes_loaded_rows_to_two_decimals(): void
    {
        $rows = [
            [
                'organismo' => 'DIP',
                'approved_percent' => 33.3333,
                'approved_amount' => 123.4567,
                'contract_percent' => 33.3333,
                'contract_amount' => 123.4567,
                'awarded_percent' => 33.3333,
                'awarded_amount' => 123.4567,
                'drop_percent' => 0.9876,
                'drop_amount' => 1.2345,
                'executed_percent' => 33.3333,
                'executed_amount' => 123.4567,
            ],
        ];

        $result = $this->calculator->initializePhasePercentages($rows);

        self::assertSame(33.33, $result[0]['approved_percent']);
        self::assertSame(123.46, $result[0]['approved_amount']);
        self::assertSame(0.99, $result[0]['drop_percent']);
        self::assertSame(1.23, $result[0]['drop_amount']);
    }

    #[Test]
    public function it_recalculates_contract_amounts_from_total_and_percentages(): void
    {
        $rows = [
            [
                'organismo' => 'DIP',
                'approved_percent' => 60.0,
                'approved_amount' => 0.0,
                'contract_percent' => 60.0,
                'contract_amount' => 0.0,
                'awarded_percent' => 60.0,
                'awarded_amount' => 0.0,
                'drop_percent' => 0.0,
                'drop_amount' => 0.0,
                'executed_percent' => 60.0,
                'executed_amount' => 0.0,
            ],
            [
                'organismo' => 'AYT',
                'approved_percent' => 40.0,
                'approved_amount' => 0.0,
                'contract_percent' => 40.0,
                'contract_amount' => 0.0,
                'awarded_percent' => 40.0,
                'awarded_amount' => 0.0,
                'drop_percent' => 0.0,
                'drop_amount' => 0.0,
                'executed_percent' => 40.0,
                'executed_amount' => 0.0,
            ],
        ];

        $result = $this->calculator->recalculateByTotalAndPercentages($rows, 1000.0, ImportesStage::CONTRATACION);

        self::assertSame(600.0, $result[0]['awarded_amount']);
        self::assertSame(400.0, $result[1]['awarded_amount']);
    }

    #[Test]
    public function it_recalculates_percentages_when_amount_changes(): void
    {
        $rows = [
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
        ];

        $result = $this->calculator->recalculateByRowAmount($rows, 0, 700.0, 1000.0, ImportesStage::INICIO);

        self::assertSame(70.0, $result[0]['approved_percent']);
        self::assertSame(50.0, $result[1]['approved_percent']);
    }

    #[Test]
    public function it_recalculates_drop_amount_and_percentage(): void
    {
        $rows = [
            [
                'organismo' => 'DIP',
                'approved_percent' => 50.0,
                'approved_amount' => 500.0,
                'contract_percent' => 50.0,
                'contract_amount' => 500.0,
                'awarded_percent' => 40.0,
                'awarded_amount' => 400.0,
                'drop_percent' => 0.0,
                'drop_amount' => 0.0,
                'executed_percent' => 40.0,
                'executed_amount' => 400.0,
            ],
        ];

        $result = $this->calculator->recalculateByRowPercent($rows, 0, 40.0, 1000.0, ImportesStage::CONTRATACION);

        self::assertSame(100.0, $result[0]['drop_amount']);
        self::assertSame(20.0, $result[0]['drop_percent']);
    }

    #[Test]
    public function it_does_not_generate_drop_before_award_exists(): void
    {
        $rows = [
            [
                'organismo' => 'DIP',
                'approved_percent' => 100.0,
                'approved_amount' => 1000.0,
                'contract_percent' => 100.0,
                'contract_amount' => 1000.0,
                'awarded_percent' => 0.0,
                'awarded_amount' => 0.0,
                'drop_percent' => 0.0,
                'drop_amount' => 0.0,
                'executed_percent' => 0.0,
                'executed_amount' => 0.0,
            ],
        ];

        $result = $this->calculator->recalculateByTotalAndPercentages($rows, 1000.0, ImportesStage::INICIO);

        self::assertSame(0.0, $result[0]['drop_amount']);
        self::assertSame(0.0, $result[0]['drop_percent']);
    }

    #[Test]
    public function it_returns_totals_for_amounts_and_percentages(): void
    {
        $rows = [
            [
                'organismo' => 'DIP',
                'approved_percent' => 55.5,
                'approved_amount' => 555.0,
                'contract_percent' => 54.0,
                'contract_amount' => 540.0,
                'awarded_percent' => 53.0,
                'awarded_amount' => 530.0,
                'drop_percent' => 1.0,
                'drop_amount' => 10.0,
                'executed_percent' => 52.0,
                'executed_amount' => 520.0,
            ],
            [
                'organismo' => 'AYT',
                'approved_percent' => 44.5,
                'approved_amount' => 445.0,
                'contract_percent' => 46.0,
                'contract_amount' => 460.0,
                'awarded_percent' => 47.0,
                'awarded_amount' => 470.0,
                'drop_percent' => 0.0,
                'drop_amount' => 0.0,
                'executed_percent' => 48.0,
                'executed_amount' => 480.0,
            ],
        ];

        $totals = $this->calculator->totals($rows);
        $percentageTotals = $this->calculator->percentageTotals($rows);

        self::assertSame(1000.0, $totals['approved_total']);
        self::assertSame(1000.0, $totals['contract_total']);
        self::assertSame(1000.0, $totals['awarded_total']);
        self::assertSame(10.0, $totals['drop_total']);
        self::assertSame(1000.0, $totals['executed_total']);

        self::assertSame(100.0, $percentageTotals['approved_percent_total']);
        self::assertSame(100.0, $percentageTotals['contract_percent_total']);
        self::assertSame(100.0, $percentageTotals['awarded_percent_total']);
        self::assertSame(100.0, $percentageTotals['executed_percent_total']);
    }
}

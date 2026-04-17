<?php

declare(strict_types=1);

namespace App\Services\Importes;

use App\Enums\ImportesStage;

final class ImportesDistributionCalculator
{
    /**
     * @param array<int, array<string, float|string>> $rows
     * @return array<int, array<string, float|string>>
     */
    public function initializePhasePercentages(array $rows): array
    {
        foreach ($rows as $index => $row) {
            $approved = $this->round2($this->toFloat($row['approved_percent'] ?? 0.0));
            $contract = $this->round2($this->toFloat($row['contract_percent'] ?? 0.0));
            $awarded = $this->round2($this->toFloat($row['awarded_percent'] ?? 0.0));
            $executed = $this->round2($this->toFloat($row['executed_percent'] ?? 0.0));

            $row['approved_percent'] = $approved;
            $row['approved_amount'] = $this->round2($this->toFloat($row['approved_amount'] ?? 0.0));
            $row['contract_amount'] = $this->round2($this->toFloat($row['contract_amount'] ?? 0.0));
            $row['awarded_amount'] = $this->round2($this->toFloat($row['awarded_amount'] ?? 0.0));
            $row['drop_percent'] = $this->round2($this->toFloat($row['drop_percent'] ?? 0.0));
            $row['drop_amount'] = $this->round2($this->toFloat($row['drop_amount'] ?? 0.0));
            $row['executed_amount'] = $this->round2($this->toFloat($row['executed_amount'] ?? 0.0));

            $row['contract_percent'] = $contract <= 0.0 ? $approved : $contract;
            $row['awarded_percent'] = $awarded <= 0.0 ? $this->round2($this->toFloat($row['contract_percent'] ?? 0.0)) : $awarded;
            $row['executed_percent'] = $executed <= 0.0 ? $this->round2($this->toFloat($row['awarded_percent'] ?? 0.0)) : $executed;

            $rows[$index] = $row;
        }

        return $rows;
    }

    /**
     * @param array<int, array<string, float|string>> $rows
     * @return array<int, array<string, float|string>>
     */
    public function recalculateByTotalAndPercentages(array $rows, float $total, ImportesStage $stage): array
    {
        $amountKey = $this->amountKey($stage);
        $percentKey = $this->percentKey($stage);

        foreach ($rows as $index => $row) {
            $percent = $this->toFloat($row[$percentKey] ?? 0.0);
            $row[$amountKey] = $this->round2($total * ($percent / 100));
            $rows[$index] = $row;
        }

        return $this->recalculateDrop($rows);
    }

    /**
     * @param array<int, array<string, float|string>> $rows
     * @return array<int, array<string, float|string>>
     */
    public function recalculateByRowAmount(array $rows, int $rowIndex, float $newAmount, float $total, ImportesStage $stage): array
    {
        $amountKey = $this->amountKey($stage);
        $percentKey = $this->percentKey($stage);

        if (! array_key_exists($rowIndex, $rows)) {
            return $rows;
        }

        $rows[$rowIndex][$amountKey] = $this->round2($newAmount);

        $sum = 0.0;
        foreach ($rows as $row) {
            $sum += $this->toFloat($row[$amountKey] ?? 0.0);
        }

        if ($sum > 0.0 && $total > 0.0) {
            foreach ($rows as $index => $row) {
                $row[$percentKey] = $this->round2(($this->toFloat($row[$amountKey] ?? 0.0) / $total) * 100);
                $rows[$index] = $row;
            }
        }

        return $this->recalculateDrop($rows);
    }

    /**
     * @param array<int, array<string, float|string>> $rows
     * @return array<int, array<string, float|string>>
     */
    public function recalculateByRowPercent(array $rows, int $rowIndex, float $newPercent, float $total, ImportesStage $stage): array
    {
        $percentKey = $this->percentKey($stage);
        $amountKey = $this->amountKey($stage);

        if (! array_key_exists($rowIndex, $rows)) {
            return $rows;
        }

        $rows[$rowIndex][$percentKey] = $this->round2($newPercent);
        $rows[$rowIndex][$amountKey] = $this->round2($total * ($newPercent / 100));

        return $this->recalculateDrop($rows);
    }

    /**
     * @param array<int, array<string, float|string>> $rows
     * @return array{approved_total: float, contract_total: float, awarded_total: float, drop_total: float, executed_total: float}
     */
    public function totals(array $rows): array
    {
        $approved = 0.0;
        $contract = 0.0;
        $awarded = 0.0;
        $drop = 0.0;
        $executed = 0.0;

        foreach ($rows as $row) {
            $approved += $this->toFloat($row['approved_amount'] ?? 0.0);
            $contract += $this->toFloat($row['contract_amount'] ?? 0.0);
            $awarded += $this->toFloat($row['awarded_amount'] ?? 0.0);
            $drop += $this->toFloat($row['drop_amount'] ?? 0.0);
            $executed += $this->toFloat($row['executed_amount'] ?? 0.0);
        }

        return [
            'approved_total' => $this->round2($approved),
            'contract_total' => $this->round2($contract),
            'awarded_total' => $this->round2($awarded),
            'drop_total' => $this->round2($drop),
            'executed_total' => $this->round2($executed),
        ];
    }

    /**
     * @param array<int, array<string, float|string>> $rows
     * @return array{approved_percent_total: float, contract_percent_total: float, awarded_percent_total: float, executed_percent_total: float}
     */
    public function percentageTotals(array $rows): array
    {
        $approved = 0.0;
        $contract = 0.0;
        $awarded = 0.0;
        $executed = 0.0;

        foreach ($rows as $row) {
            $approved += $this->toFloat($row['approved_percent'] ?? 0.0);
            $contract += $this->toFloat($row['contract_percent'] ?? 0.0);
            $awarded += $this->toFloat($row['awarded_percent'] ?? 0.0);
            $executed += $this->toFloat($row['executed_percent'] ?? 0.0);
        }

        return [
            'approved_percent_total' => $this->round2($approved),
            'contract_percent_total' => $this->round2($contract),
            'awarded_percent_total' => $this->round2($awarded),
            'executed_percent_total' => $this->round2($executed),
        ];
    }

    /**
     * @param array<int, array<string, float|string>> $rows
     * @return array<int, array<string, float|string>>
     */
    private function recalculateDrop(array $rows): array
    {
        foreach ($rows as $index => $row) {
            $contract = $this->toFloat($row['contract_amount'] ?? 0.0);
            $awarded = $this->toFloat($row['awarded_amount'] ?? 0.0);

            if ($contract <= 0.0 || $awarded <= 0.0) {
                $row['drop_amount'] = 0.0;
                $row['drop_percent'] = 0.0;
                $rows[$index] = $row;

                continue;
            }

            $dropAmount = max($contract - $awarded, 0.0);
            $dropPercent = ($dropAmount / $contract) * 100;

            $row['drop_amount'] = $this->round2($dropAmount);
            $row['drop_percent'] = $this->round2($dropPercent);
            $rows[$index] = $row;
        }

        return $rows;
    }

    private function percentKey(ImportesStage $stage): string
    {
        return match ($stage) {
            ImportesStage::INICIO => 'approved_percent',
            ImportesStage::CESION, ImportesStage::CONTRATACION => 'awarded_percent',
            ImportesStage::EJECUCION => 'executed_percent',
        };
    }

    private function amountKey(ImportesStage $stage): string
    {
        return match ($stage) {
            ImportesStage::INICIO => 'approved_amount',
            ImportesStage::CESION, ImportesStage::CONTRATACION => 'awarded_amount',
            ImportesStage::EJECUCION => 'executed_amount',
        };
    }

    private function toFloat(mixed $value): float
    {
        if ($value === null || $value === '') {
            return 0.0;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        if (is_string($value)) {
            $normalized = str_replace(['.', ','], ['', '.'], $value);

            return is_numeric($normalized) ? (float) $normalized : 0.0;
        }

        return 0.0;
    }

    private function round2(float $value): float
    {
        return round($value, 2);
    }

    private function round4(float $value): float
    {
        return round($value, 4);
    }
}

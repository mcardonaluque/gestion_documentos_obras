<?php

declare(strict_types=1);

namespace App\DTOs;

final class ImportesOrganismoRowData
{
    public function __construct(
        public readonly string $organismo,
        public readonly float $approvedPercent,
        public readonly float $approvedAmount,
        public readonly float $contractPercent,
        public readonly float $contractAmount,
        public readonly float $awardedPercent,
        public readonly float $awardedAmount,
        public readonly float $dropPercent,
        public readonly float $dropAmount,
        public readonly float $executedPercent,
        public readonly float $executedAmount,
    ) {
    }

    /**
     * @return array<string, float|string>
     */
    public function toArray(): array
    {
        return [
            'organismo' => $this->organismo,
            'approved_percent' => $this->approvedPercent,
            'approved_amount' => $this->approvedAmount,
            'contract_percent' => $this->contractPercent,
            'contract_amount' => $this->contractAmount,
            'awarded_percent' => $this->awardedPercent,
            'awarded_amount' => $this->awardedAmount,
            'drop_percent' => $this->dropPercent,
            'drop_amount' => $this->dropAmount,
            'executed_percent' => $this->executedPercent,
            'executed_amount' => $this->executedAmount,
        ];
    }
}

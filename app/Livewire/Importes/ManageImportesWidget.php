<?php

declare(strict_types=1);

namespace App\Livewire\Importes;

use App\Enums\ImportesStage;
use App\Services\Importes\ImportesDistributionCalculator;
use App\Services\Importes\ImportesManagementRepository;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Livewire\Component;

final class ManageImportesWidget extends Component
{
    private const PERCENTAGE_TOLERANCE = 0.01;

    public ?string $expedienteId = null;

    public ?string $formaEjecucion = null;

    public string $stage = ImportesStage::INICIO->value;

    /**
     * @var array{approved_total: float, contract_total: float, awarded_total: float, drop_total: float, executed_total: float}
     */
    public array $master = [
        'approved_total' => 0.0,
        'contract_total' => 0.0,
        'awarded_total' => 0.0,
        'drop_total' => 0.0,
        'executed_total' => 0.0,
    ];

    /**
     * @var array<int, array<string, float|string>>
     */
    public array $rows = [];

    public bool $loaded = false;

    /**
     * @var array{approved_percent_total: float, contract_percent_total: float, awarded_percent_total: float, executed_percent_total: float}
     */
    public array $percentageTotals = [
        'approved_percent_total' => 0.0,
        'contract_percent_total' => 0.0,
        'awarded_percent_total' => 0.0,
        'executed_percent_total' => 0.0,
    ];

    private ImportesDistributionCalculator $calculator;

    private ImportesManagementRepository $repository;

    public function boot(ImportesDistributionCalculator $calculator, ImportesManagementRepository $repository): void
    {
        $this->calculator = $calculator;
        $this->repository = $repository;
    }

    public function mount(?string $expedienteId = null, ?string $formaEjecucion = null, ?string $stage = null): void
    {
        $this->expedienteId = $expedienteId;
        $this->formaEjecucion = $formaEjecucion;

        if ($expedienteId === null || trim($expedienteId) === '') {
            return;
        }

        $data = $this->repository->load($expedienteId);
        $this->master = $data['master'];
        $this->rows = $this->calculator->initializePhasePercentages($data['rows']);
        $this->stage = $this->resolveStageValue($stage, $data['stage_hint']);
        $this->loaded = true;

        $this->rows = $this->calculator->recalculateByTotalAndPercentages(
            $this->rows,
            (float) $this->master[$this->totalKeyForStage($this->currentStage())],
            $this->currentStage(),
        );

        $this->refreshComputedData();
    }

    public function updated(string $name): void
    {
        if (! $this->loaded) {
            return;
        }

        if ($name === 'stage') {
            $this->onStageUpdated();

            return;
        }

        if (str_starts_with($name, 'master.')) {
            $this->onMasterTotalUpdated($name);

            return;
        }

        if (str_starts_with($name, 'rows.')) {
            $this->onRowUpdated($name);
        }
    }

    public function save(): void
    {
        if (! $this->loaded || $this->expedienteId === null || trim($this->expedienteId) === '') {
            return;
        }

        if (! $this->validatePercentagesForSave()) {
            Notification::make()
                ->title('No se puede guardar')
                ->body('La suma de porcentajes por fase debe ser 100.00%. Revisa los importes antes de guardar.')
                ->danger()
                ->send();

            return;
        }

        $this->repository->save($this->expedienteId, $this->master, $this->rows);

        Notification::make()
            ->title('Importes guardados')
            ->body('La distribucion por organismo se ha recalculado y guardado correctamente.')
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('livewire.importes.manage-importes-widget', [
            'stageOptions' => ImportesStage::options(),
            'canEditAprobado' => $this->currentStage() === ImportesStage::INICIO,
            'canEditContratar' => $this->currentStage() === ImportesStage::CONTRATACION,
            'canEditAdjudicado' => in_array($this->currentStage(), [ImportesStage::CESION, ImportesStage::CONTRATACION], true),
            'canEditEjecutado' => $this->currentStage() === ImportesStage::EJECUCION,
            'percentageTotals' => $this->percentageTotals,
            'percentageErrors' => $this->percentageErrorsByPhase(),
        ]);
    }

    private function onStageUpdated(): void
    {
        $stage = $this->currentStage();
        $totalKey = $this->totalKeyForStage($stage);

        $this->rows = $this->calculator->recalculateByTotalAndPercentages(
            $this->rows,
            (float) $this->master[$totalKey],
            $stage,
        );

        $this->refreshComputedData();
    }

    private function onMasterTotalUpdated(string $name): void
    {
        $stage = $this->currentStage();
        $totalKey = $this->totalKeyForStage($stage);

        if ($name !== 'master.' . $totalKey) {
            return;
        }

        $this->rows = $this->calculator->recalculateByTotalAndPercentages(
            $this->rows,
            (float) $this->master[$totalKey],
            $stage,
        );

        $this->refreshComputedData();
    }

    private function onRowUpdated(string $name): void
    {
        $parts = explode('.', $name);

        if (count($parts) !== 3) {
            return;
        }

        $rowIndex = (int) $parts[1];
        $field = (string) $parts[2];

        $stage = $this->currentStage();
        $amountKey = $this->amountFieldForStage($stage);
        $percentKey = $this->percentFieldForStage($stage);
        $total = (float) $this->master[$this->totalKeyForStage($stage)];

        if ($field === $amountKey) {
            $newAmount = (float) ($this->rows[$rowIndex][$amountKey] ?? 0.0);
            $this->rows = $this->calculator->recalculateByRowAmount($this->rows, $rowIndex, $newAmount, $total, $stage);
            $this->refreshComputedData();

            return;
        }

        if ($field === $percentKey) {
            $newPercent = (float) ($this->rows[$rowIndex][$percentKey] ?? 0.0);
            $this->rows = $this->calculator->recalculateByRowPercent($this->rows, $rowIndex, $newPercent, $total, $stage);
            $this->refreshComputedData();
        }
    }

    private function refreshComputedData(): void
    {
        $totals = $this->calculator->totals($this->rows);
        $this->master = [
            ...$this->master,
            ...$totals,
        ];

        $this->percentageTotals = $this->calculator->percentageTotals($this->rows);

        $this->syncPercentageValidationErrors();
    }

    private function currentStage(): ImportesStage
    {
        return ImportesStage::tryFrom($this->stage) ?? ImportesStage::INICIO;
    }

    private function resolveStageValue(?string $explicitStage, string $hintStage): string
    {
        if ($explicitStage !== null && ImportesStage::tryFrom($explicitStage) !== null) {
            return $explicitStage;
        }

        return ImportesStage::tryFrom($hintStage)?->value ?? ImportesStage::INICIO->value;
    }

    private function totalKeyForStage(ImportesStage $stage): string
    {
        return match ($stage) {
            ImportesStage::INICIO => 'approved_total',
            ImportesStage::CONTRATACION => 'contract_total',
            ImportesStage::CESION => 'awarded_total',
            ImportesStage::EJECUCION => 'executed_total',
        };
    }

    private function amountFieldForStage(ImportesStage $stage): string
    {
        return match ($stage) {
            ImportesStage::INICIO => 'approved_amount',
            ImportesStage::CONTRATACION => 'contract_amount',
            ImportesStage::CESION => 'awarded_amount',
            ImportesStage::EJECUCION => 'executed_amount',
        };
    }

    private function percentFieldForStage(ImportesStage $stage): string
    {
        return match ($stage) {
            ImportesStage::INICIO => 'approved_percent',
            ImportesStage::CONTRATACION => 'contract_percent',
            ImportesStage::CESION => 'awarded_percent',
            ImportesStage::EJECUCION => 'executed_percent',
        };
    }

    private function syncPercentageValidationErrors(): void
    {
        $errors = $this->percentageErrorsByPhase();

        foreach ($errors as $phase => $message) {
            if ($message === null) {
                $this->resetErrorBag('percentages.' . $phase);

                continue;
            }

            $this->addError('percentages.' . $phase, $message);
        }
    }

    private function validatePercentagesForSave(): bool
    {
        $errors = $this->percentageErrorsByPhase();

        foreach ($errors as $message) {
            if ($message !== null) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return array<string, string|null>
     */
    private function percentageErrorsByPhase(): array
    {
        return [
            'inicio' => $this->validatePercentageTotal($this->percentageTotals['approved_percent_total'] ?? 0.0, 'aprobacion'),
            'contratacion' => $this->validatePercentageTotal($this->percentageTotals['contract_percent_total'] ?? 0.0, 'contratacion'),
            'cesion' => $this->validatePercentageTotal($this->percentageTotals['awarded_percent_total'] ?? 0.0, 'adjudicacion/cesion'),
            'ejecucion' => $this->validatePercentageTotal($this->percentageTotals['executed_percent_total'] ?? 0.0, 'ejecucion'),
        ];
    }

    private function validatePercentageTotal(float $value, string $phaseLabel): ?string
    {
        if (abs($value - 100.0) <= self::PERCENTAGE_TOLERANCE) {
            return null;
        }

        return sprintf('La suma de porcentajes en %s debe ser 100.00%% (actual: %.4f%%).', $phaseLabel, $value);
    }
}

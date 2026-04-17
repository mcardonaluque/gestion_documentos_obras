<?php

namespace App\Forms\Components;

use App\Enums\ImportesStage;
use App\Services\Importes\ImportesDistributionCalculator;
use App\Services\Importes\ImportesManagementRepository;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;

class ImportesManagement extends Fieldset
{
    private const PERCENTAGE_TOLERANCE = 0.01;

    protected array $importesState = [];

    protected ?string $expedienteId = null;

    protected ?ImportesDistributionCalculator $calculator = null;

    protected ?ImportesManagementRepository $repository = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calculator = app(ImportesDistributionCalculator::class);
        $this->repository = app(ImportesManagementRepository::class);

        $this->columns(1);
        $this->schema(fn (): array => $this->getSchemaComponents());
    }

    public function setImportesContext(?string $expedienteId = null): static
    {
        $this->expedienteId = filled($expedienteId) ? (string) $expedienteId : null;
        $this->importesState = $this->loadState();

        return $this->default($this->importesState);
    }

    /**
     * @return array<string, mixed>
     */
    protected function loadState(): array
    {
        $expedienteId = $this->expedienteId;

        if (blank($expedienteId) && $this->getRecord()) {
            $expedienteId = (string) ($this->getRecord()->expediente_id ?? '');
        }

        $master = $this->defaultMaster();
        $rows = $this->defaultRows();
        $stage = ImportesStage::INICIO;

        if (filled($expedienteId)) {
            $data = $this->repository?->load($expedienteId) ?? [];

            $master = [
                ...$master,
                ...(is_array($data['master'] ?? null) ? $data['master'] : []),
            ];

            $rows = is_array($data['rows'] ?? null) && $data['rows'] !== []
                ? $data['rows']
                : $rows;

            $stage = ImportesStage::tryFrom((string) ($data['stage_hint'] ?? '')) ?? ImportesStage::INICIO;
        }

        $rows = $this->calculator?->initializePhasePercentages($rows) ?? $rows;
        $rows = $this->calculator?->recalculateByTotalAndPercentages(
            $rows,
            (float) ($master[$this->totalKeyForStage($stage)] ?? 0.0),
            $stage,
        ) ?? $rows;

        $master = [
            ...$master,
            ...($this->calculator?->totals($rows) ?? []),
        ];

        return [
            'stage' => $stage->value,
            'master' => $master,
            'rows' => $rows,
        ];
    }

    /**
     * @return array<int, mixed>
     */
    protected function getSchemaComponents(): array
    {
        return [
            Placeholder::make('importes_management_hint')
                ->hiddenLabel()
                ->content('Los cambios de este bloque se guardan con el botón Guardar del expediente.')
                ->extraAttributes(['class' => 'text-sm text-gray-600']),

            Grid::make([
                'default' => 1,
                'md' => 2,
                'xl' => 6,
            ])
                ->schema([
                    Select::make('stage')
                        ->label('Fase')
                        ->options(ImportesStage::options())
                        ->live()
                        ->afterStateUpdated(fn (Get $get, Set $set) => $this->recalculateForCurrentStage($get, $set)),

                    $this->makeMasterInput('master.approved_total', 'Importe aprobado', ImportesStage::INICIO),
                    $this->makeMasterInput('master.contract_total', 'Importe a contratar', ImportesStage::CONTRATACION),
                    $this->makeMasterInput('master.awarded_total', 'Importe adjudicado', ImportesStage::CESION),
                    $this->makeMasterInput('master.executed_total', 'Importe ejecutado', ImportesStage::EJECUCION),

                    TextInput::make('master.drop_total')
                        ->label('Importe baja')
                        ->disabled()
                        ->dehydrated()
                        ->formatStateUsing(fn ($state) => $this->normalizeNumber($state))
                        ->prefix('€')
                        ->extraInputAttributes(['class' => 'text-right'])
                        ->step('0.01'),
                ]),

            Placeholder::make('importes_phase_summary')
                ->hiddenLabel()
                ->content(fn (Get $get) => new HtmlString($this->renderPhaseSummary($get('rows') ?? []))),

            Grid::make(12)
                ->schema([
                    $this->makeHeaderPlaceholder('hdr_organismo', 'Organismo', 2),
                    $this->makeHeaderPlaceholder('hdr_approved_percent', '% aprobado'),
                    $this->makeHeaderPlaceholder('hdr_approved_amount', 'Importe aprobado'),
                    $this->makeHeaderPlaceholder('hdr_contract_percent', '% contratar'),
                    $this->makeHeaderPlaceholder('hdr_contract_amount', 'Importe contratar'),
                    $this->makeHeaderPlaceholder('hdr_awarded_percent', '% adjudicado'),
                    $this->makeHeaderPlaceholder('hdr_awarded_amount', 'Importe adjudicado'),
                    $this->makeHeaderPlaceholder('hdr_drop_percent', '% baja'),
                    $this->makeHeaderPlaceholder('hdr_drop_amount', 'Importe baja'),
                    $this->makeHeaderPlaceholder('hdr_executed_percent', '% ejecutado'),
                    $this->makeHeaderPlaceholder('hdr_executed_amount', 'Importe ejecutado'),
                ])
                ->extraAttributes(['class' => 'gap-y-2']),

            ...$this->getRowsSchema(),
        ];
    }

    protected function makeMasterInput(string $name, string $label, ImportesStage $editableStage): TextInput
    {
        return TextInput::make($name)
            ->label($label)
            ->numeric()
            ->prefix('€')
            ->step('0.01')
            ->formatStateUsing(fn ($state) => $this->normalizeNumber($state))
            ->live(onBlur: true)
            ->disabled(fn (Get $get): bool => $this->currentStageFromGet($get) !== $editableStage)
            ->dehydrated()
            ->afterStateUpdated(fn (Get $get, Set $set) => $this->recalculateForCurrentStage($get, $set))
            ->extraInputAttributes(['class' => 'text-right']);
    }

    protected function makeHeaderPlaceholder(string $name, string $label, int $columnSpan = 1): Placeholder
    {
        return Placeholder::make($name)
            ->hiddenLabel()
            ->content(new HtmlString('<span class="text-xs font-semibold uppercase tracking-wide text-gray-600">' . e($label) . '</span>'))
            ->columnSpan($columnSpan);
    }

    /**
     * @return array<int, Grid>
     */
    protected function getRowsSchema(): array
    {
        $rows = $this->importesState['rows'] ?? $this->defaultRows();
        $schema = [];

        foreach (array_keys($rows) as $index) {
            $schema[] = Grid::make(12)
                ->schema([
                    TextInput::make("rows.{$index}.organismo")
                        ->hiddenLabel()
                        ->disabled()
                        ->dehydrated()
                        ->columnSpan(2),

                    $this->makePercentInput("rows.{$index}.approved_percent", ImportesStage::INICIO),
                    $this->makeAmountInput("rows.{$index}.approved_amount", ImportesStage::INICIO),
                    $this->makePercentInput("rows.{$index}.contract_percent", ImportesStage::CONTRATACION),
                    $this->makeAmountInput("rows.{$index}.contract_amount", ImportesStage::CONTRATACION),
                    $this->makePercentInput("rows.{$index}.awarded_percent", ImportesStage::CESION),
                    $this->makeAmountInput("rows.{$index}.awarded_amount", ImportesStage::CESION),
                    $this->makePercentInput("rows.{$index}.drop_percent", null, disabled: true),
                    $this->makeAmountInput("rows.{$index}.drop_amount", null, disabled: true),
                    $this->makePercentInput("rows.{$index}.executed_percent", ImportesStage::EJECUCION),
                    $this->makeAmountInput("rows.{$index}.executed_amount", ImportesStage::EJECUCION),
                ])
                ->extraAttributes(['class' => 'gap-y-2 rounded-lg border border-gray-200 bg-white p-3']);
        }

        return $schema;
    }

    protected function makePercentInput(string $name, ?ImportesStage $editableStage, bool $disabled = false): TextInput
    {
        return TextInput::make($name)
            ->hiddenLabel()
            ->numeric()
            ->suffix('%')
            ->step('0.01')
            ->formatStateUsing(fn ($state) => $this->normalizeNumber($state))
            ->live(onBlur: true)
            ->disabled(fn (Get $get): bool => $disabled || ($editableStage !== null && $this->currentStageFromGet($get) !== $editableStage))
            ->dehydrated()
            ->afterStateUpdated(function (Get $get, Set $set, TextInput $component): void {
                $this->handleRowFieldUpdate($component->getStatePath(), $get, $set);
            })
            ->extraInputAttributes(['class' => 'text-right']);
    }

    protected function makeAmountInput(string $name, ?ImportesStage $editableStage, bool $disabled = false): TextInput
    {
        return TextInput::make($name)
            ->hiddenLabel()
            ->numeric()
            ->prefix('€')
            ->step('0.01')
            ->formatStateUsing(fn ($state) => $this->normalizeNumber($state))
            ->live(onBlur: true)
            ->disabled(fn (Get $get): bool => $disabled || ($editableStage !== null && $this->currentStageFromGet($get) !== $editableStage))
            ->dehydrated()
            ->afterStateUpdated(function (Get $get, Set $set, TextInput $component): void {
                $this->handleRowFieldUpdate($component->getStatePath(), $get, $set);
            })
            ->extraInputAttributes(['class' => 'text-right']);
    }

    protected function handleRowFieldUpdate(string $statePath, Get $get, Set $set): void
    {
        if (! preg_match('/rows\.(\d+)\.(.+)$/', $statePath, $matches)) {
            return;
        }

        $rowIndex = (int) $matches[1];
        $field = $matches[2];
        $stage = $this->currentStageFromGet($get);
        $rows = $get('rows') ?? [];
        $master = [
            ...$this->defaultMaster(),
            ...($get('master') ?? []),
        ];

        $total = (float) ($master[$this->totalKeyForStage($stage)] ?? 0.0);
        $amountField = $this->amountFieldForStage($stage);
        $percentField = $this->percentFieldForStage($stage);

        if ($field === $amountField) {
            $newAmount = (float) Arr::get($rows, "{$rowIndex}.{$field}", 0.0);
            $rows = $this->calculator?->recalculateByRowAmount($rows, $rowIndex, $newAmount, $total, $stage) ?? $rows;
            $this->pushComputedState($set, $master, $rows);

            return;
        }

        if ($field === $percentField) {
            $newPercent = (float) Arr::get($rows, "{$rowIndex}.{$field}", 0.0);
            $rows = $this->calculator?->recalculateByRowPercent($rows, $rowIndex, $newPercent, $total, $stage) ?? $rows;
            $this->pushComputedState($set, $master, $rows);
        }
    }

    protected function recalculateForCurrentStage(Get $get, Set $set): void
    {
        $stage = $this->currentStageFromGet($get);
        $master = [
            ...$this->defaultMaster(),
            ...($get('master') ?? []),
        ];
        $rows = $get('rows') ?? $this->defaultRows();

        $rows = $this->calculator?->recalculateByTotalAndPercentages(
            $rows,
            (float) ($master[$this->totalKeyForStage($stage)] ?? 0.0),
            $stage,
        ) ?? $rows;

        $this->pushComputedState($set, $master, $rows);
    }

    protected function pushComputedState(Set $set, array $master, array $rows): void
    {
        $set('rows', $rows);
        $set('master', [
            ...$master,
            ...($this->calculator?->totals($rows) ?? []),
        ]);
    }

    protected function currentStageFromGet(Get $get): ImportesStage
    {
        return ImportesStage::tryFrom((string) ($get('stage') ?? '')) ?? ImportesStage::INICIO;
    }

    protected function totalKeyForStage(ImportesStage $stage): string
    {
        return match ($stage) {
            ImportesStage::INICIO => 'approved_total',
            ImportesStage::CONTRATACION => 'contract_total',
            ImportesStage::CESION => 'awarded_total',
            ImportesStage::EJECUCION => 'executed_total',
        };
    }

    protected function amountFieldForStage(ImportesStage $stage): string
    {
        return match ($stage) {
            ImportesStage::INICIO => 'approved_amount',
            ImportesStage::CONTRATACION => 'contract_amount',
            ImportesStage::CESION => 'awarded_amount',
            ImportesStage::EJECUCION => 'executed_amount',
        };
    }

    protected function percentFieldForStage(ImportesStage $stage): string
    {
        return match ($stage) {
            ImportesStage::INICIO => 'approved_percent',
            ImportesStage::CONTRATACION => 'contract_percent',
            ImportesStage::CESION => 'awarded_percent',
            ImportesStage::EJECUCION => 'executed_percent',
        };
    }

    /**
     * @return array<string, float>
     */
    protected function defaultMaster(): array
    {
        return [
            'approved_total' => 0.0,
            'contract_total' => 0.0,
            'awarded_total' => 0.0,
            'drop_total' => 0.0,
            'executed_total' => 0.0,
        ];
    }

    /**
     * @return array<int, array<string, float|string>>
     */
    protected function defaultRows(): array
    {
        return [
            [
                'organismo' => 'DIP',
                'approved_percent' => 50.0,
                'approved_amount' => 0.0,
                'contract_percent' => 50.0,
                'contract_amount' => 0.0,
                'awarded_percent' => 50.0,
                'awarded_amount' => 0.0,
                'drop_percent' => 0.0,
                'drop_amount' => 0.0,
                'executed_percent' => 50.0,
                'executed_amount' => 0.0,
            ],
            [
                'organismo' => 'AYT',
                'approved_percent' => 50.0,
                'approved_amount' => 0.0,
                'contract_percent' => 50.0,
                'contract_amount' => 0.0,
                'awarded_percent' => 50.0,
                'awarded_amount' => 0.0,
                'drop_percent' => 0.0,
                'drop_amount' => 0.0,
                'executed_percent' => 50.0,
                'executed_amount' => 0.0,
            ],
        ];
    }

    public static function percentageErrors(array $rows): array
    {
        $calculator = app(ImportesDistributionCalculator::class);
        $totals = $calculator->percentageTotals($rows);

        return array_filter([
            'inicio' => self::validatePercentageTotal((float) ($totals['approved_percent_total'] ?? 0.0), 'aprobacion'),
            'contratacion' => self::validatePercentageTotal((float) ($totals['contract_percent_total'] ?? 0.0), 'contratacion'),
            'cesion' => self::validatePercentageTotal((float) ($totals['awarded_percent_total'] ?? 0.0), 'adjudicacion/cesion'),
            'ejecucion' => self::validatePercentageTotal((float) ($totals['executed_percent_total'] ?? 0.0), 'ejecucion'),
        ]);
    }

    protected static function validatePercentageTotal(float $value, string $phaseLabel): ?string
    {
        if (abs($value - 100.0) <= self::PERCENTAGE_TOLERANCE) {
            return null;
        }

        return sprintf('La suma de porcentajes en %s debe ser 100.00%% (actual: %.2f%%).', $phaseLabel, $value);
    }

    protected function renderPhaseSummary(array $rows): string
    {
        $errors = self::percentageErrors($rows);
        $totals = $this->calculator?->percentageTotals($rows) ?? [];

        $items = [
            'inicio' => ['label' => 'Aprobación', 'value' => (float) ($totals['approved_percent_total'] ?? 0.0)],
            'contratacion' => ['label' => 'Contratación', 'value' => (float) ($totals['contract_percent_total'] ?? 0.0)],
            'cesion' => ['label' => 'Adjudicación', 'value' => (float) ($totals['awarded_percent_total'] ?? 0.0)],
            'ejecucion' => ['label' => 'Ejecución', 'value' => (float) ($totals['executed_percent_total'] ?? 0.0)],
        ];

        $html = '<div class="grid gap-2 md:grid-cols-2 xl:grid-cols-4">';

        foreach ($items as $key => $item) {
            $hasError = array_key_exists($key, $errors);
            $classes = $hasError
                ? 'border-danger-200 bg-danger-50 text-danger-700'
                : 'border-success-200 bg-success-50 text-success-700';

            $html .= sprintf(
                '<div class="rounded-lg border px-3 py-2 text-sm %s"><span class="font-medium">%s:</span> %s%%</div>',
                $classes,
                e($item['label']),
                number_format($item['value'], 2, '.', '')
            );
        }

        $html .= '</div>';

        if ($errors !== []) {
            $html .= '<div class="mt-2 text-sm text-danger-600">' . e(implode(' · ', array_values($errors))) . '</div>';
        }

        return $html;
    }

    protected function normalizeNumber(mixed $value): string
    {
        return number_format((float) ($value ?? 0), 2, '.', '');
    }
}

<?php

declare(strict_types=1);

namespace App\Filament\Obras\Pages;

use App\Models\User;
use App\Services\DateHitos\ConsultaExpedienteFechaHitosService;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

final class HitosFechasMaterializados extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Hitos materializados';

    protected static ?string $slug = 'hitos-fechas-materializados';

    protected string $view = 'filament.obras.pages.hitos-fechas-materializados';

    /**
     * @var array<string, int|string|null>
     */
    public array $filtros = [];

    /**
     * @var array{
     *   filas: array<int, array<string, int|string|null>>,
     *   total: int,
     *   generado_en: string,
     *   tiene_resultados: bool
     * }
     */
    public array $resultado = [
        'filas' => [],
        'total' => 0,
        'generado_en' => '',
        'tiene_resultados' => false,
    ];

    public function mount(ConsultaExpedienteFechaHitosService $service): void
    {
        $this->filtros = [
            'expediente_id' => null,
            'codigo_hito' => null,
            'tabla_origen' => null,
            'fecha_desde' => null,
            'fecha_hasta' => null,
            'limite' => 100,
        ];

        $this->resultado = $service->buscar($this->filtros);
    }

    public function getTitle(): string
    {
        return 'Hitos de fechas materializados';
    }

    public static function canAccess(): bool
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            return false;
        }

        return $user->hasRole('super_admin');
    }

    /**
     * @throws ValidationException
     */
    public function aplicarFiltros(ConsultaExpedienteFechaHitosService $service): void
    {
        $validator = Validator::make($this->filtros, [
            'expediente_id' => ['nullable', 'string', 'max:120'],
            'codigo_hito' => ['nullable', 'string', 'max:80'],
            'tabla_origen' => ['nullable', 'string', 'max:120'],
            'fecha_desde' => ['nullable', 'date'],
            'fecha_hasta' => ['nullable', 'date', 'after_or_equal:fecha_desde'],
            'limite' => ['required', 'integer', 'min:25', 'max:500'],
        ], [
            'fecha_hasta.after_or_equal' => 'La fecha hasta debe ser mayor o igual que la fecha desde.',
        ]);

        /** @var array<string, int|string|null> $validated */
        $validated = $validator->validate();
        $this->filtros = $validated;
        $this->resultado = $service->buscar($this->filtros);
    }

    /**
     * @return array<string, string>
     */
    public function getOpcionesHitos(): array
    {
        return app(ConsultaExpedienteFechaHitosService::class)->opcionesHitos();
    }

    /**
     * @return array<string, string>
     */
    public function getOpcionesTablas(): array
    {
        return app(ConsultaExpedienteFechaHitosService::class)->opcionesTablas();
    }
}

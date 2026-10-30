<?php

declare(strict_types=1);

namespace App\Filament\Obras\Pages;

use App\Models\User;
use App\Services\DateHitos\ConsultaExpedienteFechaHitosService;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Página de consulta de hitos de fechas materializados de los expedientes.
 *
 * Accesible solo para usuarios con rol 'super_admin'. Permite filtrar por
 * expediente, código de hito, tabla de origen y rango de fechas, delegando
 * la búsqueda en {@see ConsultaExpedienteFechaHitosService}.
 */
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

    /**
     * Inicializa los filtros por defecto y lanza la primera búsqueda.
     */
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

    /**
     * Solo pueden acceder los usuarios con rol 'super_admin'.
     */
    public static function canAccess(): bool
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            return false;
        }

        return $user->hasRole('super_admin');
    }

    /**
     * Valida los filtros introducidos y ejecuta la búsqueda de hitos.
     *
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
     * Opciones de códigos de hito para el selector del filtro.
     *
     * @return array<string, string>
     */
    public function getOpcionesHitos(): array
    {
        return app(ConsultaExpedienteFechaHitosService::class)->opcionesHitos();
    }

    /**
     * Opciones de tablas de origen para el selector del filtro.
     *
     * @return array<string, string>
     */
    public function getOpcionesTablas(): array
    {
        return app(ConsultaExpedienteFechaHitosService::class)->opcionesTablas();
    }
}

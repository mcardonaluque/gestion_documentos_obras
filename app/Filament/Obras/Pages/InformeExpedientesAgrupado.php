<?php

declare(strict_types=1);

namespace App\Filament\Obras\Pages;

use App\DTOs\Informes\InformeExpedientesFiltrosData;
use App\DTOs\Informes\InformeExpedientesFilaData;
use App\DTOs\Informes\InformeExpedientesGrupoData;
use App\DTOs\Informes\InformeExpedientesResultadoData;
use App\Enums\InformeExpedientesAgrupacion;
use App\Models\TablaDeEstados;
use App\Models\Team;
use App\Models\User;
use App\Policies\InformeExpedientesPolicy;
use App\Services\Informes\InformeExpedientesService;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

final class InformeExpedientesAgrupado extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static string | \UnitEnum | null $navigationGroup = 'Informes';

    protected static ?string $navigationLabel = 'Expedientes agrupados';

    protected string $view = 'filament.obras.pages.informe-expedientes-agrupado';

    /**
     * @var array<string, int|string|null>
     */
    public array $filtros = [];

    /**
     * @var array{
     *   grupos: array<int, array{
     *     clave: string,
     *     etiqueta: string,
     *     total_expedientes: int,
     *     total_importe_aprobado: float,
     *     filas: array<int, array{
     *       clave_grupo: string,
     *       etiqueta_grupo: string,
     *       expediente_id: string,
     *       nombre_obra: string,
     *       estado: string,
     *       municipio: string,
     *       anio_ejecucion: int,
     *       importe_aprobado: float
     *     }>
     *   }>,
     *   total_expedientes: int,
     *   total_importe_aprobado: float,
     *   generado_en: string,
     *   tiene_resultados: bool
     * }
     */
    public array $resultado = [
        'grupos' => [],
        'total_expedientes' => 0,
        'total_importe_aprobado' => 0.0,
        'generado_en' => '',
        'tiene_resultados' => false,
    ];

    public function mount(InformeExpedientesService $service): void
    {
        $anioActual = (int) now()->format('Y');

        $this->filtros = [
            'anio_desde' => $anioActual - 1,
            'anio_hasta' => $anioActual,
            'cod_estado' => null,
            'team_id' => null,
            'agrupacion' => InformeExpedientesAgrupacion::ESTADO->value,
        ];

        $this->resultado = $this->toLivewireArray($service->generar($this->getFiltrosData()));
    }

    public function getTitle(): string
    {
        return 'Informe agrupado de expedientes';
    }

    public static function canAccess(): bool
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            return false;
        }

        return app(InformeExpedientesPolicy::class)->viewAny($user);
    }

    /**
     * @throws ValidationException
     */
    public function aplicarFiltros(InformeExpedientesService $service): void
    {
        $validator = Validator::make($this->filtros, $this->rules(), $this->messages());

        $validated = $validator->validate();

        /** @var array<string, int|string|null> $validated */
        $this->filtros = $validated;
        $this->resultado = $this->toLivewireArray($service->generar($this->getFiltrosData()));
    }

    /**
     * @return array<string, string>
     */
    public function getOpcionesEstado(): array
    {
        /** @var array<string, string> $options */
        $options = TablaDeEstados::query()
            ->orderBy('estado')
            ->pluck('estado', 'cod_estado')
            ->toArray();

        return $options;
    }

    /**
     * @return array<int|string, string>
     */
    public function getOpcionesMunicipio(): array
    {
        /** @var array<int|string, string> $options */
        $options = Team::query()
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();

        return $options;
    }

    public function getUrlImpresion(): string
    {
        return route('informes.expedientes.imprimir', $this->getFiltrosData()->toQueryParams());
    }

    private function getFiltrosData(): InformeExpedientesFiltrosData
    {
        return InformeExpedientesFiltrosData::fromArray($this->filtros);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    private function rules(): array
    {
        return [
            'anio_desde' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'anio_hasta' => ['nullable', 'integer', 'min:2000', 'max:2100', 'gte:anio_desde'],
            'cod_estado' => ['nullable', 'string', 'max:50'],
            'team_id' => ['nullable', 'integer', 'exists:teams,id'],
            'agrupacion' => [
                'required',
                'string',
                Rule::in(array_keys(InformeExpedientesAgrupacion::options())),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    private function messages(): array
    {
        return [
            'anio_hasta.gte' => 'El año hasta debe ser mayor o igual al año desde.',
        ];
    }

    /**
     * Convierte DTOs de dominio a una estructura serializable por Livewire.
     *
     * @return array{
     *   grupos: array<int, array{
     *     clave: string,
     *     etiqueta: string,
     *     total_expedientes: int,
     *     total_importe_aprobado: float,
     *     filas: array<int, array{
     *       clave_grupo: string,
     *       etiqueta_grupo: string,
     *       expediente_id: string,
     *       nombre_obra: string,
     *       estado: string,
     *       municipio: string,
     *       anio_ejecucion: int,
     *       importe_aprobado: float
     *     }>
     *   }>,
     *   total_expedientes: int,
     *   total_importe_aprobado: float,
     *   generado_en: string,
     *   tiene_resultados: bool
     * }
     */
    private function toLivewireArray(InformeExpedientesResultadoData $resultado): array
    {
        $grupos = [];

        foreach ($resultado->grupos as $grupo) {
            $grupos[] = $this->grupoToArray($grupo);
        }

        return [
            'grupos' => $grupos,
            'total_expedientes' => $resultado->totalExpedientes,
            'total_importe_aprobado' => $resultado->totalImporteAprobado,
            'generado_en' => $resultado->generadoEn->format('d/m/Y H:i'),
            'tiene_resultados' => $resultado->tieneResultados(),
        ];
    }

    /**
     * @return array{
     *   clave: string,
     *   etiqueta: string,
     *   total_expedientes: int,
     *   total_importe_aprobado: float,
     *   filas: array<int, array{
     *     clave_grupo: string,
     *     etiqueta_grupo: string,
     *     expediente_id: string,
     *     nombre_obra: string,
     *     estado: string,
     *     municipio: string,
     *     anio_ejecucion: int,
     *     importe_aprobado: float
     *   }>
     * }
     */
    private function grupoToArray(InformeExpedientesGrupoData $grupo): array
    {
        $filas = [];

        foreach ($grupo->filas as $fila) {
            $filas[] = $this->filaToArray($fila);
        }

        return [
            'clave' => $grupo->clave,
            'etiqueta' => $grupo->etiqueta,
            'total_expedientes' => $grupo->totalExpedientes(),
            'total_importe_aprobado' => $grupo->totalImporteAprobado(),
            'filas' => $filas,
        ];
    }

    /**
     * @return array{
     *   clave_grupo: string,
     *   etiqueta_grupo: string,
     *   expediente_id: string,
     *   nombre_obra: string,
     *   estado: string,
     *   municipio: string,
     *   anio_ejecucion: int,
     *   importe_aprobado: float
     * }
     */
    private function filaToArray(InformeExpedientesFilaData $fila): array
    {
        return [
            'clave_grupo' => $fila->claveGrupo,
            'etiqueta_grupo' => $fila->etiquetaGrupo,
            'expediente_id' => $fila->expedienteId,
            'nombre_obra' => $fila->nombreObra,
            'estado' => $fila->estado,
            'municipio' => $fila->municipio,
            'anio_ejecucion' => $fila->anioEjecucion,
            'importe_aprobado' => $fila->importeAprobado,
        ];
    }
}

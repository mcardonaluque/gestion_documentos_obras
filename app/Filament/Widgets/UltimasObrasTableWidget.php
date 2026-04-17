<?php

namespace App\Filament\Widgets;

use Filament\Tables\Table;
use Filament\Actions\ActionGroup;
use Filament\Actions\Action;
use App\Filament\Obras\Resources\DatosDeInicioDeObras\DatosDeInicioDeObrasResource;
use App\Filament\Obras\Resources\DatosEjecucionObras\DatosEjecucionObrasResource;
use App\Filament\Obras\Resources\Documentoexpedientes\DocumentoexpedienteResource;
use App\Filament\Obras\Resources\Proyectos\ProyectoResource;
use App\Filament\Obras\Resources\ObraCedidas\ObraCedidaResource;
use App\Models\DatosDeInicioDeObras;
use App\Models\DatosEjecucionObras;
use App\Models\DocumentoExpediente;
use App\Models\Proyecto;
use App\Models\ObraCedida;
use App\Services\Assignments\ExpedienteAssignmentVisibilityService;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Traits\CommonFilters;

class UltimasObrasTableWidget extends BaseWidget
{
    use CommonFilters;
    protected static ?string $heading = 'Últimas Obras';

    protected int | string | array $columnSpan = 'full'; // ocupa todo el ancho
    public ?string $obraSeleccionadaId = null;
    public function table(Table $table): Table
    {
        return $table
            ->query(
                app(ExpedienteAssignmentVisibilityService::class)->scopeToCurrentUserAssigned(
                    DatosDeInicioDeObras::query()
                    ->where('Codigo_Plan', '<>', '')
                    ->whereNotNull('expediente_id')
                    ->where('expediente_id', '<>', '')
                )
                    // ajusta si usas otra columna de fecha
                    //->limit(500)

            )
            ->columns([

                TextColumn::make('expediente_id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('Obra')
                    ->label('Obra')
                    // ->sortable()

                    ->grow(false)
                    ->extraHeaderAttributes(['class' => 'px-8'])
                    ->extraCellAttributes(['class' => 'px-8'])
                    ->getStateUsing(function ($record) {
                        return $record->Codigo_Plan . '-' . $record->numero_obra . '-' . $record->subreferecnia . '-' . $record->ao_ejecucion;
                    }),
                TextColumn::make('nombre_obra1')
                    ->label('Nombre de la obra')
                    ->searchable()
                    ->wrap()
                    ->grow(false),
                TextColumn::make('codigo_estado_obra')
                    ->label('Estado'),
                TextColumn::make('Ubicacion')
                    ->label('Ubicación')
                    ->getStateUsing(function ($record) {
                    // dd($record->municipios);
                    return ($record->municipio !== null && $record->municipio !== 0)
                    ? $record->municipios->nombre_municipio
                    : $record->carretera;
                        //return $record->municipios->nombre_municipio ?: $record->carretera;
                    }),
                TextColumn::make('municipios.zonas.ZONA')
                    ->label('Zona')
                    ->sortable()
                    ->searchable()
                    ->grow(false)
                    ->extraHeaderAttributes(['class' => 'px-8'])
                    ->extraCellAttributes(['class' => 'px-8'])
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('estados.estado')
                    ->sortable()
                    ->searchable()
                    //->grow()
                    ->extraHeaderAttributes(['class' => 'px-8'])
                    ->extraCellAttributes(['class' => 'px-8'])
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('forma_ejecucion')
                    ->Label('F.Ejecuc.')
                    ->sortable()
                    ->width(50)
                    ->searchable()
                    ->extraHeaderAttributes(['class' => 'px-8'])
                    ->extraCellAttributes(['class' => 'px-8'])
                    ->toggleable(isToggledHiddenByDefault: false),

            ])
            //->paginated(true)
            ->filters(self::getCommonFilters())
            ->filtersLayout(FiltersLayout::AboveContent)
            ->defaultSort('ao_ejecucion', 'desc')

            //->selectable()

            ->headerActions([
                // ActionGroup para el menú de acciones
                ActionGroup::make([
                    Action::make('editar_inicio')
                        ->label('Editar Datos de Inicio')
                        ->icon('heroicon-o-play')
                        ->color('info')
                        /*->action(fn () => redirect(
                            DatosDeInicioDeObrasResource::getUrl('edit', ['record' => $this->obraSeleccionadaId,'panel'=>'planes'])))*/
                        ->url(function () {
                            if (!$this->obraSeleccionadaId) return '#';
                            $obra = DatosDeInicioDeObras::find($this->obraSeleccionadaId);
                            return $obra ? DatosDeInicioDeObrasResource::getUrl('edit', ['record' => $obra])  : '#';
                        })
                        ->openUrlInNewTab(false)])

                        ->label('Inicio de Obras')
                ->icon('heroicon-o-cog')
                ->color('primary')
                ->button()
                ->dropdownPlacement('bottom-start')
                ->tooltip($this->obraSeleccionadaId ? 'Editar Datos de Inicio para obra seleccionada' : 'Selecciona una obra primero')
                ->hidden(fn () => !$this->obraSeleccionadaId),

                ActionGroup::make([
                    Action::make('Documentos')
                        ->label('Documentos del Expediente')
                        ->icon('heroicon-o-play')
                        ->color('info')
                        /*->action(fn () => redirect(
                            DatosDeInicioDeObrasResource::getUrl('edit', ['record' => $this->obraSeleccionadaId,'panel'=>'planes'])))*/
                        ->url(function () {
                            if (!$this->obraSeleccionadaId) return '#';
                            $obra = DocumentoExpediente::where('expediente_id', $this->obraSeleccionadaId)->first();
                            return $obra ? DocumentoexpedienteResource::getUrl('edit', ['record' => $obra])  : '#';
                        })
                        ->openUrlInNewTab(false)])
                        ->label('Documentos')
                ->icon('heroicon-o-cog')
                ->color('primary')
                ->button()
                ->dropdownPlacement('bottom-start')
                ->tooltip($this->obraSeleccionadaId ? 'Editar Documentos del Expediente para obra seleccionada' : 'Selecciona una obra primero')
                ->hidden(fn () => !$this->obraSeleccionadaId),
                ActionGroup::make([
                    Action::make('editar_ejecucion')
                        ->label('Editar Ejecución')
                        ->icon('heroicon-o-cog')
                        ->color('warning')
                        ->url(function () {
                            if (!$this->obraSeleccionadaId) return '#';
                            $obra = DatosEjecucionObras::find($this->obraSeleccionadaId);
                            return $obra ? DatosEjecucionObrasResource::getUrl('edit', ['record' => $obra]) . '#ejecucion' : '#';
                        })

                        ->hidden(fn () => !$this->obraSeleccionadaId),

                    Action::make('editar_cesion')
                        ->label('Editar Cesión')
                        ->icon('heroicon-o-document-duplicate')
                        ->color('success')
                        ->url(function () {
                            if (!$this->obraSeleccionadaId) return '#';
                            $obra = ObraCedida::find($this->obraSeleccionadaId);
                            return $obra ? ObraCedidaResource::getUrl('edit', ['record' => $obra]) . '#cesion' : '#';
                        })])
                        ->label('Ejecución / Cesión')
                        ->icon('heroicon-o-cog')
                        ->color('primary')
                        ->button()
                        ->dropdownPlacement('bottom-start')
                        ->tooltip($this->obraSeleccionadaId ? 'Acciones para obra seleccionada' : 'Selecciona una obra primero')
                        ->hidden(fn () => !$this->obraSeleccionadaId),
                ActionGroup::make([
                    Action::make('editar_proyecto')
                        ->label('Editar Proyecto')
                        ->icon('heroicon-o-clipboard-document')
                        ->color('primary')
                        ->url(function () {
                            if (!$this->obraSeleccionadaId) return '#';
                            $obra = Proyecto::find($this->obraSeleccionadaId);
                            return $obra ? ProyectoResource::getUrl('edit', ['record' => $obra]) . '#proyecto' : '#';
                        })
                        ->hidden(fn () => !$this->obraSeleccionadaId),

                    Action::make('ver_completo')
                        ->label('Ver Obra Completa')
                        ->icon('heroicon-o-eye')
                        ->url(function () {
                            if (!$this->obraSeleccionadaId) return '#';
                            $obra = DatosDeInicioDeObras::find($this->obraSeleccionadaId);
                            return $obra ? DatosDeInicioDeObrasResource::getUrl('view', ['record' => $obra,]) : '#';
                        })
                        ->hidden(fn () => !$this->obraSeleccionadaId),

                ])
                ->label('Proyecto / Obra Completa')
                ->icon('heroicon-o-cog')
                ->color('primary')
                ->button()
                ->dropdownPlacement('bottom-start')
                ->tooltip($this->obraSeleccionadaId ? 'Acciones para obra seleccionada' : 'Selecciona una obra primero')])


            ->recordActions([
                Action::make('seleccionar')
                    ->label(function (DatosDeInicioDeObras $record){
                        //dd($record);
                        //dd($this->obraSeleccionadaId === $record->Expediente );

                        return $this->obraSeleccionadaId === $record->expediente_id
                            ? 'Seleccionada'
                            : 'Seleccionar';
                    })


                    ->icon(fn (DatosDeInicioDeObras $record) =>
                        $this->obraSeleccionadaId === $record->expediente_id
                            ? 'heroicon-o-check-circle'
                            : 'heroicon-o-plus-circle'
                    )
                    ->color(fn (DatosDeInicioDeObras $record) =>
                        $this->obraSeleccionadaId === $record->expediente_id
                            ? 'success'
                            : 'primary'
                    )
                    ->action(function (DatosDeInicioDeObras $record) {
                        $this->obraSeleccionadaId = $record->expediente_id;
                        //dd($this->obraSeleccionadaId);
                        // Forzar recarga para actualizar la interfaz
                        $this->dispatch('refreshWidget');
                    }),
            ]);



    }

    public function getTableRecordKey(Model | array $record): string
    {
        if (is_array($record)) {
            $key = $record['expediente_id'] ?? null;

            if (filled($key)) {
                return (string) $key;
            }

            return (string) md5((string) json_encode($record));
        }

        $key = $record->getKey();

        if (filled($key)) {
            return (string) $key;
        }

        return (string) md5((string) json_encode($record->getAttributes()));
    }

    protected function getListeners(): array
    {
        return [
            'refreshWidget' => '$refresh',
        ];
    }

    // Método para mostrar qué obra está seleccionada globalmente
    protected function getFooter(): ?string
    {
        if (!$this->obraSeleccionadaId) {
            return 'No hay obra seleccionada';
        }

        $obra = DatosDeInicioDeObras::find($this->obraSeleccionadaId);
        return $obra ? "Obra seleccionada: {$obra->nombre} ({$obra->expediente_id})" : 'Obra no encontrada';
    }
}



<?php

namespace App\Filament\Widgets;

use App\Filament\Obras\Resources\DatosDeInicioDeObrasResource;
use App\Filament\Obras\Resources\DatosEjecucionObrasResource;
use App\Filament\Obras\Resources\ProyectoResource;
use App\Filament\Obras\Resources\ObraCedidaResource;
use App\Models\DatosDeInicioDeObras;
use App\Models\DatosEjecucionObras;
use App\Models\Proyecto;
use App\Models\ObraCedida;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Support\Facades\Log;
use Filament\Widgets\TableWidget as BaseWidget;

class UltimasObrasTableWidget extends BaseWidget
{
    protected static ?string $heading = 'Últimas Obras';

    protected int | string | array $columnSpan = 'full'; // ocupa todo el ancho
    public ?string $obraSeleccionadaId = null;
    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query(
                DatosDeInicioDeObras::query()->where('Codigo_Plan','<>','')
                    // ajusta si usas otra columna de fecha
                    //->limit(500)
                    
            )
            ->columns([
                
                Tables\Columns\TextColumn::make('expediente_id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Obra')
                    ->label('Obra')
                    // ->sortable()
                    
                    ->grow(false)
                    ->extraHeaderAttributes(['class' => 'px-8'])
                    ->extraCellAttributes(['class' => 'px-8'])
                    ->getStateUsing(function ($record) {
                        return $record->Codigo_Plan . '-' . $record->numero_obra . '-' . $record->subreferecnia . '-' . $record->ao_ejecucion;
                    }),
                Tables\Columns\TextColumn::make('nombre_obra1')
                    ->label('Nombre')
                    ->searchable()
                    //->wrap()
                    ->grow(false)
                    ->size(TextColumn\TextColumnSize::ExtraSmall),
                Tables\Columns\TextColumn::make('codigo_estado_obra')
                    ->label('Estado'),
                Tables\Columns\TextColumn::make('Ubicacion')
                    ->label('Ubicación')
                    ->getStateUsing(function ($record) {
                    // dd($record->municipios);
                    return ($record->municipio !== null && $record->municipio !== 0)
                    ? $record->municipios->nombre_municipio
                    : $record->carretera;
                        //return $record->municipios->nombre_municipio ?: $record->carretera;
                    }),
                Tables\Columns\TextColumn::make('municipios.zonas.ZONA')
                    ->label('Zona')
                    ->sortable()
                    ->searchable()
                    ->grow(false)
                    ->extraHeaderAttributes(['class' => 'px-8'])
                    ->extraCellAttributes(['class' => 'px-8'])
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('estados.estado_abrev')
                    ->sortable()
                    ->searchable()
                    ->grow()
                    ->extraHeaderAttributes(['class' => 'px-8'])
                    ->extraCellAttributes(['class' => 'px-8'])
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('forma_ejecucion')
                    ->Label('F.Ejecuc.')
                    ->sortable()
                    ->width(50)
                    ->searchable()
                    ->extraHeaderAttributes(['class' => 'px-8'])
                    ->extraCellAttributes(['class' => 'px-8'])
                    ->toggleable(isToggledHiddenByDefault: false),
               
            ])
            ->paginated(true) 
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
                ->tooltip($this->obraSeleccionadaId ? 'Editar Datos de Inicio para obra seleccionada' : 'Selecciona una obra primero'),

                       // ->hidden(fn () => !$this->obraSeleccionadaId),
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
                
            
            ->actions([
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



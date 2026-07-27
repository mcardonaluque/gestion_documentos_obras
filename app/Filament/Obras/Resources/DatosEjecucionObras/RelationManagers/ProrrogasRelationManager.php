<?php

namespace App\Filament\Obras\Resources\DatosEjecucionObras\RelationManagers;

use App\Actions\Prorrogas\RegistrarProrrogaAction;
use App\Enums\ProrrogaAlcance;
use App\Enums\ProrrogaOrigen;
use App\Enums\ProrrogaTipo;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProrrogasRelationManager extends RelationManager
{
    /** Relación de prórrogas ligada al expediente de la obra. */
    protected static string $relationship = 'prorrogas';

    /** Título visible del bloque de relación en Filament. */
    protected static ?string $title = 'Prórrogas';

    /**
     * Formulario de alta/edición de prórrogas con datos de tramitación y control de plazos.
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                TextInput::make('NumSec')
                    ->label('Nº secuencia')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(false),
                Select::make('tipo_prorroga')
                    ->label('Tipo de prórroga')
                    ->required()
                    ->options(ProrrogaTipo::options())
                    ->default(ProrrogaTipo::EJECUCION->value),
                Select::make('alcance_prorroga')
                    ->label('Alcance')
                    ->options(ProrrogaAlcance::options())
                    ->visible(fn (callable $get): bool => $get('tipo_prorroga') === ProrrogaTipo::NORMATIVA->value),
                Select::make('origen_prorroga')
                    ->label('Origen')
                    ->required()
                    ->options(ProrrogaOrigen::options())
                    ->default(ProrrogaOrigen::SOLICITUD->value),
                DateTimePicker::make('FecPeticionProrrogaDeDip')
                    ->label('Petición prórroga Diputación'),
                DateTimePicker::make('FecSolicitudProrrogaDeCont')
                    ->label('Solicitud prórroga contratista'),
                DateTimePicker::make('FecProrroga')
                    ->label('Fecha prórroga'),
                DatePicker::make('fecha_limite_anterior')
                    ->label('Fecha límite anterior'),
                DatePicker::make('fecha_limite_nueva')
                    ->label('Fecha límite nueva')
                    ->required(),
                TextInput::make('dias_concedidos')
                    ->label('Días concedidos')
                    ->numeric()
                    ->minValue(1),
                DatePicker::make('fecha_notificacion_ayto')
                    ->label('Notificación/remisión al Ayto.'),
                DateTimePicker::make('FecSolicitudInfTec')
                    ->label('Solicitud informe técnico'),
                DateTimePicker::make('FecInfTec')
                    ->label('Fecha informe técnico'),
                DatePicker::make('fecha_firma_informe_rof')
                    ->label('Firma informe ROF'),
                TextInput::make('csv_informe_rof')
                    ->label('CSV informe ROF')
                    ->maxLength(120),
                DateTimePicker::make('FecComInf')
                    ->label('Comisión informativa'),
                DatePicker::make('fecha_firma_propuesta')
                    ->label('Firma propuesta'),
                TextInput::make('csv_propuesta')
                    ->label('CSV propuesta')
                    ->maxLength(120),
                DateTimePicker::make('FecComGob')
                    ->label('Comisión de gobierno'),
                DateTimePicker::make('FecDecreto')
                    ->label('Fecha decreto'),
                DatePicker::make('fecha_firma_decreto')
                    ->label('Firma decreto'),
                TextInput::make('csv_decreto')
                    ->label('CSV decreto')
                    ->maxLength(120),
                TextInput::make('NumDecreto')
                    ->label('Nº decreto')
                    ->maxLength(10),
                Textarea::make('MotivoProrroga')
                    ->label('Motivo prórroga')
                    ->maxLength(250)
                    ->columnSpanFull(),
                Textarea::make('observaciones_tramitacion')
                    ->label('Observaciones de tramitación')
                    ->columnSpanFull(),
            ]);
    }

    /**
     * Tabla de seguimiento de prórrogas del expediente propietario.
     */
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('NumSec')
            ->defaultSort('NumSec')
            ->columns([
                TextColumn::make('NumSec')
                    ->label('Nº secuencia')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tipo_prorroga')
                    ->label('Tipo')
                    ->badge(),
                TextColumn::make('alcance_prorroga')
                    ->label('Alcance')
                    ->badge(),
                TextColumn::make('FecProrroga')
                    ->label('Fecha prórroga')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('fecha_limite_nueva')
                    ->label('Nueva fecha límite')
                    ->date()
                    ->sortable(),
                TextColumn::make('FecDecreto')
                    ->label('Fecha decreto')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('NumDecreto')
                    ->label('Nº decreto')
                    ->searchable(),
                TextColumn::make('MotivoProrroga')
                    ->label('Motivo')
                    ->limit(80)
                    ->searchable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Añadir prórroga')
                    ->action(function (array $data): void {
                        app(RegistrarProrrogaAction::class)->execute($this->getOwnerRecord(), $data);
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

<?php

namespace App\Filament\Obras\Resources\DatosEjecucionObras\RelationManagers;

use App\Models\Prorroga;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProrrogasRelationManager extends RelationManager
{
    protected static string $relationship = 'prorrogas';

    protected static ?string $title = 'Prórrogas';

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
                DateTimePicker::make('FecPeticionProrrogaDeDip')
                    ->label('Petición prórroga Diputación'),
                DateTimePicker::make('FecSolicitudProrrogaDeCont')
                    ->label('Solicitud prórroga contratista'),
                DateTimePicker::make('FecProrroga')
                    ->label('Fecha prórroga'),
                DateTimePicker::make('FecSolicitudInfTec')
                    ->label('Solicitud informe técnico'),
                DateTimePicker::make('FecInfTec')
                    ->label('Fecha informe técnico'),
                DateTimePicker::make('FecComInf')
                    ->label('Comisión informativa'),
                DateTimePicker::make('FecComGob')
                    ->label('Comisión de gobierno'),
                DateTimePicker::make('FecDecreto')
                    ->label('Fecha decreto'),
                TextInput::make('NumDecreto')
                    ->label('Nº decreto')
                    ->maxLength(10),
                Textarea::make('MotivoProrroga')
                    ->label('Motivo prórroga')
                    ->maxLength(250)
                    ->columnSpanFull(),
            ]);
    }

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
                TextColumn::make('FecProrroga')
                    ->label('Fecha prórroga')
                    ->dateTime()
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
                    ->mutateDataUsing(function (array $data): array {
                        $owner = $this->getOwnerRecord();

                        $nextNumSec = (int) (Prorroga::query()
                            ->where('expediente_id', $owner->expediente_id)
                            ->max('NumSec') ?? 0) + 1;

                        $data['PlanObra'] = $owner->Codigo_Plan;
                        $data['NumObra'] = (int) $owner->numero_obra;
                        $data['SubRef'] = (int) $owner->subreferencia;
                        $data['AoObra'] = (int) $owner->ao_ejecucion;
                        $data['NumSec'] = $nextNumSec;
                        $data['expediente_id'] = $owner->expediente_id;
                        $data['team_id'] = $owner->team_id;

                        return $data;
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

<?php

namespace App\Filament\Obras\Resources\ActasDeRecepcion;

use App\Filament\Obras\Resources\ActasDeRecepcion\Pages\CreateActaderecepcion;
use App\Filament\Obras\Resources\ActasDeRecepcion\Pages\EditActaderecepcion;
use App\Filament\Obras\Resources\ActasDeRecepcion\Pages\ListActaderecepcions;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ActaderecepcionResource extends Resource
{
    protected static ?string $model = \App\Models\actaderecepcion::class;

    protected static ?string $modelLabel = 'Acta de recepcion';

    protected static ?string $pluralModelLabel = 'Actas de recepcion';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static string | \UnitEnum | null $navigationGroup = 'Ejecucion';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                TextInput::make('expediente_id')
                    ->label('Expediente')
                    ->required()
                    ->maxLength(50),
                TextInput::make('TipoActaRecepcion')
                    ->maxLength(1),
                DateTimePicker::make('Fecha_Acta_RecProv'),
                TextInput::make('Lugar_Acta_Rec')
                    ->maxLength(25),
                DateTimePicker::make('Fecha_Com_Inf'),
                DateTimePicker::make('Fecha_Edicto_BOE'),
                DateTimePicker::make('Fecha_BOE'),
                TextInput::make('Num_BOE')
                    ->maxLength(3),
                TextInput::make('Plazo_Reclam')
                    ->numeric(),
                DateTimePicker::make('Fecha_Certif_NO_Reclam'),
                DateTimePicker::make('Fecha_Com_Inf_2'),
                DateTimePicker::make('Fecha_Com_Gob'),
                DateTimePicker::make('Fecha_Comun_Contrat'),
                DateTimePicker::make('Fecha_Certif_Liquid'),
                DateTimePicker::make('Fecha_Rem_Interv'),
                DateTimePicker::make('Fecha_Rem_MAP'),
                TextInput::make('Admin_ActaRecepcion')
                    ->maxLength(60),
                TextInput::make('Dir_ActaRecepcion')
                    ->maxLength(60),
                TextInput::make('Alcalde_ActaRecepcion')
                    ->maxLength(60),
                TextInput::make('Cont_ActaRecepcion')
                    ->maxLength(60),
                TextInput::make('Interv_ActaRecepcion')
                    ->maxLength(60),
                TextInput::make('Dipu_ActaRecepcion')
                    ->maxLength(60),
                Textarea::make('Texto')
                    ->columnSpanFull(),
                DateTimePicker::make('Fecha_Paralizacion_Temporal'),
                TextInput::make('Motivo_Paralizacion')
                    ->maxLength(200),
                DateTimePicker::make('Fecha_Aprob_Paralizacion_Temporal'),
                DateTimePicker::make('Fecha_Inicio_Paralizacion'),
                DateTimePicker::make('Fecha_Final_Paralizacion'),
                DateTimePicker::make('Fecha_Acta_Rec'),
                DateTimePicker::make('Fecha_Aviso_Finalizacion'),
                DateTimePicker::make('Fecha_Aviso_FinalizacionMAP'),
                DateTimePicker::make('Fecha_Medicion'),
                TextInput::make('team_id')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('expediente_id')
                    ->label('Expediente')
                    ->searchable(),
                TextColumn::make('TipoActaRecepcion')
                    ->label('Tipo')
                    ->searchable(),
                TextColumn::make('Fecha_Acta_RecProv')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('Lugar_Acta_Rec')
                    ->searchable(),
                TextColumn::make('Fecha_Acta_Rec')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('Fecha_Medicion')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActaderecepcions::route('/'),
            'create' => CreateActaderecepcion::route('/create'),
            'edit' => EditActaderecepcion::route('/{record}/edit'),
        ];
    }
}

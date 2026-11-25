<?php

namespace App\Filament\Obras\Resources\ActasDeRecepcion;

use App\Filament\Obras\Resources\ActasDeRecepcion\Pages\CreateActaderecepcion;
use App\Filament\Obras\Resources\ActasDeRecepcion\Pages\EditActaderecepcion;
use App\Filament\Obras\Resources\ActasDeRecepcion\Pages\ListActaderecepcions;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use App\Filament\Support\DateFieldRules;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Recurso Filament para gestionar las actas de recepción de obras.
 *
 * Gestiona el modelo {@see \App\Models\actaderecepcion} dentro del grupo de
 * navegación "Ejecucion" del panel Obras. Permite registrar fechas de
 * recepción provisional/definitiva, publicaciones en BOE, paralizaciones
 * temporales y los intervinientes en el acta.
 */
class ActaderecepcionResource extends Resource
{
    protected static ?string $model = \App\Models\actaderecepcion::class;

    protected static ?string $modelLabel = 'Acta de recepcion';

    protected static ?string $pluralModelLabel = 'Actas de recepcion';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static string | \UnitEnum | null $navigationGroup = 'Ejecucion';

    /**
     * Define el formulario de creación/edición del acta de recepción.
     */
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
                DateFieldRules::apply(DateTimePicker::make('Fecha_Acta_RecProv')),
                TextInput::make('Lugar_Acta_Rec')
                    ->maxLength(25),
                DateFieldRules::apply(DateTimePicker::make('Fecha_Com_Inf')),
                DateFieldRules::apply(DateTimePicker::make('Fecha_Edicto_BOE')),
                DateFieldRules::apply(DateTimePicker::make('Fecha_BOE')),
                TextInput::make('Num_BOE')
                    ->maxLength(3),
                TextInput::make('Plazo_Reclam')
                    ->numeric(),
                DateFieldRules::apply(DateTimePicker::make('Fecha_Certif_NO_Reclam')),
                DateFieldRules::apply(DateTimePicker::make('Fecha_Com_Inf_2')),
                DateFieldRules::apply(DateTimePicker::make('Fecha_Com_Gob')),
                DateFieldRules::apply(DateTimePicker::make('Fecha_Comun_Contrat')),
                DateFieldRules::apply(DateTimePicker::make('Fecha_Certif_Liquid')),
                DateFieldRules::apply(DateTimePicker::make('Fecha_Rem_Interv')),
                DateFieldRules::apply(DateTimePicker::make('Fecha_Rem_MAP')),
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
                DateFieldRules::apply(DateTimePicker::make('Fecha_Paralizacion_Temporal')),
                TextInput::make('Motivo_Paralizacion')
                    ->maxLength(200),
                DateFieldRules::apply(DateTimePicker::make('Fecha_Aprob_Paralizacion_Temporal')),
                DateFieldRules::apply(DateTimePicker::make('Fecha_Inicio_Paralizacion')),
                DateFieldRules::apply(DateTimePicker::make('Fecha_Final_Paralizacion')),
                DateFieldRules::apply(DateTimePicker::make('Fecha_Acta_Rec')),
                DateFieldRules::apply(DateTimePicker::make('Fecha_Aviso_Finalizacion')),
                DateFieldRules::apply(DateTimePicker::make('Fecha_Aviso_FinalizacionMAP')),
                DateFieldRules::apply(DateTimePicker::make('Fecha_Medicion')),
                TextInput::make('team_id')
                    ->numeric(),
            ]);
    }

    /**
     * Define la tabla del listado de actas de recepción.
     */
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

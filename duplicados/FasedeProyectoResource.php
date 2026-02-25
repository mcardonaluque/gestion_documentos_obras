<?php

namespace App\Filament\Obras\Resources\FasedeProyectos;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Obras\Resources\FasedeProyectos\Pages\ListFasedeProyectos;
use App\Filament\Obras\Resources\FasedeProyectos\Pages\CreateFasedeProyecto;
use App\Filament\Obras\Resources\FasedeProyectos\Pages\EditFasedeProyecto;
use App\Filament\Obras\Resources\FasedeProyectoResource\Pages;
use App\Models\FasedeProyecto;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FasedeProyectoResource extends Resource
{
    protected static ?string $model = FasedeProyecto::class;

    protected static ?string $modelLabel = 'Fase de Proyecto';
    protected static ?string $pluralModelLabel = 'Fases de Proyectos';
    protected static ?string $tenantOwnershipRelationshipName = 'team';
    /**protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationColor = 'custom-blue';
    protected static \UnitEnum|string|null $navigationGroup="Proyectos";
    protected static ?string $navigationLabel ='Fases de Proyectos';**/

    public static function getEloquentQuery(): Builder
    {
        $añoActual = now()->year;

        $añoAnterior2 = now()->year - 2;
        return parent::getEloquentQuery()
        ->select('FasesDeProyectos.*') // Selecciona todas las columnas de la tabla "obras"
            //->leftJoin('DatosInicioDeObras', 'DatosInicioDeObras.Expediente', '=', 'Datos_Ejecucion_Obras.Expediente') // Join con la tabla "municipios"
            //->addSelect(trim('TablaDeMunicipios.nombre_municipio'))
           // ->WhereNotNull('carretera');  //->with('municipios');
           ->where('FasesDeProyectos.Expediente', '!=', NULL)
            ->where('FasesDeProyectos.ao_ejecucion', '>=', $añoAnterior2)
            ->where('FasesDeProyectos.ao_ejecucion', '<=', $añoActual);
            //->where('codigo_municipio','=', )

    }
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('cod_plan')
                    ->required()
                    ->serachable()
                    ->maxLength(45),
                TextInput::make('referencia')
                    ->required()
                    ->numeric(),
                TextInput::make('subreferencia')
                    ->numeric()
                    ->default(null),
                TextInput::make('ao_ejecucion')
                    ->required()
                    ->serachable()
                    ->numeric(),
                DatePicker::make('fechaincorporacion')
                    ->required(),
                DatePicker::make('fechaHelp'),
                TextInput::make('coddcoumento')
                    ->required()
                    ->numeric(),
                TextInput::make('csv')
                    ->maxLength(50)
                    ->default(null),
                TextInput::make('nregistro')
                    ->maxLength(45)
                    ->default(null),
                TextInput::make('nsecuencia')
                    ->numeric()
                    ->default(null),
                TextInput::make('estado')
                    ->required()
                    ->numeric(),
                TextInput::make('descripcion')
                    ->maxLength(255)
                    ->default(null),
                Select::make('team_id')
                    ->relationship('team', 'name'),
                TextInput::make('destino')
                    ->numeric(),
                TextInput::make('procedencia')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cod_plan')
                    ->searchable(),
                TextColumn::make('referencia')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('subreferencia')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ao_ejecucion')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('fechaincorporacion')
                    ->date()
                    ->sortable(),
                TextColumn::make('fechaHelp')
                    ->date()
                    ->sortable(),
                TextColumn::make('coddcoumento')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('Expediente')
                    ->searchable(),
                TextColumn::make('csv')
                    ->searchable(),
                TextColumn::make('nregistro')
                    ->searchable(),
                TextColumn::make('nsecuencia')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('estado')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('descripcion')
                    ->searchable(),
                TextColumn::make('team.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('destino')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('procedencia')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => ListFasedeProyectos::route('/'),
            'create' => CreateFasedeProyecto::route('/create'),
            'edit' => EditFasedeProyecto::route('/{record}/edit'),
        ];
    }
}

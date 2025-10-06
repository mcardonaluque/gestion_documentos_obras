<?php

namespace App\Filament\Obras\Resources;

use App\Filament\Obras\Resources\FasedeProyectoResource\Pages;
use App\Filament\Obras\Resources\FasedeProyectoResource\RelationManagers;
use App\Models\FasedeProyecto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FasedeProyectoResource extends Resource
{
    protected static ?string $model = FasedeProyecto::class;

    protected static ?string $modelLabel = 'Fase de Proyecto';
    protected static ?string $pluralModelLabel = 'Fases de Proyectos'; 
    protected static ?string $tenantOwnershipRelationshipName = 'team';
    /**protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationColor = 'custom-blue';
    protected static ?string $navigationGroup="Proyectos";
    protected static ?string $navigationLabel ='Fases de Proyectos';**/

    public static function getEloquentQuery(): Builder
    {
        $añoActual = now()->year;

        $añoAnterior2 = now()->subYear(2)->year;
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
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('cod_plan')
                    ->required()
                    ->serachable()
                    ->maxLength(45),
                Forms\Components\TextInput::make('referencia')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('subreferencia')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('ao_ejecucion')
                    ->required()
                    ->serachable()
                    ->numeric(),
                Forms\Components\DatePicker::make('fechaincorporacion')
                    ->required(),
                Forms\Components\DatePicker::make('fechaHelp'),
                Forms\Components\TextInput::make('coddcoumento')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('csv')
                    ->maxLength(50)
                    ->default(null),
                Forms\Components\TextInput::make('nregistro')
                    ->maxLength(45)
                    ->default(null),
                Forms\Components\TextInput::make('nsecuencia')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('estado')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('descripcion')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Select::make('team_id')
                    ->relationship('team', 'name'),
                Forms\Components\TextInput::make('destino')
                    ->numeric(),
                Forms\Components\TextInput::make('procedencia')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cod_plan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('referencia')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subreferencia')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ao_ejecucion')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fechaincorporacion')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fechaHelp')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('coddcoumento')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Expediente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('csv')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nregistro')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nsecuencia')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('estado')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('descripcion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('team.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('destino')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('procedencia')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListFasedeProyectos::route('/'),
            'create' => Pages\CreateFasedeProyecto::route('/create'),
            'edit' => Pages\EditFasedeProyecto::route('/{record}/edit'),
        ];
    }
}

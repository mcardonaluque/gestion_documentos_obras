<?php

namespace App\Filament\Obras\Resources\ImportesPorOrganismos;

use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use App\Filament\Obras\Resources\ImportesPorOrganismos\Pages\ListImportesPorOrganismos;
use App\Filament\Obras\Resources\ImportesPorOrganismos\Pages\CreateImportesPorOrganismo;
use App\Filament\Obras\Resources\ImportesPorOrganismos\Pages\EditImportesPorOrganismo;
use App\Filament\Obras\Resources\ImportesPorOrganismo\Pages;
use App\Models\ImportesPorOrganismo;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ImportesPorOrganismoResource extends Resource
{
    protected static ?string $model = ImportesPorOrganismo::class;
    protected static ?string $tenantOwnershipRelationshipName = 'team';
   /**protected static ?string $navigationGroup="Gestión de Importes";
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static bool $shouldRegisterNavigation = true;
    protected static ?string $navigationLabel = 'Importes por organismo';**/
    public static function getEloquentQuery(): Builder
    {
        $añoActual = now()->year;

        $añoAnterior2 = now()->subYears(2)->year;


        return parent::getEloquentQuery()
        ->select('ImportesPorOrganismo.*') // Selecciona todas las columnas de la tabla "obras"
            ->leftJoin('DatosInicioDeObras', 'DatosInicioDeObras.expediente_id', '=', 'ImportesPorOrganismo.expediente_id') // Join con la tabla "municipios"
            //->addSelect(trim('TablaDeMunicipios.nombre_municipio'))
           // ->WhereNotNull('carretera');  //->with('municipios');
            ->where('ImportesPorOrganismo.ao_ejecucion', '>=', $añoAnterior2)
            ->where('ImportesPorOrganismo.ao_ejecucion', '<=', $añoActual);
            //->where('codigo_municipio','=', )

    }
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
            TextInput::make('expediente_id')
                ->required()
                ->maxLength(255),
            TextInput::make('organismo')
                ->required()
                ->maxLength(255),
            TextInput::make('Porc_imp_aprobado')
                ->required()
                ->maxLength(255),
            TextInput::make('importe_aprobado')
                ->required()
                ->maxLength(255),
            TextInput::make('Porc_imp_contratar')
                ->required()
                ->maxLength(255),
            TextInput::make('Importe_a_contratar')
                ->required()
                ->maxLength(255),
            TextInput::make('Porc_imp_adjudicado')
                ->required()
                ->maxLength(255),
            TextInput::make('importe_adjudicacion')
                ->required()
                ->maxLength(255),
            TextInput::make('Porc_imp_baj')
                ->required()
                ->maxLength(255),
            TextInput::make('importe_baja_contratación')
                ->required()
                ->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('expediente_id'),
                TextColumn::make('organismo'),
                TextColumn::make('importe_aprobado'),
                TextColumn::make('Porc_imp_contratar'),
                TextColumn::make('Importe_a_contratar'),
                TextColumn::make('Porc_imp_adjudicado'),
                TextColumn::make('importe_adjudicacion'),
                TextColumn::make('Porc_imp_baj'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
              //      Tables\Actions\DeleteBulkAction::make(),
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
            'index' => ListImportesPorOrganismos::route('/'),
            'create' => CreateImportesPorOrganismo::route('/create'),
            'edit' => EditImportesPorOrganismo::route('/{record}/edit'),
        ];
    }
}

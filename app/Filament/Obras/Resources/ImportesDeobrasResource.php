<?php

namespace App\Filament\Obras\Resources;

use App\Filament\Obras\Resources\ImportesDeObrasResource\Pages;
use App\Filament\Obras\Resources\ImportesDeObrasResource\RelationManagers;
use App\Models\ImportesDeObras;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Facades\Filament;

class ImportesDeObrasResource extends Resource
{
    protected static ?string $model = ImportesDeObras::class;
    protected static ?string $tenantOwnershipRelationshipName = 'team';
    /**protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static bool $shouldRegisterNavigation = true;
    protected static ?string $navigationGroup="Gestión de Importes";
    protected static ?string $navigationLabel = 'Importes de Obras';**/
    public static function getLabel(): string
    {
        return 'Importes de Obras';
    }
    public static function getEloquentQuery(): Builder
    {
        $añoActual = now()->year;

        $añoAnterior2 = now()->subYear(2)->year;
        
        return parent::getEloquentQuery()
        ->with('obra')
        ->whereHas('obra', function ($query) {
            $query->where('team_id', Filament::getTenant()->id);
        })
        ->where('ao_ejecucion', '>=', $añoAnterior2)
        ->where('ao_ejecucion', '<=', $añoActual);
      
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            Forms\Components\TextInput::make('expediente_id')
                ->required()
                ->maxLength(255),
           
            Forms\Components\TextInput::make('Porc_imp_aprobado')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('importe_aprobado')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('Porc_imp_contratar')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('Importe_a_contratar')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('obra.expediente_id'),
                Tables\Columns\TextColumn::make('importe_aprobado'),
                Tables\Columns\TextColumn::make('importe_a_contratar'),
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
            'index' => Pages\ListImportesDeobras::route('/'),
            'create' => Pages\CreateImportesDeobras::route('/create'),
            'edit' => Pages\EditImportesDeobras::route('/{record}/edit'),
        ];
    }
}

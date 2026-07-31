<?php

namespace App\Filament\Obras\Resources\ImportesDeObras;

use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Obras\Resources\ImportesDeObras\Pages\ListImportesDeobras;
use App\Filament\Obras\Resources\ImportesDeObras\Pages\CreateImportesDeobras;
use App\Filament\Obras\Resources\ImportesDeObras\Pages\EditImportesDeobras;

use App\Models\ImportesDeObras;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Facades\Filament;

class ImportesDeObrasResource extends Resource
{
    protected static ?string $model = ImportesDeObras::class;
    protected static ?string $tenantOwnershipRelationshipName = 'team';
    /**protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static bool $shouldRegisterNavigation = true;
    protected static \UnitEnum|string|null $navigationGroup="Gestión de Importes";
    protected static ?string $navigationLabel = 'Importes de Obras';**/
    public static function getLabel(): string
    {
        return 'Importes de Obras';
    }
    public static function getEloquentQuery(): Builder
    {
        $tenantId = Filament::getTenant()?->id;

        $query = parent::getEloquentQuery();

        if ($tenantId !== null) {
            $query->where('team_id', $tenantId);
        }

        return $query;
    }
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            TextInput::make('expediente_id')
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('expediente_id')
                    ->label('Expediente')
                    ->searchable(),
                TextColumn::make('importe_aprobado'),
                TextColumn::make('importe_a_contratar'),
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
            'index' => ListImportesDeobras::route('/'),
            'create' => CreateImportesDeobras::route('/create'),
            'edit' => EditImportesDeobras::route('/{record}/edit'),
        ];
    }
}

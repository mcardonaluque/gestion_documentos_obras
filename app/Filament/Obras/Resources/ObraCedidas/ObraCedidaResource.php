<?php

namespace App\Filament\Obras\Resources\ObraCedidas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use App\Filament\Obras\Resources\ObraCedidas\Pages\ListObraCedidas;
use App\Filament\Obras\Resources\ObraCedidas\Pages\CreateObraCedida;
use App\Filament\Obras\Resources\ObraCedidas\Pages\EditObraCedida;
use App\Filament\Obras\Resources\ObraCedidaResource\Pages;
use App\Filament\Obras\Resources\Concerns\HasAssignedExpedienteVisibility;
use App\Filament\Support\DateFieldRules;
use App\Filament\Traits\CommonFilters;
use App\Filament\Traits\MunicipiosFilter;
use App\Filament\Traits\ZonasFilter;
use App\Models\ObraCedida;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;

/**
 * Recurso Filament para gestionar las obras cedidas a los ayuntamientos.
 *
 * Gestiona el modelo {@see \App\Models\ObraCedida}: fechas de la fase de
 * cesión (remisión al ayuntamiento, recepción de certificado, cesión,
 * adjudicación y contrato) y datos del contratista adjudicatario. Usa los
 * traits {@see CommonFilters}, {@see MunicipiosFilter}, {@see ZonasFilter}
 * y {@see HasAssignedExpedienteVisibility}, y aplica
 * {@see DateFieldRules} a los campos de fecha. Multi-tenant vía 'team'.
 */
class ObraCedidaResource extends Resource
{
    use CommonFilters;
    use MunicipiosFilter;
    use ZonasFilter;
    use HasAssignedExpedienteVisibility;

    protected static ?string $model = ObraCedida::class;
    protected static ?string $modelLabel = 'Obra Cedida';
    protected static ?string $pluralModelLabel = 'Obras Cedidas';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $tenantOwnershipRelationshipName = 'team';

    /**
     * Define el formulario de la obra cedida (fechas de cesión y datos del
     * contratista), validando las fechas con DateFieldRules.
     */
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(7)
            ->components([
                TextInput::make('Codigo_Plan')
                    ->required()
                    ->maxLength(7),
                TextInput::make('numero_obra')
                    ->required()
                    ->numeric(),
                TextInput::make('subreferencia')
                    ->required()
                    ->numeric(),
                TextInput::make('ao_ejecucion')
                    ->required()
                    ->numeric(),
                DateFieldRules::apply(DateTimePicker::make('FechaRemisionAyto')),
                DateFieldRules::apply(DateTimePicker::make('FechaRecepcionCerti')),
                DateFieldRules::apply(DateTimePicker::make('FechaCesion')),
                DateFieldRules::apply(DateTimePicker::make('FechaAdjudicacion')),
                TextInput::make('ImporteAdjudicacion_Pts')
                    ->numeric(),
                Section::make('Datos del Contratista')
                ->columnSpanFull()
                ->columns(7)
                ->schema([
                TextInput::make('NombreContratista')
                    ->maxLength(70),
                TextInput::make('DomicilioContratista')
                    ->maxLength(80),
                TextInput::make('CPostalContratista')
                    ->numeric(),
                TextInput::make('Ciudad')
                    ->maxLength(50),
                TextInput::make('CodMunContratista')
                    ->numeric(),
                TextInput::make('NIFContratista')
                    ->maxLength(15),
                ]),
                DateFieldRules::apply(DateTimePicker::make('FechaContrato')),
                DateFieldRules::apply(DateTimePicker::make('FechaRemisionInterv')),
                TextInput::make('ImporteAdjudicacion')
                    ->numeric(),
                TextInput::make('TelefContratista')
                    ->maxLength(50),
                Toggle::make('NuevaLey')
                    ->required(),
                Select::make('team_id')
                    ->relationship('team', 'name'),
            ]);
    }

    /**
     * Define la tabla de obras cedidas, ordenada por fecha de cesión
     * descendente y con filtros comunes, de municipio y de zona.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('FechaCesion', 'desc')
            ->columns([
                TextColumn::make('Codigo_Plan')
                    ->searchable(),
                TextColumn::make('numero_obra')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('subreferencia')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ao_ejecucion')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('FechaRemisionAyto')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('FechaRecepcionCerti')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('FechaCesion')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('FechaAdjudicacion')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('ImporteAdjudicacion_Pts')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('NombreContratista')
                    ->searchable(),
                TextColumn::make('DomicilioContratista')
                    ->searchable(),
                TextColumn::make('CPostalContratista')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('Ciudad')
                    ->searchable(),
                TextColumn::make('CodMunContratista')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('NIFContratista')
                    ->searchable(),
                TextColumn::make('FechaContrato')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('FechaRemisionInterv')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('ImporteAdjudicacion')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('TelefContratista')
                    ->searchable(),
                IconColumn::make('NuevaLey')
                    ->boolean(),
                TextColumn::make('Expediente')
                    ->searchable(),
                TextColumn::make('team.name')
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
                self::assignedExpedientesFilter(),
                ...self::getCommonFilters(),
                self::getMunicipioFromInicioObrasFilter(),
                self::getZonaFromInicioObrasFilter(),
            ], layout: FiltersLayout::AboveContent)
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),
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
            'index' => ListObraCedidas::route('/'),
            'create' => CreateObraCedida::route('/create'),
            'edit' => EditObraCedida::route('/{record}/edit'),
        ];
    }
}

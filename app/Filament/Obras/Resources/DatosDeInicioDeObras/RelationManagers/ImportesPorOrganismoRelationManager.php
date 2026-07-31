<?php

namespace App\Filament\Obras\Resources\DatosDeInicioDeObras\RelationManagers;

use App\Models\Expediente;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ImportesPorOrganismoRelationManager extends RelationManager
{
    protected static string $relationship = 'importesPorOrganismo';
    protected static ?string $title = 'Importes por Organismo';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return true;
    }

    protected function getInverseRelationship(): string
    {
        if ($this->getOwnerRecord() instanceof Expediente) {
            return 'expedientes';
        }

        return 'obra';
    }

    public function getTableRecordKey(Model | array $record): string
    {
        if (is_array($record)) {
            return (string) (($record['expediente_id'] ?? '') . '-' . ($record['organismo'] ?? ''));
        }

        return (string) ($record->expediente_id . '-' . $record->organismo);
    }

    public function form(Schema $schema): Schema
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

            ]);
    }

    public function table(Table $table): Table
    {

        return $table
            ->recordTitleAttribute('expediente_id')
            ->columns([
                TextColumn::make('organismo'),
                TextColumn::make('Porc_imp_aprobado'),
                TextColumn::make('importe_aprobado'),
                TextColumn::make('Porc_imp_contratar')
                    ->label('Porc_imp_contratar')
                    ->getStateUsing(fn (Model $record) => $record->Porc_imp_contratar ?? $record->Porc_importe_contratar),
                TextColumn::make('Importe_a_contratar')
                    ->label('Importe_a_contratar')
                    ->getStateUsing(fn (Model $record) => $record->Importe_a_contratar ?? $record->importe_a_contratar),
                TextColumn::make('Porc_imp_adjudicado'),
                TextColumn::make('importe_adjudicacion'),
                TextColumn::make('Porc_imp_baja')
                    ->getStateUsing(fn (Model $record) => $record->Porc_imp_baja ?? $record->Porc_imp_baj),
                TextColumn::make('importe_baja_contratacion'),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
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

<?php

namespace App\Filament\Resources\DateValidationRules;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\DateValidationRules\Pages\ListDateValidationRules;
use App\Filament\Resources\DateValidationRules\Pages\CreateDateValidationRule;
use App\Filament\Resources\DateValidationRules\Pages\EditDateValidationRule;
use App\Filament\Resources\DateValidationRuleResource\Pages;
use App\Filament\Resources\DateValidationRuleResource\RelationManagers;
use App\Models\DateValidationRule;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DateValidationRuleResource extends Resource
{
    protected static ?string $model = DateValidationRule::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
                TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->maxLength(65535),
                TextInput::make('modelo')
                    ->required()
                    ->placeholder('Ej: contracts'),
                TextInput::make('campo')
                    ->required()
                    ->placeholder('Ej: projects.start_date'),
                TextInput::make('modelo_relacionado')
                    ->required()
                    ->placeholder('Ej: contracts'),
                TextInput::make('campo_relacionado')
                    ->required()
                    ->placeholder('Ej: signing_date'),
                Select::make('validation_type')
                    ->options([
                        'after' => 'Después de',
                        'before' => 'Antes de',
                        'after_or_equal' => 'Después o igual a',
                        'before_or_equal' => 'Antes o igual a',
                        'beetween'  => 'Entre',
                    ])
                    ->required(),
                TextInput::make('periodo')
                    ->numeric()
                    ->nullable(),
                Toggle::make('activa')
                    ->default(true),
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('name'),
                TextColumn::make('field_to_validate'),
                TextColumn::make('validation_type'),
                IconColumn::make('active')
                    ->boolean(),
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
            'index' => ListDateValidationRules::route('/'),
            'create' => CreateDateValidationRule::route('/create'),
            'edit' => EditDateValidationRule::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DateValidationRuleResource\Pages;
use App\Filament\Resources\DateValidationRuleResource\RelationManagers;
use App\Models\DateValidationRule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DateValidationRuleResource extends Resource
{
    protected static ?string $model = DateValidationRule::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535),
                Forms\Components\TextInput::make('modelo')
                    ->required()
                    ->placeholder('Ej: contracts'),
                Forms\Components\TextInput::make('campo')
                    ->required()
                    ->placeholder('Ej: projects.start_date'),
                Forms\Components\TextInput::make('modelo_relacionado')
                    ->required()
                    ->placeholder('Ej: contracts'),
                Forms\Components\TextInput::make('campo_relacionado')
                    ->required()
                    ->placeholder('Ej: signing_date'),
                Forms\Components\Select::make('validation_type')
                    ->options([
                        'after' => 'Después de',
                        'before' => 'Antes de',
                        'after_or_equal' => 'Después o igual a',
                        'before_or_equal' => 'Antes o igual a',
                        'beetween'  => 'Entre',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('periodo')
                    ->numeric()
                    ->nullable(),
                Forms\Components\Toggle::make('activa')
                    ->default(true),
            ]);
            
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('field_to_validate'),
                Tables\Columns\TextColumn::make('validation_type'),
                Tables\Columns\IconColumn::make('active')
                    ->boolean(),
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
            'index' => Pages\ListDateValidationRules::route('/'),
            'create' => Pages\CreateDateValidationRule::route('/create'),
            'edit' => Pages\EditDateValidationRule::route('/{record}/edit'),
        ];
    }
}

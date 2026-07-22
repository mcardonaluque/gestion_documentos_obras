<?php

declare(strict_types=1);

namespace App\Filament\Resources\DateValidationRules;

use App\Enums\DateRuleAction;
use App\Enums\DateRuleCondition;
use App\Enums\DateRuleOperation;
use App\Enums\DateRuleType;
use App\Filament\Resources\DateValidationRules\Pages\CreateDateValidationRule;
use App\Filament\Resources\DateValidationRules\Pages\EditDateValidationRule;
use App\Filament\Resources\DateValidationRules\Pages\ListDateValidationRules;
use App\Models\DateValidationRule;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class DateValidationRuleResource extends Resource
{
    protected static ?string $model = DateValidationRule::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Validacion de fechas';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Reglas de fechas';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->label('Nombre de la regla')
                    ->required()
                    ->maxLength(160),
                Textarea::make('descripcion')
                    ->maxLength(500)
                    ->columnSpanFull(),
                TextInput::make('tabla1')
                    ->label('Tabla principal')
                    ->required()
                    ->placeholder('Ejemplo: Expedientes o * para todas')
                    ->helperText('Use * para aplicar la regla a cualquier tabla observada.')
                    ->maxLength(120),
                TextInput::make('campo1')
                    ->label('Campo fecha principal')
                    ->required()
                    ->placeholder('Ejemplo: fechaincorporacion o * para todos los campos fecha cambiados')
                    ->helperText('Use * para aplicar la regla a todos los campos fecha modificados en el guardado.')
                    ->maxLength(120),
                TextInput::make('tabla2')
                    ->label('Tabla comparacion')
                    ->placeholder('Ejemplo: Proyectos o today/now para fecha de sistema')
                    ->maxLength(120),
                TextInput::make('campo2')
                    ->label('Campo fecha comparacion')
                    ->placeholder('Ejemplo: fecha_recepcion_proyecto o today/now')
                    ->helperText('Puede usar today, @today, current_date, now, @now o current_timestamp como valor de sistema.')
                    ->maxLength(120),
                Select::make('condicion')
                    ->options(DateRuleCondition::options())
                    ->required(),
                TextInput::make('plazo_dias')
                    ->label('Plazo en dias')
                    ->numeric()
                    ->minValue(1),
                TextInput::make('aviso_dias')
                    ->label('Aviso cuando faltan dias')
                    ->numeric()
                    ->minValue(0),
                Select::make('tipo')
                    ->options(DateRuleType::options())
                    ->required(),
                Select::make('accion')
                    ->options(DateRuleAction::options())
                    ->required(),
                Select::make('operacion')
                    ->options(DateRuleOperation::options())
                    ->required(),
                TextInput::make('fase')
                    ->maxLength(50),
                TextInput::make('estado')
                    ->maxLength(20),
                Textarea::make('mensaje')
                    ->required()
                    ->rows(3)
                    ->helperText('Mensaje base usado cuando no exista un mensaje especifico por etapa.')
                    ->columnSpanFull(),
                Textarea::make('mensaje_preventivo')
                    ->label('Mensaje preventivo')
                    ->rows(3)
                    ->helperText('Se usa en notificaciones previas al vencimiento (preaviso).')
                    ->columnSpanFull(),
                Textarea::make('mensaje_cumplida')
                    ->label('Mensaje regla cumplida')
                    ->rows(3)
                    ->helperText('Se usa cuando la condicion de la regla se cumple.')
                    ->columnSpanFull(),
                Textarea::make('mensaje_incumplida')
                    ->label('Mensaje regla incumplida')
                    ->rows(3)
                    ->helperText('Se usa cuando la condicion de la regla no se cumple.')
                    ->columnSpanFull(),
                Toggle::make('dispara_si_cumple')
                    ->label('Disparar cuando la condicion SI se cumple')
                    ->default(false),
                Toggle::make('activa')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')->searchable()->sortable(),
                TextColumn::make('tabla1')->label('Tabla')->searchable(),
                TextColumn::make('campo1')->label('Campo')->searchable(),
                TextColumn::make('condicion')->badge(),
                TextColumn::make('tipo')->badge(),
                TextColumn::make('accion')->badge(),
                IconColumn::make('activa')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('tipo')
                    ->options(DateRuleType::options()),
                SelectFilter::make('activa')
                    ->options([
                        '1' => 'Activas',
                        '0' => 'Inactivas',
                    ]),
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
        return [];
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

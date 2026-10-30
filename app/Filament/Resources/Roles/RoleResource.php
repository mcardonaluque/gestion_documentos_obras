<?php

namespace App\Filament\Resources\Roles;

use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles;
use BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole;
use BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole;
use App\Filament\Resources\RoleResource\RelationManagers;
use App\Models\Role;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use BezhanSalleh\FilamentShield\Resources\Roles\Pages;
use BezhanSalleh\FilamentShield\Resources\Roles\RoleResource as BaseRoleResource;
use Filament\Facades\Filament;


/**
 * Recurso Filament para los roles de usuario (Filament Shield).
 *
 * Gestiona el modelo App\Models\Role extendiendo el RoleResource de
 * BezhanSalleh\FilamentShield. Solo se registra en la navegación del panel
 * de administración y usa la relación «members» como pertenencia al tenant.
 */
class RoleResource extends BaseRoleResource
{
    protected static ?string $model = Role::class;
    protected static ?string $tenantOwnershipRelationshipName = 'members';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    /** Muestra el recurso únicamente en el panel de administración. */
    public static function shouldRegisterNavigation(): bool
{
    return Filament::getCurrentOrDefaultPanel()?->getId() === 'admin'; // o el ID de tu panel de administración
}
    /** Define el esquema del formulario del recurso. */
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(510),
                TextInput::make('guard_name')
                    ->required()
                    ->maxLength(510),
                TextInput::make('team_id')
                    ->numeric(),
            ]);
    }

    /** Configura la tabla de listado del recurso. */
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id')
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('guard_name')
                    ->searchable(),
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

    /** Declara los relation managers asociados al recurso. */
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    /** Declara las páginas y rutas del recurso. */
    public static function getPages(): array
    {
        return [
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'edit' => EditRole::route('/{record}/edit'),
        ];
    }
    /** Devuelve la clase del modelo de rol configurada en spatie/permission. */
    public static function getModel(): string
    {
        return config('permission.models.role');
    }
}

<?php

namespace App\Filament\Resources\Permissions;

use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Permissions\Pages\ListPermissions;
use App\Filament\Resources\Permissions\Pages\CreatePermission;
use App\Filament\Resources\Permissions\Pages\EditPermission;
use App\Models\Permission;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

/**
 * Recurso Filament para los permisos de spatie/permission.
 *
 * Gestiona el modelo App\Models\Permission en el panel de administración.
 * Permanece oculto en la navegación (shouldRegisterNavigation() devuelve
 * false) y su formulario/tabla están sin definir: existe como soporte CRUD
 * mínimo para Filament Shield.
 */
class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;
    protected static ?string $tenantOwnershipRelationshipName = '';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    /** Oculta el recurso de la navegación del panel. */
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
    /** Define el esquema del formulario del recurso. */
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }

    /** Configura la tabla de listado del recurso. */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => ListPermissions::route('/'),
            'create' => CreatePermission::route('/create'),
            'edit' => EditPermission::route('/{record}/edit'),
        ];
    }
}

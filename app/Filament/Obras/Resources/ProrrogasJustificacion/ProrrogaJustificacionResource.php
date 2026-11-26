<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\ProrrogasJustificacion;

use App\Filament\Obras\Resources\Prorrogas\ProrrogaResource;
use App\Filament\Obras\Resources\ProrrogasJustificacion\Pages\EditProrrogaJustificacion;
use App\Filament\Obras\Resources\ProrrogasJustificacion\Pages\ListProrrogasJustificacion;
use App\Filament\Obras\Resources\ProrrogasJustificacion\RelationManagers\ProrrogasJustificacionRelationManager;

/**
 * Prórrogas del plazo de justificación (menú «Justificación»).
 *
 * Reutiliza el listado y la ficha de {@see ProrrogaResource} (obras con datos
 * de ejecución) y gestiona solo las prórrogas de tipo justificación en
 * {@see ProrrogasJustificacionRelationManager}.
 */
class ProrrogaJustificacionResource extends ProrrogaResource
{
    protected static ?string $slug = 'prorrogas-justificacion';

    protected static string | \UnitEnum | null $navigationGroup = 'Justificacion';

    protected static ?string $navigationLabel = 'Prórrogas de justificación';

    protected static ?string $modelLabel = 'Prórroga de justificación';

    protected static ?string $pluralModelLabel = 'Prórrogas de justificación';

    public static function getNavigationGroup(): string | \UnitEnum | null
    {
        return 'Justificacion';
    }

    public static function getRelations(): array
    {
        return [
            ProrrogasJustificacionRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProrrogasJustificacion::route('/'),
            'edit' => EditProrrogaJustificacion::route('/{record}/edit'),
        ];
    }
}

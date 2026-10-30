<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

/**
 * Policy que controla el acceso al informe agrupado de expedientes.
 *
 * No protege un modelo Eloquent sino la funcionalidad de informes:
 * la consultan `ImprimirInformeExpedientesController` (ruta
 * informes.expedientes.imprimir) y la página de Filament
 * `InformeExpedientesAgrupado` del panel Obras.
 */
final class InformeExpedientesPolicy
{
    /**
     * Permite ver el informe a super administradores o a quien tenga
     * el permiso `page_InformeExpedientesAgrupado` de Shield.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('page_InformeExpedientesAgrupado');
    }
}

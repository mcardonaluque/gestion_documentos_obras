<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

final class InformeExpedientesPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('page_InformeExpedientesAgrupado');
    }
}

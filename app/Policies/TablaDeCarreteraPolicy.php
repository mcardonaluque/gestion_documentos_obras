<?php

namespace App\Policies;

use App\Models\TablaDeCarretera;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * Policy que protege el modelo `TablaDeCarretera` (catálogo de carreteras).
 *
 * Todas las habilidades devuelven false: el catálogo es de solo lectura
 * interna (se consulta desde los formularios de obras) y no se expone
 * ningún CRUD en los paneles de Filament ni en rutas HTTP.
 */
class TablaDeCarreteraPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TablaDeCarretera $tablaDeCarretera): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TablaDeCarretera $tablaDeCarretera): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TablaDeCarretera $tablaDeCarretera): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TablaDeCarretera $tablaDeCarretera): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TablaDeCarretera $tablaDeCarretera): bool
    {
        return false;
    }
}

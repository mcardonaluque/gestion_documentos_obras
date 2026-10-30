<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ImportesDeObras;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy que protege el modelo `ImportesDeObras` (importes económicos de la obra).
 *
 * Generada por Filament Shield: cada habilidad delega en los permisos
 * `*_importes::deobras` de Spatie Permission. La resuelve automáticamente
 * Laravel para `ImportesDeObrasResource` en los paneles Obras y Planes.
 */
class ImportesDeObrasPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_importes::deobras');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ImportesDeObras $importesDeobras): bool
    {
        return $user->can('view_importes::deobras');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_importes::deobras');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ImportesDeObras $importesDeobras): bool
    {
        return $user->can('update_importes::deobras');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ImportesDeObras $importesDeobras): bool
    {
        return $user->can('delete_importes::deobras');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_importes::deobras');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, ImportesDeObras $importesDeobras): bool
    {
        return $user->can('force_delete_importes::deobras');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_importes::deobras');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, ImportesDeObras $importesDeobras): bool
    {
        return $user->can('restore_importes::deobras');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_importes::deobras');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, ImportesDeObras $importesDeobras): bool
    {
        return $user->can('replicate_importes::deobras');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_importes::deobras');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Role as SpatieRole;

/**
 * Rol de acceso basado en Spatie Permission.
 *
 * Extiende el modelo de roles de Spatie añadiendo las relaciones con los
 * usuarios miembros y el Team (tenant) al que pertenece el rol.
 */
class Role extends SpatieRole
{
    //
    /** Usuarios que tienen asignado este rol. */
    public function members():BelongsToMany{
        return $this->belongsToMany(User::class);
    }
     /** Team (tenant) al que pertenece el rol. */
     public function team()
    {
        return $this->belongsTo(Team::class);
    }
}

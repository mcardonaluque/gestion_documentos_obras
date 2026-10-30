<?php

namespace App\Models;

use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * Usuario interno (empleado de la diputación) autenticable.
 *
 * Variante de usuario asociada a un departamento (TablaDeDepartamento) que
 * implementa FilamentUser y HasTenants usando el departamento como tenant.
 * Puede actuar como firmante mediante relación polimórfica con User.
 */
class Usuario extends Authenticatable implements FilamentUser , HasTenants
{
    use HasFactory;
    
    /** Relación polimórfica con el usuario de aplicación como firmante. */
    public function usuarios_firmantes(){
        return $this->morphOne(User::class,'userable');
    }
    /** Departamento de la diputación al que pertenece el usuario. */
    public function departamento(){
        return $this->belongsTo(TablaDeDepartamento::class, 'departamento','CODIGO_DPTO');
    }    
    /** Devuelve los tenants accesibles: el departamento del usuario. */
    public function getTenants(Panel $panel): array|Collection
    {
        return $this->departamento()->get();
    }
    /** Indica si el usuario puede acceder al tenant indicado. */
    public function canAccessTenant(Model $tenant): bool
    {
        return $this->team()->whereKey($tenant)->exists();
    }
    /** Determina a qué paneles de Filament puede acceder el usuario. */
    public function canAccessPanel(Panel $panel): bool
    {
        //dd($this->hasRole('panel_user'));
        if ($panel->getId() === 'admin') {
            return $this->hasRole('super_admin');
        }
        if ($panel->getId() === 'obras') {
            //dd($panel->getID());
            return true;
        }
       return false;
    }
}

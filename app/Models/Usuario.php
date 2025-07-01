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

class Usuario extends Authenticatable implements FilamentUser , HasTenants
{
    use HasFactory;
    
    public function usuarios_firmantes(){
        return $this->morphOne(User::class,'userable');
    }
    public function departamento(){
        return $this->belongsTo(TablaDeDepartamento::class, 'departamento','CODIGO_DPTO');
    }    
    public function getTenants(Panel $panel): array|Collection
    {
        return $this->departamento;
    }
    public function canAccessTenant(Model $tenant): bool
    {
        return $this->team()->whereKey($tenant)->exists();
    }
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

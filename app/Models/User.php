<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
//use \BezhanSalleh\FilamentShield\Traits\HasPanelShield;

class User extends Authenticatable implements FilamentUser , HasTenants
{
    use HasFactory, Notifiable, HasRoles, HasPanelShield, HasPermissions;
    protected $connection = 'Obras';

    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function team(): BelongsToMany
    {
        return $this->belongsToMany(Team::class,'team_user','user_id', 'team_id');
    }
 
    public function getTenants(Panel $panel): array|Collection
    {
        return $this->team;
    }
    public function departamento():BelongsTo
    {
        return $this->belongsTo(TablaDeDepartamento::class);
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
        if ($panel->getId() === 'planes') {
            //dd($panel->getID());
            return true;
        }
        if ($panel->getId() === 'ayuntamientos') {
            //dd($panel->getID());
            return true;
        }
       return false;
    }
    protected static function booted(): void
    {
        static::created(function (User $user) {
            // ...
        });
    }
   
}

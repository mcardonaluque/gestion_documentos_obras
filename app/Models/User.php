<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\FilamentUser;
use Filament\Facades\Filament;
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
use Illuminate\Database\Eloquent\Relations\MorphMany;

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
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim($value);
    }


    public function getTenants(Panel $panel): array|Collection
    {
        if ($this->hasGlobalAyuntamientosAccess($panel)) {
            return Team::query()->orderBy('name')->get();
        }

        return $this->team;
    }
    public function departamento():BelongsTo
    {
        return $this->belongsTo(TablaDeDepartamento::class);
    }
    public function canAccessTenant(Model $tenant): bool
    {
        if ($this->hasGlobalAyuntamientosAccess()) {
            return true;
        }

        return $this->team()->whereKey($tenant)->exists();
    }

    /**
     * Excepción controlada para el portal de Ayuntamientos.
     */
    public function hasGlobalAyuntamientosAccess(?Panel $panel = null): bool
    {
        $panelId = $panel?->getId() ?? Filament::getCurrentPanel()?->getId();

        if ($panelId !== 'ayuntamientos') {
            return false;
        }

        return (int) $this->id === 2 || $this->hasRole('super_admin');
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
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_user', 'user_id', 'team_id');
    }
    public function team(): BelongsToMany
    {
        return $this->teams();
    }

    public function notifications(): MorphMany
    {
        return $this->morphMany(CustomNotification::class, 'notifiable')->orderBy('created_at', 'desc');
    }

    /**
     * @return BelongsToMany<Expediente, self>
     */
    public function assignedExpedientes(): BelongsToMany
    {
        return $this->belongsToMany(Expediente::class, 'expediente_user_assignments', 'user_id', 'expediente_id', 'id', 'expediente_id')
            ->withPivot(['assigned_by', 'team_id'])
            ->withTimestamps();
    }

    public function unreadNotifications(): MorphMany
    {
        return $this->morphMany(CustomNotification::class, 'notifiable')
                    ->whereNull('read_at')
                    ->orderBy('created_at', 'desc');
    }



}

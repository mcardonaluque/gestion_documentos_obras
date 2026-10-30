<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;

//use \BezhanSalleh\FilamentShield\Traits\HasPanelShield;

/**
 * Usuario de la aplicación con autenticación, roles y multi-tenancy.
 *
 * Integra Filament (FilamentUser, HasTenants), Spatie Permission y las
 * notificaciones personalizadas. Usa la conexión 'Obras' (SQL Server).
 * Se relaciona con Team (belongsToMany vía team_user), Expediente
 * (asignaciones vía expediente_user_assignments), TablaDeDepartamento
 * y CustomNotification (morphMany).
 */
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
    /** Mutator: guarda el nombre del usuario sin espacios sobrantes. */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim($value);
    }


    /** Devuelve los tenants (teams) accesibles por el usuario en el panel. */
    public function getTenants(Panel $panel): array|Collection
    {
        if ($this->hasGlobalAyuntamientosAccess($panel)) {
            return Team::query()->orderBy('name')->get();
        }

        return $this->team;
    }
    /** Departamento de la diputación al que pertenece el usuario. */
    public function departamento():BelongsTo
    {
        return $this->belongsTo(TablaDeDepartamento::class);
    }
    /** Indica si el usuario puede acceder al tenant (team) indicado. */
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
    /** Teams (ayuntamientos/ámbitos) a los que pertenece el usuario. */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_user', 'user_id', 'team_id');
    }
    /** Alias de teams(). */
    public function team(): BelongsToMany
    {
        return $this->teams();
    }

    /** Notificaciones del usuario, ordenadas de más reciente a más antigua. */
    public function notifications(): MorphMany
    {
        return $this->morphMany(CustomNotification::class, 'notifiable')->orderBy('created_at', 'desc');
    }

    /**
     * Expedientes asignados expresamente al usuario para su tramitación.
     *
     * @return BelongsToMany<Expediente, self>
     */
    public function assignedExpedientes(): BelongsToMany
    {
        return $this->belongsToMany(Expediente::class, 'expediente_user_assignments', 'user_id', 'expediente_id', 'id', 'expediente_id')
            ->withPivot(['assigned_by', 'team_id'])
            ->withTimestamps();
    }

    /** Notificaciones no leídas del usuario, de más reciente a más antigua. */
    public function unreadNotifications(): MorphMany
    {
        return $this->morphMany(CustomNotification::class, 'notifiable')
                    ->whereNull('read_at')
                    ->orderBy('created_at', 'desc');
    }



}

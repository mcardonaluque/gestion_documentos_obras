<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Team o tenant del sistema multi-tenant de Filament.
 *
 * Representa el ámbito organizativo (ayuntamiento o unidad) que agrupa
 * usuarios, expedientes, obras, importes, alertas y proyectos. Usa la
 * conexión 'Obras' (SQL Server) y la clave de ruta es el campo 'name'.
 */
class Team extends Model
{
    //
    protected $connection='Obras';
    protected $fillable=['name','slug','domain'];
    /** Mutator: guarda el nombre del team sin espacios al inicio y al final. */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim($value); // Elimina espacios al inicio y final
    }
    /** Usuarios miembros del team (tabla pivote team_user). */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_user', 'team_id', 'user_id');
    }
      
   //public function roles(): HasMany
   // {
   //     return $this->hasMany(Role::class);
   // }

    /** Fichas de inicio de obra pertenecientes al team. */
    public function InicioObras(): HasMany
    {
        return $this->hasMany(DatosDeInicioDeObras::class);
    }

    /** Datos de ejecución de obras del team. */
    public function datosejecucion():HasMany
    {
        return $this->hasMany(DatosEjecucionObras::class);
    }
    /** Expedientes administrativos del team. */
    public function expedientes(): HasMany
    {
        return $this->hasMany(Expediente::class);
    }
    /** Importes por organismo registrados por el team. */
    public function importesdeorganismos(): HasMany
    {
        return $this->hasMany(ImportesPorOrganismo::class);
    }
    /** Importes globales de obras del team. */
    public function importesdeobras(): HasMany
    {
        return $this->hasMany(ImportesDeObras::class);
    }
    /** Alertas configuradas por el team. */
    public function alertas()
    {
        return $this->hasMany(Alerta::class);
    }
    /** Proyectos técnicos del team. */
    public function proyectos()
    {
        return $this->hasMany(Proyecto::class);
    }
    /** La clave de ruta pública del team es su nombre. */
    public function getRouteKeyName(): string { return 'name'; 
    }
    /** Notificaciones polimórficas dirigidas al team. */
    public function notifications()
    {
        return $this->morphMany(CustomNotification::class, 'notifiable');
    }
    /** Usuarios del team (alias de members, tabla pivote team_user). */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_user', 'team_id', 'user_id');
    }
   
}

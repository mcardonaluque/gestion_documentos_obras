<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    //
    protected $connection='Obras';
    protected $fillable=['name','slug','domain'];
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim($value); // Elimina espacios al inicio y final
    }
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_user', 'team_id', 'user_id');
    }
      
   //public function roles(): HasMany
   // {
   //     return $this->hasMany(Role::class);
   // }

    public function InicioObras(): HasMany
    {
        return $this->hasMany(\App\Models\DatosDeInicioDeObras::class);
    }

    public function datosejecucion():HasMany
    {
        return $this->hasMany(DatosEjecucionObras::class);
    }
    public function expedientes(): HasMany
    {
        return $this->hasMany(\App\Models\Expediente::class);
    }
    public function importesdeorganismos(): HasMany
    {
        return $this->hasMany(ImportesPorOrganismo::class);
    }
    public function importesdeobras(): HasMany
    {
        return $this->hasMany(ImportesDeObras::class);
    }
    public function alertas()
    {
        return $this->hasMany(Alerta::class);
    }
    public function proyectos()
    {
        return $this->hasMany(Proyecto::class);
    }
    public function getRouteKeyName(): string { return 'name'; 
    }
    public function notifications()
    {
        return $this->morphMany(CustomNotification::class, 'notifiable');
    }
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_user', 'team_id', 'user_id');
    }
   
}

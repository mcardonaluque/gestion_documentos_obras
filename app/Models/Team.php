<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    //
    protected $connection='Obras';
    protected $fillable=['name','slug','domain'];
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class,'team_user','team_id', 'user_id');
    }
      
   //public function roles(): HasMany
   // {
   //     return $this->hasMany(Role::class);
   // }

   
    public function InicioObras(): HasMany
    {
        return $this->hasMany(\App\Models\DatosDeInicioDeObras::class);
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
   /* public function alertas()
    {
        return $this->hasMany(Alerta::class);
    }*/
}

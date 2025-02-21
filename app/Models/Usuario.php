<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;
    
    public function usuarios_firmantes(){
        return $this->morphOne(User::class,'userable');
    }
    public function departamento(){
        return $this->belongsTo(TablaDeDepartamento::class);
    }    

}

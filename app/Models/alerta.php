<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class alerta extends Model
{
    //
    protected $connection='Tablas';
    
    protected $foreingKey = ['fase','estado'];
    public function estadoRelacionado()
{
    return $this->belongsTo(TablaDeEstados::class, 'estado', 'cod_estado');
}

public function faseRelacionada()
{
    return $this->belongsTo(FaseDocumento::class, 'fase', 'cod_fase');
}
public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ImportesDeObras extends Model
{
    
    use HasFactory;
    protected $connection='Obras';
    protected $table='ImportesDeObras';
    protected $primaryKey='Expediente';
    protected $casts = [
        'Expediente' => 'string',
    ];
    public function obra(): BelongsTo
    {
        return $this->belongsTo(DatosDeInicioDeObras::class, 'Expediente','Expediente');
    }
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}

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
    public function obra()
    {
        return $this->belongsTo(DatosDeInicioDeObras::class, 'Expediente','Expediente');
    }
    public function teams(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}

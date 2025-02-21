<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ImportesPorOrganismo extends Model
{
    
    use HasFactory;
    protected $connection='Obras';
    protected $table='ImportesPorOrganismo';
    public function obra()
    {
        return $this->belongsTo(DatosDeInicioDeObras::class, ['codigo_plan', 'numero_obra', 'ao_ejecucion'], ['codigo_plan', 'numero_obra', 'ao_ejecucion']);
    }
    public function teams(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ImportesDeObras extends Model
{
    /** @use HasFactory<\Database\Factories\ImportesDeObrasFactory> */
    use HasFactory;
    protected $connection='Obras';
    protected $table='ImportesDeObras';
    protected $primaryKey='n_exp';
    public function obra()
    {
        return $this->belongsTo(DatosDeInicioDeObras::class, ['codigo_plan', 'numero_obra', 'ao_ejecucion'], ['codigo_plan', 'numero_obra', 'ao_ejecucion']);
    }
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class);
    }
}

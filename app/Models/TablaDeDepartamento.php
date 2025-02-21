<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TablaDeDepartamento extends Model
{
    use HasFactory;
    protected $connection='Tablas';
    protected $table='TablaDeDepartamentos';
    protected $primaryKey='CODIGO_DPTO';
    public function teams(): BelongsTo 
    {
        return $this->belongsTo(Team::class);
    }
}

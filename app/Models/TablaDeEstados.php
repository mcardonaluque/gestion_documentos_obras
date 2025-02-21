<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TablaDeEstados extends Model
{
    use HasFactory;
    protected $connection='Tablas';
    protected $table='TablaDeEstados';
    protected $primaryKey='cod_estado';
    protected $keyType = 'string';
    public $incrementing = false;
    public function teams(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}

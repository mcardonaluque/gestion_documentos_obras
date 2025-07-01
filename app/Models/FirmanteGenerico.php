<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FirmanteGenerico extends Model
{
    protected $connection='Obras';
    protected $table='fiemante_generico';
    protected $primaryKey='id';
    public $incrementing = true;
    public $timestamps = false;
    protected $keyType = 'string';
    use HasFactory;

    public function user(){
        return $this->belongsTo(Usuario::class);
    }
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}

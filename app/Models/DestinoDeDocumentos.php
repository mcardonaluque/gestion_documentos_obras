<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DestinoDeDocumentos extends Model
{
    //
    protected $connection='Tablas';
    protected $table='DestinosDeDocumentos';
    protected $primaryKey='id';
    use HasFactory;
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class);
    }
}

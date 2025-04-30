<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DestinoDeDocumentos extends Model
{
    //
    protected $connection='Obras';
    protected $table='DestinosDeDocumentos';
    protected $primaryKey='id';
    use HasFactory;
    public function docuemntosgenericos()
    {
        return $this->hasMany(DocumentoGenerico::class);
    }
}

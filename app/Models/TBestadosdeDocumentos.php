<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TBestadosdeDocumentos extends Model
{
    //
    use HasFactory;
    protected $connection='Obras';
   protected $table='TBestadodeDocumentos';
   protected $primaryKey='id';  
   
   public $incrementing = true;

   public function documentos(){
    return $this->hasMany (DocumentoExpediente::class,'id','estado');
}
}


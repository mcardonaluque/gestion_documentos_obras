<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use function GuzzleHttp\describe_type;
use function Psy\debug;

class DocumentoExpediente extends Model
{
    use HasFactory;
    protected $connection='Obras';
    //protected $table='documentacionexpediente';
    protected $table='dbo.documentacionexpedientes';
    protected $primaryKey='idDocumento';
    protected $foreignKey='expediente_id';
    protected $keyType = 'string';
    protected $fillable = [
        'cod_plan',
        'referencia',
        'subreferencia',
        'ao_ejecucion',
        'fechaincorporacion',
        'fechaHelp',
        'cod_documento',
        'expediente_id',
        'csv',
        'nregistro',
        'nsecuencia',
        'estado',
        'descripcion',
        'team_id',
        'destino',
        'procedencia',
        'destino',
        'remitidopor',
        'notificado'
    ];
    public function expedientes(): BelongsTo{
        return $this->belongsTo(Expediente::class,'expediente_id','expediente_id',);
    }
    public function obra(): BelongsTo{
        return $this->belongsTo(DatosDeInicioDeObras::class,'expediente_id','expediente_id',);
    }
    public function tipodocumentos(): BelongsTo{
        return $this->belongsTo(DocumentoGenerico::class,'cod_documento','id');
    }
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
    public function destinos(): BelongsTo
    {
        return $this->belongsTo(DestinoDeDocumentos::class, 'destino', 'id');
    }
    public function procedencias(): BelongsTo
    {
        return $this->belongsTo(DestinoDeDocumentos::class, 'procedencia', 'id');
    }
    public function estados(): BelongsTo{
        return $this->belongsTo(TBestadosdeDocumentos::class,'estado','id');
    }
}    

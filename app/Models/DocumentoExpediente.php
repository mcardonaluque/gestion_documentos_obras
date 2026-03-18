<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'Codigo_Plan',
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
    public function planes(): BelongsTo{
        return $this->belongsTo(Planes::class, 'Codigo_Plan', 'codigo_plan');
    }

    public static function nextSequenceForExpediente(?string $expedienteId): int
    {
        if (blank($expedienteId)) {
            return 1;
        }

        $lastSequence = static::query()
            ->where('expediente_id', $expedienteId)
            ->max('nsecuencia');

        return ((int) ($lastSequence ?? 0)) + 1;
    }

    public static function applyExpedienteDefaults(array $data): array
    {
        $expedienteId = $data['expediente_id'] ?? null;

        if (blank($expedienteId)) {
            return $data;
        }

        $expediente = Expediente::query()
            ->where('expediente_id', $expedienteId)
            ->first();

        if (! $expediente) {
            return $data;
        }

        $data['Codigo_Plan'] = $expediente->codigo_plan ?? $expediente->Codigo_Plan ?? $data['Codigo_Plan'] ?? null;
        $data['referencia'] = $expediente->referencia ?? $data['referencia'] ?? null;
        $data['subreferencia'] = $expediente->subreferencia ?? $data['subreferencia'] ?? null;
        $data['ao_ejecucion'] = $expediente->ao_ejecucion ?? $data['ao_ejecucion'] ?? null;
        $data['nsecuencia'] = static::nextSequenceForExpediente((string) $expedienteId);
        $data['team_id'] = $data['team_id'] ?? $expediente->team_id ?? null;

        return $data;
    }
}

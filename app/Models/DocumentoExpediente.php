<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

use function GuzzleHttp\describe_type;
use function Psy\debug;

/**
 * Modelo que representa un documento concreto incorporado a un expediente.
 *
 * Contiene tanto metadatos de registro como referencias al catálogo de
 * documentos genéricos y a la obra o expediente al que pertenece.
 */
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
        'archivo',
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

    /**
     * Devuelve la ruta o URL utilizable del PDF asociado al documento.
     */
    public function getPdfSourceAttribute(): ?string
    {
        $value = trim((string) ($this->archivo ?? $this->csv ?? ''));

        return $value !== '' ? $value : null;
    }

    /**
     * Indica si el documento dispone de un origen PDF informado.
     */
    public function hasPdfSource(): bool
    {
        $source = $this->pdf_source;

        return filled($source) && (Str::endsWith(Str::lower($source), '.pdf') || filter_var($source, FILTER_VALIDATE_URL));
    }

    /**
     * URL interna de previsualización del documento, si existe registro persistido.
     */
    public function getPdfPreviewUrlAttribute(): ?string
    {
        if (! $this->exists || ! $this->getKey() || ! $this->pdf_source) {
            return null;
        }

        return route('documentos.pdf.preview', ['documento' => $this->getKey()]);
    }

    /**
     * URL interna de descarga del PDF, si existe registro persistido.
     */
    public function getPdfDownloadUrlAttribute(): ?string
    {
        if (! $this->exists || ! $this->getKey() || ! $this->pdf_source) {
            return null;
        }

        return route('documentos.pdf.download', ['documento' => $this->getKey()]);
    }

    /**
     * Indica si el origen del documento es una URL externa válida.
     */
    public function isPdfUrl(): bool
    {
        return filled($this->pdf_source) && filter_var($this->pdf_source, FILTER_VALIDATE_URL);
    }

    /**
     * Resuelve la ruta física del PDF si está disponible en el servidor.
     */
    public function resolvePdfPath(): ?string
    {
        $path = $this->pdf_source;

        if (blank($path) || $this->isPdfUrl()) {
            return null;
        }

        $path = trim((string) $path, " \t\n\r\0\x0B\"'");

        $candidates = [];

        if ((bool) preg_match('/^[A-Za-z]:[\\\\\/]/', $path) || str_starts_with($path, '\\\\')) {
            $candidates[] = $path;
        }

        $relativePath = ltrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path), DIRECTORY_SEPARATOR);

        $candidates = array_merge($candidates, [
            $path,
            base_path($relativePath),
            public_path($relativePath),
            storage_path('app' . DIRECTORY_SEPARATOR . $relativePath),
            storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $relativePath),
        ]);

        foreach (array_unique($candidates) as $candidate) {
            if (is_string($candidate) && is_file($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    /**
     * Calcula la siguiente secuencia documental disponible para un expediente.
     */
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

    /**
     * Rellena automáticamente los metadatos documentales a partir del expediente indicado.
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
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

<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Integrante de una cadena de validación o firma asociada a un documento.
 */
class FirmanteDocumento extends Model
{
    public const TIPO_VALIDACION = 'V';

    public const TIPO_FIRMA = 'F';

    protected $connection = 'Obras';

    protected $table = 'firmantes_documentos';

    protected $fillable = [
        'id_usuario',
        'expediente_id',
        'documento_id',
        'id_rol',
        'tipo_firma',
        'orden',
        'fecha_envio_firma',
        'fecha_firma',
    ];

    protected function casts(): array
    {
        return [
            'orden' => 'integer',
            'fecha_envio_firma' => 'datetime',
            'fecha_firma' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $firmante): void {
            $firmante->id_usuario = trim((string) $firmante->id_usuario);

            if (filled($firmante->expediente_id) || blank($firmante->documento_id)) {
                return;
            }

            $firmante->expediente_id = DocumentoExpediente::query()
                ->whereKey($firmante->documento_id)
                ->value('expediente_id');
        });
    }

    /** Usuario de la aplicación asociado al identificador de tramitador. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    /** Documento de expediente objeto de la cadena de firma. */
    public function documento(): BelongsTo
    {
        return $this->belongsTo(DocumentoExpediente::class, 'documento_id', 'idDocumento');
    }
}

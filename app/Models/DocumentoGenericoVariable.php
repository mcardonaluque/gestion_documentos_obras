<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TemplateVariableSourceType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo que representa una variable configurable asociada a una plantilla documental.
 *
 * Cada registro describe qué marcador WD_* existe en la plantilla y de dónde
 * debe obtenerse su valor durante la generación del documento final.
 *
 * @property int $id
 * @property int $documento_generico_id
 * @property string $variable
 * @property string $source_type
 * @property string|null $source_path
 * @property string|null $default_value
 * @property string|null $format
 * @property bool $is_required
 * @property bool $is_active
 */
class DocumentoGenericoVariable extends Model
{
    use HasFactory;

    protected $connection = 'Obras';

    protected $table = 'documento_generico_variables';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'documento_generico_id',
        'variable',
        'source_type',
        'source_path',
        'default_value',
        'format',
        'is_required',
        'is_active',
        'last_detected_at',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'last_detected_at' => 'datetime',
    ];

    public function documentoGenerico(): BelongsTo
    {
        return $this->belongsTo(DocumentoGenerico::class, 'documento_generico_id', 'id');
    }

    public function sourceType(): TemplateVariableSourceType
    {
        return TemplateVariableSourceType::from($this->source_type);
    }
}

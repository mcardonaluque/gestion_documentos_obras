<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FechaHitoCatalogo extends Model
{
    use HasFactory;

    protected $connection = 'Obras';

    protected $table = 'fecha_hitos_catalogo';

    protected $dateFormat = 'Ymd H:i:s';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'codigo_hito',
        'descripcion',
        'tabla_origen',
        'campo_origen',
        'fase',
        'obligatorio',
        'repetible',
        'activa',
        'orden',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'obligatorio' => 'boolean',
            'repetible' => 'boolean',
            'activa' => 'boolean',
            'orden' => 'integer',
        ];
    }
}

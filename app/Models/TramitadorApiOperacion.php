<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TramitadorApiOperacion extends Model
{
    use HasFactory;

    protected $connection = 'Obras';

    protected $table = 'tramitador_api_operaciones';

    protected $dateFormat = 'Ymd H:i:s';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'metodo_http',
        'ruta',
        'tipo_contenido',
        'timeout_segundos',
        'activa',
    ];

    protected function casts(): array
    {
        return [
            'timeout_segundos' => 'integer',
            'activa' => 'boolean',
        ];
    }

    public function parametros(): HasMany
    {
        return $this->hasMany(TramitadorApiParametro::class, 'operacion_id');
    }
}

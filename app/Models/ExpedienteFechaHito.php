<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpedienteFechaHito extends Model
{
    use HasFactory;

    protected $connection = 'Obras';

    protected $table = 'expediente_fecha_hitos';

    protected $dateFormat = 'Ymd H:i:s';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'expediente_id',
        'codigo_hito',
        'fecha',
        'tabla_origen',
        'campo_origen',
        'source_record_id',
        'team_id',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fecha' => 'datetime',
            'team_id' => 'integer',
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportesDeObras extends Model
{

    use HasFactory;
    protected $connection='Obras';
    protected $table='ImportesDeObras';
    protected $primaryKey='expediente_id';
    protected $casts = [
        'Expediente' => 'string',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('exclude_invalid_expediente', function (Builder $builder): void {
            $builder->whereNotNull('expediente_id');
        });
    }

    public function obra(): BelongsTo
    {
        $expedienteId = $this->expediente_id;

        if (blank($expedienteId) || (string) $expedienteId === '0') {
            return $this->belongsTo(DatosDeInicioDeObras::class, 'expediente_id', 'expediente_id')
                ->whereRaw('1 = 0');
        }

        return $this->belongsTo(DatosDeInicioDeObras::class, 'expediente_id', 'expediente_id');
    }
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}

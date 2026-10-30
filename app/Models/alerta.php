<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Configuración de alertas del sistema por fase y estado de tramitación.
 *
 * Define qué avisos deben dispararse según la fase documental y el estado
 * del expediente. Usa la conexión 'Obras' (SQL Server) con clave primaria
 * autoincremental 'id'. Se relaciona con TablaDeEstados, FaseDocumento y Team.
 */
class alerta extends Model
{
    //
    protected $connection='Obras';
    
    public $incrementing = true;
    protected $primaryKey='id';
    protected $foreignKey = 'TipoAviso';
    protected $foreingKey = ['fase','estado'];
   /** Estado de tramitación al que aplica la alerta. */
   public function estadoRelacionado()
    {
        return $this->belongsTo(TablaDeEstados::class, 'estado', 'cod_estado');
    }

    /** Fase documental a la que aplica la alerta. */
    public function faseRelacionada()
    {
        return $this->belongsTo(FaseDocumento::class, 'fase', 'cod_fase');
    }
/** Team (ayuntamiento/ámbito) propietario de la alerta. */
public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}

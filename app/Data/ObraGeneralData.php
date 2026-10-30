<?php
namespace App\Data;

//use Spatie\LaravelData\Data;

/**
 * DTO simple con los datos generales de una obra.
 *
 * Mapea los campos básicos (código, nombre, fechas, presupuesto) desde un
 * modelo de obra mediante fromObra(). Actualmente sin consumidores directos
 * registrados en app/ (clase auxiliar/legado).
 */
class ObraGeneralData 
{
    public function __construct(
        public ?string $codigo_obra,
        public ?string $nombre_obra,
        public ?string $descripcion,
        public ?string $tipo_obra,
        public ?string $cliente,
        public ?string $fecha_inicio,
        public ?string $fecha_fin_prevista,
        public ?float $presupuesto,
    ) {}
    
    /**
     * Crea el DTO a partir de un modelo de obra con los atributos esperados.
     */
    public static function fromObra($obra): self
    {
        return new self(
            $obra->codigo,
            $obra->nombre,
            $obra->descripcion,
            $obra->tipo,
            $obra->cliente,
            $obra->fecha_inicio,
            $obra->fecha_fin_prevista,
            $obra->presupuesto
        );
    }
}
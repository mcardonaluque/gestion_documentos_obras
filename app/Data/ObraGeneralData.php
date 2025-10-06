<?php
namespace App\Data;

//use Spatie\LaravelData\Data;

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
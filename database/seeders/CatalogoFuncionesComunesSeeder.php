<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class CatalogoFuncionesComunesSeeder extends Seeder
{
    public function run(): void
    {
        $funciones = [
            '1' => 'Pleno',
            '2' => 'Alcaldía-Presidencia',
            '3' => 'Junta Local de Gobierno',
            '4' => 'Comisiones informativas y especiales',
            '5' => 'Consejos sectoriales',
            '6' => 'Juntas municipales de distrito',
            '7' => 'Cargos públicos',
            '8' => 'Normas municipales',
            '9' => 'Convenios administrativos',
            '10' => 'Territorio',
            '11' => 'Protocolo',
            '12' => 'Patrimonio local',
            '13' => 'Padrón de habitantes',
            '14' => 'Elecciones',
            '15' => 'Contratación',
            '16' => 'Tecnologías de la información',
            '17' => 'Registro General',
            '18' => 'Archivo y gestión documental',
            '19' => 'Protección de datos',
            '20' => 'Prensa y comunicación',
            '21' => 'Transparencia',
            '22' => 'Participación',
            '23' => 'Calidad',
            '24' => 'Procedimientos judiciales',
            '25' => 'Personal',
            '26' => 'Subvenciones',
            '27' => 'Presupuesto',
            '28' => 'Fiscalización',
            '29' => 'Financiación',
            '30' => 'Recaudación',
            '31' => 'Tesorería',
            '32' => 'Seguridad ciudadana',
            '33' => 'Movilidad',
            '34' => 'Medio ambiente',
            '35' => 'Animales peligrosos',
            '36' => 'Mantenimiento urbano',
            '37' => 'Urbanismo',
            '38' => 'Industria y Comercio',
            '39' => 'Servicios sociales',
            '40' => 'Igualdad de género',
            '41' => 'Sanidad',
            '42' => 'Plagas',
            '43' => 'Cementerios',
            '44' => 'Educación',
            '45' => 'Cultura',
            '46' => 'Fiestas',
            '47' => 'Patrimonio histórico',
            '48' => 'Deportes',
            '49' => 'Personas mayores',
            '50' => 'Juventud',
            '51' => 'Infancia',
            '52' => 'Agricultura y ganadería',
            '53' => 'Pesca',
            '54' => 'Montes',
            '55' => 'Fomento de la actividad económica',
            '56' => 'Promoción del empleo',
            '57' => 'Turismo',
            '58' => 'Consumo',
            '59' => 'Asistencia a entidades locales',
        ];

        $timestamp = now()->format('Ymd H:i:s');

        foreach ($funciones as $identificador => $descripcion) {
            DB::connection('Obras')
                ->table('catalogo_funciones_comunes')
                ->updateOrInsert(
                    ['IDENTIFICADOR' => $identificador],
                    [
                        'DESCRIPCION' => $descripcion,
                        'updated_at' => $timestamp,
                        'created_at' => $timestamp,
                    ],
                );
        }
    }
}

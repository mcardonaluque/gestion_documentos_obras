<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExpedientesTableSeeder extends Seeder
{
    public function run()
    {
        // Obtener datos de las tablas relacionadas
        $datosObras = DB::connection('Obras')->table('DatosInicioDeObras')->Where('Expediente','!=', 'NULL')->get();
        $estados = DB::connection('Obras')->table('TablaDeEstados')->pluck('cod_estado')->toArray();
        $formasEjecucion = DB::connection('Obras')->table('FormasDeEjecucion')->pluck('COD_CONTRATA')->toArray();
        $municipios = DB::connection('Obras')->table('TablaDeMunicipios')->pluck('codigo_municipio')->toArray();
        
        // Estados HELP inventados
        $estadosHelp = [
            'En trámite',
            'Aprobado',
            'En ejecución',
            'Finalizado',
            'Suspendido',
            'Cancelado',
            'Pendiente documentación',
            'En revisión técnica'
        ];

        $expedientes = [];
        $index=0;
        foreach ($datosObras as $index => $obra) {
            // Generar expediente_id según tu fórmula
            $expedienteId = trim($obra->Codigo_Plan) . '_' . 
                           $obra->numero_obra . '_' . 
                           $obra->subreferencia . '_' . 
                           $obra->ao_ejecucion;

            $expedientes[] = [
                'id' => $index + 1,
                'created_at' => Carbon::now()->subDays(rand(1, 365)),
                'updated_at' => Carbon::now(),
                'expediente_id' => $expedienteId,
                'cod_programa' => $obra->Codigo_Plan ?? 'PROG_' . rand(1000, 9999),
                'ao_ejecucion' => $obra->ao_ejecucion ?? rand(2020, 2024),
                'referencia' => $obra->numero_obra ?? rand(1, 1000),
                'subreferencia' => $obra->subreferencia ?? rand(1, 50),
                'nombre_obra' => $obra->nombre_obra1 ?? 'Obra ' . ($index + 1),
                'cod_estado' => $estados[array_rand($estados)] ?? 'PEN',
                'cod_estado_help' => $estadosHelp[array_rand($estadosHelp)],
                'municipio' => $municipios[array_rand($municipios)] ?? rand(1, 100),
                'forma_ejecucion' => $formasEjecucion[array_rand($formasEjecucion)] ?? 'DIR',
                'team_id' => rand(1, 5) // Ajusta según tus teams
            ];
           
            // Insertar en lotes de 150 para optimizar
            if (count($expedientes) >= 100 ) {
                DB::connection('Obras')->table('Expedientes')->insert($expedientes);
                
               return;
                
            }
        }

        // Insertar los registros restantes
       /* if (!empty($expedientes)) {
            DB::connection('Obras')->table('Expedientes')->insert($expedientes);
            $expedientes = [];
        }*/
    }
}
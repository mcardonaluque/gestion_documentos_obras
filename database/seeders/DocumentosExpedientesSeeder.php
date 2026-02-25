<?php
// database/seeders/DocumentosExpedientesSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Expediente;
use App\Models\DocumentoExpediente;
use App\Models\DocumentoGenerico;
use App\Models\DestinoDeDocumentos;
use App\Models\TablaDeEstados;
use Carbon\Carbon;

class DocumentosExpedientesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener algunos documentos genéricos existentes para usar como referencia
        $documentosGenericos = DocumentoGenerico::limit(10)->get();
        
        if ($documentosGenericos->isEmpty()) {
            $this->command->warn('No se encontraron documentos genéricos. Creando algunos de prueba...');
            $documentosGenericos = $this->crearDocumentosGenericosDePrueba();
        }

        // Obtener destinos de documentos
        $destinos = DestinoDeDocumentos::pluck('id')->toArray();
        if (empty($destinos)) {
            $destinos = [1, 2, 3]; // Valores por defecto
        }

        // Obtener todos los expedientes existentes
        $expedientes = Expediente::all();

        if ($expedientes->isEmpty()) {
            $this->command->warn('No se encontraron expedientes. Ejecuta primero el seeder de expedientes.');
            return;
        }
        $estados = TablaDeEstados::all();

        if ($estados->isEmpty()) {
            $this->command->warn('No se estados');
            return;
        }
        $this->command->info("Generando documentos para {$expedientes->count()} expedientes...");

        $documentosCreados = 0;

        foreach ($expedientes as $expediente) {
            // Obtener los valores del expediente para hacerlos coincidir
            $codPrograma = $expediente->cod_programa ?? 'PROG-' . rand(100, 999);
            $aoEjecucion = $expediente->ao_ejecucion ?? Carbon::now()->year;
            $referencia = $expediente->referencia ?? rand(1, 100);
            $subreferencia = $expediente->subreferencia ?? rand(1, 10);

            // Generar entre 3 y 5 documentos por expediente
            $cantidadDocumentos = rand(3, 5);
            
            for ($i = 1; $i <= $cantidadDocumentos; $i++) {
                $documentoGenerico = $documentosGenericos->random();
                $estado = $estados->random();
                $fechaIncorporacion = Carbon::now()
                    ->subDays(rand(1, 365))
                    ->setTime(rand(8, 18), rand(0, 59), rand(0, 59));

                $documentoData = [
                    'cod_plan' => $codPrograma, // Coincide con cod_programa del expediente
                    'referencia' => $referencia, // Coincide con referencia del expediente
                    'subreferencia' => $subreferencia, // Coincide con subreferencia del expediente
                    'ao_ejecucion' => $aoEjecucion, // Coincide con ao_ejecucion del expediente
                    'fechaincorporacion' => $fechaIncorporacion->format('Y-m-d'),
                    'fechaHelp' => $fechaIncorporacion->addDays(rand(1, 30))->format('Y-m-d'),
                    'cod_documento' => $documentoGenerico->id,
                    'Expediente' => $expediente->expediente_id,
                    'csv' => 'CSV-' . $expediente->expediente_id . '-' . $i . '-' . uniqid(),
                    'nregistro' => 'REG-' . $expediente->expediente_id . '-' . $i,
                    'nsecuencia' => $i,
                    'estado' => rand(1, 5), // 1: Activo, 2: Pendiente, 3: Rechazado
                    'descripcion' => $this->generarDescripcionDocumento($documentoGenerico, $i),
                    'team_id' => $expediente->team_id,
                    'destino' => $destinos[array_rand($destinos)],
                    'procedencia' => $destinos[array_rand($destinos)],
                    'notificado' => rand(0, 1),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                try {
                    DocumentoExpediente::create($documentoData);
                    $documentosCreados++;
                } catch (\Exception $e) {
                    $this->command->error("Error creando documento para expediente {$expediente->expediente_id}: " . $e->getMessage());
                }
            }

            $this->command->info("Expediente {$expediente->expediente_id}: {$cantidadDocumentos} documentos creados con cod_plan: {$codPrograma}, referencia: {$referencia}");
        }

        $this->command->info("¡Completado! Se crearon {$documentosCreados} documentos para {$expedientes->count()} expedientes.");
    }

    /**
     * Generar descripción realista para el documento
     */
    private function generarDescripcionDocumento(DocumentoGenerico $documentoGenerico, int $numero): string
    {
        $tiposDocumentos = [
            'Memoria técnica',
            'Planos arquitectónicos',
            'Estudio geotécnico',
            'Proyecto de ejecución',
            'Presupuesto detallado',
            'Estudio de seguridad',
            'Certificado municipal',
            'Licencia de obras',
            'Informe pericial',
            'Acta de replanteo',
            'Certificado final',
            'Documentación fotográfica',
            'Informe de calidad',
            'Certificado de conformidad',
            'Acta de recepción'
        ];

        $adjetivos = ['inicial', 'revisado', 'aprobado', 'pendiente', 'finalizado', 'modificado', 'complementario'];

        $tipo = $tiposDocumentos[array_rand($tiposDocumentos)];
        $adjetivo = $adjetivos[array_rand($adjetivos)];

        return "{$tipo} - {$adjetivo} - Documento {$numero}";
    }

    /**
     * Crear documentos genéricos de prueba si no existen
     */
    private function crearDocumentosGenericosDePrueba(): \Illuminate\Database\Eloquent\Collection
    {
        $documentosBase = [
            [
                'id' => 'MEM-TEC',
                'nombre' => 'Memoria Técnica',
                'fase_doc' => 'FASE1',
                'fase_siguiente' => 'FASE2',
                'cod_tipo_doc' => 'TIPO1',
                'descripcion' => 'Memoria técnica del proyecto',
                'generado' => 1,
                'con_plantilla' => 0,
                'plantilla' => null,
                'rutaplantilla' => null,
                'fasesiguiente' => 'FASE2',
                'cod_destino' => 1,
                'cod_origen' => 1,
                'obligatorio' => 1,
                'cod_estado' => 'ACTIVO'
            ],
            [
                'id' => 'PLAN-ARQ',
                'nombre' => 'Planos Arquitectónicos',
                'fase_doc' => 'FASE1',
                'fase_siguiente' => 'FASE2',
                'cod_tipo_doc' => 'TIPO2',
                'descripcion' => 'Planos arquitectónicos',
                'generado' => 1,
                'con_plantilla' => 0,
                'plantilla' => null,
                'rutaplantilla' => null,
                'fasesiguiente' => 'FASE2',
                'cod_destino' => 1,
                'cod_origen' => 1,
                'obligatorio' => 1,
                'cod_estado' => 'ACTIVO'
            ],
            [
                'id' => 'PRESUP',
                'nombre' => 'Presupuesto',
                'fase_doc' => 'FASE2',
                'fase_siguiente' => 'FASE3',
                'cod_tipo_doc' => 'TIPO3',
                'descripcion' => 'Presupuesto de obra',
                'generado' => 1,
                'con_plantilla' => 0,
                'plantilla' => null,
                'rutaplantilla' => null,
                'fasesiguiente' => 'FASE3',
                'cod_destino' => 1,
                'cod_origen' => 1,
                'obligatorio' => 1,
                'cod_estado' => 'ACTIVO'
            ],
            [
                'id' => 'EST-SEG',
                'nombre' => 'Estudio Seguridad',
                'fase_doc' => 'FASE1',
                'fase_siguiente' => 'FASE2',
                'cod_tipo_doc' => 'TIPO4',
                'descripcion' => 'Estudio de seguridad y salud',
                'generado' => 1,
                'con_plantilla' => 0,
                'plantilla' => null,
                'rutaplantilla' => null,
                'fasesiguiente' => 'FASE2',
                'cod_destino' => 1,
                'cod_origen' => 1,
                'obligatorio' => 1,
                'cod_estado' => 'ACTIVO'
            ],
            [
                'id' => 'CERT-FIN',
                'nombre' => 'Certificado Final',
                'fase_doc' => 'FASE4',
                'fase_siguiente' => null,
                'cod_tipo_doc' => 'TIPO5',
                'descripcion' => 'Certificado final de obra',
                'generado' => 1,
                'con_plantilla' => 0,
                'plantilla' => null,
                'rutaplantilla' => null,
                'fasesiguiente' => null,
                'cod_destino' => 1,
                'cod_origen' => 1,
                'obligatorio' => 1,
                'cod_estado' => 'ACTIVO'
            ]
        ];

        foreach ($documentosBase as $documento) {
            try {
                DocumentoGenerico::firstOrCreate(
                    ['id' => $documento['id']],
                    $documento
                );
            } catch (\Exception $e) {
                $this->command->warn("Error creando documento genérico {$documento['id']}: " . $e->getMessage());
            }
        }

        return DocumentoGenerico::all();
    }
}
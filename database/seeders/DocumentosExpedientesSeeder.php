<?php
// database/seeders/DocumentosExpedientesSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Expediente;
use App\Models\DocumentoExpediente;
use App\Models\DocumentoGenerico;
use App\Models\DestinoDeDocumentos;
use App\Models\TBestadosdeDocumentos;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DocumentosExpedientesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Se generan documentos solo para expedientes de 2024, 2025 y 2026.
        $yearsRequested = [20024, 2025, 2026];
        $years = collect($yearsRequested)
            ->map(static fn (int $year): int => $year === 20024 ? 2024 : $year)
            ->unique()
            ->values()
            ->all();

        $documentosGenericos = DocumentoGenerico::query()->get();

        if ($documentosGenericos->isEmpty()) {
            $this->command->warn('No se encontraron documentos genéricos. Creando algunos de prueba...');
            $documentosGenericos = $this->crearDocumentosGenericosDePrueba();
        }

        $destinos = DestinoDeDocumentos::query()->pluck('id')->toArray();
        if (empty($destinos)) {
            $destinos = [1, 2, 3]; // Valores por defecto
        }

        $expedientes = Expediente::query()
            ->with('obraEjecucion.actaReplanteo')
            ->whereIn('ao_ejecucion', $years)
            ->get();

        if ($expedientes->isEmpty()) {
            $yearsText = implode(', ', $years);
            $this->command->warn("No se encontraron expedientes para los años {$yearsText}.");
            return;
        }

        $estados = TBestadosdeDocumentos::query()->pluck('id')->toArray();

        if (empty($estados)) {
            $this->command->warn('No se encontraron estados de documentos en TBEstadodeDocumentos.');
            return;
        }

        $yearsText = implode(', ', $years);
        $this->command->info("Generando documentos para {$expedientes->count()} expedientes ({$yearsText})...");

        $documentosCreados = 0;
        $nextIdDocumento = ((int) (DB::connection('Obras')
            ->table('dbo.documentacionexpedientes')
            ->max('idDocumento') ?? 0)) + 1;

        foreach ($expedientes as $expediente) {
            $codigoPlan = (string) ($expediente->codigo_plan ?? $expediente->Codigo_Plan ?? '');

            if ($codigoPlan === '') {
                $this->command->warn("Expediente {$expediente->expediente_id} sin código de plan. Se omite.");
                continue;
            }

            $aoEjecucion = (int) ($expediente->ao_ejecucion ?? Carbon::now()->year);
            $referencia = (int) ($expediente->referencia ?? 0);
            $subreferencia = (int) ($expediente->subreferencia ?? 0);

            // Continuar secuencia existente para no solapar numeraciones.
            $nextSequence = DocumentoExpediente::query()
                ->where('expediente_id', $expediente->expediente_id)
                ->max('nsecuencia');
            $nextSequence = ((int) ($nextSequence ?? 0)) + 1;

            $cantidadDocumentos = rand(2, 4);
            $creadosExpediente = 0;

            $fechaInicioActa = DB::connection('Obras')
                ->table('ActasDeReplanteo')
                ->where('expediente_id', $expediente->expediente_id)
                ->value('Fecha_Inicio_Acta_Replanteo');

            if (blank($fechaInicioActa)) {
                $fechaMinima = Carbon::now()->subDays(30)->startOfDay();
            } else {
                $fechaMinima = Carbon::parse($fechaInicioActa)->startOfDay();
            }

            $fechaMaxima = $fechaMinima->copy()->addDays(30)->endOfDay();

            for ($i = 1; $i <= $cantidadDocumentos; $i++) {
                $documentoGenerico = $documentosGenericos->random();
                $estado = $estados[array_rand($estados)];
                $diasOffset = rand(0, 30);
                $fechaIncorporacion = $fechaMinima->copy()
                    ->addDays($diasOffset)
                    ->setTime(rand(8, 18), rand(0, 59), rand(0, 59));

                if ($fechaIncorporacion->gt($fechaMaxima)) {
                    $fechaIncorporacion = $fechaMaxima->copy()->setTime(rand(8, 18), rand(0, 59), rand(0, 59));
                }

                $fechaHelp = $fechaIncorporacion->copy()->addDays(rand(1, 5));

                $documentoData = [
                    'idDocumento' => $nextIdDocumento++,
                    'Codigo_Plan' => $codigoPlan,
                    'referencia' => $referencia,
                    'subreferencia' => $subreferencia,
                    'ao_ejecucion' => $aoEjecucion,
                    'fechaincorporacion' => $fechaIncorporacion->format('Y-m-d'),
                    'fechaHelp' => $fechaHelp->format('Y-m-d'),
                    'cod_documento' => $documentoGenerico->id,
                    'expediente_id' => $expediente->expediente_id,
                    'csv' => 'CSV-' . $expediente->expediente_id . '-' . $i . '-' . uniqid(),
                    'nregistro' => 'REG-' . $expediente->expediente_id . '-' . $i,
                    'nsecuencia' => $nextSequence++,
                    'estado' => $estado,
                    'descripcion' => $this->generarDescripcionDocumento($documentoGenerico, $i),
                    'team_id' => $expediente->team_id,
                    'destino' => $destinos[array_rand($destinos)],
                    'procedencia' => $destinos[array_rand($destinos)],
                    'notificado' => rand(0, 1),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                try {
                    DocumentoExpediente::withoutEvents(function () use ($documentoData): void {
                        DocumentoExpediente::create($documentoData);
                    });
                    $documentosCreados++;
                    $creadosExpediente++;
                } catch (\Exception $e) {
                    $this->command->error("Error creando documento para expediente {$expediente->expediente_id}: " . $e->getMessage());
                }
            }

            $this->command->info("Expediente {$expediente->expediente_id}: {$creadosExpediente}/{$cantidadDocumentos} documentos creados (plan {$codigoPlan}).");
        }

        $this->command->info("¡Completado! Se crearon {$documentosCreados} documentos para expedientes de {$yearsText}.");
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
            ],
            [
                'id' => 'ACT-REP',
                'nombre' => 'Acta de Replanteo',
                'fase_doc' => 'FASE1',
                'fase_siguiente' => 'FASE2',
                'cod_tipo_doc' => 'TIPO6',
                'descripcion' => 'Acta de replanteo de obra',
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
                'id' => 'ACT-REC',
                'nombre' => 'Acta de Recepción',
                'fase_doc' => 'FASE4',
                'fase_siguiente' => null,
                'cod_tipo_doc' => 'TIPO7',
                'descripcion' => 'Acta de recepción de obra',
                'generado' => 1,
                'con_plantilla' => 0,
                'plantilla' => null,
                'rutaplantilla' => null,
                'fasesiguiente' => null,
                'cod_destino' => 1,
                'cod_origen' => 1,
                'obligatorio' => 0,
                'cod_estado' => 'ACTIVO'
            ],
            [
                'id' => 'INF-QUAL',
                'nombre' => 'Informe de Calidad',
                'fase_doc' => 'FASE3',
                'fase_siguiente' => 'FASE4',
                'cod_tipo_doc' => 'TIPO8',
                'descripcion' => 'Informe técnico de calidad de ejecución',
                'generado' => 1,
                'con_plantilla' => 0,
                'plantilla' => null,
                'rutaplantilla' => null,
                'fasesiguiente' => 'FASE4',
                'cod_destino' => 1,
                'cod_origen' => 1,
                'obligatorio' => 0,
                'cod_estado' => 'ACTIVO'
            ],
            [
                'id' => 'CERT-CONF',
                'nombre' => 'Certificado de Conformidad',
                'fase_doc' => 'FASE3',
                'fase_siguiente' => 'FASE4',
                'cod_tipo_doc' => 'TIPO9',
                'descripcion' => 'Certificado de conformidad de la obra',
                'generado' => 1,
                'con_plantilla' => 0,
                'plantilla' => null,
                'rutaplantilla' => null,
                'fasesiguiente' => 'FASE4',
                'cod_destino' => 1,
                'cod_origen' => 1,
                'obligatorio' => 0,
                'cod_estado' => 'ACTIVO'
            ],
            [
                'id' => 'LIC-OBRA',
                'nombre' => 'Licencia de Obra',
                'fase_doc' => 'FASE1',
                'fase_siguiente' => 'FASE2',
                'cod_tipo_doc' => 'TIPO10',
                'descripcion' => 'Licencia municipal de ejecución',
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
                'id' => 'DOC-FOTO',
                'nombre' => 'Documentación Fotográfica',
                'fase_doc' => 'FASE3',
                'fase_siguiente' => 'FASE4',
                'cod_tipo_doc' => 'TIPO11',
                'descripcion' => 'Anexo fotográfico de seguimiento',
                'generado' => 1,
                'con_plantilla' => 0,
                'plantilla' => null,
                'rutaplantilla' => null,
                'fasesiguiente' => 'FASE4',
                'cod_destino' => 1,
                'cod_origen' => 1,
                'obligatorio' => 0,
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

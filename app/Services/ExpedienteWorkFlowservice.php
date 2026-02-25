<?php

namespace App\Services;

use App\Events\SystemEventOccurred;
use App\Models\DocumentoExpediente;
use App\Models\DocumentoGenerico;
use App\Models\Expediente;
use App\Services\Tramitador\TramitadorApiClient;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ExpedienteWorkFlowService
{
    /* =========================
     * DOCUMENTO LOCAL
     * ========================= */

    public static function getDocumentoActual(?int $documento_id): ?DocumentoExpediente
    {
        return DocumentoExpediente::find($documento_id);
    }

    protected static function getDocumentoDefinicion(?int $documento_id): ?DocumentoGenerico
    {
        $doc = self::getDocumentoActual($documento_id);

        return $doc
            ? DocumentoGenerico::where('cod_documento', $doc->cod_documento)->first()
            : null;
    }

    /* =========================
     * WORKFLOW
     * ========================= */

    public static function saveDocumento(?int $documento_id, bool $fromApi = false): void
    {
        DB::transaction(function () use ($documento_id, $fromApi) {

            self::saveEstadoActual($documento_id);

            // Solo subir al tramitador si el origen es la app
            if (!$fromApi) {
                self::saveDocumentoHelp($documento_id);
            }

            $doc = self::getDocumentoActual($documento_id);

            if ($doc) {
                event(new SystemEventOccurred(
                    eventType: $fromApi ? 'documento_incorporado_tramitador' : 'documento_subido_app',
                    title: $fromApi ? 'Documento incorporado desde Tramitador' : 'Documento gestionado desde la aplicación',
                    message: $fromApi
                        ? "Documento {$doc->descripcion} incorporado desde Tramitador en expediente {$doc->expediente_id}."
                        : "Documento {$doc->descripcion} enviado/actualizado para expediente {$doc->expediente_id}.",
                    type: 'info',
                    toAllUsers: false,
                    userIds: [],
                    teamId: $doc->team_id,
                    entity: $doc,
                    meta: [
                        'documento_id' => $doc->idDocumento,
                        'expediente_id' => $doc->expediente_id,
                        'from_api' => $fromApi,
                    ],
                ));
            }
        });
    }

    public static function saveEstadoActual(?int $documento_id): void
    {
        $doc = self::getDocumentoActual($documento_id);
        $def = self::getDocumentoDefinicion($documento_id);

        if (!$doc || !$def?->cod_estado) {
            return;
        }

        Expediente::where('expediente_id', $doc->expediente_id)->update([
            'cod_estado' => $def->cod_estado,
            'cod_estado_help' => $def->descripcion,
        ]);
    }

    /* =========================
     * API TRAMITADOR
     * ========================= */

    /**
     * 🔼 Subir documento al tramitador
     */
    public static function saveDocumentoHelp(?int $documento_id): void
    {
       $doc = self::getDocumentoActual($documento_id);

    if (!$doc || !$doc->csv) {
        return;
    }

    $dir3 = config('tramite.dir3_emisor');

    $response = TramitadorApiClient::postFile(
        "{$dir3}/exp/{$doc->expediente_id}/csv",
        storage_path("app/{$doc->csv}")
    );

    if ($response->successful()) {
        $doc->update([
            'notificado' => true,
            'nregistro'  => $response->json('nregistro'),
            'nsecuencia' => $response->json('nsecuencia'),
        ]);
    }
    }

    /**
     * 🔽 Descargar documento desde el tramitador
     */
    public static function getDocumentoHelp(
        string $dir3_emisor,
        string $idExp,
        string $csv
    ): ?string {
          $response = TramitadorApiClient::get(
        "{$dir3_emisor}/exp/{$idExp}/docs/{$csv}"
    );

    if (!$response->successful()) {
        return null;
    }

    $path = "expedientes/{$idExp}/{$csv}.pdf";
     Storage::put($path, $response->body());

    return $path;
    }

    /* =========================
     * FASES / ESTADOS (GETTERS)
     * ========================= */

    public static function getEstadoActual(?int $documento_id): ?string
    {
        return self::getDocumentoActual($documento_id)?->expediente?->cod_estado;
    }

    public static function getEstadoSiguiente(?int $documento_id): ?string
    {
        return self::getDocumentoDefinicion($documento_id)?->cod_estado;
    }

    public static function getFaseActual(?int $documento_id): ?string
    {
        return self::getDocumentoDefinicion($documento_id)?->fase_doc;
    }

    public static function getFaseSiguiente(?int $documento_id): ?string
    {
        return self::getDocumentoDefinicion($documento_id)?->fase_siguiente;
    }

    /* =========================
     * URLS
     * ========================= */

    protected static function getUploadUrl(DocumentoExpediente $doc): string
    {
        return "https://api-se.diputacion.malaga.es/api/v1/"
            . config('tramite.dir3_emisor')
            . "/exp/{$doc->expediente_id}/csv";
    }
}

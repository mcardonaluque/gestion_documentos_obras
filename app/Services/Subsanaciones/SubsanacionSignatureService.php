<?php

declare(strict_types=1);

namespace App\Services\Subsanaciones;

use App\Models\FirmanteDocumento;
use App\Models\Subsanacion;
use App\Models\User;
use App\Services\Tramitador\TramitadorApiClient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

/**
 * Envía a NIMEXP el documento de solicitud y su cadena de validación/firma.
 */
final class SubsanacionSignatureService
{
    public function requestSignature(Subsanacion $subsanacion): void
    {
        if ($subsanacion->id_peticion_firma !== null) {
            return;
        }

        if (blank($subsanacion->archivo_solicitud)) {
            throw new RuntimeException('Debe adjuntar el documento de solicitud para enviarlo a firma.');
        }

        $documentPath = Storage::disk('local')->path($subsanacion->archivo_solicitud);

        if (! is_file($documentPath)) {
            throw new RuntimeException('No se encuentra el documento de solicitud adjunto.');
        }

        $firmantesDocumento = $subsanacion->firmantes()->get();
        $idUsuarios = $firmantesDocumento
            ->pluck('id_usuario')
            ->map(fn (string $idUsuario): string => trim($idUsuario))
            ->filter()
            ->unique()
            ->values()
            ->all();
        $usuariosPorId = User::query()
            ->with('roles')
            ->whereIn(DB::raw('RTRIM(id_usuario)'), $idUsuarios)
            ->get()
            ->keyBy(fn (User $usuario): string => trim((string) $usuario->id_usuario));

        $firmantes = $firmantesDocumento
            ->map(function (FirmanteDocumento $firmante) use ($usuariosPorId): array {
                $idUsuario = trim((string) $firmante->id_usuario);

                if ($idUsuario === '') {
                    throw new RuntimeException(sprintf(
                        'El usuario seleccionado para la cadena de firma no tiene id_usuario de tramitador.'
                    ));
                }

                $usuario = $usuariosPorId->get($idUsuario);

                if (! $usuario instanceof User) {
                    throw new RuntimeException(sprintf(
                        'No se encuentra el usuario de Tramitador "%s" seleccionado para la cadena de firma.',
                        $idUsuario,
                    ));
                }

                if (! $usuario->hasRole($firmante->id_rol)) {
                    throw new RuntimeException(sprintf(
                        'El usuario seleccionado no tiene asignado el rol NIMBUS "%s".',
                        $firmante->id_rol,
                    ));
                }

                return [
                    'id_usu' => $idUsuario,
                    'id_rol' => $firmante->id_rol,
                    'tipo' => $firmante->tipo_firma,
                    'orden' => $firmante->orden,
                ];
            })
            ->values()
            ->all();

        if ($firmantes === []) {
            throw new RuntimeException('Debe indicar al menos un validador o firmante.');
        }

        $idUsuarioTramitador = trim((string) Auth::user()?->id_usuario);

        if ($idUsuarioTramitador === '') {
            throw new RuntimeException('El usuario que solicita la firma no tiene id_usuario de tramitador.');
        }

        $fileContents = file_get_contents($documentPath);

        if ($fileContents === false) {
            throw new RuntimeException('No se ha podido leer el documento de solicitud adjunto.');
        }

        $response = TramitadorApiClient::post(sprintf(
            '/%s/exp/%s/nimb',
            config('tramitador.dir3_emisor'),
            rawurlencode((string) $subsanacion->expediente_id),
        ), [
            'ref_emisor' => 'SUBSANACION-'.$subsanacion->id,
            'concepto' => 'Solicitud de subsanación',
            'id_usu_tramitador' => $idUsuarioTramitador,
            'documentos' => [
                'contenido' => base64_encode($fileContents),
                'nombre' => basename($documentPath),
                'metadatos' => [
                    'tipo_documental' => $subsanacion->tipo_documental_solicitud,
                ],
            ],
            'firmantes' => $firmantes,
        ]);

        if (! $response->successful()) {
            throw new RuntimeException(sprintf(
                'NIMEXP rechazó la solicitud de firma: %s',
                (string) ($response->json('message') ?? $response->body()),
            ));
        }

        $idPeticion = $response->json('data.id_peticion');

        if (! is_scalar($idPeticion) || trim((string) $idPeticion) === '') {
            throw new RuntimeException('NIMEXP no devolvió el identificador de la petición de firma.');
        }

        $subsanacion->forceFill([
            'id_peticion_firma' => (string) $idPeticion,
            'fecha_solicitud_firma' => now(),
        ])->save();

        $subsanacion->firmantes()->update([
            'fecha_envio_firma' => now(),
        ]);
    }
}

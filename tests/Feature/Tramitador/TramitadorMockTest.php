<?php

declare(strict_types=1);

namespace Tests\Feature\Tramitador;

use App\Services\Tramitador\Mock\TramitadorMock;
use App\Services\Tramitador\TramitadorApiClient;
use App\Services\Tramitador\TramitadorApiOperationService;
use App\Services\Tramitador\TramitadorApiResponseReader;
use App\Services\Tramitador\TramitadorAuthService;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class TramitadorMockTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        TramitadorAuthService::clearToken();
        TramitadorMock::register();
    }

    public function test_obtiene_documentos_de_un_expediente(): void
    {
        $response = TramitadorApiClient::get('/LA0003318/exp/5894/docs');

        $this->assertTrue($response->successful());
        $this->assertSame(200, $response->json('status'));
        $this->assertSame(5894, $response->json('data.id_exp'));
        $this->assertCount(3, $response->json('data.documentos'));
        $this->assertSame(589403, $response->json('data.documentos.0.id_doc'));
    }

    public function test_expediente_sin_fixture_usa_default(): void
    {
        $response = TramitadorApiClient::get('/LA0003318/exp/777/docs');

        $this->assertSame(777, $response->json('data.id_exp'));
        $this->assertCount(1, $response->json('data.documentos'));
    }

    public function test_expediente_sin_documentos(): void
    {
        $this->assertSame([], TramitadorApiClient::get('/LA0003318/exp/0/docs')->json('data.documentos'));
    }

    public function test_ruta_no_simulada_devuelve_404(): void
    {
        $this->assertSame(404, TramitadorApiClient::get('/LA0003318/exp/abc/docs')->status());
    }

    public function test_el_lector_extrae_los_campos_de_la_lista(): void
    {
        $respuesta = app(TramitadorApiOperationService::class)->ejecutar('ODOCSEXP', [
            'dir3_emisor' => 'LA0003318',
            'id_exp' => 5894,
        ]);

        $data = app(TramitadorApiResponseReader::class)->leer('ODOCSEXP', $respuesta)['data'];

        $this->assertCount(3, $data['documentos']);
        $this->assertSame(589403, $data['documentos'][0]['id_doc']);
        $this->assertSame(['id_doc', 'nombre', 'csv', 'fecha'], array_keys($data['documentos'][0]));
        $this->assertArrayNotHasKey('id_doc', $data);
    }

    public function test_infexp_devuelve_la_informacion_del_expediente(): void
    {
        $respuesta = app(TramitadorApiOperationService::class)->ejecutar('INFEXP', [
            'dir3_emisor' => 'LA0001721',
            'id_exp' => 11184,
        ]);

        $this->assertSame(200, $respuesta->status());
        $this->assertSame('ok', $respuesta->json('status'));

        $data = app(TramitadorApiResponseReader::class)->leer('INFEXP', $respuesta)['data'];

        $this->assertSame(11184, $data['id_exp']);
        $this->assertSame('28/2022', $data['num_exp']);
        $this->assertSame('20/03/2022', $data['fecha_registro']);
        $this->assertSame('Prueba grupo 9017 (11)', $data['grp_descripcion']);
        $this->assertSame(3, $data['duracion_meses']);
    }

    public function test_infexp_expediente_cerrado(): void
    {
        $this->assertSame('CER', TramitadorApiClient::get('/LA0003318/exp/5895')->json('data.estado'));
    }

    public function test_infexp_expediente_inexistente_devuelve_404(): void
    {
        $response = TramitadorApiClient::get('/LA0003318/exp/404');

        $this->assertSame(404, $response->status());
        $this->assertSame('Expediente no encontrado', $response->json('message'));
    }

    public function test_infrof_devuelve_informacion_de_informe_pendiente(): void
    {
        $respuesta = app(TramitadorApiOperationService::class)->ejecutar('INFROF', [
            'dir3_emisor' => 'LA0001721',
            'id_exp' => 11184,
            'id_peticion' => 9001,
        ]);

        $this->assertSame(200, $respuesta->status());
        $this->assertSame('ok', $respuesta->json('status'));

        $data = app(TramitadorApiResponseReader::class)->leer('INFROF', $respuesta)['data'];

        $this->assertSame(9001, $data['id_peticion_s']);
        $this->assertSame(11184, $data['id_exp_s']);
        $this->assertSame('PEN', $data['estado']);
        $this->assertSame(900101, $data['documento']['id_doc']);
        $this->assertSame('Informe_ROF_11184.pdf', $data['documento']['nombre']);
        $this->assertNull($data['firmante']['fecha_firma']);
    }

    public function test_infrof_devuelve_firma_realizada(): void
    {
        $respuesta = app(TramitadorApiOperationService::class)->ejecutar('INFROF', [
            'dir3_emisor' => 'LA0001721',
            'id_exp' => 11184,
            'id_peticion' => 9002,
        ]);

        $data = app(TramitadorApiResponseReader::class)->leer('INFROF', $respuesta)['data'];

        $this->assertSame('FIN', $data['estado']);
        $this->assertSame('usuprp', $data['firmante']['id_usu_firma']);
        $this->assertSame('FIR', $data['firmante']['accion']);
    }

    public function test_infrof_peticion_inexistente_devuelve_404(): void
    {
        $respuesta = app(TramitadorApiOperationService::class)->ejecutar('INFROF', [
            'dir3_emisor' => 'LA0001721',
            'id_exp' => 11184,
            'id_peticion' => 404,
        ]);

        $this->assertSame(404, $respuesta->status());
        $this->assertSame('Petición de informe ROF no encontrada', $respuesta->json('message'));
    }

    public function test_infnimb_devuelve_tramite_de_firma_en_curso(): void
    {
        $respuesta = app(TramitadorApiOperationService::class)->ejecutar('INFNIMB', [
            'dir3_emisor' => 'LA0001721',
            'id_exp' => 11184,
            'id_peticion' => 9101,
        ]);

        $this->assertSame(200, $respuesta->status());

        $data = app(TramitadorApiResponseReader::class)->leer('INFNIMB', $respuesta)['data'];

        $this->assertSame('PEN', $data['estado']);
        $this->assertCount(2, $data['documentos']);
        $this->assertCount(2, $data['firmantes']);
        $this->assertSame('Firmado', $data['firmantes'][0]['accion']);
        $this->assertNull($data['firmantes'][1]['fecha_firma']);
        $this->assertCount(1, $data['traslados']);
        $this->assertSame([], $data['traslados_leidos']);
    }

    public function test_infnimb_devuelve_tramite_finalizado(): void
    {
        $respuesta = app(TramitadorApiOperationService::class)->ejecutar('INFNIMB', [
            'dir3_emisor' => 'LA0001721',
            'id_exp' => 11190,
            'id_peticion' => 9102,
        ]);

        $data = app(TramitadorApiResponseReader::class)->leer('INFNIMB', $respuesta)['data'];

        $this->assertSame('FIR', $data['estado']);
        $this->assertSame(['Firmado', 'Validado'], array_column($data['firmantes'], 'accion'));
        $this->assertSame('23/09/2026', $data['traslados_leidos'][0]['fecha_leido']);
    }

    public function test_infnimb_peticion_inexistente_devuelve_404(): void
    {
        $respuesta = app(TramitadorApiOperationService::class)->ejecutar('INFNIMB', [
            'dir3_emisor' => 'LA0001721',
            'id_exp' => 11184,
            'id_peticion' => 404,
        ]);

        $this->assertSame(404, $respuesta->status());
        $this->assertSame('Trámite de firma no encontrado', $respuesta->json('message'));
    }

    public function test_modexp_modifica_un_expediente_abierto(): void
    {
        $respuesta = app(TramitadorApiOperationService::class)->ejecutar('MODEXP', [
            'dir3_emisor' => 'LA0001721',
            'id_exp' => 11190,
            'asunto' => 'Subvención modificada',
            'id_usu_responsable' => 'usuprp2',
        ]);

        $this->assertSame(200, $respuesta->status());

        $data = app(TramitadorApiResponseReader::class)->leer('MODEXP', $respuesta)['data'];

        $this->assertSame(11190, $data['id_exp_s']);
        $this->assertSame('Subvención modificada', $data['asunto_s']);
        $this->assertSame('usuprp2', $data['id_usu_responsable_s']);
        $this->assertSame('ABI', $data['estado_s']);

        $original = app(TramitadorApiResponseReader::class)->leer('INFEXP', app(TramitadorApiOperationService::class)->ejecutar('INFEXP', [
            'dir3_emisor' => 'LA0001721',
            'id_exp' => 11190,
        ]))['data'];

        $this->assertSame($original['num_exp'], $data['num_exp_s']);
        $this->assertSame($original['id_uo'], $data['id_uo_s']);
    }

    public function test_modexp_expediente_cerrado_devuelve_409(): void
    {
        $respuesta = app(TramitadorApiOperationService::class)->ejecutar('MODEXP', [
            'dir3_emisor' => 'LA0001721',
            'id_exp' => 11184,
            'asunto' => 'Intento de cambio',
        ]);

        $this->assertSame(409, $respuesta->status());
        $this->assertStringContainsString('cerrado', (string) $respuesta->json('message'));
    }

    public function test_modexp_duracion_sin_meses_es_rechazada_por_el_servicio(): void
    {
        $this->expectException(ValidationException::class);

        app(TramitadorApiOperationService::class)->ejecutar('MODEXP', [
            'dir3_emisor' => 'LA0001721',
            'id_exp' => 11190,
            'id_tipo_duracion' => 'SP',
        ]);
    }

    public function test_modexp_acepta_duracion_con_meses(): void
    {
        $respuesta = app(TramitadorApiOperationService::class)->ejecutar('MODEXP', [
            'dir3_emisor' => 'LA0001721',
            'id_exp' => 11190,
            'id_tipo_duracion' => 'SP',
            'duracion_meses' => 12,
        ]);

        $this->assertSame(200, $respuesta->status());
    }

    public function test_modexp_origen_inexistente_en_catalogo_es_rechazado(): void
    {
        try {
            app(TramitadorApiOperationService::class)->ejecutar('MODEXP', [
                'dir3_emisor' => 'LA0001721',
                'id_exp' => 11190,
                'id_efecto_silencio' => 'XXX',
            ]);
            $this->fail('Debía lanzar ValidationException');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('id_efecto_silencio', $e->errors());
        }
    }

    public function test_modexp_acepta_valor_existente_en_catalogo(): void
    {
        $respuesta = app(TramitadorApiOperationService::class)->ejecutar('MODEXP', [
            'dir3_emisor' => 'LA0001721',
            'id_exp' => 11190,
            'id_efecto_silencio' => 'DES',
        ]);

        $this->assertSame(200, $respuesta->status());
    }

    public function test_modesexp_cierra_expediente_abierto_sin_tramites_pendientes(): void
    {
        $respuesta = app(TramitadorApiOperationService::class)->ejecutar('MODESEXP', [
            'dir3_emisor' => 'LA0001721',
            'id_exp' => 1,
            'id_usu_tramitador' => 'usuarioprp01',
            'estado' => 'CER',
        ]);

        $this->assertSame(200, $respuesta->status());
        $this->assertSame(1, $respuesta->json('data.id_exp'));
        $this->assertSame('CER', $respuesta->json('data.estado'));
    }

    public function test_modesexp_no_cierra_expediente_que_ya_esta_cerrado(): void
    {
        $respuesta = app(TramitadorApiOperationService::class)->ejecutar('MODESEXP', [
            'dir3_emisor' => 'LA0001721',
            'id_exp' => 11184,
            'id_usu_tramitador' => 'usuarioprp01',
            'estado' => 'CER',
        ]);

        $this->assertSame(409, $respuesta->status());
    }

    public function test_modesexp_rechaza_expediente_con_tramites_pendientes(): void
    {
        $respuesta = app(TramitadorApiOperationService::class)->ejecutar('MODESEXP', [
            'dir3_emisor' => 'LA0001721',
            'id_exp' => 11190,
            'id_usu_tramitador' => 'usuarioprp01',
            'estado' => 'CER',
        ]);

        $this->assertSame(409, $respuesta->status());
        $this->assertStringContainsString('pendientes', (string) $respuesta->json('message'));
    }

    public function test_modesexp_solo_admite_el_estado_cerrado(): void
    {
        $this->expectException(ValidationException::class);

        app(TramitadorApiOperationService::class)->ejecutar('MODESEXP', [
            'dir3_emisor' => 'LA0001721',
            'id_exp' => 1,
            'id_usu_tramitador' => 'usuarioprp01',
            'estado' => 'ABI',
        ]);
    }

    public function test_creaexp_rango_de_meses_depende_del_tipo_de_duracion(): void
    {
        $base = [
            'dir3_emisor' => 'LA0001721',
            'asunto' => 'Prueba',
            'id_tipo_duracion' => 'NR',
            'duracion_meses' => 7,
        ];

        try {
            app(TramitadorApiOperationService::class)->ejecutar('CREAEXP', $base);
            $this->fail('Debía lanzar ValidationException');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('duracion_meses', $e->errors());
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function notificacion(int $idExp, array $extra = []): array
    {
        return array_merge([
            'dir3_emisor' => 'LA0001721',
            'id_exp' => $idExp,
            'tipo_envio' => 'NOT',
            'ref_emisor' => 'REF-001',
            'concepto' => 'Requerimiento de subsanación',
            'id_usu_tramitador' => 'usuarioprp01',
            'programar_envio' => false,
            'titular' => 1001,
            'id_doc_not' => 589403,
            'callback_url' => 'https://mi-app.test/callback',
        ], $extra);
    }

    public function test_notexp_crea_notificacion_en_expediente_abierto(): void
    {
        $respuesta = app(TramitadorApiOperationService::class)->ejecutar('NOTEXP', $this->notificacion(11190));

        $this->assertSame(200, $respuesta->status());
        $this->assertSame('9311190', app(TramitadorApiResponseReader::class)->leer('NOTEXP', $respuesta)['data']['id_peticion']);
    }

    public function test_notexp_expediente_cerrado_devuelve_409(): void
    {
        $respuesta = app(TramitadorApiOperationService::class)->ejecutar('NOTEXP', $this->notificacion(11184));

        $this->assertSame(409, $respuesta->status());
    }

    public function test_notexp_programada_exige_fecha_de_envio(): void
    {
        try {
            app(TramitadorApiOperationService::class)->ejecutar('NOTEXP', $this->notificacion(11190, ['programar_envio' => true]));
            $this->fail('Debía lanzar ValidationException');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('fecha_envio_prog', $e->errors());
        }

        $respuesta = app(TramitadorApiOperationService::class)->ejecutar('NOTEXP', $this->notificacion(11190, [
            'programar_envio' => true,
            'fecha_envio_prog' => '20/10/2026',
        ]));

        $this->assertSame(200, $respuesta->status());
    }

    public function test_notexp_tipo_de_envio_invalido_es_rechazado(): void
    {
        $this->expectException(ValidationException::class);

        app(TramitadorApiOperationService::class)->ejecutar('NOTEXP', $this->notificacion(11190, ['tipo_envio' => 'XXX']));
    }

    public function test_infnot_devuelve_notificacion_creada_por_notexp(): void
    {
        $respuesta = app(TramitadorApiOperationService::class)->ejecutar('INFNOT', [
            'dir3_emisor' => 'LA0001721',
            'id_exp' => 11190,
            'id_peticion' => 9311190,
        ]);

        $data = app(TramitadorApiResponseReader::class)->leer('INFNOT', $respuesta)['data'];

        $this->assertSame('NOT', $data['tipo_envio']);
        $this->assertSame('12345678Z', $data['titular']['nif']);
        $this->assertSame('Pendiente de Comparecencia', $data['estado_notificacion']);
        $this->assertSame('EN_PROCESO', $data['estado']);
    }

    public function test_infnot_comunicacion_leida_incluye_certificacion(): void
    {
        $respuesta = app(TramitadorApiOperationService::class)->ejecutar('INFNOT', [
            'dir3_emisor' => 'LA0001721',
            'id_exp' => 11190,
            'id_peticion' => 9302,
        ]);

        $data = app(TramitadorApiResponseReader::class)->leer('INFNOT', $respuesta)['data'];

        $this->assertSame('COM', $data['tipo_envio']);
        $this->assertSame('Leido', $data['estado_notificacion']);
        $this->assertSame(589411, $data['documento_certificacion']['id_doc_c']);
    }

    public function test_infnot_peticion_inexistente_devuelve_404(): void
    {
        $respuesta = app(TramitadorApiOperationService::class)->ejecutar('INFNOT', [
            'dir3_emisor' => 'LA0001721',
            'id_exp' => 11190,
            'id_peticion' => 404,
        ]);

        $this->assertSame(404, $respuesta->status());
    }

    /**
     * @return array<string, mixed>
     */
    private function nuevoExpediente(array $extra = []): array
    {
        return array_merge([
            'dir3_emisor' => 'LA0001721',
            'ref_emisor' => 'REF-NUEVO-1',
            'id_proc' => 1,
            'asunto' => 'Subvención: nuevo expediente',
            'fecha_apertura' => '08/10/2026',
            'id_uo' => '9017',
            'id_usu_tramitador' => 'usuarioprp01',
            'id_cla_fun_comunes' => '25',
        ], $extra);
    }

    public function test_creaexp_crea_expediente(): void
    {
        $respuesta = app(TramitadorApiOperationService::class)->ejecutar('CREAEXP', $this->nuevoExpediente());

        $this->assertSame(200, $respuesta->status());

        $data = app(TramitadorApiResponseReader::class)->leer('CREAEXP', $respuesta)['data'];

        $this->assertSame(11200, $data['id_exp']);
        $this->assertSame('REF-NUEVO-1', $data['ref_emisor_s']);
    }

    public function test_creaexp_exige_campos_obligatorios(): void
    {
        try {
            app(TramitadorApiOperationService::class)->ejecutar('CREAEXP', ['dir3_emisor' => 'LA0001721']);
            $this->fail('Debía lanzar ValidationException');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('asunto', $e->errors());
            $this->assertArrayHasKey('id_uo', $e->errors());
        }
    }

    public function test_lexp_lista_expedientes_filtrados_por_ejercicio_y_uo(): void
    {
        $respuesta = app(TramitadorApiOperationService::class)->ejecutar('LEXP', [
            'dir3_emisor' => 'LA0001721',
            'ejercicio' => 2022,
            'id_uo' => '9017',
        ]);

        $data = app(TramitadorApiResponseReader::class)->leer('LEXP', $respuesta)['data'];

        $this->assertSame(2, $data['total']);
        $this->assertSame([11190, 11184], array_column($data['expedientes'], 'id_exp'));
        $this->assertSame('9017', $data['id_uo_s']);
        $this->assertSame('Subvención: azl 2', $data['expedientes'][0]['asunto_s']);
    }

    public function test_lexp_filtra_por_estado(): void
    {
        $respuesta = app(TramitadorApiOperationService::class)->ejecutar('LEXP', [
            'dir3_emisor' => 'LA0001721',
            'ejercicio' => 2026,
            'id_uo' => '9018',
            'estado' => 'ABI',
        ]);

        $this->assertSame(2, $respuesta->json('data.total'));
    }

    public function test_lexp_sin_resultados(): void
    {
        $respuesta = app(TramitadorApiOperationService::class)->ejecutar('LEXP', [
            'dir3_emisor' => 'LA0001721',
            'ejercicio' => 1999,
            'id_uo' => '9017',
        ]);

        $this->assertSame(0, $respuesta->json('data.total'));
        $this->assertSame([], $respuesta->json('data.expedientes'));
    }

    /**
     * @param  array<string, mixed>  $extra
     */
    private function obtenerDocumento(string|int $id, array $extra = []): Response
    {
        return app(TramitadorApiOperationService::class)->ejecutar('OBDOC', [
            'dir3_emisor' => 'LA0003318',
            'id_doc' => $id,
            'tipo' => 'contenido',
            'original' => true,
            'caratula' => false,
            'metadatos' => true,
            ...$extra,
        ]);
    }

    public function test_obdoc_devuelve_documento_con_original_y_metadatos(): void
    {
        $data = app(TramitadorApiResponseReader::class)->leer('OBDOC', $this->obtenerDocumento(589403))['data'];

        $this->assertSame(589403, $data['id_doc_s']);
        $this->assertSame('Informe_tecnico_final.pdf', $data['nombre']);
        $this->assertSame('52DBEE34DBF7C31201B2', $data['csv']);
        $this->assertStringStartsWith('%PDF', (string) base64_decode((string) $data['original_contenido']));
        $this->assertNull($data['caratula_contenido']);
        $this->assertSame('PDF', $data['metadatos']['formato']);
        $this->assertSame('TD14', $data['metadatos']['tipo_documental']);
        $this->assertSame('52DBEE34DBF7C31201B2', $data['metadatos']['csv_s']);
    }

    public function test_obdoc_acepta_el_csv_como_identificador(): void
    {
        $respuesta = $this->obtenerDocumento('370DAB14F5D87C68F4CA');

        $this->assertSame(589402, $respuesta->json('data.id_doc'));
    }

    public function test_obdoc_omite_bloques_no_solicitados(): void
    {
        $respuesta = $this->obtenerDocumento(589401, ['original' => false, 'metadatos' => false]);

        $this->assertArrayNotHasKey('original_contenido', $respuesta->json('data'));
        $this->assertArrayNotHasKey('metadatos', $respuesta->json('data'));
    }

    public function test_obdoc_documento_inexistente_devuelve_404(): void
    {
        $this->assertSame(404, $this->obtenerDocumento(404)->status());
    }

    /**
     * @return array<string, array{string, array<string, mixed>, string}>
     */
    public static function peticionesProvider(): array
    {
        $base = ['dir3_emisor' => 'LA0001721', 'ref_emisor' => 'REF-1', 'id_usu_tramitador' => 'usuarioprp01'];

        return [
            'ICDEXP' => ['ICDEXP', $base + ['csv' => 'CSV-ABC123'], '94'],
            'ROFEXP' => ['ROFEXP', $base + [
                'id_rol' => 'JEFE',
                'contenido' => base64_encode('PDF'), 'nombre' => 'informe.pdf',
                'callback_url' => 'https://mi-app.test/callback',
            ], '96'],
        ];
    }

    /**
     * @param  array<string, mixed>  $datos
     */
    #[DataProvider('peticionesProvider')]
    public function test_peticiones_en_expediente_abierto_devuelven_id_peticion(string $codigo, array $datos, string $prefijo): void
    {
        $respuesta = app(TramitadorApiOperationService::class)->ejecutar($codigo, $datos + ['id_exp' => 11190]);

        $this->assertSame(200, $respuesta->status());
        $this->assertSame($prefijo.'11190', $respuesta->json('data.id_peticion'));
    }

    /**
     * @param  array<string, mixed>  $datos
     */
    #[DataProvider('peticionesProvider')]
    public function test_peticiones_en_expediente_cerrado_devuelven_409(string $codigo, array $datos): void
    {
        $respuesta = app(TramitadorApiOperationService::class)->ejecutar($codigo, $datos + ['id_exp' => 11184]);

        $this->assertSame(409, $respuesta->status());
    }

    public function test_rofexp_rechaza_contenido_que_no_es_base64(): void
    {
        $this->expectException(ValidationException::class);

        app(TramitadorApiOperationService::class)->ejecutar('ROFEXP', [
            'dir3_emisor' => 'LA0001721', 'id_exp' => 11190, 'ref_emisor' => 'R', 'id_usu_tramitador' => 'u',
            'id_rol' => 'JEFE', 'contenido' => '%%%no-base64%%%', 'nombre' => 'a.pdf',
            'callback_url' => 'https://mi-app.test/callback',
        ]);
    }

    public function test_nimexp_crea_peticion_de_firma(): void
    {
        $respuesta = app(TramitadorApiOperationService::class)->ejecutar('NIMEXP', [
            'dir3_emisor' => 'LA0001721', 'id_exp' => 11190, 'ref_emisor' => 'R', 'concepto' => 'Firma',
            'id_usu_tramitador' => 'u',
            'id_rol' => 'JEFE', 'tipo' => 'F', 'orden' => 1,
            'contenido' => base64_encode('PDF'), 'nombre' => 'a.pdf', 'tipo_documental' => 'TD01',
        ]);

        $this->assertSame('9511190', $respuesta->json('data.id_peticion'));
        Http::assertSent(fn (Request $r): bool => str_ends_with($r->url(), '/nimb') && $r['firmantes']['id_rol'] === 'JEFE' && $r['documentos']['metadatos']['tipo_documental'] === 'TD01');
    }

    public function test_peticiones_creadas_se_consultan_con_su_info(): void
    {
        $servicio = app(TramitadorApiOperationService::class);
        $ruta = ['dir3_emisor' => 'LA0001721', 'id_exp' => 11190];

        $nimb = $servicio->ejecutar('INFNIMB', $ruta + ['id_peticion' => 9511190]);
        $rof = $servicio->ejecutar('INFROF', $ruta + ['id_peticion' => 9611190]);

        $this->assertSame(11190, $nimb->json('data.id_exp'));
        $this->assertSame(9511190, $nimb->json('data.id_peticion'));
        $this->assertSame(11190, $rof->json('data.id_exp'));
        $this->assertSame(9611190, $rof->json('data.id_peticion'));
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Informes;

use App\DTOs\Informes\InformeExpedientesFiltrosData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Informes\ImprimirInformeExpedientesRequest;
use App\Models\User;
use App\Policies\InformeExpedientesPolicy;
use App\Services\Informes\InformeExpedientesService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;

/**
 * Controlador invocable que genera la versión imprimible del informe
 * agrupado de expedientes.
 *
 * Lo expone la ruta GET /informes/expedientes/imprimir
 * (nombre `informes.expedientes.imprimir`, routes\web.php, middleware auth).
 * Valida los filtros con `ImprimirInformeExpedientesRequest`, autoriza con
 * `InformeExpedientesPolicy` y delega el cálculo en `InformeExpedientesService`,
 * devolviendo la vista `informes.expedientes.imprimir`.
 */
final class ImprimirInformeExpedientesController extends Controller
{
    /**
     * Genera el informe agrupado aplicando los filtros validados
     * y devuelve la vista imprimible con el resultado.
     */
    public function __invoke(
        ImprimirInformeExpedientesRequest $request,
        InformeExpedientesService $service,
        InformeExpedientesPolicy $policy,
    ): View {
        $user = $request->user();

        if (! $user instanceof User || ! $policy->viewAny($user)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        /** @var array<string, mixed> $validated */
        $validated = $request->validated();

        $filtros = InformeExpedientesFiltrosData::fromArray($validated);
        $resultado = $service->generar($filtros);

        return view('informes.expedientes.imprimir', [
            'resultado' => $resultado,
            'titulo' => 'Informe agrupado de expedientes',
        ]);
    }
}

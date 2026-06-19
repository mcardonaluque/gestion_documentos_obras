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

final class ImprimirInformeExpedientesController extends Controller
{
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

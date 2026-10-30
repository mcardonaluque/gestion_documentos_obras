<?php

declare(strict_types=1);

namespace App\Http\Requests\Informes;

use App\Enums\InformeExpedientesAgrupacion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

/**
 * Valida los filtros del informe imprimible de expedientes agrupados.
 *
 * La usa `ImprimirInformeExpedientesController` (ruta GET
 * /informes/expedientes/imprimir, routes\web.php). Comprueba rango de
 * años, estado, equipo y el modo de agrupación contra las opciones del
 * enum `InformeExpedientesAgrupacion`.
 */
final class ImprimirInformeExpedientesRequest extends FormRequest
{
    /**
     * Autoriza la petición a cualquier usuario autenticado; la
     * autorización fina la aplica después `InformeExpedientesPolicy`.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Reglas de validación de los filtros del informe.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'anio_desde' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'anio_hasta' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'cod_estado' => ['nullable', 'string', 'max:50'],
            'team_id' => ['nullable', 'integer', 'exists:teams,id'],
            'agrupacion' => [
                'required',
                'string',
                Rule::in(array_keys(InformeExpedientesAgrupacion::options())),
            ],
        ];
    }

    /**
     * Validación adicional: el año "hasta" no puede ser anterior al año "desde".
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $desde = $this->integer('anio_desde');
            $hasta = $this->integer('anio_hasta');

            if ($desde > 0 && $hasta > 0 && $desde > $hasta) {
                $validator->errors()->add('anio_hasta', 'El año hasta debe ser mayor o igual al año desde.');
            }
        });
    }
}

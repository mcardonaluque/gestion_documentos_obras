<?php

declare(strict_types=1);

namespace App\Http\Requests\Informes;

use App\Enums\InformeExpedientesAgrupacion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

final class ImprimirInformeExpedientesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
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

<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Form request de scaffolding para la actualización de `ImportesPorOrganismo`.
 *
 * Solo lo referencia `ImportesPorOrganismoController::update()`, que no
 * tiene rutas registradas; por ello `authorize()` devuelve false y
 * `rules()` está vacío. El reparto por organismo se gestiona en Filament.
 */
class UpdateImportesPorOrganismoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}

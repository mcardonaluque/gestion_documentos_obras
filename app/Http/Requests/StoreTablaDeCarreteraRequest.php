<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Form request de scaffolding para la creación de `TablaDeCarretera`.
 *
 * Solo lo referencia `TablaDeCarreteraController::store()`, que no tiene
 * rutas registradas; por ello `authorize()` devuelve false y `rules()`
 * está vacío. Las carreteras se mantienen como catálogo de solo lectura.
 */
class StoreTablaDeCarreteraRequest extends FormRequest
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

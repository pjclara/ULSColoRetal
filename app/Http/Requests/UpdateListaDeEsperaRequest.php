<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateListaDeEsperaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'prioridade_id' => 'nullable|integer',
            'diagnostico_ids' => 'array',
            'responsavel_id' => 'nullable|integer',
            'comentarios' => 'nullable|string',
            'estado_lista_espera' => 'nullable|string',
            'cancelar_lista_espera' => 'nullable|boolean',
            'data_de_lista' => 'nullable|date',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCentroDeReferenciaRequest extends FormRequest
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
            'utente_id' => ['required', 'exists:utentes,id'],
            'data_de_diagnostico' => ['required', 'date'],
            'data_de_referenciacao' => ['required', 'date'],
            'origem_id' => ['required', 'exists:origem_da_referenciacaos,id'],
            'data_de_entrada' => ['nullable', 'date'],
            'data_de_saida' => ['nullable', 'date'],
            'destino_id' => ['nullable', 'exists:destinos,id'],
            'responsavel_id' => ['nullable', 'exists:users,id'],
            'comentarios' => ['nullable', 'string'],
        ];
    }
}

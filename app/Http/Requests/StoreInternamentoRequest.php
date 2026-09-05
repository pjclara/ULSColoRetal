<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreInternamentoRequest extends FormRequest
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
            'cama' => ['nullable', 'string'],
            'data_de_entrada' => ['required', 'date'],
            'data_de_alta' => ['nullable', 'date'],
            'data_de_saida' => ['nullable', 'date'],
            'origem_do_internamento_id' => ['nullable', 'exists:origem_do_internamentos,id'],
            'estado_da_alta_id' => ['nullable', 'exists:estado_da_altas,id'],
            'motivo_internamento' => ['nullable', 'string'],
            'observacoes' => ['nullable', 'string'],
            'responsavel_id' => ['nullable', 'exists:users,id'],
            'clavien_dindo_id' => ['nullable', 'exists:clavien_dindos,id'],
            'destino_id' => ['nullable', 'exists:destinos,id'],
            'caso_social_id' => ['nullable', 'exists:caso_socials,id'],
            'localizacao_id' => ['nullable', 'exists:localizacaos,id'],
            'complicacao_ids' => ['nullable', 'array'],
            'complicacao_ids.*' => ['integer', 'exists:complicacaos,id'],
        ];
    }
}

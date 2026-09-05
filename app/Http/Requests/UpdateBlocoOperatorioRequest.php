<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBlocoOperatorioRequest extends FormRequest
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
            'data_de_inicio' => ['required', 'date'],
            'tipo_de_cirurgia_id' => ['required', 'integer', 'exists:tipo_de_cirurgias,id'],
            'resseccao_de_orgao' => ['nullable', 'in:1,2,99'],
            're_intervencao_nao_programada_id' => ['required', 'integer', 'exists:re_intervencao_nao_programadas,id'],
            'tipo_de_abordagem_id' => ['required', 'integer', 'exists:tipo_de_abordagems,id'],
            'causa_de_conversao' => ['nullable', 'string', 'max:255'],
            'comentarios' => ['nullable', 'string'],
            'intervencao_ids' => ['nullable', 'array'],
            'intervencao_ids.*' => ['integer', 'exists:intervencaos,id'],
        ];
    }
}

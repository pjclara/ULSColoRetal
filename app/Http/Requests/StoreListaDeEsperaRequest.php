<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreListaDeEsperaRequest extends FormRequest
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
            'utente_id' => ['required', 'integer', 'exists:utentes,id'],
            'data_de_lista' => ['required', 'date'],
            // Ignorado: ao inscrever em lista o estado fica sempre "Pendente" (ver ListaDeEsperaService::create).
            'estado_lista_espera' => ['sometimes', 'in:1,2,3,4'],
            'cancelar_lista_espera' => ['nullable', 'boolean'],
            'comentarios' => ['nullable', 'string'],
            'responsavel_id' => ['required', 'integer', 'exists:users,id'],
            'diagnostico_ids' => ['nullable', 'array'],
            'diagnostico_ids.*' => ['integer', 'exists:diagnosticos,id'],
        ];
    }
}

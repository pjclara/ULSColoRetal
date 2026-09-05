<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAgendamentoRequest extends FormRequest
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
            'lista_de_espera_id' => ['sometimes', 'integer', 'exists:lista_de_esperas,id'],
            'start' => ['required', 'date'],
            // 'end' acompanha sempre 'start' (ver AgendamentoService::withMatchingEnd) — não é pedido ao cliente.
            'end' => ['nullable', 'date'],
            'responsavel_id' => ['required', 'integer', 'exists:users,id'],
            'tipo_de_agendamento_id' => ['required', 'integer', 'exists:tipo_de_agendamentos,id'],
            'local_de_agendamento_id' => ['required', 'integer', 'exists:local_de_agendamentos,id'],
            'sala_de_agendamento_id' => ['required', 'integer', 'exists:sala_de_agendamentos,id'],
            'periodo_de_agendamento_id' => ['required', 'integer'],
            'estado_de_agendamento_id' => ['nullable', 'integer', 'exists:estado_de_agendamentos,id'],
            'estado_de_agendamento' => ['nullable', 'integer', 'exists:estado_de_agendamentos,id'],
            'comentarios' => ['nullable', 'string'],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreIntervencaoDescricaoRequest extends FormRequest
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
            'intervencao_id' => ['required', 'integer', 'exists:bloco_operatorio_intervencao,id'],
            'anastemose_modo_id' => ['required', 'integer', 'exists:anastemose_modos,id'],
            'anastemose_via_id' => ['required', 'integer', 'exists:anastemose_vias,id'],
            'anastemose_sentido_id' => ['required', 'integer', 'exists:anastemose_sentidos,id'],
            'tipo_de_reconstrucao_id' => ['required', 'integer', 'exists:tipo_de_reconstrucaos,id'],
            'reforco_anastemose' => ['required', 'in:1,2,99'],
            'localizacao_anastemose_id' => ['required', 'integer', 'exists:localizacao_anastemoses,id'],
            'confirmacao_anastemose_id' => ['required', 'integer', 'exists:confirmacao_anastemoses,id'],
            'libertacao_angulo' => ['required', 'in:1,2,99'],
            'numero_cargas' => ['nullable', 'integer'],
            'distancia_linha_pectinea_anastemose' => ['nullable', 'integer'],
            'distancia_linha_pectinea_tumor' => ['nullable', 'integer'],
            'qualidade_peca_operatoria_id' => ['nullable', 'integer', 'exists:qualidade_peca_operatorias,id'],
            'comentarios' => ['nullable', 'string'],
        ];
    }
}

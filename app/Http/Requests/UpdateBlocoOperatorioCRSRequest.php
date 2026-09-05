<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBlocoOperatorioCRSRequest extends FormRequest
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
            'experiencia_cirurgiao_id' => ['nullable', 'integer'],
            'asa' => ['nullable', 'integer'],
            'mortalidade' => ['nullable', 'numeric'],
            'score_fisiologico' => ['nullable', 'integer'],
            'score_gravidade_cirurgico' => ['nullable', 'integer'],
            'preparacao_intestinal_id' => ['nullable', 'integer'],
            'intencao_id' => ['required', 'integer', 'exists:intencaos,id'],
            'paleativa_causa' => ['nullable', 'string'],
            'duracao' => ['required', 'integer'],
            'estoma_de_protecao_id' => ['required', 'integer', 'exists:estoma_de_protecaos,id'],
            'local_extracao_peca_id' => ['required', 'integer', 'exists:local_extracao_pecas,id'],
            'tipo_de_dreno_id' => ['required', 'integer', 'exists:tipo_de_drenos,id'],
            'resseccao_multi_orgao' => ['required', 'in:1,2,99'],
            'resseccao_multi_orgao_quais' => ['nullable', 'string'],
            'aderencia_id' => ['required', 'integer', 'exists:aderencias,id'],
            'tipo_de_resseccao_id' => ['required', 'integer', 'exists:tipo_de_resseccaos,id'],
            'neoplasia_residual_id' => ['required', 'integer', 'exists:neoplasia_residuals,id'],
            'perdas_hematica_id' => ['required', 'integer', 'exists:perdas_hematicas,id'],
            'transfusao_intra_operatoria' => ['required', 'in:1,2,99'],
            'unidades_globulos' => ['nullable', 'integer'],
            'protector_de_parede' => ['required', 'in:1,2,99'],
            'complicacoes' => ['required', 'in:1,2,99'],
            'complicacoes_quais' => ['nullable', 'string'],
        ];
    }
}

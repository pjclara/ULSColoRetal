<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUtenteRequest extends FormRequest
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
            'nome' => ['required', 'string', 'max:255'],
            'numero_utente' => ['required', 'numeric'],
            'numero_processo' => ['required', 'numeric'],
            'data_nascimento' => ['required', 'date'],
            'sexo_id' => ['required', 'integer', 'exists:sexos,id'],
            'concelho_id' => ['required', 'integer', 'exists:concelhos,id'],
        ];
    }
}

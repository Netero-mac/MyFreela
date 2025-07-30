<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a fazer esta requisição.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->project);
    }

    public function messages(): array
    {
        return [
            'deadline.after_or_equal' => 'A data do prazo final não pode ser uma data no passado.',
            'value.gt' => 'O valor do projeto, se informado, deve ser maior que zero.',
        ];
    }

    /**
     * Obtém as regras de validação que se aplicam à requisição.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client_id' => [
                'required',
                Rule::exists('clients', 'id')->where('user_id', auth()->id())
            ],
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'nullable|date|after_or_equal:today',
            'value' => 'nullable|numeric|gt:0' 
        ];
    }
}
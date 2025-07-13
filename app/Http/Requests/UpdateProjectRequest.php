<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // Adicione esta linha

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Pega o projeto da rota (ex: /projects/{project})
        $project = $this->route('project');

        // Usa a ProjectPolicy para verificar se o usuário logado pode atualizar este projeto
        return $this->user()->can('update', $project);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Validação de segurança crucial:
            // Garante que o client_id exista na tabela 'clients' E que
            // o 'user_id' desse cliente seja o do utilizador autenticado.
            'client_id' => [
                'required',
                Rule::exists('clients', 'id')->where('user_id', auth()->id())
            ],
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'nullable|date',
        ];
    }
}
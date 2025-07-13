<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
{
    // netero-mac/myfreela/Netero-mac-MyFreela-a4c5b535674ff2ff79ece93d0deb8a90ae4a3614/app/Http/Requests/UpdateProjectRequest.php
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
            //
        ];
    }
}

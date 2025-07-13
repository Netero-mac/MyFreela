<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // Adicione esta linha

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Pega o cliente da rota (ex: /clients/{client})
        $client = $this->route('client');

        // Usa a ClientPolicy para verificar se o usuário logado pode atualizar este cliente
        return $this->user()->can('update', $client);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Pega o cliente que está sendo atualizado a partir da rota
        $client = $this->route('client');

        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                // Garante que o email é único, ignorando o ID do cliente atual
                Rule::unique('clients')->ignore($client->id),
            ],
            'phone' => 'nullable|string|max:20',
        ];
    }
}
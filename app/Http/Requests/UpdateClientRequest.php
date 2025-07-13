<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
{
    // netero-mac/myfreela/Netero-mac-MyFreela-a4c5b535674ff2ff79ece93d0deb8a90ae4a3614/app/Http/Requests/UpdateClientRequest.php
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
        return [
            //
        ];
    }
}

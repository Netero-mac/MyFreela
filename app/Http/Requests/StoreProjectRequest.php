<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    // Em app/Http/Requests/StoreProjectRequest.php

    public function rules(): array
    {
        return [
            'client_id' => [
                'required',
                \Illuminate\Validation\Rule::exists('clients', 'id')->where('user_id', auth()->id())
            ],
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'nullable|date',
        ];
    }
}

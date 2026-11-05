<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssuntoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'Descricao' => ['required', 'string', 'max:20'],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LivroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $anoAtual = now()->year;

        return [
            'Titulo' => ['required', 'string', 'max:40'],
            'Editora' => ['nullable', 'string', 'max:40'],
            'Edicao' => ['nullable', 'integer', 'min:1'],
            'AnoPublicacao' => ['nullable', 'bail', 'digits:4', 'numeric', 'min:1000', 'max:'.$anoAtual],
            'Valor' => ['required', 'numeric', 'min:0'],
            'autores' => ['nullable', 'array'],
            'autores.*' => ['integer', 'exists:autor,CodAu'],
            'assuntos' => ['nullable', 'array'],
            'assuntos.*' => ['integer', 'exists:assunto,codAs'],
        ];
    }

    public function messages(): array
    {
        return [
            'AnoPublicacao.digits' => 'O ano de publicação deve ter 4 dígitos.',
            'AnoPublicacao.numeric' => 'O ano de publicação deve ser numérico.',
            'AnoPublicacao.min' => 'O ano de publicação deve ser maior ou igual a :min.',
            'AnoPublicacao.max' => 'O ano de publicação não pode ser maior que :max.',
        ];
    }
}

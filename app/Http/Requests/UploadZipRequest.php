<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadZipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Ajuste se quiser restrições de acesso
    }

    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:zip', 'max:10240'], // 10MB de limite, ajuste conforme necessário
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'O arquivo ZIP é obrigatório.',
            'file.file' => 'O campo deve ser um arquivo válido.',
            'file.mimes' => 'O arquivo deve estar no formato .zip',
            'file.max' => 'O tamanho máximo permitido para o arquivo é de 10MB.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form request for validating teacher (formador) data.
 *
 * This request handles validation for creating and updating teachers,
 * ensuring data integrity and consistency.
 */
class TeacherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $id = $this->route('id');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                // Garantir unicidade, ignorando o registro atual se estiver atualizando
                'unique:teachers,email,' . $id
            ],
            'gender' => [
                'required',
                'string',
                'in:Masculino,Feminino',
            ],
            'specialization' => [
                'required',
                'string',
                'max:255',
            ],
            'number_of_identify' => [
                'required',
                'string',
                'max:50',
                // Garantir unicidade, ignorando o registro atual se estiver atualizando
                'unique:teachers,number_of_identify,' . $id
            ],
            'phone' => [
                'required',
                'string',
                'max:20',
                // Alterado para string para acomodar códigos de país e zeros à esquerda
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:2048', // 2MB max
            ],
        ];
    }

    /**
     * Get custom error messages for validation.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'name.required' => 'O nome do formador é obrigatório.',
            'name.string' => 'O nome do formador deve ser um texto.',
            'name.max' => 'O nome do formador não pode exceder 255 caracteres.',
            'email.required' => 'O e-mail do formador é obrigatório.',
            'email.email' => 'O e-mail fornecido não é válido.',
            'email.unique' => 'Este e-mail já está em uso.',
            'gender.required' => 'O gênero do formador é obrigatório.',
            'gender.in' => 'O gênero deve ser Masculino ou Feminino.',
            'specialization.required' => 'A especialização do formador é obrigatória.',
            'specialization.string' => 'A especialização deve ser um texto.',
            'specialization.max' => 'A especialização não pode exceder 255 caracteres.',
            'number_of_identify.required' => 'O número de identificação é obrigatório.',
            'number_of_identify.string' => 'O número de identificação deve ser um texto.',
            'number_of_identify.max' => 'O número de identificação não pode exceder 50 caracteres.',
            'number_of_identify.unique' => 'Este número de identificação já está em uso.',
            'phone.required' => 'O telefone do formador é obrigatório.',
            'phone.string' => 'O telefone deve ser um texto (para aceitar códigos de país).',
            'phone.max' => 'O telefone não pode exceder 20 caracteres.',
            'image.image' => 'O arquivo enviado deve ser uma imagem.',
            'image.mimes' => 'A imagem deve ser no formato: jpeg, png, jpg, gif, svg.',
            'image.max' => 'O tamanho da imagem não pode exceder 2MB.',
        ];
    }
}

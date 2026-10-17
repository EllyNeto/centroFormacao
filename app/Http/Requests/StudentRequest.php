<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form request for validating student (formando) data.
 *
 * This request handles validation for creating and updating students,
 * ensuring data integrity and consistency.
 */
class StudentRequest extends FormRequest
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
            'enrollment_id' => [
                'required',
                'exists:enrollments,id',
                // Ensure uniqueness - one enrollment can only have one student
                'unique:students,enrollment_id,' . $id
            ],
            'room_id' => [
                'required',
                'exists:rooms,id',
            ],
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
            ],
            'number_of_identify' => [
                'required',
                'string',
                'max:50',
            ],
            'phone' => [
                'required',
                'string',
                'max:20',
                // Changed to string to accommodate country codes and leading zeros
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:4096', // 4MB max
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
            'enrollment_id.required' => 'A inscrição é obrigatória.',
            'enrollment_id.exists' => 'A inscrição selecionada não existe.',
            'enrollment_id.unique' => 'Esta inscrição já possui um formando associado.',
            'room_id.required' => 'A turma é obrigatória.',
            'room_id.exists' => 'A turma selecionada não existe.',
            'name.required' => 'O nome do formando é obrigatório.',
            'name.string' => 'O nome do formando deve ser um texto.',
            'name.max' => 'O nome do formando não pode exceder 255 caracteres.',
            'email.required' => 'O e-mail do formando é obrigatório.',
            'email.string' => 'O e-mail do formando deve ser um texto.',
            'email.email' => 'O e-mail fornecido não é válido.',
            'email.max' => 'O e-mail do formando não pode exceder 255 caracteres.',
            'number_of_identify.required' => 'O número de identificação é obrigatório.',
            'number_of_identify.string' => 'O número de identificação deve ser um texto.',
            'number_of_identify.max' => 'O número de identificação não pode exceder 50 caracteres.',
            'phone.required' => 'O telefone do formando é obrigatório.',
            'phone.string' => 'O telefone deve ser um texto (para aceitar códigos de país).',
            'phone.max' => 'O telefone não pode exceder 20 caracteres.',
            'image.image' => 'O arquivo enviado deve ser uma imagem.',
            'image.mimes' => 'A imagem deve ser no formato: jpeg, png, jpg, webp.',
            'image.max' => 'O tamanho da imagem não pode exceder 4MB.',
        ];
    }
}

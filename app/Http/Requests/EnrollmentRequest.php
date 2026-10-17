<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form request for validating enrollment (inscrição) data.
 *
 * This request handles validation for creating and updating enrollments,
 * ensuring data integrity and consistency.
 */
class EnrollmentRequest extends FormRequest
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
            ],
            'phone' => [
                'required',
                'string',
                'max:20',
                // Alterado para string para acomodar códigos de país e zeros à esquerda
            ],
            'number_of_identify' => [
                'required',
                'string',
                'max:50',
            ],
            'course_id' => [
                'required',
                'exists:courses,id',
            ],
            'shift' => [
                'required',
                'string',
                'in:Manhã,Tarde,Pós-Laboral',
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:4096', // 4MB max
            ],
            // Nota: room_id é tratado especialmente no controller
            // Pode ser nulo para inscrições em lista de espera
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
            'name.required' => 'O nome do candidato é obrigatório.',
            'name.string' => 'O nome do candidato deve ser um texto.',
            'name.max' => 'O nome do candidato não pode exceder 255 caracteres.',
            'email.required' => 'O e-mail do candidato é obrigatório.',
            'email.string' => 'O e-mail do candidato deve ser um texto.',
            'email.email' => 'O e-mail fornecido não é válido.',
            'email.max' => 'O e-mail do candidato não pode exceder 255 caracteres.',
            'phone.required' => 'O telefone do candidato é obrigatório.',
            'phone.string' => 'O telefone deve ser um texto (para aceitar códigos de país).',
            'phone.max' => 'O telefone não pode exceder 20 caracteres.',
            'number_of_identify.required' => 'O número de identificação é obrigatório.',
            'number_of_identify.string' => 'O número de identificação deve ser um texto.',
            'number_of_identify.max' => 'O número de identificação não pode exceder 50 caracteres.',
            'course_id.required' => 'O curso é obrigatório.',
            'course_id.exists' => 'O curso selecionado não existe.',
            'shift.required' => 'O turno é obrigatório.',
            'shift.in' => 'O turno deve ser Manhã, Tarde ou Pós-Laboral.',
            'image.image' => 'O arquivo enviado deve ser uma imagem.',
            'image.mimes' => 'A imagem deve ser no formato: jpeg, png, jpg, webp.',
            'image.max' => 'O tamanho da imagem não pode exceder 4MB.',
        ];
    }
}

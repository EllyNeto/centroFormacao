<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form request for validating course data.
 *
 * This request handles validation for creating and updating courses,
 * ensuring data integrity and consistency.
 */
class CourseRequest extends FormRequest
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
                // Ensure uniqueness, ignoring the current record if updating
                'unique:courses,name,' . $id
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'duration' => [
                'required',
                'integer',
                'min:1',
            ],
            'status' => [
                'required',
                'boolean',
            ],
            'value' => [
                'required',
                'numeric',
                'min:0',
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
            'name.required' => 'O nome do curso é obrigatório.',
            'name.string' => 'O nome do curso deve ser um texto.',
            'name.max' => 'O nome do curso não pode exceder 255 caracteres.',
            'name.unique' => 'Este nome de curso já está em uso.',
            'description.string' => 'A descrição deve ser um texto.',
            'duration.required' => 'A duração do curso é obrigatória.',
            'duration.integer' => 'A duração deve ser um número inteiro.',
            'duration.min' => 'A duração deve ser maior que zero.',
            'status.required' => 'O status do curso é obrigatório.',
            'status.boolean' => 'O status deve ser um valor booleano.',
            'value.required' => 'O valor do curso é obrigatório.',
            'value.numeric' => 'O valor deve ser um número.',
            'value.min' => 'O valor do curso não pode ser negativo.',
        ];
    }
}

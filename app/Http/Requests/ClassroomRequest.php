<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

/**
 * Form request for validating classroom (sala) data.
 *
 * This request handles validation for creating and updating classrooms,
 * ensuring data integrity and consistency.
 */
class ClassroomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // In a real application, you would check permissions here
        // For now, we allow all authenticated users
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        // Get the classroom ID if we're updating (from route parameter)
        $id = $this->route('id');

        return [
            'number_of_classroom' => [
                'required',
                'integer',
                'min:1',
                // Ensure uniqueness, ignoring the current record if updating
                'unique:classrooms,number_of_classroom,' . $id
            ],
            'capacity' => [
                'required',
                'integer',
                'min:1',
                // Capacity must be at least 1
            ],
            'description' => [
                'nullable',
                'string',
                'max:500',
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
            'number_of_classroom.required' => 'O número da sala é obrigatório.',
            'number_of_classroom.integer' => 'O número da sala deve ser um número inteiro.',
            'number_of_classroom.min' => 'O número da sala deve ser maior que zero.',
            'number_of_classroom.unique' => 'Este número de sala já está em uso.',
            'capacity.required' => 'A capacidade da sala é obrigatória.',
            'capacity.integer' => 'A capacidade deve ser um número inteiro.',
            'capacity.min' => 'A capacidade deve ser maior que zero.',
            'description.max' => 'A descrição não pode exceder 500 caracteres.',
        ];
    }
}

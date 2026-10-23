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
        // Em uma aplicação real, você verificaria as permissões aqui
        // Por enquanto, permitimos todos os usuários autenticados
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        // Obtém o ID da sala se estiver atualizando (do parâmetro da rota)
        $id = $this->route('id');

        return [
            'number_of_classroom' => [
                'required',
                'integer',
                'min:1',
                // Garantir unicidade, ignorando o registro atual se estiver atualizando
                'unique:classrooms,number_of_classroom,' . $id
            ],
            'capacity' => [
                'required',
                'integer',
                'min:1',
                // A capacidade deve ser pelo menos 1
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

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form request for validating room (turma) data.
 *
 * This request handles validation for creating and updating rooms,
 * ensuring data integrity and consistency.
 */
class RoomRequest extends FormRequest
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
            'course_id' => [
                'required',
                'exists:courses,id',
            ],
            'teacher_id' => [
                'required',
                'exists:teachers,id',
            ],
            'classroom_id' => [
                'required',
                'exists:classrooms,id',
                // Classroom is now required (not nullable) as per ideal structure
            ],
            'shift' => [
                'required',
                'string',
                'in:Manhã,Tarde,Pós-Laboral',
            ],
            'days_of_week' => [
                'required',
                'array',
                'min:1',
            ],
            'days_of_week.*' => [
                'string',
                'in:Segunda-feira,Terça-feira,Quarta-feira,Quinta-feira,Sexta-feira,Sábado',
            ],
            'start_time' => [
                'required',
                'date_format:H:i',
            ],
            'end_time' => [
                'required',
                'date_format:H:i',
                'after:start_time',
            ],
            'start_date' => [
                'nullable',
                'date',
            ],
            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],
            // Nota: max_capacity não é validado aqui pois é definido pelo controller
            // com base na capacidade da sala de aula
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
            'name.required' => 'O nome da turma é obrigatório.',
            'name.string' => 'O nome da turma deve ser um texto.',
            'name.max' => 'O nome da turma não pode exceder 255 caracteres.',
            'course_id.required' => 'O curso é obrigatório.',
            'course_id.exists' => 'O curso selecionado não existe.',
            'teacher_id.required' => 'O formador é obrigatório.',
            'teacher_id.exists' => 'O formador selecionado não existe.',
            'classroom_id.required' => 'A sala de aula é obrigatória.',
            'classroom_id.exists' => 'A sala de aula selecionada não existe.',
            'shift.required' => 'O turno é obrigatório.',
            'shift.in' => 'O turno deve ser Manhã, Tarde ou Pós-Laboral.',
            'days_of_week.required' => 'Os dias da semana são obrigatórios.',
            'days_of_week.array' => 'Os dias da semana devem ser fornecidos como um array.',
            'days_of_week.min' => 'Pelo menos um dia da semana deve ser selecionado.',
            'days_of_week.*.string' => 'Cada dia da semana deve ser um texto.',
            'days_of_week.*.in' => 'Cada dia deve ser um dia da semana válido.',
            'start_time.required' => 'A hora de início é obrigatória.',
            'start_time.date_format' => 'A hora de início deve estar no formato HH:MM.',
            'end_time.required' => 'A hora de término é obrigatória.',
            'end_time.date_format' => 'A hora de término deve estar no formato HH:MM.',
            'end_time.after' => 'A hora de término deve ser depois da hora de início.',
            'start_date.date' => 'A data de início deve ser uma data válida.',
            'end_date.date' => 'A data de término deve ser uma data válida.',
            'end_date.after_or_equal' => 'A data de término deve ser igual ou posterior à data de início.',
        ];
    }
}

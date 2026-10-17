<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form request for validating payment data.
 *
 * This request handles validation for creating and updating payments,
 * ensuring data integrity and consistency.
 */
class PaymentRequest extends FormRequest
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
            ],
            'type_of_payment' => [
                'required',
                'string',
                'in:Transferência,Multicaixa,Numerário',
            ],
            'value' => [
                // Note: In the actual system, value is set by the controller
                // based on the course price, not from the form
                // But we'll keep validation for completeness
                'required',
                'numeric',
                'min:0',
            ],
            'currency' => [
                'required',
                'string',
                'size:3',
                // Changed to string with fixed size
            ],
            'reference' => [
                'required',
                'string',
                'max:100',
                // Changed to string to allow alphanumeric references
                // Ensure uniqueness, ignoring the current record if updating
                'unique:payments,reference,' . $id,
            ],
            'status' => [
                'required',
                'string',
                'in:Pendente,Confirmado,Anulado',
            ],
            'date' => [
                'required',
                'date',
            ],
            // Note: cancellation_reason is required when status is Anulado
            // This is handled with conditional validation rules
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
            'type_of_payment.required' => 'O tipo de pagamento é obrigatório.',
            'type_of_payment.in' => 'O tipo de pagamento deve ser Transferência, Multicaixa ou Numerário.',
            'value.required' => 'O valor do pagamento é obrigatório.',
            'value.numeric' => 'O valor deve ser um número.',
            'value.min' => 'O valor do pagamento não pode ser negativo.',
            'currency.required' => 'A moeda é obrigatória.',
            'currency.string' => 'A moeda deve ser um texto.',
            'currency.size' => 'A moeda deve ter exatamente 3 caracteres (ex: AOA).',
            'reference.required' => 'A referência é obrigatória.',
            'reference.string' => 'A referência deve ser um texto.',
            'reference.max' => 'A referência não pode exceder 100 caracteres.',
            'reference.unique' => 'Esta referência já está em uso.',
            'status.required' => 'O status do pagamento é obrigatório.',
            'status.in' => 'O status deve ser Pendente, Confirmado ou Anulado.',
            'date.required' => 'A data do pagamento é obrigatória.',
            'date.date' => 'A data fornecida não é válida.',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->sometimes('cancellation_reason', 'required|string|max:500', function ($input) {
            return $input->status === 'Anulado';
        });
    }
}

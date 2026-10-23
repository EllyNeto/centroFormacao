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

        // Caso seja atualização de um pagamento existente (PUT /pagamento/update/{id})
        if ($id) {
            $payment = \App\Models\Payment::find($id);

            // Se o pagamento já está Confirmado, o formulário apenas submete o motivo de anulação (R8)
            if ($payment && $payment->isConfirmed()) {
                return [
                    'cancellation_reason' => [
                        'required',
                        'string',
                        'max:500',
                    ],
                ];
            }

            // Edição de pagamento Pendente
            return [
                'type_of_payment' => [
                    'required',
                    'string',
                    'in:Transferência,Multicaixa,Numerário',
                ],
                'reference' => [
                    'required',
                    'string',
                    'max:100',
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
                'cancellation_reason' => [
                    'nullable',
                    'string',
                    'max:500',
                ],
            ];
        }

        // Registo de novo pagamento (POST /pagamento/store)
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
                // O valor é calculado e fixado pelo PaymentController com base no preço do curso (R5)
                'nullable',
                'numeric',
                'min:0',
            ],
            'currency' => [
                // Moeda assume 'AOA' por omissão no controlador
                'nullable',
                'string',
                'size:3',
            ],
            'reference' => [
                'required',
                'string',
                'max:100',
                'unique:payments,reference',
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
            'value.numeric' => 'O valor deve ser um número.',
            'value.min' => 'O valor do pagamento não pode ser negativo.',
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
            'cancellation_reason.required' => 'O motivo da anulação é obrigatório.',
            'cancellation_reason.max' => 'O motivo da anulação não pode exceder 500 caracteres.',
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

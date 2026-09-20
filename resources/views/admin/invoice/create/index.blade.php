@extends('layouts.main')

@section('title', 'Emitir Nova Fatura')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Emitir Nova Fatura</h4>
                        <a href="{{ route('payment.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left me-1"></i> Voltar à Listagem de Pagamentos
                        </a>
                    </div>

                    <form action="{{ route('invoice.store') }}" method="POST">
                        @csrf

                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger alert-alt alert-dismissible fade show mb-4" role="alert">
                                    <strong>Erro!</strong> Por favor verifique os seguintes problemas:
                                    <ul class="mb-0 mt-1 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <div class="row">
                                {{-- Coluna Esquerda: Dados do Pagamento, Formando e Inscrição Associada --}}
                                <div class="col-xl-6 col-sm-6">
                                    @if(isset($selectedPayment) && $selectedPayment)
                                        {{-- Exibição dos Dados do Pagamento Associado (Sem Necessidade de Selects Repetitivos) --}}
                                        <input type="hidden" name="payment_id" value="{{ $selectedPayment->id }}">
                                        <input type="hidden" name="enrollment_id" value="{{ $selectedEnrollment->id ?? ($selectedPayment->enrollment_id ?? '') }}">
                                        <input type="hidden" name="course_id" value="{{ $selectedEnrollment->course_id ?? ($selectedPayment->enrollment->course_id ?? '') }}">
                                        <input type="hidden" id="payment_method" value="{{ $selectedPayment->payment_method ?? '' }}">

                                        <div class="card border p-3 shadow-none mb-3" style="background-color: #f8fafc; border-radius: 12px;">
                                            <h6 class="text-primary font-w600 mb-3">
                                                <i class="fa fa-credit-card me-2"></i> Detalhes do Pagamento Associado
                                            </h6>

                                            <p class="mb-2 fs-14">
                                                <strong>Registo de Pagamento:</strong> 
                                                <span class="text-primary font-w600">#PAG-{{ sprintf('%04d', $selectedPayment->id) }} — {{ $selectedPayment->type_of_payment }}</span>
                                            </p>
                                            
                                            <p class="mb-2 fs-14">
                                                <strong>Formando:</strong> 
                                                <span class="text-dark font-w600">{{ $selectedPayment->student->name ?? ($selectedEnrollment->student->name ?? 'N/D') }}</span>
                                            </p>

                                            <p class="mb-0 fs-14">
                                                <strong>Curso Associado:</strong> 
                                                <span class="text-dark font-w600">{{ $selectedEnrollment->course->name ?? ($selectedPayment->enrollment->course->name ?? ($selectedPayment->student && $selectedPayment->student->enrollments->first() ? $selectedPayment->student->enrollments->first()->course->name : 'N/D')) }}</span>
                                            </p>
                                        </div>
                                    @else
                                        {{-- Seleção Manual de Pagamento (caso o utilizador aceda diretamente sem parâmetro) --}}
                                        <div class="mb-3">
                                            <label for="payment_id" class="form-label text-primary font-w600">Registo de Pagamento Associado <span class="text-danger">*</span></label>
                                            <select id="payment_id" name="payment_id" class="form-control select2-single" required>
                                                <option value="">Selecione um Pagamento...</option>
                                                @foreach($payments as $payment)
                                                    <option value="{{ $payment->id }}" 
                                                            data-value="{{ number_format($payment->value, 2, '.', '') }}"
                                                            data-enrollment-id="{{ $payment->enrollment_id ?? '' }}"
                                                            data-course-id="{{ $payment->enrollment->course_id ?? '' }}"
                                                            data-course-name="{{ $payment->enrollment->course->name ?? '' }}"
                                                            {{ old('payment_id', $selectedPaymentId ?? '') == $payment->id ? 'selected' : '' }}>
                                                        #PAG-{{ sprintf('%04d', $payment->id) }} - {{ $payment->student->name ?? 'Formando' }} ({{ $payment->type_of_payment }} — {{ number_format($payment->value, 2, ',', '.') }} Kz)
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="enrollment_id" id="enrollment_id" value="{{ old('enrollment_id', $selectedEnrollmentId ?? '') }}">
                                            <input type="hidden" name="course_id" id="course_id" value="{{ old('course_id') }}">
                                        </div>

                                        <div class="mb-3">
                                            <label for="course_name_display" class="form-label text-primary font-w600">Curso Associado</label>
                                            <input type="text" id="course_name_display" class="form-control" placeholder="Selecione um pagamento para preencher o curso" readonly disabled>
                                        </div>
                                    @endif
                                </div>

                                {{-- Coluna Direita: Valor a Pagar, Valor Recebido, Troco e Valor em Falta --}}
                                <div class="col-xl-6 col-sm-6">
                                    {{-- Valor Total a Pagar (Proveniente do Pagamento) --}}
                                    <div class="mb-3">
                                        <label for="amount_to_pay" class="form-label text-primary font-w600">Valor a Pagar (Soma dos Emolumentos) (Kz) <span class="text-danger">*</span></label>
                                        <input type="text" id="amount_to_pay" name="amount_to_pay" class="form-control font-w600 currency-input" value="{{ old('amount_to_pay', number_format($selectedPayment->value ?? 0, 2, ',', '.')) }}" readonly>
                                        <small class="text-muted">Valor total cobrado no registo de pagamento.</small>
                                    </div>

                                    {{-- Valor Recebido (Digitado pelo Funcionário no Ato da Emissão) --}}
                                    <div class="mb-3">
                                        <label for="amount_paid" class="form-label text-primary font-w600">Valor Recebido (Kz) <span class="text-danger">*</span></label>
                                        <input type="text" id="amount_paid" name="amount_paid" class="form-control font-w600 text-primary currency-input" value="{{ old('amount_paid', number_format($selectedPayment->value ?? 0, 2, ',', '.')) }}" placeholder="0,00" required>
                                        <small class="text-muted">Digite o valor entregue pelo formando.</small>
                                    </div>

                                     {{-- Campo de Troco ou Saldo Presente na Conta Calculado --}}
                                     <div class="mb-3">
                                         <label for="change_display" id="change_label" class="form-label text-success font-w600">
                                             @if(isset($selectedPayment) && !empty($selectedPayment->payment_method) && !str_contains(strtolower($selectedPayment->payment_method), 'numerári') && !str_contains(strtolower($selectedPayment->payment_method), 'numerari'))
                                                 Saldo Presente na Conta
                                             @else
                                                 Troco a Devolver
                                             @endif
                                         </label>
                                         <input type="text" id="change_display" class="form-control font-w600 text-success" readonly value="0.00 Kz">
                                     </div>

                                    {{-- Campo de Valor em Falta --}}
                                    <div class="mb-3">
                                        <label for="remaining_display" class="form-label text-danger font-w600">Valor em Falta (Falta Pagar)</label>
                                        <input type="text" id="remaining_display" class="form-control font-w600 text-danger" readonly value="0.00 Kz (Sem Pendência)">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <a href="{{ route('payment.index') }}" class="btn btn-danger light me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-file-text me-1"></i> Emitir Fatura
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/invoice-form.js') }}"></script>
@endpush
@endsection

@extends('layouts.main')

@section('title', 'Editar Fatura')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Editar Fatura: #FAT-{{ sprintf('%04d', $invoice->id) }}</h4>
                        <a href="{{ route('invoice.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left me-1"></i> Voltar à Listagem
                        </a>
                    </div>

                    <form action="{{ route('invoice.update', $invoice->id) }}" method="POST">
                        @csrf
                        @method('PUT')

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
                                <div class="col-xl-6 col-sm-6">
                                    {{-- Inscrição Associada (Inalterável / Preenchida Automaticamente) --}}
                                    <div class="mb-3">
                                        <label for="enrollment_display" class="form-label text-primary font-w600">Inscrição Associada <span class="text-danger">*</span></label>
                                        @php
                                            $enrollmentObj = $invoice->enrollment;
                                            $enrollmentText = $enrollmentObj 
                                                ? '#INS-' . sprintf('%04d', $enrollmentObj->id) . ' - ' . ($enrollmentObj->student->name ?? 'Estudante') . ' (' . ($enrollmentObj->course->name ?? 'Curso') . ')'
                                                : 'N/D';
                                        @endphp
                                        <input type="text" id="enrollment_display" class="form-control font-w600 text-dark" value="{{ $enrollmentText }}" style="background-color: #e9ecef !important; cursor: not-allowed; border: 1px solid #cbd5e1;" readonly disabled>
                                        <input type="hidden" name="enrollment_id" value="{{ $invoice->enrollment_id }}">
                                    </div>

                                    {{-- Curso Associado (Inalterável / Preenchido Automaticamente a partir da Inscrição) --}}
                                    <div class="mb-3">
                                        <label for="course_name_display" class="form-label text-primary font-w600">Curso Associado</label>
                                        <input type="text" id="course_name_display" class="form-control font-w600 text-dark" value="{{ $invoice->course->name ?? ($invoice->enrollment->course->name ?? 'N/D') }}" style="background-color: #e9ecef !important; cursor: not-allowed; border: 1px solid #cbd5e1;" readonly disabled>
                                        <input type="hidden" name="course_id" id="course_id" value="{{ $invoice->course_id }}">
                                    </div>

                                    {{-- Registo de Pagamento Associado (Inalterável / Preenchido Automaticamente) --}}
                                    <div class="mb-3">
                                        <label for="payment_display" class="form-label text-primary font-w600">Registo de Pagamento Associado</label>
                                        @php
                                            $paymentObj = $invoice->payment;
                                            $paymentText = $paymentObj 
                                                ? '#PAG-' . sprintf('%04d', $paymentObj->id) . ' - ' . $paymentObj->type_of_payment . ' (' . number_format($paymentObj->value, 2, ',', '.') . ' Kz)'
                                                : 'N/D';
                                        @endphp
                                        <input type="text" id="payment_display" class="form-control font-w600 text-dark" value="{{ $paymentText }}" style="background-color: #e9ecef !important; cursor: not-allowed; border: 1px solid #cbd5e1;" readonly disabled>
                                        <input type="hidden" name="payment_id" value="{{ $invoice->payment_id }}">
                                    </div>
                                </div>

                                <div class="col-xl-6 col-sm-6">
                                    {{-- Valor Total a Pagar (Soma dos Emolumentos) --}}
                                    <div class="mb-3">
                                        <label for="amount_to_pay" class="form-label text-primary font-w600">Valor a Pagar (Soma dos Emolumentos) (Kz) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" min="0" id="amount_to_pay" name="amount_to_pay" class="form-control font-w600" value="{{ old('amount_to_pay', $invoice->amount_to_pay) }}" required>
                                        <small class="text-muted">Valor total cobrado pelos emolumentos.</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="amount_paid" class="form-label text-primary font-w600">Valor Pago (Recebido) (Kz) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" min="0" id="amount_paid" name="amount_paid" class="form-control font-w600 text-primary" value="{{ old('amount_paid', $invoice->amount_paid) }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="change_display" class="form-label text-success font-w600">Saldo a Favor / Crédito</label>
                                        <input type="text" id="change_display" class="form-control font-w600 text-success" readonly value="{{ number_format($invoice->change, 2, '.', '') }} Kz">
                                    </div>

                                    <div class="mb-3">
                                        <label for="remaining_display" class="form-label text-danger font-w600">Valor em Falta (Falta Pagar)</label>
                                        <input type="text" id="remaining_display" class="form-control font-w600 text-danger" readonly value="0.00 Kz">
                                    </div>

                                    <div class="mb-3">
                                        <label for="created_at" class="form-label text-primary">Data de Emissão</label>
                                        <input type="text" class="form-control" id="created_at" value="{{ $invoice->created_at ? $invoice->created_at->format('d/m/Y H:i') : 'N/D' }}" readonly disabled>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <a href="{{ route('invoice.index') }}" class="btn btn-danger light me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Atualizar Fatura</button>
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

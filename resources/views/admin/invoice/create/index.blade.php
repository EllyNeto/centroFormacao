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
                        <a href="{{ route('invoice.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left me-1"></i> Voltar à Listagem
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
                                {{-- Coluna Esquerda: Inscrição, Curso Automático e Pagamento --}}
                                <div class="col-xl-6 col-sm-6">
                                    <div class="mb-3">
                                        <label for="enrollment_id" class="form-label text-primary">Inscrição Associada <span class="text-danger">*</span></label>
                                        <select id="enrollment_id" name="enrollment_id" class="default-select wide form-control" required>
                                            <option value="">Selecione uma Inscrição...</option>
                                            @foreach($enrollments as $enrollment)
                                                <option value="{{ $enrollment->id }}" 
                                                        data-course-id="{{ $enrollment->course_id }}"
                                                        data-course-name="{{ $enrollment->course->name ?? '' }}"
                                                        {{ old('enrollment_id') == $enrollment->id ? 'selected' : '' }}>
                                                    #INS-{{ sprintf('%04d', $enrollment->id) }} - {{ $enrollment->student->name ?? 'Estudante' }} ({{ $enrollment->course->name ?? 'Curso' }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Curso do Estudante (Preenchido Automaticamente a partir da Inscrição) --}}
                                    <div class="mb-3">
                                        <label for="course_name_display" class="form-label text-primary">Curso Associado (Automático)</label>
                                        <input type="text" id="course_name_display" class="form-control" placeholder="Selecione a inscrição para preencher o curso" readonly disabled>
                                        <input type="hidden" name="course_id" id="course_id" value="{{ old('course_id') }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="payment_id" class="form-label text-primary">Registo de Pagamento Associado</label>
                                        <select id="payment_id" name="payment_id" class="default-select wide form-control">
                                            <option value="">Selecione um Pagamento (Opcional)</option>
                                            @foreach($payments as $payment)
                                                <option value="{{ $payment->id }}" 
                                                        data-value="{{ number_format($payment->value, 2, '.', '') }}"
                                                        {{ old('payment_id') == $payment->id ? 'selected' : '' }}>
                                                    #PAG-{{ sprintf('%04d', $payment->id) }} - {{ $payment->type_of_payment }} ({{ number_format($payment->value, 2, ',', '.') }} Kz)
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Ao selecionar um pagamento, o valor pago é preenchido automaticamente.</small>
                                    </div>
                                </div>

                                {{-- Coluna Direita: Valor Pago, Troco e Valor em Falta --}}
                                <div class="col-xl-6 col-sm-6">
                                    <div class="mb-3">
                                        <label for="amount_paid" class="form-label text-primary">Valor Pago (Recebido) (Kz) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" min="0" id="amount_paid" name="amount_paid" class="form-control" value="{{ old('amount_paid', '0.00') }}" placeholder="0.00" required>
                                    </div>

                                    {{-- Campo de Troco --}}
                                    <div class="mb-3">
                                        <label for="change_display" class="form-label text-success font-w600">Troco a Devolver</label>
                                        <input type="text" id="change_display" class="form-control font-w600 text-success" readonly value="0.00 Kz">
                                    </div>

                                    {{-- Campo de Valor em Falta --}}
                                    <div class="mb-3">
                                        <label for="remaining_display" class="form-label text-danger font-w600">Valor em Falta (Falta Pagar)</label>
                                        <input type="text" id="remaining_display" class="form-control font-w600 text-danger" readonly value="0.00 Kz">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <a href="{{ route('invoice.index') }}" class="btn btn-danger light me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Emitir Fatura</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const enrollmentSelect  = document.getElementById('enrollment_id');
        const courseInput       = document.getElementById('course_id');
        const courseNameDisplay = document.getElementById('course_name_display');
        const paymentSelect     = document.getElementById('payment_id');
        const amountPaidInput   = document.getElementById('amount_paid');
        const changeDisplay     = document.getElementById('change_display');
        const remainingDisplay  = document.getElementById('remaining_display');

        // Preenchimento automático do curso ao selecionar a inscrição
        function updateCourseFromEnrollment() {
            if (!enrollmentSelect) return;
            const selectedOption = enrollmentSelect.options[enrollmentSelect.selectedIndex];
            if (selectedOption) {
                const courseId   = selectedOption.getAttribute('data-course-id');
                const courseName = selectedOption.getAttribute('data-course-name');
                if (courseId && courseInput) {
                    courseInput.value = courseId;
                }
                if (courseNameDisplay) {
                    courseNameDisplay.value = courseName || '';
                }
            }
        }

        if (enrollmentSelect) {
            enrollmentSelect.addEventListener('change', updateCourseFromEnrollment);
            updateCourseFromEnrollment();
        }

        // Preenchimento automático do valor pago ao selecionar o registo de pagamento
        if (paymentSelect) {
            paymentSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const paymentVal = selectedOption.getAttribute('data-value');
                if (paymentVal !== null && paymentVal !== undefined && amountPaidInput) {
                    amountPaidInput.value = paymentVal;
                    updateCalculations();
                }
            });
        }

        // Cálculo de Troco e Valor em Falta
        function updateCalculations() {
            if (!amountPaidInput) return;
            const paid = parseFloat(amountPaidInput.value) || 0;
            // Quando não há um valor a pagar separado no formulário, a exibição fica 0.00 Kz para troco/pendência
            changeDisplay.value    = '0.00 Kz';
            remainingDisplay.value = '0.00 Kz (Sem Pendência)';
        }

        if (amountPaidInput) {
            amountPaidInput.addEventListener('input', updateCalculations);
            updateCalculations();
        }
    });
</script>
@endpush
@endsection

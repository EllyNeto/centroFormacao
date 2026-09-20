{{-- Extende o layout principal da aplicação --}}
@extends('layouts.main')

{{-- Define o título dinâmico da página no navegador --}}
@section('title', 'Editar Pagamento')

{{-- Conteúdo principal da página de edição de pagamento --}}
@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    {{-- Cabeçalho do cartão com o título da ação contendo o ID do pagamento e botão para voltar --}}
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0">Editar Pagamento: #PAG-{{ sprintf('%04d', $payment->id) }}</h4>
                            <p class="m-0 subtitle">Atualize a associação ao estudante, emolumentos e estado da transação</p>
                        </div>
                        <a href="{{ route('payment.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left me-1"></i> Voltar à Listagem
                        </a>
                    </div>

                    {{-- Formulário de submissão para atualizar o pagamento existente --}}
                    <form action="{{ route('payment.update', $payment->id) }}" method="POST">
                        {{-- Proteção CSRF e simulação do método HTTP PUT --}}
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            {{-- Exibição de alertas caso existam erros de validação submetidos --}}
                            @if ($errors->any())
                                <div class="alert alert-danger alert-alt alert-dismissible fade show mb-4" role="alert">
                                    <div>
                                        <strong>Erro!</strong> Por favor, verifique os erros abaixo ao atualizar o pagamento:
                                        <ul class="mb-0 mt-2 ps-3">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <div class="row">
                                {{-- Coluna Esquerda: Associação do Formando, Emolumentos e Valores --}}
                                <div class="col-xl-6 col-sm-6">

                                    {{-- Campo: Formando Associado (Select2 com Pesquisa) --}}
                                    <div class="mb-3">
                                        <label for="student_id" class="form-label text-primary font-w600">Formando <span class="text-danger">*</span></label>
                                        @php
                                            $studentIdVal = old('student_id', $payment->student_id);
                                        @endphp
                                        <select id="student_id" name="student_id" class="form-control select2-student" required>
                                            <option value="">Pesquise e selecione o formando...</option>
                                            @foreach($students as $student)
                                                <option value="{{ $student->id }}" data-balance="{{ $student->balance ?? 0 }}" {{ $studentIdVal == $student->id ? 'selected' : '' }}>
                                                    {{ $student->name }} (Cód: {{ $student->code ?? $student->id }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Cartão Dinâmico de Exibição do Saldo Acumulado do Formando --}}
                                    <div id="student_balance_card" class="card border border-primary p-3 mb-3 shadow-none {{ $currentStudent && $currentStudent->balance > 0 ? '' : 'd-none' }}" style="background-color: #f0f7ff; border-radius: 12px;">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                                            <div class="d-flex align-items-center">
                                                <i class="fa fa-wallet text-primary me-2 fs-16"></i>
                                                <strong class="text-primary font-w600 fs-14">Saldo de Crédito Disponível:</strong>
                                            </div>
                                            <span id="current_balance_text" class="badge badge-success light font-w700 fs-14 py-2 px-3" style="border-radius: 8px;">
                                                {{ number_format($currentStudent->balance ?? 0, 2, ',', '.') }} Kz
                                            </span>
                                        </div>
                                        <div class="border-top pt-2 mt-1">
                                            <small class="text-muted d-block" style="font-size: 12px; line-height: 1.4;">
                                                O formando pode utilizar este crédito acumulado em futuros emolumentos ou gerar novo saldo ao pagar a mais.
                                            </small>
                                        </div>
                                    </div>

                                    {{-- Campo: Seleção Múltipla de Emolumentos --}}
                                    <div class="mb-3">
                                        <label class="form-label text-primary font-w600">Selecione os Emolumentos <span class="text-danger">*</span></label>
                                        <div class="card p-3 border shadow-none mb-2" style="background-color: #f8fafc; border-radius: 10px;">
                                            <div class="row">
                                                {{-- Emolumento: Inscrição --}}
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input emolumento-check" type="checkbox" value="Inscrição" data-price="15000.00" id="emol_1">
                                                        <label class="form-check-label font-w500" for="emol_1">
                                                            Inscrição — <strong>15.000,00 Kz</strong>
                                                        </label>
                                                    </div>
                                                </div>

                                                {{-- Emolumento: Valor do Curso --}}
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input emolumento-check" type="checkbox" value="Valor do Curso" data-price="35000.00" id="emol_2">
                                                        <label class="form-check-label font-w500" for="emol_2">
                                                            Valor do Curso — <strong>35.000,00 Kz</strong>
                                                        </label>
                                                    </div>
                                                </div>

                                                {{-- Emolumento: Cartão do Formando --}}
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input emolumento-check" type="checkbox" value="Cartão do Formando" data-price="3000.00" id="emol_3">
                                                        <label class="form-check-label font-w500" for="emol_3">
                                                            Cartão do Formando — <strong>3.000,00 Kz</strong>
                                                        </label>
                                                    </div>
                                                </div>

                                                {{-- Emolumento: Certificado --}}
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input emolumento-check" type="checkbox" value="Certificado" data-price="10000.00" id="emol_4">
                                                        <label class="form-check-label font-w500" for="emol_4">
                                                            Certificado — <strong>10.000,00 Kz</strong>
                                                        </label>
                                                    </div>
                                                </div>

                                                {{-- Emolumento: Exame de Recurso --}}
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input emolumento-check" type="checkbox" value="Exame de Recurso" data-price="8000.00" id="emol_5">
                                                        <label class="form-check-label font-w500" for="emol_5">
                                                            Exame de Recurso — <strong>8.000,00 Kz</strong>
                                                        </label>
                                                    </div>
                                                </div>

                                                {{-- Emolumento: Outro Emolumento --}}
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input emolumento-check" type="checkbox" value="Outro Emolumento" data-price="0.00" id="emol_6">
                                                        <label class="form-check-label font-w500" for="emol_6">
                                                            Outro Emolumento
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="type_of_payment" id="type_of_payment" value="{{ old('type_of_payment', $payment->type_of_payment) }}" required>
                                        <small class="text-muted d-block">Pode selecionar um ou mais emolumentos em simultâneo.</small>
                                    </div>

                                    {{-- Campo: Valor do Pagamento --}}
                                    <div class="mb-3">
                                        <label for="value" class="form-label text-primary font-w600">Valor Total (Kz) <span class="text-danger">*</span></label>
                                        <input type="text" id="value" name="value" class="form-control currency-input" value="{{ old('value', number_format($payment->value, 2, ',', '.')) }}" required>
                                    </div>

                                    {{-- Campo: Forma de Pagamento --}}
                                    <div class="mb-3">
                                        <label for="payment_method" class="form-label text-primary font-w600">Forma de Pagamento <span class="text-danger">*</span></label>
                                        <select id="payment_method" name="payment_method" class="default-select wide form-control" required>
                                            <option value="Numerário" {{ old('payment_method', $payment->payment_method ?? 'Numerário') == 'Numerário' ? 'selected' : '' }}>Numerário</option>
                                            <option value="Cartão" {{ old('payment_method', $payment->payment_method) == 'Cartão' ? 'selected' : '' }}>Cartão / TPA</option>
                                            <option value="Transferência" {{ old('payment_method', $payment->payment_method) == 'Transferência' ? 'selected' : '' }}>Transferência Bancária</option>
                                        </select>
                                    </div>

                                    {{-- Campo: Moeda --}}
                                    <div class="mb-3">
                                        <label for="currency" class="form-label text-primary font-w600">Moeda <span class="text-danger">*</span></label>
                                        <select id="currency" name="currency" class="default-select wide form-control" required>
                                            <option value="AOA" {{ old('currency', $payment->currency) == 'AOA' ? 'selected' : '' }}>AOA - Kwanza (Kz)</option>
                                            <option value="USD" {{ old('currency', $payment->currency) == 'USD' ? 'selected' : '' }}>USD - Dólar ($)</option>
                                            <option value="EUR" {{ old('currency', $payment->currency) == 'EUR' ? 'selected' : '' }}>EUR - Euro (€)</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Coluna Direita: Referência, Estado e Data do Pagamento --}}
                                <div class="col-xl-6 col-sm-6">
                                    {{-- Campo: Número de Referência --}}
                                    <div class="mb-3">
                                        <label for="reference" class="form-label text-primary font-w600">Número de Referência</label>
                                        <input type="text" id="reference" class="form-control" value="{{ $payment->reference }}" readonly disabled>
                                    </div>

                                    {{-- Campo: Estado do Pagamento --}}
                                    <div class="mb-3">
                                        <label class="form-label text-primary font-w600 d-block">Estado do Pagamento</label>
                                        <div class="mt-1">
                                            <span class="badge badge-success light fs-14 py-2 px-3"><i class="fa fa-check-circle me-1"></i> Concluído / Pago </span>
                                        </div>
                                        <input type="hidden" name="status" value="1">
                                        <small class="text-muted d-block mt-1">Os pagamentos mantêm o estado de liquidação concluída.</small>
                                    </div>

                                    {{-- Campo: Data e Hora do Pagamento --}}
                                    <div class="mb-3">
                                        <label for="date" class="form-label text-primary font-w600">Data e Hora do Pagamento</label>
                                        <input type="text" id="date" class="form-control" value="{{ $payment->date ? date('d/m/Y H:i', strtotime($payment->date)) : 'N/D' }}" readonly disabled>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Rodapé do cartão com os botões de ação para Atualizar ou Cancelar --}}
                        <div class="card-footer text-end">
                            <a href="{{ route('payment.index') }}" class="btn btn-danger light me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save me-1"></i> Atualizar Pagamento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/payment-form.js') }}"></script>
@endpush
@endsection
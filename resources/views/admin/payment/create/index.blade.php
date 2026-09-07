{{-- Extende o layout principal unificado da aplicação --}}
@extends('layouts.main')

{{-- Define o título dinâmico da página no navegador --}}
@section('title', 'Adicionar Novo Pagamento')

{{-- Conteúdo principal da página de criação de pagamento --}}
@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    {{-- Cabeçalho do cartão com o título da ação e o botão para regressar à lista --}}
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Adicionar Novo Pagamento</h4>
                        <a href="{{ route('payment.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left me-1"></i> Voltar à Listagem
                        </a>
                    </div>
                    
                    {{-- Formulário de submissão para guardar um novo pagamento --}}
                    <form action="{{ route('payment.store') }}" method="POST">
                        {{-- Diretiva CSRF obrigatória do Laravel para validação de segurança do formulário --}}
                        @csrf
                        
                        <div class="card-body">
                            {{-- Exibição do painel de erros de validação caso algum campo não cumpra as regras --}}
                            @if ($errors->any())
                                <div class="alert alert-danger alert-alt alert-dismissible fade show mb-4" role="alert">
                                    <div>
                                        <strong>Erro!</strong> Por favor corrija os erros no formulário:
                                        <ul class="mb-0 mt-1 ps-3">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <div class="row">
                                {{-- Coluna Esquerda: Tipo de Emolumento, Valor, Forma de Pagamento e Moeda --}}
                                <div class="col-xl-6 col-sm-6">
                                    {{-- Campo: Tipo de Emolumento (Select com preços automáticos) --}}
                                    <div class="mb-3">
                                        <label for="type_of_payment" class="form-label text-primary">Tipo de Emolumento <span class="text-danger">*</span></label>
                                        <select id="type_of_payment" name="type_of_payment" class="default-select wide form-control" required>
                                            <option value="">Selecione o Emolumento...</option>
                                            <option value="Inscrição" data-price="15000.00" {{ old('type_of_payment') == 'Inscrição' ? 'selected' : '' }}>Inscrição - 15.000,00 Kz</option>
                                            <option value="Propina Mensal" data-price="35000.00" {{ old('type_of_payment') == 'Propina Mensal' ? 'selected' : '' }}>Propina Mensal - 35.000,00 Kz</option>
                                            <option value="Matrícula / Confirmação" data-price="20000.00" {{ old('type_of_payment') == 'Matrícula / Confirmação' ? 'selected' : '' }}>Matrícula / Confirmação - 20.000,00 Kz</option>
                                            <option value="Certificado" data-price="10000.00" {{ old('type_of_payment') == 'Certificado' ? 'selected' : '' }}>Certificado - 10.000,00 Kz</option>
                                            <option value="Declaração" data-price="5000.00" {{ old('type_of_payment') == 'Declaração' ? 'selected' : '' }}>Declaração - 5.000,00 Kz</option>
                                            <option value="Cartão de Estudante" data-price="3000.00" {{ old('type_of_payment') == 'Cartão de Estudante' ? 'selected' : '' }}>Cartão de Estudante - 3.000,00 Kz</option>
                                            <option value="Exame de Recurso" data-price="8000.00" {{ old('type_of_payment') == 'Exame de Recurso' ? 'selected' : '' }}>Exame de Recurso - 8.000,00 Kz</option>
                                            <option value="Outro Emolumento" data-price="0.00" {{ old('type_of_payment') == 'Outro Emolumento' ? 'selected' : '' }}>Outro Emolumento</option>
                                        </select>
                                    </div>

                                    {{-- Campo: Valor do Pagamento (Preenchido automaticamente ao selecionar o emolumento) --}}
                                    <div class="mb-3">
                                        <label for="value" class="form-label text-primary">Valor (Kz) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" min="0" id="value" name="value" class="form-control" value="{{ old('value', '0.00') }}" placeholder="Preço do emolumento..." required>
                                        <small class="text-muted">Valor atualizado automaticamente ao escolher o emolumento.</small>
                                    </div>

                                    {{-- Campo: Forma de Pagamento (Select) --}}
                                    <div class="mb-3">
                                        <label for="payment_method" class="form-label text-primary">Forma de Pagamento <span class="text-danger">*</span></label>
                                        <select id="payment_method" name="payment_method" class="default-select wide form-control" required>
                                            <option value="Numerário" {{ old('payment_method', 'Numerário') == 'Numerário' ? 'selected' : '' }}>Numerário</option>
                                            <option value="Cartão" {{ old('payment_method') == 'Cartão' ? 'selected' : '' }}>Cartão</option>
                                            <option value="Transferência" {{ old('payment_method') == 'Transferência' ? 'selected' : '' }}>Transferência</option>
                                        </select>
                                    </div>

                                    {{-- Campo: Moeda --}}
                                    <div class="mb-3">
                                        <label for="currency" class="form-label text-primary">Moeda <span class="text-danger">*</span></label>
                                        <select id="currency" name="currency" class="default-select wide form-control" required>
                                            <option value="AOA" {{ old('currency', 'AOA') == 'AOA' ? 'selected' : '' }}>AOA - Kwanza (Kz)</option>
                                            <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD - Dólar ($)</option>
                                            <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR - Euro (€)</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Coluna Direita: Referência Automática, Estado Padrão e Data do Pagamento --}}
                                <div class="col-xl-6 col-sm-6">
                                    {{-- Campo: Número de Referência (Gerado Automaticamente) --}}
                                    @php
                                        $autoReference = old('reference', rand(10000000, 99999999));
                                    @endphp
                                    <div class="mb-3">
                                        <label for="reference_display" class="form-label text-primary">Número de Referência (Gerado Automaticamente)</label>
                                        <input type="text" id="reference_display" class="form-control" value="{{ $autoReference }}" readonly disabled>
                                        <input type="hidden" name="reference" value="{{ $autoReference }}">
                                        <small class="text-muted">Gerado automaticamente pelo sistema (não alterável).</small>
                                    </div>

                                    {{-- Campo: Estado do Pagamento (Default: Pendente) --}}
                                    <div class="mb-3">
                                        <label for="status" class="form-label text-primary">Estado do Pagamento <span class="text-danger">*</span></label>
                                        <select id="status" name="status" class="default-select wide form-control" required>
                                            <option value="0" {{ old('status', '0') == '0' ? 'selected' : '' }}>Pendente / Não Pago (Padrão)</option>
                                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Concluído / Pago</option>
                                        </select>
                                        <small class="text-muted">Ao alterar para Concluído / Pago, o estado da inscrição é automaticamente atualizado.</small>
                                    </div>

                                    {{-- Campo: Data e Hora do Pagamento --}}
                                    <div class="mb-3">
                                        <label for="date" class="form-label text-primary">Data e Hora do Pagamento <span class="text-danger">*</span></label>
                                        <input type="datetime-local" id="date" name="date" class="form-control" value="{{ old('date', date('Y-m-d\TH:i')) }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Rodapé do cartão com os botões de ação para Salvar ou Cancelar --}}
                        <div class="card-footer text-end">
                            <a href="{{ route('payment.index') }}" class="btn btn-danger light me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Salvar Pagamento</button>
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
        const typeSelect = document.getElementById('type_of_payment');
        const valueInput = document.getElementById('value');

        if (typeSelect && valueInput) {
            typeSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const price = selectedOption.getAttribute('data-price');
                if (price !== null && price !== undefined) {
                    valueInput.value = price;
                }
            });
        }
    });
</script>
@endpush
@endsection
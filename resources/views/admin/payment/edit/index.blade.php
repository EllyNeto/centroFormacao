{{-- Extende o layout principal unificado da aplicação --}}
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
                    {{-- Cabeçalho do cartão com o título da ação contendo o ID do pagamento e o botão para voltar --}}
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Editar Pagamento: #PAG-{{ sprintf('%04d', $payment->id) }}</h4>
                        <a href="{{ route('payment.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left me-1"></i> Voltar à Listagem
                        </a>
                    </div>

                    {{-- Formulário de submissão para atualizar o pagamento existente --}}
                    <form action="{{ route('payment.update', $payment->id) }}" method="POST">
                        {{-- Diretiva CSRF obrigatória para proteção contra ataques CSRF no Laravel --}}
                        @csrf
                        {{-- Simulação do método HTTP PUT necessário para a rota de atualização no Laravel --}}
                        @method('PUT')

                        <div class="card-body">
                            {{-- Exibição de alertas caso existam erros de validação submetidos --}}
                            @if ($errors->any())
                                <div class="alert alert-danger alert-alt alert-dismissible fade show mb-4" role="alert">
                                    <strong>Erro!</strong> Por favor, verifique os erros abaixo ao atualizar o pagamento:
                                    <ul class="mb-0 mt-2 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <div class="row">
                                {{-- Coluna Esquerda: Tipo de Emolumento, Valor, Forma de Pagamento e Moeda --}}
                                <div class="col-xl-6 col-sm-6">
                                    {{-- Campo: Tipo de Emolumento (Select) --}}
                                    <div class="mb-3">
                                        <label for="type_of_payment" class="form-label text-primary">Tipo de Emolumento <span class="text-danger">*</span></label>
                                        <select id="type_of_payment" name="type_of_payment" class="default-select wide form-control" required>
                                            <option value="Inscrição" data-price="15000.00" {{ old('type_of_payment', $payment->type_of_payment) == 'Inscrição' ? 'selected' : '' }}>Inscrição - 15.000,00 Kz</option>
                                            <option value="Propina Mensal" data-price="35000.00" {{ old('type_of_payment', $payment->type_of_payment) == 'Propina Mensal' ? 'selected' : '' }}>Propina Mensal - 35.000,00 Kz</option>
                                            <option value="Matrícula / Confirmação" data-price="20000.00" {{ old('type_of_payment', $payment->type_of_payment) == 'Matrícula / Confirmação' ? 'selected' : '' }}>Matrícula / Confirmação - 20.000,00 Kz</option>
                                            <option value="Certificado" data-price="10000.00" {{ old('type_of_payment', $payment->type_of_payment) == 'Certificado' ? 'selected' : '' }}>Certificado - 10.000,00 Kz</option>
                                            <option value="Declaração" data-price="5000.00" {{ old('type_of_payment', $payment->type_of_payment) == 'Declaração' ? 'selected' : '' }}>Declaração - 5.000,00 Kz</option>
                                            <option value="Cartão de Estudante" data-price="3000.00" {{ old('type_of_payment', $payment->type_of_payment) == 'Cartão de Estudante' ? 'selected' : '' }}>Cartão de Estudante - 3.000,00 Kz</option>
                                            <option value="Exame de Recurso" data-price="8000.00" {{ old('type_of_payment', $payment->type_of_payment) == 'Exame de Recurso' ? 'selected' : '' }}>Exame de Recurso - 8.000,00 Kz</option>
                                            <option value="Outro Emolumento" data-price="0.00" {{ old('type_of_payment', $payment->type_of_payment) == 'Outro Emolumento' ? 'selected' : '' }}>Outro Emolumento</option>
                                        </select>
                                    </div>

                                    {{-- Campo: Valor do Pagamento --}}
                                    <div class="mb-3">
                                        <label for="value" class="form-label text-primary">Valor (Kz) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" min="0" id="value" name="value" class="form-control" value="{{ old('value', $payment->value) }}" required>
                                    </div>

                                    {{-- Campo: Forma de Pagamento --}}
                                    <div class="mb-3">
                                        <label for="payment_method" class="form-label text-primary">Forma de Pagamento <span class="text-danger">*</span></label>
                                        <select id="payment_method" name="payment_method" class="default-select wide form-control" required>
                                            <option value="Numerário" {{ old('payment_method', $payment->payment_method ?? 'Numerário') == 'Numerário' ? 'selected' : '' }}>Numerário</option>
                                            <option value="Cartão" {{ old('payment_method', $payment->payment_method) == 'Cartão' ? 'selected' : '' }}>Cartão</option>
                                            <option value="Transferência" {{ old('payment_method', $payment->payment_method) == 'Transferência' ? 'selected' : '' }}>Transferência</option>
                                        </select>
                                    </div>

                                    {{-- Campo: Moeda --}}
                                    <div class="mb-3">
                                        <label for="currency" class="form-label text-primary">Moeda <span class="text-danger">*</span></label>
                                        <select id="currency" name="currency" class="default-select wide form-control" required>
                                            <option value="AOA" {{ old('currency', $payment->currency) == 'AOA' ? 'selected' : '' }}>AOA - Kwanza (Kz)</option>
                                            <option value="USD" {{ old('currency', $payment->currency) == 'USD' ? 'selected' : '' }}>USD - Dólar ($)</option>
                                            <option value="EUR" {{ old('currency', $payment->currency) == 'EUR' ? 'selected' : '' }}>EUR - Euro (€)</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Coluna Direita: Referência, Estado e Data do Pagamento --}}
                                <div class="col-xl-6 col-sm-6">
                                    {{-- Campo: Número de Referência (Não Editável) --}}
                                    <div class="mb-3">
                                        <label for="reference" class="form-label text-primary">Número de Referência (Não Editável)</label>
                                        <input type="text" id="reference" class="form-control" value="{{ $payment->reference }}" readonly disabled>
                                    </div>

                                    {{-- Campo: Estado do Pagamento --}}
                                    <div class="mb-3">
                                        <label for="status" class="form-label text-primary">Estado do Pagamento <span class="text-danger">*</span></label>
                                        <select id="status" name="status" class="default-select wide form-control" required>
                                            <option value="0" {{ old('status', $payment->status) == '0' ? 'selected' : '' }}>Pendente / Não Pago</option>
                                            <option value="1" {{ old('status', $payment->status) == '1' ? 'selected' : '' }}>Concluído / Pago</option>
                                        </select>
                                        <small class="text-muted">Ao alterar para Concluído / Pago, o estado da inscrição é automaticamente atualizado.</small>
                                    </div>

                                    {{-- Campo: Data e Hora do Pagamento (Não Editável) --}}
                                    <div class="mb-3">
                                        <label for="date" class="form-label text-primary">Data e Hora do Pagamento (Não Editável)</label>
                                        <input type="text" id="date" class="form-control" value="{{ $payment->date ? date('d/m/Y H:i', strtotime($payment->date)) : 'N/D' }}" readonly disabled>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Rodapé do cartão com os botões de ação para Atualizar ou Cancelar --}}
                        <div class="card-footer text-end">
                            <a href="{{ route('payment.index') }}" class="btn btn-danger light me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Atualizar Pagamento</button>
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
                if (price !== null && price !== undefined && price !== "0.00") {
                    valueInput.value = price;
                }
            });
        }
    });
</script>
@endpush
@endsection
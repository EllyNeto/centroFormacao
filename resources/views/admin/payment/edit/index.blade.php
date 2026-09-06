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
                        <h4 class="card-title mb-0">Editar Pagamento: #{{ $payment->id }}</h4>
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
                                {{-- Coluna Esquerda: Tipo de Pagamento, Valor e Moeda --}}
                                <div class="col-xl-6 col-sm-6">
                                    {{-- Campo: Tipo de Pagamento --}}
                                    <div class="mb-3">
                                        <label for="type_of_payment" class="form-label text-primary">Tipo de Pagamento <span class="text-danger">*</span></label>
                                        <input type="text" id="type_of_payment" name="type_of_payment" class="form-control" value="{{ old('type_of_payment', $payment->type_of_payment) }}" required>
                                    </div>

                                    {{-- Campo: Valor do Pagamento --}}
                                    <div class="mb-3">
                                        <label for="value" class="form-label text-primary">Valor <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" min="0" id="value" name="value" class="form-control" value="{{ old('value', $payment->value) }}" required>
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
                                    {{-- Campo: Número de Referência --}}
                                    <div class="mb-3">
                                        <label for="reference" class="form-label text-primary">Número de Referência <span class="text-danger">*</span></label>
                                        <input type="number" id="reference" name="reference" class="form-control" value="{{ old('reference', $payment->reference) }}" min="1" required>
                                    </div>

                                    {{-- Campo: Estado do Pagamento --}}
                                    <div class="mb-3">
                                        <label for="status" class="form-label text-primary">Estado do Pagamento <span class="text-danger">*</span></label>
                                        <select id="status" name="status" class="default-select wide form-control" required>
                                            <option value="1" {{ old('status', $payment->status) == '1' ? 'selected' : '' }}>Concluído / Pago</option>
                                            <option value="0" {{ old('status', $payment->status) == '0' ? 'selected' : '' }}>Pendente / Cancelado</option>
                                        </select>
                                    </div>

                                    {{-- Campo: Data e Hora do Pagamento --}}
                                    <div class="mb-3">
                                        <label for="date" class="form-label text-primary">Data e Hora do Pagamento <span class="text-danger">*</span></label>
                                        <input type="datetime-local" id="date" name="date" class="form-control" value="{{ old('date', $payment->date ? date('Y-m-d\TH:i', strtotime($payment->date)) : date('Y-m-d\TH:i')) }}" required>
                                        <small class="text-muted">Data e hora do registo do pagamento.</small>
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
@endsection
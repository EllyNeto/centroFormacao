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
                                {{-- Coluna Esquerda: Tipo de Pagamento, Valor e Moeda --}}
                                <div class="col-xl-6 col-sm-6">
                                    {{-- Campo: Tipo de Pagamento --}}
                                    <div class="mb-3">
                                        <label for="type_of_payment" class="form-label text-primary">Tipo de Pagamento <span class="text-danger">*</span></label>
                                        <input type="text" id="type_of_payment" name="type_of_payment" class="form-control" value="{{ old('type_of_payment') }}" placeholder="Ex: Propinas, Matrícula, Inscrição, Emolumentos" required>
                                        <small class="text-muted">Descreva a categoria do pagamento efetuado.</small>
                                    </div>

                                    {{-- Campo: Valor do Pagamento --}}
                                    <div class="mb-3">
                                        <label for="value" class="form-label text-primary">Valor <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" min="0" id="value" name="value" class="form-control" value="{{ old('value') }}" placeholder="Ex: 50000.00" required>
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

                                {{-- Coluna Direita: Referência, Estado e Data do Pagamento --}}
                                <div class="col-xl-6 col-sm-6">
                                    {{-- Campo: Número de Referência --}}
                                    <div class="mb-3">
                                        <label for="reference" class="form-label text-primary">Número de Referência <span class="text-danger">*</span></label>
                                        <input type="number" id="reference" name="reference" class="form-control" value="{{ old('reference') }}" placeholder="Ex: 10020304" min="1" required>
                                        <small class="text-muted">Número do talão de depósito ou comprovativo bancário.</small>
                                    </div>

                                    {{-- Campo: Estado do Pagamento --}}
                                    <div class="mb-3">
                                        <label for="status" class="form-label text-primary">Estado do Pagamento <span class="text-danger">*</span></label>
                                        <select id="status" name="status" class="default-select wide form-control" required>
                                            <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Concluído / Pago</option>
                                            <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>Pendente / Cancelado</option>
                                        </select>
                                    </div>

                                    {{-- Campo: Data e Hora do Pagamento (Preenchido automaticamente com a data e hora atual do sistema) --}}
                                    <div class="mb-3">
                                        <label for="date" class="form-label text-primary">Data e Hora do Pagamento <span class="text-danger">*</span></label>
                                        <input type="datetime-local" id="date" name="date" class="form-control" value="{{ old('date', date('Y-m-d\TH:i')) }}" required>
                                        <small class="text-muted">Data e hora preenchidas automaticamente com o momento atual. Pode alterar se necessário.</small>
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
@endsection
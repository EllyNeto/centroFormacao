@extends('layout.main')

@section('title', 'Gestão de Pagamentos')

@section('content')
{{-- Estrutura principal da página de listagem de pagamentos --}}
<div class="content-body">
    <div class="container-fluid">
        {{-- Mensagens de Sucesso ou Erro do Sistema --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4">
                <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4">
                <i class="fa fa-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Painel de Métricas e Resumo Financeiro do Topo --}}
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-sm-6">
                <div class="payment-metric-card d-flex align-items-center justify-content-between h-100">
                    <div>
                        <span class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Total Liquidado</span>
                        <h4 class="mb-0 font-w700 text-dark">{{ number_format($totalCollected, 2, ',', '.') }} Kz</h4>
                    </div>
                    <div class="payment-metric-icon payment-icon-success">
                        <i class="fa fa-coins"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6">
                <div class="payment-metric-card d-flex align-items-center justify-content-between h-100">
                    <div>
                        <span class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Confirmados</span>
                        <h4 class="mb-0 font-w700 text-success">{{ $totalConfirmed }}</h4>
                    </div>
                    <div class="payment-metric-icon payment-icon-primary">
                        <i class="fa fa-check-circle"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6">
                <div class="payment-metric-card d-flex align-items-center justify-content-between h-100">
                    <div>
                        <span class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Pendentes</span>
                        <h4 class="mb-0 font-w700 text-warning">{{ $totalPending }}</h4>
                    </div>
                    <div class="payment-metric-icon payment-icon-warning">
                        <i class="fa fa-clock"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6">
                <div class="payment-metric-card d-flex align-items-center justify-content-between h-100">
                    <div>
                        <span class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Anulados</span>
                        <h4 class="mb-0 font-w700 text-danger">{{ $totalCanceled }}</h4>
                    </div>
                    <div class="payment-metric-icon payment-icon-danger">
                        <i class="fa fa-ban"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabela Principal de Pagamentos --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    {{-- Cabeçalho do Cartão com Título e Ação de Novo Pagamento --}}
                    <div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center border-bottom">
                        <div>
                            <h4 class="card-title mb-0 text-dark">Histórico de Pagamentos</h4>
                            <p class="m-0 subtitle text-muted">Controlo de liquidações, emissão de recibos e sincronização de inscrições</p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('payment.create') }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-plus me-1"></i> Registar Pagamento
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table id="example3" class="display table table-hover style-1 custom-table">
                                <thead>
                                    <tr>
                                        <th>Recibo</th>
                                        <th>Candidato</th>
                                        <th>Valor</th>
                                        <th>Estado</th>
                                        <th class="text-end">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($payments as $payment)
                                        <tr>
                                            {{-- Referência do Recibo --}}
                                            <td>
                                                <a href="{{ route('payment.show', $payment->id) }}" class="font-w600 text-primary">
                                                    {{ $payment->reference }}
                                                </a>
                                            </td>

                                            {{-- Nome do Candidato --}}
                                            <td>
                                                @if($payment->enrollment)
                                                    <a href="{{ route('enrollment.show', $payment->enrollment->id) }}" class="font-w600 text-dark d-block">
                                                        {{ $payment->enrollment->name }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">Inscrição Não Encontrada</span>
                                                @endif
                                            </td>

                                            {{-- Valor Liquidado --}}
                                            <td>
                                                <strong class="text-dark">{{ $payment->formattedValue() }}</strong>
                                            </td>

                                            {{-- Estado do Pagamento --}}
                                            <td>
                                                <span class="badge light {{ $payment->statusBadgeClass() }} font-w600">
                                                    {{ $payment->status }}
                                                </span>
                                            </td>

                                            {{-- Ações Administrativas --}}
                                            <td class="text-end">
                                                <div class="d-flex justify-content-end gap-1">
                                                    {{-- Botão de Visualização / Recibo --}}
                                                    <a href="{{ route('payment.show', $payment->id) }}" 
                                                       class="btn btn-info btn-xs sharp" 
                                                       title="Ver Recibo Oficial">
                                                        <i class="fa fa-file-invoice"></i>
                                                    </a>

                                                    {{-- Botão de Edição / Anulação (oculto para Anulado) --}}
                                                    @if(!$payment->isCanceled())
                                                        <a href="{{ route('payment.edit', $payment->id) }}" 
                                                           class="btn btn-primary btn-xs sharp" 
                                                           title="{{ $payment->isConfirmed() ? 'Anular Pagamento' : 'Editar Pagamento' }}">
                                                            <i class="fa {{ $payment->isConfirmed() ? 'fa-ban' : 'fa-pencil' }}"></i>
                                                        </a>
                                                    @endif

                                                    {{-- Botão de Eliminação (apenas para Anulado) --}}
                                                    @if($payment->isCanceled())
                                                        <button type="button" 
                                                                class="btn btn-danger btn-xs sharp" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#deleteModal{{ $payment->id }}" 
                                                                title="Eliminar Pagamento Anulado">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    @endif
                                                </div>

                                                {{-- Modal de Confirmação de Eliminação --}}
                                                <div class="modal fade" id="deleteModal{{ $payment->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered text-start">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Confirmar Eliminação</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p class="text-dark mb-2">
                                                                    Tem certeza de que pretende eliminar o registo de pagamento de referência 
                                                                    <strong>{{ $payment->reference }}</strong>?
                                                                </p>
                                                                <p class="text-muted fs-7 mb-0">
                                                                    Montante: <strong>{{ $payment->formattedValue() }}</strong> &bull; Candidato: 
                                                                    <strong>{{ $payment->enrollment ? $payment->enrollment->name : 'N/D' }}</strong>.
                                                                    <br>
                                                                    <em>Nota: Se este for o único pagamento confirmado, a inscrição reverterá automaticamente para o estado Pendente.</em>
                                                                </p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                                                                <form action="{{ route('payment.destroy', $payment->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar Pagamento</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                <i class="fa fa-info-circle me-1"></i>Nenhum pagamento registado até ao momento.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layout.main')

@section('title', 'Recibo de Pagamento - ' . $payment->reference)

@section('content')
{{-- Estrutura principal da página de detalhes e recibo de pagamento --}}
<div class="content-body">
    <div class="container-fluid">
        {{-- Barra Superior de Navegação e Ações Rápidas (Ocultada na Impressão) --}}
        <div class="row mb-4 no-print">
            <div class="col-12 d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <h4 class="mb-0 text-dark font-w700">Recibo de Pagamento</h4>
                    <p class="m-0 text-muted">Comprovativo oficial de transação financeira e liquidação de propina</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    {{-- Botão de Impressão Acionado via JS Externo --}}
                    <a href="{{ route('payment.show', $payment->id) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fa fa-print me-1"></i> Imprimir Recibo
                    </a>

                    @if($payment->enrollment)
                        <a href="{{ route('enrollment.show', $payment->enrollment->id) }}" class="btn btn-info btn-sm">
                            <i class="fa fa-user me-1"></i> Ver Candidatura
                        </a>
                    @endif

                    @if(!$payment->isCanceled())
                        <a href="{{ route('payment.edit', $payment->id) }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-pencil me-1"></i> {{ $payment->isConfirmed() ? 'Anular' : 'Editar' }}
                        </a>
                    @endif

                    <a href="{{ route('payment.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fa fa-arrow-left me-1"></i> Voltar
                    </a>
                </div>
            </div>
        </div>

        {{-- Alertas do Sistema --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 no-print">
                <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Recibo Oficial de Pagamento Formatado e Preparado para Impressão --}}
        <div class="receipt-container">
            {{-- Cabeçalho do Recibo com Fundo Escuro Nobre --}}
            <div class="receipt-header">
                <div class="row align-items-center">
                    <div class="col-sm-8 mb-3 mb-sm-0">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fa fa-university text-primary fs-3"></i>
                            <h4 class="receipt-title mb-0">CENTRO DE FORMAÇÃO PROFISSIONAL</h4>
                        </div>
                        <p class="text-light mb-0 fs-7 opacity-75">
                            Sistema Integrado de Gestão Académica e Financeira &bull; República de Angola
                        </p>
                    </div>
                    <div class="col-sm-4 text-sm-end">
                        <span class="d-block fs-8 text-uppercase tracking-wider text-light opacity-75 mb-1">Comprovativo Nº</span>
                        <span class="receipt-number">{{ $payment->reference }}</span>
                    </div>
                </div>
            </div>

            {{-- Corpo do Recibo --}}
            <div class="receipt-body">
                {{-- Carimbo Visual de Estado e Data de Registo --}}
                <div class="d-flex flex-wrap justify-content-between align-items-center pb-4 mb-4 border-bottom">
                    <div>
                        <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Data e Hora da Liquidação</small>
                        <span class="fs-6 font-w600 text-dark">
                            <i class="fa fa-calendar-check-o text-muted me-1"></i>{{ $payment->formattedDate() }}
                        </span>
                    </div>
                    <div>
                        @if($payment->isConfirmed())
                            <span class="receipt-stamp receipt-stamp-confirmed">
                                <i class="fa fa-check-circle me-1"></i> Confirmado
                            </span>
                        @elseif($payment->isPending())
                            <span class="receipt-stamp receipt-stamp-pending">
                                <i class="fa fa-clock-o me-1"></i> Pendente
                            </span>
                        @else
                            <span class="receipt-stamp receipt-stamp-canceled">
                                <i class="fa fa-times-circle me-1"></i> Anulado
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Bloco 1: Dados do Candidato / Formando --}}
                <div class="mb-4">
                    <h6 class="receipt-section-title">1. Identificação do Formando</h6>
                    @if($payment->enrollment)
                        <div class="row g-3">
                            <div class="col-md-6">
                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Nome Completo</small>
                                <span class="fs-6 font-w600 text-dark">{{ $payment->enrollment->name }}</span>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Nº de Identificação (BI)</small>
                                <span class="fs-6 text-dark">{{ $payment->enrollment->number_of_identify }}</span>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Código de Inscrição</small>
                                <span class="fs-6 font-w600 text-primary">{{ $payment->enrollment->formattedCode() }}</span>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Contacto Telefónico</small>
                                <span class="fs-6 text-dark">{{ $payment->enrollment->phone }}</span>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Correio Eletrónico</small>
                                <span class="fs-6 text-dark">{{ $payment->enrollment->email }}</span>
                            </div>
                        </div>
                    @else
                        <p class="text-muted mb-0">Inscrição desassociada ou não encontrada.</p>
                    @endif
                </div>

                {{-- Bloco 2: Discriminação do Curso e Alocação Académica --}}
                <div class="mb-4">
                    <h6 class="receipt-section-title">2. Dados Pedagógicos do Curso</h6>
                    @if($payment->enrollment && $payment->enrollment->course)
                        <div class="row g-3">
                            <div class="col-md-6">
                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Curso Formativo</small>
                                <span class="fs-6 font-w600 text-dark">{{ $payment->enrollment->course->name }}</span>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Carga Horária</small>
                                <span class="fs-6 text-dark">{{ $payment->enrollment->course->duration }} Horas</span>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Turno Solicitado</small>
                                <span class="badge light badge-primary">{{ $payment->enrollment->shift }}</span>
                            </div>
                            @if($payment->enrollment->room)
                                <div class="col-12">
                                    <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Turma Atribuída</small>
                                    <span class="fs-6 text-dark">
                                        {{ $payment->enrollment->room->name }} 
                                        ({{ $payment->enrollment->room->classroom ? 'Sala ' . $payment->enrollment->room->classroom->number_of_classroom : 'Sala N/D' }})
                                    </span>
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-muted mb-0">Informações de curso indisponíveis.</p>
                    @endif
                </div>

                {{-- Bloco 3: Destaque do Montante Liquidado --}}
                <div class="receipt-amount-box">
                    <small class="text-muted d-block text-uppercase font-w700 fs-8 mb-1 tracking-wider">
                        Montante Total Liquidado Nesta Transação
                    </small>
                    <div class="receipt-amount-display">
                        {{ $payment->formattedValue() }}
                    </div>
                    <div class="mt-2 text-muted fs-7">
                        Método: <strong><i class="{{ $payment->typeIcon() }} me-1"></i>{{ $payment->type_of_payment }}</strong> &bull; 
                        Moeda: <strong>{{ $payment->currency }}</strong>
                    </div>
                </div>

                {{-- Bloco 4: Resumo da Conta Corrente do Formando --}}
                                @if($payment->enrollment)
                    <div class="p-3 rounded bg-light border mb-4">
                        <div class="row g-2 text-center">
                            <div class="col-sm-4 border-end">
                                <small class="text-muted d-block fs-8 text-uppercase font-w600">Preço do Curso</small>
                                <span class="fs-6 font-w600 text-dark">
                                    {{ $payment->enrollment->course ? number_format($payment->enrollment->course->value, 2, ',', '.') . ' Kz' : '-' }}
                                </span>
                            </div>
                            <div class="col-sm-4 border-end">
                                <small class="text-muted d-block fs-8 text-uppercase font-w600">Total Liquidado (Ciclo {{ $payment->enrollment->cycle }})</small>
                                <span class="fs-6 font-w600 text-success">
                                    {{ number_format($payment->enrollment->totalPaid(), 2, ',', '.') }} Kz
                                </span>
                            </div>
                            <div class="col-sm-4">
                                <small class="text-muted d-block fs-8 text-uppercase font-w600">Saldo Remanescente</small>
                                <span class="fs-6 font-w700 {{ $payment->enrollment->remainingBalance() > 0 ? 'text-danger' : 'text-muted' }}">
                                    {{ number_format($payment->enrollment->remainingBalance(), 2, ',', '.') }} Kz
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Turma anterior (em caso de repetição) --}}
                    @if($payment->previous_room_id && $payment->previousRoom)
                        <div class="p-3 rounded border border-warning bg-light mb-4">
                            <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">
                                <i class="fa fa-history me-1"></i> Turma do Ciclo Anterior
                            </small>
                            <span class="fs-7 text-dark font-w600">{{ $payment->previousRoom->name }}</span>
                            @if($payment->previousRoom->course)
                                <small class="text-muted d-block">{{ $payment->previousRoom->course->name }}</small>
                            @endif
                        </div>
                    @endif

                    {{-- Motivo de anulação --}}
                    @if($payment->isCanceled() && $payment->cancellation_reason)
                        <div class="alert alert-danger mb-4">
                            <strong><i class="fa fa-ban me-1"></i> Motivo da Anulação:</strong>
                            {{ $payment->cancellation_reason }}
                            @if($payment->canceled_at)
                                <br><small class="text-muted">Anulado em {{ $payment->canceled_at->format('d/m/Y H:i') }}</small>
                            @endif
                        </div>
                    @endif
                @endif

                {{-- Bloco 5: Linhas de Assinatura para Impressão em Papel --}}
                <div class="receipt-signatures">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="signature-line"></div>
                            <small class="text-muted font-w600 text-uppercase fs-8">O Responsável Financeiro</small>
                        </div>
                        <div class="col-6">
                            <div class="signature-line"></div>
                            <small class="text-muted font-w600 text-uppercase fs-8">O Formando / Depositante</small>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <small class="text-muted fs-8">
                            Este documento é emitido por computador e serve como comprovativo oficial de pagamento.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
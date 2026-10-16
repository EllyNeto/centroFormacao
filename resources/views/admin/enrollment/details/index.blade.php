@extends('layout.main')

@section('title', 'Detalhes da Candidatura')

@section('content')
{{-- Estrutura principal da página de detalhes da candidatura --}}
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    {{-- Cabeçalho do Cartão com Título e Ações Rápidas --}}
                    <div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center border-bottom">
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <h4 class="card-title mb-0 text-dark">Candidatura: {{ $enrollment->name }}</h4>
                                <span class="badge light badge-primary font-w600">
                                    {{ $enrollment->formattedCode() }}
                                </span>
                            </div>
                            <p class="m-0 subtitle text-muted">Acompanhamento dos dados do candidato, turma atribuída e estado do processo</p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            @if($enrollment->canReceivePayment())
                                <a href="{{ route('payment.create', ['enrollment_id' => $enrollment->id]) }}" class="btn btn-success btn-sm">
                                    <i class="fa fa-money me-1"></i> {{ $enrollment->canRepeat() ? 'Registar Repetição' : 'Registar Pagamento' }}
                                </a>
                            @endif
                            <a href="{{ route('enrollment.edit', $enrollment->id) }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-pencil me-1"></i> Editar
                            </a>
                            <a href="{{ route('enrollment.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fa fa-arrow-left me-1"></i> Voltar
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        {{-- Barra Superior de Resumo do Candidato e Estado Atual --}}
                        <div class="p-3 mb-4 rounded border bg-light">
                            <div class="row align-items-center">
                                <div class="col-md-6 mb-3 mb-md-0 d-flex align-items-center">
                                    @if($enrollment->image)
                                        <img src="{{ asset('storage/' . $enrollment->image) }}" 
                                             class="candidate-avatar-sm me-3" 
                                             alt="{{ $enrollment->name }}">
                                    @else
                                        <div class="candidate-avatar-placeholder-sm me-3">
                                            <i class="fa fa-user fa-lg"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h5 class="mb-0 text-dark font-w600">{{ $enrollment->name }}</h5>
                                        <small class="text-muted">
                                            <i class="fa fa-id-card me-1"></i>{{ $enrollment->number_of_identify }} &bull;
                                            <i class="fa fa-phone ms-2 me-1"></i>{{ $enrollment->phone }}
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <span class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Estado da Candidatura</span>
                                    <span class="badge light {{ $enrollment->statusBadgeClass() }} fs-6 px-3 py-2">
                                        {{ $enrollment->status }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Grelha de Informações Detalhadas --}}
                        <div class="row g-4">
                            {{-- 1. Dados Pessoais do Candidato --}}
                            <div class="col-md-6">
                                <div class="p-4 border rounded bg-white h-100 shadow-sm">
                                    <h5 class="font-w600 mb-3 text-dark border-bottom pb-2">
                                        <i class="fa fa-user text-muted me-2"></i>Dados Pessoais do Candidato
                                    </h5>
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Nome Completo</small>
                                            <span class="fs-6 font-w600 text-dark">{{ $enrollment->name }}</span>
                                        </div>
                                        <div class="col-sm-6">
                                            <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Número do Bilhete de Identidade</small>
                                            <span class="fs-6 font-w600 text-dark">{{ $enrollment->number_of_identify }}</span>
                                        </div>
                                        <div class="col-sm-6">
                                            <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Email</small>
                                            <span class="fs-6 font-w500 text-dark">{{ $enrollment->email }}</span>
                                        </div>
                                        <div class="col-sm-6">
                                            <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Telefone</small>
                                            <span class="fs-6 font-w500 text-dark">{{ $enrollment->phone }}</span>
                                        </div>
                                        <div class="col-12">
                                            <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Data de Registo</small>
                                            <span class="fs-6 text-dark">
                                                {{ $enrollment->date ? $enrollment->date->format('d/m/Y') : ($enrollment->created_at ? $enrollment->created_at->format('d/m/Y \à\s H:i') : '-') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 2. Curso Pretendido & Valores --}}
                            <div class="col-md-6">
                                <div class="p-4 border rounded bg-white h-100 shadow-sm">
                                    <h5 class="font-w600 mb-3 text-dark border-bottom pb-2">
                                        <i class="fa fa-graduation-cap text-muted me-2"></i>Curso Pretendido
                                    </h5>
                                    @if($enrollment->course)
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Nome do Curso</small>
                                                <span class="fs-6 font-w600 text-dark">{{ $enrollment->course->name }}</span>
                                            </div>
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Carga Horária</small>
                                                <span class="fs-6 font-w600 text-dark">{{ $enrollment->course->duration }} horas</span>
                                            </div>
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Valor do Curso</small>
                                                <span class="fs-6 font-w600 text-dark">
                                                    {{ number_format($enrollment->course->value, 2, ',', '.') }} Kz
                                                </span>
                                            </div>
                                            <div class="col-12">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Turno Selecionado</small>
                                                <span class="badge light badge-primary">{{ $enrollment->shift }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-muted mb-0">Nenhum curso associado.</p>
                                    @endif
                                </div>
                            </div>

                            {{-- 3. Turma Atribuída --}}
                            <div class="col-md-6">
                                <div class="p-4 border rounded bg-white h-100 shadow-sm">
                                    <h5 class="font-w600 mb-3 text-dark border-bottom pb-2">
                                        <i class="fa fa-users text-muted me-2"></i>Turma Atribuída
                                    </h5>
                                    @if($enrollment->room)
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Nome da Turma</small>
                                                <span class="fs-6 font-w600 text-dark">{{ $enrollment->room->name }}</span>
                                            </div>
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Horário das Aulas</small>
                                                <span class="fs-6 text-dark">
                                                    {{ \Carbon\Carbon::parse($enrollment->room->start_time)->format('H:i') }} às {{ \Carbon\Carbon::parse($enrollment->room->end_time)->format('H:i') }}
                                                </span>
                                            </div>
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Sala Física</small>
                                                <span class="fs-6 text-dark">
                                                    {{ $enrollment->room->classroom ? 'Sala ' . $enrollment->room->classroom->number_of_classroom : 'Não definida' }}
                                                </span>
                                            </div>
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Formador Responsável</small>
                                                <span class="fs-6 text-dark">
                                                    {{ $enrollment->room->teacher->name ?? 'Não atribuído' }}
                                                </span>
                                            </div>
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Ocupação da Turma</small>
                                                <span class="fs-6 text-dark">
                                                    {{ $enrollment->room->occupiedSeats() }} de {{ $enrollment->room->max_capacity }} lugares
                                                </span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="p-3 bg-light rounded border">
                                            <div class="d-flex align-items-center">
                                                <i class="fa fa-info-circle fa-2x text-primary me-3"></i>
                                                <div>
                                                    <strong class="text-dark d-block mb-1">Turma Não Atribuída</strong>
                                                    <span class="text-muted fs-7">
                                                        Turno de preferência: <strong>{{ $enrollment->shift }}</strong>. A alocação da turma oficial será realizada após a confirmação do pagamento e efetivação da matrícula.
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- 4. Situação Financeira & Pagamentos --}}
                            <div class="col-md-6">
                                <div class="p-4 border rounded bg-white h-100 shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                                        <h5 class="font-w600 text-dark mb-0">
                                            <i class="fa fa-money text-muted me-2"></i>Situação Financeira
                                            @if(($enrollment->cycle ?? 1) > 1)
                                                <span class="badge badge-info ms-2 fs-8">Ciclo {{ $enrollment->cycle }}</span>
                                            @endif
                                        </h5>
                                        @if($enrollment->canReceivePayment())
                                            <a href="{{ route('payment.create', ['enrollment_id' => $enrollment->id]) }}" class="btn btn-outline-primary btn-xs">
                                                <i class="fa fa-plus me-1"></i> {{ $enrollment->canRepeat() ? 'Nova Repetição' : 'Registar Pagamento' }}
                                            </a>
                                        @endif
                                    </div>

                                    @php
                                        $coursePrice = $enrollment->course ? (float) $enrollment->course->value : 0.0;
                                        $paid = $enrollment->totalPaid();
                                        $balance = $enrollment->remainingBalance();
                                    @endphp

                                    <div class="row g-3 mb-3">
                                        <div class="col-sm-6">
                                            <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Montante Liquidado</small>
                                            <span class="fs-6 font-w600 text-success">
                                                {{ number_format($paid, 2, ',', '.') }} Kz
                                            </span>
                                        </div>
                                        <div class="col-sm-6">
                                            <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Saldo em Dívida</small>
                                            <span class="fs-6 font-w600 {{ $balance > 0 ? 'text-danger' : 'text-muted' }}">
                                                {{ number_format($balance, 2, ',', '.') }} Kz
                                            </span>
                                        </div>
                                        <div class="col-12">
                                            <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Estado da Propina</small>
                                            @php $credit = $enrollment->creditBalance(); @endphp
                                            @if($credit > 0)
                                                <span class="badge light badge-info">
                                                    <i class="fa fa-arrow-left me-1"></i>Crédito: {{ number_format($credit, 2, ',', '.') }} Kz
                                                </span>
                                            @elseif($enrollment->isPaid() || $enrollment->isEnrolled())
                                                <span class="badge light badge-success">
                                                    <i class="fa fa-check me-1"></i>Propina Liquidada
                                                </span>
                                            @else
                                                <span class="badge light badge-warning">Pendente de Pagamento</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Histórico de Pagamentos Agrupado por Ciclo (GAP 5) --}}
                                    @if($enrollment->payments && $enrollment->payments->count() > 0)
                                        @php
                                            $paymentsByCycle = $enrollment->payments->groupBy('cycle')->sortKeys();
                                        @endphp
                                        <div class="border-top pt-3">
                                            <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-2">Comprovativos Registados</small>

                                            @foreach($paymentsByCycle as $cycleNum => $cyclePayments)
                                                @php
                                                    $cycleLabel = $cycleNum == 1
                                                        ? '1.ª Frequência'
                                                        : ($cycleNum - 1) . '.ª Repetição (Ciclo ' . $cycleNum . ')';
                                                @endphp
                                                <div class="mb-2">
                                                    <small class="text-muted text-uppercase font-w600 fs-8 d-block py-1 px-2 bg-light rounded border mb-1">
                                                        <i class="fa fa-repeat me-1"></i>{{ $cycleLabel }}
                                                    </small>
                                                    <ul class="list-unstyled mb-0">
                                                        @foreach($cyclePayments as $p)
                                                            <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                                <div>
                                                                    <a href="{{ route('payment.show', $p->id) }}" class="font-w600 text-primary fs-7">
                                                                        {{ $p->reference }}
                                                                    </a>
                                                                    <small class="text-muted d-block fs-8">{{ $p->formattedDate() }} &bull; {{ $p->type_of_payment }}</small>
                                                                    @if($p->previous_room_id && $p->previousRoom)
                                                                        <small class="text-warning d-block fs-8">
                                                                            <i class="fa fa-history me-1"></i>Turma anterior: {{ $p->previousRoom->name }}
                                                                        </small>
                                                                    @endif
                                                                </div>
                                                                <div class="text-end">
                                                                    <strong class="fs-7 text-dark">{{ $p->formattedValue() }}</strong>
                                                                    <span class="badge light {{ $p->statusBadgeClass() }} d-block fs-8 mt-1">{{ $p->status }}</span>
                                                                    @if($p->isCanceled() && $p->cancellation_reason)
                                                                        <small class="text-muted d-block fs-8" title="{{ $p->cancellation_reason }}">
                                                                            <i class="fa fa-info-circle me-1"></i>Ver motivo
                                                                        </small>
                                                                    @endif
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
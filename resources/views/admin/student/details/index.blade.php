@extends('layout.main')

@section('title', 'Ficha do Formando')

@section('content')
{{-- Estrutura principal da página de ficha e detalhes do formando --}}
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    {{-- Cabeçalho do Cartão com Título e Ações Rápidas --}}
                    <div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center border-bottom">
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <h4 class="card-title mb-0 text-dark">Ficha do Formando: {{ $student->name }}</h4>
                                <span class="badge light badge-primary font-w600 fs-7">
                                    {{ $student->formattedCode() }}
                                </span>
                            </div>
                            <p class="m-0 subtitle text-muted">Acompanhamento cadastral, académico e histórico financeiro do aluno oficial</p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            @if($student->room_id)
                                <a href="{{ route('room.printStudents', $student->room_id) }}" class="btn btn-outline-primary btn-sm" target="_blank">
                                    <i class="fa fa-print me-1"></i> Imprimir Pauta
                                </a>
                            @endif
                            <a href="{{ route('student.edit', $student->id) }}" class="btn btn-warning btn-sm">
                                <i class="fa fa-pencil me-1"></i> Editar Dados
                            </a>
                            <a href="{{ route('student.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fa fa-arrow-left me-1"></i> Voltar
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        {{-- Barra Superior de Resumo do Formando com Fotografia e Contactos Rápidos --}}
                        <div class="p-4 mb-4 rounded border bg-light shadow-sm">
                            <div class="row align-items-center">
                                <div class="col-md-7 d-flex align-items-center mb-3 mb-md-0">
                                    <img src="{{ $student->avatarUrl() }}"
                                         class="rounded-circle avatar avatar-xl me-3 border border-3 border-white shadow-sm"
                                         alt="{{ $student->name }}"
                                         style="width: 80px; height: 80px; object-fit: cover;">
                                    <div>
                                        <h4 class="mb-1 text-dark font-w700">{{ $student->name }}</h4>
                                        <div class="text-muted fs-7">
                                            <span class="me-3"><i class="fa fa-id-card text-primary me-1"></i>BI: <strong>{{ $student->number_of_identify }}</strong></span>
                                            <span class="me-3"><i class="fa fa-phone text-success me-1"></i>{{ $student->phone }}</span>
                                            <span><i class="fa fa-envelope text-info me-1"></i>{{ $student->email }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 text-md-end">
                                    <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Estado da Matrícula</small>
                                    <span class="badge light badge-success fs-6 px-3 py-2">
                                        <i class="fa fa-check-circle me-1"></i> Matriculado Oficialmente
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Grelha de Informações Detalhadas (4 Secções Principais) --}}
                        <div class="row g-4">
                            {{-- 1. Dados Civis e Pessoais do Formando --}}
                            <div class="col-md-6">
                                <div class="p-4 border rounded bg-white h-100 shadow-sm">
                                    <h5 class="font-w600 mb-3 text-dark border-bottom pb-2">
                                        <i class="fa fa-user text-muted me-2"></i>1. Dados Pessoais e Identificação
                                    </h5>
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Nome Completo</small>
                                            <span class="fs-6 font-w600 text-dark">{{ $student->name }}</span>
                                        </div>
                                        <div class="col-sm-6">
                                            <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Código de Aluno</small>
                                            <span class="fs-6 font-w600 text-primary">{{ $student->formattedCode() }}</span>
                                        </div>
                                        <div class="col-sm-6">
                                            <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Nº do Bilhete de Identidade</small>
                                            <span class="fs-6 font-w600 text-dark">{{ $student->number_of_identify }}</span>
                                        </div>
                                        <div class="col-sm-6">
                                            <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Telefone Principal</small>
                                            <span class="fs-6 font-w500 text-dark">{{ $student->phone }}</span>
                                        </div>
                                        <div class="col-sm-6">
                                            <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Correio Eletrónico</small>
                                            <span class="fs-6 font-w500 text-dark">{{ $student->email }}</span>
                                        </div>
                                        <div class="col-sm-6">
                                            <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Data de Matrícula</small>
                                            <span class="fs-6 text-dark">
                                                {{ $student->created_at ? $student->created_at->format('d/m/Y \à\s H:i') : '-' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 2. Enquadramento Pedagógico (Turma & Curso) --}}
                            <div class="col-md-6">
                                <div class="p-4 border rounded bg-white h-100 shadow-sm">
                                    <h5 class="font-w600 mb-3 text-dark border-bottom pb-2">
                                        <i class="fa fa-graduation-cap text-muted me-2"></i>2. Enquadramento Pedagógico
                                    </h5>
                                    @if($student->room)
                                        <div class="row g-3">
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Turma Alocada</small>
                                                <a href="{{ route('room.show', $student->room->id) }}" class="fs-6 font-w600 text-primary">
                                                    {{ $student->room->name }}
                                                </a>
                                            </div>
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Curso Ministrado</small>
                                                <span class="fs-6 font-w600 text-dark">
                                                    {{ $student->room->course->name ?? ($student->course->name ?? 'N/D') }}
                                                </span>
                                            </div>
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Turno de Aulas</small>
                                                <span class="badge light badge-primary">{{ $student->room->shift }}</span>
                                            </div>
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Horário das Sessões</small>
                                                @if($student->room->start_time && $student->room->end_time)
                                                    <span class="fs-6 text-dark">
                                                        {{ \Carbon\Carbon::parse($student->room->start_time)->format('H:i') }} às {{ \Carbon\Carbon::parse($student->room->end_time)->format('H:i') }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">Horário não definido</span>
                                                @endif
                                            </div>
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Sala de Aulas</small>
                                                <span class="fs-6 text-dark font-w500">
                                                    @if($student->room->classroom)
                                                        <i class="fa fa-building text-muted me-1"></i> Sala {{ $student->room->classroom->number_of_classroom }}
                                                    @else
                                                        <span class="text-muted">Não definida</span>
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Formador Responsável</small>
                                                <span class="fs-6 text-dark font-w500">
                                                    {{ $student->room->teacher->name ?? 'Não atribuído' }}
                                                </span>
                                            </div>
                                            <div class="col-12">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Ocupação da Turma</small>
                                                @if($student->room)
                                                    <div class="progress mt-1" style="height: 10px;">
                                                        @php $percentage = $student->room->occupancyPercentage(); @endphp
                                                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                    <small class="text-muted mt-1 d-block">
                                                        {{ $student->room->occupiedSeats() }} ocupadas de {{ $student->room->max_capacity }} vagas ({{ $student->room->availableSeats() }} disponíveis)
                                                    </small>
                                                @else
                                                    <span class="text-muted">Nenhuma turma atribuída</span>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <div class="p-3 bg-light rounded text-center">
                                            <p class="text-muted mb-0">Nenhuma turma atribuída atualmente a este formando.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- 3. Inscrição de Origem --}}
                            <div class="col-md-6">
                                <div class="p-4 border rounded bg-white h-100 shadow-sm">
                                    <h5 class="font-w600 mb-3 text-dark border-bottom pb-2">
                                        <i class="fa fa-id-card-o text-muted me-2"></i>3. Candidatura de Origem
                                    </h5>
                                    @if($student->enrollment)
                                        <div class="row g-3">
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Código da Candidatura</small>
                                                <a href="{{ route('enrollment.show', $student->enrollment->id) }}" class="fs-6 font-w600 text-primary">
                                                    {{ $student->enrollment->formattedCode() }}
                                                </a>
                                            </div>
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Data da Inscrição</small>
                                                <span class="fs-6 text-dark">
                                                    {{ $student->enrollment->date ? $student->enrollment->date->format('d/m/Y') : ($student->enrollment->created_at ? $student->enrollment->created_at->format('d/m/Y') : '-') }}
                                                </span>
                                            </div>
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Ciclo de Frequência</small>
                                                <span class="badge light badge-info font-w600">
                                                    Ciclo {{ $student->enrollment->cycle ?? 1 }}
                                                </span>
                                            </div>
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Turno Pretendido Inicial</small>
                                                <span class="fs-6 text-dark">{{ $student->enrollment->shift }}</span>
                                            </div>
                                            <div class="col-12 pt-2 border-top">
                                                <a href="{{ route('enrollment.show', $student->enrollment->id) }}" class="btn btn-outline-secondary btn-xs">
                                                    <i class="fa fa-external-link me-1"></i> Ver Ficha da Inscrição
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-muted mb-0">Inscrição de origem não vinculada.</p>
                                    @endif
                                </div>
                            </div>

                            {{-- 4. Regularização Financeira & Comprovativos --}}
                            <div class="col-md-6">
                                <div class="p-4 border rounded bg-white h-100 shadow-sm">
                                    <h5 class="font-w600 mb-3 text-dark border-bottom pb-2">
                                        <i class="fa fa-money text-muted me-2"></i>4. Regularização Financeira
                                    </h5>
                                    @php
                                        $payments = $student->enrollment ? $student->enrollment->payments : collect();
                                        $totalPaid = $student->enrollment ? $student->enrollment->totalPaid() : 0.0;
                                        $coursePrice = $student->course ? (float) $student->course->value : 0.0;
                                    @endphp
                                    <div class="row g-3 mb-3">
                                        <div class="col-sm-6">
                                            <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Preço do Curso</small>
                                            <span class="fs-6 font-w600 text-dark">
                                                {{ number_format($coursePrice, 2, ',', '.') }} Kz
                                            </span>
                                        </div>
                                        <div class="col-sm-6">
                                            <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Montante Liquidado</small>
                                            <span class="fs-6 font-w600 text-success">
                                                {{ number_format($totalPaid, 2, ',', '.') }} Kz
                                            </span>
                                        </div>
                                    </div>

                                    @if($payments->count() > 0)
                                        <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-2">Comprovativos e Recibos Registados</small>
                                        <ul class="list-unstyled mb-0">
                                            @foreach($payments as $p)
                                                <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                    <div>
                                                        <a href="{{ route('payment.show', $p->id) }}" class="font-w600 text-primary fs-7">
                                                            {{ $p->reference }}
                                                        </a>
                                                        <small class="text-muted d-block fs-8">{{ $p->formattedDate() }} &bull; {{ $p->type_of_payment }}</small>
                                                    </div>
                                                    <div class="text-end">
                                                        <strong class="fs-7 text-dark">{{ $p->formattedValue() }}</strong>
                                                        <span class="badge light {{ $p->statusBadgeClass() }} d-block fs-8 mt-1">{{ $p->status }}</span>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-muted mb-0 fs-7">Nenhum pagamento registado nesta matrícula.</p>
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
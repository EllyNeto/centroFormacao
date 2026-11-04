@extends('layout.main')

@section('title', 'Detalhes da Turma')

@section('content')
{{-- Estrutura principal da página de detalhes da turma --}}
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    {{-- Cabeçalho do Cartão com título e ações rápidas --}}
                    <div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center border-bottom">
                        <div>
                            <h4 class="card-title mb-1 text-dark">Detalhes da Turma: {{ $room->name }}</h4>
                            <p class="m-0 subtitle text-muted">Informações pedagógicas, lotação e atribuições da turma</p>
                        </div>
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            {{-- Botão para Imprimir a Lista Nominal Oficial dos Formandos --}}
                            <a href="{{ route('room.printStudents', $room->id) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="fa fa-print me-1"></i> Imprimir Lista de Formandos
                            </a>
                            {{-- Botão para Editar a Turma --}}
                            <a href="{{ route('room.edit', $room->id) }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-pencil me-1"></i> Editar
                            </a>
                            {{-- Botão para Regressar à Listagem --}}
                            <a href="{{ route('room.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fa fa-arrow-left me-1"></i> Voltar
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        @php
                            $occupied   = $room->occupiedSeats();
                            $capacity   = $room->max_capacity;
                            $available  = $room->availableSeats();
                            $percentage = $room->occupancyPercentage();
                        @endphp

                        {{-- Barra Superior Limpa e Neutra de Lotação e Estado --}}
                        <div class="p-3 mb-4 rounded border bg-light">
                            <div class="row align-items-center">
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <small class="text-muted text-uppercase font-w600 d-block fs-7 mb-1">Estado da Turma</small>
                                    <div>
                                        <span class="badge light badge-primary fs-6 px-3 py-2">
                                            {{ $room->status }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="font-w600 text-dark">Ocupação: {{ $occupied }} de {{ $capacity }} lugares ocupados</span>
                                        <span class="font-w600 text-dark">
                                            {{ $available }} {{ $available == 1 ? 'vaga livre' : 'vagas livres' }} ({{ $percentage }}%)
                                        </span>
                                    </div>
                                    <div class="progress room-progress-track">
                                        <div class="progress-bar bg-primary room-progress-fill" 
                                             role="progressbar" 
                                             style="width: {{ $percentage }}%;" 
                                             aria-valuenow="{{ $percentage }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Grelha de Informações com Paleta Unificada e Elegante (Cores Neutras e Consistentes) --}}
                        <div class="row g-4">
                            {{-- 1. Horários e Calendário --}}
                            <div class="col-md-6">
                                <div class="p-4 border rounded bg-white h-100 shadow-sm">
                                    <h5 class="font-w600 mb-3 text-dark border-bottom pb-2">
                                        <i class="fa fa-clock-o text-muted me-2"></i>Horários e Calendário
                                    </h5>
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Turno</small>
                                            <span class="fs-6 font-w600 text-dark">{{ $room->shift }}</span>
                                        </div>
                                        <div class="col-sm-6">
                                            <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Horário das Aulas</small>
                                            <span class="fs-6 font-w600 text-dark">{{ \Carbon\Carbon::parse($room->start_time)->format('H:i') }} às {{ \Carbon\Carbon::parse($room->end_time)->format('H:i') }}</span>
                                        </div>
                                        <div class="col-12">
                                            <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Dias da Semana</small>
                                            <span class="fs-6 font-w600 text-dark">{{ is_array($room->days_of_week) ? implode(', ', $room->days_of_week) : $room->days_of_week }}</span>
                                        </div>
                                        <div class="col-12">
                                            <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Período Previsto</small>
                                            <span class="fs-6 font-w600 text-dark">
                                                {{ $room->start_date ? $room->start_date->format('d/m/Y') : 'Início N/D' }} 
                                                até 
                                                {{ $room->end_date ? $room->end_date->format('d/m/Y') : 'Fim N/D' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 2. Sala Física Atribuída --}}
                            <div class="col-md-6">
                                <div class="p-4 border rounded bg-white h-100 shadow-sm">
                                    <h5 class="font-w600 mb-3 text-dark border-bottom pb-2">
                                        <i class="fa fa-building text-muted me-2"></i>Sala 
                                    </h5>
                                    @if($room->classroom)
                                        <div class="row g-3">
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Número da Sala</small>
                                                <span class="fs-6 font-w600 text-dark">Sala {{ $room->classroom->number_of_classroom }}</span>
                                            </div>
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Capacidade da Sala</small>
                                                <span class="fs-6 font-w600 text-dark">{{ $room->classroom->capacity }} lugares</span>
                                            </div>
                                            <div class="col-12">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Descrição / Equipamento</small>
                                                <span class="fs-6 text-dark">{{ $room->classroom->description ?? 'Sem observações adicionais.' }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-muted mb-0">Nenhuma sala física atribuída.</p>
                                    @endif
                                </div>
                            </div>

                            {{-- 3. Curso Associado --}}
                            <div class="col-md-6">
                                <div class="p-4 border rounded bg-white h-100 shadow-sm">
                                    <h5 class="font-w600 mb-3 text-dark border-bottom pb-2">
                                        <i class="fa fa-book text-muted me-2"></i>Curso Associado
                                    </h5>
                                    @if($room->course)
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Nome do Curso</small>
                                                <span class="fs-6 font-w600 text-dark">{{ $room->course->name }}</span>
                                            </div>
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Carga Horária</small>
                                                <span class="fs-6 font-w600 text-dark">{{ $room->course->duration }} horas</span>
                                            </div>
                                            {{-- <div class="col-sm-6">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Preço da Propina</small>
                                                <span class="fs-6 font-w600 text-dark">{{ number_format($room->course->value, 2, ',', '.') }} Kz</span>
                                            </div> --}}
                                        </div>
                                    @else
                                        <p class="text-muted mb-0">Curso não identificado.</p>
                                    @endif
                                </div>
                            </div>

                            {{-- 4. Formador Responsável --}}
                            <div class="col-md-6">
                                <div class="p-4 border rounded bg-white h-100 shadow-sm">
                                    <h5 class="font-w600 mb-3 text-dark border-bottom pb-2">
                                        <i class="fa fa-user text-muted me-2"></i>Formador
                                    </h5>
                                    @if($room->teacher)
                                        <div class="d-flex align-items-center mb-3">
                                            @if($room->teacher->image)
                                                <img src="{{ asset('storage/' . $room->teacher->image) }}" class="rounded-circle me-3 room-avatar-detail" alt="{{ $room->teacher->name }}">
                                            @else
                                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3 room-avatar-placeholder">
                                                    <i class="fa fa-user text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <span class="fs-6 font-w600 text-dark d-block">{{ $room->teacher->name }}</span>
                                                <small class="text-muted">{{ $room->teacher->specialization }}</small>
                                            </div>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Email</small>
                                                <span class="fs-6 font-w500 text-dark">{{ $room->teacher->email }}</span>
                                            </div>
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Telefone</small>
                                                <span class="fs-6 font-w500 text-dark">{{ $room->teacher->phone }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-muted mb-0">Nenhum formador atribuído a esta turma.</p>
                                    @endif
                                </div>
                            </div>

                            {{-- 5. Alunos e Inscrições Alocadas na Turma --}}
                            <div class="col-12">
                                <div class="p-4 border rounded bg-white shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                                        <h5 class="font-w600 mb-0 text-dark">
                                            <i class="fa fa-users text-muted me-2"></i>Alunos / Candidatos nesta Turma ({{ $room->enrollments->count() }})
                                        </h5>
                                    </div>
                                    @if($room->enrollments && $room->enrollments->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-hover style-1 custom-table mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Código</th>
                                                        <th>Nome</th>
                                                        <th>BI / Identificação</th>
                                                        <th>Contacto</th>
                                                        <th>Estado</th>
                                                        <th class="text-end">Ação</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($room->enrollments as $enrollment)
                                                        <tr>
                                                            <td>
                                                                <span class="badge light badge-primary font-w600">
                                                                    {{ $enrollment->formattedCode() }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('enrollment.show', $enrollment->id) }}" class="font-w600 text-dark">
                                                                    {{ $enrollment->name }}
                                                                </a>
                                                            </td>
                                                            <td>{{ $enrollment->number_of_identify }}</td>
                                                            <td>{{ $enrollment->phone }}</td>
                                                            <td>
                                                                <span class="badge light {{ $enrollment->statusBadgeClass() }}">
                                                                    {{ $enrollment->status }}
                                                                </span>
                                                            </td>
                                                            <td class="text-end">
                                                                <a href="{{ route('enrollment.show', $enrollment->id) }}" class="btn btn-outline-primary btn-xs">
                                                                    <i class="fa fa-eye me-1"></i> Ver
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted mb-0">Nenhum aluno ou candidato alocado a esta turma até ao momento.</p>
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
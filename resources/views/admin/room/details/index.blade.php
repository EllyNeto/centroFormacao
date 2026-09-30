@extends('layout.main')

@section('title','Detalhes da Turma')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center">
                        <div>
                            <h4 class="card-title mb-1">Detalhes da Turma: {{ $room->name }}</h4>
                            <p class="m-0 subtitle text-muted">Informações completas sobre a turma e associações</p>
                        </div>
                        <div>
                            <a href="{{ route('room.edit', $room->id) }}" class="btn btn-warning btn-sm me-2">
                                <i class="fa fa-edit me-1"></i> Editar
                            </a>
                            <a href="{{ route('room.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fa fa-arrow-left me-1"></i> Voltar
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="row">
                            {{-- Informações da Turma --}}
                            <div class="col-md-6 mb-4">
                                <div class="p-3 border rounded bg-light">
                                    <h5 class="fw-bold mb-3 text-primary"><i class="fa fa-users me-2"></i>Dados da Turma</h5>
                                    <p class="mb-2"><strong>Nome:</strong> {{ $room->name }}</p>
                                    <p class="mb-2"><strong>Capacidade Máxima:</strong> {{ $room->max_capacity ?? 'N/A' }} formandos</p>
                                    <p class="mb-2"><strong>Turno:</strong> <span class="badge light badge-info">{{ $room->shift }}</span></p>
                                    <p class="mb-2"><strong>Dias da Semana:</strong> {{ is_array($room->days_of_week) ? implode(', ', $room->days_of_week) : $room->days_of_week }}</p>
                                    <p class="mb-2"><strong>Horário:</strong> {{ \Carbon\Carbon::parse($room->start_time)->format('H:i') }} às {{ \Carbon\Carbon::parse($room->end_time)->format('H:i') }}</p>
                                </div>
                            </div>

                            {{-- Curso Associado --}}
                            <div class="col-md-6 mb-4">
                                <div class="p-3 border rounded bg-light">
                                    <h5 class="fw-bold mb-3 text-success"><i class="fa fa-book me-2"></i>Curso</h5>
                                    <p class="mb-2"><strong>Nome:</strong> {{ $room->course->name ?? 'Não atribuído' }}</p>
                                    <p class="mb-2"><strong>Duração:</strong> {{ $room->course->duration ?? '-' }} horas</p>
                                    <p class="mb-2"><strong>Valor:</strong> {{ isset($room->course->value) ? number_format($room->course->value, 2, ',', '.') . ' Kz' : '-' }}</p>
                                </div>
                            </div>

                            {{-- Formador Responsável --}}
                            <div class="col-md-6 mb-4">
                                <div class="p-3 border rounded bg-light">
                                    <h5 class="fw-bold mb-3 text-info"><i class="fa fa-user me-2"></i>Formador </h5>
                                    <p class="mb-2"><strong>Nome:</strong> {{ $room->teacher->name ?? 'Não atribuído' }}</p>
                                    <p class="mb-2"><strong>Especialização:</strong> {{ $room->teacher->specialization ?? '-' }}</p>
                                    <p class="mb-2"><strong>Email:</strong> {{ $room->teacher->email ?? '-' }}</p>
                                    <p class="mb-2"><strong>Telefone:</strong> {{ $room->teacher->phone ?? '-' }}</p>
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
@extends('layout.main')

@section('title', 'Detalhes da Sala')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h4 class="card-title mb-1">Detalhes da Sala: {{ $classroom->number_of_classroom }}</h4>
                            <p class="subtitle text-muted mb-0">Informações gerais e turmas alocadas a esta sala</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('classroom.edit', $classroom->id) }}" class="btn btn-warning btn-sm text-white">
                                <i class="fa fa-edit me-1"></i> Editar Sala
                            </a>
                            <a href="{{ route('classroom.index') }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-arrow-left me-1"></i> Voltar
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <div class="p-3 border rounded bg-light">
                                    <h6 class="text-primary mb-1">Número da Sala</h6>
                                    <p class="fw-bold mb-0">Sala {{ $classroom->number_of_classroom }}</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="p-3 border rounded bg-light">
                                    <h6 class="text-primary mb-1">Capacidade de Alunos</h6>
                                    <p class="fw-bold mb-0">{{ $classroom->capacity ? $classroom->capacity . ' alunos' : 'Não especificado' }}</p>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="p-3 border rounded bg-light">
                                    <h6 class="text-primary mb-1"> Observações</h6>
                                    <p class="mb-0">{{ $classroom->description ?: 'Sem observação registada.' }}</p>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5 class="text-primary mb-3"><i class="fa fa-users me-2"></i> Turmas Atribuídas a Esta Sala</h5>

                        <div class="table-responsive">
                            <table class="table table-hover style-1 custom-table">
                                <thead>
                                    <tr>
                                        <th>Turma</th>
                                        <th>Curso</th>
                                        <th>Formador</th>
                                        <th>Turno</th>
                                        <th>Horário</th>
                                        <th>Capacidade Máx.</th>
                                        <th class="text-end">Acções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($classroom->rooms as $room)
                                        <tr>
                                            <td><strong>{{ $room->name }}</strong></td>
                                            <td>{{ $room->course->name ?? 'N/A' }}</td>
                                            <td>{{ $room->teacher->name ?? 'N/A' }}</td>
                                            <td><span class="badge light badge-info">{{ $room->shift }}</span></td>
                                            <td>
                                                <i class="fa fa-clock-o me-1 text-muted"></i>
                                                {{ \Carbon\Carbon::parse($room->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($room->end_time)->format('H:i') }}
                                            </td>
                                            <td>{{ $room->max_capacity }} alunos</td>
                                            <td class="text-end">
                                                <a href="{{ route('room.show', $room->id) }}" class="btn btn-xs btn-outline-primary">Ver Turma</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">
                                                <i class="fa fa-info-circle me-1"></i> Nenhuma turma atribuída a esta sala no momento.
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

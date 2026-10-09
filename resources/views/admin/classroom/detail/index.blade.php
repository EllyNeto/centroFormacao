{{-- 
    Vista: Detalhes da Sala de Aula (admin/classroom/detail/index.blade.php)
    Descrição: Exibe os dados gerais da sala (número, capacidade de lugares e observações) 
               bem como a tabela das turmas ativas alocadas a esta sala com atalho para os seus detalhes.
--}}
@extends('layout.main')

@section('title', 'Detalhes da Sala de Aula')

@section('content')
{{-- Estrutura principal da página --}}
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                {{-- Cartão de Detalhes da Sala --}}
                <div class="card">
                    
                    {{-- Cabeçalho do Cartão com botões de Ação (Editar e Voltar) --}}
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h4 class="card-title mb-1">Detalhes da Sala: {{ $classroom->number_of_classroom }}</h4>
                            <p class="subtitle text-muted mb-0">Informações físicas do espaço e lista de turmas alocadas</p>
                        </div>
                        <div class="d-flex gap-2">
                            {{-- Atalho para Editar esta sala --}}
                            <a href="{{ route('classroom.edit', $classroom->id) }}" class="btn btn-warning btn-sm text-white">
                                <i class="fa fa-edit me-1"></i> Editar Sala
                            </a>
                            {{-- Regressar à listagem --}}
                            <a href="{{ route('classroom.index') }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-arrow-left me-1"></i> Voltar
                            </a>
                        </div>
                    </div>

                    {{-- Corpo com os cartões informativos da sala --}}
                    <div class="card-body p-4">
                        <div class="row mb-4">
                            {{-- Bloco 1: Número da Sala --}}
                            <div class="col-md-4 mb-3">
                                <div class="p-3 border rounded bg-light">
                                    <h6 class="text-primary mb-1">
                                        <i class="fa fa-building me-1"></i> Número da Sala
                                    </h6>
                                    <p class="fw-bold mb-0 fs-16">Sala {{ $classroom->number_of_classroom }}</p>
                                </div>
                            </div>

                            {{-- Bloco 2: Capacidade de Lugares --}}
                            <div class="col-md-4 mb-3">
                                <div class="p-3 border rounded bg-light">
                                    <h6 class="text-primary mb-1">
                                        <i class="fa fa-users me-1"></i> Capacidade Física
                                    </h6>
                                    <p class="fw-bold mb-0 fs-16">{{ $classroom->capacity }} lugares</p>
                                </div>
                            </div>

                            {{-- Bloco 3: Total de Turmas Alocadas --}}
                            <div class="col-md-4 mb-3">
                                <div class="p-3 border rounded bg-light">
                                    <h6 class="text-primary mb-1">
                                        <i class="fa fa-calendar me-1"></i> Turmas Alocadas
                                    </h6>
                                    <p class="fw-bold mb-0 fs-16">{{ $classroom->rooms->count() }} turma(s)</p>
                                </div>
                            </div>

                            {{-- Bloco 4: Observações e Equipamentos --}}
                            <div class="col-md-12 mb-3">
                                <div class="p-3 border rounded bg-light">
                                    <h6 class="text-primary mb-1">
                                        <i class="fa fa-info-circle me-1"></i> Observações 
                                    </h6>
                                    <p class="mb-0">{{ $classroom->description ?: 'Nenhuma observação ou equipamento registado para esta sala.' }}</p>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Secção: Turmas Alocadas a esta Sala --}}
                        <h5 class="text-primary mb-3">
                            <i class="fa fa-list me-2"></i> Turmas Atribuídas a Esta Sala
                        </h5>

                        <div class="table-responsive">
                            <table class="table table-hover style-1 custom-table">
                                <thead>
                                    <tr>
                                        <th>Nome da Turma</th>
                                        <th>Curso</th>
                                        <th>Formador</th>
                                        <th>Turno</th>
                                        <th>Horário de Aulas</th>
                                        <th>Capacidade da Turma</th>
                                        <th class="text-end">Acções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Iteração sobre as turmas associadas à sala --}}
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
                                            <td>{{ $room->max_capacity }} lugares</td>
                                            <td class="text-end">
                                                <a href="{{ route('room.show', $room->id) }}" class="btn btn-xs btn-outline-primary">
                                                    <i class="fa fa-eye me-1"></i> Ver Turma
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        {{-- Estado quando não existem turmas atribuídas a esta sala --}}
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

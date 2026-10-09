{{-- 
    Vista: Detalhes do Curso (admin/course/details/index.blade.php)
    Descrição: Apresenta as informações completas do curso (preço, duração em horas, estado, descrição)
               e a listagem de todas as turmas ativas alocadas a este curso.
--}}
@extends('layout.main')

@section('title', 'Detalhes do Curso')

@section('content')
{{-- Estrutura principal da página --}}
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                {{-- Cartão Principal de Detalhes do Curso --}}
                <div class="card">
                    
                    {{-- Cabeçalho do Cartão com Botões de Ação (Editar e Voltar) --}}
                    <div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center">
                        <div>
                            <h4 class="card-title mb-1">Detalhes do Curso: {{ $course->name }}</h4>
                            <p class="subtitle text-muted mb-0">Informações gerais e turmas vinculadas à oferta formativa</p>
                        </div>
                        <div class="d-flex gap-2">
                            {{-- Atalho para Editar o Curso --}}
                            <a href="{{ route('course.edit', $course->id) }}" class="btn btn-warning btn-sm text-white">
                                <i class="fa fa-edit me-1"></i> Editar Curso
                            </a>
                            {{-- Regressar à listagem --}}
                            <a href="{{ route('course.index') }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-arrow-left me-1"></i> Voltar à Listagem
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="row">
                            {{-- Coluna Esquerda: Descrição e Programa Formativo --}}
                            <div class="col-xl-8 col-lg-7 mb-4">
                                <h3 class="text-dark font-w700 mb-3">{{ $course->name }}</h3>
                                
                                <h5 class="text-primary font-w600 mb-2">
                                    <i class="fa fa-file-text me-1"></i> Descrição / Programa Formativo
                                </h5>
                                <div class="p-3 bg-light rounded border">
                                    <p class="fs-15 text-dark mb-0" style="line-height: 1.8; white-space: pre-line;">{{ $course->description ?: 'Nenhuma descrição registada para este curso.' }}</p>
                                </div>
                            </div>

                            {{-- Coluna Direita: Painel Informativo (Preço, Duração, Estado, Data) --}}
                            <div class="col-xl-4 col-lg-5 mb-4">
                                <div class="p-4 rounded border bg-light">
                                    <h5 class="text-primary font-w700 mb-3">
                                        <i class="fa fa-info-circle me-1"></i> Informações Gerais
                                    </h5>

                                    <ul class="list-group list-group-flush border-0 bg-transparent">
                                        {{-- Estado --}}
                                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0 py-2 border-bottom border-light">
                                            <span class="text-dark font-w600 fs-15">
                                                <i class="fa fa-toggle-on me-2 text-primary"></i> Estado:
                                            </span>
                                            @if($course->status)
                                                <span class="badge light badge-success font-w700 fs-14 px-3 py-1">Ativo</span>
                                            @else
                                                <span class="badge light badge-danger font-w700 fs-14 px-3 py-1">Inativo</span>
                                            @endif
                                        </li>

                                        {{-- Preço do Curso --}}
                                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0 py-2 border-bottom border-light">
                                            <span class="text-dark font-w600 fs-15">
                                                <i class="fa fa-tag me-2 text-primary"></i> Preço:
                                            </span>
                                            <span class="text-dark font-w700 fs-16">{{ number_format($course->value, 2, ',', '.') }} Kz</span>
                                        </li>

                                        {{-- Carga Horária --}}
                                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0 py-2 border-bottom border-light">
                                            <span class="text-dark font-w600 fs-15">
                                                <i class="fa fa-clock-o me-2 text-primary"></i> Carga Horária:
                                            </span>
                                            <span class="text-dark font-w700 fs-15">{{ $course->duration }} horas</span>
                                        </li>

                                        {{-- Data de Criação --}}
                                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0 py-2">
                                            <span class="text-dark font-w600 fs-15">
                                                <i class="fa fa-calendar me-2 text-primary"></i> Registado em:
                                            </span>
                                            <span class="text-dark font-w700 fs-14">{{ $course->created_at ? $course->created_at->format('d/m/Y') : 'N/A' }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Secção de Turmas Alocadas a este Curso --}}
                        <h5 class="text-primary mb-3">
                            <i class="fa fa-users me-2"></i> Turmas Ativas Deste Curso
                        </h5>

                        <div class="table-responsive">
                            <table class="table table-hover style-1 custom-table">
                                <thead>
                                    <tr>
                                        <th>Nome da Turma</th>
                                        <th>Formador</th>
                                        <th>Sala</th>
                                        <th>Turno</th>
                                        <th>Horário</th>
                                        <th>Capacidade Máx.</th>
                                        <th class="text-end">Acções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Iteração sobre as turmas do curso --}}
                                    @forelse ($course->rooms as $room)
                                        <tr>
                                            <td><strong>{{ $room->name }}</strong></td>
                                            <td>{{ $room->teacher->name ?? 'N/A' }}</td>
                                            <td>
                                                @if($room->classroom)
                                                    <span class="badge light badge-success">Sala {{ $room->classroom->number_of_classroom }}</span>
                                                @else
                                                    <span class="text-muted">Sem sala</span>
                                                @endif
                                            </td>
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
                                        {{-- Estado quando não existem turmas registadas para o curso --}}
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">
                                                <i class="fa fa-info-circle me-1"></i> Nenhuma turma registada para este curso até ao momento.
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
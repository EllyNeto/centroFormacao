@extends('layouts.main')

@section('title', 'Detalhes da Turma')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0">Detalhes da Turma: {{ $class->name }}</h4>
                            <small class="text-muted">Identificador: {{ $class->code }}</small>
                        </div>
                        <div>
                            <a href="{{ route('class.edit', $class->id) }}" class="btn btn-primary btn-sm me-1">
                                <i class="fa fa-pencil me-1"></i> Editar Turma
                            </a>
                            <a href="{{ route('class.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fa fa-arrow-left me-1"></i> Voltar à Listagem
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="p-3 mb-3 border rounded">
                                    <h6 class="text-primary font-w600 mb-3"><i class="fa fa-info-circle me-2"></i>Informação Geral</h6>
                                    <p><strong>Identificador (#ID):</strong> {{ $class->code }}</p>
                                    <p><strong>Nome da Turma:</strong> {{ $class->name }}</p>
                                    <p>
                                      <strong>Horário: </strong>{{ date('H:i', strtotime($class->start_time)) }}h às {{ date('H:i', strtotime($class->end_time)) }}h
                                    </p>
                                    <p><strong>Estado:</strong> 
                                        @if($class->status)
                                            <span class="badge badge-success light">Activa</span>
                                        @else
                                            <span class="badge badge-danger light">Inactiva</span>
                                        @endif
                                    </p>
                                    <p><strong>Aluno Associado:</strong> {{ $class->student->name ?? 'Nenhum' }}</p>
                                    <p><strong>Total de Faltas:</strong> <span class="badge badge-secondary light">{{ $class->falta ?? 0 }}</span></p>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <div class="p-3 mb-3 border rounded">
                                    <h6 class="text-primary font-w600 mb-3"><i class="fa fa-building me-2"></i>Associação e Logística</h6>
                                    <p><strong>Nome do Curso Associado:</strong> {{ $class->course->name ?? 'Não atribuído' }}</p>
                                    <p><strong>Nome do Formador Responsável:</strong> {{ $class->teacher->name ?? 'Não atribuído' }}</p>
                                    <p><strong>Turno das Aulas:</strong> {{ $class->shift }}</p>
                                    <p><strong>Capacidade Máxima:</strong> {{ $class->capacity }} Alunos</p>
                                    <p>
                                        <strong>Dias da semana:</strong>
                                        @if(is_array($class->days_of_week))
                                            {{ implode(', ', $class->days_of_week) }}
                                        @else
                                            {{ $class->days_of_week }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <form action="{{ route('class.destroy', $class->id) }}" method="POST" onsubmit="return confirm('Tem a certeza que deseja eliminar esta turma?');" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger light">
                                <i class="fa fa-trash me-1"></i> Eliminar Turma
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

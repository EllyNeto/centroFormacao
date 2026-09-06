{{-- Extende o layout principal unificado da aplicação --}}
@extends('layouts.main')

{{-- Define o título dinâmico da página --}}
@section('title', 'Detalhes da Turma')

{{-- Conteúdo principal da página de visualização da turma --}}
@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    {{-- Cabeçalho do cartão com o nome da turma e botões de ação --}}
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0">Detalhes da Turma: {{ $class->name }}</h4>
                            <small class="text-muted">Código: {{ $class->code }}</small>
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
                                    <p><strong>Identificador (#ID):</strong> #{{ $class->id }}</p>
                                    <p><strong>Nome da Turma:</strong> {{ $class->name }}</p>
                                    <p><strong>Código da Turma:</strong> <span class="badge badge-secondary light">{{ $class->code }}</span></p>
                                    <p><strong>Estado:</strong> 
                                        @if($class->status)
                                            <span class="badge badge-success light">Activa</span>
                                        @else
                                            <span class="badge badge-danger light">Inactiva</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <div class="p-3 mb-3 border rounded">
                                    <h6 class="text-primary font-w600 mb-3"><i class="fa fa-building me-2"></i>Associação e Logística</h6>
                                    <p><strong>Curso Associado:</strong> {{ $class->course_name ?: 'Não atribuído' }}</p>
                                    <p><strong>Formador Responsável:</strong> {{ $class->teacher_name ?: 'Não atribuído' }}</p>
                                    <p><strong>Turno das Aulas:</strong> {{ $class->shift }}</p>
                                    <p><strong>Sala / Localização:</strong> {{ $class->room ?: 'A definir' }}</p>
                                    <p><strong>Capacidade Máxima:</strong> {{ $class->capacity }} Alunos</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Rodapé do cartão --}}
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

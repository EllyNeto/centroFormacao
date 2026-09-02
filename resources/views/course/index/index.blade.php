{{-- Extende o layout principal unificado da aplicação --}}
@extends('layouts.main.main')

{{-- Define o título dinâmico da página --}}
@section('title', 'Lista de Cursos')

{{-- Conteúdo principal da página de listagem de cursos --}}
@section('content')
<div class="content-body">
    <div class="container-fluid">
        {{-- Alerta de sucesso quando existe uma mensagem na sessão (flash session) --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h4 class="card-title mb-0">Gestão de Cursos</h4>
                            <p class="text-muted mb-0 fs-13">Lista de todos os cursos de formação registados</p>
                        </div>
                        <a href="{{ route('course.create') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus me-1"></i> Adicionar Novo Curso
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>#ID</th>
                                        <th>Nome do Curso</th>
                                        <th>Categoria</th>
                                        <th>Duração</th>
                                        <th>Descrição</th>
                                        <th class="text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Iteração sobre a lista de cursos passada pelo controlador --}}
                                    @forelse($courses as $courseItem)
                                        <tr>
                                            <td><strong>#{{ $courseItem->id }}</strong></td>
                                            <td>
                                                <a href="{{ route('course.show', $courseItem->id) }}" class="text-primary font-w600">
                                                    {{ $courseItem->name }}
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge badge-primary light">
                                                    {{ $courseItem->category }}
                                                </span>
                                            </td>
                                            <td>
                                                <i class="fa fa-clock-o text-muted me-1"></i> {{ $courseItem->duration }} horas
                                            </td>
                                            <td>
                                                {{ Str::limit($courseItem->description, 60, '...') ?: 'Sem descrição' }}
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center">
                                                    {{-- Botão para Visualizar Detalhes do Curso --}}
                                                    <a href="{{ route('course.show', $courseItem->id) }}" class="btn btn-info shadow btn-xs sharp me-1" title="Ver Detalhes">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    
                                                    {{-- Botão para Editar Curso --}}
                                                    <a href="{{ route('course.edit', $courseItem->id) }}" class="btn btn-primary shadow btn-xs sharp me-1" title="Editar Curso">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>

                                                    {{-- Formulário para Eliminar Curso --}}
                                                    <form action="{{ route('course.destroy', $courseItem->id) }}" method="POST" onsubmit="return confirm('Tem a certeza que deseja eliminar este curso?');" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger shadow btn-xs sharp" title="Eliminar Curso">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        {{-- Apresentado caso a coleção de cursos esteja vazia --}}
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                <i class="fa fa-folder-open-o fs-24 mb-2 d-block"></i>
                                                Nenhum curso encontrado na base de dados.
                                                <br>
                                                <a href="{{ route('course.create') }}" class="btn btn-primary btn-sm mt-2">
                                                    <i class="fa fa-plus me-1"></i> Adicionar o Primeiro Curso
                                                </a>
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
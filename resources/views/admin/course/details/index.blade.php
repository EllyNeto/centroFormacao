{{-- Extende o layout principal unificado da aplicação --}}
@extends('layouts.main')

{{-- Define o título dinâmico da página --}}
@section('title', 'Detalhes do Curso')

{{-- Conteúdo principal da página de detalhes do curso --}}
@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Detalhes do Curso</h4>
                        <div>
                            <a href="{{ route('course.index') }}" class="btn btn-secondary btn-sm me-1">
                                <i class="fa fa-arrow-left me-1"></i> Voltar à Listagem
                            </a>
                            <a href="{{ route('course.edit', $course->id) }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-pencil me-1"></i> Editar Curso
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="row">
                            {{-- Coluna Principal: Informações do Curso --}}
                            <div class="col-xl-8 col-lg-7">
                                <div class="mb-4">
                                    <h2 class="font-w700 text-black mb-1">{{ $course->name }}</h2>
                                    <span class="badge badge-primary light">#ID {{ $course->id }}</span>
                                </div>

                                <div class="mb-4">
                                    <h5 class="text-primary font-w600">Descrição</h5>
                                    <p class="fs-15 text-justify">
                                        {{ $course->description ?: 'Sem descrição disponível para este curso.' }}
                                    </p>
                                </div>
                            </div>

                            {{-- Coluna Lateral: Resumo de Metadados --}}
                            <div class="col-xl-4 col-lg-5">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <h5 class="card-title text-primary mb-3">Informações Gerais</h5>
                                        
                                        <ul class="list-group list-group-flush bg-transparent">
                                            <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0">
                                                <span class="text-muted"><i class="fa fa-hashtag me-2"></i> ID do Curso:</span>
                                                <strong>#{{ $course->id }}</strong>
                                            </li>
                                            <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0">
                                                <span class="text-muted"><i class="fa fa-tag me-2"></i> Estado:</span>
                                                    @if($course->status)
                                                        <strong class="text-success">Activo</strong>
                                                    @else
                                                        <strong class="text-danger">Desativo</strong>
                                                    @endif
                                            </li>
                                            <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0">
                                                <span class="text-muted"><i class="fa fa-clock-o me-2"></i> Carga Horária:</span>
                                                <strong>{{ $course->duration }} horas</strong>
                                            </li>
                                            <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0">
                                                <span class="text-muted"><i class="fa fa-calendar me-2"></i> Data de Criação:</span>
                                                <strong>{{ $course->created_at ? $course->created_at->format('d/m/Y H:i') : 'N/D' }}</strong>
                                            </li>
                                        </ul>

                                        <div class="mt-4 pt-2 d-grid gap-2">
                                            <form action="{{ route('course.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Tem a certeza que deseja eliminar este curso?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger w-100">
                                                    <i class="fa fa-trash me-1"></i> Eliminar Curso
                                                </button>
                                            </form>
                                        </div>
                                    </div>
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
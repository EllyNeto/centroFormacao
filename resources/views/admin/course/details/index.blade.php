{{-- Extende o layout principal do painel administrativo --}}
@extends('layout.main')

{{-- Define o título da página --}}
@section('title', 'Detalhes do Curso')

{{-- Conteúdo Principal de Detalhes do Curso --}}
@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    {{-- Cabeçalho do Cartão com Botões de Navegação e Edição --}}
                    <div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center">
                        <div>
                            <h4 class="card-title mb-0">Detalhes Do Curso</h4>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('course.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fa fa-arrow-left me-1"></i> Voltar à Listagem
                            </a>
                            <a href="{{ route('course.edit', $course->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-pencil-alt me-1"></i> Editar Curso
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="row">
                            {{-- Coluna Esquerda: Nome, ID e Descrição do Curso --}}
                            <div class="col-xl-8 col-lg-7 mb-4">
                                <h2 class="text-dark font-w700 mb-2">{{ $course->name }}</h2>
                                {{-- <div class="mb-4">
                                    <span class="badge light badge-primary font-w600 fs-14">#ID {{ $course->id }}</span>
                                </div> --}}

                                <h5 class="text-primary font-w600 mb-2">Descrição</h5>
                                <div class="p-3 bg-light rounded">
                                    <p class="fs-16 text-dark mb-0" style="line-height: 1.8; white-space: pre-line;">{{ $course->description }}</p>
                                </div>
                            </div>

                            {{-- Coluna Direita: Painel de Informações Gerais (Com alta visibilidade e contraste) --}}
                            <div class="col-xl-4 col-lg-5">
                                <div class="p-4 rounded" style="background-color: #f3f4f9; border: 1px solid #e2e8f0;">
                                    <h4 class="text-primary font-w700 mb-4">Informações Gerais</h4>

                                    <ul class="list-group list-group-flush border-0 bg-transparent">
                                        {{-- ID do Curso --}}
                                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0 py-2 border-bottom border-light">
                                            <span class="text-dark font-w600 fs-15">
                                                <i class="fa fa-hashtag me-2 text-primary"></i> ID do Curso:
                                            </span>
                                            <span class="text-dark font-w700 fs-15">#{{ $course->id }}</span>
                                        </li>

                                        {{-- Estado do Curso --}}
                                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0 py-2 border-bottom border-light">
                                            <span class="text-dark font-w600 fs-15">
                                                <i class="fa fa-toggle-on me-2 text-primary"></i> Estado:
                                            </span>
                                            @if($course->status)
                                                <span class="badge light badge-success font-w700 fs-14 px-3 py-1">Activo</span>
                                            @else
                                                <span class="badge light badge-danger font-w700 fs-14 px-3 py-1">Inactivo</span>
                                            @endif
                                        </li>

                                        {{-- Preço / Valor --}}
                                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0 py-2 border-bottom border-light">
                                            <span class="text-dark font-w600 fs-15">
                                                <i class="fa fa-tag me-2 text-primary"></i> Preço:
                                            </span>
                                            <span class="text-dark font-w700 fs-16">{{ number_format($course->value, 2, ',', '.') }} Kz</span>
                                        </li>

                                        {{-- Carga Horária --}}
                                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0 py-2 border-bottom border-light">
                                            <span class="text-dark font-w600 fs-15">
                                                <i class="fa fa-clock me-2 text-primary"></i> Carga Horária:
                                            </span>
                                            <span class="text-dark font-w700 fs-15">{{ $course->duration }} horas</span>
                                        </li>

                                        {{-- Data de Criação --}}
                                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0 py-2">
                                            <span class="text-dark font-w600 fs-15">
                                                <i class="fa fa-calendar me-2 text-primary"></i> Data de Criação:
                                            </span>
                                            <span class="text-dark font-w700 fs-14">{{ $course->created_at ? $course->created_at->format('d/m/Y') : 'N/A' }}</span>
                                        </li>
                                    </ul>

                                    {{-- Botão de Eliminar Curso --}}
                                    {{-- <form action="{{ route('course.destroy', $course->id) }}" method="POST" class="mt-4" onsubmit="return confirm('Tem a certeza que deseja eliminar este curso?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-100 py-2 font-w600">
                                            <i class="fa fa-trash me-2"></i> Eliminar Curso
                                        </button>
                                    </form> --}}
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
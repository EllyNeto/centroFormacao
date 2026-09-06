{{-- Extende o layout principal unificado da aplicação --}}
@extends('layouts.main')

{{-- Define o título dinâmico da página --}}
@section('title', 'Adicionar Novo Curso')

{{-- Conteúdo principal da página de criação de curso --}}
@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Adicionar Novo Curso</h4>
                        <a href="{{ route('course.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left me-1"></i> Voltar à Listagem
                        </a>
                    </div>
                    
                    {{-- Formulário de submissão para criar um novo curso --}}
                    <form action="{{ route('course.store') }}" method="POST">
                        {{-- Directiva CSRF obrigatória para proteção contra ataques CSRF no Laravel --}}
                        @csrf
                        
                        <div class="card-body">
                            {{-- Exibição de erros de validação se houver algum campo inválido --}}
                            @if ($errors->any())
                                <div class="alert alert-danger alert-alt alert-dismissible fade show mb-4" role="alert">
                                    <div>
                                        <strong>Erro!</strong> Por favor verifique os seguintes problemas:
                                        <ul class="mb-0 mt-1 ps-3">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <div class="row">
                                {{-- Coluna Esquerda: Nome e Descrição do Curso --}}
                                <div class="col-xl-6 col-sm-6">
                                    {{-- Campo: Nome do Curso --}}
                                    <div class="mb-3">
                                        <label for="name" class="form-label text-primary">Nome do Curso <span class="text-danger">*</span></label>
                                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" placeholder="Ex: Programação Python Avançada" required>
                                    </div>

                                    {{-- Campo: Descrição / Programa do Curso --}}
                                    <div class="mb-3">
                                        <label for="description" class="form-label text-primary">Descrição do Curso</label>
                                        <textarea id="description" name="description" class="form-control" rows="5" placeholder="Resumo do programa curricular e objetivos do curso...">{{ old('description') }}</textarea>
                                    </div>
                                </div>

                                {{-- Coluna Direita: Estado e Duração em Horas --}}
                                <div class="col-xl-6 col-sm-6">
                                    {{-- Campo: Estado --}}
                                    <div class="mb-3">
                                        <label for="status" class="form-label text-primary">Estado <span class="text-danger">*</span></label>
                                        <select id="status" name="status" class="default-select wide form-control" required>
                                            <option value="1" selected>Activo</option>
                                            <option value="0">Desativo</option>
                                        </select>
                                    </div>

                                    {{-- Campo: Duração em Horas --}}
                                    <div class="mb-3">
                                        <label for="duration" class="form-label text-primary">Duração (em Horas) <span class="text-danger">*</span></label>
                                        <input type="number" id="duration" name="duration" class="form-control" value="{{ old('duration') }}" placeholder="Ex: 80" min="1" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Rodapé do cartão com os botões de ação --}}
                        <div class="card-footer text-end">
                            <a href="{{ route('course.index') }}" class="btn btn-danger light me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Salvar Curso</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
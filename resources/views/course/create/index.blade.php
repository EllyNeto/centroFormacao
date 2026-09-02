{{-- Extende o layout principal unificado da aplicação --}}
@extends('layouts.main.main')

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
                            <div class="row">
                                {{-- Coluna Esquerda: Nome e Descrição do Curso --}}
                                <div class="col-xl-6 col-sm-6">
                                    {{-- Campo: Nome do Curso --}}
                                    <div class="mb-3">
                                        <label for="name" class="form-label text-primary">Nome do Curso <span class="text-danger">*</span></label>
                                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Ex: Programação Python Avançada" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Campo: Descrição / Programa do Curso --}}
                                    <div class="mb-3">
                                        <label for="description" class="form-label text-primary">Descrição do Curso</label>
                                        <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="5" placeholder="Resumo do programa curricular e objetivos do curso...">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Coluna Direita: Categoria e Duração em Horas --}}
                                <div class="col-xl-6 col-sm-6">
                                    {{-- Campo: Categoria --}}
                                    <div class="mb-3">
                                        <label for="category" class="form-label text-primary">Categoria <span class="text-danger">*</span></label>
                                        <select id="category" name="category" class="default-select wide form-control @error('category') is-invalid @enderror" required>
                                            <option value="" disabled {{ old('category') ? '' : 'selected' }}>Selecione uma categoria...</option>
                                            <option value="Tecnologia da Informação" {{ old('category') == 'Tecnologia da Informação' ? 'selected' : '' }}>Tecnologia da Informação</option>
                                            <option value="Gestão & Negócios" {{ old('category') == 'Gestão & Negócios' ? 'selected' : '' }}>Gestão & Negócios</option>
                                            <option value="Contabilidade" {{ old('category') == 'Contabilidade' ? 'selected' : '' }}>Contabilidade</option>
                                            <option value="Línguas" {{ old('category') == 'Línguas' ? 'selected' : '' }}>Línguas</option>
                                            <option value="Design & Multimédia" {{ old('category') == 'Design & Multimédia' ? 'selected' : '' }}>Design & Multimédia</option>
                                        </select>
                                        @error('category')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Campo: Duração em Horas --}}
                                    <div class="mb-3">
                                        <label for="duration" class="form-label text-primary">Duração (em Horas) <span class="text-danger">*</span></label>
                                        <input type="number" id="duration" name="duration" class="form-control @error('duration') is-invalid @enderror" value="{{ old('duration') }}" placeholder="Ex: 80" min="1" required>
                                        @error('duration')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
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
{{-- Extende o layout principal unificado da aplicação --}}
@extends('layouts.main.main')

{{-- Define o título dinâmico da página --}}
@section('title', 'Editar Curso')

{{-- Conteúdo principal da página de edição de curso --}}
@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Editar Curso: {{ $course->name }}</h4>
                        <a href="{{ route('course.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left me-1"></i> Voltar à Listagem
                        </a>
                    </div>

                    {{-- Formulário de submissão para atualizar os dados do curso --}}
                    <form action="{{ route('course.update', $course->id) }}" method="POST">
                        {{-- Directiva CSRF para proteção de formulário no Laravel --}}
                        @csrf
                        {{-- Simulação do método HTTP PUT necessário para rotas de atualização --}}
                        @method('PUT')

                        <div class="card-body">
                            <div class="row">
                                {{-- Coluna Esquerda: Nome e Descrição --}}
                                <div class="col-xl-6 col-sm-6">
                                    {{-- Campo: Nome do Curso --}}
                                    <div class="mb-3">
                                        <label for="name" class="form-label text-primary">Nome do Curso <span class="text-danger">*</span></label>
                                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $course->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Campo: Descrição / Programa do Curso --}}
                                    <div class="mb-3">
                                        <label for="description" class="form-label text-primary">Descrição do Curso</label>
                                        <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="5">{{ old('description', $course->description) }}</textarea>
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
                                            <option value="" disabled>Selecione uma categoria...</option>
                                            @php $cat = old('category', $course->category); @endphp
                                            <option value="Tecnologia da Informação" {{ $cat == 'Tecnologia da Informação' ? 'selected' : '' }}>Tecnologia da Informação</option>
                                            <option value="Gestão & Negócios" {{ $cat == 'Gestão & Negócios' ? 'selected' : '' }}>Gestão & Negócios</option>
                                            <option value="Contabilidade" {{ $cat == 'Contabilidade' ? 'selected' : '' }}>Contabilidade</option>
                                            <option value="Línguas" {{ $cat == 'Línguas' ? 'selected' : '' }}>Línguas</option>
                                            <option value="Design & Multimédia" {{ $cat == 'Design & Multimédia' ? 'selected' : '' }}>Design & Multimédia</option>
                                        </select>
                                        @error('category')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Campo: Duração em Horas --}}
                                    <div class="mb-3">
                                        <label for="duration" class="form-label text-primary">Duração (em Horas) <span class="text-danger">*</span></label>
                                        <input type="number" id="duration" name="duration" class="form-control @error('duration') is-invalid @enderror" value="{{ old('duration', $course->duration) }}" min="1" required>
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
                            <button type="submit" class="btn btn-primary">Atualizar Curso</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

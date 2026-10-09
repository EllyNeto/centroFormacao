{{-- 
    Vista: Formulário de Cadastro de Novo Curso (admin/course/create/index.blade.php)
    Descrição: Permite ao utilizador registar o nome do curso, carga horária em horas, 
               preço em Kwanzas (AOA), estado de atividade e descrição do programa formativo.
--}}
@extends('layout.main')

@section('title', 'Adicionar Novo Curso')

@section('content')
{{-- Estrutura principal da página --}}
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                {{-- Cartão do Formulário --}}
                <div class="card">
                    
                    {{-- Cabeçalho com botão de regresso à listagem --}}
                    <div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center">
                        <div>
                            <h4 class="card-title mb-1">Adicionar Novo Curso</h4>
                            <p class="m-0 subtitle text-muted">Preencha os dados da oferta formativa para disponibiizar no centro</p>
                        </div>
                        <a href="{{ route('course.index') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-arrow-left me-1"></i> Voltar à Listagem
                        </a>
                    </div>

                    <div class="card-body p-4">
                        {{-- Alerta global de erros de validação --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show mb-4">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- Formulário de Envio POST para a rota course.store --}}
                        <form action="{{ route('course.store') }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                {{-- Campo: Nome do Curso (Único) --}}
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label text-primary">Nome do Curso <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Ex: Programação Web com Laravel" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Campo: Duração em Horas --}}
                                <div class="col-md-6 mb-3">
                                    <label for="duration" class="form-label text-primary">Carga Horária (Horas) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('duration') is-invalid @enderror" id="duration" name="duration" value="{{ old('duration') }}" placeholder="Ex: 60" min="1" required>
                                    @error('duration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Campo: Preço / Valor do Curso (Kz) --}}
                                <div class="col-md-6 mb-3">
                                    <label for="value" class="form-label text-primary">Preço do Curso (Kz) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0" class="form-control @error('value') is-invalid @enderror" id="value" name="value" value="{{ old('value') }}" placeholder="Ex: 45000" required>
                                    @error('value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Campo: Estado do Curso (Ativo / Inativo) --}}
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label text-primary">Estado do Curso <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                        <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Ativo</option>
                                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inativo</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Campo: Descrição / Programa Formativo --}}
                                <div class="col-md-12 mb-4">
                                    <label for="description" class="form-label text-primary">Descrição / Conteúdo Programático</label>
                                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="5" placeholder="Descreva os objetivos, módulos e requisitos do curso...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Rodapé com botão de envio --}}
                            <div class="card-footer text-end px-0 pb-0 bg-transparent border-0">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save me-1"></i> Guardar Curso
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
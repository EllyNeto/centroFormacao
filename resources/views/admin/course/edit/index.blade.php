{{-- Extende o layout principal do painel administrativo --}}
@extends('layout.main')

{{-- Define o título da página --}}
@section('title', 'Editar Curso')

{{-- Conteúdo Principal do Formulário de Edição --}}
@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    {{-- Cabeçalho do Cartão com botão de retorno à listagem --}}
                    <div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center">
                        <div>
                            <h4 class="card-title mb-1">Editar Curso</h4>
                            <p class="m-0 subtitle text-muted">Actualize os dados do curso "{{ $course->name }}"</p>
                        </div>
                        <a href="{{ route('course.index') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-list me-1"></i> Ver Todos os Cursos
                        </a>
                    </div>

                    <div class="card-body p-4">
                        {{-- Exibição dos erros de validação --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show mb-4">
                                <strong>Erro ao actualizar o curso!</strong> Por favor, verifique os campos destacados abaixo.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- Formulário de Actualização com Método PUT para 'course.update' --}}
                        <form action="{{ route('course.update', $course->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                {{-- Campo: Nome do Curso --}}
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label font-w600">Nome do Curso <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $course->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Campo: Duração em Horas --}}
                                <div class="col-md-6 mb-3">
                                    <label for="duration" class="form-label font-w600">Duração (Horas) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('duration') is-invalid @enderror" id="duration" name="duration" value="{{ old('duration', $course->duration) }}" min="1" required>
                                    @error('duration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Campo: Valor do Curso --}}
                                <div class="col-md-6 mb-3">
                                    <label for="value" class="form-label font-w600">Valor do Curso (Kz) <span class="text-danger">*</span></label>
                                    <input type="text" inputmode="numeric" class="form-control currency-mask @error('value') is-invalid @enderror" id="value" name="value" value="{{ old('value', $course->value) }}" placeholder="Ex: 120.000" required>
                                    @error('value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Campo: Estado do Curso --}}
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label font-w600">Estado do Curso <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                        <option value="1" {{ old('status', $course->status) == '1' ? 'selected' : '' }}>Activo</option>
                                        <option value="0" {{ old('status', $course->status) == '0' ? 'selected' : '' }}>Inactivo</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Campo: Descrição do Curso --}}
                                <div class="col-md-12 mb-4">
                                    <label for="description" class="form-label font-w600">Descrição do Curso <span class="text-danger">*</span></label>
                                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="5" required>{{ old('description', $course->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Botões de Acção --}}
                            <div class="d-flex justify-content-end gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save me-1"></i> Actualizar Curso
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
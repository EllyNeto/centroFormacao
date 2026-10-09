{{-- 
    Vista: Formulário de Edição de Curso (admin/course/edit/index.blade.php)
    Descrição: Permite atualizar o nome, carga horária, preço em Kwanzas, estado e descrição do curso.
--}}
@extends('layout.main')

@section('title', 'Editar Curso')

@section('content')
{{-- Estrutura principal da página --}}
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                {{-- Cartão do Formulário de Edição --}}
                <div class="card">
                    
                    {{-- Cabeçalho do Cartão com o nome do curso e botão de regresso --}}
                    <div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center">
                        <div>
                            <h4 class="card-title mb-1">Editar Curso: {{ $course->name }}</h4>
                            <p class="m-0 subtitle text-muted">Atualize os dados da oferta formativa</p>
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

                        {{-- Formulário com Método PUT para a rota course.update --}}
                        <form action="{{ route('course.update', $course->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                {{-- Campo: Nome do Curso --}}
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label text-primary">Nome do Curso <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $course->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Campo: Duração em Horas --}}
                                <div class="col-md-6 mb-3">
                                    <label for="duration" class="form-label text-primary">Carga Horária (Horas) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('duration') is-invalid @enderror" id="duration" name="duration" value="{{ old('duration', $course->duration) }}" min="1" required>
                                    @error('duration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Campo: Preço / Valor do Curso (Kz) --}}
                                <div class="col-md-6 mb-3">
                                    <label for="value" class="form-label text-primary">Preço do Curso (Kz) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0" class="form-control @error('value') is-invalid @enderror" id="value" name="value" value="{{ old('value', $course->value) }}" required>
                                    @error('value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Campo: Estado do Curso --}}
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label text-primary">Estado do Curso <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                        <option value="1" {{ old('status', $course->status ? '1' : '0') == '1' ? 'selected' : '' }}>Ativo</option>
                                        <option value="0" {{ old('status', $course->status ? '1' : '0') == '0' ? 'selected' : '' }}>Inativo</option>
                                    </select>
                                    <small class="text-muted d-block mt-1">
                                        Não é possível desativar um curso que tenha turmas ativas ou inscrições pendentes vinculadas.
                                    </small>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Campo: Descrição / Programa Formativo --}}
                                <div class="col-md-12 mb-4">
                                    <label for="description" class="form-label text-primary">Descrição / Conteúdo Programático</label>
                                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="5">{{ old('description', $course->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Rodapé com botões de ação --}}
                            <div class="card-footer text-end px-0 pb-0 bg-transparent border-0">
                                <a href="{{ route('course.index') }}" class="btn btn-danger light me-2">Cancelar</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-refresh me-1"></i> Atualizar Curso
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
{{-- 
    Vista: Formulário de Edição de Sala de Aula (admin/classroom/edit/index.blade.php)
    Descrição: Permite editar o número da sala, a capacidade e as observações.
               Exibe aviso informativo sobre a sincronização da capacidade com as turmas associadas.
--}}
@extends('layout.main')

@section('title', 'Editar Sala de Aula')

@section('content')
{{-- Estrutura principal da página --}}
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                {{-- Cartão do Formulário de Edição --}}
                <div class="card">
                    
                    {{-- Cabeçalho do Cartão com o número da sala atual e botão de regresso --}}
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h4 class="card-title">Editar Sala: {{ $classroom->number_of_classroom }}</h4>
                        <a href="{{ route('classroom.index') }}" class="btn btn-primary">
                            <i class="fa fa-arrow-left me-2"></i> Voltar à Listagem
                        </a>
                    </div>
                    
                    {{-- Formulário de envio PUT para a rota classroom.update --}}
                    <form action="{{ route('classroom.update', $classroom->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="card-body">
                            {{-- Apresentação global de erros de validação caso existam --}}
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

                            <div class="row">
                                {{-- Campo: Número da Sala --}}
                                <div class="col-xl-6 col-sm-6 mb-3">
                                    <label for="number_of_classroom" class="form-label text-primary">
                                        Número da Sala <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" min="1" name="number_of_classroom" id="number_of_classroom" 
                                           class="form-control @error('number_of_classroom') is-invalid @enderror" 
                                           value="{{ old('number_of_classroom', $classroom->number_of_classroom) }}" required>
                                    @error('number_of_classroom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Campo: Capacidade Física de Alunos --}}
                                <div class="col-xl-6 col-sm-6 mb-3">
                                    <label for="capacity" class="form-label text-primary">
                                        Capacidade de Alunos <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" min="1" name="capacity" id="capacity" 
                                           class="form-control @error('capacity') is-invalid @enderror" 
                                           value="{{ old('capacity', $classroom->capacity) }}" required>
                                    <small class="text-muted d-block mt-1">
                                        A alteração da capacidade atualizará automaticamente a capacidade máxima de todas as turmas alocadas a esta sala.
                                    </small>
                                    @error('capacity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Campo: Descrição / Observações Opcionais --}}
                                <div class="col-xl-12 col-sm-12 mb-3">
                                    <label for="description" class="form-label text-primary">Observações </label>
                                    <textarea name="description" id="description" 
                                              class="form-control @error('description') is-invalid @enderror" 
                                              rows="4">{{ old('description', $classroom->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Rodapé do Cartão com botões de cancelamento e atualização --}}
                        <div class="card-footer text-end">
                            <a href="{{ route('classroom.index') }}" class="btn btn-danger light me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-refresh me-1"></i> Atualizar Sala
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

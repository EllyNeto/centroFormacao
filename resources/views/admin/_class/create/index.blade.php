{{-- Extende o layout principal unificado da aplicação --}}
@extends('layouts.main')

{{-- Define o título dinâmico da página --}}
@section('title', 'Adicionar Nova Turma')

{{-- Conteúdo principal da página de registo de turma --}}
@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    {{-- Cabeçalho do cartão com título e botão para voltar --}}
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Adicionar Nova Turma</h4>
                        <a href="{{ route('class.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left me-1"></i> Voltar à Listagem
                        </a>
                    </div>

                    {{-- Formulário de submissão de nova turma --}}
                    <form action="{{ route('class.store') }}" method="POST">
                        @csrf

                        <div class="card-body">
                            {{-- Exibição de alertas de erro de validação no modelo Alerts Alt --}}
                            @if ($errors->any())
                                <div class="alert alert-danger alert-alt alert-dismissible fade show mb-4" role="alert">
                                    <strong>Erro!</strong> Por favor, verifique os erros abaixo:
                                    <ul class="mb-0 mt-2 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <div class="row">
                                {{-- Coluna Esquerda: Nome, Código, Curso e Formador --}}
                                <div class="col-xl-6 col-sm-6">
                                    {{-- Campo: Nome da Turma --}}
                                    <div class="mb-3">
                                        <label for="name" class="form-label text-primary">Nome da Turma <span class="text-danger">*</span></label>
                                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" placeholder="Ex: Turma Web Dev 01 - Manhã" required>
                                    </div>

                                    {{-- Campo: Código da Turma --}}
                                    <div class="mb-3">
                                        <label for="code" class="form-label text-primary">Código da Turma <span class="text-danger">*</span></label>
                                        <input type="text" id="code" name="code" class="form-control" value="{{ old('code', 'TURMA-'.date('Y').'-'.rand(10,99)) }}" placeholder="Ex: TURMA-2026-A1" required>
                                    </div>

                                    {{-- Campo: Curso --}}
                                    <div class="mb-3">
                                        <label for="course_id" class="form-label text-primary">Curso Associado</label>
                                        <select id="course_id" name="course_id" class="default-select wide form-control">
                                            <option value="">Selecione um curso (Opcional)</option>
                                            @foreach($courses as $course)
                                                <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
                                            @endforeach
                                            @if($courses->isEmpty())
                                                <option value="" >Nenhum curso foi adcicionado.</option>
                                            @endif
                                        </select>
                                    </div>

                                    {{-- Campo: Formador Responsável --}}
                                    <div class="mb-3">
                                        <label for="teacher_id" class="form-label text-primary">Formador Responsável</label>
                                        <select id="teacher_id" name="teacher_id" class="default-select wide form-control">
                                            <option value="">Selecione um formador</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                                    {{ $teacher->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="start_time" class="form-label text-primary">Hora de Início</label>
                                        <input type="time" name="start_time" id="start_time" class="form-control" value="{{ old('start_time') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="end_time" class="form-label text-primary">Hora de Término</label>
                                        <input type="time" name="end_time" id="end_time" class="form-control" value="{{ old('end_time') }}">
                                    </div>
                                </div>

                                {{-- Coluna Direita: Sala, Turno, Capacidade e Estado --}}
                                <div class="col-xl-6 col-sm-6">
                                    {{-- Campo: Sala / Local --}}
                                    {{-- <div class="mb-3">
                                        <label for="room" class="form-label text-primary">Sala / Localização</label>
                                        <input type="text" id="room" name="room" class="form-control" value="{{ old('room') }}" placeholder="Ex: Sala 101 - Bloco A">
                                    </div> --}}

                                    {{-- Campo: Turno --}}
                                    <div class="mb-3">
                                        <label for="shift" class="form-label text-primary">Turno <span class="text-danger">*</span></label>
                                        <select id="shift" name="shift" class="default-select wide form-control" required>
                                            <option value="Manhã" {{ old('shift') == 'Manhã' ? 'selected' : '' }}>Manhã</option>
                                            <option value="Tarde" {{ old('shift') == 'Tarde' ? 'selected' : '' }}>Tarde</option>
                                            <option value="Pós-Laboral" {{ old('shift') == 'Pós-Laboral' ? 'selected' : '' }}>Pós-Laboral</option>
                                        </select>
                                    </div>

                                    {{-- Campo: Capacidade --}}
                                    <div class="mb-3">
                                        <label for="capacity" class="form-label text-primary">Capacidade Máxima <span class="text-danger">*</span></label>
                                        <input type="number" min="1" id="capacity" name="capacity" class="form-control" value="{{ old('capacity', 25) }}" required>
                                    </div>

                                    {{-- Campo: Estado da Turma --}}
                                    <div class="mb-3">
                                        <label for="status" class="form-label text-primary">Estado da Turma <span class="text-danger">*</span></label>
                                        <select id="status" name="status" class="default-select wide form-control" required>
                                            <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Activa</option>
                                            <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>Inactiva</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-primary" ><ion-icon name="form-label text-primary"></ion-icon> Dias da Semana:<span class="text-danger">*</span></label>
                                        <div class="checkbox-grid">
                                            <label class="checkbox-chip text-primary">
                                                <input type="checkbox" name="days_of_week[]" value="Segunda-feira">
                                                <span>Segunda-feira</span>
                                            </label>
                                            <label class="checkbox-chip text-primary">
                                                <input type="checkbox" name="days_of_week[]" value="Terça-feira">
                                                <span>Terça-feira</span>
                                            </label>
                                            <label class="checkbox-chip text-primary">
                                                <input type="checkbox" name="days_of_week[]" value="Quarta-feira">
                                                <span>Quarta-feira</span>
                                            </label>
                                            <label class="checkbox-chip text-primary">
                                                <input type="checkbox" name="days_of_week[]" value="Quinta-feira">
                                                <span>Quinta-feira</span>
                                            </label>
                                            <label class="checkbox-chip text-primary">
                                                <input type="checkbox" name="days_of_week[]" value="Sexta-feira">
                                                <span>Sexta-feira</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Rodapé com botões de ação --}}
                        <div class="card-footer text-end">
                            <a href="{{ route('class.index') }}" class="btn btn-danger light me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Salvar Turma</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

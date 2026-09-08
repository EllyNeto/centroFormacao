@extends('layouts.main')

@section('title', 'Adicionar Nova Turma')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Adicionar Nova Turma</h4>
                        <a href="{{ route('class.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left me-1"></i> Voltar à Listagem
                        </a>
                    </div>

                    <form action="{{ route('class.store') }}" method="POST">
                        @csrf

                        <div class="card-body">
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
                                <div class="col-xl-6 col-sm-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label text-primary">Nome da Turma <span class="text-danger">*</span></label>
                                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" placeholder="Ex: Turma Web Dev 01 - Manhã" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="code" class="form-label text-primary">Código da Turma <span class="text-danger">*</span></label>
                                        <input type="text" id="code" name="code" class="form-control" readonly value="{{ old('code', 'TURMA-'.date('Y').'-'.rand(10,99)) }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="course_id" class="form-label text-primary">Curso Associado <span class="text-danger">*</span></label>
                                        <select id="course_id" name="course_id" class="default-select wide form-control" required>
                                            <option value="">Selecione o Curso</option>
                                            @foreach($courses as $course)
                                                <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="teacher_id" class="form-label text-primary">Formador Responsável <span class="text-danger">*</span></label>
                                        <select id="teacher_id" name="teacher_id" class="default-select wide form-control" required>
                                            <option value="">Selecione o Formador</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                                    {{ $teacher->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="student_id" class="form-label text-primary">Estudante Associado (Opcional)</label>
                                        <select id="student_id" name="student_id" class="default-select wide form-control">
                                            <option value="">Selecione um Estudante (Opcional)</option>
                                            @foreach($students as $student)
                                                <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                                    {{ $student->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="falta" class="form-label text-primary">Número de Faltas</label>
                                        <input type="number" min="0" id="falta" name="falta" class="form-control" value="{{ old('falta', 0) }}">
                                    </div>
                                </div>

                                <div class="col-xl-6 col-sm-6">
                                    <div class="mb-3">
                                        <label for="start_time" class="form-label text-primary">Hora de Início <span class="text-danger">*</span></label>
                                        <input type="time" name="start_time" id="start_time" class="form-control" value="{{ old('start_time', '08:00') }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="end_time" class="form-label text-primary">Hora de Término <span class="text-danger">*</span></label>
                                        <input type="time" name="end_time" id="end_time" class="form-control" value="{{ old('end_time', '12:00') }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="shift" class="form-label text-primary">Turno <span class="text-danger">*</span></label>
                                        <select id="shift" name="shift" class="default-select wide form-control" required>
                                            <option value="Manhã" {{ old('shift') == 'Manhã' ? 'selected' : '' }}>Manhã</option>
                                            <option value="Tarde" {{ old('shift') == 'Tarde' ? 'selected' : '' }}>Tarde</option>
                                            <option value="Pós-Laboral" {{ old('shift') == 'Pós-Laboral' ? 'selected' : '' }}>Pós-Laboral</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="capacity" class="form-label text-primary">Capacidade Máxima <span class="text-danger">*</span></label>
                                        <input type="number" min="1" id="capacity" name="capacity" class="form-control" value="{{ old('capacity', 25) }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="status" class="form-label text-primary">Estado da Turma <span class="text-danger">*</span></label>
                                        <select id="status" name="status" class="default-select wide form-control" required>
                                            <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Activa</option>
                                            <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>Inactiva</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-primary">Dias da Semana <span class="text-danger">*</span></label>
                                        <div class="checkbox-grid">
                                            <label class="checkbox-chip text-primary me-2">
                                                <input type="checkbox" name="days_of_week[]" value="Segunda-feira" checked>
                                                <span>Segunda-feira</span>
                                            </label>
                                            <label class="checkbox-chip text-primary me-2">
                                                <input type="checkbox" name="days_of_week[]" value="Terça-feira" checked>
                                                <span>Terça-feira</span>
                                            </label>
                                            <label class="checkbox-chip text-primary me-2">
                                                <input type="checkbox" name="days_of_week[]" value="Quarta-feira" checked>
                                                <span>Quarta-feira</span>
                                            </label>
                                            <label class="checkbox-chip text-primary me-2">
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

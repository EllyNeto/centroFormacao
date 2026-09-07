@extends('layouts.main')

@section('title', 'Editar Turma')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Editar Turma: #{{ $class->id }} - {{ $class->name }}</h4>
                        <a href="{{ route('class.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left me-1"></i> Voltar à Listagem
                        </a>
                    </div>

                    <form action="{{ route('class.update', $class->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger alert-alt alert-dismissible fade show mb-4" role="alert">
                                    <strong>Erro!</strong> Por favor, verifique os erros abaixo ao atualizar a turma:
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
                                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $class->name) }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="code" class="form-label text-primary">Código da Turma (Não Editável)</label>
                                        <input type="text" id="code" class="form-control" value="{{ $class->code }}" readonly disabled>
                                    </div>

                                    <div class="mb-3">
                                        <label for="course_id" class="form-label text-primary">Curso Associado <span class="text-danger">*</span></label>
                                        <select id="course_id" name="course_id" class="default-select wide form-control" required>
                                            <option value="">Selecione um curso</option>
                                            @foreach($courses as $course)
                                                <option value="{{ $course->id }}" {{ old('course_id', $class->course_id) == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="teacher_id" class="form-label text-primary">Formador Responsável <span class="text-danger">*</span></label>
                                        <select id="teacher_id" name="teacher_id" class="default-select wide form-control" required>
                                            <option value="">Selecione um formador</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}" {{ old('teacher_id', $class->teacher_id) == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="student_id" class="form-label text-primary">Estudante Associado (Opcional)</label>
                                        <select id="student_id" name="student_id" class="default-select wide form-control">
                                            <option value="">Nenhum Estudante Associado</option>
                                            @foreach($students as $student)
                                                <option value="{{ $student->id }}" {{ old('student_id', $class->student_id) == $student->id ? 'selected' : '' }}>
                                                    {{ $student->name }} (BI: {{ $student->identity_card_number }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="falta" class="form-label text-primary">Número de Faltas</label>
                                        <input type="number" min="0" id="falta" name="falta" class="form-control" value="{{ old('falta', $class->falta ?? 0) }}">
                                    </div>
                                </div>

                                <div class="col-xl-6 col-sm-6">
                                    <div class="mb-3">
                                        <label for="start_time" class="form-label text-primary">Hora de Início <span class="text-danger">*</span></label>
                                        <input type="time" name="start_time" id="start_time" class="form-control" value="{{ old('start_time', date('H:i', strtotime($class->start_time))) }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="end_time" class="form-label text-primary">Hora de Término <span class="text-danger">*</span></label>
                                        <input type="time" name="end_time" id="end_time" class="form-control" value="{{ old('end_time', date('H:i', strtotime($class->end_time))) }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="shift" class="form-label text-primary">Turno <span class="text-danger">*</span></label>
                                        <select id="shift" name="shift" class="default-select wide form-control" required>
                                            <option value="Manhã" {{ old('shift', $class->shift) == 'Manhã' ? 'selected' : '' }}>Manhã</option>
                                            <option value="Tarde" {{ old('shift', $class->shift) == 'Tarde' ? 'selected' : '' }}>Tarde</option>
                                            <option value="Pós-Laboral" {{ old('shift', $class->shift) == 'Pós-Laboral' ? 'selected' : '' }}>Pós-Laboral</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="capacity" class="form-label text-primary">Capacidade Máxima <span class="text-danger">*</span></label>
                                        <input type="number" min="1" id="capacity" name="capacity" class="form-control" value="{{ old('capacity', $class->capacity) }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="status" class="form-label text-primary">Estado da Turma <span class="text-danger">*</span></label>
                                        <select id="status" name="status" class="default-select wide form-control" required>
                                            <option value="1" {{ old('status', $class->status) ? 'selected' : '' }}>Activa</option>
                                            <option value="0" {{ !old('status', $class->status) ? 'selected' : '' }}>Inactiva</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-primary">Dias da Semana <span class="text-danger">*</span></label>
                                        @php
                                            $selectedDays = is_array($class->days_of_week) ? $class->days_of_week : [];
                                        @endphp
                                        <div class="checkbox-grid">
                                            <label class="checkbox-chip text-primary me-2">
                                                <input type="checkbox" name="days_of_week[]" value="Segunda-feira" {{ in_array('Segunda-feira', $selectedDays) ? 'checked' : '' }}>
                                                <span>Segunda-feira</span>
                                            </label>
                                            <label class="checkbox-chip text-primary me-2">
                                                <input type="checkbox" name="days_of_week[]" value="Terça-feira" {{ in_array('Terça-feira', $selectedDays) ? 'checked' : '' }}>
                                                <span>Terça-feira</span>
                                            </label>
                                            <label class="checkbox-chip text-primary me-2">
                                                <input type="checkbox" name="days_of_week[]" value="Quarta-feira" {{ in_array('Quarta-feira', $selectedDays) ? 'checked' : '' }}>
                                                <span>Quarta-feira</span>
                                            </label>
                                            <label class="checkbox-chip text-primary me-2">
                                                <input type="checkbox" name="days_of_week[]" value="Quinta-feira" {{ in_array('Quinta-feira', $selectedDays) ? 'checked' : '' }}>
                                                <span>Quinta-feira</span>
                                            </label>
                                            <label class="checkbox-chip text-primary">
                                                <input type="checkbox" name="days_of_week[]" value="Sexta-feira" {{ in_array('Sexta-feira', $selectedDays) ? 'checked' : '' }}>
                                                <span>Sexta-feira</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <a href="{{ route('class.index') }}" class="btn btn-danger light me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Atualizar Turma</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

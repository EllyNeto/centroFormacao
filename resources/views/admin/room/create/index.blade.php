@extends('layout.main')

@section('title', 'Adicionar Nova Turma')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h4 class="card-title">Adicionar Nova Turma</h4>
                        <a href="{{ route('room.index') }}" class="btn btn-primary">
                            <i class="fa fa-arrow-left me-2"></i> Voltar à Listagem
                        </a>
                    </div>
                    
                    <form action="{{ route('room.store') }}" method="POST">
                        @csrf
                        
                        <div class="card-body">
                            {{-- Exibição de Erros de Validação --}}
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
                                {{-- Coluna Esquerda --}}
                                <div class="col-xl-6 col-sm-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label text-primary">Nome da Turma <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Ex: Turma de Informática-A" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="course_id" class="form-label text-primary">Curso<span class="text-danger">*</span></label>
                                        <select id="course_id" name="course_id" class="form-control select2 @error('course_id') is-invalid @enderror" required>
                                            <option value="">Selecione o Curso</option>
                                            @foreach($courses as $course)
                                                <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                                    {{ $course->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('course_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="teacher_id" class="form-label text-primary">Formador<span class="text-danger">*</span></label>
                                        <select id="teacher_id" name="teacher_id" class="form-control select2 @error('teacher_id') is-invalid @enderror" required>
                                            <option value="">Selecione o Formador</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                                    {{ $teacher->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('teacher_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="max_capacity" class="form-label text-primary">Capacidade Máxima <span class="text-danger">*</span></label>
                                        <input type="number" min="1" id="max_capacity" name="max_capacity" class="form-control @error('max_capacity') is-invalid @enderror" value="{{ old('max_capacity') }}" placeholder="Ex: 25" required>
                                        @error('max_capacity')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Coluna Direita --}}
                                <div class="col-xl-6 col-sm-6">
                                    <div class="mb-3">
                                        <label for="start_time" class="form-label text-primary">Hora de Início <span class="text-danger">*</span></label>
                                        <input type="time" name="start_time" id="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time') }}" required>
                                        @error('start_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="end_time" class="form-label text-primary">Hora de Término <span class="text-danger">*</span></label>
                                        <input type="time" name="end_time" id="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time') }}" required>
                                        @error('end_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="shift" class="form-label text-primary">Turno <span class="text-danger">*</span></label>
                                        <select id="shift" name="shift" class="form-control @error('shift') is-invalid @enderror" required>
                                            <option value="Manhã" {{ old('shift', 'Manhã') == 'Manhã' ? 'selected' : '' }}>Manhã</option>
                                            <option value="Tarde" {{ old('shift') == 'Tarde' ? 'selected' : '' }}>Tarde</option>
                                            <option value="Pós-Laboral" {{ old('shift') == 'Pós-Laboral' ? 'selected' : '' }}>Pós-Laboral</option>
                                        </select>
                                        @error('shift')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-primary">Dias da Semana <span class="text-danger">*</span></label>
                                        <div class="d-flex flex-wrap gap-3 mt-1">
                                            @php
                                                $days = ['Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];
                                                $selectedDays = old('days_of_week');
                                                if(is_string($selectedDays)) {
                                                    $selectedDays = array_map('trim', explode(',', $selectedDays));
                                                }
                                            @endphp
                                            @foreach($days as $day)
                                                <div class="form-check me-3 mb-2">
                                                    <input class="form-check-input" type="checkbox" name="days_of_week[]" value="{{ $day }}" id="day_{{ $loop->index }}"
                                                        {{ (is_array($selectedDays) && in_array($day, $selectedDays)) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-primary" for="day_{{ $loop->index }}">
                                                        {{ $day }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                        @error('days_of_week')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary">Guadar Turma</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layout.main')

@section('title', 'Editar Turma')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h4 class="card-title">Editar Turma</h4>
                        <a href="{{ route('room.index') }}" class="btn btn-primary">
                            <i class="fa fa-arrow-left me-2"></i> Voltar à Listagem
                        </a>
                    </div>
                    
                    <form action="{{ route('room.update', $room->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
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
                                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $room->name) }}" placeholder="Ex: Turma A - Manhã" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="course_id" class="form-label text-primary">Curso Associado <span class="text-danger">*</span></label>
                                        <select id="course_id" name="course_id" class="form-control select2 @error('course_id') is-invalid @enderror" required>
                                            <option value="">Selecione o Curso</option>
                                            @foreach($courses as $course)
                                                <option value="{{ $course->id }}" {{ old('course_id', $room->course_id) == $course->id ? 'selected' : '' }}>
                                                    {{ $course->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('course_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="teacher_id" class="form-label text-primary">Formador Responsável <span class="text-danger">*</span></label>
                                        <select id="teacher_id" name="teacher_id" class="form-control select2 @error('teacher_id') is-invalid @enderror" required>
                                            <option value="">Selecione o Formador</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}" {{ old('teacher_id', $room->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                                    {{ $teacher->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('teacher_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="classroom_id" class="form-label text-primary">Sala de Aula</label>
                                        <select id="classroom_id" name="classroom_id" class="form-control select2 @error('classroom_id') is-invalid @enderror">
                                            <option value="" data-capacity="">Selecione a Sala (Opcional)</option>
                                            @foreach($classrooms as $classroom)
                                                <option value="{{ $classroom->id }}" data-capacity="{{ $classroom->capacity }}" {{ old('classroom_id', $room->classroom_id) == $classroom->id ? 'selected' : '' }}>
                                                    {{ $classroom->name }} (Sala {{ $classroom->number_of_classroom }}) {{ $classroom->capacity ? '- ' . $classroom->capacity . ' lugares' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('classroom_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="max_capacity" class="form-label text-primary">Capacidade Máxima <span class="text-danger">*</span></label>
                                        <input type="number" min="1" id="max_capacity" name="max_capacity" class="form-control bg-light @error('max_capacity') is-invalid @enderror" value="{{ old('max_capacity', $room->max_capacity) }}" placeholder="Definido pela sala" readonly style="background-color: #e9ecef !important; cursor: not-allowed;" required>
                                        <small class="text-muted d-block mt-1">Definido automaticamente com base na capacidade da sala selecionada.</small>
                                        @error('max_capacity')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Coluna Direita --}}
                                <div class="col-xl-6 col-sm-6">
                                    <div class="mb-3">
                                        <label for="start_time" class="form-label text-primary">Hora de Início <span class="text-danger">*</span></label>
                                        <input type="time" name="start_time" id="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time', $room->start_time) }}" required>
                                        @error('start_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="end_time" class="form-label text-primary">Hora de Término <span class="text-danger">*</span></label>
                                        <input type="time" name="end_time" id="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time', $room->end_time) }}" required>
                                        @error('end_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="shift" class="form-label text-primary">Turno <span class="text-danger">*</span></label>
                                        <select id="shift" name="shift" class="form-control @error('shift') is-invalid @enderror" required>
                                            <option value="Manhã" {{ old('shift', $room->shift) == 'Manhã' ? 'selected' : '' }}>Manhã</option>
                                            <option value="Tarde" {{ old('shift', $room->shift) == 'Tarde' ? 'selected' : '' }}>Tarde</option>
                                            <option value="Pós-Laboral" {{ old('shift', $room->shift) == 'Pós-Laboral' ? 'selected' : '' }}>Pós-Laboral</option>
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
                                                $savedDays = old('days_of_week', $room->days_of_week);
                                                if (is_string($savedDays)) {
                                                    $selectedDays = array_map('trim', explode(',', $savedDays));
                                                } else {
                                                    $selectedDays = (array)$savedDays;
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
                            <a href="{{ route('room.index') }}" class="btn btn-danger light me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Atualizar Turma</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const classroomSelect = document.getElementById('classroom_id');
    const maxCapacityInput = document.getElementById('max_capacity');

    function updateCapacity() {
        if (!classroomSelect || !maxCapacityInput) return;
        const selectedOption = classroomSelect.options[classroomSelect.selectedIndex];
        if (selectedOption && selectedOption.dataset && selectedOption.dataset.capacity) {
            maxCapacityInput.value = selectedOption.dataset.capacity;
        } else if (!classroomSelect.value) {
            maxCapacityInput.value = '';
        }
    }

    if (classroomSelect && maxCapacityInput) {
        classroomSelect.addEventListener('change', updateCapacity);
        if (window.jQuery) {
            $(classroomSelect).on('change', updateCapacity);
        }
        if (!maxCapacityInput.value) {
            updateCapacity();
        }
    }
});
</script>
@endsection
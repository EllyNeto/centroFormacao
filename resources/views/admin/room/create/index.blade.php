@extends('layout.main')

@section('title', 'Adicionar Nova Turma')

@section('content')
{{-- Estrutura principal da página de registo de turma --}}
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    {{-- Cabeçalho do Cartão com título e botão de regresso --}}
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h4 class="card-title mb-1">Adicionar Nova Turma</h4>
                            <p class="m-0 subtitle text-muted">Defina o curso, formador, sala física, horários e dias de formação</p>
                        </div>
                        <a href="{{ route('room.index') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-arrow-left me-1"></i> Voltar à Listagem
                        </a>
                    </div>
                    
                    {{-- Formulário de Criação de Turma --}}
                    <form action="{{ route('room.store') }}" method="POST">
                        @csrf
                        
                        <div class="card-body">
                            {{-- Exibição de Alertas de Erro de Validação --}}
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show mb-4 room-form-alert">
                                    <h6 class="alert-heading font-w600"><i class="fa fa-exclamation-triangle me-2"></i>Por favor corrija os seguintes erros:</h6>
                                    <ul class="mb-0 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <div class="row">
                                {{-- Coluna Esquerda: Dados Gerais e Pedagógicos --}}
                                <div class="col-xl-6 col-sm-6">
                                    {{-- Nome da Turma --}}
                                    <div class="mb-3">
                                        <label for="name" class="form-label text-primary font-w600">Nome da Turma <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Ex: Turma de Redes - Manhã T1" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Curso Associado --}}
                                    <div class="mb-3">
                                        <label for="course_id" class="form-label text-primary font-w600">Curso <span class="text-danger">*</span></label>
                                        <select id="course_id" name="course_id" class="form-control select2 @error('course_id') is-invalid @enderror" required>
                                            <option value="">Selecione o Curso...</option>
                                            @foreach($courses as $course)
                                                <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                                    {{ $course->name }} ({{ $course->duration }}h - {{ number_format($course->value, 2, ',', '.') }} Kz)
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('course_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Formador Responsável --}}
                                    <div class="mb-3">
                                        <label for="teacher_id" class="form-label text-primary font-w600">Formador<span class="text-danger">*</span></label>
                                        <select id="teacher_id" name="teacher_id" class="form-control select2 @error('teacher_id') is-invalid @enderror" required>
                                            <option value="">Selecione o Formador...</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                                    {{ $teacher->name }} — {{ $teacher->specialization }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('teacher_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Sala de Aula Física (Obrigatória: a capacidade da turma deriva dela) --}}
                                    <div class="mb-3">
                                        <label for="classroom_id" class="form-label text-primary font-w600">Sala de Aula <span class="text-danger">*</span></label>
                                        <select id="classroom_id" name="classroom_id" class="form-control select2 @error('classroom_id') is-invalid @enderror" required>
                                            <option value="" data-capacity="">Selecione a Sala Física...</option>
                                            @foreach($classrooms as $classroom)
                                                <option value="{{ $classroom->id }}" data-capacity="{{ $classroom->capacity }}" {{ old('classroom_id') == $classroom->id ? 'selected' : '' }}>
                                                    Sala {{ $classroom->number_of_classroom }} (Capacidade: {{ $classroom->capacity }} lugares)
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('classroom_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Capacidade Máxima (Campo Readonly derivado automaticamente da sala) --}}
                                    <div class="mb-3">
                                        <label for="max_capacity" class="form-label text-primary font-w600">Capacidade Máxima</label>
                                        <input type="number" id="max_capacity" name="max_capacity" class="form-control bg-light readonly-capacity @error('max_capacity') is-invalid @enderror" value="{{ old('max_capacity') }}" placeholder="Herdada da sala selecionada" readonly>
                                        <small class="text-muted d-block mt-1">
                                            <i class="fa fa-info-circle me-1"></i> Preenchido automaticamente com base na capacidade da sala física escolhida.
                                        </small>
                                        @error('max_capacity')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>


                                </div>

                                {{-- Coluna Direita: Horários, Períodos e Dias da Semana --}}
                                <div class="col-xl-6 col-sm-6">
                                    {{-- Turno --}}
                                    <div class="mb-3">
                                        <label for="shift" class="form-label text-primary font-w600">Turno <span class="text-danger">*</span></label>
                                        <select id="shift" name="shift" class="form-control @error('shift') is-invalid @enderror" required>
                                            <option value="Manhã" {{ old('shift', 'Manhã') == 'Manhã' ? 'selected' : '' }}>Manhã</option>
                                            <option value="Tarde" {{ old('shift') == 'Tarde' ? 'selected' : '' }}>Tarde</option>
                                            <option value="Pós-Laboral" {{ old('shift') == 'Pós-Laboral' ? 'selected' : '' }}>Pós-Laboral</option>
                                        </select>
                                        @error('shift')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Horário de Início e Término --}}
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="start_time" class="form-label text-primary font-w600">Hora de Início <span class="text-danger">*</span></label>
                                            <input type="time" name="start_time" id="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time', '08:00') }}" required>
                                            @error('start_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="end_time" class="form-label text-primary font-w600">Hora de Término <span class="text-danger">*</span></label>
                                            <input type="time" name="end_time" id="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time', '10:00') }}" required>
                                            @error('end_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Datas Previstas de Início e Fim da Formação --}}
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="start_date" class="form-label text-primary font-w600">Data de Início das Aulas</label>
                                            <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}">
                                            @error('start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="end_date" class="form-label text-primary font-w600">Data de Conclusão Prevista</label>
                                            <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}">
                                            @error('end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Seleção dos Dias da Semana --}}
                                    <div class="mb-3">
                                        <label class="form-label text-primary font-w600 d-block">Dias da Semana <span class="text-danger">*</span></label>
                                        <div class="d-flex flex-wrap gap-2 mt-1 p-3 border rounded bg-light">
                                            @php
                                                $days = ['Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];
                                                $selectedDays = old('days_of_week', []);
                                                if (is_string($selectedDays)) {
                                                    $selectedDays = array_map('trim', explode(',', $selectedDays));
                                                }
                                            @endphp
                                            @foreach($days as $day)
                                                <div class="form-check me-3 mb-2">
                                                    <input class="form-check-input" type="checkbox" name="days_of_week[]" value="{{ $day }}" id="day_{{ $loop->index }}"
                                                        {{ (is_array($selectedDays) && in_array($day, $selectedDays)) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-dark" for="day_{{ $loop->index }}">
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

                        {{-- Rodapé do Formulário com Botões de Ação --}}
                        <div class="card-footer d-flex justify-content-end gap-2">
                            <a href="{{ route('room.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Guardar Turma</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
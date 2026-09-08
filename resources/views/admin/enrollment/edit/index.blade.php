@extends('layouts.main')

@section('title', 'Editar Inscrição')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Editar Inscrição: #{{ $enrollment->id }}</h4>
                        <a href="{{ route('enrollment.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left me-1"></i> Voltar à Listagem
                        </a>
                    </div>

                    <form action="{{ route('enrollment.update', $enrollment->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger alert-alt alert-dismissible fade show mb-4" role="alert">
                                    <strong>Erro!</strong> Por favor verifique os seguintes problemas:
                                    <ul class="mb-0 mt-1 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-xl-6 col-sm-6">
                                    {{-- Busca Estudante com datalist --}}
                                    <div class="mb-3">
                                        <label for="student_search" class="form-label text-primary">Pesquisar Estudante <span class="text-danger">*</span></label>
                                        @php
                                            $currentStudent = $students->firstWhere('id', old('student_id', $enrollment->student_id));
                                            $studentVal = $currentStudent ? $currentStudent->name . ' (BI: ' . $currentStudent->identity_card_number . ', Cód: ' . $currentStudent->code . ')' : '';
                                        @endphp
                                        <input type="text" class="form-control" id="student_search" list="students_list" value="{{ $studentVal }}" placeholder="Digite o nome do estudante..." autocomplete="off" required>
                                        <input type="hidden" name="student_id" id="student_id" value="{{ old('student_id', $enrollment->student_id) }}">
                                        <datalist id="students_list">
                                            @foreach($students as $student)
                                                <option data-id="{{ $student->id }}" value="{{ $student->name }} (BI: {{ $student->identity_card_number }}, Cód: {{ $student->code }})"></option>
                                            @endforeach
                                        </datalist>
                                    </div>

                                    {{-- Data da Inscrição (Não Editável) --}}
                                    <div class="mb-3">
                                        <label for="date" class="form-label text-primary">Data da Inscrição (Não Editável)</label>
                                        <input type="text" class="form-control" id="date" value="{{ date('d/m/Y H:i', strtotime($enrollment->date)) }}" readonly disabled>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-sm-6">
                                    {{-- Busca Curso com datalist --}}
                                    <div class="mb-3">
                                        <label for="course_search" class="form-label text-primary">Pesquisar Curso <span class="text-danger">*</span></label>
                                        @php
                                            $currentCourse = $courses->firstWhere('id', old('course_id', $enrollment->course_id));
                                            $courseVal = $currentCourse ? $currentCourse->name . ' (Cód: ' . ($currentCourse->code ?? $currentCourse->id) . ')' : '';
                                        @endphp
                                        <input type="text" class="form-control" id="course_search" list="courses_list" value="{{ $courseVal }}" placeholder="Digite o nome do curso..." autocomplete="off" required>
                                        <input type="hidden" name="course_id" id="course_id" value="{{ old('course_id', $enrollment->course_id) }}">
                                        <datalist id="courses_list">
                                            @foreach($courses as $course)
                                                <option data-id="{{ $course->id }}" value="{{ $course->name }} (Cód: {{ $course->code ?? $course->id }})"></option>
                                            @endforeach
                                        </datalist>
                                    </div>

                                    {{-- Estado --}}
                                    <div class="mb-3">
                                        <label for="status" class="form-label text-primary">Estado da Inscrição <span class="text-danger">*</span></label>
                                        <select id="status" name="status" class="default-select wide form-control" required>
                                            <option value="1" {{ old('status', $enrollment->status) ? 'selected' : '' }}>Ativa / Confirmada</option>
                                            <option value="0" {{ !old('status', $enrollment->status) ? 'selected' : '' }}>Pendente / Inativa</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <a href="{{ route('enrollment.index') }}" class="btn btn-danger light me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Atualizar Inscrição</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const studentSearchInput = document.getElementById('student_search');
        const studentHiddenInput = document.getElementById('student_id');
        const studentsDatalist = document.getElementById('students_list');

        studentSearchInput.addEventListener('input', function() {
            const val = this.value;
            const options = studentsDatalist.options;
            studentHiddenInput.value = '';
            for (let i = 0; i < options.length; i++) {
                if (options[i].value === val) {
                    studentHiddenInput.value = options[i].getAttribute('data-id');
                    break;
                }
            }
        });

        const courseSearchInput = document.getElementById('course_search');
        const courseHiddenInput = document.getElementById('course_id');
        const coursesDatalist = document.getElementById('courses_list');

        courseSearchInput.addEventListener('input', function() {
            const val = this.value;
            const options = coursesDatalist.options;
            courseHiddenInput.value = '';
            for (let i = 0; i < options.length; i++) {
                if (options[i].value === val) {
                    courseHiddenInput.value = options[i].getAttribute('data-id');
                    break;
                }
            }
        });
    });
</script>
@endpush
@endsection
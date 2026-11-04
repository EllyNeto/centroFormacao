@extends('layout.main')

@section('title', 'Nova Matrícula de Formando')

@section('content')
{{-- Estrutura principal da página de efetivação de nova matrícula --}}
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    {{-- Cabeçalho do Cartão com Título e Ação de Retorno --}}
                    <div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center border-bottom">
                        <div>
                            <h4 class="card-title mb-0 text-dark">Efetivar Nova Matrícula de Formando</h4>
                            <p class="m-0 subtitle text-muted">Oficialização de formando a partir de candidatura com propina totalmente liquidada</p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('student.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fa fa-arrow-left me-1"></i> Voltar à Listagem
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        {{-- Exibição de Alertas de Erro de Validação --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                <h6 class="alert-heading font-w600 mb-2">
                                    <i class="fa fa-exclamation-triangle me-2"></i> Não foi possível efetivar a matrícula:
                                </h6>
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if($eligibleEnrollments->isEmpty())
                            {{-- Aviso quando não existem inscrições aptas para matrícula --}}
                            <div class="p-5 text-center bg-light rounded border">
                                <i class="fa fa-check-circle-o fa-3x text-success mb-3 d-block"></i>
                                <h5 class="text-dark">Não existem candidaturas com pagamento liquidado pendentes de efetivação</h5>
                                <p class="text-muted fs-7 mb-4">
                                    Para matricular um formando, é necessário que o candidato possua uma inscrição ativa com o valor integral do curso pago e confirmado.
                                </p>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('enrollment.index') }}" class="btn btn-primary btn-sm">
                                        <i class="fa fa-list me-1"></i> Ver Candidaturas
                                    </a>
                                    <a href="{{ route('payment.create') }}" class="btn btn-success btn-sm">
                                        <i class="fa fa-money me-1"></i> Registar Pagamento
                                    </a>
                                </div>
                            </div>
                        @else
                            {{-- Formulário de Efetivação da Matrícula --}}
                            <form action="{{ route('student.store') }}" method="POST" enctype="multipart/form-data" id="studentForm">
                                @csrf

                                {{-- Secção 1: Seleção da Candidatura Liquidada --}}
                                <div class="p-4 mb-4 rounded border bg-light">
                                    <h5 class="font-w600 text-dark mb-3">
                                        <i class="fa fa-id-card-o text-primary me-2"></i>1. Seleção da Candidatura Liquidada
                                    </h5>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label font-w600 text-dark">
                                                Candidato / Inscrição Apta <span class="text-danger">*</span>
                                            </label>
                                            <select name="enrollment_id" 
                                                    id="selectEnrollment" 
                                                    class="form-control @error('enrollment_id') is-invalid @enderror" 
                                                    required>
                                                <option value="">-- Selecione o Candidato --</option>
                                                @foreach($eligibleEnrollments as $e)
                                                    @php
                                                        $isSelected = old('enrollment_id', optional($selectedEnrollment)->id) == $e->id;
                                                    @endphp
                                                    <option value="{{ $e->id }}" 
                                                            data-name="{{ $e->name }}"
                                                            data-email="{{ $e->email }}"
                                                            data-phone="{{ $e->phone }}"
                                                            data-bi="{{ $e->number_of_identify }}"
                                                            data-course-id="{{ $e->course_id }}"
                                                            data-course-name="{{ $e->course->name ?? '' }}"
                                                            data-shift="{{ $e->shift }}"
                                                            data-room-id="{{ $e->room_id ?? '' }}"
                                                            {{ $isSelected ? 'selected' : '' }}>
                                                        {{ $e->formattedCode() }} &bull; {{ $e->name }} (BI: {{ $e->number_of_identify }}) — Curso: {{ $e->course->name ?? 'N/D' }} [Turno: {{ $e->shift }}]
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('enrollment_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted d-block mt-1">
                                                Apenas são listadas candidaturas com estado 'Pago' que ainda não possuem matrícula oficial.
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                {{-- Secção 2: Dados Cadastrais do Formando (Preenchidos Automaticamente) --}}
                                <div class="p-4 mb-4 rounded border bg-white shadow-sm">
                                    <h5 class="font-w600 text-dark mb-3">
                                        <i class="fa fa-user text-primary me-2"></i>2. Dados Cadastrais do Formando
                                    </h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label font-w600 text-dark">
                                                Nome Completo <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   name="name" 
                                                   id="inputName" 
                                                   class="form-control @error('name') is-invalid @enderror" 
                                                   value="{{ old('name', optional($selectedEnrollment)->name) }}" 
                                                   placeholder="Nome completo do aluno" 
                                                   required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label font-w600 text-dark">
                                                Nº do Bilhete de Identidade (BI) <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   name="number_of_identify" 
                                                   id="inputBi" 
                                                   class="form-control @error('number_of_identify') is-invalid @enderror" 
                                                   value="{{ old('number_of_identify', optional($selectedEnrollment)->number_of_identify) }}" 
                                                   placeholder="Nº do documento de identificação" 
                                                   required>
                                            @error('number_of_identify')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label font-w600 text-dark">
                                                Email <span class="text-danger">*</span>
                                            </label>
                                            <input type="email" 
                                                   name="email" 
                                                   id="inputEmail" 
                                                   class="form-control @error('email') is-invalid @enderror" 
                                                   value="{{ old('email', optional($selectedEnrollment)->email) }}" 
                                                   placeholder="correio@dominio.ao" 
                                                   required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label font-w600 text-dark">
                                                Telefone <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   name="phone" 
                                                   id="inputPhone" 
                                                   class="form-control @error('phone') is-invalid @enderror" 
                                                   value="{{ old('phone', optional($selectedEnrollment)->phone) }}" 
                                                   placeholder="Ex: 923 456 789" 
                                                   required>
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label font-w600 text-dark">Fotografia de Perfil</label>
                                            <input type="file" 
                                                   name="image" 
                                                   class="form-control @error('image') is-invalid @enderror" 
                                                   accept="image/jpeg,image/png,image/jpg,image/webp">
                                            @error('image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Se não anexar nova foto, será utilizada a fotografia fornecida na candidatura.</small>
                                        </div>
                                    </div>
                                </div>

                                {{-- Secção 3: Atribuição da Turma --}}
                                <div class="p-4 mb-4 rounded border bg-white shadow-sm">
                                    <h5 class="font-w600 text-dark mb-3">
                                        <i class="fa fa-users text-primary me-2"></i>3. Atribuição de Turma
                                    </h5>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label font-w600 text-dark">
                                                Turma a Frequentar <span class="text-danger">*</span>
                                            </label>
                                            <select name="room_id" 
                                                    id="selectRoom" 
                                                    class="form-control @error('room_id') is-invalid @enderror" 
                                                    required>
                                                <option value="">-- Selecione a Turma Compatível --</option>
                                                @foreach($openRooms as $room)
                                                    @php
                                                        $isSelected = old('room_id', optional($selectedEnrollment)->room_id) == $room->id;
                                                        $available  = $room->availableSeats();
                                                        $isFull     = $room->isFull();
                                                    @endphp
                                                    <option value="{{ $room->id }}" 
                                                            data-course-id="{{ $room->course_id }}" 
                                                            data-shift="{{ $room->shift }}"
                                                            {{ $isFull ? 'disabled' : '' }}
                                                            {{ $isSelected ? 'selected' : '' }}>
                                                        {{ $room->name }} 
                                                        &bull; Curso: {{ $room->course->name ?? 'N/D' }}
                                                        &bull; Turno: {{ $room->shift }}
                                                        &bull; {{ $room->classroom ? 'Sala ' . $room->classroom->number_of_classroom : 'Sem sala' }}
                                                        &bull; {{ $isFull ? '[LOTADA]' : 'Vagas: ' . $available . '/' . $room->max_capacity }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('room_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted d-block mt-1">
                                                Apenas turmas com vagas disponíveis e com o mesmo curso do candidato são aceites.
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                {{-- Botões de Submissão --}}
                                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                                    <a href="{{ route('student.index') }}" class="btn btn-secondary">
                                        <i class="fa fa-times me-1"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-check-circle me-1"></i> Efetivar Matrícula Oficial
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Script dedicado para sincronização dinâmica do candidato com os campos do formulário --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    var selectEnrollment = document.getElementById('selectEnrollment');
    var inputName        = document.getElementById('inputName');
    var inputBi          = document.getElementById('inputBi');
    var inputEmail       = document.getElementById('inputEmail');
    var inputPhone       = document.getElementById('inputPhone');
    var selectRoom       = document.getElementById('selectRoom');

    if (!selectEnrollment) return;

    // Função de sincronização dos dados ao selecionar uma inscrição
    selectEnrollment.addEventListener('change', function () {
        var selectedOption = this.options[this.selectedIndex];
        if (!selectedOption || !selectedOption.value) {
            return;
        }

        // Auto-preenchimento dos dados civis
        if (inputName)  inputName.value  = selectedOption.getAttribute('data-name') || '';
        if (inputBi)    inputBi.value    = selectedOption.getAttribute('data-bi') || '';
        if (inputEmail) inputEmail.value = selectedOption.getAttribute('data-email') || '';
        if (inputPhone) inputPhone.value = selectedOption.getAttribute('data-phone') || '';

        var courseId = selectedOption.getAttribute('data-course-id');
        var roomId   = selectedOption.getAttribute('data-room-id');

        // Pré-seleciona a turma se já definida ou filtra por curso
        if (selectRoom) {
            for (var i = 0; i < selectRoom.options.length; i++) {
                var opt = selectRoom.options[i];
                if (!opt.value) continue;

                var optCourseId = opt.getAttribute('data-course-id');
                if (courseId && optCourseId && optCourseId !== courseId) {
                    opt.style.display = 'none';
                } else {
                    opt.style.display = '';
                }
            }

            if (roomId) {
                selectRoom.value = roomId;
            }
        }
    });

    // Se já houver um valor selecionado ao carregar a página (ex: ?enrollment_id=X)
    if (selectEnrollment.value) {
        selectEnrollment.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush

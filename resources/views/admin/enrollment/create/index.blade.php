@extends('layout.main')

@section('title', 'Registar Candidatura')

@section('content')
{{-- Estrutura principal da página de registo de nova candidatura --}}
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    {{-- Cabeçalho do Cartão com Título e Ação de Retorno --}}
                    <div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center border-bottom">
                        <div>
                            <h4 class="card-title mb-1 text-dark">Registar Nova Candidatura</h4>
                            <p class="m-0 subtitle text-muted">Preencha os dados do candidato e selecione o curso e turma pretendidos</p>
                        </div>
                        <a href="{{ route('enrollment.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left me-1"></i> Voltar à Listagem
                        </a>
                    </div>
                    
                    <form action="{{ route('enrollment.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="card-body p-4">
                            {{-- Exibição de Alertas de Erros de Validação --}}
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show mb-4 enrollment-alert">
                                    <h6 class="alert-heading font-w600"><i class="fa fa-exclamation-triangle me-2"></i>Por favor corrija os seguintes erros:</h6>
                                    <ul class="mb-0 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <div class="row g-4">
                                {{-- Coluna Esquerda: Fotografia do Candidato --}}
                                <div class="col-xl-3 col-lg-4 col-md-5">
                                    <label class="form-label text-dark font-w600 d-block">Fotografia do Candidato</label>
                                    
                                    <div class="photo-upload-container">
                                        {{-- Caixa de Pré-visualização --}}
                                        <div class="candidate-photo-preview-card">
                                            <img id="imagePreview" src="" alt="Pré-visualização" class="candidate-photo-img-hidden">
                                            <svg id="defaultAvatarSvg" width="100%" height="100%" viewBox="0 0 160 180" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect width="160" height="180" fill="#E2E8F0"/>
                                                <circle cx="80" cy="70" r="35" fill="#94A3B8"/>
                                                <path d="M25 160C25 125 50 115 80 115C110 115 135 125 135 160V180H25V160Z" fill="#94A3B8"/>
                                            </svg>
                                        </div>

                                        {{-- Seletor de Ficheiro com Estilo Unificado --}}
                                        <div class="mb-1">
                                            <label for="image" class="file-input-wrapper">
                                                <span class="file-input-btn">Selecionar Foto</span>
                                                <span id="fileNameDisplay" class="file-input-name">Nenhum ficheiro selecionado</span>
                                            </label>
                                            <input type="file" name="image" id="image" class="d-none" accept="image/*">
                                        </div>
                                        <small class="candidate-photo-helper">Formatos aceites: JPG, PNG, WEBP (Máx: 4MB)</small>
                                    </div>
                                </div>

                                {{-- Coluna Direita: Dados Pessoais e Opções Académicas --}}
                                <div class="col-xl-9 col-lg-8 col-md-7">
                                    <div class="row g-3">
                                        {{-- Nome Completo do Candidato --}}
                                        <div class="col-md-6">
                                            <label for="name" class="form-label text-dark font-w600">Nome Completo <span class="text-danger">*</span></label>
                                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Ex: António Manuel Silva" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Número do BI / Passaporte --}}
                                        <div class="col-md-6">
                                            <label for="number_of_identify" class="form-label text-dark font-w600">Número do Bilhete de Identidade<span class="text-danger">*</span></label>
                                            <input type="text" name="number_of_identify" id="number_of_identify" class="form-control @error('number_of_identify') is-invalid @enderror" value="{{ old('number_of_identify') }}" placeholder="Ex: 007654321LA045" required>
                                            @error('number_of_identify')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Endereço de Email --}}
                                        <div class="col-md-6">
                                            <label for="email" class="form-label text-dark font-w600">Endereço de Email <span class="text-danger">*</span></label>
                                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="candidato@email.com" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Telefone de Contacto --}}
                                        <div class="col-md-6">
                                            <label for="phone" class="form-label text-dark font-w600">Telefone <span class="text-danger">*</span></label>
                                            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="Ex: 923 123 456" required>
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Código de Aluno (Opcional — R4)
                                        <div class="col-md-6">
                                            <label for="student_code" class="form-label text-dark font-w600">Código de Aluno <span class="text-muted fw-normal">(opcional)</span></label>
                                            <input type="text" name="student_code" id="student_code" class="form-control @error('student_code') is-invalid @enderror" value="{{ old('student_code') }}" placeholder="Ex: ALU-0001">
                                            <small class="text-muted">Preencha apenas se o candidato já for aluno registado.</small>
                                            @error('student_code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div> --}}

                                        {{-- Curso Pretendido --}}
                                        <div class="col-md-6">
                                            <label for="course_id" class="form-label text-dark font-w600">Curso Pretendido <span class="text-danger">*</span></label>
                                            <select id="course_id" name="course_id" class="form-control select2 @error('course_id') is-invalid @enderror" required>
                                                <option value="">Selecione o Curso...</option>
                                                @foreach($courses as $course)
                                                    <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}
                                                            data-open-rooms="{{ $course->open_rooms_count ?? 0 }}">
                                                        {{ $course->name }}
                                                        @if(($course->open_rooms_count ?? 0) == 0)
                                                            — ⚠ Sem turmas abertas
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('course_id')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Turno Pretendido --}}
                                        <div class="col-md-6">
                                            <label for="shift" class="form-label text-dark font-w600">Turno Pretendido <span class="text-danger">*</span></label>
                                            <select id="shift" name="shift" class="form-control @error('shift') is-invalid @enderror" required>
                                                <option value="Manhã" {{ old('shift', 'Manhã') == 'Manhã' ? 'selected' : '' }}>Manhã</option>
                                                <option value="Tarde" {{ old('shift') == 'Tarde' ? 'selected' : '' }}>Tarde</option>
                                                <option value="Pós-Laboral" {{ old('shift') == 'Pós-Laboral' ? 'selected' : '' }}>Pós-Laboral</option>
                                            </select>
                                            @error('shift')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Rodapé do Formulário com Botões de Ação --}}
                        <div class="card-footer d-flex justify-content-end gap-2 border-top">
                            <a href="{{ route('enrollment.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save me-1"></i> Guardar Candidatura
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
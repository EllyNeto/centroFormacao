@extends('layout.main')

@section('title', 'Editar Candidatura')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h4 class="card-title">Editar Candidatura: {{ $enrollment->name }}</h4>
                        <a href="{{ route('enrollment.index') }}" class="btn btn-primary">
                            <i class="fa fa-arrow-left me-2"></i> Voltar à Listagem
                        </a>
                    </div>
                    
                    <form action="{{ route('enrollment.update', $enrollment->id) }}" method="POST" enctype="multipart/form-data">
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
                                {{-- Fotografia do Candidato (Coluna Esquerda - Mesma Lógica do Formador) --}}
                                <div class="col-xl-3 col-lg-4 col-md-4 mb-4">
                                    <label class="form-label text-primary d-block">Fotografia do Candidato</label>
                                    
                                    <div class="photo-upload-container">
                                        <div class="photo-preview-card mb-3">
                                            <img id="imagePreview" src="{{ $enrollment->image ? asset('storage/' . $enrollment->image) : '' }}" alt="Preview da Foto" 
                                                 style="width: 100%; height: 100%; object-fit: cover; {{ $enrollment->image ? 'display: block;' : 'display: none;' }}">
                                            <svg id="defaultAvatarSvg" width="100%" height="100%" viewBox="0 0 160 180" fill="none" xmlns="http://www.w3.org/2000/svg" style="{{ $enrollment->image ? 'display: none;' : 'display: block;' }}">
                                                <rect width="160" height="180" fill="#E2E8F0"/>
                                                <circle cx="80" cy="70" r="35" fill="#94A3B8"/>
                                                <path d="M25 160C25 125 50 115 80 115C110 115 135 125 135 160V180H25V160Z" fill="#94A3B8"/>
                                            </svg>
                                        </div>

                                        <div class="mb-1">
                                            <label for="image" class="file-input-wrapper">
                                                <span class="file-input-btn">Selecionar Ficheiro</span>
                                                <span id="fileNameDisplay" class="file-input-name">Nenhum ficheiro selecionado</span>
                                            </label>
                                            <input type="file" name="image" id="image" class="d-none" accept="image/*" onchange="previewTeacherImage(event)">
                                        </div>
                                        <small style="font-size: 11px; color: #94A3B8; display: block; margin-top: 4px;">Formatos: JPG, PNG, WEBP (Máx: 2MB)</small>
                                    </div>
                                </div>

                                {{-- Coluna Direita: Dados Pessoais & Candidatura --}}
                                <div class="col-xl-9 col-lg-8 col-md-8">
                                    <div class="row">
                                        {{-- Nome Completo --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="name" class="form-label text-primary">Nome Completo do Candidato <span class="text-danger">*</span></label>
                                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $enrollment->name) }}" placeholder="Ex: Maria dos Santos" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Número do BI--}}
                                        <div class="col-md-6 mb-3">
                                            <label for="number_of_identify" class="form-label text-primary">Número do BI<span class="text-danger">*</span></label>
                                            <input type="text" name="number_of_identify" id="number_of_identify" class="form-control @error('number_of_identify') is-invalid @enderror" value="{{ old('number_of_identify', $enrollment->number_of_identify) }}" placeholder="Ex: 000123456LA042" required>
                                            @error('number_of_identify')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Email --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="email" class="form-label text-primary">Email <span class="text-danger">*</span></label>
                                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $enrollment->email) }}" placeholder="candidato@gmail.com" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Telefone --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="phone" class="form-label text-primary">Telefone <span class="text-danger">*</span></label>
                                            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $enrollment->phone) }}" placeholder="Ex: 923 000 000" required>
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Curso Pretendido --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="course_id" class="form-label text-primary">Curso <span class="text-danger">*</span></label>
                                            <select id="course_id" name="course_id" class="form-control select2 @error('course_id') is-invalid @enderror" required>
                                                <option value="">Selecione o Curso</option>
                                                @foreach($courses as $course)
                                                    <option value="{{ $course->id }}" {{ old('course_id', $enrollment->course_id) == $course->id ? 'selected' : '' }}>
                                                        {{ $course->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('course_id')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Turno Pretendido --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="shift" class="form-label text-primary">Turno  <span class="text-danger">*</span></label>
                                            <select id="shift" name="shift" class="form-control @error('shift') is-invalid @enderror" required>
                                                <option value="Manhã" {{ old('shift', $enrollment->shift) == 'Manhã' ? 'selected' : '' }}>Manhã</option>
                                                <option value="Tarde" {{ old('shift', $enrollment->shift) == 'Tarde' ? 'selected' : '' }}>Tarde</option>
                                                <option value="Pós-Laboral" {{ old('shift', $enrollment->shift) == 'Pós-Laboral' ? 'selected' : '' }}>Pós-Laboral</option>
                                            </select>
                                            @error('shift')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Estado da Candidatura --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="status" class="form-label text-primary">Estado da Candidatura <span class="text-danger">*</span></label>
                                            <select id="status" name="status" class="form-control @error('status') is-invalid @enderror" required>
                                                <option value="Pendente" {{ old('status', $enrollment->status) == 'Pendente' ? 'selected' : '' }}>Pendente</option>
                                                <option value="Pago" {{ old('status', $enrollment->status) == 'Pago' ? 'selected' : '' }}>Pago</option>
                                                <option value="Matriculado" {{ old('status', $enrollment->status) == 'Matriculado' ? 'selected' : '' }}>Matriculado</option>
                                                <option value="Lista de Espera" {{ old('status', $enrollment->status) == 'Lista de Espera' ? 'selected' : '' }}>Lista de Espera</option>
                                                <option value="Cancelado" {{ old('status', $enrollment->status) == 'Cancelado' ? 'selected' : '' }}>Cancelado</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <a href="{{ route('enrollment.index') }}" class="btn btn-danger light me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Atualizar Candidatura</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
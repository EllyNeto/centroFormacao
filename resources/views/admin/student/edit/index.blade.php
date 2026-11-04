@extends('layout.main')

@section('title', 'Editar Formando')

@section('content')
{{-- Estrutura principal da página de edição do formando --}}
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    {{-- Cabeçalho do Cartão de Edição --}}
                    <div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center border-bottom">
                        <div>
                            <h4 class="card-title mb-0 text-dark">Editar Formando: {{ $student->name }}</h4>
                            <p class="m-0 subtitle text-muted">Atualização de dados cadastrais, contactos e eventual transferência de turma</p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('student.show', $student->id) }}" class="btn btn-secondary btn-sm">
                                <i class="fa fa-arrow-left me-1"></i> Voltar à Ficha
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        {{-- Exibição de Erros Globais de Validação --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                <h6 class="alert-heading font-w600 mb-2">
                                    <i class="fa fa-exclamation-triangle me-2"></i> Por favor, corrija os seguintes erros:
                                </h6>
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- Formulário de Atualização do Formando --}}
                        <form action="{{ route('student.update', $student->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row g-4">
                                {{-- 1. Código Oficial do Formando (Não editável) --}}
                                <div class="col-md-4">
                                    <label class="form-label font-w600 text-dark">Código de Formando</label>
                                    <input type="text" 
                                           class="form-control bg-light text-muted" 
                                           value="{{ $student->formattedCode() }}" 
                                           readonly>
                                    <small class="text-muted">Identificador permanente e único atribuído pelo sistema.</small>
                                </div>

                                {{-- 2. Nome Completo --}}
                                <div class="col-md-8">
                                    <label class="form-label font-w600 text-dark">
                                        Nome Completo <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           name="name" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name', $student->name) }}" 
                                           placeholder="Introduza o nome completo do formando" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- 3. Documento de Identificação (BI / Passaporte) --}}
                                <div class="col-md-4">
                                    <label class="form-label font-w600 text-dark">
                                        Nº do Bilhete de Identidade (BI) <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           name="number_of_identify" 
                                           class="form-control @error('number_of_identify') is-invalid @enderror" 
                                           value="{{ old('number_of_identify', $student->number_of_identify) }}" 
                                           placeholder="Ex: 004123456LA042" 
                                           required>
                                    @error('number_of_identify')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- 4. Correio Eletrónico --}}
                                <div class="col-md-4">
                                    <label class="form-label font-w600 text-dark">
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" 
                                           name="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email', $student->email) }}" 
                                           placeholder="exemplo@dominio.ao" 
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- 5. Contacto Telefónico --}}
                                <div class="col-md-4">
                                    <label class="form-label font-w600 text-dark">
                                        Telefone <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           name="phone" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           value="{{ old('phone', $student->phone) }}" 
                                           placeholder="Ex: 923 456 789" 
                                           required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- 6. Turma Frequentada (com possibilidade de transferência de turma) --}}
                                <div class="col-md-6">
                                    <label class="form-label font-w600 text-dark">
                                        Turma Alocada <span class="text-danger">*</span>
                                    </label>
                                    <select name="room_id" class="form-control @error('room_id') is-invalid @enderror" required>
                                        <option value="">-- Selecione a Turma --</option>
                                        @foreach($openRooms as $room)
                                            @php
                                                $isSelected = old('room_id', $student->room_id) == $room->id;
                                                $available = $room->availableSeats();
                                            @endphp
                                            <option value="{{ $room->id }}" {{ $isSelected ? 'selected' : '' }}>
                                                {{ $room->name }} 
                                                ({{ $room->shift }} &bull; {{ $room->classroom ? 'Sala ' . $room->classroom->number_of_classroom : 'Sem sala' }} &bull; Vagas: {{ $available }}/{{ $room->max_capacity }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('room_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted d-block mt-1">Apenas são listadas turmas abertas pertencentes ao mesmo curso do formando.</small>
                                </div>

                                {{-- 7. Fotografia de Perfil --}}
                                <div class="col-md-6">
                                    <label class="form-label font-w600 text-dark">Fotografia de Perfil</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $student->avatarUrl() }}" 
                                             class="rounded-circle border" 
                                             alt="Foto atual" 
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                        <input type="file" 
                                               name="image" 
                                               class="form-control @error('image') is-invalid @enderror" 
                                               accept="image/jpeg,image/png,image/jpg,image/webp">
                                    </div>
                                    @error('image')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Formatos suportados: JPG, PNG, WEBP (Máximo 4MB). Opcional.</small>
                                </div>
                            </div>

                            {{-- Botões de Submissão do Formulário --}}
                            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                                <a href="{{ route('student.show', $student->id) }}" class="btn btn-secondary">
                                    <i class="fa fa-times me-1"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save me-1"></i> Guardar Alterações
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
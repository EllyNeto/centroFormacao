{{-- Extende o layout principal da aplicação --}}
@extends('layouts.main')

{{-- Define o título dinâmico da página na barra do navegador --}}
@section('title', 'Adicionar Nova Inscrição')

{{-- Conteúdo principal da página de registo de inscrição --}}
@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    {{-- Cabeçalho do Cartão com título e botão para voltar à listagem --}}
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0">Adicionar Nova Inscrição de Candidato</h4>
                            <p class="m-0 subtitle">Preencha os dados do candidato e selecione o curso pretendido</p>
                        </div>
                        <a href="{{ route('enrollment.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left me-1"></i> Voltar à Listagem
                        </a>
                    </div>

                    {{-- Formulário de Registo de Inscrição com suporte para envio de ficheiros (multipart/form-data) --}}
                    <form action="{{ route('enrollment.store') }}" method="POST" enctype="multipart/form-data">
                        {{-- Proteção contra solicitações forjadas entre sites (CSRF) --}}
                        @csrf

                        <div class="card-body">
                            {{-- Painel de exibição de mensagens de erro de validação do formulário --}}
                            @if ($errors->any())
                                <div class="alert alert-danger alert-alt alert-dismissible fade show mb-4" role="alert">
                                    <div>
                                        <strong>Erro!</strong> Por favor verifique os seguintes problemas:
                                        <ul class="mb-0 mt-1 ps-3">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            
                            <div class="row">
                                {{-- Coluna Esquerda: Upload e pré-visualização da Fotografia de Perfil do Candidato --}}
                                <div class="col-xl-3 col-lg-4 text-center mb-4 mb-lg-0">
                                    <label class="form-label text-primary font-w600">Fotografia do Candidato</label>
                                    <div class="avatar-upload">
                                        {{-- Contentor de pré-visualização da imagem --}}
                                        <div class="avatar-preview mb-3">
                                            <div id="imagePreview" style="background-image: url('{{ asset('images/no-img-avatar.png') }}'); width: 140px; height: 140px; background-size: cover; background-position: center; border-radius: 12px; border: 2px solid #e2e8f0; margin: 0 auto;"> 			
                                            </div>
                                        </div>
                                        {{-- Campo de seleção de ficheiro de imagem --}}
                                        <div class="change-btn mt-2">
                                            <input type="file" class="form-control" name="image" id="imageUpload" accept="image/*" onchange="previewStudentImage(this)">
                                            <small class="text-muted d-block mt-1">Formatos: JPG, PNG, WEBP (Máx: 2MB)</small>
                                        </div>
                                    </div>	
                                </div>

                                {{-- Coluna Direita: Campos de dados pessoais do candidato e seleção de curso --}}
                                <div class="col-xl-9 col-lg-8">
                                    <div class="row">
                                        {{-- Campo: Nome Completo do Candidato --}}
                                        <div class="col-xl-6 col-sm-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label text-primary">Nome Completo <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="name" name="name" placeholder="Ex: Maria dos Santos" value="{{ old('name') }}" required>
                                            </div>
                                        </div>

                                        {{-- Campo: Número do Bilhete de Identidade (BI) --}}
                                        <div class="col-xl-6 col-sm-6">
                                            <div class="mb-3">
                                                <label for="identity_card_number" class="form-label text-primary">Número do BI <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="identity_card_number" name="identity_card_number" placeholder="Ex: 000123456LA042" value="{{ old('identity_card_number') }}" required>
                                            </div>
                                        </div>

                                        {{-- Campo: Endereço de E-mail --}}
                                        <div class="col-xl-6 col-sm-6">
                                            <div class="mb-3">
                                                <label for="email" class="form-label text-primary">Email <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control" id="email" name="email" placeholder="candidato@gmail.com" value="{{ old('email') }}" required>
                                            </div>
                                        </div>

                                        {{-- Campo: Género --}}
                                        <div class="col-xl-6 col-sm-6">
                                            <div class="mb-3">
                                                <label for="gender" class="form-label text-primary">Género</label>
                                                <select id="gender" name="gender" class="form-control">
                                                    <option value="">Selecione o género...</option>
                                                    <option value="Masculino" {{ old('gender') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                                    <option value="Feminino" {{ old('gender') == 'Feminino' ? 'selected' : '' }}>Feminino</option>
                                                    <option value="Outro" {{ old('gender') == 'Outro' ? 'selected' : '' }}>Outro</option>
                                                </select>
                                            </div>
                                        </div>

                                        {{-- Campo: Número de Telefone --}}
                                        <div class="col-xl-6 col-sm-6">
                                            <div class="mb-3">
                                                <label for="phone" class="form-label text-primary">Número de Telefone <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="phone" name="phone" placeholder="Ex: 923000000" value="{{ old('phone') }}" required>
                                            </div>
                                        </div>

                                        {{-- Campo: Curso Pretendido (Lista Suspensa) --}}
                                        <div class="col-xl-6 col-sm-6">
                                            <div class="mb-3">
                                                <label for="course_id" class="form-label text-primary">Curso Pretendido <span class="text-danger">*</span></label>
                                                <select id="course_id" name="course_id" class="form-control" required>
                                                    <option value="">Selecione o Curso Pretendido...</option>
                                                    @foreach($courses as $course)
                                                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                                            {{ $course->name }} (Cód: {{ $course->code ?? $course->id }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        {{-- Campo: Data da Inscrição --}}
                                        <div class="col-xl-6 col-sm-6">
                                            <div class="mb-3">
                                                <label for="date" class="form-label text-primary">Data da Inscrição <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                                            </div>
                                        </div>

                                        {{-- Campo: Estado Inicial (Definido Automaticamente como Pendente) --}}
                                        <div class="col-xl-6 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary d-block">Estado Inicial</label>
                                                <div class="mt-2">
                                                    <span class="badge badge-warning light fs-14 py-2 px-3"><i class="fa fa-clock-o me-1"></i> Pendente </span>
                                                </div>
                                                {{-- Input oculto que envia o estado padrão (0 = Pendente) --}}
                                                <input type="hidden" name="status" value="0">
                                                <small class="text-muted d-block mt-1"></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Rodapé do Cartão com os botões de ação do formulário --}}
                        <div class="card-footer text-end">
                            <a href="{{ route('enrollment.index') }}" class="btn btn-danger light me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save me-1"></i> Guardar Inscrição
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Injeção do script de pré-visualização de imagem de perfil --}}
@push('scripts')
<script src="{{ asset('js/enrollment-form.js') }}"></script>
@endpush
@endsection
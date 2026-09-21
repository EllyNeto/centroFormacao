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
                            <p class="m-0 subtitle">Preencha os dados do candidato e selecione o Curso</p>
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
                                {{-- Campos de dados pessoais do candidato e seleção de curso --}}
                                <div class="col-xl-12 col-lg-12">
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
                                                <select id="gender" name="gender" class="form-control" style="background-color: #ffffff !important;">
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

                                        {{-- Campo: Curso (Lista Suspensa) --}}
                                        <div class="col-xl-6 col-sm-6">
                                            <div class="mb-3">
                                                <label for="course_id" class="form-label text-primary">Curso <span class="text-danger">*</span></label>
                                                <select id="course_id" name="course_id" class="form-control" style="background-color: #ffffff !important;" required>
                                                    <option value="">Selecione o Curso...</option>
                                                    @foreach($courses as $course)
                                                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                                            {{ $course->name }} (Cód: {{ $course->code ?? $course->id }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                         {{-- Campo: Data da Inscrição (Fundo cinza e leitura apenas) --}}
                                        <div class="col-xl-6 col-sm-6">
                                            <div class="mb-3">
                                                <label for="date" class="form-label text-primary">Data da Inscrição <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control uneditable-field" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" style="background-color: #e9ecef !important;" readonly required>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>        
                                    {{-- Rodapé do Cartão com os botões de ação do formulário --}}
                            <div class="card-footer text-end">
                                {{-- <a href="{{ route('enrollment.index') }}" class="btn btn-danger light me-2">Cancelar</a>--}}
                                <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save me-1"></i> Submeter Inscrição
                                </button>
                            </div>
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
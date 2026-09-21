{{-- Extende o layout principal da aplicação --}}
@extends('layouts.main')

{{-- Define o título dinâmico da página na barra do navegador --}}
@section('title', 'Editar Inscrição')

{{-- Conteúdo principal da página de edição de inscrição --}}
@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    {{-- Cabeçalho do Cartão com o ID da Inscrição e botão de retorno --}}
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0">Editar Inscrição: #{{ $enrollment->id }}</h4>
                            <p class="m-0 subtitle">Atualize a fotografia, os dados do candidato e o Curso</p>
                        </div>
                        <a href="{{ route('enrollment.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left me-1"></i> Voltar à Listagem
                        </a>
                    </div>

                    {{-- Formulário de Atualização com suporte para upload de novos ficheiros de fotografia --}}
                    <form action="{{ route('enrollment.update', $enrollment->id) }}" method="POST" enctype="multipart/form-data">
                        {{-- Diretivas CSRF e verbo HTTP PUT para atualização do registo --}}
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            {{-- Exibição de alertas e erros de validação do formulário --}}
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
                                {{-- Formulário editável do candidato e do curso --}}
                                <div class="col-xl-12 col-lg-12">
                                    <div class="row">
                                        {{-- Campo: Nome Completo do Formando --}}
                                        <div class="col-xl-6 col-sm-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label text-primary">Nome Completo <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $enrollment->student->name ?? '') }}" required>
                                            </div>
                                        </div>

                                        {{-- Campo: Número do BI --}}
                                        <div class="col-xl-6 col-sm-6">
                                            <div class="mb-3">
                                                <label for="identity_card_number" class="form-label text-primary">Número do BI <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="identity_card_number" name="identity_card_number" value="{{ old('identity_card_number', $enrollment->student->identity_card_number ?? '') }}" required>
                                            </div>
                                        </div>

                                        {{-- Campo: Endereço de E-mail --}}
                                        <div class="col-xl-6 col-sm-6">
                                            <div class="mb-3">
                                                <label for="email" class="form-label text-primary">Email <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $enrollment->student->email ?? '') }}" required>
                                            </div>
                                        </div>

                                        {{-- Campo: Género --}}
                                        <div class="col-xl-6 col-sm-6">
                                            <div class="mb-3">
                                                <label for="gender" class="form-label text-primary">Género</label>
                                                <select id="gender" name="gender" class="form-control" style="background-color: #ffffff !important;">
                                                    <option value="">Selecione o género...</option>
                                                    <option value="Masculino" {{ old('gender', $enrollment->student->gender ?? '') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                                    <option value="Feminino" {{ old('gender', $enrollment->student->gender ?? '') == 'Feminino' ? 'selected' : '' }}>Feminino</option>
                                                    <option value="Outro" {{ old('gender', $enrollment->student->gender ?? '') == 'Outro' ? 'selected' : '' }}>Outro</option>
                                                </select>
                                            </div>
                                        </div>

                                        {{-- Campo: Número de Telefone --}}
                                        <div class="col-xl-6 col-sm-6">
                                            <div class="mb-3">
                                                <label for="phone" class="form-label text-primary">Número de Telefone <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $enrollment->student->phone_number ?? $enrollment->student->phone ?? '') }}" required>
                                            </div>
                                        </div>

                                        {{-- Campo: Curso --}}
                                        <div class="col-xl-6 col-sm-6">
                                            <div class="mb-3">
                                                <label for="course_id" class="form-label text-primary">Curso <span class="text-danger">*</span></label>
                                                <select id="course_id" name="course_id" class="form-control" style="background-color: #ffffff !important;" required>
                                                    <option value="">Selecione o Curso...</option>
                                                    @foreach($courses as $course)
                                                        <option value="{{ $course->id }}" {{ old('course_id', $enrollment->course_id) == $course->id ? 'selected' : '' }}>
                                                            {{ $course->name }} (Cód: {{ $course->code ?? $course->id }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        {{-- Campo: Data da Inscrição (Leitura apenas - inalterável com fundo cinza) --}}
                                        <div class="col-xl-6 col-sm-6">
                                            <div class="mb-3">
                                                <label for="date_display" class="form-label text-primary">Data da Inscrição</label>
                                                <input type="text" class="form-control uneditable-field" id="date_display" value="{{ date('d/m/Y', strtotime($enrollment->date)) }}" style="background-color: #e9ecef !important;" readonly disabled>
                                            </div>
                                        </div>

                                        {{-- Campo: Estado da Inscrição (Automático - Não editável por select manual) --}}
                                        <div class="col-xl-6 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary d-block">Estado da Inscrição</label>
                                                <div class="mt-2">
                                                    @if($enrollment->status)
                                                        <span class="badge badge-success light fs-14 py-2 px-3"><i class="fa fa-check-circle me-1"></i> Confirmada</span>
                                                    @else
                                                        <span class="badge badge-warning light fs-14 py-2 px-3"><i class="fa fa-clock-o me-1"></i> Pendente </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Rodapé do Cartão com os botões de submissão --}}
                        <div class="card-footer text-end">
                            {{-- <a href="{{ route('enrollment.index') }}" class="btn btn-danger light me-2">Cancelar</a> --}}
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save me-1"></i> Atualizar Inscrição
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script de suporte à pré-visualização instantânea da fotografia enviada --}}
@push('scripts')
<script src="{{ asset('js/enrollment-form.js') }}"></script>
@endpush
@endsection
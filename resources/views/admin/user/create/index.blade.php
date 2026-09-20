@extends('layouts.main')

@section('title', 'Adicionar Novo Utilizador - Centro de Formação')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Adicionar Novo Administrador</h4>
                    <p class="mb-0">Registo de novos elementos de gestão da instituição pelo Super Admin</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <a href="{{ route('user.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fa fa-arrow-left me-1"></i> Voltar à Listagem
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Dados do Novo Utilizador</h4>
                    </div>
                    <form action="{{ route('user.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger alert-alt alert-dismissible fade show mb-4">
                                    <strong>Erro!</strong> Verifique os seguintes pontos:
                                    <ul class="mb-0 mt-1 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-xl-6 col-sm-6 mb-3">
                                    <label for="name" class="form-label text-primary font-w600">Nome Completo <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Ex: Carlos Alberto Silva" required>
                                </div>

                                <div class="col-xl-6 col-sm-6 mb-3">
                                    <label for="email" class="form-label text-primary font-w600">Endereço de E-mail <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="utilizador@centroformacao.co.ao" required>
                                </div>

                                <div class="col-xl-6 col-sm-6 mb-3">
                                    <label for="role" class="form-label text-primary font-w600">Perfil / Função de Acesso <span class="text-danger">*</span></label>
                                    <select id="role" name="role" class="form-control" required>
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador / Operador Normal</option>
                                        <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Administrador (Acesso Total)</option>
                                    </select>
                                    <small class="text-muted">O Super Administrador tem acesso à gestão total de contas e definições.</small>
                                </div>

                                <div class="col-xl-6 col-sm-6 mb-3">
                                    <label for="password" class="form-label text-primary font-w600">Palavra-passe <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Mínimo 6 carateres" required>
                                </div>

                                <div class="col-xl-6 col-sm-6 mb-3">
                                    <label for="password_confirmation" class="form-label text-primary font-w600">Confirmar Palavra-passe <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Repita a palavra-passe" required>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <a href="{{ route('user.index') }}" class="btn btn-danger light me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save me-1"></i> Cadastrar Utilizador
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

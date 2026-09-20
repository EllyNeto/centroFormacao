@extends('layouts.main')

@section('title', 'Gestão de Utilizadores - Centro de Formação')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Gestão de Utilizadores e Administradores</h4>
                    <p class="mb-0">Painel exclusivo do Super Administrador para gestão de contas da equipa</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <a href="{{ route('user.create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus me-1"></i> Adicionar Novo Administrador
                </a>
            </div>
        </div>

        {{-- Exibição de Mensagens de Sucesso ou Erro --}}
        @if(session('success'))
            <div class="alert alert-success alert-alt alert-dismissible fade show" role="alert">
                <strong>Sucesso!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-alt alert-dismissible fade show" role="alert">
                <strong>Erro!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Utilizadores do Sistema</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-responsive-md table-striped custom-table text-center">
                                <thead>
                                    <tr>
                                        <th>#ID</th>
                                        <th>Nome Completo</th>
                                        <th>E-mail</th>
                                        <th>Perfil / Função</th>
                                        <th>Estado</th>
                                        <th>Data de Registo</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                        <tr>
                                            <td><strong>#{{ sprintf('%03d', $user->id) }}</strong></td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @if($user->isSuperAdmin())
                                                    <span class="badge badge-primary light"><i class="fa fa-shield me-1"></i> Administrador</span>
                                                @elseif($user->role === 'secretaria')
                                                    <span class="badge badge-info light"><i class="fa fa-folder-open me-1"></i> Operador — Secretaria</span>
                                                @elseif($user->role === 'financas')
                                                    <span class="badge badge-success light"><i class="fa fa-calculator me-1"></i> Operador — Finanças</span>
                                                @else
                                                    <span class="badge badge-secondary light"><i class="fa fa-user-circle me-1"></i> {{ $user->role_name }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->status)
                                                    <span class="badge badge-success light">Ativo</span>
                                                @else
                                                    <span class="badge badge-danger light">Desativado</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/D' }}</td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    @if(auth()->id() != $user->id)
                                                        <form action="{{ route('user.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Tem a certeza que deseja remover este utilizador?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger shadow btn-xs sharp" title="Remover Utilizador">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="badge badge-secondary light fs-12">Sessão Atual</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">Nenhum utilizador registado.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

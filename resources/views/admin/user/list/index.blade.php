@extends('layout.main')

@section('title', 'Gestão de Utilizadores')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center border-bottom">
                        <div>
                            <h4 class="card-title mb-1 text-dark">Utilizadores do Sistema</h4>
                            <p class="m-0 subtitle text-muted">Contas administrativas com acesso autorizado à plataforma</p>
                        </div>
                        <a href="{{ route('user.create') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-user-plus me-1"></i> Registar Novo Utilizador
                        </a>
                    </div>

                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show mb-4">
                                <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show mb-4">
                                <i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table id="example4" class="display table table-hover style-1 custom-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nome Completo</th>
                                        <th>E-mail de Acesso</th>
                                        <th>Data de Registo</th>
                                        <th class="text-end">Acções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>
                                                <span class="badge light badge-primary font-w600">#{{ $user->id }}</span>
                                            </td>
                                            <td>
                                                <span class="font-w600 text-dark">{{ $user->name }}</span>
                                                @if (Auth::id() === $user->id)
                                                    <span class="badge light badge-success ms-2">Sessão Atual</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : '-' }}</td>
                                            <td class="text-end">
                                                @if (Auth::id() !== $user->id)
                                                    <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-inline" data-confirm="Tem a certeza que deseja remover este utilizador?">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-xs">
                                                            <i class="fa fa-trash me-1"></i> Remover
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-muted fs-8">Conta em uso</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
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

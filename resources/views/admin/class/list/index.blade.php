{{-- Extende o layout principal unificado da aplicação --}}
@extends('layouts.main')

{{-- Define o título dinâmico da página --}}
@section('title', 'Lista de Turmas')

{{-- Conteúdo principal da página de listagem de turmas --}}
@section('content')

<div class="content-body">
    {{-- Alerta de sucesso no modelo Alerts Alt exibido quando existe mensagem na sessão --}}
    @if(session('success'))
        <div class="alert alert-success alert-alt alert-dismissible fade show mb-4 me-4 ms-4" role="alert">
            <div><strong>Sucesso!</strong> {{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container-fluid">
        {{-- Inclusão do componente de gráficos seguindo a arquitetura de componentes do projeto (resources/views/components) --}}
       @include('components.graphic_class')

        <div class="element-area">
            <div class="demo-view">
                <div class="container-fluid pt-0 ps-0 pe-lg-4 pe-0">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card" id="accordion-one">
                                {{-- Cabeçalho do Cartão no modelo Profile Datatable --}}
                                <div class="card-header flex-wrap px-3 d-flex justify-content-between align-items-center">
                                    <div>
                                        <h4 class="card-title">Gestão de Turmas</h4>
                                        <p class="m-0 subtitle">Lista de todas as turmas de formação registadas</p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <a href="{{ route('class.create') }}" class="btn btn-primary btn-sm">
                                            <i class="fa fa-plus me-1"></i> Adicionar Nova Turma
                                        </a>
                                    </div>
                                </div>

                                {{-- Conteúdo com Tabela Datatable Profile --}}
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="Preview" role="tabpanel">
                                        <div class="card-body p-3">
                                            <div class="table-responsive">
                                                <table id="example" class="table-responsive-lg table display dataTablesCard student-tab profile-tab dataTable no-footer w-100" style="width: 100%;">
                                                    <thead>
                                                        <tr>
                                                            <th>#ID</th>
                                                            <th>Nome da Turma</th>
                                                            <th>Curso</th>
                                                            <th>Turno</th>
                                                            <th>Estado</th>
                                                            <th class="text-center" style="min-width: 120px;">Ações</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($classes as $classItem)
                                                            <tr>
                                                                <td><strong>#{{ $classItem->id }}</strong></td>
                                                                <td>
                                                                    <a href="{{ route('class.show', $classItem->id) }}" class="text-primary font-w600">
                                                                        {{ $classItem->name }}
                                                                    </a>
                                                                </td>
                                                                <td>{{ $classItem->course_name ?: 'N/A' }}</td>
                                                                <td>
                                                                    <span class="badge badge-light text-dark">
                                                                        <i class="fa fa-clock-o text-primary me-1"></i>{{ $classItem->shift }}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    @if($classItem->status)
                                                                        <span class="badge badge-success light">Activa</span>
                                                                    @else
                                                                        <span class="badge badge-danger light">Inactiva</span>
                                                                    @endif
                                                                </td>
                                                                <td class="text-center">
                                                                    <div class="d-flex justify-content-center align-items-center">
                                                                        {{-- Botão Ver Detalhes --}}
                                                                        <a href="{{ route('class.show', $classItem->id) }}" class="btn btn-info shadow btn-xs sharp me-1" title="Ver Detalhes">
                                                                            <i class="fa fa-eye"></i>
                                                                        </a>
                                                                        
                                                                        {{-- Botão Editar --}}
                                                                        <a href="{{ route('class.edit', $classItem->id) }}" class="btn btn-primary shadow btn-xs sharp me-1" title="Editar Turma">
                                                                            <i class="fa fa-pencil"></i>
                                                                        </a>
                                                        
                                                                        {{-- Botão Eliminar --}}
                                                                        <form action="{{ route('class.destroy', $classItem->id) }}" method="POST" onsubmit="return confirm('Tem a certeza que deseja eliminar esta turma?');" style="display: inline-block;">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="btn btn-danger shadow btn-xs sharp" title="Eliminar Turma">
                                                                                <i class="fa fa-trash"></i>
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="6" class="text-center py-4 text-muted">
                                                                    Nenhuma turma registada na base de dados.
                                                                </td>
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
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

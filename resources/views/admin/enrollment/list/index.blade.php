@extends('layouts.main')

@section('title', 'Lista de Inscrições')

@section('content')
<div class="content-body">
    @if(session('success'))
        <div class="alert alert-success alert-alt alert-dismissible fade show mb-4 me-4 ms-4" role="alert">
            <div><strong>Sucesso!</strong> {{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card" id="accordion-one">
                    <div class="card-header flex-wrap px-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">Gestão de Inscrições</h4>
                            <p class="m-0 subtitle">Lista de todas as inscrições registadas no sistema</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <a href="{{ route('enrollment.create') }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-plus me-1"></i> Adicionar Nova Inscrição
                            </a>
                        </div>
                    </div>

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="Preview" role="tabpanel">
                            <div class="card-body p-3">
                                <div class="table-responsive">
                                    <table id="example" class="table-responsive-lg table display dataTablesCard student-tab profile-tab dataTable no-footer w-100" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>#ID</th>
                                                <th>Estudante</th>
                                                <th>Curso</th>
                                                <th>Data da Inscrição</th>
                                                <th>Estado</th>
                                                <th class="text-center" style="min-width: 120px;">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($enrollments as $item)
                                                <tr>
                                                    <td><strong>#{{ $item->id }}</strong></td>
                                                    <td>
                                                        <a href="{{ route('enrollment.show', $item->id) }}" class="text-primary font-w600">
                                                            {{ $item->student->name ?? 'Estudante Removido' }}
                                                        </a>
                                                        @if(isset($item->student->code))
                                                            <small class="d-block text-muted">Cód: {{ $item->student->code }}</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-info light">
                                                            {{ $item->course->name ?? 'Curso N/D' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <i class="fa fa-calendar text-muted me-1"></i>
                                                        {{ date('d/m/Y H:i', strtotime($item->date)) }}
                                                    </td>
                                                    <td>
                                                        @if($item->status)
                                                            <span class="badge badge-success light">Confirmada</span>
                                                        @else
                                                            <span class="badge badge-warning light">Pendente</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="d-flex justify-content-center align-items-center">
                                                            <a href="{{ route('enrollment.show', $item->id) }}" class="btn btn-info shadow btn-xs sharp me-1" title="Ver Detalhes">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                            <a href="{{ route('enrollment.edit', $item->id) }}" class="btn btn-primary shadow btn-xs sharp me-1" title="Editar Inscrição">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                            <form action="{{ route('enrollment.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Tem a certeza que deseja eliminar esta inscrição?');" style="display: inline-block;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger shadow btn-xs sharp" title="Eliminar Inscrição">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-4 text-muted">
                                                        Nenhuma inscrição registada na base de dados.
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
@endsection
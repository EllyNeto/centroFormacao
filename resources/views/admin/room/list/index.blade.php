@extends('layouts.main')

@section('title', 'Lista de Turmas')

@section('content')
<div class="content-body">
    @if(session('success'))
        <div class="alert alert-success alert-alt alert-dismissible fade show mb-4 me-4 ms-4" role="alert">
            <div><strong>Sucesso!</strong> {{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container-fluid">
        <div class="element-area">
            <div class="demo-view">
                <div class="container-fluid pt-0 ps-0 pe-lg-4 pe-0">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card" id="accordion-one">
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
                                                            <th>Formador</th>
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
                                                                    <small class="d-block text-muted">Cód: {{ $classItem->code }}</small>
                                                                </td>
                                                                <td>
                                                                    <span class="badge badge-info light">
                                                                        {{ $classItem->course->name ?? 'N/A' }}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    {{ $classItem->teacher->name ?? 'N/A' }}
                                                                </td>
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
                                                                <td class="text-center align-middle text-center align-middle">
													<div class="dropdown custom-dropdown d-inline-block">
                                                                        <div class="btn sharp btn-light" data-bs-toggle="dropdown" data-bs-boundary="viewport" style="cursor: pointer;">
                                                                            <svg width="24" height="6" viewBox="0 0 24 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <path d="M12.0012 0.359985C11.6543 0.359985 11.3109 0.428302 10.9904 0.561035C10.67 0.693767 10.3788 0.888317 10.1335 1.13358C9.88829 1.37883 9.69374 1.67 9.56101 1.99044C9.42828 2.31089 9.35996 2.65434 9.35996 3.00119C9.35996 3.34803 9.42828 3.69148 9.56101 4.01193C9.69374 4.33237 9.88829 4.62354 10.1335 4.8688C10.3788 5.11405 10.67 5.3086 10.9904 5.44134C11.3109 5.57407 11.6543 5.64239 12.0012 5.64239C12.7017 5.64223 13.3734 5.36381 13.8686 4.86837C14.3638 4.37294 14.6419 3.70108 14.6418 3.00059C14.6416 2.3001 14.3632 1.62836 13.8677 1.13315C13.3723 0.637942 12.7004 0.359826 12 0.359985H12.0012ZM3.60116 0.359985C3.25431 0.359985 2.91086 0.428302 2.59042 0.561035C2.26997 0.693767 1.97881 0.888317 1.73355 1.13358C1.48829 1.37883 1.29374 1.67 1.16101 1.99044C1.02828 2.31089 0.959961 2.65434 0.959961 3.00119C0.959961 3.34803 1.02828 3.69148 1.16101 4.01193C1.29374 4.33237 1.48829 4.62354 1.73355 4.8688C1.97881 5.11405 2.26997 5.3086 2.59042 5.44134C2.91086 5.57407 3.25431 5.64239 3.60116 5.64239C4.30165 5.64223 4.97339 5.36381 5.4686 4.86837C5.9638 4.37294 6.24192 3.70108 6.24176 3.00059C6.2416 2.3001 5.96318 1.62836 5.46775 1.13315C4.97231 0.637942 4.30045 0.359826 3.59996 0.359985H3.60116ZM20.4012 0.359985C20.0543 0.359985 19.7109 0.428302 19.3904 0.561035C19.07 0.693767 18.7788 0.888317 18.5336 1.13358C18.2883 1.37883 18.0937 1.67 17.961 1.99044C17.8283 2.31089 17.76 2.65434 17.76 3.00119C17.76 3.34803 17.8283 3.69148 17.961 4.01193C18.0937 4.33237 18.2883 4.62354 18.5336 4.8688C18.7788 5.11405 19.07 5.3086 19.3904 5.44134C19.7109 5.57407 20.0543 5.64239 20.4012 5.64239C21.1017 5.64223 21.7734 5.36381 22.2686 4.86837C22.7638 4.37294 23.0419 3.70108 23.0418 3.00059C23.0416 2.3001 22.7632 1.62836 22.2677 1.13315C21.7723 0.637942 21.1005 0.359826 20.4 0.359985H20.4012Z" fill="#A098AE"/>
                                                                            </svg>
                                                                        </div>
                                                                        <div class="dropdown-menu dropdown-menu-end">
                                                                            <a class="dropdown-item" href="{{ route('class.show', $classItem->id) }}">Ver Detalhes</a>
                                                                            <a class="dropdown-item" href="{{ route('class.edit', $classItem->id) }}">Editar</a>
                                                                            <form action="{{ route('class.destroy', $classItem->id) }}" method="POST" onsubmit="return confirm('Tem a certeza que deseja eliminar esta turma?');">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit" class="dropdown-item text-danger">Eliminar</button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="7" class="text-center py-4 text-muted">
                                                                    Nenhuma turma registada.
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

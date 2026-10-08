@extends('layout.main')

@section('title', 'Listar Salas')

@section('content')
<div class="content-body">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xl-12">
				<div class="card" id="accordion-four">
					<div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center">
						<div>
							<h4 class="card-title mb-1">Gestão de Salas</h4>
							<p class="m-0 subtitle text-muted">Listagem das salas de aula registadas no sistema</p>
						</div>
						<a href="{{ route('classroom.create') }}" class="btn btn-primary btn-sm">
							<i class="fa fa-plus me-1"></i> Adicionar Nova Sala
						</a>
					</div>

					<div class="card-body p-4">
						@if (session('success'))
							<div class="alert alert-success alert-dismissible fade show mb-4">
								{{ session('success') }}
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
						@endif

						<div class="table-responsive">
							<table id="example4" class="display table style-1 custom-table" style="min-width: 845px; width: 100%;">
								<thead>
									<tr>
										<th>Número da Sala</th>
										<th>Capacidade</th>
										<th class="text-end">Acções</th>
									</tr>
								</thead>
								<tbody>
									@forelse ($classrooms as $classroom)
										<tr>
											<td><span class="badge light badge-primary">Sala {{ $classroom->number_of_classroom }}</span></td>
											<td>{{ $classroom->capacity ? $classroom->capacity . ' alunos' : 'N/A' }}</td>
											
											<td class="text-end action-cell">
												<div class="dropdown dropup ms-auto text-end c-pointer">
													<div class="btn-link" data-bs-toggle="dropdown" data-bs-placement="top" aria-expanded="false" role="button">
														<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
															<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																<rect x="0" y="0" width="24" height="24"></rect>
																<circle fill="#000000" cx="5" cy="12" r="2"></circle>
																<circle fill="#000000" cx="12" cy="12" r="2"></circle>
																<circle fill="#000000" cx="19" cy="12" r="2"></circle>
															</g>
														</svg>
													</div>
													<div class="dropdown-menu dropdown-menu-end">
														<a class="dropdown-item" href="{{ route('classroom.show', $classroom->id) }}">Ver Detalhes</a>
														<a class="dropdown-item" href="{{ route('classroom.edit', $classroom->id) }}">Editar</a>
														<form action="{{ route('classroom.destroy', $classroom->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem a certeza que deseja eliminar esta sala?')">
															@csrf
															@method('DELETE')
															<button type="submit" class="dropdown-item text-danger border-0 bg-transparent w-100 text-start">Eliminar</button>
														</form>
													</div>
												</div>
											</td>
										</tr>
									@empty
										<tr>
											<td colspan="6" class="text-center py-4 text-muted">
												<i class="fa fa-info-circle me-1"></i> Nenhuma sala cadastrada.
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
@endsection

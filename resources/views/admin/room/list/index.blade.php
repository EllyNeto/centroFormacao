@extends('layout.main')

@section('title', 'Listar Turmas')

@section('content')
{{-- Wrappers padrão do tema para enquadramento da página --}}
<div class="content-body">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xl-12">
				{{-- Cartão Principal da Tabela de Turmas --}}
				<div class="card" id="accordion-four">
					{{-- Cabeçalho do Cartão com título e botão de adição --}}
					<div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center">
						<div>
							<h4 class="card-title mb-1">Gestão de Turmas</h4>
							<p class="m-0 subtitle text-muted">Listagem e controlo de vagas das turmas ativas e concluídas</p>
						</div>
						{{-- Botão para navegar até o formulário de cadastro de nova turma --}}
						<a href="{{ route('room.create') }}" class="btn btn-primary btn-sm">
							<i class="fa fa-plus me-1"></i> Adicionar Nova Turma
						</a>
					</div>

					<div class="card-body p-4">
						{{-- Exibe mensagem de feedback de sucesso após operações CRUD --}}
						@if (session('success'))
							<div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
								<i class="fa fa-check-circle me-2"></i> {{ session('success') }}
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
						@endif

						{{-- Exibe mensagem de feedback de erro se a turma não puder ser eliminada --}}
						@if (session('error'))
							<div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
								<i class="fa fa-exclamation-triangle me-2"></i> {{ session('error') }}
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
						@endif

						{{-- Container responsivo da Tabela --}}
						<div class="table-responsive">
							<table id="example4" class="display table style-1 custom-table room-table-min-width">
								<thead>
									<tr>
										<th>Turma</th>
										<th>Curso</th>
										<th>Formador</th>
										<th>Vagas</th>
										<th>Estado</th>
										<th class="text-end">Acções</th>
									</tr>
								</thead>
								<tbody>
									{{-- Percorre dinamicamente os registos de turmas obtidos da base de dados --}}
									@forelse ($rooms as $room)
										@php
											$occupied = $room->occupiedSeats();
											$capacity = $room->max_capacity;
										@endphp
										<tr>
											{{-- Nome da Turma --}}
											<td>
												<a href="{{ route('room.show', $room->id) }}" class="text-dark font-w600 d-block">
													{{ $room->name }}
												</a>
											</td>

											{{-- Curso Associado --}}
											<td>
												<span class="badge light badge-primary">{{ $room->course->name ?? 'N/A' }}</span>
											</td>

											{{-- Formador Responsável --}}
											<td>
												<span>{{ $room->teacher->name ?? 'Não atribuído' }}</span>
											</td>

											{{-- Indicador de Vagas e Lotação --}}
											<td>
												<span class="font-w600 text-dark">{{ $occupied }}/{{ $capacity }}</span>
											</td>

											{{-- Estado da Turma --}}
											<td>
												@if($room->status == 'Aberta')
													<span class="badge badge-success light">Aberta</span>
												@elseif($room->status == 'Em curso')
													<span class="badge badge-warning light">Em curso</span>
												@elseif($room->status == 'Concluída')
													<span class="badge badge-info light">Concluída</span>
												@else
													<span class="badge badge-danger light">{{ $room->status }}</span>
												@endif
											</td>

											{{-- Célula de Acções com menu flutuante --}}
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
														<a class="dropdown-item" href="{{ route('room.show', $room->id) }}">
															<i class="fa fa-eye me-2 text-primary"></i> Ver Detalhes
														</a>
														<a class="dropdown-item" href="{{ route('room.printStudents', $room->id) }}" target="_blank">
															<i class="fa fa-print me-2 text-info"></i> Imprimir Pauta
														</a>
														<a class="dropdown-item" href="{{ route('room.edit', $room->id) }}">
															<i class="fa fa-pencil me-2 text-warning"></i> Editar
														</a>
														<form action="{{ route('room.destroy', $room->id) }}" method="POST" class="d-inline" data-confirm="Tem a certeza que deseja eliminar a turma {{ $room->name }}?">
															@csrf
															@method('DELETE')
															<button type="submit" class="dropdown-item text-danger border-0 bg-transparent w-100 text-start">
																<i class="fa fa-trash me-2 text-danger"></i> Eliminar
															</button>
														</form>
													</div>
												</div>
											</td>
										</tr>
									@empty
										<tr>
											<td colspan="6" class="text-center py-4 text-muted">
												<i class="fa fa-info-circle me-1"></i> Nenhuma turma registada no sistema.
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
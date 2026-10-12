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
										<th>Sala Física</th>
										<th>Horário</th>
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
											$percentage = $room->occupancyPercentage();
										@endphp
										<tr>
											{{-- Nome da Turma --}}
											<td>
												<strong>{{ $room->name }}</strong>
												@if($room->start_date && $room->end_date)
													<small class="d-block text-muted">
														<i class="fa fa-calendar me-1"></i> {{ $room->start_date->format('d/m/Y') }} - {{ $room->end_date->format('d/m/Y') }}
													</small>
												@endif
											</td>

											{{-- Curso Associado --}}
											<td>
												<span class="badge light badge-primary">{{ $room->course->name ?? 'N/A' }}</span>
											</td>

											{{-- Formador Responsável --}}
											<td>
												<div class="d-flex align-items-center">
													@if($room->teacher && $room->teacher->image)
														<img src="{{ asset('storage/' . $room->teacher->image) }}" class="rounded-circle avatar avatar-sm me-2 room-avatar-sm" alt="{{ $room->teacher->name }}">
													@endif
													<span>{{ $room->teacher->name ?? 'Não atribuído' }}</span>
												</div>
											</td>

											{{-- Sala Física com Capacidade --}}
											<td>
												@if($room->classroom)
													<span class="badge light badge-info font-w500">
														<i class="fa fa-building me-1"></i> Sala {{ $room->classroom->number_of_classroom }}
													</span>
												@else
													<span class="text-muted">Sem sala</span>
												@endif
											</td>

											{{-- Turno, Horário e Dias da Semana --}}
											<td>
												<div>
													<i class="fa fa-clock-o me-1 text-muted"></i>
													{{ \Carbon\Carbon::parse($room->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($room->end_time)->format('H:i') }}
												</div>
											</td>

											{{-- Indicador de Vagas e Lotação --}}
											<td>
												<div class="d-flex align-items-center gap-2">
													<small class="font-w600 text-dark">
														{{ $occupied }}/{{ $capacity }}
													</small>
												</div>
												{{-- @if($room->isFull())
													<small class="text-danger font-w500 d-block"><i class="fa fa-exclamation-circle"></i> Turma Lotada</small>
												@else
													<small class="text-success d-block">{{ $room->availableSeats() }} vagas livres</small>
												@endif --}}
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
													{{-- Ícone dos 3 pontos (...) --}}
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
													{{-- Menu suspenso de ações por turma --}}
													<div class="dropdown-menu dropdown-menu-end">
														<a class="dropdown-item" href="{{ route('room.show', $room->id) }}">
															<i class="fa fa-eye me-2 text-primary"></i> Ver Detalhes
														</a>
														<a class="dropdown-item" href="{{ route('room.edit', $room->id) }}">
															<i class="fa fa-pencil me-2 text-warning"></i> Editar
														</a>
														<form action="{{ route('room.destroy', $room->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem a certeza que deseja eliminar a turma \'{{ $room->name }}\'?')">
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
										{{-- Estado exibido quando não existem turmas cadastradas --}}
										<tr>
											<td colspan="8" class="text-center py-4 text-muted">
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
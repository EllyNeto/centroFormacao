@extends('layout.main')

@section('title','Listar Turmas')

@section('content')
{{-- Wrappers padrão do tema para enquadramento perfeito --}}
<div class="content-body">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xl-12">
				{{-- Cartão Principal da Tabela --}}
				<div class="card" id="accordion-four">
					{{-- Cabeçalho do Cartão com título e botão de adição --}}
					<div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center">
						<div>
							<h4 class="card-title mb-1">Gestão de Turmas</h4>
							<p class="m-0 subtitle text-muted">Listagem das turmas registadas no sistema</p>
						</div>
						{{-- Botão para navegar até o formulário de cadastro de nova turma --}}
						<a href="{{ route('room.create') }}" class="btn btn-primary btn-sm">
							<i class="fa fa-plus me-1"></i> Adicionar Nova Turma
						</a>
					</div>

					<div class="card-body p-4">
						{{-- Exibe mensagem de feedback de sucesso após operações CRUD --}}
						@if (session('success'))
							<div class="alert alert-success alert-dismissible fade show mb-4">
								{{ session('success') }}
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
						@endif

						{{-- Container responsivo da Tabela --}}
						<div class="table-responsive">
							<table id="example4" class="display table style-1 custom-table" style="min-width: 845px; width: 100%;">
								<thead>
									<tr>
										<th>Nome da Turma</th>
										<th>Curso</th>
										<th>Formador</th>
										<th>Turno</th>
										<th>Horário</th>
										<th class="text-end">Acções</th>
									</tr>
								</thead>
								<tbody>
									{{-- Percorre dinamicamente os registos obtidos da base de dados --}}
									@forelse ($rooms as $room)
										<tr>
											<td><strong>{{ $room->name }}</strong></td>
											<td>{{ $room->course->name ?? 'N/A' }}</td>
											<td>{{ $room->teacher->name ?? 'N/A' }}</td>
											<td>
												<span class="badge light badge-info">{{ $room->shift }}</span>
											</td>
											<td>
												<i class="fa fa-clock-o me-1 text-muted"></i>
												{{ \Carbon\Carbon::parse($room->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($room->end_time)->format('H:i') }}
											</td>

											{{-- Célula de Acções com dropdown flutuante --}}
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
													{{-- Menu suspenso flutuante --}}
													<div class="dropdown-menu dropdown-menu-end">
														<a class="dropdown-item" href="{{ route('room.show', $room->id) }}">Ver Detalhes</a>
														<a class="dropdown-item" href="{{ route('room.edit', $room->id) }}">Editar</a>
														<form action="{{ route('room.destroy', $room->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem a certeza que deseja eliminar esta turma?')">
															@csrf
															@method('DELETE')
															<button type="submit" class="dropdown-item text-danger border-0 bg-transparent w-100 text-start">Eliminar</button>
														</form>
													</div>
												</div>
											</td>
										</tr>
									@empty
										{{-- Estado quando não existem registos no banco de dados --}}
										<tr>
											<td colspan="7" class="text-center py-4 text-muted">
												<i class="fa fa-info-circle me-1"></i> Nenhuma turma cadastrada.
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
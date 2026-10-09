{{-- 
    Vista: Listagem de Salas de Aula Físicas (admin/classroom/list/index.blade.php)
    Descrição: Apresenta a tabela responsiva com todas as salas de aula cadastradas, 
               capacidade de lugares, total de turmas associadas e ações disponíveis (Ver, Editar, Eliminar).
--}}
@extends('layout.main')

@section('title', 'Listar Salas de Aula')

@section('content')
{{-- Estrutura principal da página (Theme Container) --}}
<div class="content-body">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xl-12">
				{{-- Cartão Principal da Tabela de Salas --}}
				<div class="card" id="accordion-four">
					
					{{-- Cabeçalho do Cartão: Título, Subtítulo e Botão de Adicionar Nova Sala --}}
					<div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center">
						<div>
							<h4 class="card-title mb-1">Gestão de Salas de Aula</h4>
							<p class="m-0 subtitle text-muted">Listagem dos espaços físicos e capacidade de lugares do centro de formação</p>
						</div>
						{{-- Botão de ação para criar nova sala --}}
						<a href="{{ route('classroom.create') }}" class="btn btn-primary btn-sm">
							<i class="fa fa-plus me-1"></i> Adicionar Nova Sala
						</a>
					</div>

					{{-- Corpo do Cartão com tabela de dados --}}
					<div class="card-body p-4">
						
						{{-- Exibição de mensagem de Sucesso das operações (CRUD) --}}
						@if (session('success'))
							<div class="alert alert-success alert-dismissible fade show mb-4">
								<i class="fa fa-check-circle me-2"></i> {{ session('success') }}
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
						@endif

						{{-- Exibição de mensagem de Erro (ex: bloqueio de eliminação de sala ativa) --}}
						@if (session('error'))
							<div class="alert alert-danger alert-dismissible fade show mb-4">
								<i class="fa fa-exclamation-triangle me-2"></i> {{ session('error') }}
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
						@endif

						{{-- Tabela Responsiva de Salas --}}
						<div class="table-responsive">
							<table id="example4" class="display table style-1 custom-table" style="min-width: 845px; width: 100%;">
								<thead>
									<tr>
										<th>Número da Sala</th>
										<th>Capacidade Física</th>
										<th>Turmas Alocadas</th>
										<th>Observações / Descrição</th>
										<th class="text-end">Acções</th>
									</tr>
								</thead>
								<tbody>
									{{-- Iteração sobre a lista de salas registadas --}}
									@forelse ($classrooms as $classroom)
										<tr>
											{{-- Número da Sala --}}
											<td>
												<span class="badge light badge-primary fs-14 fw-bold">
													Sala {{ $classroom->number_of_classroom }}
												</span>
											</td>

											{{-- Capacidade de Lugares --}}
											<td>
												<i class="fa fa-users me-1 text-muted"></i>
												{{ $classroom->capacity }} lugares
											</td>

											{{-- Total de Turmas Alocadas (contagem calculada no controller) --}}
											<td>
												<span class="badge light {{ $classroom->rooms_count > 0 ? 'badge-info' : 'badge-secondary' }}">
													{{ $classroom->rooms_count }} turma(s)
												</span>
											</td>

											{{-- Descrição ou Observação resumida --}}
											<td>
												{{ Str::limit($classroom->description, 50, '...') ?: 'Sem observações registadas' }}
											</td>

											{{-- Menu suspenso de ações por registo --}}
											<td class="text-end action-cell">
												<div class="dropdown dropup ms-auto text-end c-pointer">
													{{-- Ícone dos 3 pontos para abrir opções --}}
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

													{{-- Links de Ação: Ver Detalhes, Editar, Eliminar --}}
													<div class="dropdown-menu dropdown-menu-end">
														{{-- Ver Detalhes da Sala --}}
														<a class="dropdown-item" href="{{ route('classroom.show', $classroom->id) }}">
															<i class="fa fa-eye me-2 text-primary"></i> Ver Detalhes
														</a>
														
														{{-- Editar Dados da Sala --}}
														<a class="dropdown-item" href="{{ route('classroom.edit', $classroom->id) }}">
															<i class="fa fa-edit me-2 text-warning"></i> Editar
														</a>

														{{-- Eliminar Sala (Soft Delete com confirmação) --}}
														<form action="{{ route('classroom.destroy', $classroom->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem a certeza que deseja eliminar a Sala {{ $classroom->number_of_classroom }}?')">
															@csrf
															@method('DELETE')
															<button type="submit" class="dropdown-item text-danger border-0 bg-transparent w-100 text-start">
																<i class="fa fa-trash me-2"></i> Eliminar
															</button>
														</form>
													</div>
												</div>
											</td>
										</tr>
									@empty
										{{-- Estado quando não existem salas cadastradas na base de dados --}}
										<tr>
											<td colspan="5" class="text-center py-4 text-muted">
												<i class="fa fa-info-circle me-1"></i> Nenhuma sala de aula registada até ao momento.
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

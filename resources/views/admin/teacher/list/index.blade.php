@extends('layout.main')

@section('title', 'Listar Formadores')

@section('content')
{{-- Wrappers padrão do tema para enquadramento da página --}}
<div class="content-body">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xl-12">
				{{-- Cartão Principal da Tabela de Formadores --}}
				<div class="card" id="accordion-four">
					{{-- Cabeçalho do Cartão com título e botão para cadastrar novo formador --}}
					<div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center">
						<div>
							<h4 class="card-title mb-1">Gestão de Formadores</h4>
							<p class="m-0 subtitle text-muted">Listagem dos formadores e corpo docente registado no sistema</p>
						</div>
						{{-- Botão de navegação para o formulário de cadastro --}}
						<a href="{{ route('teacher.create') }}" class="btn btn-primary btn-sm">
							<i class="fa fa-plus me-1"></i> Adicionar Novo Formador
						</a>
					</div>

					<div class="card-body p-4">
						{{-- Exibe mensagem de sucesso após criar, atualizar ou eliminar um formador --}}
						@if (session('success'))
							<div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
								<i class="fa fa-check-circle me-2"></i> {{ session('success') }}
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
						@endif

						{{-- Exibe mensagem de erro se o formador não puder ser eliminado (ex: turmas ativas vinculadas) --}}
						@if (session('error'))
							<div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
								<i class="fa fa-exclamation-triangle me-2"></i> {{ session('error') }}
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
						@endif

						{{-- Container responsivo da Tabela --}}
						<div class="table-responsive">
							<table id="example4" class="display table table-hover style-1 custom-table">
								<thead>
									<tr>
										<th>Formador</th>
										<th>Contactos</th>
										<th>Género</th>
										<th>Especialização</th>
										<th>Turmas</th>
										<th class="text-end">Acções</th>
									</tr>
								</thead>
								<tbody>
									@forelse ($teachers as $teacher)
										<tr>
											{{-- Fotografia e Nome do Formador --}}
											<td>
												<div class="d-flex align-items-center">
													<img src="{{ $teacher->image ? asset('storage/' . $teacher->image) : asset('images/no-img-avatar.png') }}" 
														 class="cf-avatar-md me-2" 
														 alt="{{ $teacher->name }}">
													<div>
														<a href="{{ route('teacher.show', $teacher->id) }}" class="text-dark font-w600 d-block">
															{{ $teacher->name }}
														</a>
														<small class="text-muted">BI: {{ $teacher->number_of_identify }}</small>
													</div>
												</div>
											</td>

											{{-- Contactos --}}
											<td>
												<div class="fs-7">
													<div><i class="fa fa-phone text-muted me-1"></i>{{ $teacher->phone }}</div>
													<div class="text-muted"><i class="fa fa-envelope text-muted me-1"></i>{{ $teacher->email }}</div>
												</div>
											</td>

											{{-- Género --}}
											<td>
												<span class="badge light {{ $teacher->gender == 'Masculino' ? 'badge-info' : 'badge-secondary' }}">
													{{ $teacher->gender ?? 'N/A' }}
												</span>
											</td>

											{{-- Especialização --}}
											<td>
												<span class="badge light badge-primary">{{ $teacher->specialization }}</span>
											</td>

											{{-- Total de Turmas Lecionadas --}}
											<td>
												<span class="badge badge-outline-primary">
													{{ $teacher->rooms_count ?? 0 }} {{ ($teacher->rooms_count ?? 0) == 1 ? 'Turma' : 'Turmas' }}
												</span>
											</td>

											{{-- Célula de Acções --}}
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
														<a class="dropdown-item" href="{{ route('teacher.show', $teacher->id) }}">
															<i class="fa fa-eye me-2 text-primary"></i> Ver Detalhes
														</a>
														<a class="dropdown-item" href="{{ route('teacher.edit', $teacher->id) }}">
															<i class="fa fa-pencil me-2 text-warning"></i> Editar
														</a>
														<form action="{{ route('teacher.destroy', $teacher->id) }}" method="POST" class="d-inline" data-confirm="Tem a certeza que deseja eliminar o formador {{ $teacher->name }}?">
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
												<i class="fa fa-info-circle me-1"></i> Nenhum formador registado na base de dados.
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
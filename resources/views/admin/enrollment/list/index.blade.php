@extends('layout.main')

@section('title', 'Listar Inscrições')

@section('content')
{{-- Estrutura principal da página de listagem de inscrições/candidaturas --}}
<div class="content-body">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xl-12">
				<div class="card">
					{{-- Cabeçalho do Cartão com Título e Ação Rápida --}}
					<div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center border-bottom">
						<div>
							<h4 class="card-title mb-1 text-dark">Gestão de Inscrições e Candidaturas</h4>
							<p class="m-0 subtitle text-muted">Acompanhamento de pré-inscrições, turmas atribuídas e estado do processo</p>
						</div>
						<a href="{{ route('enrollment.create') }}" class="btn btn-primary btn-sm">
							<i class="fa fa-plus me-1"></i> Nova Candidatura
						</a>
					</div>

					<div class="card-body p-4">
						{{-- Mensagem de Sucesso --}}
						@if (session('success'))
							<div class="alert alert-success alert-dismissible fade show mb-4 enrollment-alert">
								<i class="fa fa-check-circle me-2"></i>{{ session('success') }}
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
						@endif

						{{-- Mensagem de Aviso (ex: colocado em Lista de Espera por turma lotada) --}}
						@if (session('warning'))
							<div class="alert alert-warning alert-dismissible fade show mb-4 enrollment-alert">
								<i class="fa fa-exclamation-triangle me-2"></i>{{ session('warning') }}
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
						@endif

						{{-- Mensagem de Erro --}}
						@if (session('error'))
							<div class="alert alert-danger alert-dismissible fade show mb-4 enrollment-alert">
								<i class="fa fa-times-circle me-2"></i>{{ session('error') }}
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
						@endif

						{{-- Tabela Responsiva de Inscrições --}}
						<div class="table-responsive">
							<table id="example4" class="display table style-1 custom-table enrollment-table-min-width">
								<thead>
									<tr>
										<th>Código</th>
										<th>Foto</th>
										<th>Nome</th>
										<th>Curso</th>
										<th>Estado</th>
										<th class="text-end">Acções</th>
									</tr>
								</thead>
								<tbody>
									@forelse ($enrollments as $enrollment)
										<tr>
											{{-- Código Oficial da Candidatura --}}
											<td>
												<span class="badge light badge-primary font-w600">
													{{ $enrollment->formattedCode() }}
												</span>
											</td>

											{{-- Fotografia de Identificação do Candidato --}}
											<td>
												<div class="d-flex align-items-center">
													@if($enrollment->image)
														<img src="{{ asset('storage/' . $enrollment->image) }}" 
															 class="candidate-avatar-sm me-2" 
															 alt="{{ $enrollment->name }}">
													@else
														<div class="candidate-avatar-placeholder-sm me-2">
															<i class="fa fa-user"></i>
														</div>
													@endif
												</div>
											</td>
											<td>
												<strong class="text-dark d-block">{{ $enrollment->name }}</strong>
											</td>
											{{-- Curso Pretendido --}}
											<td>
												<span class="fw-semibold text-dark">{{ $enrollment->course->name ?? 'N/A' }}</span>
											</td>

											{{-- Estado da Candidatura com Cores Consistentes --}}
											<td>
												<span class="badge light {{ $enrollment->statusBadgeClass() }} px-3 py-2">
													{{ $enrollment->status }}
												</span>
											</td>

											{{-- Coluna de Ações com Menu Dropdown --}}
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
														<a class="dropdown-item" href="{{ route('enrollment.show', $enrollment->id) }}">
															<i class="fa fa-eye me-2 text-primary"></i> Ver Detalhes
														</a>
														<a class="dropdown-item" href="{{ route('enrollment.edit', $enrollment->id) }}">
															<i class="fa fa-pencil me-2 text-warning"></i> Editar
														</a>
														<form action="{{ route('enrollment.destroy', $enrollment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem a certeza que deseja eliminar esta candidatura?')">
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
										<tr>
											<td colspan="7" class="text-center py-5 text-muted">
												<i class="fa fa-id-card-o fa-2x mb-2 d-block"></i>
												Nenhuma candidatura ou inscrição registada até ao momento.
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
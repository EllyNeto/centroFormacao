@extends('layout.main')

@section('title', 'Listar Formadores')

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
							<h4 class="card-title mb-1">Gestão de Formadores</h4>
							<p class="m-0 subtitle text-muted">Listagem dos formadores registados no sistema</p>
						</div>
						{{-- Botão para navegar até o formulário de cadastro de novo Formador --}}
						<a href="{{ route('teacher.create') }}" class="btn btn-primary btn-sm">
							<i class="fa fa-plus me-1"></i> Adicionar Novo Formador
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
										<th>Fotografia</th>
										<th>Nome</th>
										<th>Género</th>
										<th>Especialização</th>
										<th>Email</th>
										<th>Telefone</th>
										<th>Nº Identificação</th>
										<th class="text-end">Acções</th>
									</tr>
								</thead>
								<tbody>
									@forelse ($teachers as $teacher)
										<tr>
											<td>
												<div class="d-flex align-items-center">
													<img src="{{ $teacher->image ? asset('storage/' . $teacher->image) : asset('images/no-img-avatar.png') }}" class="rounded-circle avatar avatar-md me-2" alt="{{ $teacher->name }}" style="width: 45px; height: 45px; object-fit: cover; border: 2px solid #e2e8f0;">
												</div>
											</td>
											<td><strong>{{ $teacher->name }}</strong></td>
											<td>
												<span class="badge light badge-info">{{ $teacher->gender ?? 'N/A' }}</span>
											</td>
											<td>
												<span class="badge light badge-primary">{{ $teacher->specialization }}</span>
											</td>
											<td>{{ $teacher->email }}</td>
											<td>{{ $teacher->phone }}</td>
											<td>{{ $teacher->number_of_identify }}</td>

											{{-- Célula de Acções com classe dedicada .action-cell e dropdown flutuante --}}
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
													{{-- Menu suspenso flutuante com as opções da linha --}}
													<div class="dropdown-menu dropdown-menu-end">
														<a class="dropdown-item" href="{{ route('teacher.show', $teacher->id) }}">Ver Detalhes</a>
														<a class="dropdown-item" href="{{ route('teacher.edit', $teacher->id) }}">Editar</a>
														<form action="{{ route('teacher.destroy', $teacher->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem a certeza que deseja eliminar este Formador?')">
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
												<i class="fa fa-info-circle me-1"></i> Nenhum formador encontrado registado.
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
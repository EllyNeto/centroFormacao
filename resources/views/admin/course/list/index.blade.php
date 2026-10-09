{{-- 
    Vista: Listagem de Cursos (admin/course/list/index.blade.php)
    Descrição: Apresenta a tabela com a oferta formativa (cursos), carga horária em horas, 
               preço em Kwanzas, estado de atividade, turmas alocadas e opções de gestão (Ver, Editar, Eliminar).
--}}
@extends('layout.main')

@section('title', 'Listar Cursos')

@section('content')
{{-- Estrutura principal da página (Theme Container) --}}
<div class="content-body">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xl-12">
				{{-- Cartão Principal da Tabela de Cursos --}}
				<div class="card" id="accordion-four">
					
					{{-- Cabeçalho do Cartão com Título e Botão de Cadastro --}}
					<div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center">
						<div>
							<h4 class="card-title mb-1">Gestão de Cursos</h4>
							<p class="m-0 subtitle text-muted">Listagem dos cursos da oferta formativa do centro</p>
						</div>
						{{-- Botão de navegação para a página de criação de curso --}}
						<a href="{{ route('course.create') }}" class="btn btn-primary btn-sm">
							<i class="fa fa-plus me-1"></i> Adicionar Novo Curso
						</a>
					</div>

					{{-- Corpo do Cartão com tabela de dados --}}
					<div class="card-body p-4">
						
						{{-- Alerta de Sucesso após operações CRUD --}}
						@if (session('success'))
							<div class="alert alert-success alert-dismissible fade show mb-4">
								<i class="fa fa-check-circle me-2"></i> {{ session('success') }}
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
						@endif

						{{-- Alerta de Erro (ex: tentativa de eliminar curso em uso) --}}
						@if (session('error'))
							<div class="alert alert-danger alert-dismissible fade show mb-4">
								<i class="fa fa-exclamation-triangle me-2"></i> {{ session('error') }}
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
						@endif

						{{-- Tabela Responsiva de Cursos --}}
						<div class="table-responsive">
							<table id="example4" class="display table style-1 custom-table" style="min-width: 845px; width: 100%;">
								<thead>
									<tr>
										<th>Nome do Curso</th>
										<th>Carga Horária</th>
										<th>Preço (Kz)</th>
										<th>Estado</th>
										<th>Turmas Ativas</th>
										<th class="text-end">Acções</th>
									</tr>
								</thead>
								<tbody>
									{{-- Iteração sobre a lista de cursos cadastrados --}}
									@forelse ($courses as $course)
										<tr>
											{{-- Nome do Curso --}}
											<td><strong>{{ $course->name }}</strong></td>

											{{-- Duração em Horas --}}
											<td>
												<i class="fa fa-clock-o me-1 text-muted"></i>
												{{ $course->duration }} horas
											</td>

											{{-- Preço Formatado em Kz --}}
											<td>
												<strong>{{ number_format($course->value, 2, ',', '.') }} Kz</strong>
											</td>

											{{-- Estado (Ativo / Inativo) --}}
											<td>
												@if($course->status)
													<span class="badge light badge-success">Ativo</span>
												@else
													<span class="badge light badge-danger">Inativo</span>
												@endif
											</td>

											{{-- Contagem de Turmas Alocadas --}}
											<td>
												<span class="badge light {{ ($course->rooms_count ?? 0) > 0 ? 'badge-info' : 'badge-secondary' }}">
													{{ $course->rooms_count ?? 0 }} turma(s)
												</span>
											</td>

											{{-- Célula de Ações com menu suspenso --}}
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

													{{-- Links de Ação --}}
													<div class="dropdown-menu dropdown-menu-end">
														{{-- Ver Detalhes --}}
														<a class="dropdown-item" href="{{ route('course.show', $course->id) }}">
															<i class="fa fa-eye me-2 text-primary"></i> Ver Detalhes
														</a>

														{{-- Editar Curso --}}
														<a class="dropdown-item" href="{{ route('course.edit', $course->id) }}">
															<i class="fa fa-edit me-2 text-warning"></i> Editar
														</a>

														{{-- Eliminar Curso --}}
														<form action="{{ route('course.destroy', $course->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem a certeza que deseja eliminar o curso {{ $course->name }}?')">
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
										{{-- Estado quando não existem cursos registados --}}
										<tr>
											<td colspan="6" class="text-center py-4 text-muted">
												<i class="fa fa-info-circle me-1"></i> Nenhum curso registado até ao momento.
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
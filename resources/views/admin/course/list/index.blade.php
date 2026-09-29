{{-- Extende a estrutura base do layout principal --}}
@extends('layout.main')

{{-- Define o título da página exibido na aba do navegador --}}
@section('title', 'Listar Cursos')

{{-- Bloco do Conteúdo Principal da Página --}}
@section('content')
{{-- Wrappers padrão do tema para enquadramento perfeito (evita sobreposição no sidebar/header) --}}
<div class="content-body">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xl-12">
				{{-- Cartão Principal da Tabela --}}
				<div class="card" id="accordion-four">
					{{-- Cabeçalho do Cartão com título e botão de adição --}}
					<div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center">
						<div>
							<h4 class="card-title mb-1">Gestão de Cursos</h4>
							<p class="m-0 subtitle text-muted">Listagem dos cursos registados no sistema</p>
						</div>
						{{-- Botão para navegar até o formulário de cadastro de novo curso --}}
						<a href="{{ route('course.create') }}" class="btn btn-primary btn-sm">
							<i class="fa fa-plus me-1"></i> Adicionar Novo Curso
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

						{{-- Container responsivo da Tabela com suporte para rolagem horizontal em mobile --}}
						<div class="table-responsive">
							<table id="example4" class="display table style-1 custom-table" style="min-width: 845px; width: 100%;">
								<thead>
									<tr>
										<th>Nome</th>
										<th>Duração</th>
										<th>Estado</th>
										<th>Valor</th>
										<th class="text-end">Acções</th>
									</tr>
								</thead>
								<tbody>
									{{-- Percorre dinamicamente os registos obtidos da base de dados --}}
									@forelse ($courses as $course)
										<tr>
											<td>{{ $course->name }}</td>
											<td>{{ $course->duration }} horas</td>
											<td>
												@if($course->status)
													<span class="badge light badge-success">Activo</span>
												@else
													<span class="badge light badge-danger">Inactivo</span>
												@endif
											</td>
											<td><strong>{{ number_format($course->value, 2, ',', '.') }} Kz</strong></td>

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
														<a class="dropdown-item" href="{{ route('course.show', $course->id) }}">Ver Detalhes</a>
														<a class="dropdown-item" href="{{ route('course.edit', $course->id) }}">Editar</a>
														<form action="{{ route('course.destroy', $course->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem a certeza que deseja eliminar este curso?')">
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
											<td colspan="5" class="text-center py-4 text-muted">
												<i class="fa fa-info-circle me-1"></i> Nenhum curso castrado.
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
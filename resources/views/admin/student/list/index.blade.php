@extends('layout.main')

@section('title', 'Listar Formandos')

@section('content')
{{-- Wrappers padrão do tema DexignLab para enquadramento da página --}}
<div class="content-body">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xl-12">
				{{-- Cartão Principal da Tabela de Formandos --}}
				<div class="card" id="accordion-four">
					{{-- Cabeçalho do Cartão com título descritivo e botão de ação rápida --}}
					<div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center">
						<div>
							<h4 class="card-title mb-1 text-dark">Gestão de Formandos</h4>
							<p class="m-0 subtitle text-muted">Listagem oficial de formandos matriculados no centro de formação</p>
						</div>
						{{-- Botão de navegação para efetivar uma nova matrícula manual --}}
						<a href="{{ route('student.create') }}" class="btn btn-primary btn-sm">
							<i class="fa fa-user-plus me-1"></i> Nova Matrícula
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

						{{-- Exibe mensagem de erro caso ocorra impedimento de negócio --}}
						@if (session('error'))
							<div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
								<i class="fa fa-exclamation-triangle me-2"></i> {{ session('error') }}
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
						@endif

						{{-- Exibe mensagem informativa --}}
						@if (session('info'))
							<div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
								<i class="fa fa-info-circle me-2"></i> {{ session('info') }}
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
						@endif

						{{-- Container responsivo da Tabela com DataTables integrado --}}
						<div class="table-responsive">
							<table id="example4" class="display table style-1 custom-table" style="min-width: 845px; width: 100%;">
								<thead>
									<tr>
										<th>Fotografia</th>
										<th>Código</th>
										<th>Nome Completo</th>
										<th>Documento (BI)</th>
										<th>Curso</th>
										<th>Turma</th>
										<th>Contactos</th>
										<th class="text-end">Acções</th>
									</tr>
								</thead>
								<tbody>
									@forelse ($students as $student)
										<tr>
											{{-- Fotografia de Perfil do Formando --}}
											<td>
												<div class="d-flex align-items-center">
													<img src="{{ $student->avatarUrl() }}" 
														 class="rounded-circle avatar avatar-md me-2" 
														 alt="{{ $student->name }}" 
														 style="width: 45px; height: 45px; object-fit: cover; border: 2px solid #e2e8f0;">
												</div>
											</td>

											{{-- Código Oficial Único do Formando --}}
											<td>
												<span class="badge light badge-primary font-w600 fs-7">
													{{ $student->formattedCode() }}
												</span>
											</td>

											{{-- Nome Completo do Formando --}}
											<td>
												<a href="{{ route('student.show', $student->id) }}" class="text-dark font-w600 text-hover-primary">
													{{ $student->name }}
												</a>
											</td>

											{{-- Bilhete de Identidade (BI) --}}
											<td>
												<span class="text-dark">{{ $student->number_of_identify }}</span>
											</td>

											{{-- Curso Frequentado --}}
											<td>
												@if($student->course)
													<span class="badge light badge-info">
														{{ $student->course->name }}
													</span>
												@else
													<span class="text-muted">N/D</span>
												@endif
											</td>

											{{-- Turma e Sala --}}
											<td>
												@if($student->room)
													<div>
														<strong class="text-dark">{{ $student->room->name }}</strong>
														<small class="d-block text-muted">
															{{ $student->room->shift }} &bull; 
															{{ $student->room->classroom ? 'Sala ' . $student->room->classroom->number_of_classroom : 'Sem sala' }}
														</small>
													</div>
												@else
													<span class="badge light badge-warning">Não alocado</span>
												@endif
											</td>

											{{-- Contactos (Telefone e Email) --}}
											<td>
												<div class="fs-7">
													<div><i class="fa fa-phone text-muted me-1"></i>{{ $student->phone }}</div>
													<div class="text-muted"><i class="fa fa-envelope text-muted me-1"></i>{{ $student->email }}</div>
												</div>
											</td>

											{{-- Célula de Ações com Menu Dropdown --}}
											<td class="text-end action-cell">
												<div class="dropdown dropup ms-auto text-end c-pointer">
													{{-- Botão acionador dos 3 pontos --}}
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
													{{-- Menu suspenso de ações individuais por formando --}}
													<div class="dropdown-menu dropdown-menu-end">
														<a class="dropdown-item" href="{{ route('student.show', $student->id) }}">
															<i class="fa fa-eye me-2 text-primary"></i> Ver Ficha Completa
														</a>
														<a class="dropdown-item" href="{{ route('student.edit', $student->id) }}">
															<i class="fa fa-pencil me-2 text-warning"></i> Editar Dados
														</a>
														@if($student->room_id)
															<a class="dropdown-item" href="{{ route('room.printStudents', $student->room_id) }}" target="_blank">
																<i class="fa fa-print me-2 text-info"></i> Pauta da Turma
															</a>
														@endif
														<form action="{{ route('student.destroy', $student->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem a certeza que deseja remover o formando {{ $student->name }}? A inscrição de origem retornará ao estado Pago.')">
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
										{{-- Estado quando ainda não existem formandos registados --}}
										<tr>
											<td colspan="8" class="text-center py-5 text-muted">
												<i class="fa fa-graduation-cap fa-3x mb-3 d-block text-muted"></i>
												<h5 class="text-dark">Nenhum formando matriculado até ao momento</h5>
												<p class="text-muted fs-7">Para matricular formandos, efetive as inscrições que já possuem pagamento liquidado ('Pago').</p>
												<a href="{{ route('enrollment.index') }}" class="btn btn-outline-primary btn-sm mt-2">
													<i class="fa fa-list me-1"></i> Ver Candidaturas
												</a>
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
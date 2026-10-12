@extends('layout.main')

@section('title', 'Detalhes do Formador')

@section('content')
{{-- Estrutura principal da página de detalhes e perfil do formador --}}
<div class="content-body">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xl-12">
				<div class="card">
					{{-- Cabeçalho do Cartão com título e ações rápidas --}}
					<div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center">
						<div>
							<h4 class="card-title mb-1">Perfil do Formador</h4>
							<p class="m-0 subtitle text-muted">Informações detalhadas e turmas lecionadas pelo formador</p>
						</div>
						<div>
							<a href="{{ route('teacher.edit', $teacher->id) }}" class="btn btn-primary btn-sm me-1">
								<i class="fa fa-pencil me-1"></i> Editar
							</a>
							<a href="{{ route('teacher.index') }}" class="btn btn-secondary btn-sm">
								<i class="fa fa-arrow-left me-1"></i> Voltar
							</a>
						</div>
					</div>

					<div class="card-body p-4">
						{{-- Seção de Perfil Superior com Foto, Nome e Especialização --}}
						<div class="row align-items-center mb-4 pb-3 border-bottom">
							{{-- Foto de Perfil --}}
							<div class="col-auto">
								<img src="{{ $teacher->image ? asset('storage/' . $teacher->image) : asset('images/no-img-avatar.png') }}" 
									 alt="{{ $teacher->name }}" 
									 class="rounded-circle shadow-sm" 
									 style="width: 110px; height: 110px; object-fit: cover; border: 3px solid #1E40AF;">
							</div>
							<div class="col">
								<h3 class="font-w600 mb-1" style="color: #1e293b;">{{ $teacher->name }}</h3>
								<div class="d-flex align-items-center flex-wrap gap-2">
									<span class="badge light badge-primary px-3 py-2 fs-6">{{ $teacher->specialization }}</span>
									<span class="text-muted"><i class="fa fa-id-card me-1 text-info"></i> BI: {{ $teacher->number_of_identify }}</span>
									<span class="text-muted"><i class="fa fa-calendar me-1"></i> Registado em: {{ $teacher->created_at ? $teacher->created_at->format('d/m/Y') : 'N/A' }}</span>
								</div>
							</div>
						</div>

						{{-- Grelha de Informações Detalhadas de Contacto e Identificação --}}
						<div class="row g-4 mb-5">
							<div class="col-xl-4 col-md-6">
								<div class="p-3 rounded bg-light border">
									<small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Nome Completo</small>
									<span class="fs-6 font-w600 text-dark">{{ $teacher->name }}</span>
								</div>
							</div>

							<div class="col-xl-4 col-md-6">
								<div class="p-3 rounded bg-light border">
									<small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Endereço de Email</small>
									<span class="fs-6 font-w600 text-dark"><i class="fa fa-envelope text-primary me-1"></i> {{ $teacher->email }}</span>
								</div>
							</div>

							<div class="col-xl-4 col-md-6">
								<div class="p-3 rounded bg-light border">
									<small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Contacto Telefónico</small>
									<span class="fs-6 font-w600 text-dark"><i class="fa fa-phone text-success me-1"></i> {{ $teacher->phone }}</span>
								</div>
							</div>

							<div class="col-xl-4 col-md-6">
								<div class="p-3 rounded bg-light border">
									<small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Género</small>
									<span class="fs-6 font-w600 text-dark"><i class="fa fa-user text-secondary me-1"></i> {{ $teacher->gender ?? 'Não informado' }}</span>
								</div>
							</div>

							<div class="col-xl-4 col-md-6">
								<div class="p-3 rounded bg-light border">
									<small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Especialização / Área</small>
									<span class="fs-6 font-w600 text-dark">{{ $teacher->specialization }}</span>
								</div>
							</div>

							<div class="col-xl-4 col-md-6">
								<div class="p-3 rounded bg-light border">
									<small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Número do Bilhete de Identificação (BI)</small>
									<span class="fs-6 font-w600 text-dark"><i class="fa fa-id-card text-info me-1"></i> {{ $teacher->number_of_identify }}</span>
								</div>
							</div>
						</div>

						{{-- Tabela de Turmas Atribuidas ao Formador --}}
						<div class="mt-4">
							<h5 class="font-w600 mb-3" style="color: #1e293b;">
								<i class="fa fa-graduation-cap text-primary me-2"></i> Turmas Atribuídas a este Formador
							</h5>
							<div class="table-responsive">
								<table class="table table-bordered table-hover style-1">
									<thead class="thead-primary">
										<tr>
											<th>Código da Turma</th>
											<th>Curso</th>
											<th>Sala Física</th>
											<th>Horário / Período</th>
											<th>Estado</th>
										</tr>
									</thead>
									<tbody>
										@forelse ($teacher->rooms as $room)
											<tr>
												<td><strong>{{ $room->code_room ?? 'TURMA-' . $room->id }}</strong></td>
												<td>{{ $room->course->name ?? 'N/A' }}</td>
												<td>
													@if($room->classroom)
														Sala {{ $room->classroom->number_of_classroom }}
													@else
														<span class="text-muted">Não atribuída</span>
													@endif
												</td>
												<td>
													<div>{{ $room->start_time }} - {{ $room->end_time }}</div>
													<small class="text-muted">{{ $room->shift }}</small>
												</td>
												<td>
													@if($room->status == 'Aberta')
														<span class="badge light badge-success">Aberta</span>
													@elseif($room->status == 'Em curso')
														<span class="badge light badge-warning">Em curso</span>
													@elseif($room->status == 'Concluída')
														<span class="badge light badge-info">Concluída</span>
													@else
														<span class="badge light badge-danger">{{ $room->status }}</span>
													@endif
												</td>
											</tr>
										@empty
											<tr>
												<td colspan="5" class="text-center py-3 text-muted">
													<i class="fa fa-info-circle me-1"></i> Este formador ainda não possui turmas atribuídas.
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
</div>
@endsection
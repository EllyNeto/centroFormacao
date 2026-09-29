@extends('layout.main')

@section('title', 'Detalhes do Formador')

@section('content')
<div class="content-body">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xl-12">
				<div class="card">
					<div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center">
						<div>
							<h4 class="card-title mb-1">Perfil do Formador</h4>
							<p class="m-0 subtitle text-muted">Informações detalhadas do registo do formador</p>
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
						<div class="row align-items-center mb-4 pb-3 border-bottom">
							{{-- Foto de Perfil --}}
							<div class="col-auto">
								<img src="{{ $teacher->image ? asset('storage/' . $teacher->image) : asset('images/no-img-avatar.png') }}" alt="{{ $teacher->name }}" class="rounded-circle shadow-sm" style="width: 110px; height: 110px; object-fit: cover; border: 3px solid #1E40AF;">
							</div>
							<div class="col">
								<h3 class="font-w600 mb-1" style="color: #1e293b;">{{ $teacher->name }}</h3>
								<div class="d-flex align-items-center flex-wrap gap-2">
									<span class="badge light badge-primary px-3 py-2 fs-6">{{ $teacher->specialization }}</span>
									<span class="text-muted"><i class="fa fa-calendar me-1"></i> Registado em: {{ $teacher->created_at ? $teacher->created_at->format('d/m/Y') : 'N/A' }}</span>
								</div>
							</div>
						</div>

						{{-- Grelha de Informações Detalhadas --}}
						<div class="row g-4">
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
									<small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Telefone</small>
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
									<small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Nº do  Bilhete de Identificação </small>
									<span class="fs-6 font-w600 text-dark"><i class="fa fa-id-card text-info me-1"></i> {{ $teacher->number_of_identify }}</span>
								</div>
							</div>

							{{-- <div class="col-xl-4 col-md-6">
								<div class="p-3 rounded bg-light border">
									<small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Última Atualização</small>
									<span class="fs-6 font-w600 text-dark">{{ $teacher->updated_at ? $teacher->updated_at->format('d/m/Y H:i') : 'N/A' }}</span>
								</div>
							</div> --}}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
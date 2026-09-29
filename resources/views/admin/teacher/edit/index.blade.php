@extends('layout.main')

@section('title', 'Editar Formador')

@section('content')
<div class="content-body">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xl-12">
				<div class="akademi-card">
					{{-- Header do Formulário --}}
					<div class="akademi-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
						<div>
							<h3 class="akademi-title mb-1">Editar Formador: {{ $teacher->name }}</h3>
							<p class="akademi-subtitle mb-0">Atualize os dados do formador e guarde as alterações</p>
						</div>
						<a href="{{ route('teacher.index') }}" class="btn-orange-back">
							<i class="fa fa-arrow-left"></i> Voltar à Listagem
						</a>
					</div>

					<form action="{{ route('teacher.update', $teacher->id) }}" method="POST" enctype="multipart/form-data">
						@csrf
						@method('PUT')
						<div class="akademi-card-body">
							{{-- Exibição de Erros de Validação --}}
							@if ($errors->any())
								<div class="alert alert-danger alert-dismissible fade show mb-4" style="border-radius: 8px;">
									<ul class="mb-0 ps-3">
										@foreach ($errors->all() as $error)
											<li>{{ $error }}</li>
										@endforeach
									</ul>
									<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
								</div>
							@endif

							<div class="row">
								{{-- Fotografia do Formador (Coluna Esquerda) --}}
								<div class="col-xl-3 col-lg-4 col-md-4 mb-4">
									<label class="field-label d-block">Fotografia do Formador</label>
									
									<div class="photo-upload-container">
										<div class="photo-preview-card mb-3">
											<img id="imagePreview" src="{{ $teacher->image ? asset('storage/' . $teacher->image) : '' }}" alt="Preview da Foto" 
												 style="width: 100%; height: 100%; object-fit: cover; {{ $teacher->image ? 'display: block;' : 'display: none;' }}">
											<svg id="defaultAvatarSvg" width="100%" height="100%" viewBox="0 0 160 180" fill="none" xmlns="http://www.w3.org/2000/svg" style="{{ $teacher->image ? 'display: none;' : 'display: block;' }}">
												<rect width="160" height="180" fill="#E2E8F0"/>
												<circle cx="80" cy="70" r="35" fill="#94A3B8"/>
												<path d="M25 160C25 125 50 115 80 115C110 115 135 125 135 160V180H25V160Z" fill="#94A3B8"/>
											</svg>
										</div>

										<div class="mb-1">
											<label for="image" class="file-input-wrapper">
												<span class="file-input-btn">Selecionar Ficheiro</span>
												<span id="fileNameDisplay" class="file-input-name">Nenhum ficheiro selecionado</span>
											</label>
											<input type="file" name="image" id="image" class="d-none" accept="image/*" onchange="previewTeacherImage(event)">
										</div>
										<small style="font-size: 11px; color: #94A3B8; display: block; margin-top: 4px;">Formatos: JPG, PNG, WEBP (Máx: 2MB)</small>
									</div>
								</div>

								{{-- Dados do Formador (Coluna Direita) --}}
								<div class="col-xl-9 col-lg-8 col-md-8">
									<div class="row">
										{{-- Nome Completo --}}
										<div class="col-md-6 mb-4">
											<label for="name" class="field-label">Nome Completo <span class="text-danger">*</span></label>
											<input type="text" name="name" id="name" class="form-control form-control-akademi" placeholder="Ex: Maria dos Santos" value="{{ old('name', $teacher->name) }}" required>
										</div>

										{{-- Número do BI --}}
										<div class="col-md-6 mb-4">
											<label for="number_of_identify" class="field-label">Número do BI <span class="text-danger">*</span></label>
											<input type="text" name="number_of_identify" id="number_of_identify" class="form-control form-control-akademi" placeholder="Ex: 000123456LA042" value="{{ old('number_of_identify', $teacher->number_of_identify) }}" required>
										</div>

										{{-- Email --}}
										<div class="col-md-6 mb-4">
											<label for="email" class="field-label">Email <span class="text-danger">*</span></label>
											<input type="email" name="email" id="email" class="form-control form-control-akademi" placeholder="candidato@gmail.com" value="{{ old('email', $teacher->email) }}" required>
										</div>

										{{-- Género --}}
										<div class="col-md-6 mb-4">
											<label for="gender" class="field-label">Género <span class="text-danger">*</span></label>
											<select name="gender" id="gender" class="form-control form-control-akademi" required>
												<option value="" disabled>Selecione o género...</option>
												<option value="Masculino" {{ old('gender', $teacher->gender) == 'Masculino' ? 'selected' : '' }}>Masculino</option>
												<option value="Feminino" {{ old('gender', $teacher->gender) == 'Feminino' ? 'selected' : '' }}>Feminino</option>
											</select>
										</div>

										{{-- Especialização --}}
										<div class="col-md-6 mb-4">
											<label for="specialization" class="field-label">Especialização <span class="text-danger">*</span></label>
											<input type="text" name="specialization" id="specialization" class="form-control form-control-akademi" placeholder="Ex: Engenharia de Software, Gestão" value="{{ old('specialization', $teacher->specialization) }}" required>
										</div>

										{{-- Número de Telefone --}}
										<div class="col-md-6 mb-4">
											<label for="phone" class="field-label">Número de Telefone <span class="text-danger">*</span></label>
											<input type="text" name="phone" id="phone" class="form-control form-control-akademi" placeholder="Ex: 923000000" value="{{ old('phone', $teacher->phone) }}" required>
										</div>
									</div>
								</div>
							</div>
						</div>

						{{-- Rodapé com Botões de Ação --}}
						<div class="akademi-card-footer">
							<a href="{{ route('teacher.index') }}" class="btn-cancel-akademi">Cancelar</a>
							<button type="submit" class="btn-save-akademi">
								<i class="fa fa-lock" style="font-size: 13px;"></i> Guardar Alterações
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
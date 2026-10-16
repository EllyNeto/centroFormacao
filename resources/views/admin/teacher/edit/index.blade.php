@extends('layout.main')

@section('title', 'Editar Formador')

@section('content')
{{-- Estrutura principal da página de edição do formador --}}
<div class="content-body">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xl-12">
				<div class="akademi-card">
					{{-- Cabeçalho do Formulário com Título e Botão de Voltar --}}
					<div class="akademi-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
						<div>
							<h3 class="akademi-title mb-1">Editar Formador: {{ $teacher->name }}</h3>
							<p class="akademi-subtitle mb-0">Atualize as informações do formador e guarde as alterações</p>
						</div>
						<a href="{{ route('teacher.index') }}" class="btn-orange-back">
							<i class="fa fa-arrow-left"></i> Voltar à Listagem
						</a>
					</div>

					{{-- Formulário de Edição com suporte para upload de nova imagem --}}
					<form action="{{ route('teacher.update', $teacher->id) }}" method="POST" enctype="multipart/form-data">
						@csrf
						@method('PUT')
						<div class="akademi-card-body">
							{{-- Exibição de Alertas de Erros de Validação --}}
							@if ($errors->any())
								<div class="alert alert-danger alert-dismissible fade show mb-4 teacher-form-alert">
									<h6 class="alert-heading font-w600"><i class="fa fa-exclamation-triangle me-2"></i>Por favor corrija os seguintes erros:</h6>
									<ul class="mb-0 ps-3">
										@foreach ($errors->all() as $error)
											<li>{{ $error }}</li>
										@endforeach
									</ul>
									<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
								</div>
							@endif

							<div class="row">
								{{-- Fotografia do Formador (Coluna Esquerda com pré-visualização) --}}
								<div class="col-xl-3 col-lg-4 col-md-4 mb-4">
									<label class="field-label d-block">Fotografia do Formador</label>
									
									<div class="photo-upload-container">
										<div class="photo-preview-card mb-3">
											<img id="imagePreview" src="{{ $teacher->image ? asset('storage/' . $teacher->image) : '' }}" alt="Preview da Foto" 
												 class="photo-preview-img-edit {{ $teacher->image ? '' : 'd-none' }}">
											<svg id="defaultAvatarSvg" width="100%" height="100%" viewBox="0 0 160 180" fill="none" xmlns="http://www.w3.org/2000/svg" class="{{ $teacher->image ? 'd-none' : '' }}">
												<rect width="160" height="180" fill="#E2E8F0"/>
												<circle cx="80" cy="70" r="35" fill="#94A3B8"/>
												<path d="M25 160C25 125 50 115 80 115C110 115 135 125 135 160V180H25V160Z" fill="#94A3B8"/>
											</svg>
										</div>

										<div class="mb-1">
											<label for="image" class="file-input-wrapper">
												<span class="file-input-btn">Alterar Ficheiro</span>
												<span id="fileNameDisplay" class="file-input-name">Nenhum ficheiro selecionado</span>
											</label>
											<input type="file" name="image" id="image" class="d-none" accept="image/*" onchange="previewTeacherImage(event)">
										</div>
										<small class="photo-helper-text">Formatos: JPG, PNG, WEBP (Máx: 4MB)</small>
									</div>
								</div>

								{{-- Dados do Formador (Coluna Direita) --}}
								<div class="col-xl-9 col-lg-8 col-md-8">
									<div class="row">
										{{-- Nome Completo --}}
										<div class="col-md-6 mb-4">
											<label for="name" class="field-label">Nome Completo <span class="text-danger">*</span></label>
											<input type="text" name="name" id="name" class="form-control form-control-akademi @error('name') is-invalid @enderror" placeholder="Ex: Maria dos Santos" value="{{ old('name', $teacher->name) }}" required>
											@error('name')
												<div class="invalid-feedback">{{ $message }}</div>
											@enderror
										</div>

										{{-- Número do BI --}}
										<div class="col-md-6 mb-4">
											<label for="number_of_identify" class="field-label">Número do BI <span class="text-danger">*</span></label>
											<input type="text" name="number_of_identify" id="number_of_identify" class="form-control form-control-akademi @error('number_of_identify') is-invalid @enderror" placeholder="Ex: 000123456LA042" value="{{ old('number_of_identify', $teacher->number_of_identify) }}" required>
											@error('number_of_identify')
												<div class="invalid-feedback">{{ $message }}</div>
											@enderror
										</div>

										{{-- Email --}}
										<div class="col-md-6 mb-4">
											<label for="email" class="field-label">Email <span class="text-danger">*</span></label>
											<input type="email" name="email" id="email" class="form-control form-control-akademi @error('email') is-invalid @enderror" placeholder="formador@exemplo.com" value="{{ old('email', $teacher->email) }}" required>
											@error('email')
												<div class="invalid-feedback">{{ $message }}</div>
											@enderror
										</div>

										{{-- Género --}}
										<div class="col-md-6 mb-4">
											<label for="gender" class="field-label">Género <span class="text-danger">*</span></label>
											<select name="gender" id="gender" class="form-control form-control-akademi @error('gender') is-invalid @enderror" required>
												<option value="" disabled>Selecione o género...</option>
												<option value="Masculino" {{ old('gender', $teacher->gender) == 'Masculino' ? 'selected' : '' }}>Masculino</option>
												<option value="Feminino" {{ old('gender', $teacher->gender) == 'Feminino' ? 'selected' : '' }}>Feminino</option>
											</select>
											@error('gender')
												<div class="invalid-feedback">{{ $message }}</div>
											@enderror
										</div>

										{{-- Especialização --}}
										<div class="col-md-6 mb-4">
											<label for="specialization" class="field-label">Especialização <span class="text-danger">*</span></label>
											<input type="text" name="specialization" id="specialization" class="form-control form-control-akademi @error('specialization') is-invalid @enderror" placeholder="Ex: Engenharia de Software, Contabilidade" value="{{ old('specialization', $teacher->specialization) }}" required>
											@error('specialization')
												<div class="invalid-feedback">{{ $message }}</div>
											@enderror
										</div>

										{{-- Número de Telefone --}}
										<div class="col-md-6 mb-4">
											<label for="phone" class="field-label">Número de Telefone <span class="text-danger">*</span></label>
											<input type="text" name="phone" id="phone" class="form-control form-control-akademi @error('phone') is-invalid @enderror" placeholder="Ex: 923000000" value="{{ old('phone', $teacher->phone) }}" required>
											@error('phone')
												<div class="invalid-feedback">{{ $message }}</div>
											@enderror
										</div>
									</div>
								</div>
							</div>
						</div>

						{{-- Rodapé com Botões de Ação --}}
						<div class="akademi-card-footer d-flex justify-content-end gap-2">
							<a href="{{ route('teacher.index') }}" class="btn btn-secondary">Cancelar</a>
							<button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Guardar Alterações</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection
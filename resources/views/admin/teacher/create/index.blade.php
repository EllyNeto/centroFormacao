@extends('layout.main')

@section('title', 'Adicionar Formador')

@section('content')
{{-- Estrutura principal da página de formulário --}}
<div class="content-body">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xl-12">
				<div class="akademi-card">
					{{-- Cabeçalho do Formulário com Título e Botão de Regresso --}}
					<div class="akademi-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
						<div>
							<h3 class="akademi-title mb-1">Adicionar Novo Formador</h3>
							<p class="akademi-subtitle mb-0">Preencha as informações pessoais e profissionais do formador</p>
						</div>
						<a href="{{ route('teacher.index') }}" class="btn-orange-back">
							<i class="fa fa-arrow-left"></i> Voltar à Listagem
						</a>
					</div>

					{{-- Formulário de Registo de Formador com Suporte para Envio de Ficheiros (enctype) --}}
					<form action="{{ route('teacher.store') }}" method="POST" enctype="multipart/form-data">
						@csrf
						<div class="akademi-card-body">
							{{-- Exibição de Alertas de Erro de Validação --}}
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
								{{-- Fotografia do Formador (Coluna Esquerda) --}}
								<div class="col-xl-3 col-lg-4 col-md-4 mb-4">
									<label class="field-label d-block">Fotografia do Formador</label>
									
									<div class="photo-upload-container">
										{{-- Caixa de pré-visualização da imagem --}}
										<div class="photo-preview-card mb-3">
											<img id="imagePreview" src="" alt="Preview da Foto" class="photo-preview-img">
											<svg id="defaultAvatarSvg" width="100%" height="100%" viewBox="0 0 160 180" fill="none" xmlns="http://www.w3.org/2000/svg">
												<rect width="160" height="180" fill="#E2E8F0"/>
												<circle cx="80" cy="70" r="35" fill="#94A3B8"/>
												<path d="M25 160C25 125 50 115 80 115C110 115 135 125 135 160V180H25V160Z" fill="#94A3B8"/>
											</svg>
										</div>

										{{-- Seletor de Ficheiro --}}
										<div class="mb-1">
											<label for="image" class="file-input-wrapper">
												<span class="file-input-btn">Selecionar Ficheiro</span>
												<span id="fileNameDisplay" class="file-input-name">Nenhum ficheiro selecionado</span>
											</label>
											<input type="file" name="image" id="image" class="d-none" accept="image/*" onchange="previewTeacherImage(event)">
										</div>
										<small class="photo-helper-text">Formatos: JPG, PNG, WEBP (Máx: 4MB)</small>
									</div>
								</div>

								{{-- Dados Pessoais e Profissionais do Formador (Coluna Direita) --}}
								<div class="col-xl-9 col-lg-8 col-md-8">
									<div class="row">
										{{-- Nome Completo --}}
										<div class="col-md-6 mb-4">
											<label for="name" class="field-label">Nome Completo <span class="text-danger">*</span></label>
											<input type="text" name="name" id="name" class="form-control form-control-akademi @error('name') is-invalid @enderror" placeholder="Ex: Maria dos Santos" value="{{ old('name') }}" required>
											@error('name')
												<div class="invalid-feedback">{{ $message }}</div>
											@enderror
										</div>

										{{-- Número do BI (Bilhete de Identidade) --}}
										<div class="col-md-6 mb-4">
											<label for="number_of_identify" class="field-label">Número do BI <span class="text-danger">*</span></label>
											<input type="text" name="number_of_identify" id="number_of_identify" class="form-control form-control-akademi @error('number_of_identify') is-invalid @enderror" placeholder="Ex: 000123456LA042" value="{{ old('number_of_identify') }}" required>
											@error('number_of_identify')
												<div class="invalid-feedback">{{ $message }}</div>
											@enderror
										</div>

										{{-- Email de Contacto --}}
										<div class="col-md-6 mb-4">
											<label for="email" class="field-label">Email <span class="text-danger">*</span></label>
											<input type="email" name="email" id="email" class="form-control form-control-akademi @error('email') is-invalid @enderror" placeholder="formador@exemplo.com" value="{{ old('email') }}" required>
											@error('email')
												<div class="invalid-feedback">{{ $message }}</div>
											@enderror
										</div>

										{{-- Género --}}
										<div class="col-md-6 mb-4">
											<label for="gender" class="field-label">Género <span class="text-danger">*</span></label>
											<select name="gender" id="gender" class="form-control form-control-akademi @error('gender') is-invalid @enderror" required>
												<option value="" disabled {{ old('gender') ? '' : 'selected' }}>Selecione o género...</option>
												<option value="Masculino" {{ old('gender') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
												<option value="Feminino" {{ old('gender') == 'Feminino' ? 'selected' : '' }}>Feminino</option>
											</select>
											@error('gender')
												<div class="invalid-feedback">{{ $message }}</div>
											@enderror
										</div>

										{{-- Especialização / Área Técnico-Científica --}}
										<div class="col-md-6 mb-4">
											<label for="specialization" class="field-label">Especialização <span class="text-danger">*</span></label>
											<input type="text" name="specialization" id="specialization" class="form-control form-control-akademi @error('specialization') is-invalid @enderror" placeholder="Ex: Engenharia de Software, Contabilidade" value="{{ old('specialization') }}" required>
											@error('specialization')
												<div class="invalid-feedback">{{ $message }}</div>
											@enderror
										</div>

										{{-- Número de Telefone --}}
										<div class="col-md-6 mb-4">
											<label for="phone" class="field-label">Número de Telefone <span class="text-danger">*</span></label>
											<input type="text" name="phone" id="phone" class="form-control form-control-akademi @error('phone') is-invalid @enderror" placeholder="Ex: 923000000" value="{{ old('phone') }}" required>
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
							<button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Guardar Formador</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection


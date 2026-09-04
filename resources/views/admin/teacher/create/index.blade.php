@extends('layouts.main')

@section('title', 'Adicionar Formadores')

@section('content')

<!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xl-12">
						<div class="card">
							<div class="card-header d-flex justify-content-between align-items-center">
								<h5 class="mb-0">Adicionar Novo Formador</h5>
								<a href="{{ route('teacher.index') }}" class="btn btn-secondary btn-sm">
									<i class="fa fa-arrow-left me-1"></i> Voltar à Listagem
								</a>
							</div>

							{{-- Formulário de submissão dos dados do formador via POST --}}
							<form action="{{ route('teacher.store') }}" method="POST" enctype="multipart/form-data">
								{{-- Diretiva de proteção contra CSRF no Laravel --}}
								@csrf

								<div class="card-body">
									{{-- Exibição de erros de validação se houver algum campo inválido --}}
									@if ($errors->any())
										<div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
											<h6 class="alert-heading font-w600 mb-1"><i class="fa fa-exclamation-triangle me-2"></i> Erro ao guardar formador:</h6>
											<ul class="mb-0 ps-3">
												@foreach ($errors->all() as $error)
													<li>{{ $error }}</li>
												@endforeach
											</ul>
											<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
										</div>
									@endif

									<div class="row">
										{{-- Coluna Esquerda: Fotografia do Formador --}}
										<div class="col-xl-3 col-lg-4">
											<label class="form-label text-primary">Fotografia do Formador</label>
											<div class="avatar-upload">
												<div class="avatar-preview mb-3">
													<div id="imagePreview" style="background-image: url('{{ asset('images/no-img-avatar.png') }}'); width: 130px; height: 130px; background-size: cover; background-position: center; border-radius: 12px; border: 2px solid #e2e8f0; margin: 0 auto;"></div>
												</div>
												<div class="change-btn mt-2 mb-lg-0 mb-3">
													{{-- Campo para seleção do ficheiro de foto com pré-visualização instantânea --}}
													<input type='file' class="form-control" name="image" id="imageUpload" accept="image/*" onchange="previewTeacherImage(this)">
													<small class="text-muted d-block mt-1">Formatos aceites: JPG, PNG, WEBP (Máx: 2MB)</small>
												</div>
											</div>
										</div>

										{{-- Coluna Direita: Informações Pessoais do Formador --}}
										<div class="col-xl-9 col-lg-8">
											<div class="row">
												<div class="col-xl-6 col-sm-6">
													{{-- Campo: Nome Completo --}}
													<div class="mb-3">
														<label for="name" class="form-label text-primary">Nome Completo <span class="text-danger">*</span></label>
														<input type="text" class="form-control" id="name" name="name" placeholder="Ex: Professor Manuel Santos" value="{{ old('name') }}" required>
													</div>

													{{-- Campo: Email --}}
													<div class="mb-3">
														<label for="email" class="form-label text-primary">E-mail <span class="text-danger">*</span></label>
														<input type="email" class="form-control" id="email" name="email" placeholder="formador@exemplo.com" value="{{ old('email') }}" required>
													</div>

													{{-- Campo: Número do BI --}}
													<div class="mb-3">
														<label for="identity_card_number" class="form-label text-primary">Número do BI</label>
														<input type="text" class="form-control" id="identity_card_number" name="identity_card_number" placeholder="Ex: 000987654LA031" value="{{ old('identity_card_number') }}">
													</div>
												</div>

												<div class="col-xl-6 col-sm-6">
													{{-- Campo: Número de Telefone --}}
													<div class="mb-3">
														<label for="phone" class="form-label text-primary">Telefone</label>
														<input type="text" class="form-control" id="phone" name="phone" placeholder="Ex: 924112233" value="{{ old('phone') }}">
													</div>

													{{-- Campo: Área de Especialização --}}
													<div class="mb-3">
														<label for="specialty" class="form-label text-primary">Área de Especialização / Disciplina</label>
														<input type="text" class="form-control" id="specialty" name="specialty" placeholder="Ex: Matemática, Programação Web..." value="{{ old('specialty') }}">
													</div>

													{{-- Campo: Estado (Ativo/Desativo) --}}
													<div class="mb-3">
														<label for="status" class="form-label text-primary">Estado</label>
														<select id="status" name="status" class="default-select wide form-control">
															<option value="1" selected>Activo</option>
															<option value="0">Desativo</option>
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								{{-- Rodapé do cartão com botões de ação --}}
								<div class="card-footer text-end">
									<a href="{{ route('teacher.index') }}" class="btn btn-danger light me-2">Cancelar</a>
									<button type="submit" class="btn btn-primary">Salvar Formador</button>
								</div>
							</form>
						</div>
					</div>
				</div>

{{-- Preservação do código estático original em comentário Blade --}}
{{--
<div class="card">
	<div class="card-header">
		<h5 class="mb-0">Personal Details</h5>
	</div>

				<div class="col-xl-12">
					<div class="card">
						<div class="card-header">
							<h5 class="mb-0">Education</h5>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-xl-6 col-sm-6">
									<div class="mb-3">
									  <label for="exampleFormControlInput9" class="form-label text-primary">University <span class="required">*</span></label>
									  <input type="text" class="form-control" id="exampleFormControlInput9" placeholder="University of Oxford">
									</div>
									<div class="mb-3">
									  <div class="mb-3">
										  
										  <label class="form-label text-primary">Start & End Date<span class="required">*</span></label>
											<div class="d-flex">
												<input type="text" class="form-control w-50" id="datepicker1">
												<input type="text" class="form-control w-50 ms-3" id="datepicker2">
											</div>
										</div>
									</div>
								</div>
								<div class="col-xl-6 col-sm-6">
									<div class="mb-3">
									  <label for="exampleFormControlInput14" class="form-label text-primary">Degree<span class="required">*</span></label>
									  <input type="text" class="form-control" id="exampleFormControlInput14" placeholder="B.Tech">
									</div>
									
									<div class="mb-3">
									  <label for="exampleFormControlInput13" class="form-label text-primary">City<span class="required">*</span></label>
									  <input type="number" class="form-control" id="exampleFormControlInput13" placeholder="USA">
									</div>
								</div>
							</div>
							<div class="float-end">
								<button class="btn btn-outline-primary me-3">Save as Draft</button>
								<button class="btn btn-primary" type="button">Save</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
--}}
		</div>
		
        <!--**********************************
            Content body end
        ***********************************-->

{{-- Script para pré-visualização da fotografia do formador --}}
@push('scripts')
<script>
	function previewTeacherImage(input) {
		if (input.files && input.files[0]) {
			var file = input.files[0];
			// Verificação do tamanho máximo do ficheiro (2MB = 2 * 1024 * 1024 bytes)
			if (file.size > 2 * 1024 * 1024) {
				alert('A fotografia selecionada é demasiado grande! O tamanho máximo permitido é de 2MB.');
				input.value = '';
				return;
			}
			var reader = new FileReader();
			reader.onload = function(e) {
				var preview = document.getElementById('imagePreview');
				if (preview) {
					preview.style.backgroundImage = 'url(' + e.target.result + ')';
				}
			}
			reader.readAsDataURL(file);
		}
	}
</script>
@endpush
@endsection
@extends('layouts.main')

@section('title', 'Adiconar novo estudante')

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
								<h5 class="mb-0">Detalhes do Estudante</h5>
								<a href="{{ route('student.index') }}" class="btn btn-secondary btn-sm">
									<i class="fa fa-arrow-left me-1"></i> Voltar à Listagem
								</a>
							</div>
							
							{{-- Formulário para envio dos dados do novo estudante via POST --}}
							<form action="{{ route('student.store') }}" method="POST" enctype="multipart/form-data">
								{{-- Diretiva CSRF obrigatória do Laravel --}}
								 @csrf
								 
								<div class="card-body">
									{{-- Exibição de erros de validação se houver algum campo inválido --}}
									@if ($errors->any())
										<div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
											<h6 class="alert-heading font-w600 mb-1"><i class="fa fa-exclamation-triangle me-2"></i> Erro ao guardar estudante:</h6>
											<ul class="mb-0 ps-3">
												@foreach ($errors->all() as $error)
													<li>{{ $error }}</li>
												@endforeach
											</ul>
											<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
										</div>
									@endif

									<div class="row">
										{{-- Coluna Esquerda: Fotografia do Aluno --}}
										<div class="col-xl-3 col-lg-4">
											<label class="form-label text-primary">Fotografia do Estudante</label>
											<div class="avatar-upload">
												<div class="avatar-preview mb-3">
													<div id="imagePreview" style="background-image: url('{{ asset('images/no-img-avatar.png') }}'); width: 130px; height: 130px; background-size: cover; background-position: center; border-radius: 12px; border: 2px solid #e2e8f0; margin: 0 auto;"> 			
													</div>
												</div>
												<div class="change-btn mt-2 mb-lg-0 mb-3">
													{{-- Campo do tipo File para seleção da imagem com pré-visualização instantânea --}}
													<input type='file' class="form-control" name="image" id="imageUpload" accept="image/*" onchange="previewStudentImage(this)">
													<small class="text-muted d-block mt-1">Formatos aceites: JPG, PNG, WEBP (Máx: 2MB)</small>
												</div>
											</div>	
										</div>
									
										{{-- Coluna Direita: Dados Pessoais do Estudante --}}
										<div class="col-xl-9 col-lg-8">
											<div class="row">
												<div class="col-xl-6 col-sm-6">
													{{-- Campo: Nome Completo --}}
													<div class="mb-3">
													  <label for="name" class="form-label text-primary">Nome Completo <span class="text-danger">*</span></label>
													  <input type="text" class="form-control" id="name" name="name" placeholder="Ex: James Lino" value="{{ old('name') }}" required>
													</div>
													
													{{-- Campo: Email --}}
													<div class="mb-3">
													  <label for="email" class="form-label text-primary">Email <span class="text-danger">*</span></label>
													  <input type="email" class="form-control" id="email" name="email" placeholder="exemplo@gmail.com" value="{{ old('email') }}" required>
													</div>
													
													{{-- Campo: Número do BI --}}
													<div class="mb-3">
													  <label for="identity_card_number" class="form-label text-primary">Número do BI <span class="text-danger">*</span></label>
													  <input type="text" class="form-control" id="identity_card_number" name="identity_card_number" placeholder="Ex: 000123456LA042" value="{{ old('identity_card_number') }}" required>
													</div>
												</div>
												
												<div class="col-xl-6 col-sm-6">
													{{-- Campo: Número de Telefone --}}
													<div class="mb-3">
													  <label for="phone" class="form-label text-primary">Número de Telefone <span class="text-danger">*</span></label>
													  <input type="text" class="form-control" id="phone" name="phone" placeholder="Ex: 923000000" value="{{ old('phone') }}" required>
													</div>
													
													{{-- Campo: Código do Estudante --}}
													<div class="mb-3">
													  <label for="code" class="form-label text-primary">Código do Estudante <span class="text-danger">*</span></label>
													  <input type="number" class="form-control" id="code" name="code" placeholder="Ex: 1001" value="{{ old('code') }}" required>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								
								{{-- Rodapé do cartão com os botões de ação --}}
								<div class="card-footer text-end">
									<a href="{{ route('student.index') }}" class="btn btn-danger light me-2">Cancelar</a>
									<button type="submit" class="btn btn-primary">Salvar Estudante</button>
								</div>
							</form>
						</div>
				</div>
				{{-- <div class="col-xl-12">
					<div class="card">
						<div class="card-header">
							<h5 class="mb-0">Parents Details</h5>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-xl-6 col-sm-6">
									<div class="mb-3">
									  <label for="exampleFormControlInput8" class="form-label text-primary">First Name<span class="required">*</span></label>
									  <input type="text" class="form-control" id="exampleFormControlInput8" placeholder="Mana">
									</div>
									<div class="mb-3">
									  <label for="exampleFormControlInput9" class="form-label text-primary">Email<span class="required">*</span></label>
									  <input type="email" class="form-control" id="exampleFormControlInput9" placeholder="hello@example.com">
									</div>
									<div class="mb-3">
									  <label for="exampleFormControlTextarea2" class="form-label text-primary">Address<span class="required">*</span></label>
									  <textarea class="form-control" id="exampleFormControlTextarea2" rows="6">
										 
									  </textarea>
									</div>
								</div>
								
								<div class="col-xl-6 col-sm-6">
									<div class="mb-3">
									  <label for="exampleFormControlInput10" class="form-label text-primary">Last Name<span class="required">*</span></label>
									  <input type="text" class="form-control" id="exampleFormControlInput10" placeholder="Wick">
									</div>
									<div class="mb-3">
									  <label for="exampleFormControlInput11" class="form-label text-primary">Phone Number<span class="required">*</span></label>
									  <input type="number" class="form-control" id="exampleFormControlInput11" placeholder="+123456789">
									</div>
									<label class="form-label text-primary">Payments<span class="required">*</span></label>
									<div class="d-flex align-items-center">
										<div class="form-check">
										  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
										  <label class="form-check-label font-w500" for="flexCheckDefault">
											Cash
										  </label>
										</div>
										<div class="form-check ms-3">
										  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault1">
										  <label class="form-check-label font-w500" for="flexCheckDefault1">
											Debits
										  </label>
										</div>
									
									</div>
								</div>
							</div> --}}
							{{-- <div class="">
								<button class="btn btn-outline-primary me-3">Save as Draft</button>
								<button class="btn btn-primary" type="button">Save</button>
							</div> --}}
						</div>
					</div>
				</div>
			</div>
		</div>
			{{-- <script>
		$(function () {
			  $("#datepicker").datepicker({ 
					autoclose: true, 
					todayHighlight: true
			  }).datepicker('update', new Date());
		
		});

	</script>
	
	 <script>
		function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#imagePreview').css('background-image', 'url('+e.target.result +')');
            $('#imagePreview').hide();
            $('#imagePreview').fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$("#imageUpload").change(function() {
    readURL(this);
});
	$('.remove-img').on('click', function() {
		var imageUrl = "images/no-img-avatar.png";
		$('.avatar-preview, #imagePreview').removeAttr('style');
		$('#imagePreview').css('background-image', 'url(' + imageUrl + ')');
	});



	</script> --}}
		
        <!--**********************************
            Content body end
        ***********************************-->

{{-- Script para pré-visualização instantânea da fotografia do estudante selecionada --}}
@push('scripts')
<script>
	function previewStudentImage(input) {
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
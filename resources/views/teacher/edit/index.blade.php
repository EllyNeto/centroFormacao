{{-- Extende o layout principal unificado da aplicação --}}
@extends('layouts.main.main')

{{-- Define o título dinâmico da página --}}
@section('title', 'Editar Formador')

{{-- Conteúdo principal da página de edição de formador --}}
@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Editar Formador: {{ $teacher->name }}</h4>
                        <a href="{{ route('teacher.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left me-1"></i> Voltar à Listagem
                        </a>
                    </div>

                    {{-- Formulário de submissão para atualizar os dados do formador enviando por PUT --}}
                    <form action="{{ route('teacher.update', $teacher->id) }}" method="POST" enctype="multipart/form-data">
                        {{-- Directiva CSRF para proteção de formulário no Laravel --}}
                        @csrf
                        {{-- Simulação do método HTTP PUT necessário para rotas de atualização --}}
                        @method('PUT')

                        <div class="card-body">
                            {{-- Exibição de erros de validação se houver algum erro --}}
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                    <h6 class="alert-heading font-w600 mb-1"><i class="fa fa-exclamation-triangle me-2"></i> Erro ao atualizar formador:</h6>
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
                                            @if($teacher->image)
                                                <div id="imagePreview" style="background-image: url('{{ asset('img/teachers/'.$teacher->image) }}'); width: 130px; height: 130px; background-size: cover; background-position: center; border-radius: 12px; border: 2px solid #e2e8f0; margin: 0 auto;"></div>
                                            @else
                                                <div id="imagePreview" style="background-image: url('{{ asset('images/avatar/8.jpg') }}'); width: 130px; height: 130px; background-size: cover; background-position: center; border-radius: 12px; border: 2px solid #e2e8f0; margin: 0 auto;"></div>
                                            @endif
                                        </div>
                                        <div class="change-btn mt-2 mb-lg-0 mb-3">
                                            {{-- Campo para carregar uma nova foto com pré-visualização instantânea --}}
                                            <input type='file' class="form-control" name="image" id="imageUpload" accept="image/*" onchange="previewTeacherImage(this)">
                                            <small class="text-muted d-block mt-1">Formatos aceites: JPG, PNG, WEBP (Máx: 2MB)</small>
                                        </div>
                                    </div>	
                                </div>

                                {{-- Coluna Direita: Formulário com os campos pré-preenchidos --}}
                                <div class="col-xl-9 col-lg-8">
                                    <div class="row">
                                        <div class="col-xl-6 col-sm-6">
                                            {{-- Campo: Nome Completo --}}
                                            <div class="mb-3">
                                                <label for="name" class="form-label text-primary">Nome Completo <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $teacher->name) }}" required>
                                            </div>

                                            {{-- Campo: Email --}}
                                            <div class="mb-3">
                                                <label for="email" class="form-label text-primary">E-mail <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $teacher->email) }}" required>
                                            </div>

                                            {{-- Campo: Número do BI --}}
                                            <div class="mb-3">
                                                <label for="identity_card_number" class="form-label text-primary">Número do BI</label>
                                                <input type="text" class="form-control" id="identity_card_number" name="identity_card_number" value="{{ old('identity_card_number', $teacher->identity_card_number) }}">
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-sm-6">
                                            {{-- Campo: Número de Telefone --}}
                                            <div class="mb-3">
                                                <label for="phone" class="form-label text-primary">Telefone</label>
                                                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $teacher->phone_number ?? $teacher->phone) }}">
                                            </div>

                                            {{-- Campo: Área de Especialização --}}
                                            <div class="mb-3">
                                                <label for="specialty" class="form-label text-primary">Área de Especialização / Disciplina</label>
                                                <input type="text" class="form-control" id="specialty" name="specialty" value="{{ old('specialty', $teacher->specialty) }}">
                                            </div>

                                            {{-- Campo: Estado (Ativo/Desativo) --}}
                                            <div class="mb-3">
                                                <label for="status" class="form-label text-primary">Estado</label>
                                                <select id="status" name="status" class="default-select wide form-control">
                                                    <option value="1" {{ old('status', $teacher->status) ? 'selected' : '' }}>Activo</option>
                                                    <option value="0" {{ !old('status', $teacher->status) ? 'selected' : '' }}>Desativo</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Rodapé do cartão com os botões de ação --}}
                        <div class="card-footer text-end">
                            <a href="{{ route('teacher.index') }}" class="btn btn-danger light me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Atualizar Formador</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script para pré-visualização da foto alterada do formador --}}
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

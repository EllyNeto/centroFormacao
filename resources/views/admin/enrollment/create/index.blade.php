@extends('layouts.main')

@section('title', 'Adicionar Nova Inscrição')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0">Adicionar Nova Inscrição de Candidato</h4>
                            <p class="m-0 subtitle">Preencha os dados para registar a inscrição no curso</p>
                        </div>
                        <a href="{{ route('enrollment.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left me-1"></i> Voltar à Listagem
                        </a>
                    </div>

                    <form action="{{ route('enrollment.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="card-body">
                            {{-- Exibição de erros de validação se houver algum campo inválido --}}
                            @if ($errors->any())
                                <div class="alert alert-danger alert-alt alert-dismissible fade show mb-4" role="alert">
                                    <div>
                                        <strong>Erro!</strong> Por favor verifique os seguintes problemas:
                                        <ul class="mb-0 mt-1 ps-3">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            
                            <div class="row">
                                {{-- Coluna Esquerda: Fotografia do Candidato --}}
                                <div class="col-xl-3 col-lg-4 text-center mb-4 mb-lg-0">
                                    <label class="form-label text-primary font-w600">Fotografia do Candidato</label>
                                    <div class="avatar-upload">
                                        <div class="avatar-preview mb-3">
                                            <div id="imagePreview" style="background-image: url('{{ asset('images/no-img-avatar.png') }}'); width: 140px; height: 140px; background-size: cover; background-position: center; border-radius: 12px; border: 2px solid #e2e8f0; margin: 0 auto;"> 			
                                            </div>
                                        </div>
                                        <div class="change-btn mt-2">
                                            <input type='file' class="form-control" name="image" id="imageUpload" accept="image/*" onchange="previewStudentImage(this)">
                                            <small class="text-muted d-block mt-1">Formatos: JPG, PNG, WEBP (Máx: 2MB)</small>
                                        </div>
                                    </div>	
                                </div>

                                {{-- Coluna Direita: Todos os campos organizados em grelha contínua --}}
                                <div class="col-xl-9 col-lg-8">
                                    <div class="row">
                                        {{-- Nome Completo --}}
                                        <div class="col-xl-6 col-sm-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label text-primary">Nome Completo <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="name" name="name" placeholder="Ex: Maria dos Santos" value="{{ old('name') }}" required>
                                            </div>
                                        </div>

                                        {{-- Número do BI --}}
                                        <div class="col-xl-6 col-sm-6">
                                            <div class="mb-3">
                                                <label for="identity_card_number" class="form-label text-primary">Número do BI <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="identity_card_number" name="identity_card_number" placeholder="Ex: 000123456LA042" value="{{ old('identity_card_number') }}" required>
                                            </div>
                                        </div>

                                        {{-- Email --}}
                                        <div class="col-xl-6 col-sm-6">
                                            <div class="mb-3">
                                                <label for="email" class="form-label text-primary">Email <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control" id="email" name="email" placeholder="candidato@gmail.com" value="{{ old('email') }}" required>
                                            </div>
                                        </div>

                                        {{-- Telefone --}}
                                        <div class="col-xl-6 col-sm-6">
                                            <div class="mb-3">
                                                <label for="phone" class="form-label text-primary">Número de Telefone <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="phone" name="phone" placeholder="Ex: 923000000" value="{{ old('phone') }}" required>
                                            </div>
                                        </div>

                                        {{-- Curso Pretendido (Lista Suspensa / Select Directo) --}}
                                        <div class="col-xl-12">
                                            <div class="mb-3">
                                                <label for="course_id" class="form-label text-primary">Curso Pretendido <span class="text-danger">*</span></label>
                                                <select id="course_id" name="course_id" class="default-select wide form-control" required>
                                                    <option value="">Selecione o Curso Pretendido...</option>
                                                    @foreach($courses as $course)
                                                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                                            {{ $course->name }} (Cód: {{ $course->code ?? $course->id }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        {{-- Data da Inscrição --}}
                                        <div class="col-xl-6 col-sm-6">
                                            <div class="mb-3">
                                                <label for="date" class="form-label text-primary">Data da Inscrição <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                                            </div>
                                        </div>

                                        {{-- Estado Inicial --}}
                                        <div class="col-xl-6 col-sm-6">
                                            <div class="mb-3">
                                                <label for="status" class="form-label text-primary">Estado Inicial <span class="text-danger">*</span></label>
                                                <select id="status" name="status" class="default-select wide form-control" required>
                                                    <option value="0" {{ old('status', '0') === '0' ? 'selected' : '' }}>Pendente / Aguarda Pagamento</option>
                                                    <option value="1" {{ old('status') === '1' ? 'selected' : '' }}>Ativa / Confirmada</option>
                                                </select>
                                                <small class="text-muted">O candidato só passará a formando ativo após o pagamento.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <a href="{{ route('enrollment.index') }}" class="btn btn-danger light me-2">Cancelar</a>
                            <button type="submit" name="action" value="save" class="btn btn-secondary light me-2">
                                <i class="fa fa-save me-1"></i> Guardar Apenas Inscrição
                            </button>
                            <button type="submit" name="action" value="save_and_pay" class="btn btn-primary">
                                <i class="fa fa-credit-card me-1"></i> Guardar e Ir para Pagamento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewStudentImage(input) {
        if (input.files && input.files[0]) {
            var file = input.files[0];
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
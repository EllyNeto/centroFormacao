{{-- Extende o layout principal da aplicação --}}
@extends('layouts.main')

{{-- Define o título dinâmico da página na barra do navegador --}}
@section('title', 'Detalhes da Inscrição')

{{-- Conteúdo principal da página de detalhes da inscrição --}}
@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    {{-- Cabeçalho do Cartão com ID da Inscrição, Data de Registo e Botões de Ação --}}
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0">Detalhes da Inscrição: #{{ $enrollment->id }}</h4>
                            <small class="text-muted">Data de Registo: {{ date('d/m/Y', strtotime($enrollment->date)) }}</small>
                        </div>
                        <div>
                            {{-- Botão para navegar para a página de edição --}}
                            <a href="{{ route('enrollment.edit', $enrollment->id) }}" class="btn btn-primary btn-sm me-1">
                                <i class="fa fa-pencil me-1"></i> Editar
                            </a>
                            {{-- Botão para retornar à listagem de inscrições --}}
                            <a href="{{ route('enrollment.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fa fa-arrow-left me-1"></i> Voltar à Listagem
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            {{-- Coluna 1: Exibição da Fotografia de Perfil do Candidato e Código de Estudante --}}
                            <div class="col-xl-3 col-md-4 text-center mb-4 mb-md-0">
                                @php
                                    // Obtém a imagem do candidato no storage ou utiliza a imagem padrão se não definida
                                    $studentImg = ($enrollment->student && $enrollment->student->image) 
                                        ? asset('storage/' . $enrollment->student->image) 
                                        : asset('images/no-img-avatar.png');
                                @endphp
                                <div class="avatar-preview mb-2">
                                    <div style="background-image: url('{{ $studentImg }}'); width: 140px; height: 140px; background-size: cover; background-position: center; border-radius: 12px; border: 2px solid #e2e8f0; margin: 0 auto;"></div>
                                </div>
                                <span class="badge badge-outline-primary mt-2">Código: {{ $enrollment->student->code ?? 'N/D' }}</span>
                            </div>

                            {{-- Coluna 2: Cartão com Dados Pessoais do Candidato (Nome, BI, E-mail, Telefone) --}}
                            <div class="col-xl-4 col-md-4">
                                <div class="p-3 mb-3 border rounded">
                                    <h6 class="text-primary font-w600 mb-3"><i class="fa fa-user me-2"></i>Informação do Candidato</h6>
                                    <p class="mb-2"><strong>Nome:</strong> {{ $enrollment->student->name ?? 'N/D' }}</p>
                                    <p class="mb-2"><strong>Código do Estudante:</strong> {{ $enrollment->student->code ?? 'N/D' }}</p>
                                    <p class="mb-2"><strong>Género:</strong> {{ $enrollment->student->gender ?? 'N/D' }}</p>
                                    <p class="mb-2"><strong>E-mail:</strong> {{ $enrollment->student->email ?? 'N/D' }}</p>
                                    <p class="mb-2"><strong>Nº do BI:</strong> {{ $enrollment->student->identity_card_number ?? 'N/D' }}</p>
                                    {{-- Exibição garantida do número de telefone através dos atributos 'phone_number' ou 'phone' --}}
                                    <p class="mb-2"><strong>Telefone:</strong> {{ $enrollment->student->phone_number ?? $enrollment->student->phone ?? 'N/D' }}</p>
                                </div>
                            </div>

                            {{-- Coluna 3: Cartão com Informações do Curso Inscrito e Estado da Inscrição --}}
                            <div class="col-xl-5 col-md-4">
                                <div class="p-3 mb-3 border rounded">
                                    <h6 class="text-primary font-w600 mb-3"><i class="fa fa-book me-2"></i>Informação do Curso e Estado</h6>
                                    <p class="mb-2"><strong>Curso Inscrito:</strong> {{ $enrollment->course->name ?? 'N/D' }}</p>
                                    <p class="mb-2"><strong>Duração :</strong> {{ $enrollment->course->duration ?? 'N/D' }}</p>
                                    <p class="mb-2"><strong>Estado da Inscrição:</strong> 
                                        @if($enrollment->status)
                                            <span class="badge badge-success light">Confirmada / Ativa</span>
                                        @else
                                            <span class="badge badge-warning light">Pendente </span>
                                        @endif
                                    </p>
                                    <p class="mb-2"><strong>Data de Registo:</strong> {{ date('d/m/Y', strtotime($enrollment->date)) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Rodapé do Cartão com botão de exclusão de inscrição --}}
                    <div class="card-footer text-end">
                        <form action="{{ route('enrollment.destroy', $enrollment->id) }}" method="POST" onsubmit="return confirm('Tem a certeza que deseja eliminar esta inscrição?');" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger light">
                                <i class="fa fa-trash me-1"></i> Eliminar Inscrição
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
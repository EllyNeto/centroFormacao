@extends('layouts.main')

@section('title', 'Detalhes da Inscrição')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0">Detalhes da Inscrição: #{{ $enrollment->id }}</h4>
                            <small class="text-muted">Data: {{ date('d/m/Y H:i', strtotime($enrollment->date)) }}</small>
                        </div>
                        <div>
                            <a href="{{ route('enrollment.edit', $enrollment->id) }}" class="btn btn-primary btn-sm me-1">
                                <i class="fa fa-pencil me-1"></i> Editar
                            </a>
                            <a href="{{ route('enrollment.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fa fa-arrow-left me-1"></i> Voltar à Listagem
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="p-3 mb-3 border rounded">
                                    <h6 class="text-primary font-w600 mb-3"><i class="fa fa-user me-2"></i>Informação do Estudante</h6>
                                    <p><strong>Nome:</strong> {{ $enrollment->student->name ?? 'N/D' }}</p>
                                    <p><strong>Código do Estudante:</strong> {{ $enrollment->student->code ?? 'N/D' }}</p>
                                    <p><strong>E-mail:</strong> {{ $enrollment->student->email ?? 'N/D' }}</p>
                                    <p><strong>Nº do BI:</strong> {{ $enrollment->student->identity_card_number ?? 'N/D' }}</p>
                                    <p><strong>Telefone:</strong> {{ $enrollment->student->phone_number ?? 'N/D' }}</p>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <div class="p-3 mb-3 border rounded">
                                    <h6 class="text-primary font-w600 mb-3"><i class="fa fa-book me-2"></i>Informação do Curso e Estado</h6>
                                    <p><strong>Curso Inscrito:</strong> {{ $enrollment->course->name ?? 'N/D' }}</p>
                                    <p><strong>Duração / Detalhes:</strong> {{ $enrollment->course->duration ?? 'N/D' }}</p>
                                    <p><strong>Estado:</strong> 
                                        @if($enrollment->status)
                                            <span class="badge badge-success light">Confirmada / Ativa</span>
                                        @else
                                            <span class="badge badge-warning light">Pendente</span>
                                        @endif
                                    </p>
                                    <p><strong>Data de Registo:</strong> {{ date('d/m/Y H:i', strtotime($enrollment->date)) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

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
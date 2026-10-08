@extends('layout.main')

@section('title', 'Detalhes da Candidatura')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center">
                        <div>
                            <h4 class="card-title mb-1">Candidatura: {{ $enrollment->name }}</h4>
                            <p class="m-0 subtitle text-muted">Informações detalhadas sobre o candidato e o estado da candidatura</p>
                        </div>
                        <div>
                            <a href="{{ route('enrollment.edit', $enrollment->id) }}" class="btn btn-warning btn-sm me-2">
                                <i class="fa fa-edit me-1"></i> Editar
                            </a>
                            <a href="{{ route('enrollment.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fa fa-arrow-left me-1"></i> Voltar
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="row">
                            {{-- Perfil do Candidato --}}
                            <div class="col-md-4 mb-4 text-center">
                                <div class="p-3 border rounded bg-light">
                                    <div class="mb-3 mx-auto" style="width: 150px; height: 170px; overflow: hidden; border-radius: 8px; border: 2px solid #cbd5e1; background: #ffffff;">
                                        @if($enrollment->image)
                                            <img src="{{ asset('storage/' . $enrollment->image) }}" alt="{{ $enrollment->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <svg width="100%" height="100%" viewBox="0 0 160 180" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect width="160" height="180" fill="#E2E8F0"/>
                                                <circle cx="80" cy="70" r="35" fill="#94A3B8"/>
                                                <path d="M25 160C25 125 50 115 80 115C110 115 135 125 135 160V180H25V160Z" fill="#94A3B8"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <h5 class="fw-bold text-dark mb-1">{{ $enrollment->name }}</h5>
                                    <p class="text-muted mb-2"><i class="fa fa-id-card me-1"></i> {{ $enrollment->number_of_identify }}</p>
                                    
                                    @php
                                        $badgeClass = 'badge-warning';
                                        if ($enrollment->status === 'Pago') $badgeClass = 'badge-info';
                                        elseif ($enrollment->status === 'Matriculado') $badgeClass = 'badge-success';
                                        elseif ($enrollment->status === 'Lista de Espera') $badgeClass = 'badge-primary';
                                        elseif ($enrollment->status === 'Cancelado') $badgeClass = 'badge-danger';
                                    @endphp
                                    <span class="badge light {{ $badgeClass }} fs-14 px-3 py-2">{{ $enrollment->status }}</span>
                                </div>
                            </div>

                            {{-- Informações Detalhadas --}}
                            <div class="col-md-8 mb-4">
                                <div class="p-3 border rounded bg-light mb-4">
                                    <h5 class="fw-bold mb-3 text-primary"><i class="fa fa-user me-2"></i>Dados Pessoais</h5>
                                    <p class="mb-2"><strong>Nome Completo:</strong> {{ $enrollment->name }}</p>
                                    <p class="mb-2"><strong>BI / Passaporte:</strong> {{ $enrollment->number_of_identify }}</p>
                                    <p class="mb-2"><strong>Email:</strong> {{ $enrollment->email }}</p>
                                    <p class="mb-2"><strong>Telefone:</strong> {{ $enrollment->phone }}</p>
                                </div>

                                <div class="p-3 border rounded bg-light">
                                    <h5 class="fw-bold mb-3 text-success"><i class="fa fa-book me-2"></i>Dados da Opção de Curso</h5>
                                    <p class="mb-2"><strong>Curso:</strong> {{ $enrollment->course->name ?? 'Não atribuído' }}</p>
                                    <p class="mb-2"><strong>Turno :</strong> <span class="badge light badge-info">{{ $enrollment->shift }}</span></p>
                                    <p class="mb-2"><strong>Duração do Curso:</strong> {{ $enrollment->course->duration ?? '-' }} horas</p>
                                    <p class="mb-2"><strong>Valor do Curso:</strong> {{ isset($enrollment->course->value) ? number_format($enrollment->course->value, 2, ',', '.') . ' Kz' : '-' }}</p>
                                    <p class="mb-0"><strong>Data da Candidatura:</strong> {{ $enrollment->created_at ? $enrollment->created_at->format('d/m/Y H:i') : '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
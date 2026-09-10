@extends('layouts.main')

@section('title', 'Detalhes do Pagamento')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    {{-- Cabeçalho do cartão com o título e botões de ação para voltar ou editar --}}
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Detalhes do Pagamento #PAG-{{ sprintf('%04d', $payment->id) }}</h4>
                        <div>
                            <a href="{{ route('payment.index') }}" class="btn btn-secondary btn-sm me-1">
                                <i class="fa fa-arrow-left me-1"></i> Voltar à Listagem
                            </a>
                            <a href="{{ route('payment.edit', $payment->id) }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-pencil me-1"></i> Editar Pagamento
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="row">
                            {{-- Coluna Principal: Destaque do Valor e Tipo de Pagamento --}}
                            <div class="col-xl-8 col-lg-7">
                                <div class="mb-4">
                                    <span class="badge badge-primary light mb-2">#ID {{ $payment->id }}</span>
                                    <div class="d-flex flex-wrap gap-2 align-items-center my-2">
                                        @foreach(array_filter(array_map('trim', explode(',', $payment->type_of_payment))) as $t)
                                            <span class="badge badge-primary light me-1 mb-1 fs-14 py-2 px-3">{{ $t }}</span>
                                        @endforeach
                                    </div>
                                    <h3 class="text-primary font-w600 mt-2">
                                        {{ number_format($payment->value, 2, ',', '.') }} <small class="fs-16">{{ $payment->currency }}</small>
                                    </h3>
                                </div>

                                <div class="mb-4">
                                    <h5 class="text-primary font-w600">Referência do Comprovativo</h5>
                                    <p class="fs-16 font-w500 text-dark">
                                        <i class="fa fa-barcode me-2 text-primary"></i> Nº {{ $payment->reference }}
                                    </p>
                                </div>
                            </div>

                            {{-- Coluna Lateral: Resumo de Dados com alta visibilidade e contraste --}}
                            <div class="col-xl-4 col-lg-5">
                                <div class="card border shadow-none" style="background-color: #f8fafc; border-radius: 12px;">
                                    <div class="card-body p-4">
                                        <h5 class="card-title text-primary font-w600 mb-3"><i class="fa fa-info-circle me-2"></i>Informações de Registo</h5>
                                        
                                        <ul class="list-group list-group-flush bg-transparent">
                                            <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 py-2 border-bottom">
                                                <span class="text-dark font-w500"><i class="fa fa-hashtag me-2 text-primary"></i> ID Pagamento:</span>
                                                <strong class="text-dark font-w600">#{{ $payment->id }}</strong>
                                            </li>
                                            <li class="list-group-item bg-transparent d-flex justify-content-between align-items-start px-0 py-2 border-bottom">
                                                <span class="text-dark font-w500"><i class="fa fa-list me-2 text-primary"></i> Tipo:</span>
                                                <div class="text-end ms-2">
                                                    @foreach(array_filter(array_map('trim', explode(',', $payment->type_of_payment))) as $t)
                                                        <span class="badge badge-primary light me-1 mb-1" style="font-size: 11px;">{{ $t }}</span>
                                                    @endforeach
                                                </div>
                                            </li>
                                            <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 py-2 border-bottom">
                                                <span class="text-dark font-w500"><i class="fa fa-money me-2 text-primary"></i> Valor:</span>
                                                <strong class="text-primary font-w700">{{ number_format($payment->value, 2, ',', '.') }} {{ $payment->currency }}</strong>
                                            </li>
                                            <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 py-2 border-bottom">
                                                <span class="text-dark font-w500"><i class="fa fa-barcode me-2 text-primary"></i> Referência:</span>
                                                <strong class="text-dark font-w600">#{{ $payment->reference }}</strong>
                                            </li>
                                            <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 py-2 border-bottom">
                                                <span class="text-dark font-w500"><i class="fa fa-check-circle me-2 text-primary"></i> Estado:</span>
                                                @if($payment->status)
                                                    <span class="badge badge-success light font-w600">Concluído / Pago</span>
                                                @else
                                                    <span class="badge badge-warning light font-w600">Pendente / Cancelado</span>
                                                @endif
                                            </li>
                                            <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 py-2 border-bottom">
                                                <span class="text-dark font-w500"><i class="fa fa-clock-o me-2 text-primary"></i> Data Pagamento:</span>
                                                <strong class="text-dark font-w600">{{ $payment->date ? \Carbon\Carbon::parse($payment->date)->format('d/m/Y H:i') : 'N/D' }}</strong>
                                            </li>
                                            <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 py-2">
                                                <span class="text-dark font-w500"><i class="fa fa-calendar me-2 text-primary"></i> Data de Registo:</span>
                                                <strong class="text-dark font-w600">{{ $payment->created_at ? $payment->created_at->format('d/m/Y H:i') : 'N/D' }}</strong>
                                            </li>
                                        </ul>

                                        {{-- Formulário de exclusão com confirmação JS --}}
                                        <div class="mt-4 pt-2 d-grid gap-2">
                                            <form action="{{ route('payment.destroy', $payment->id) }}" method="POST" onsubmit="return confirm('Tem a certeza que deseja eliminar este pagamento?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger w-100">
                                                    <i class="fa fa-trash me-1"></i> Eliminar Pagamento
                                                </button>
                                            </form>
                                        </div>
                                    </div>
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
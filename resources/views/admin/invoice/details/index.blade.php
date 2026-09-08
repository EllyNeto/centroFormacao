@extends('layouts.main')

@section('title', 'Detalhes da Fatura')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0">Fatura: #FAT-{{ sprintf('%04d', $invoice->id) }}</h4>
                            <small class="text-muted">Data de Emissão: {{ date('d/m/Y H:i', strtotime($invoice->created_at)) }}</small>
                        </div>
                        <div>
                            <a href="{{ route('invoice.edit', $invoice->id) }}" class="btn btn-primary btn-sm me-1">
                                <i class="fa fa-pencil me-1"></i> Editar
                            </a>
                            <a href="{{ route('invoice.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fa fa-arrow-left me-1"></i> Voltar à Listagem
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-xl-6 col-md-6">
                                <div class="p-3 border rounded">
                                    <h6 class="text-primary font-w600 mb-3"><i class="fa fa-file-text-o me-2"></i>Informação da Inscrição e Curso</h6>
                                    <p><strong>Identificador Inscrição:</strong> #INS-{{ sprintf('%04d', $invoice->enrollment_id) }}</p>
                                    <p><strong>Estudante:</strong> {{ $invoice->enrollment->student->name ?? 'N/D' }}</p>
                                    <p><strong>Código Estudante:</strong> {{ $invoice->enrollment->student->code ?? 'N/D' }}</p>
                                    <p><strong>Curso Associado:</strong> {{ $invoice->course->name ?? 'N/D' }}</p>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="p-3 border rounded">
                                    <h6 class="text-primary font-w600 mb-3"><i class="fa fa-money me-2"></i>Valores, Troco e Pendências</h6>
                                    <p><strong>Valor por Pagar:</strong> <span class="font-w600">{{ number_format($invoice->amount_to_pay, 2, ',', '.') }} Kz</span></p>
                                    <p><strong>Valor Pago:</strong> <span class="text-success font-w600">{{ number_format($invoice->amount_paid, 2, ',', '.') }} Kz</span></p>
                                    
                                    @php
                                        $diff = $invoice->amount_paid - $invoice->amount_to_pay;
                                    @endphp

                                    @if($diff >= 0)
                                        <p><strong>Troco Devolvido:</strong> <span class="badge badge-success light">{{ number_format($diff, 2, ',', '.') }} Kz</span></p>
                                        <p><strong>Estado Financeiro:</strong> <span class="badge badge-success light">Pago na Totalidade</span></p>
                                    @else
                                        <p><strong>Valor em Falta:</strong> <span class="badge badge-danger light">{{ number_format(abs($diff), 2, ',', '.') }} Kz</span></p>
                                        <p><strong>Estado Financeiro:</strong> <span class="badge badge-warning light">Pagamento Parcial / Pendente</span></p>
                                    @endif

                                    <p><strong>Registo / Emolumento:</strong> {{ $invoice->payment->type_of_payment ?? 'N/D' }} ({{ $invoice->payment->payment_method ?? 'Numerário' }})</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <form action="{{ route('invoice.destroy', $invoice->id) }}" method="POST" onsubmit="return confirm('Tem a certeza que deseja eliminar esta fatura?');" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger light">
                                <i class="fa fa-trash me-1"></i> Eliminar Fatura
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

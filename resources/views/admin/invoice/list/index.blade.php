@extends('layouts.main')

@section('title', 'Lista de Faturas')

@section('content')
<div class="content-body">
    @if(session('success'))
        <div class="alert alert-success alert-alt alert-dismissible fade show mb-4 me-4 ms-4" role="alert">
            <div><strong>Sucesso!</strong> {{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card" id="accordion-one">
                    <div class="card-header flex-wrap px-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">Gestão de Faturas</h4>
                            <p class="m-0 subtitle">Lista de todas as faturas emitidas e pagamentos associados</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <a href="{{ route('invoice.create') }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-plus me-1"></i> Emitir Nova Fatura
                            </a>
                        </div>
                    </div>

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="Preview" role="tabpanel">
                            <div class="card-body p-3">
                                <div class="table-responsive">
                                    <table id="example" class="table-responsive-lg table display dataTablesCard student-tab profile-tab dataTable no-footer w-100" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>#ID Fatura</th>
                                                <th>#Inscrição</th>
                                                <th>Curso</th>
                                                <th>Valor por Pagar</th>
                                                <th>Valor Pago</th>
                                                <th>Troco</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($invoices as $invoiceItem)
                                                <tr>
                                                    <td><strong>#FAT-{{ sprintf('%04d', $invoiceItem->id) }}</strong></td>
                                                    <td>
                                                        <a href="{{ route('enrollment.show', $invoiceItem->enrollment_id) }}" class="text-primary font-w600">
                                                            #INS-{{ $invoiceItem->enrollment_id }}
                                                        </a>
                                                        @if(isset($invoiceItem->enrollment->student))
                                                            <small class="d-block text-muted">{{ $invoiceItem->enrollment->student->name }}</small>
                                                        @endif
                                                    </td>
                                                    <td>{{ $invoiceItem->course->name ?? 'N/D' }}</td>
                                                    <td><strong>{{ number_format($invoiceItem->amount_to_pay, 2, ',', '.') }} Kz</strong></td>
                                                    <td><span class="text-success font-w600">{{ number_format($invoiceItem->amount_paid, 2, ',', '.') }} Kz</span></td>
                                                    <td>
                                                        @php
                                                            $diff = $invoiceItem->amount_paid - $invoiceItem->amount_to_pay;
                                                        @endphp
                                                        @if($diff > 0)
                                                            <span class="badge badge-success light" title="Troco devolvido">Troco: {{ number_format($diff, 2, ',', '.') }} Kz</span>
                                                        @elseif($diff < 0)
                                                            <span class="badge badge-danger light" title="Valor em falta">Falta: {{ number_format(abs($diff), 2, ',', '.') }} Kz</span>
                                                        @else
                                                            <span class="badge badge-light text-muted">0,00 Kz (Sem Troco)</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="d-flex justify-content-center align-items-center">
                                                            <a href="{{ route('invoice.show', $invoiceItem->id) }}" class="btn btn-info shadow btn-xs sharp me-1" title="Ver Detalhes">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                            <a href="{{ route('invoice.edit', $invoiceItem->id) }}" class="btn btn-primary shadow btn-xs sharp me-1" title="Editar Fatura">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                            <form action="{{ route('invoice.destroy', $invoiceItem->id) }}" method="POST" onsubmit="return confirm('Tem a certeza que deseja eliminar esta fatura?');" style="display: inline-block;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger shadow btn-xs sharp" title="Eliminar Fatura">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-4 text-muted">
                                                        Nenhuma fatura emitida na base de dados.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
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

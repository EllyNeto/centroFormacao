@extends('layout.main')

@section('title', 'Editar Pagamento — ' . $payment->reference)

@section('content')
{{-- Estrutura principal da página de edição de pagamento --}}
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    {{-- Cabeçalho --}}
                    <div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center border-bottom">
                        <div>
                            <h4 class="card-title mb-0 text-dark">
                                @if($payment->isConfirmed())
                                    Anular Pagamento: {{ $payment->reference }}
                                @else
                                    Editar Pagamento: {{ $payment->reference }}
                                @endif
                            </h4>
                            <p class="m-0 subtitle text-muted">
                                @if($payment->isConfirmed())
                                    Pagamentos confirmados só podem ser anulados. Para corrigir, anule e registe um novo.
                                @else
                                    Atualização de dados financeiros, método de pagamento e estado
                                @endif
                            </p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('payment.show', $payment->id) }}" class="btn btn-info btn-sm">
                                <i class="fa fa-file-text-o me-1"></i> Ver Recibo
                            </a>
                            <a href="{{ route('payment.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fa fa-arrow-left me-1"></i> Voltar à Lista
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        {{-- Alertas de Validação --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show mb-4">
                                <h6 class="alert-heading font-w600 text-danger mb-2">
                                    <i class="fa fa-exclamation-triangle me-2"></i>Por favor, corrija os seguintes erros:
                                </h6>
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- Informação do candidato (sempre visível) --}}
                        <div class="p-3 border rounded bg-light mb-4">
                            <div class="d-flex align-items-center gap-3">
                                @if($payment->enrollment && $payment->enrollment->image)
                                    <img src="{{ asset('storage/' . $payment->enrollment->image) }}"
                                         class="candidate-avatar-preview"
                                         alt="Foto">
                                @else
                                    <div class="candidate-avatar-preview-placeholder">
                                        <i class="fa fa-user"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-1 font-w700 text-dark">{{ $payment->enrollment ? $payment->enrollment->name : '-' }}</h6>
                                    <small class="text-muted">
                                        BI: {{ $payment->enrollment ? $payment->enrollment->number_of_identify : '-' }}
                                        &bull; Tel: {{ $payment->enrollment ? $payment->enrollment->phone : '-' }}
                                    </small>
                                    <br>
                                    <small class="text-muted">
                                        Curso: <strong>{{ $payment->enrollment && $payment->enrollment->course ? $payment->enrollment->course->name : '-' }}</strong>
                                        &bull; Ciclo: <strong>{{ $payment->cycle }}</strong>
                                        &bull; Ref: <strong>{{ $payment->reference }}</strong>
                                        &bull; Valor: <strong>{{ $payment->formattedValue() }}</strong>
                                    </small>
                                </div>
                            </div>
                        </div>

                        @if($payment->isConfirmed())
                            {{-- =========================================================
                                 FORMULÁRIO SIMPLIFICADO: SÓ ANULAÇÃO (R8)
                                 Pagamento Confirmado não pode ser editado, apenas anulado.
                                 ========================================================= --}}
                            <div class="alert alert-warning mb-4">
                                <i class="fa fa-lock me-2"></i>
                                <strong>Pagamento Confirmado:</strong> Não é possível editar os dados. Pode apenas <strong>anular</strong> este pagamento (com motivo obrigatório). Para corrigir um erro, anule e registe um novo pagamento.
                            </div>

                            <form action="{{ route('payment.update', $payment->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="cancellation_reason" class="form-label font-w600 text-dark">
                                            Motivo da Anulação <span class="text-danger">*</span>
                                        </label>
                                        <textarea name="cancellation_reason"
                                                  id="cancellation_reason"
                                                  rows="4"
                                                  class="form-control @error('cancellation_reason') is-invalid @enderror"
                                                  placeholder="Descreva o motivo da anulação deste pagamento..."
                                                  required>{{ old('cancellation_reason') }}</textarea>
                                        @error('cancellation_reason')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                                    <a href="{{ route('payment.show', $payment->id) }}" class="btn btn-secondary">Cancelar</a>
                                    <button type="submit" class="btn btn-danger" id="btnSubmitPaymentEdit">
                                        <i class="fa fa-ban me-1"></i> Confirmar Anulação
                                    </button>
                                </div>
                            </form>

                        @else
                            {{-- =========================================================
                                 FORMULÁRIO DE EDIÇÃO COMPLETO (Pendente)
                                 Permite alterar forma de pagamento, referência, data e estado.
                                 Valor e inscrição são fixos.
                                 ========================================================= --}}
                            <form action="{{ route('payment.update', $payment->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row g-4">
                                    <div class="col-lg-6">
                                        <div class="p-3 border rounded bg-light">
                                            <h5 class="font-w600 text-dark mb-3 border-bottom pb-2">
                                                <i class="fa fa-credit-card me-2 text-primary"></i>Dados do Pagamento
                                            </h5>

                                            {{-- Forma de Pagamento --}}
                                            <div class="mb-3">
                                                <label for="type_of_payment" class="form-label font-w600 text-dark">
                                                    Forma de Pagamento <span class="text-danger">*</span>
                                                </label>
                                                <select name="type_of_payment"
                                                        id="type_of_payment"
                                                        class="form-control default-select wide @error('type_of_payment') is-invalid @enderror"
                                                        required>
                                                    <option value="Transferência" {{ old('type_of_payment', $payment->type_of_payment) == 'Transferência' ? 'selected' : '' }}>
                                                        Transferência Bancária (IBAN)
                                                    </option>
                                                    <option value="Multicaixa" {{ old('type_of_payment', $payment->type_of_payment) == 'Multicaixa' ? 'selected' : '' }}>
                                                        Multicaixa (TPA / Express)
                                                    </option>
                                                    <option value="Numerário" {{ old('type_of_payment', $payment->type_of_payment) == 'Numerário' ? 'selected' : '' }}>
                                                        Numerário (Em Dinheiro)
                                                    </option>
                                                </select>
                                                @error('type_of_payment')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Montante (readonly — fixo) --}}
                                            <div class="mb-3">
                                                <label class="form-label font-w600 text-dark">Montante (Kz)</label>
                                                <div class="input-group">
                                                    <input type="text"
                                                           class="form-control bg-light font-w600"
                                                           value="{{ $payment->formattedValue() }}"
                                                           readonly>
                                                    <span class="input-group-text">AOA / Kz</span>
                                                </div>
                                                <small class="text-muted">O montante não pode ser alterado. Anule e registe um novo pagamento para corrigir.</small>
                                            </div>

                                            {{-- Referência do Recibo --}}
                                            <div class="mb-3">
                                                <label for="reference" class="form-label font-w600 text-dark">
                                                    Referência / Nº de Recibo <span class="text-danger">*</span>
                                                </label>
                                                <input type="text"
                                                       name="reference"
                                                       id="reference"
                                                       class="form-control @error('reference') is-invalid @enderror"
                                                       value="{{ old('reference', $payment->reference) }}"
                                                       required>
                                                @error('reference')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Data e Hora --}}
                                            <div class="mb-3">
                                                <label for="date" class="form-label font-w600 text-dark">
                                                    Data e Hora da Operação <span class="text-danger">*</span>
                                                </label>
                                                <input type="datetime-local"
                                                       name="date"
                                                       id="date"
                                                       class="form-control @error('date') is-invalid @enderror"
                                                       value="{{ old('date', $payment->date ? $payment->date->format('Y-m-d\TH:i') : '') }}"
                                                       required>
                                                @error('date')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Estado --}}
                                            <div class="mb-3">
                                                <label for="status" class="form-label font-w600 text-dark">
                                                    Estado da Transação <span class="text-danger">*</span>
                                                </label>
                                                <select name="status"
                                                        id="status"
                                                        class="form-control default-select wide @error('status') is-invalid @enderror"
                                                        required>
                                                    <option value="Pendente" {{ old('status', $payment->status) == 'Pendente' ? 'selected' : '' }}>
                                                        Pendente
                                                    </option>
                                                    <option value="Confirmado" {{ old('status', $payment->status) == 'Confirmado' ? 'selected' : '' }}>
                                                        Confirmado
                                                    </option>
                                                    <option value="Anulado" {{ old('status', $payment->status) == 'Anulado' ? 'selected' : '' }}>
                                                        Anulado
                                                    </option>
                                                </select>
                                                @error('status')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Motivo da anulação (visível ao selecionar Anulado) --}}
                                            <div class="mb-3" id="cancelReasonBlock" style="display:none;">
                                                <label for="cancellation_reason" class="form-label font-w600 text-dark">
                                                    Motivo da Anulação <span class="text-danger">*</span>
                                                </label>
                                                <textarea name="cancellation_reason"
                                                          id="cancellation_reason"
                                                          rows="3"
                                                          class="form-control @error('cancellation_reason') is-invalid @enderror"
                                                          placeholder="Descreva o motivo da anulação...">{{ old('cancellation_reason') }}</textarea>
                                                @error('cancellation_reason')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                                    <a href="{{ route('payment.show', $payment->id) }}" class="btn btn-secondary">Cancelar</a>
                                    <button type="submit" class="btn btn-primary" id="btnSubmitPaymentEdit">
                                        <i class="fa fa-save me-1"></i> Guardar Alterações
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
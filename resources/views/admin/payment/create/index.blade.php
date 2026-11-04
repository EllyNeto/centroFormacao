@extends('layout.main')

@section('title', 'Registar Pagamento')

@section('content')
{{-- Estrutura principal da página de registo de pagamento --}}
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    {{-- Cabeçalho do Cartão com Título e Ação de Retorno --}}
                    <div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center border-bottom">
                        <div>
                            <h4 class="card-title mb-0 text-dark">Registar Novo Pagamento</h4>
                            <p class="m-0 subtitle text-muted">Efetue o registo da liquidação de propina associada à candidatura do formando</p>
                        </div>
                        <a href="{{ route('payment.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left me-1"></i> Voltar à Lista
                        </a>
                    </div>

                    <div class="card-body p-4">
                        {{-- Exibição de Alertas de Validação --}}
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

                        {{-- Alerta de erro de regra de negócio (ex: pagamento duplicado — R6) --}}
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show mb-4">
                                <i class="fa fa-ban me-2"></i><strong>Operação não permitida:</strong> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('payment.store') }}" method="POST">
                            @csrf

                            <div class="row g-4">
                                {{-- Secção 1: Seleção do Candidato / Inscrição --}}
                                <div class="col-lg-6">
                                    <div class="p-3 border rounded bg-light mb-3">
                                        <h5 class="font-w600 text-dark mb-3 border-bottom pb-2">
                                            <i class="fa fa-user me-2 text-primary"></i>1. Seleção do Formando
                                        </h5>

                                        <div class="mb-3">
                                            <label for="enrollment_id" class="form-label font-w600 text-dark">
                                               Inscrição <span class="text-danger">*</span>
                                            </label>
                                            <select name="enrollment_id"
                                                    id="enrollment_id"
                                                    class="form-control select2 @error('enrollment_id') is-invalid @enderror"
                                                    required>
                                                <option value="">Selecione o candidato...</option>
                                                @foreach($enrollments as $enrollment)
                                                    @php
                                                        $isSelected   = (old('enrollment_id', $selectedEnrollment ? $selectedEnrollment->id : null) == $enrollment->id);
                                                        $coursePrice  = $enrollment->course ? (float)$enrollment->course->value : 0;
                                                        $paid         = $enrollment->totalPaid();
                                                        $balance      = $enrollment->remainingBalance();
                                                        $isRepeat     = $enrollment->canRepeat();
                                                    @endphp
                                                    <option value="{{ $enrollment->id }}"
                                                            data-name="{{ $enrollment->name }}"
                                                            data-bi="{{ $enrollment->number_of_identify }}"
                                                            data-phone="{{ $enrollment->phone }}"
                                                            data-course="{{ $enrollment->course ? $enrollment->course->name : 'N/D' }}"
                                                            data-course-value="{{ $coursePrice }}"
                                                            data-paid="{{ $paid }}"
                                                            data-balance="{{ $isRepeat ? $coursePrice : $balance }}"
                                                            data-is-repeat="{{ $isRepeat ? '1' : '0' }}"
                                                            data-image="{{ $enrollment->image ? asset('storage/' . $enrollment->image) : '' }}"
                                                            {{ $isSelected ? 'selected' : '' }}>
                                                        {{ $enrollment->formattedCode() }} - {{ $enrollment->name }} ({{ $enrollment->course ? $enrollment->course->name : 'Sem Curso' }}){{ $isRepeat ? ' — REPETIÇÃO' : '' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('enrollment_id')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Caixa Dinâmica de Pré-visualização do Candidato Selecionado --}}
                                        <div id="candidatePreviewBox" class="candidate-preview-box">
                                            <div id="candidatePreviewPlaceholder" class="text-center py-4 text-muted">
                                                <i class="fa fa-info-circle fa-2x mb-2 d-block text-secondary"></i>
                                                <span class="fs-7">Selecione um candidato acima para visualizar os dados financeiros e do curso.</span>
                                            </div>

                                            <div id="candidatePreviewContent" class="d-none">
                                                <div class="d-flex align-items-center mb-3">
                                                    <img id="previewCandidateAvatar" 
                                                         src="" 
                                                         class="candidate-avatar-preview me-3 d-none" 
                                                         alt="Foto">
                                                    <div id="previewCandidateAvatarIcon" class="candidate-avatar-preview-placeholder me-3">
                                                        <i class="fa fa-user"></i>
                                                    </div>
                                                    <div>
                                                        <h6 id="previewCandidateName" class="mb-1 font-w700 text-dark">-</h6>
                                                        <small class="text-muted">
                                                            BI: <span id="previewCandidateBi">-</span> &bull; Tel: <span id="previewCandidatePhone">-</span>
                                                        </small>
                                                    </div>
                                                </div>

                                                <div class="row g-2 pt-2 border-top">
                                                    <div class="col-6">
                                                        <small class="text-muted d-block fs-8 text-uppercase font-w600">Curso Pretendido</small>
                                                        <strong id="previewCourseName" class="text-dark fs-7 d-block text-truncate">-</strong>
                                                    </div>
                                                    <div class="col-6 text-end">
                                                        <small class="text-muted d-block fs-8 text-uppercase font-w600">Preço da Formação</small>
                                                        <strong id="previewCoursePrice" class="text-primary fs-7 d-block">-</strong>
                                                    </div>
                                                    <div class="col-6">
                                                        <small class="text-muted d-block fs-8 text-uppercase font-w600">Já Liquidado</small>
                                                        <span id="previewTotalPaid" class="text-success font-w600 fs-7 d-block">-</span>
                                                    </div>
                                                    <div class="col-6 text-end">
                                                        <small class="text-muted d-block fs-8 text-uppercase font-w600">Saldo em Dívida</small>
                                                        <span id="previewBalance" class="text-danger font-w700 fs-7 d-block">-</span>
                                                    </div>
                                                </div>

                                                {{-- Aviso de Repetição de Curso --}}
                                                <div id="repeatCourseAlert" class="alert alert-warning mt-3 mb-0 py-2 d-none">
                                                    <i class="fa fa-refresh me-1"></i>
                                                    <strong>Repetição de curso:</strong> O ciclo anterior foi concluído. Este pagamento iniciará um novo ciclo nesta inscrição.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Secção 2: Dados Financeiros da Transação --}}
                                <div class="col-lg-6">
                                    <div class="p-3 border rounded bg-light mb-3">
                                        <h5 class="font-w600 text-dark mb-3 border-bottom pb-2">
                                            <i class="fa fa-credit-card me-2 text-primary"></i>2. Detalhes do Pagamento
                                        </h5>

                                        {{-- Tipo de Pagamento --}}
                                        <div class="mb-3">
                                            <label for="type_of_payment" class="form-label font-w600 text-dark">
                                                Forma de Pagamento <span class="text-danger">*</span>
                                            </label>
                                            <select name="type_of_payment"
                                                    id="type_of_payment"
                                                    class="form-control default-select wide @error('type_of_payment') is-invalid @enderror"
                                                    required>
                                                <option value="Transferência" {{ old('type_of_payment') == 'Transferência' ? 'selected' : '' }}>
                                                    Transferência Bancária (IBAN)
                                                </option>
                                                <option value="Multicaixa" {{ old('type_of_payment', 'Multicaixa') == 'Multicaixa' ? 'selected' : '' }}>
                                                    Multicaixa (TPA / Express)
                                                </option>
                                                <option value="Numerário" {{ old('type_of_payment') == 'Numerário' ? 'selected' : '' }}>
                                                    Numerário (Em Dinheiro)
                                                </option>
                                            </select>
                                            @error('type_of_payment')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Montante a Liquidar — fixado ao preço do curso (R5), não editável --}}
                                        <div class="mb-3">
                                            <label for="value_display" class="form-label font-w600 text-dark">
                                                Montante a Liquidar (Kz)
                                            </label>
                                            <input type="hidden"
                                                   name="value"
                                                   id="payment_value"
                                                   value="{{ $selectedEnrollment ? ($selectedEnrollment->canRepeat() ? (float)$selectedEnrollment->course->value : $selectedEnrollment->remainingBalance()) : 0 }}">
                                            <input type="hidden" name="currency" value="AOA">
                                            <div class="input-group">
                                                <input type="text"
                                                       id="value_display"
                                                       class="form-control bg-light font-w600"
                                                       value="{{ $selectedEnrollment ? number_format($selectedEnrollment->canRepeat() ? (float)$selectedEnrollment->course->value : $selectedEnrollment->remainingBalance(), 2, ',', '.') : '—' }}"
                                                       readonly>
                                                <span class="input-group-text font-w600">AOA / Kz</span>
                                            </div>
                                            <small class="text-muted">O valor é fixado automaticamente ao preço do curso.</small>
                                        </div>

                                        {{-- Referência do Recibo / Comprovativo --}}
                                        <div class="mb-3">
                                            <label for="reference" class="form-label font-w600 text-dark">
                                                Referência / Nº de Recibo <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group payment-reference-input-group">
                                                <input type="text" 
                                                       name="reference" 
                                                       id="reference" 
                                                       class="form-control @error('reference') is-invalid @enderror" 
                                                       placeholder="Ex: REC-2026-0001 ou Nº Transação Bancária" 
                                                       value="{{ old('reference', $suggestedReference) }}" 
                                                       required>
                                                <button type="button" 
                                                        id="btnGenerateReference" 
                                                        class="btn btn-outline-secondary" 
                                                        title="Gerar nova referência aleatória">
                                                    <i class="fa fa-refresh me-1"></i> Gerar Nova
                                                </button>
                                            </div>
                                            <small class="text-muted fs-8">Insira o código do talão bancário ou mantenha a referência gerada pelo centro.</small>
                                            @error('reference')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Data e Hora da Operação --}}
                                        <div class="mb-3">
                                            <label for="date" class="form-label font-w600 text-dark">
                                                Data e Hora da Operação <span class="text-danger">*</span>
                                            </label>
                                            <input type="datetime-local" 
                                                   name="date" 
                                                   id="date" 
                                                   class="form-control @error('date') is-invalid @enderror" 
                                                   value="{{ old('date', now()->format('Y-m-d\TH:i')) }}" 
                                                   required>
                                            @error('date')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Estado do Pagamento --}}
                                        <div class="mb-3">
                                            <label for="status" class="form-label font-w600 text-dark">
                                                Estado da Transação <span class="text-danger">*</span>
                                            </label>
                                            <select name="status" 
                                                    id="status" 
                                                    class="form-control default-select wide @error('status') is-invalid @enderror" 
                                                    required>
                                                <option value="Confirmado" {{ old('status', 'Confirmado') == 'Confirmado' ? 'selected' : '' }}>
                                                    Confirmado
                                                </option>
                                                <option value="Pendente" {{ old('status') == 'Pendente' ? 'selected' : '' }}>
                                                    Pendente
                                                </option>
                                            </select>
                                            {{-- <small class="text-muted fs-8">
                                                Ao manter 'Confirmado', a candidatura do formando passará imediatamente ao estado 'Pago'.
                                            </small> --}}
                                            @error('status')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Botões de Submissão do Formulário --}}
                            <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                                <a href="{{ route('payment.index') }}" class="btn btn-secondary">
                                    Cancelar
                                </a>
                                <button type="submit" id="btnSubmitPayment" class="btn btn-primary">
                                    <i class="fa fa-check me-1"></i> Confirmar e Registar Pagamento
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
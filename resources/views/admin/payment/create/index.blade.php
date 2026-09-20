{{-- Extende o layout principal da aplicação --}}
@extends('layouts.main')

{{-- Define o título dinâmico da página no navegador --}}
@section('title', 'Adicionar Novo Pagamento')

{{-- Conteúdo principal da página de criação de pagamento --}}
@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    {{-- Cabeçalho do cartão com o título da ação e o botão para regressar à listagem --}}
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0">Adicionar Novo Pagamento</h4>
                            <p class="m-0 subtitle">Associe o pagamento a um formando, selecione os emolumentos e defina os valores</p>
                        </div>
                        <a href="{{ route('payment.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left me-1"></i> Voltar à Listagem
                        </a>
                    </div>
                    
                    {{-- Formulário de submissão para guardar um novo pagamento --}}
                    <form action="{{ route('payment.store') }}" method="POST">
                        {{-- Diretiva CSRF obrigatória do Laravel para validação de segurança do formulário --}}
                        @csrf
                        
                        {{-- Se o pagamento estiver associado a uma inscrição vinda de parâmetro de URL --}}
                        @if(isset($selectedEnrollment) && $selectedEnrollment)
                            <input type="hidden" name="enrollment_id" value="{{ $selectedEnrollment->id }}">
                            <div class="alert alert-info alert-alt alert-dismissible fade show mb-4 me-4 ms-4 mt-3" role="alert">
                                <i class="fa fa-info-circle me-2"></i>
                                <strong>Inscrição Associada:</strong> #INS-{{ sprintf('%04d', $selectedEnrollment->id) }} — <strong>{{ $selectedEnrollment->student->name ?? 'Candidato' }}</strong> (Curso: {{ $selectedEnrollment->course->name ?? 'N/D' }})
                            </div>
                        @elseif(isset($selectedEnrollmentId) && $selectedEnrollmentId)
                            <input type="hidden" name="enrollment_id" value="{{ $selectedEnrollmentId }}">
                        @endif

                        <div class="card-body">
                            {{-- Exibição do painel de erros de validação caso algum campo não cumpra as regras --}}
                            @if ($errors->any())
                                <div class="alert alert-danger alert-alt alert-dismissible fade show mb-4" role="alert">
                                    <div>
                                        <strong>Erro!</strong> Por favor corrija os erros no formulário:
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
                                {{-- Coluna Esquerda: Associação de Formando (Datalist), Emolumentos e Valores --}}
                                <div class="col-xl-6 col-sm-6">

                                    {{-- Campo: Associação a um Formando através do Select2 com Pesquisa ao Digitar --}}
                                    <div class="mb-3">
                                        <label for="student_id" class="form-label text-primary font-w600">Formando <span class="text-danger">*</span></label>
                                        @php
                                            $preselectedStudent = null;
                                            if (isset($selectedEnrollment) && $selectedEnrollment->student) {
                                                $preselectedStudent = $selectedEnrollment->student;
                                            }
                                            $studentIdVal = old('student_id', $preselectedStudent ? $preselectedStudent->id : '');
                                        @endphp
                                        <select id="student_id" name="student_id" class="form-control select2-student" required>
                                            <option value="">Pesquise e selecione o formando...</option>
                                            @foreach($students as $student)
                                                <option value="{{ $student->id }}" data-balance="{{ $student->balance ?? 0 }}" {{ $studentIdVal == $student->id ? 'selected' : '' }}>
                                                    {{ $student->name }} (Cód: {{ $student->code ?? $student->id }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted d-block mt-1">Selecione ou digite o nome/código do formando a quem este pagamento pertence.</small>
                                    </div>

                                    {{-- Cartão Dinâmico de Exibição do Saldo Acumulado do Formando --}}
                                    <div id="student_balance_card" class="card border border-primary p-3 mb-3 shadow-none {{ $preselectedStudent && $preselectedStudent->balance > 0 ? '' : 'd-none' }}" style="background-color: #f0f7ff; border-radius: 12px;">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                                            <div class="d-flex align-items-center">
                                                <i class="fa fa-wallet text-primary me-2 fs-16"></i>
                                                <strong class="text-primary font-w600 fs-14">Saldo de Crédito Disponível:</strong>
                                            </div>
                                            <span id="current_balance_text" class="badge badge-success light font-w700 fs-14 py-2 px-3" style="border-radius: 8px;">
                                                {{ number_format($preselectedStudent->balance ?? 0, 2, ',', '.') }} Kz
                                            </span>
                                        </div>
                                        <div class="border-top pt-2 mt-1">
                                            <small class="text-muted d-block" style="font-size: 12px; line-height: 1.4;">
                                                O formando pode utilizar este crédito acumulado em futuros emolumentos ou gerar novo saldo ao pagar a mais.
                                            </small>
                                        </div>
                                    </div>

                                    {{-- Campo: Seleção Múltipla de Emolumentos Adequados ao Centro de Formação --}}
                                    <div class="mb-3">
                                        <label class="form-label text-primary font-w600">Selecione os Emolumentos a Pagar <span class="text-danger">*</span></label>
                                        <div class="card p-3 border shadow-none mb-2" style="background-color: #f8fafc; border-radius: 10px;">
                                            <div class="row">
                                                {{-- Emolumento: Inscrição --}}
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input emolumento-check" type="checkbox" value="Inscrição" data-price="15000.00" id="emol_1">
                                                        <label class="form-check-label font-w500" for="emol_1">
                                                            Inscrição — <strong>15.000,00 Kz</strong>
                                                        </label>
                                                    </div>
                                                </div>

                                                {{-- Emolumento: Valor do Curso --}}
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input emolumento-check" type="checkbox" value="Valor do Curso" data-price="35000.00" id="emol_2">
                                                        <label class="form-check-label font-w500" for="emol_2">
                                                            Valor do Curso — <strong>35.000,00 Kz</strong>
                                                        </label>
                                                    </div>
                                                </div>

                                                {{-- Emolumento: Cartão do Formando --}}
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input emolumento-check" type="checkbox" value="Cartão do Formando" data-price="3000.00" id="emol_3">
                                                        <label class="form-check-label font-w500" for="emol_3">
                                                            Cartão do Formando — <strong>3.000,00 Kz</strong>
                                                        </label>
                                                    </div>
                                                </div>

                                                {{-- Emolumento: Certificado --}}
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input emolumento-check" type="checkbox" value="Certificado" data-price="10000.00" id="emol_4">
                                                        <label class="form-check-label font-w500" for="emol_4">
                                                            Certificado — <strong>10.000,00 Kz</strong>
                                                        </label>
                                                    </div>
                                                </div>

                                                {{-- Emolumento: Exame de Recurso --}}
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input emolumento-check" type="checkbox" value="Exame de Recurso" data-price="8000.00" id="emol_5">
                                                        <label class="form-check-label font-w500" for="emol_5">
                                                            Exame de Recurso — <strong>8.000,00 Kz</strong>
                                                        </label>
                                                    </div>
                                                </div>

                                                {{-- Emolumento: Outro Emolumento --}}
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input emolumento-check" type="checkbox" value="Outro Emolumento" data-price="0.00" id="emol_6">
                                                        <label class="form-check-label font-w500" for="emol_6">
                                                            Outro Emolumento
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Contentor Opcional para Valor do Outro Emolumento --}}
                                            <div id="outro_emolumento_container" class="mt-2 pt-2 border-top d-none">
                                                <label for="custom_emolument_price" class="form-label text-primary font-w600 fs-13 mb-1">Valor do Outro Emolumento (Kz):</label>
                                                <input type="text" id="custom_emolument_price" class="form-control form-control-sm currency-input" placeholder="0,00" value="0,00">
                                                <small class="text-muted d-block mt-1">Este valor será somado automaticamente ao valor total dos emolumentos.</small>
                                            </div>
                                        </div>
                                        <input type="hidden" name="type_of_payment" id="type_of_payment" value="{{ old('type_of_payment') }}" required>
                                        <input type="hidden" name="used_balance" id="used_balance" value="{{ old('used_balance', '0.00') }}">
                                        <input type="hidden" name="add_to_balance" id="add_to_balance" value="{{ old('add_to_balance', '0.00') }}">
                                        <small class="text-muted d-block">Pode selecionar um ou mais emolumentos em simultâneo.</small>
                                    </div>

                                    {{-- Campo: Valor Total a Pagar / Cobrado --}}
                                    <div class="mb-3">
                                        <label for="value" class="form-label text-primary font-w600">Valor Total a Cobrar (Kz) <span class="text-danger">*</span></label>
                                        <input type="text" id="value" name="value" class="form-control currency-input font-w600" value="{{ old('value', '0.00') }}" placeholder="0,00" required>
                                        <small class="text-muted">Calculado automaticamente com base nos emolumentos selecionados e abatimento de saldo (editável).</small>
                                    </div>

                                    {{-- Campo: Forma de Pagamento (Select) --}}
                                    <div class="mb-3">
                                        <label for="payment_method" class="form-label text-primary font-w600">Forma de Pagamento <span class="text-danger">*</span></label>
                                        <select id="payment_method" name="payment_method" class="form-control" required>
                                            <option value="Numerário" {{ old('payment_method', 'Numerário') == 'Numerário' ? 'selected' : '' }}>Numerário</option>
                                            <option value="Cartão" {{ old('payment_method') == 'Cartão' ? 'selected' : '' }}>Cartão / TPA</option>
                                            <option value="Transferência" {{ old('payment_method') == 'Transferência' ? 'selected' : '' }}>Transferência Bancária</option>
                                        </select>
                                    </div>

                                    {{-- Campo: Moeda --}}
                                    <div class="mb-3">
                                        <label for="currency" class="form-label text-primary font-w600">Moeda <span class="text-danger">*</span></label>
                                        <select id="currency" name="currency" class="form-control" required>
                                            <option value="AOA" {{ old('currency', 'AOA') == 'AOA' ? 'selected' : '' }}>AOA - Kwanza (Kz)</option>
                                            <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD - Dólar ($)</option>
                                            <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR - Euro (€)</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Coluna Direita: Referência Automática, Estado Padrão e Data do Pagamento --}}
                                <div class="col-xl-6 col-sm-6">
                                    {{-- Campo: Número de Referência (Gerado Automaticamente) --}}
                                    @php
                                        $autoReference = old('reference', rand(10000000, 99999999));
                                    @endphp
                                    <div class="mb-3">
                                        <label for="reference_display" class="form-label text-primary font-w600">Número de Referência (Gerado Automaticamente)</label>
                                        <input type="text" id="reference_display" class="form-control font-w600 text-dark" value="{{ $autoReference }}" style="background-color: #e9ecef !important; cursor: not-allowed; border: 1px solid #cbd5e1;" readonly disabled>
                                        <input type="hidden" name="reference" value="{{ $autoReference }}">
                                        <small class="text-muted">Gerado automaticamente pelo sistema (não alterável).</small>
                                    </div>

                                    {{-- Campo: Estado do Pagamento (Predefinido como Concluído / Pago) --}}
                                    <div class="mb-3">
                                        <label class="form-label text-primary font-w600 d-block">Estado do Pagamento</label>
                                        <div class="mt-1">
                                            <span class="badge badge-success light fs-14 py-2 px-3"><i class="fa fa-check-circle me-1"></i> Concluído / Pago </span>
                                        </div>
                                        <input type="hidden" name="status" value="1">
                                        <small class="text-muted d-block mt-1">Ao registar o pagamento, a inscrição do formando é automaticamente confirmada.</small>
                                    </div>

                                    {{-- Campo: Data e Hora do Pagamento --}}
                                    <div class="mb-3">
                                        <label for="date" class="form-label text-primary font-w600">Data e Hora do Pagamento <span class="text-danger">*</span></label>
                                        <input type="datetime-local" id="date" name="date" class="form-control" value="{{ old('date', date('Y-m-d\TH:i')) }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Rodapé do cartão com os botões de ação para Salvar Pagamento --}}
                        <div class="card-footer text-end">
                            <a href="{{ route('payment.index') }}" class="btn btn-danger light me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-arrow-right me-1"></i> Salvar Pagamento e Continuar para a Fatura
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Injeção do script de apoio às interações de pagamentos e saldos --}}
@push('scripts')
<script src="{{ asset('js/payment-form.js') }}"></script>
@endpush
@endsection
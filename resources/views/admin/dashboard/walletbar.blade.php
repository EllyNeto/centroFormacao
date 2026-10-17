<!--**********************************
    Barra Lateral - Painel de Actividade Recente
***********************************-->
<div class="wallet-bar wow fadeInRight dlab-scroll" id="wallet-bar" data-wow-delay="0.7s">
    <div class="row">

        {{-- 1. Formandos Recentes --}}
        <div class="col-xl-12">
            <div class="card bg-transparent mb-3">
                <div class="card-header border-0 px-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="heading mb-0">Formandos Recentes</h2>
                        <span class="fs-13 text-muted">Total: <span class="font-w600 text-dark">{{ $totalStudents }}</span> matriculados</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('enrollment.create') }}" class="add icon-box bg-primary me-2" title="Registar Nova Inscrição">
                            <i class="fa fa-plus text-white"></i>
                        </a>
                        <a href="javascript:void(0);" class="wallet-bar-close btn btn-danger btn-xs sharp rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; padding: 0;" title="Fechar Barra">
                            <i class="fa fa-times text-white"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body height300 dlab-scroll loadmore-content recent-activity-wrapper p-3 pt-2" id="RecentActivityContent">
                    @forelse ($recentStudents as $student)
                        <div class="d-flex align-items-center student mb-3 pb-2 border-bottom">
                            <span class="dz-media me-3">
                                @if (!empty($student->image))
                                    <img src="{{ asset('storage/' . $student->image) }}" alt="{{ $student->name }}" width="45" height="45" class="avatar rounded-circle">
                                @else
                                    <div class="avatar avatar-md rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 45px; height: 45px;">
                                        {{ strtoupper(substr($student->name, 0, 2)) }}
                                    </div>
                                @endif
                            </span>
                            <div class="user-info">
                                <h6 class="name mb-0">
                                    <a href="{{ route('student.show', $student->id) }}" class="text-dark font-w600">{{ $student->name }}</a>
                                </h6>
                                <span class="fs-12 text-muted text-wrap">
                                    {{ $student->room->course->name ?? 'Curso N/A' }}
                                    @if(!empty($student->room->name))
                                        • {{ $student->room->name }}
                                    @endif
                                </span>
                            </div>
                            <div class="ms-auto">
                                <span class="badge badge-xs light badge-primary font-w600">{{ $student->code }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <i class="material-symbols-outlined fs-36 d-block mb-1 text-primary opacity-50">school</i>
                            <span class="fs-13">Nenhum formando matriculado até ao momento.</span>
                        </div>
                    @endforelse
                </div>
                <div class="card-footer text-center border-0 pt-0 px-3 pb-0">
                    <a href="{{ route('student.index') }}" class="btn btn-block btn-primary light btn-rounded">
                        Ver Todos os Formandos
                    </a>
                </div>
            </div>
        </div>

        {{-- 2. Resumo Financeiro --}}
        <div class="col-xl-12">
            <div class="card bg-transparent mb-3">
                <div class="card-header border-0 px-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="heading mb-0">Resumo Financeiro</h2>
                        <span class="fs-13 text-muted">Receitas e pendências financeiras</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row text-center">
                        <div class="col-6">
                            <div>
                                <h3 class="text-success">Receita Confirmada</h3>
                                <p class="display-4 font-w600 mb-0">
                                    <span class="fs-5">+{{ number_format($totalRevenue, 0, ',', '.') }}</span> Kz
                                </p>
                                <p class="text-muted fs-13 mb-0">Total liquidado</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div>
                                <h3 class="text-warning">Receita Pendente</h3>
                                <p class="display-4 font-w600 mb-0">
                                    <span class="fs-5">+{{ number_format($pendingRevenue, 0, ',', '.') }}</span> Kz
                                </p>
                                <p class="text-muted fs-13 mb-0">Aguardar confirmação</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Últimos Pagamentos Confirmados --}}
        <div class="col-xl-12">
            <div class="card massage bg-transparent mb-3">
                <div class="card-header border-0 px-3 py-0">
                    <div>
                        <h2 class="heading mb-0">Últimos Pagamentos</h2>
                        <span class="fs-13 text-muted">Receitas confirmadas recentemente</span>
                    </div>
                </div>
                <div class="card-body height300 dlab-scroll p-0 px-3 mt-2" id="messageContent">
                    @forelse ($recentPayments as $payment)
                        <div class="d-flex align-items-center student border-bottom py-2">
                            <div class="icon-box icon-box-sm bg-success text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                <i class="fa fa-check fs-14"></i>
                            </div>
                            <div class="user-info flex-grow-1">
                                <h6 class="name mb-0 font-w600 text-dark">
                                    {{ $payment->enrollment->name ?? 'Formando' }}
                                </h6>
                                <span class="fs-12 text-muted">
                                    Ref: {{ $payment->reference }}
                                </span>
                            </div>
                            <div class="text-end ms-2">
                                <span class="fs-13 font-w700 text-success d-block">
                                    +{{ number_format($payment->value, 0, ',', '.') }} Kz
                                </span>
                                <small class="fs-11 text-muted">
                                    {{ $payment->date ? $payment->date->format('d/m/Y') : $payment->created_at->format('d/m/Y') }}
                                </small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <i class="material-symbols-outlined fs-36 d-block mb-1 text-success opacity-50">payments</i>
                            <span class="fs-13">Nenhum pagamento registado até ao momento.</span>
                        </div>
                    @endforelse
                </div>
                <div class="card-footer text-center border-0 px-3 pb-0 pt-2">
                    <a href="{{ route('payment.index') }}" class="btn btn-block btn-primary light btn-rounded">
                        Ver Todos os Pagamentos
                    </a>
                </div>
            </div>
        </div>

        {{-- 4. Resumo Operacional de Vagas e Lotação --}}
        <div class="col-xl-12">
            <div class="card tags bg-transparent mb-0">
                <div class="card-header border-0 px-3 py-0">
                    <div>
                        <h2 class="heading mb-0">Ocupação do Centro</h2>
                        <span class="fs-13 text-muted">Capacidade e infraestrutura</span>
                    </div>
                </div>
                <div class="card-body p-3 py-2">
                    <div class="card-body-inner d-flex align-items-center mb-2 p-2 bg-white rounded shadow-sm">
                        <div class="icon-box icon-box-sm bg-primary text-white me-3 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa fa-folder-open fs-13"></i>
                        </div>
                        <div class="user-info">
                            <h6 class="name mb-0 font-w600 text-dark">{{ $openRooms }} Turmas Abertas</h6>
                            <span class="fs-12 text-muted">Disponíveis para candidaturas</span>
                        </div>
                    </div>

                    <div class="card-body-inner d-flex align-items-center mb-2 p-2 bg-white rounded shadow-sm">
                        <div class="icon-box icon-box-sm bg-info text-white me-3 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa fa-users fs-13"></i>
                        </div>
                        <div class="user-info">
                            <h6 class="name mb-0 font-w600 text-dark">{{ $totalCapacity }} Vagas no Centro</h6>
                            <span class="fs-12 text-muted">Capacidade física instalada</span>
                        </div>
                    </div>

                    <div class="card-body-inner d-flex align-items-center mb-2 p-2 bg-white rounded shadow-sm">
                        <div class="icon-box icon-box-sm bg-success text-white me-3 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa fa-door-open fs-13"></i>
                        </div>
                        <div class="user-info">
                            <h6 class="name mb-0 font-w600 text-dark">{{ $totalClassrooms }} Salas Físicas</h6>
                            <span class="fs-12 text-muted">{{ $activeClassrooms }} salas em utilização</span>
                        </div>
                    </div>
                </div>
                <div class="card-footer pt-0 border-0 px-3 pb-3">
                    <a href="{{ route('room.index') }}" class="btn btn-block btn-primary light btn-rounded">
                        Gerir Turmas
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
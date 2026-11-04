<!--**********************************
    Corpo do Conteúdo - Painel Principal
***********************************-->
<div class="content-body">
    <div class="container-fluid">

        {{-- 1. Cartões de Indicadores Principais (KPIs) --}}
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body pb-xl-4 pb-sm-3 pb-0">	
                        <div class="row">

                            {{-- Card 1: Formandos Oficiais --}}
                            <div class="col-xl-3 col-6">
                                <div class="content-box">
                                    <div class="icon-box icon-box-xl std-data">
                                        <svg width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white">
                                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                                            <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                                        </svg>
                                    </div>
                                    <div class="chart-num">
                                        <p class="mb-1 text-muted fs-14">Formandos</p>
                                        <h2 class="font-w700 mb-0">{{ number_format($totalStudents, 0, ',', '.') }}</h2>
                                        <small class="text-success fs-12 font-w500">
                                            <i class="fa fa-check-circle me-1"></i>{{ $enrolledStudents }} matriculados
                                        </small>
                                    </div>
                                </div>
                            </div>

                            {{-- Card 2: Formadores (Docentes) --}}
                            <div class="col-xl-3 col-6">
                                <div class="content-box">
                                    <div class="teach-data icon-box icon-box-xl">
                                        <svg width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                            <circle cx="9" cy="7" r="4"/>
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                        </svg>
                                    </div>
                                    <div class="chart-num">
                                        <p class="mb-1 text-muted fs-14">Formadores</p>
                                        <h2 class="font-w700 mb-0">{{ number_format($totalTeachers, 0, ',', '.') }}</h2>
                                        <small class="text-info fs-12 font-w500">
                                            <i class="fa fa-chalkboard-teacher me-1"></i>{{ $activeTeachers }} com turmas activas
                                        </small>
                                    </div>
                                </div>
                            </div>

                            {{-- Card 3: Cursos e Turmas --}}
                            <div class="col-xl-3 col-6">
                                <div class="content-box">
                                    <div class="event-data icon-box icon-box-xl">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white">
                                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                                        </svg>	
                                    </div>
                                    <div class="chart-num">
                                        <p class="mb-1 text-muted fs-14">Cursos Activos</p>
                                        <h2 class="font-w700 mb-0">{{ number_format($activeCourses, 0, ',', '.') }} <small class="fs-14 text-muted fw-normal">/ {{ $totalCourses }}</small></h2>
                                        <small class="text-primary fs-12 font-w500">
                                            <i class="fa fa-users me-1"></i>{{ $openRooms }} turmas abertas
                                        </small>
                                    </div>
                                </div>
                            </div>

                            {{-- Card 4: Faturação Arrecadada --}}
                            <div class="col-xl-3 col-6">
                                <div class="content-box">
                                    <div class="food-data icon-box icon-box-xl">
                                        <svg width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white">
                                            <line x1="12" y1="1" x2="12" y2="23"/>
                                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                                        </svg>
                                    </div>
                                    <div class="chart-num">
                                        <p class="mb-1 text-muted fs-14">Arrecadação Global</p>
                                        <h2 class="font-w700 mb-0 fs-20">{{ number_format($totalRevenue, 2, ',', '.') }} <small class="fs-12 text-muted fw-bold">Kz</small></h2>
                                        <small class="text-warning fs-12 font-w500">
                                            <i class="fa fa-clock me-1"></i>Pendente: {{ number_format($pendingRevenue, 2, ',', '.') }} Kz
                                        </small>
                                    </div>
                                </div>
                            </div>

                        </div>	
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Banner de Mini-Indicadores Operacionais --}}
        <div class="row mb-4">
            <div class="col-xl-3 col-sm-6 mb-3 mb-xl-0">
                <div class="card h-100 shadow-sm border-0" style="border-radius: 12px; background: rgba(79, 70, 229, 0.08);">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted fs-13 font-w500 d-block">Inscrições Registadas</span>
                            <h4 class="font-w700 mb-0 mt-1 text-primary">{{ $totalEnrollments }}</h4>
                            <small class="text-muted">{{ $pendingEnrollments }} aguardam pagamento</small>
                        </div>
                        <span class="badge badge-primary light font-w600">Inscrições</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-3 mb-xl-0">
                <div class="card h-100 shadow-sm border-0" style="border-radius: 12px; background: rgba(58, 201, 119, 0.08);">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted fs-13 font-w500 d-block">Capacidade do Centro</span>
                            <h4 class="font-w700 mb-0 mt-1 text-success">{{ $totalCapacity }} <small class="fs-13 fw-normal">Vagas</small></h4>
                            <small class="text-muted">{{ $activeRooms }} turmas activas</small>
                        </div>
                        <span class="badge badge-success light font-w600">Lotação</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-3 mb-sm-0">
                <div class="card h-100 shadow-sm border-0" style="border-radius: 12px; background: rgba(2, 132, 199, 0.08);">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted fs-13 font-w500 d-block">Salas de Aula Físicas</span>
                            <h4 class="font-w700 mb-0 mt-1 text-info">{{ $totalClassrooms }} <small class="fs-13 fw-normal">Salas</small></h4>
                            <small class="text-muted">{{ $activeClassrooms }} com turmas alocadas</small>
                        </div>
                        <span class="badge badge-info light font-w600">Instalações</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6">
                <div class="card h-100 shadow-sm border-0" style="border-radius: 12px; background: rgba(245, 158, 11, 0.08);">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted fs-13 font-w500 d-block">Taxa de Pagamentos</span>
                            <h4 class="font-w700 mb-0 mt-1 text-warning">
                                {{ $totalPayments > 0 ? round(($confirmedPayments / $totalPayments) * 100) : 0 }}%
                            </h4>
                            <small class="text-muted">{{ $confirmedPayments }} de {{ $totalPayments }} pagamentos</small>
                        </div>
                        <span class="badge badge-warning light font-w600">Finanças</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Painel de Gráficos Analíticos Dinâmicos --}}
        <div class="row">
            {{-- Gráfico 1: Desempenho da Actividade Formativa --}}
            <div class="col-xl-6">
                <div class="card crypto-chart">
                    <div class="card-header pb-0 border-0 flex-wrap">
                        <div class="mb-2 mb-sm-0">
                            <div class="chart-title mb-3">
                                <h2 class="heading mb-1">Desempenho da Formação</h2>
                                <p class="text-muted fs-13 m-0">Evolução mensal de candidaturas e matrículas efectivadas</p>
                            </div>
                        </div>
                        <div class="p-static">
                            <div class="d-flex align-items-center mb-3 mb-sm-0">
                                <div class="round weekly" id="dzOldSeries">
                                    <div>
                                        <input type="checkbox" id="checkbox1" name="radio" value="weekly" checked>
                                        <label for="checkbox1" class="checkmark"></label>
                                    </div>
                                    <div>
                                        <span class="fs-13 text-muted">Inscrições</span>
                                        <h4 class="fs-5 font-w700 mb-0">{{ $totalEnrollmentsLast6Months }}</h4>
                                    </div>
                                </div>
                                <div class="round" id="dzNewSeries">
                                    <div>
                                        <input type="checkbox" id="checkbox2" name="radio" value="monthly" checked>
                                        <label for="checkbox2" class="checkmark"></label>
                                    </div>
                                    <div>
                                        <span class="fs-13 text-muted">Matrículas</span>
                                        <h4 class="fs-5 font-w700 mb-0">{{ $totalStudentsLast6Months }}</h4>
                                    </div>	
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-2 custome-tooltip pb-0 px-2">
                        <div id="marketChart"></div>
                    </div>
                </div>
            </div>

            {{-- Gráfico 2: Visão Geral Operacional (Abas Dinâmicas) --}}
            <div class="col-xl-6">
                <div class="card h-auto">
                    <div class="card-header border-0 pb-0 flex-wrap">
                        <div>
                            <h4 class="heading mb-1">Visão Geral</h4>
                            <p class="text-muted fs-13 m-0">Distribuição comparativa de formações e turnos</p>
                        </div>
                        <ul class="nav nav-pills mix-chart-tab" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" data-series="evolucao" type="button" role="tab">Evolução</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-series="turnos" type="button" role="tab">Turnos</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-series="cursos" type="button" role="tab">Top Cursos</button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body p-0">
                        <div id="overiewChart"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. Calendário e Tabela de Formadores --}}
        <div class="row">
            {{-- Coluna 1: Calendário Académico & Estado Operacional --}}
            <div class="col-xl-4 wow fadeInUp" data-wow-delay="0.2s">
                <div class="card mb-4">
                    <div class="card-header pb-0 border-0 flex-wrap">
                        <div>
                            <h2 class="heading mb-0">Calendário Académico</h2>
                            <small class="text-muted">Acompanhamento da agenda formativa</small>
                        </div>
                    </div>
                    <div class="card-body text-center event-calender py-0 px-1">
                        <input type='text' class="form-control d-none" id='datetimepicker1'>
                    </div>
                </div>

                {{-- Card de Resumo de Estados Operacionais --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header border-0 pb-0">
                        <h5 class="heading mb-0 fs-16">Estado das Turmas</h5>
                    </div>
                    <div class="card-body p-3">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-0">
                                <span><i class="fa fa-folder-open text-primary me-2"></i>Turmas Abertas</span>
                                <span class="badge badge-primary light font-w600">{{ $openRooms }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-0">
                                <span><i class="fa fa-chalkboard text-info me-2"></i>Turmas em Curso</span>
                                <span class="badge badge-info light font-w600">{{ $inProgressRooms }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-0">
                                <span><i class="fa fa-door-open text-success me-2"></i>Salas Físicas Disponíveis</span>
                                <span class="badge badge-success light font-w600">{{ $totalClassrooms }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-0">
                                <span><i class="fa fa-user-clock text-warning me-2"></i>Inscrições Pendentes</span>
                                <span class="badge badge-warning light font-w600">{{ $pendingEnrollments }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Coluna 2: Corpo Docente (Formadores) --}}
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header py-3 border-0 px-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="heading m-0">Corpo Docente</h4>
                            <p class="m-0 subtitle text-muted fs-13">Formadores do centro e turmas associadas</p>
                        </div>
                        <a href="{{ route('teacher.index') }}" class="btn btn-primary btn-sm">
                            Ver Todos <i class="fa fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive basic-tbl">
                            <table id="teacher-table" class="tech-data" style="min-width: 700px">
                                <thead>
                                    <tr>
                                        <th>Formador</th>
                                        <th>Especialidade</th>
                                        <th>Telefone</th>
                                        <th>Turmas</th>
                                        <th class="text-end">Acção</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentTeachers as $teacher)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if (!empty($teacher->image))
                                                        <img src="{{ asset('storage/' . $teacher->image) }}" alt="{{ $teacher->name }}" class="avatar avatar-sm rounded-circle me-3">
                                                    @else
                                                        <div class="avatar avatar-sm rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3 fw-bold">
                                                            {{ strtoupper(substr($teacher->name, 0, 2)) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0 font-w600">{{ $teacher->name }}</h6>
                                                        <small class="text-muted">{{ $teacher->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="font-w500 text-dark">{{ $teacher->specialization ?? 'Docente' }}</span>
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $teacher->phone ?? 'Sem contacto' }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-sm light badge-info font-w600">
                                                    {{ $teacher->rooms_count ?? 0 }} turmas
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('teacher.show', $teacher->id) }}" class="btn btn-primary shadow btn-xs sharp me-1" title="Visualizar Detalhes">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                <i class="material-symbols-outlined fs-36 d-block mb-2 text-primary opacity-50">person_off</i>
                                                Nenhum formador registado até ao momento.<br>
                                                <a href="{{ route('teacher.create') }}" class="btn btn-sm btn-primary mt-2">
                                                    <i class="fa fa-plus me-1"></i> Registar Primeiro Formador
                                                </a>
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

        {{-- 5. Inscrições Recentes & Controlo de Propinas --}}
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header border-0 px-4 py-3 d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h4 class="heading mb-0">Inscrições Recentes & Controlo de Propinas</h4>
                            <p class="m-0 subtitle text-muted fs-13">Acompanhamento das candidaturas submetidas e regularização de pagamentos</p>
                        </div>
                        <a href="{{ route('enrollment.index') }}" class="btn btn-primary btn-sm">
                            Ver Todas as Inscrições <i class="fa fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive basic-tbl">
                            <table id="example-1" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>Candidato / Formando</th>
                                        <th>Código</th>
                                        <th>Curso</th>
                                        <th>Turno / Turma</th>
                                        <th>Propina (AOA)</th>
                                        <th>Estado</th>
                                        <th class="text-center">Acções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentEnrollments as $enrollment)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if (!empty($enrollment->image))
                                                        <img src="{{ asset('storage/' . $enrollment->image) }}" alt="{{ $enrollment->name }}" class="avatar avatar-sm rounded-circle me-3">
                                                    @else
                                                        <div class="avatar avatar-sm rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-3 fw-bold">
                                                            {{ strtoupper(substr($enrollment->name, 0, 2)) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0 font-w600">{{ $enrollment->name }}</h6>
                                                        <small class="text-muted">{{ $enrollment->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-primary font-w600">
                                                    {{ $enrollment->code ?? ('INS-' . str_pad($enrollment->id, 4, '0', STR_PAD_LEFT)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="icon-box icon-box-sm bg-primary text-white me-2">
                                                        <i class="fa fa-book fs-13"></i>
                                                    </div>
                                                    <span class="font-w500">{{ $enrollment->course->name ?? 'Curso N/A' }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-dark font-w500">
                                                    {{ $enrollment->room->name ?? $enrollment->shift ?? 'A definir' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="font-w600 text-dark">
                                                    {{ number_format($enrollment->course->value ?? 0, 2, ',', '.') }} Kz
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $statusBadge = [
                                                        'Matriculado'     => 'badge-success',
                                                        'Pago'            => 'badge-info',
                                                        'Pendente'        => 'badge-warning text-dark',
                                                        'Lista de Espera' => 'badge-secondary',
                                                        'Cancelada'       => 'badge-danger',
                                                    ][$enrollment->status] ?? 'badge-light text-dark';
                                                @endphp
                                                <span class="badge badge-sm {{ $statusBadge }}">
                                                    {{ $enrollment->status }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center">
                                                    <a href="{{ route('enrollment.show', $enrollment->id) }}" class="btn btn-primary shadow btn-xs sharp me-1" title="Ver Ficha de Inscrição">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    @if ($enrollment->status === 'Pendente')
                                                        <a href="{{ route('payment.create') }}?enrollment_id={{ $enrollment->id }}" class="btn btn-success shadow btn-xs sharp me-1" title="Registar Pagamento">
                                                            <i class="fa fa-credit-card"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">
                                                <i class="material-symbols-outlined fs-36 d-block mb-2 text-primary opacity-50">app_registration</i>
                                                Nenhuma inscrição registada até ao momento.<br>
                                                <a href="{{ route('enrollment.create') }}" class="btn btn-sm btn-primary mt-2">
                                                    <i class="fa fa-plus me-1"></i> Registar Nova Inscrição
                                                </a>
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
<!--**********************************
    Fim do Corpo do Conteúdo
***********************************-->
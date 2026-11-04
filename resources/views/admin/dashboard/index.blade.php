@extends('layout.main')

@section('title', 'Painel de Controlo')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        @if(session('success'))
            <x-alerts type="success" message="{{ session('success') }}" />
        @endif

        @if(session('error'))
            <x-alerts type="danger" message="{{ session('error') }}" />
        @endif

        {{-- Contentor de dados JSON desacoplado para os gráficos analíticos (Zero JS Inline) --}}
        <div id="dashboardAnalyticsData" class="d-none" data-chart='@json($chartData)'></div>

        {{-- Banner Institucional de Boas-Vindas & Ações Rápidas --}}
        {{-- <div class="cf-hero-banner mb-4">
            <div class="row align-items-center g-3">
                <div class="col-xl-7 col-lg-6">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                        <span class="cf-hero-badge">
                            <svg class="me-1" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                            </svg>
                            Monitorização em Tempo Real
                        </span>
                        <span class="cf-hero-date">
                            <svg class="me-1" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            {{ now()->format('d/m/Y \à\s H:i') }}
                        </span>
                    </div>
                    <h2 class="cf-hero-title mb-1">Centro de Formação &mdash; Gestão Académica</h2>
                    <p class="cf-hero-subtitle mb-0">
                        Acompanhe o fluxo de candidaturas, liquidação de propinas, lotação de turmas e indicadores pedagógicos em tempo real.
                    </p>
                </div>
                <div class="col-xl-5 col-lg-6 text-lg-end">
                    <div class="d-flex flex-wrap justify-content-lg-end gap-2">
                        <a href="{{ route('enrollment.create') }}" class="btn cf-btn-hero-light">
                            <svg class="me-1" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="8.5" cy="7" r="4"></circle>
                                <line x1="20" y1="8" x2="20" y2="14"></line>
                                <line x1="23" y1="11" x2="17" y2="11"></line>
                            </svg>
                            Nova Inscrição
                        </a>
                        <a href="{{ route('payment.create') }}" class="btn cf-btn-hero-outline">
                            <svg class="me-1" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                                <line x1="2" y1="10" x2="22" y2="10"></line>
                            </svg>
                            Registar Pagamento
                        </a>
                        <a href="{{ route('room.create') }}" class="btn cf-btn-hero-outline">
                            <svg class="me-1" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            Criar Turma
                        </a>
                    </div>
                </div>
            </div>

            {{-- Barra de Alertas Acionáveis da Secretaria --}}
             {{-- @if($pendingEnrollments > 0 || $approvedEnrollments > 0 || $waitingEnrollments > 0)
                <div class="cf-hero-alerts-strip mt-3 pt-3">
                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <span class="cf-hero-alert-label">
                            <svg class="me-1" viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                            </svg>
                            Atenção da Secretaria:
                        </span>
                        @if($approvedEnrollments > 0)
                            <a href="{{ route('enrollment.index') }}" class="cf-hero-pill cf-hero-pill-success">
                                <strong>{{ $approvedEnrollments }}</strong>&nbsp;{{ $approvedEnrollments == 1 ? 'inscrição paga pronta' : 'inscrições pagas prontas' }} para matricular
                                <i class="fa fa-arrow-right ms-1"></i>
                            </a>
                        @endif
                        @if($pendingEnrollments > 0)
                            <a href="{{ route('enrollment.index') }}" class="cf-hero-pill cf-hero-pill-warning">
                                <strong>{{ $pendingEnrollments }}</strong>&nbsp;{{ $pendingEnrollments == 1 ? 'candidatura aguarda' : 'candidaturas aguardam' }} pagamento
                                <i class="fa fa-arrow-right ms-1"></i>
                            </a>
                        @endif
                        @if($waitingEnrollments > 0)
                            <a href="{{ route('enrollment.index') }}" class="cf-hero-pill cf-hero-pill-info">
                                <strong>{{ $waitingEnrollments }}</strong>&nbsp;em lista de espera
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>  --}}

        {{-- Linha 1: 4 Cartões Principais de KPIs Executivos (Ícones Vetoriais Outline em Badges Translúcidos) --}}
        <div class="row g-3 mb-4">
            {{-- KPI 1: Receita Confirmada (Payment Credit Card Icon) --}}
            <div class="col-xl-3 col-sm-6">
                <div class="cf-kpi-card h-100">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="cf-kpi-label">Receita Liquidada</span>
                            <h3 class="cf-kpi-value text-primary mb-0">
                                {{ number_format($totalRevenue, 2, ',', '.') }} <small class="fs-6">Kz</small>
                            </h3>
                        </div>
                        <div class="cf-kpi-icon cf-kpi-icon-primary" aria-hidden="true">
                            <svg class="cf-kpi-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="5" width="20" height="14" rx="2.5"></rect>
                                <line x1="2" y1="10" x2="22" y2="10"></line>
                                <line x1="6" y1="15" x2="10" y2="15"></line>
                            </svg>
                        </div>
                    </div>
                    <div class="cf-kpi-footer d-flex justify-content-between align-items-center pt-2 border-top">
                        <span class="text-muted fs-8">
                            <i class="fa fa-check-circle text-success me-1"></i>
                            <strong>{{ $confirmedPayments }}</strong> confirmados
                        </span>
                        @if($pendingPaymentsCount > 0)
                            <span class="badge light badge-warning fs-8">{{ $pendingPaymentsCount }} pendente(s)</span>
                        @else
                            <a href="{{ route('payment.index') }}" class="cf-kpi-link">Ver recibos <i class="fa fa-angle-right"></i></a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- KPI 2: Formandos Matriculados (Student Graduation Cap Icon) --}}
            <div class="col-xl-3 col-sm-6">
                <div class="cf-kpi-card h-100">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="cf-kpi-label">Formandos Ativos</span>
                            <h3 class="cf-kpi-value mb-0">{{ $totalStudents }}</h3>
                        </div>
                        <div class="cf-kpi-icon cf-kpi-icon-success" aria-hidden="true">
                            <svg class="cf-kpi-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                                <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                            </svg>
                        </div>
                    </div>
                    @php
                        $capacityOccupancy = $totalCapacity > 0 ? min(100, round(($totalStudents / $totalCapacity) * 100, 1)) : 0;
                    @endphp
                    <div class="cf-kpi-footer d-flex justify-content-between align-items-center pt-2 border-top">
                        <span class="text-muted fs-8">
                            Lotação global: <strong>{{ $capacityOccupancy }}%</strong> ({{ $totalCapacity }} vagas)
                        </span>
                        <a href="{{ route('student.index') }}" class="cf-kpi-link">Listar <i class="fa fa-angle-right"></i></a>
                    </div>
                </div>
            </div>

            {{-- KPI 3: Candidaturas / Inscrições (Registration Clipboard Icon) --}}
            <div class="col-xl-3 col-sm-6">
                <div class="cf-kpi-card h-100">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="cf-kpi-label">Total de Inscrições</span>
                            <h3 class="cf-kpi-value mb-0">{{ $totalEnrollments }}</h3>
                        </div>
                        <div class="cf-kpi-icon cf-kpi-icon-info" aria-hidden="true">
                            <svg class="cf-kpi-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                <path d="m9 14 2 2 4-4"></path>
                            </svg>
                        </div>
                    </div>
                    @php
                        $conversionRate = $totalEnrollments > 0 ? round(($enrolledStudents / $totalEnrollments) * 100, 1) : 0;
                    @endphp
                    <div class="cf-kpi-footer d-flex justify-content-between align-items-center pt-2 border-top">
                        <span class="text-muted fs-8">
                            Conversão: <strong class="text-success">{{ $conversionRate }}%</strong> matriculados
                        </span>
                        <a href="{{ route('enrollment.index') }}" class="cf-kpi-link">Gerir <i class="fa fa-angle-right"></i></a>
                    </div>
                </div>
            </div>

            {{-- KPI 4: Turmas & Oferta Formativa (Group Users Icon) --}}
            <div class="col-xl-3 col-sm-6">
                <div class="cf-kpi-card h-100">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="cf-kpi-label">Turmas em Funcionamento</span>
                            <h3 class="cf-kpi-value mb-0">
                                {{ $activeRooms }} <small class="text-muted fs-6 font-w500">/ {{ $totalRooms }}</small>
                            </h3>
                        </div>
                        <div class="cf-kpi-icon cf-kpi-icon-warning" aria-hidden="true">
                            <svg class="cf-kpi-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="cf-kpi-footer d-flex justify-content-between align-items-center pt-2 border-top">
                        <span class="text-muted fs-8">
                            <strong>{{ $activeCourses }}</strong> cursos &bull; <strong>{{ $activeTeachers }}</strong> formadores
                        </span>
                        <a href="{{ route('room.index') }}" class="cf-kpi-link">Turmas <i class="fa fa-angle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Linha 2: Resumo de Infraestrutura e Funil de Secretaria (4 Mini-Indicadores com Ícones Vetoriais Outline) --}}
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-sm-6 col-12">
                <a href="{{ route('classroom.index') }}" class="cf-mini-stat-card">
                    <div class="cf-mini-stat-icon bg-primary-subtle text-primary" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect>
                            <path d="M9 22v-4h6v4"></path>
                            <path d="M8 6h.01"></path>
                            <path d="M16 6h.01"></path>
                            <path d="M12 6h.01"></path>
                            <path d="M12 10h.01"></path>
                            <path d="M12 14h.01"></path>
                            <path d="M16 10h.01"></path>
                            <path d="M16 14h.01"></path>
                            <path d="M8 10h.01"></path>
                            <path d="M8 14h.01"></path>
                        </svg>
                    </div>
                    <div class="cf-mini-stat-body">
                        <span class="cf-mini-stat-title">Salas</span>
                        <strong class="cf-mini-stat-num">{{ $activeClassrooms }} ativas de {{ $totalClassrooms }}</strong>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <a href="{{ route('course.index') }}" class="cf-mini-stat-card">
                    <div class="cf-mini-stat-icon bg-info-subtle text-info" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                        </svg>
                    </div>
                    <div class="cf-mini-stat-body">
                        <span class="cf-mini-stat-title">Oferta Formativa</span>
                        <strong class="cf-mini-stat-num">{{ $activeCourses }} cursos ativos ({{ $totalCourses }} total)</strong>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <a href="{{ route('teacher.index') }}" class="cf-mini-stat-card">
                    <div class="cf-mini-stat-icon bg-success-subtle text-success" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="3" width="20" height="14" rx="2"></rect>
                            <path d="M8 21h8"></path>
                            <path d="M12 17v4"></path>
                            <path d="m7 11 3-3 2 2 4-4"></path>
                        </svg>
                    </div>
                    <div class="cf-mini-stat-body">
                        <span class="cf-mini-stat-title">Corpo Docente</span>
                        <strong class="cf-mini-stat-num">{{ $totalTeachers }} formadores ({{ $activeTeachers }} c/ turma)</strong>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <a href="{{ route('enrollment.index') }}" class="cf-mini-stat-card">
                    <div class="cf-mini-stat-icon bg-warning-subtle text-warning" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                        </svg>
                    </div>
                    <div class="cf-mini-stat-body">
                        <span class="cf-mini-stat-title">Funil de Admissão</span>
                        <strong class="cf-mini-stat-num">{{ $pendingEnrollments }} Pend. &bull; {{ $approvedEnrollments }} Pagos &bull; {{ $enrolledStudents }} Matr.</strong>
                    </div>
                </a>
            </div>
        </div>

        {{-- Linha 3: Gráficos Analíticos (Evolução Semestral + Distribuição por Turno & Top Cursos) --}}
        <div class="row g-4 mb-4">
            {{-- Gráfico 1: Evolução Mensal de Inscrições, Matrículas e Receita (Últimos 6 Meses) --}}
            <div class="col-xxl-8 col-xl-7 col-12">
                <div class="card cf-dashboard-card h-100 mb-0">
                    <div class="card-header flex-wrap d-flex justify-content-between align-items-start align-items-md-center gap-3">
                        <div class="cf-card-header-info">
                            <h5 class="card-title mb-1">Desempenho Académico e Financeiro (Últimos 6 Meses)</h5>
                            <p class="subtitle text-muted mb-0">Comparativo mensal de novas inscrições, matrículas efetivadas e receita</p>
                        </div>
                        <div class="cf-chart-summary-group">
                            <div class="cf-chart-summary-pill">
                                <small class="text-muted d-block">Inscrições (6M)</small>
                                <strong class="text-primary">{{ $totalEnrollmentsLast6Months }}</strong>
                            </div>
                            <div class="cf-chart-summary-pill">
                                <small class="text-muted d-block">Matrículas (6M)</small>
                                <strong class="text-success">{{ $totalStudentsLast6Months }}</strong>
                            </div>
                            <div class="cf-chart-summary-pill">
                                <small class="text-muted d-block">Receita (6M)</small>
                                <strong class="text-dark">{{ number_format($totalRevenueLast6Months, 0, ',', '.') }} Kz</strong>
                            </div>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="cf-chart-container">
                            <canvas id="enrollmentRevenueChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Gráfico 2: Cursos Mais Procurados & Distribuição por Turno --}}
            <div class="col-xxl-4 col-xl-5 col-12">
                <div class="card cf-dashboard-card h-100 mb-0">
                    <div class="card-header">
                        <h5 class="card-title mb-1">Cursos Mais Procurados &amp; Turnos</h5>
                        <p class="subtitle text-muted mb-0">Top 5 cursos por volume de candidaturas</p>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between">
                        {{-- Lista de Progresso dos Top 5 Cursos --}}
                        <div class="mb-4">
                            @php
                                $maxCourseEnrollments = $topCourses->max('enrollments_count') ?: 1;
                            @endphp
                            @forelse($topCourses as $course)
                                @php
                                    $pct = round(($course->enrollments_count / $maxCourseEnrollments) * 100);
                                @endphp
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center gap-2 mb-1">
                                        <a href="{{ route('course.show', $course->id) }}" class="font-w600 text-dark fs-7 text-truncate">
                                            {{ $course->name }}
                                        </a>
                                        <span class="badge light badge-primary fs-8 flex-shrink-0">
                                            {{ $course->enrollments_count }} {{ $course->enrollments_count == 1 ? 'inscrição' : 'inscrições' }}
                                        </span>
                                    </div>
                                    <div class="progress room-progress-track">
                                        <div class="progress-bar bg-primary room-progress-fill" role="progressbar" style="width: {{ $pct }}%" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted fs-7 text-center py-3 mb-0">Ainda não existem cursos registados.</p>
                            @endforelse
                        </div>

                        {{-- Distribuição de Turmas por Turno --}}
                        <div class="pt-3 border-top">
                            <h6 class="text-uppercase text-muted fs-8 font-w700 mb-3">Distribuição de Turmas por Turno</h6>
                            <div class="cf-doughnut-container">
                                <canvas id="shiftDistributionChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Linha 4: Tabelas Operacionais de Monitorização Rápida (Essenciais e Compactas) --}}
        <div class="row g-4">
            {{-- Últimas Inscrições --}}
            <div class="col-xl-6 col-12">
                <div class="card cf-dashboard-card h-100 mb-0">
                    <div class="card-header flex-wrap d-flex justify-content-between align-items-center gap-2">
                        <div>
                            <h5 class="card-title mb-0">Últimas Inscrições</h5>
                        </div>
                        <a href="{{ route('enrollment.index') }}" class="btn btn-outline-primary btn-xs flex-shrink-0">
                            Ver Todas <i class="fa fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive cf-dashboard-table-wrap">
                            <table class="table table-hover custom-table cf-dashboard-table mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Candidato</th>
                                        <th>Curso</th>
                                        <th class="text-end pe-4">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentEnrollments as $enrollment)
                                        <tr>
                                            <td class="ps-4" data-label="Candidato">
                                                <a href="{{ route('enrollment.show', $enrollment->id) }}" class="font-w600 text-dark d-block text-truncate cf-table-cell-text">
                                                    {{ $enrollment->name }}
                                                </a>
                                            </td>
                                            <td data-label="Curso">
                                                <span class="text-muted d-block text-truncate cf-table-cell-text">
                                                    {{ $enrollment->course->name ?? 'N/D' }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-4" data-label="Estado">
                                                <span class="badge light {{ $enrollment->statusBadgeClass() }}">
                                                    {{ $enrollment->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-4 text-muted">
                                                Nenhuma inscrição registada recentemente.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Últimos Pagamentos --}}
            <div class="col-xl-6 col-12">
                <div class="card cf-dashboard-card h-100 mb-0">
                    <div class="card-header flex-wrap d-flex justify-content-between align-items-center gap-2">
                        <div>
                            <h5 class="card-title mb-0">Últimos Pagamentos</h5>
                        </div>
                        <a href="{{ route('payment.index') }}" class="btn btn-outline-primary btn-xs flex-shrink-0">
                            Histórico <i class="fa fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive cf-dashboard-table-wrap">
                            <table class="table table-hover custom-table cf-dashboard-table mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Recibo</th>
                                        <th>Candidato</th>
                                        <th class="text-end pe-4">Valor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentPayments as $payment)
                                        <tr>
                                            <td class="ps-4 text-nowrap" data-label="Recibo">
                                                <a href="{{ route('payment.show', $payment->id) }}" class="font-w600 text-primary">
                                                    {{ $payment->reference }}
                                                </a>
                                            </td>
                                            <td data-label="Candidato">
                                                <span class="text-dark d-block text-truncate cf-table-cell-text">
                                                    {{ $payment->enrollment->name ?? 'N/D' }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-4 text-nowrap" data-label="Valor">
                                                <strong class="text-success">{{ $payment->formattedValue() }}</strong>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-4 text-muted">
                                                Nenhum pagamento confirmado recentemente.
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
@endsection

@extends('layout.main')

@section('content')
<div class="container">
    @if(session('success'))
        <x-alerts type="success" message="{{ session('success') }}" />
    @endif

    @if(session('error'))
        <x-alerts type="danger" message="{{ session('error') }}" />
    @endif

    <div class="row">
        <div class="col-md-12">
            <h2>Painel de Controle</h2>
            <p class="text-muted">Última atualização: {{ now()->format('d/m/Y H:i:s') }}</p>
            <p class="text-muted mb-4">Visão geral dos indicadores principais do centro de formação</p>
        </div>
    </div>

    <div class="row">
        <!-- Cards de Estatísticas - Fluxo de Entrada -->
        <div class="col-xl-2 col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Salas de Aula</h5>
                    <p class="card-text">
                        <strong>{{ $totalClassrooms }}</strong> total<br>
                        <span class="text-success">
                            {{ $activeClassrooms }} ativas
                        </span>
                    </p>
                    <!-- MELHORIA: Adicionar tendência (↑/↓) comparado ao período anterior -->
                    @if(isset($classroomTrend))
                        <small class="text-{{ $classroomTrend >= 0 ? 'success' : 'danger' }}">
                            {{ ($classroomTrend >= 0 ? '↑' : '↓') }} {{ abs($classroomTrend) }}% vs mês passado
                        </small>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Cursos</h5>
                    <p class="card-text">
                        <strong>{{ $totalCourses }}</strong> total<br>
                        <span class="text-success">
                            {{ $activeCourses }} ativos
                        </span>
                    </p>
                    <!-- MELHORIA: Mostrar capacidade utilização média das turmas por curso -->
                    @if(isset($averageCourseUtilization))
                        <small class="text-muted">
                            Utilização média: {{ number_format($averageCourseUtilization, 1) }}%
                        </small>
                    @endif

                    <!-- MELHORIA: Adicionar tendência (↑/↓) comparado ao período anterior -->
                    @if(isset($courseTrend))
                        <small class="text-{{ $courseTrend >= 0 ? 'success' : 'danger' }} mt-1">
                            {{ ($courseTrend >= 0 ? '↑' : '↓') }} {{ abs($courseTrend) }}% vs mês passado
                        </small>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Formadores</h5>
                    <p class="card-text">
                        <strong>{{ $totalTeachers }}</strong> total<br>
                        <span class="text-success">
                            {{ $activeTeachers }} ativos
                        </span>
                    </p>
                    <!-- MELHORIA: Adicionar carga horária média ou número médio de turmas por formador -->
                    @if(isset($averageRoomsPerTeacher))
                        <small class="text-muted">
                            Média: {{ number_format($averageRoomsPerTeacher, 1) }} turmas/formador
                        </small>
                    @endif

                    <!-- MELHORIA: Adicionar tendência (↑/↓) comparado ao período anterior -->
                    @if(isset($teacherTrend))
                        <small class="text-{{ $teacherTrend >= 0 ? 'success' : 'danger' }} mt-1">
                            {{ ($teacherTrend >= 0 ? '↑' : '↓') }} {{ abs($teacherTrend) }}% vs mês passado
                        </small>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Turmas</h5>
                    <p class="card-text">
                        <strong>{{ $totalRooms }}</strong> total<br>
                        <span class="text-success">
                            {{ $activeRooms }} ativas
                        </span>
                    </p>
                    <!-- MELHORIA: Mostrar lotação média ou percentual de vagas preenchidas -->
                    @if(isset($averageOccupancyRate))
                        <small class="text-muted">
                            Lotação média: {{ number_format($averageOccupancyRate, 1) }}%
                        </small>
                    @endif

                    <!-- MELHORIA: Adicionar tendência (↑/↓) comparado ao período anterior -->
                    @if(isset($roomTrend))
                        <small class="text-{{ $roomTrend >= 0 ? 'success' : 'danger' }} mt-1">
                            {{ ($roomTrend >= 0 ? '↑' : '↓') }} {{ abs($roomTrend) }}% vs mês passado
                        </small>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- MELHORIA: Reorganizar cards por categoria relacionada para melhor fluxo visual -->
        <!-- Agrupar: Inscrições + Formandos (fluxo de entrada) -->
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Inscrições</h5>
                            <p class="card-text">
                                <strong>{{ $totalEnrollments }}</strong> total<br>
                                <span class="text-info">
                                    {{ $pendingEnrollments }} pendentes
                                </span><br>
                                <span class="text-success">
                                    {{ $approvedEnrollments }} pagas
                                </span><br>
                                <span class="text-warning">
                                    {{ $enrolledStudents }} matriculadas
                                </span>
                            </p>
                            <!-- MELHORIA: Adicionar taxa de conversão (inscrições → matrículas) -->
                            @if($totalEnrollments > 0)
                                <small class="text-muted">
                                    {{ round(($enrolledStudents / $totalEnrollments) * 100, 1) }}% de conversão
                                </small>
                            @endif

                            <!-- MELHORIA: Adicionar tendência (↑/↓) comparado ao período anterior -->
                            @if(isset($enrollmentTrend))
                                <small class="text-{{ $enrollmentTrend >= 0 ? 'success' : 'danger' }} mt-1">
                                    {{ ($enrollmentTrend >= 0 ? '↑' : '↓') }} {{ abs($enrollmentTrend) }}% vs mês passado
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Formandos</h5>
                            <p class="card-text">
                                <strong>{{ $totalStudents }}</strong> formandos
                            </p>
                            <!-- MELHORIA: Adicionar divisão por gênero, faixa etária ou curso -->
                            @if(isset($maleStudents) && isset($femaleStudents))
                                <small class="text-muted">
                                    ♂: {{ $maleStudents }} | ♀: {{ $femaleStudents }}
                                </small>
                            @endif

                            <!-- MELHORIA: Adicionar tendência (↑/↓) comparado ao período anterior -->
                            @if(isset($studentTrend))
                                <small class="text-{{ $studentTrend >= 0 ? 'success' : 'danger' }} mt-1">
                                    {{ ($studentTrend >= 0 ? '↑' : '↓') }} {{ abs($studentTrend) }}% vs mês passado
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Agrupar: Pagamentos + Taxa de Ocupação (indicadores de saúde financeira e operacional) -->
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Pagamentos</h5>
                            <p class="card-text">
                                <strong>{{ $totalPayments }}</strong> total<br>
                                <span class="text-success">
                                    {{ $confirmedPayments }} confirmados
                                </span><br>
                                <span class="text-success">
                                    Total Recebido: {{ number_format($totalRevenue, 2, ',', '.') }} Kz
                                </span>
                            </p>
                            <!-- MELHORIA: Mostrar receita mensal em tendência ou inadimplência -->
                            @if(isset($currentMonthRevenue))
                                <small class="text-muted">
                                    Mês atual: Kz {{ number_format($currentMonthRevenue, 0, ',', '.') }}
                                </small>
                            @endif

                            <!-- MELHORIA: Adicionar tendência (↑/↓) comparado ao período anterior -->
                            @if(isset($paymentTrend))
                                <small class="text-{{ $paymentTrend >= 0 ? 'success' : 'danger' }} mt-1">
                                    {{ ($paymentTrend >= 0 ? '↑' : '↓') }} {{ abs($paymentTrend) }}% vs mês passado
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Taxa de Ocupação</h5>
                            <p class="card-text">
                                @if($totalRooms > 0)
                                    <span class="badge badge-info">
                                        {{ round(($activeRooms / $totalRooms) * 100, 1) }}%
                                    </span>
                                @else
                                    <span class="badge badge-secondary">0%</span>
                                @endif
                            </p>
                            <!-- MELHORIA: Adicionar meta de ocupação e indicador de atingimento -->
                            @if(isset($occupancyTarget))
                                <small class="text-muted">
                                    Meta: {{ $occupancyTarget }}% |
                                    <span class="text-{{ $occupancyRate >= $occupancyTarget ? 'success' : 'danger' }}">
                                        {{ number_format($occupancyRate, 1) }}%
                                    </span>
                                </small>
                            @endif

                            <!-- MELHORIA: Adicionar tendência (↑/↓) comparado ao período anterior -->
                            @if(isset($occupancyTrend))
                                <small class="text-{{ $occupancyTrend >= 0 ? 'success' : 'danger' }} mt-1">
                                    {{ ($occupancyTrend >= 0 ? '↑' : '↓') }} {{ abs($occupancyTrend) }}% vs mês passado
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- MELHORIA: Adicionar sexto card com indicador estratégico importante -->
        <!-- Ex: Card para "Evasão" ou "Satisfação" ou "Resultado de Aprendizagem" -->
        <div class="col-md-3 offset-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Taxa de Conclusão</h5>
                    <p class="card-text">
                        @if(isset($completionRate))
                            <strong>{{ number_format($completionRate, 1) }}%</strong><br>
                            <small class="text-muted">Taxa de conclusão de cursos</small>
                        @else
                            <span class="badge badge-info">Dados em processamento</span><br>
                            <small class="text-muted">Aguardando cálculo da taxa de conclusão</small>
                        @endif
                    </p>

                    <!-- MELHORIA: Adicionar tendência (↑/↓) comparado ao período anterior -->
                    @if(isset($completionTrend))
                        <small class="text-{{ $completionTrend >= 0 ? 'success' : 'danger' }} mt-1">
                            {{ ($completionTrend >= 0 ? '↑' : '↓') }} {{ abs($completionTrend) }}% vs mês passado
                        </small>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

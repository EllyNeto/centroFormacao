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
            <!-- MELHORIA: Adicionar descrição contextual e últimos updates -->
            <!-- Ex: "<p class="text-muted">Última atualização: {{ $lastUpdated }}</p>" -->
        </div>
    </div>

    <div class="row">
        <!-- Cards de Estatísticas -->
        <!-- MELHORIA: Considerar usar grid responsivo (col-xl-2 para 6 items em telas largas) -->
        <div class="col-md-3">
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
                    <!-- Ex: "<small class="text-success">↑ 5% vs mês passado</small>" -->
                </div>
            </div>
        </div>
        <div class="col-md-3">
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
                </div>
            </div>
        </div>
        <div class="col-md-3">
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
                </div>
            </div>
        </div>
        <div class="col-md-3">
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
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- MELHORIA: Reorganizar cards por categoria relacionada para melhor fluxo visual -->
        <!-- Agrupar: Inscrições + Formandos (fluxo de entrada) -->
        <!-- Agrupar: Pagamentos + Taxa de Ocupação (indicadores de saúde financeira e operacional) -->
        <div class="col-md-3">
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
                    <!-- Ex: "<small>{{ round(($enrolledStudents / $totalEnrollments) * 100, 1) }}% de conversão</small>" -->
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Formandos</h5>
                    <p class="card-text">
                        <strong>{{ $totalStudents }}</strong> formandos
                    </p>
                    <!-- MELHORIA: Adicionar divisão por gênero, faixa etária ou curso -->
                    <!-- Ex: "<small>♂: {{$maleStudents}} | ♀: {{$femaleStudents}}</small>" -->
                </div>
            </div>
        </div>
        <div class="col-md-3">
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
                    <!-- Ex: "<small>Mês atual: Kz {{$currentMonthRevenue}}</small>" -->
                </div>
            </div>
        </div>
        <div class="col-md-3">
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
                    <!-- Ex: "<small>Meta: 80% | <span class="text-{{ $occupancyRate >= 80 ? 'success' : 'danger' }}">{{ $occupancyRate }}%</span></small>" -->
                </div>
            </div>
        </div>
        <!-- MELHORIA: Adicionar sexto card com indicador estratégico importante -->
        <!-- Ex: Card para "Evasão" ou "Satisfação" ou "Resultado de Aprendizagem" -->
    </div>
</div>
@endsection

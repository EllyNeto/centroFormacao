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
        </div>
    </div>

    <div class="row">
        <!-- Cards de Estatísticas -->
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
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

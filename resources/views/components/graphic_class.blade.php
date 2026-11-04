{{-- Componente de Gráficos e Estatísticas de Turmas (Estrutura em resources/views/components) --}}

<div class="row mb-4">
    {{-- Cards Indicadores / KPIs --}}
    <div class="col-xl-3 col-sm-6 mb-3 mb-xl-0">
        <div class="card text-white h-100 shadow-sm" style="border-radius: 12px; background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%) !important;">
            <div class="card-body d-flex align-items-center justify-content-between p-3">
                <div>
                    <span class="text-white-50 fs-14 fw-semibold">Total de Turmas</span>
                    <h2 class="text-white fw-bold mb-0 mt-1">15</h2>
                </div>
                <div class="icon-box bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="fa fa-users fs-20 text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6 mb-3 mb-xl-0">
        <div class="card text-white h-100 shadow-sm" style="border-radius: 12px; background: linear-gradient(135deg, #10b981 0%, #047857 100%) !important;">
            <div class="card-body d-flex align-items-center justify-content-between p-3">
                <div>
                    <span class="text-white-50 fs-14 fw-semibold">Turmas Activas</span>
                    <h2 class="text-white fw-bold mb-0 mt-1">12</h2>
                </div>
                <div class="icon-box bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="fa fa-check-circle fs-20 text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6 mb-3 mb-sm-0">
        <div class="card text-white h-100 shadow-sm" style="border-radius: 12px; background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%) !important;">
            <div class="card-body d-flex align-items-center justify-content-between p-3">
                <div>
                    <span class="text-white-50 fs-14 fw-semibold">Capacidade Total</span>
                    <h2 class="text-white fw-bold mb-0 mt-1">350 <small class="fs-14">Vagas</small></h2>
                </div>
                <div class="icon-box bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="fa fa-graduation-cap fs-20 text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card text-white h-100 shadow-sm" style="border-radius: 12px; background: linear-gradient(135deg, #f59e0b 0%, #b45309 100%) !important;">
            <div class="card-body d-flex align-items-center justify-content-between p-3">
                <div>
                    <span class="text-white-50 fs-14 fw-semibold">Média p/ Turma</span>
                    <h2 class="text-white fw-bold mb-0 mt-1">25 <small class="fs-14">Alunos</small></h2>
                </div>
                <div class="icon-box bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="fa fa-pie-chart fs-20 text-white"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Painel de Gráficos ApexCharts --}}
<div class="row mb-4">
    {{-- Gráfico 1: Distribuição por Turno --}}
    <div class="col-xl-6 col-lg-6 mb-4 mb-lg-0">
        <div class="card h-100 shadow-sm" style="border-radius: 12px;">
            <div class="card-header border-0 pb-0 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title fw-bold mb-1">Distribuição das Turmas por Turno</h5>
                    <small class="text-muted">Proporção de turmas entre Manhã, Tarde, Pós-Laboral</small>
                </div>
                <span class="badge badge-primary light">Turnos</span>
            </div>
            <div class="card-body pt-2 d-flex align-items-center justify-content-center">
                <div id="chartTurnos" style="min-height: 280px; width: 100%;"></div>
            </div>
        </div>
    </div>

    {{-- Gráfico 2: Capacidade de Alunos por Turma --}}
    <div class="col-xl-6 col-lg-6">
        <div class="card h-100 shadow-sm" style="border-radius: 12px;">
            <div class="card-header border-0 pb-0 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title fw-bold mb-1">Capacidade de Alunos por Turma</h5>
                    <small class="text-muted">Lotação máxima configurada por cada turma</small>
                </div>
                <span class="badge badge-info light">Lotação</span>
            </div>
            <div class="card-body pt-2">
                <div id="chartCapacidade" style="min-height: 280px; width: 100%;"></div>
            </div>
        </div>
    </div>
</div>

{{-- Inclusão dos ficheiros de script JS separados da vista Blade --}}
@push('scripts')
<script src="{{ asset('vendor/apexchart/apexchart.js') }}"></script>
<script src="{{ asset('js/class-charts.js') }}"></script>
@endpush

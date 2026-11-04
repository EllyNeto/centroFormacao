<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controlador do Painel Principal (Dashboard).
 *
 * Responsável por agregar todos os indicadores analíticos, pedagógicos e financeiros
 * do Centro de Formação Profissional e fornecer os dados estruturados para a vista Blade.
 */
class DashboardController extends Controller
{
    /**
     * Exibe o painel principal com os indicadores executivos em tempo real.
     *
     * Recolhe métricas de:
     * - Indicadores-chave de desempenho (KPIs financeiros e de lotação)
     * - Infraestrutura e corpo docente (Salas, Cursos e Formadores)
     * - Funil de admissão (Inscrições pendentes, pagas e matriculadas)
     * - Dados temporais para os gráficos analíticos (últimos 6 meses)
     * - Tabelas operacionais recentes (últimas candidaturas e liquidações)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        // 1. Indicadores Financeiros e de Matrículas (KPIs do topo)
        // Calcula a receita acumulada de pagamentos com status confirmado (verdadeiro / 'Pago')
        $totalRevenue = (float) Payment::where('status', true)->sum('value');
        $confirmedPayments = Payment::where('status', true)->count();
        $pendingPaymentsCount = Payment::where('status', false)->count();

        // 2. Indicadores Académicos e de Infraestrutura
        $totalStudents = Student::count();
        $enrolledStudents = Enrollment::where('status', 'Matriculado')->count();
        $totalCapacity = (int) Classroom::sum('capacity');

        // Total global de candidaturas/inscrições registadas
        $totalEnrollments = Enrollment::count();
        $pendingEnrollments = Enrollment::where('status', 'Pendente')->count();
        $approvedEnrollments = Enrollment::where('status', 'Pago')->count();
        $waitingEnrollments = Enrollment::where('status', 'Lista de Espera')->count();

        // Contagem de turmas (rooms), cursos e docentes
        $totalRooms = Room::count();
        $activeRooms = Room::count(); // Todas as turmas cadastradas em funcionamento
        $totalCourses = Course::count();
        $activeCourses = Course::where('status', true)->count();
        $totalTeachers = Teacher::count();
        $activeTeachers = Teacher::count();

        // Contagem de salas físicas
        $totalClassrooms = Classroom::count();
        $activeClassrooms = Classroom::count();

        // 3. Indicadores Semestrais (Últimos 6 meses para os cartões de resumo)
        $sixMonthsAgo = Carbon::now()->subMonths(6)->startOfMonth();

        $totalEnrollmentsLast6Months = Enrollment::where('created_at', '>=', $sixMonthsAgo)->count();
        $totalStudentsLast6Months = Student::where('created_at', '>=', $sixMonthsAgo)->count();
        $totalRevenueLast6Months = (float) Payment::where('status', true)
            ->where('created_at', '>=', $sixMonthsAgo)
            ->sum('value');

        // 4. Preparação dos dados estruturados para os Gráficos Chart.js
        // Constrói as etiquetas mensais dos últimos 6 meses (ex.: "Outubro", "Novembro")
        $monthsLabels = [];
        $monthlyEnrollments = [];
        $monthlyStudents = [];
        $monthlyRevenue = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthDate = Carbon::now()->subMonths($i);
            $monthName = $monthDate->translatedFormat('M'); // Abreviatura do mês em português
            $start = (clone $monthDate)->startOfMonth();
            $end = (clone $monthDate)->endOfMonth();

            $monthsLabels[] = ucfirst($monthName);
            $monthlyEnrollments[] = Enrollment::whereBetween('created_at', [$start, $end])->count();
            $monthlyStudents[] = Student::whereBetween('created_at', [$start, $end])->count();
            $monthlyRevenue[] = (float) Payment::where('status', true)
                ->whereBetween('created_at', [$start, $end])
                ->sum('value');
        }

        // Distribuição de turmas por turno para o gráfico Doughnut
        $shiftMorning = Room::where('shift', 'Manhã')->count();
        $shiftAfternoon = Room::where('shift', 'Tarde')->count();
        $shiftNight = Room::where('shift', 'Pós-Laboral')->count();

        // Estrutura enviada em formato JSON para o script public/js/dashboard-init.js
        $chartData = [
            'labels' => $monthsLabels,
            'enrollments' => $monthlyEnrollments,
            'students' => $monthlyStudents,
            'revenue' => $monthlyRevenue,
            'shifts' => [
                'morning' => $shiftMorning,
                'afternoon' => $shiftAfternoon,
                'night' => $shiftNight,
            ],
        ];

        // 5. Cursos Mais Procurados (Top 5 por volume de inscrições associadas)
        $topCourses = Course::withCount('enrollments')
            ->orderByDesc('enrollments_count')
            ->take(5)
            ->get();

        // 6. Listagens Operacionais Recentes para monitorização rápida da secretaria
        $recentEnrollments = Enrollment::with('course')
            ->latest()
            ->take(5)
            ->get();

        $recentPayments = Payment::latest()
            ->take(5)
            ->get();

        // Retorna a vista principal do painel passando todas as variáveis compiladas
        return view('admin.dashboard.index', compact(
            'totalRevenue',
            'confirmedPayments',
            'pendingPaymentsCount',
            'totalStudents',
            'enrolledStudents',
            'totalCapacity',
            'totalEnrollments',
            'pendingEnrollments',
            'approvedEnrollments',
            'waitingEnrollments',
            'totalRooms',
            'activeRooms',
            'totalCourses',
            'activeCourses',
            'totalTeachers',
            'activeTeachers',
            'totalClassrooms',
            'activeClassrooms',
            'totalEnrollmentsLast6Months',
            'totalStudentsLast6Months',
            'totalRevenueLast6Months',
            'chartData',
            'topCourses',
            'recentEnrollments',
            'recentPayments'
        ));
    }
}

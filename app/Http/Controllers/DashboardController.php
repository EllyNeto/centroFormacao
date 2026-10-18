<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Carbon\Carbon;

/**
 * Controlador DashboardController - Painel Principal e Analítico do Centro de Formação.
 *
 * Responsável por consolidar todos os indicadores operacionais, pedagógicos e financeiros
 * do Centro de Formação, alimentando gráficos analíticos e listagens de monitorização em tempo real.
 */
class DashboardController extends Controller
{
    /**
     * Apresenta o painel de controlo dinâmico com métricas consolidadas.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // 1. Contagens quantitativas das entidades principais
        $totalStudents       = Student::count();
        $totalTeachers       = Teacher::count();
        $activeTeachers      = Teacher::whereHas('rooms', function ($q) {
            $q->whereIn('status', ['Aberta', 'Em curso']);
        })->count();

        $totalCourses        = Course::count();
        $activeCourses       = Course::where('status', true)->count();

        $totalRooms          = Room::count();
        $openRooms           = Room::where('status', 'Aberta')->count();
        $inProgressRooms     = Room::where('status', 'Em curso')->count();
        $activeRooms         = $openRooms + $inProgressRooms;

        $totalClassrooms     = Classroom::count();
        $activeClassrooms    = Classroom::whereHas('rooms', function ($q) {
            $q->whereIn('status', ['Aberta', 'Em curso']);
        })->count();

        $totalCapacity       = (int) Room::whereIn('status', ['Aberta', 'Em curso'])->sum('max_capacity');

        // 2. Inscrições / Candidaturas
        $totalEnrollments    = Enrollment::count();
        $pendingEnrollments  = Enrollment::where('status', 'Pendente')->count();
        $approvedEnrollments = Enrollment::where('status', 'Pago')->count();
        $enrolledStudents    = Enrollment::where('status', 'Matriculado')->count();
        $waitingEnrollments  = Enrollment::where('status', 'Lista de Espera')->count();

        // 3. Métricas Financeiras em Kwanzas (AOA)
        $totalPayments       = Payment::count();
        $confirmedPayments   = Payment::where('status', 'Confirmado')->count();
        $pendingPaymentsCount = Payment::where('status', 'Pendente')->count();
        $totalRevenue        = Payment::where('status', 'Confirmado')->sum('value');
        $pendingRevenue      = Payment::where('status', 'Pendente')->sum('value');

        // 4. Registos recentes para monitorização rápida
        $recentStudents = Student::with(['room.course', 'room.classroom', 'enrollment'])
            ->latest()
            ->take(6)
            ->get();

        $recentEnrollments = Enrollment::with(['course', 'room'])
            ->latest()
            ->take(8)
            ->get();

        $recentPayments = Payment::with(['enrollment.course'])
            ->where('status', 'Confirmado')
            ->latest()
            ->take(6)
            ->get();

        $recentTeachers = Teacher::withCount('rooms')
            ->latest()
            ->take(6)
            ->get();

        // 5. Agregações mensais para os gráficos analíticos (Últimos 6 meses)
        $monthsLabels         = [];
        $monthlyEnrollments   = [];
        $monthlyStudents      = [];
        $monthlyRevenue       = [];
        $monthlyPaymentsCount = [];

        $ptMonths = [
            1 => 'Jan', 2 => 'Fev', 3 => 'Mar', 4 => 'Abr', 5 => 'Mai', 6 => 'Jun',
            7 => 'Jul', 8 => 'Ago', 9 => 'Set', 10 => 'Out', 11 => 'Nov', 12 => 'Dez'
        ];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $year = $date->year;
            $month = $date->month;
            $label = $ptMonths[$month] . ' ' . substr($year, -2);
            $monthsLabels[] = $label;

            $monthlyEnrollments[] = Enrollment::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();

            $monthlyStudents[] = Student::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();

            $monthlyRevenue[] = Payment::where('status', 'Confirmado')
                ->whereYear('date', $year)
                ->whereMonth('date', $month)
                ->sum('value');

            $monthlyPaymentsCount[] = Payment::where('status', 'Confirmado')
                ->whereYear('date', $year)
                ->whereMonth('date', $month)
                ->count();
        }

        $totalEnrollmentsLast6Months = array_sum($monthlyEnrollments);
        $totalStudentsLast6Months    = array_sum($monthlyStudents);
        $totalRevenueLast6Months     = array_sum($monthlyRevenue);

        // 6. Distribuição das Turmas por Turno
        $shiftLabels = ['Manhã', 'Tarde', 'Pós-Laboral'];
        $shiftSeries = [
            Room::where('shift', 'Manhã')->count(),
            Room::where('shift', 'Tarde')->count(),
            Room::where('shift', 'Pós-Laboral')->count(),
        ];

        // 7. Top Cursos mais procurados (Top 5)
        $topCourses = Course::withCount(['enrollments', 'rooms'])
            ->orderByDesc('enrollments_count')
            ->take(5)
            ->get();

        $topCoursesLabels = $topCourses->pluck('name')->toArray();
        $topCoursesSeries = $topCourses->pluck('enrollments_count')->toArray();

        // 8. Objeto JSON consolidado para os scripts de gráficos
        $chartData = [
            'monthsLabels'         => $monthsLabels,
            'monthlyEnrollments'   => $monthlyEnrollments,
            'monthlyStudents'      => $monthlyStudents,
            'monthlyRevenue'       => $monthlyRevenue,
            'monthlyPaymentsCount' => $monthlyPaymentsCount,
            'shiftLabels'          => $shiftLabels,
            'shiftSeries'          => $shiftSeries,
            'topCoursesLabels'     => $topCoursesLabels,
            'topCoursesSeries'     => $topCoursesSeries,
        ];

        return view('admin.dashboard.index', compact(
            'totalStudents',
            'totalTeachers',
            'activeTeachers',
            'totalCourses',
            'activeCourses',
            'totalRooms',
            'openRooms',
            'inProgressRooms',
            'activeRooms',
            'totalClassrooms',
            'activeClassrooms',
            'totalCapacity',
            'totalEnrollments',
            'pendingEnrollments',
            'approvedEnrollments',
            'enrolledStudents',
            'waitingEnrollments',
            'totalPayments',
            'confirmedPayments',
            'pendingPaymentsCount',
            'totalRevenue',
            'pendingRevenue',
            'recentStudents',
            'recentEnrollments',
            'recentPayments',
            'recentTeachers',
            'monthsLabels',
            'monthlyEnrollments',
            'monthlyStudents',
            'monthlyRevenue',
            'monthlyPaymentsCount',
            'totalEnrollmentsLast6Months',
            'totalStudentsLast6Months',
            'totalRevenueLast6Months',
            'shiftLabels',
            'shiftSeries',
            'topCourses',
            'topCoursesLabels',
            'topCoursesSeries',
            'chartData'
        ));
    }
}

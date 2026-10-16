<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Course;
use App\Models\Room;
use App\Models\Classroom;
use App\Models\Enrollment;
use App\Models\Payment;
use Illuminate\Http\Request;

/**
 * Controlador DashboardController - Painel Principal de Indicadores do Centro de Formação.
 *
 * Responsável por reunir, consolidar e fornecer todos os dados analíticos, estatísticos
 * e operacionais do centro para apresentação no painel administrativo principal.
 *
 * Métricas Fornecidas:
 * - Total de formandos oficiais matriculados.
 * - Total de formadores docentes ativos.
 * - Total de cursos ministrados.
 * - Total de turmas ativas e abertas para candidaturas.
 * - Total de salas físicas disponíveis.
 * - Total de candidaturas / inscrições registadas.
 * - Volume de faturação global confirmado (receitas em Kwanzas).
 * - Listagens dos registos e pagamentos mais recentes para acompanhamento em tempo real.
 */
class DashboardController extends Controller
{
    /**
     * Apresenta o painel principal com indicadores consolidados do sistema.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // 1. Contagens quantitativas das entidades principais
        $totalStudents    = Student::count();
        $totalTeachers    = Teacher::count();
        $totalCourses     = Course::count();
        $activeCourses    = Course::where('status', true)->count();
        $totalRooms       = Room::count();
        $openRooms        = Room::where('status', 'Aberta')->count();
        $totalClassrooms  = Classroom::count();
        $totalEnrollments = Enrollment::count();

        // 2. Métricas financeiras em moeda nacional (AOA)
        $totalRevenue     = (float) Payment::where('status', 'Confirmado')->sum('value');
        $pendingRevenue   = (float) Payment::where('status', 'Pendente')->sum('value');

        // 3. Registos recentes para monitorização rápida da atividade
        $recentStudents = Student::with(['room.course', 'room.classroom'])
            ->latest()
            ->take(5)
            ->get();

        $recentEnrollments = Enrollment::with('course')
            ->latest()
            ->take(5)
            ->get();

        $recentPayments = Payment::with('enrollment.course')
            ->latest()
            ->take(5)
            ->get();

        // 4. Retorna a vista do painel principal passando todas as métricas consolidadas
        return view('admin.dashboard.index', compact(
            'totalStudents',
            'totalTeachers',
            'totalCourses',
            'activeCourses',
            'totalRooms',
            'openRooms',
            'totalClassrooms',
            'totalEnrollments',
            'totalRevenue',
            'pendingRevenue',
            'recentStudents',
            'recentEnrollments',
            'recentPayments'
        ));
    }
}

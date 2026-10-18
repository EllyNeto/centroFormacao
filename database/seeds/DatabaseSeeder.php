<?php

use Illuminate\Database\Seeder;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\Room;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Popula a base de dados do Centro de Formação com dados realistas de demonstração.
     *
     * Abrange todos os módulos:
     * 0. Utilizadores Administrativos (Users)
     * 1. Salas de Aula (Classrooms)
     * 2. Cursos Formativos (Courses) — incluindo cursos com turmas abertas e cursos para teste de Lista de Espera
     * 3. Formadores (Teachers)
     * 4. Turmas (Rooms)
     * 5. Candidaturas / Inscrições (Enrollments) — Matriculado, Pago, Pendente e Lista de Espera
     * 6. Pagamentos (Payments) — Confirmados e Pendentes distribuídos pelos meses para gráficos da Dashboard
     * 7. Formandos Oficiais (Students)
     *
     * @return void
     */
    public function run()
    {
        $year = (int) date('Y');

        // =====================================================================
        // 0. UTILIZADORES DE ACESSO AO SISTEMA (USERS)
        // =====================================================================
        User::updateOrCreate(
            ['email' => 'admin@centroformacao.ao'],
            [
                'name'     => 'Administrador Geral',
                'password' => Hash::make('12345678'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'secretaria@centroformacao.ao'],
            [
                'name'     => 'Secretaria Académica',
                'password' => Hash::make('12345678'),
            ]
        );

        // =====================================================================
        // 1. SALAS DE AULA FÍSICAS (CLASSROOMS)
        // =====================================================================
        $classroomsData = [
            [
                'number_of_classroom' => 101,
                'capacity'            => 20,
                'description'         => 'Laboratório de Informática I — 20 Computadores i7, Projetor Full HD e Quadro Interativo.',
            ],
            [
                'number_of_classroom' => 102,
                'capacity'            => 25,
                'description'         => 'Laboratório de Redes & Cibersegurança — Racks Cisco, Switches, Routers e Bancada Técnica.',
            ],
            [
                'number_of_classroom' => 103,
                'capacity'            => 18,
                'description'         => 'Laboratório de Eletrónica e CCTV — Câmaras IP, Gravadores NVR/DVR e Painéis de Alarme.',
            ],
            [
                'number_of_classroom' => 201,
                'capacity'            => 30,
                'description'         => 'Sala Executiva de Gestão e Finanças — Climatizada, Som Ambiente e Ecrã LED 75".',
            ],
            [
                'number_of_classroom' => 202,
                'capacity'            => 22,
                'description'         => 'Laboratório de Informática II — Estações de Trabalho para Ciência de Dados e BI.',
            ],
            [
                'number_of_classroom' => 203,
                'capacity'            => 15,
                'description'         => 'Sala Polivalente de Formação Pedagógica e Certificações.',
            ],
        ];

        $classrooms = [];
        foreach ($classroomsData as $cData) {
            $classrooms[$cData['number_of_classroom']] = Classroom::updateOrCreate(
                ['number_of_classroom' => $cData['number_of_classroom']],
                $cData
            );
        }

        // =====================================================================
        // 2. CURSOS FORMATIVOS (COURSES)
        // =====================================================================
        $coursesData = [
            'web' => [
                'name'        => 'Desenvolvimento Web Full-Stack (Laravel & Vue.js)',
                'description' => 'Formação prática em engenharia de software web moderna com PHP/Laravel, APIs REST, bases de dados MySQL e interfaces reativas.',
                'duration'    => 120,
                'status'      => true,
                'value'       => 95000.00,
            ],
            'ccna' => [
                'name'        => 'Administração de Redes e Infraestrutura Cisco (CCNA)',
                'description' => 'Planeamento, configuração, roteamento, switching e resolução de problemas em redes empresariais.',
                'duration'    => 90,
                'status'      => true,
                'value'       => 110000.00,
            ],
            'cyber' => [
                'name'        => 'Cibersegurança e Ethical Hacking',
                'description' => 'Análise de vulnerabilidades, testes de intrusão (Pentesting), defesa de perímetro e resposta a incidentes.',
                'duration'    => 80,
                'status'      => true,
                'value'       => 135000.00,
            ],
            'contab' => [
                'name'        => 'Contabilidade Geral e Gestão Financeira (PGC Angola)',
                'description' => 'Domínio do Plano Geral de Contabilidade Angolano, fecho de contas, fiscalidade e relatórios financeiros.',
                'duration'    => 60,
                'status'      => true,
                'value'       => 65000.00,
            ],
            'cctv' => [
                'name'        => 'Sistemas de Videovigilância (CCTV) e Controlo de Acesso',
                'description' => 'Instalação e configuração prática de câmaras analógicas e IP, gravadores DVR/NVR, acesso remoto e biometria.',
                'duration'    => 45,
                'status'      => true,
                'value'       => 75000.00,
            ],
            'powerbi' => [
                'name'        => 'Análise de Dados e Business Intelligence com Power BI',
                'description' => 'Transformação de dados com Power Query, modelação DAX e construção de dashboards executivos.',
                'duration'    => 60,
                'status'      => true,
                'value'       => 85000.00,
            ],
            // Cursos SEM turmas abertas inicialmente (para demonstrar Lista de Espera e alocação automática!)
            'python_ai' => [
                'name'        => 'Inteligência Artificial e Ciência de Dados com Python',
                'description' => 'Programação em Python, Machine Learning, análise preditiva e automação inteligente de processos.',
                'duration'    => 100,
                'status'      => true,
                'value'       => 150000.00,
            ],
            'scrum' => [
                'name'        => 'Gestão de Projetos Ágeis (Scrum & PMP)',
                'description' => 'Metodologias ágeis e tradicionais para planeamento, execução e controlo de projetos corporativos.',
                'duration'    => 50,
                'status'      => true,
                'value'       => 80000.00,
            ],
        ];

        $courses = [];
        foreach ($coursesData as $key => $courseItem) {
            $courses[$key] = Course::updateOrCreate(
                ['name' => $courseItem['name']],
                $courseItem
            );
        }

        // =====================================================================
        // 3. FORMADORES (TEACHERS)
        // =====================================================================
        $teachersData = [
            'eng_carlos' => [
                'name'               => 'Eng.º Carlos Alberto Mateus',
                'email'              => 'carlos.mateus@centroformacao.ao',
                'gender'             => 'Masculino',
                'specialization'     => 'Engenharia de Software e Desenvolvimento Web',
                'number_of_identify' => '004512389LA041',
                'phone'              => '923 410 112',
            ],
            'eng_helena' => [
                'name'               => 'Eng.ª Helena Domingas Sebastião',
                'email'              => 'helena.sebastiao@centroformacao.ao',
                'gender'             => 'Feminino',
                'specialization'     => 'Redes de Computadores e Cibersegurança',
                'number_of_identify' => '005198234LA042',
                'phone'              => '924 550 223',
            ],
            'dr_manuel' => [
                'name'               => 'Dr. Manuel Joaquim Baptista',
                'email'              => 'manuel.baptista@centroformacao.ao',
                'gender'             => 'Masculino',
                'specialization'     => 'Contabilidade, Auditoria e Finanças Empresariais',
                'number_of_identify' => '003876512LA038',
                'phone'              => '931 220 334',
            ],
            'eng_paulo' => [
                'name'               => 'Eng.º Paulo Jorge Kiala',
                'email'              => 'paulo.kiala@centroformacao.ao',
                'gender'             => 'Masculino',
                'specialization'     => 'Sistemas Eletrónicos, CCTV e Automação',
                'number_of_identify' => '006234890LA045',
                'phone'              => '926 778 445',
            ],
            'dra_marcia' => [
                'name'               => 'Dra. Márcia Lukénia Neto',
                'email'              => 'marcia.neto@centroformacao.ao',
                'gender'             => 'Feminino',
                'specialization'     => 'Business Intelligence, Ciência de Dados e Gestão',
                'number_of_identify' => '005891234LA049',
                'phone'              => '929 881 556',
            ],
        ];

        $teachers = [];
        foreach ($teachersData as $key => $tData) {
            $existingTeacher = Teacher::withTrashed()
                ->where('email', $tData['email'])
                ->orWhere('number_of_identify', $tData['number_of_identify'])
                ->first();

            if ($existingTeacher) {
                if ($existingTeacher->trashed()) {
                    $existingTeacher->restore();
                }
                $existingTeacher->update($tData);
                $teachers[$key] = $existingTeacher;
            } else {
                $teachers[$key] = Teacher::create($tData);
            }
        }

        // =====================================================================
        // 4. TURMAS PEDAGÓGICAS (ROOMS)
        // =====================================================================
        $roomsData = [
            'turma_web' => [
                'name'         => 'Turma Web Full-Stack — Manhã A',
                'course_id'    => $courses['web']->id,
                'teacher_id'   => $teachers['eng_carlos']->id,
                'classroom_id' => $classrooms[101]->id,
                'max_capacity' => $classrooms[101]->capacity,
                'shift'        => 'Manhã',
                'days_of_week' => ['Segunda', 'Quarta', 'Sexta'],
                'start_time'   => '08:00:00',
                'end_time'     => '11:00:00',
                'start_date'   => Carbon::now()->subDays(15)->format('Y-m-d'),
                'end_date'     => Carbon::now()->addDays(45)->format('Y-m-d'),
                'status'       => 'Aberta',
            ],
            'turma_ccna' => [
                'name'         => 'Turma Redes Cisco CCNA — Tarde A',
                'course_id'    => $courses['ccna']->id,
                'teacher_id'   => $teachers['eng_helena']->id,
                'classroom_id' => $classrooms[102]->id,
                'max_capacity' => $classrooms[102]->capacity,
                'shift'        => 'Tarde',
                'days_of_week' => ['Terça', 'Quinta'],
                'start_time'   => '14:00:00',
                'end_time'     => '17:00:00',
                'start_date'   => Carbon::now()->subDays(10)->format('Y-m-d'),
                'end_date'     => Carbon::now()->addDays(50)->format('Y-m-d'),
                'status'       => 'Aberta',
            ],
            'turma_cyber' => [
                'name'         => 'Turma Cibersegurança — Pós-Laboral',
                'course_id'    => $courses['cyber']->id,
                'teacher_id'   => $teachers['eng_helena']->id,
                'classroom_id' => $classrooms[102]->id,
                'max_capacity' => $classrooms[102]->capacity,
                'shift'        => 'Pós-Laboral',
                'days_of_week' => ['Segunda', 'Quarta', 'Sexta'],
                'start_time'   => '18:00:00',
                'end_time'     => '20:30:00',
                'start_date'   => Carbon::now()->subDays(5)->format('Y-m-d'),
                'end_date'     => Carbon::now()->addDays(55)->format('Y-m-d'),
                'status'       => 'Aberta',
            ],
            'turma_contab' => [
                'name'         => 'Turma Contabilidade PGC — Manhã',
                'course_id'    => $courses['contab']->id,
                'teacher_id'   => $teachers['dr_manuel']->id,
                'classroom_id' => $classrooms[201]->id,
                'max_capacity' => $classrooms[201]->capacity,
                'shift'        => 'Manhã',
                'days_of_week' => ['Terça', 'Quinta'],
                'start_time'   => '08:30:00',
                'end_time'     => '11:30:00',
                'start_date'   => Carbon::now()->subDays(7)->format('Y-m-d'),
                'end_date'     => Carbon::now()->addDays(40)->format('Y-m-d'),
                'status'       => 'Aberta',
            ],
            'turma_cctv' => [
                'name'         => 'Turma CCTV e Controlo de Acesso — Tarde',
                'course_id'    => $courses['cctv']->id,
                'teacher_id'   => $teachers['eng_paulo']->id,
                'classroom_id' => $classrooms[103]->id,
                'max_capacity' => $classrooms[103]->capacity,
                'shift'        => 'Tarde',
                'days_of_week' => ['Segunda', 'Quarta', 'Sexta'],
                'start_time'   => '14:00:00',
                'end_time'     => '16:30:00',
                'start_date'   => Carbon::now()->subDays(3)->format('Y-m-d'),
                'end_date'     => Carbon::now()->addDays(35)->format('Y-m-d'),
                'status'       => 'Aberta',
            ],
            'turma_powerbi_concluida' => [
                'name'         => 'Turma Power BI Executivo — 1.ª Edição',
                'course_id'    => $courses['powerbi']->id,
                'teacher_id'   => $teachers['dra_marcia']->id,
                'classroom_id' => $classrooms[202]->id,
                'max_capacity' => $classrooms[202]->capacity,
                'shift'        => 'Manhã',
                'days_of_week' => ['Segunda', 'Quarta', 'Sexta'],
                'start_time'   => '08:30:00',
                'end_time'     => '11:30:00',
                'start_date'   => Carbon::now()->subDays(75)->format('Y-m-d'),
                'end_date'     => Carbon::now()->subDays(15)->format('Y-m-d'),
                'status'       => 'Concluída',
            ],
        ];

        $rooms = [];
        foreach ($roomsData as $key => $rData) {
            $rooms[$key] = Room::updateOrCreate(
                ['name' => $rData['name']],
                $rData
            );
        }

        // =====================================================================
        // 5. INSCRIÇÕES / CANDIDATURAS, PAGAMENTOS E FORMANDOS OFICIAIS
        // =====================================================================
        $candidates = [
            // --- FORMANDOS JÁ MATRICULADOS (Pago + Student criado) ---
            [
                'name'               => 'António Manuel Kahombo',
                'email'              => 'antonio.kahombo@email.ao',
                'phone'              => '923 111 221',
                'number_of_identify' => '007111222LA041',
                'course_key'         => 'web',
                'room_key'           => 'turma_web',
                'shift'              => 'Manhã',
                'status'             => 'Matriculado',
                'payment_status'     => 'Confirmado',
                'payment_type'       => 'Multicaixa',
                'payment_month'      => max(1, (int) date('n') - 2),
                'create_student'     => true,
            ],
            [
                'name'               => 'Esperança Nzinga Domingos',
                'email'              => 'esperanca.domingos@email.ao',
                'phone'              => '924 222 332',
                'number_of_identify' => '007222333LA042',
                'course_key'         => 'web',
                'room_key'           => 'turma_web',
                'shift'              => 'Manhã',
                'status'             => 'Matriculado',
                'payment_status'     => 'Confirmado',
                'payment_type'       => 'Transferência',
                'payment_month'      => max(1, (int) date('n') - 1),
                'create_student'     => true,
            ],
            [
                'name'               => 'João Baptista Lourenço',
                'email'              => 'joao.lourenco@email.ao',
                'phone'              => '931 333 443',
                'number_of_identify' => '007333444LA043',
                'course_key'         => 'ccna',
                'room_key'           => 'turma_ccna',
                'shift'              => 'Tarde',
                'status'             => 'Matriculado',
                'payment_status'     => 'Confirmado',
                'payment_type'       => 'Transferência',
                'payment_month'      => max(1, (int) date('n') - 1),
                'create_student'     => true,
            ],
            [
                'name'               => 'Cláudia Sofia Van-Dúnem',
                'email'              => 'claudia.vandunem@email.ao',
                'phone'              => '925 444 554',
                'number_of_identify' => '007444555LA044',
                'course_key'         => 'cyber',
                'room_key'           => 'turma_cyber',
                'shift'              => 'Pós-Laboral',
                'status'             => 'Matriculado',
                'payment_status'     => 'Confirmado',
                'payment_type'       => 'Multicaixa',
                'payment_month'      => (int) date('n'),
                'create_student'     => true,
            ],
            [
                'name'               => 'Osvaldo Francisco Gourgel',
                'email'              => 'osvaldo.gourgel@email.ao',
                'phone'              => '926 555 665',
                'number_of_identify' => '007555666LA045',
                'course_key'         => 'contab',
                'room_key'           => 'turma_contab',
                'shift'              => 'Manhã',
                'status'             => 'Matriculado',
                'payment_status'     => 'Confirmado',
                'payment_type'       => 'Numerário',
                'payment_month'      => (int) date('n'),
                'create_student'     => true,
            ],

            // --- CANDIDATURAS COM ESTADO 'PAGO' (prontas para Efetivar Matrícula!) ---
            [
                'name'               => 'Edmilson Pedro Cassoma',
                'email'              => 'edmilson.cassoma@email.ao',
                'phone'              => '927 666 776',
                'number_of_identify' => '007666777LA046',
                'course_key'         => 'cctv',
                'room_key'           => 'turma_cctv',
                'shift'              => 'Tarde',
                'status'             => 'Pago',
                'payment_status'     => 'Confirmado',
                'payment_type'       => 'Multicaixa',
                'payment_month'      => (int) date('n'),
                'create_student'     => false,
            ],
            [
                'name'               => 'Teresa Margaridaiala Chipenda',
                'email'              => 'teresa.chipenda@email.ao',
                'phone'              => '928 777 887',
                'number_of_identify' => '007777888LA047',
                'course_key'         => 'web',
                'room_key'           => 'turma_web',
                'shift'              => 'Manhã',
                'status'             => 'Pago',
                'payment_status'     => 'Confirmado',
                'payment_type'       => 'Transferência',
                'payment_month'      => (int) date('n'),
                'create_student'     => false,
            ],

            // --- CANDIDATURAS 'PENDENTE' (prontas para Registar Pagamento!) ---
            [
                'name'               => 'Domingos SavimbiLutero',
                'email'              => 'domingos.lutero@email.ao',
                'phone'              => '929 888 998',
                'number_of_identify' => '007888999LA048',
                'course_key'         => 'ccna',
                'room_key'           => 'turma_ccna',
                'shift'              => 'Tarde',
                'status'             => 'Pendente',
                'payment_status'     => null,
                'payment_type'       => null,
                'payment_month'      => null,
                'create_student'     => false,
            ],
            [
                'name'               => 'Neusa Patrícia Muhongo',
                'email'              => 'neusa.muhongo@email.ao',
                'phone'              => '921 999 109',
                'number_of_identify' => '007999111LA049',
                'course_key'         => 'cctv',
                'room_key'           => 'turma_cctv',
                'shift'              => 'Tarde',
                'status'             => 'Pendente',
                'payment_status'     => null,
                'payment_type'       => null,
                'payment_month'      => null,
                'create_student'     => false,
            ],
            [
                'name'               => 'Hélder Gaspar Muteka',
                'email'              => 'helder.muteka@email.ao',
                'phone'              => '922 101 212',
                'number_of_identify' => '008101212LA050',
                'course_key'         => 'cyber',
                'room_key'           => 'turma_cyber',
                'shift'              => 'Pós-Laboral',
                'status'             => 'Pendente',
                'payment_status'     => null,
                'payment_type'       => null,
                'payment_month'      => null,
                'create_student'     => false,
            ],

            // --- CANDIDATURAS EM 'LISTA DE ESPERA' (Cursos ainda sem turma aberta!) ---
            // Quando o utilizador criar uma turma para "Inteligência Artificial e Ciência de Dados com Python"
            // ou "Gestão de Projetos Ágeis (Scrum & PMP)", estes alunos serão adicionados AUTOMATICAMENTE!
            [
                'name'               => 'Gelson MaurícioQuintas',
                'email'              => 'gelson.quintas@email.ao',
                'phone'              => '923 303 404',
                'number_of_identify' => '008303404LA051',
                'course_key'         => 'python_ai',
                'room_key'           => null,
                'shift'              => 'Manhã',
                'status'             => 'Lista de Espera',
                'payment_status'     => null,
                'payment_type'       => null,
                'payment_month'      => null,
                'create_student'     => false,
            ],
            [
                'name'               => 'Yola Tatiana Cambinda',
                'email'              => 'yola.cambinda@email.ao',
                'phone'              => '924 505 606',
                'number_of_identify' => '008505606LA052',
                'course_key'         => 'python_ai',
                'room_key'           => null,
                'shift'              => 'Manhã',
                'status'             => 'Lista de Espera',
                'payment_status'     => null,
                'payment_type'       => null,
                'payment_month'      => null,
                'create_student'     => false,
            ],
            [
                'name'               => 'Benvindo Adão Cazenga',
                'email'              => 'benvindo.cazenga@email.ao',
                'phone'              => '935 707 808',
                'number_of_identify' => '008707808LA053',
                'course_key'         => 'scrum',
                'room_key'           => null,
                'shift'              => 'Pós-Laboral',
                'status'             => 'Lista de Espera',
                'payment_status'     => null,
                'payment_type'       => null,
                'payment_month'      => null,
                'create_student'     => false,
            ],
        ];

        $refCounter = (int) Payment::withTrashed()->max('id') + 1;

        foreach ($candidates as $item) {
            $course = $courses[$item['course_key']];
            $room   = $item['room_key'] ? $rooms[$item['room_key']] : null;

            $enrollment = Enrollment::updateOrCreate(
                [
                    'number_of_identify' => $item['number_of_identify'],
                    'course_id'          => $course->id,
                ],
                [
                    'name'   => $item['name'],
                    'email'  => $item['email'],
                    'phone'  => $item['phone'],
                    'room_id'=> $room ? $room->id : null,
                    'shift'  => $item['shift'],
                    'status' => $item['status'],
                    'cycle'  => 1,
                    'date'   => Carbon::now()->subDays(rand(2, 30)),
                ]
            );

            // Se tiver pagamento associado, cria ou atualiza o registo financeiro garantindo referência única
            if ($item['payment_status']) {
                $existingPayment = Payment::withTrashed()
                    ->where('enrollment_id', $enrollment->id)
                    ->where('cycle', 1)
                    ->first();

                if ($existingPayment) {
                    if ($existingPayment->trashed()) {
                        $existingPayment->restore();
                    }
                    $refCode = $existingPayment->reference;
                } else {
                    do {
                        $refCode = 'REC-' . $year . '-' . str_pad($refCounter++, 5, '0', STR_PAD_LEFT);
                    } while (Payment::withTrashed()->where('reference', $refCode)->exists());
                }

                $paymentDate = Carbon::create($year, $item['payment_month'], min(28, rand(5, 25)), rand(8, 17), rand(0, 59));

                Payment::updateOrCreate(
                    [
                        'enrollment_id' => $enrollment->id,
                        'cycle'         => 1,
                    ],
                    [
                        'type_of_payment' => $item['payment_type'],
                        'value'           => (float) $course->value,
                        'currency'        => 'AOA',
                        'reference'       => $refCode,
                        'status'          => $item['payment_status'],
                        'date'            => $paymentDate,
                    ]
                );
            }

            // Se for um formando oficial matriculado, cria o registo na tabela students
            if ($item['create_student'] && $room) {
                Student::updateOrCreate(
                    ['enrollment_id' => $enrollment->id],
                    [
                        'room_id'            => $room->id,
                        'name'               => $enrollment->name,
                        'email'              => $enrollment->email,
                        'number_of_identify' => $enrollment->number_of_identify,
                        'phone'              => $enrollment->phone,
                        'image'              => $enrollment->image,
                    ]
                );
            }
        }
    }
}


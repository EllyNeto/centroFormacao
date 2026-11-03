<?php $__env->startSection('title', 'Painel de Controlo'); ?>

<?php $__env->startSection('content'); ?>
<div class="content-body">
    <div class="container-fluid">
        <?php if(session('success')): ?>
            <?php if (isset($component)) { $__componentOriginalc5711d836f933e61eafca8928e9a27a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc5711d836f933e61eafca8928e9a27a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.alerts','data' => ['type' => 'success','message' => ''.e(session('success')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('alerts'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'success','message' => ''.e(session('success')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc5711d836f933e61eafca8928e9a27a5)): ?>
<?php $attributes = $__attributesOriginalc5711d836f933e61eafca8928e9a27a5; ?>
<?php unset($__attributesOriginalc5711d836f933e61eafca8928e9a27a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc5711d836f933e61eafca8928e9a27a5)): ?>
<?php $component = $__componentOriginalc5711d836f933e61eafca8928e9a27a5; ?>
<?php unset($__componentOriginalc5711d836f933e61eafca8928e9a27a5); ?>
<?php endif; ?>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <?php if (isset($component)) { $__componentOriginalc5711d836f933e61eafca8928e9a27a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc5711d836f933e61eafca8928e9a27a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.alerts','data' => ['type' => 'danger','message' => ''.e(session('error')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('alerts'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'danger','message' => ''.e(session('error')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc5711d836f933e61eafca8928e9a27a5)): ?>
<?php $attributes = $__attributesOriginalc5711d836f933e61eafca8928e9a27a5; ?>
<?php unset($__attributesOriginalc5711d836f933e61eafca8928e9a27a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc5711d836f933e61eafca8928e9a27a5)): ?>
<?php $component = $__componentOriginalc5711d836f933e61eafca8928e9a27a5; ?>
<?php unset($__componentOriginalc5711d836f933e61eafca8928e9a27a5); ?>
<?php endif; ?>
        <?php endif; ?>

        
        <div id="dashboardAnalyticsData" class="d-none" data-chart='<?php echo json_encode($chartData, 15, 512) ?>'></div>

        
        
             

        
        <div class="row g-3 mb-4">
            
            <div class="col-xl-3 col-sm-6">
                <div class="cf-kpi-card h-100">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="cf-kpi-label">Receita Liquidada</span>
                            <h3 class="cf-kpi-value text-primary mb-0">
                                <?php echo e(number_format($totalRevenue, 2, ',', '.')); ?> <small class="fs-6">Kz</small>
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
                            <strong><?php echo e($confirmedPayments); ?></strong> confirmados
                        </span>
                        <?php if($pendingPaymentsCount > 0): ?>
                            <span class="badge light badge-warning fs-8"><?php echo e($pendingPaymentsCount); ?> pendente(s)</span>
                        <?php else: ?>
                            <a href="<?php echo e(route('payment.index')); ?>" class="cf-kpi-link">Ver recibos <i class="fa fa-angle-right"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            
            <div class="col-xl-3 col-sm-6">
                <div class="cf-kpi-card h-100">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="cf-kpi-label">Formandos Ativos</span>
                            <h3 class="cf-kpi-value mb-0"><?php echo e($totalStudents); ?></h3>
                        </div>
                        <div class="cf-kpi-icon cf-kpi-icon-success" aria-hidden="true">
                            <svg class="cf-kpi-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                                <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                            </svg>
                        </div>
                    </div>
                    <?php
                        $capacityOccupancy = $totalCapacity > 0 ? min(100, round(($totalStudents / $totalCapacity) * 100, 1)) : 0;
                    ?>
                    <div class="cf-kpi-footer d-flex justify-content-between align-items-center pt-2 border-top">
                        <span class="text-muted fs-8">
                            Lotação global: <strong><?php echo e($capacityOccupancy); ?>%</strong> (<?php echo e($totalCapacity); ?> vagas)
                        </span>
                        <a href="<?php echo e(route('student.index')); ?>" class="cf-kpi-link">Listar <i class="fa fa-angle-right"></i></a>
                    </div>
                </div>
            </div>

            
            <div class="col-xl-3 col-sm-6">
                <div class="cf-kpi-card h-100">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="cf-kpi-label">Total de Inscrições</span>
                            <h3 class="cf-kpi-value mb-0"><?php echo e($totalEnrollments); ?></h3>
                        </div>
                        <div class="cf-kpi-icon cf-kpi-icon-info" aria-hidden="true">
                            <svg class="cf-kpi-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                <path d="m9 14 2 2 4-4"></path>
                            </svg>
                        </div>
                    </div>
                    <?php
                        $conversionRate = $totalEnrollments > 0 ? round(($enrolledStudents / $totalEnrollments) * 100, 1) : 0;
                    ?>
                    <div class="cf-kpi-footer d-flex justify-content-between align-items-center pt-2 border-top">
                        <span class="text-muted fs-8">
                            Conversão: <strong class="text-success"><?php echo e($conversionRate); ?>%</strong> matriculados
                        </span>
                        <a href="<?php echo e(route('enrollment.index')); ?>" class="cf-kpi-link">Gerir <i class="fa fa-angle-right"></i></a>
                    </div>
                </div>
            </div>

            
            <div class="col-xl-3 col-sm-6">
                <div class="cf-kpi-card h-100">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="cf-kpi-label">Turmas em Funcionamento</span>
                            <h3 class="cf-kpi-value mb-0">
                                <?php echo e($activeRooms); ?> <small class="text-muted fs-6 font-w500">/ <?php echo e($totalRooms); ?></small>
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
                            <strong><?php echo e($activeCourses); ?></strong> cursos &bull; <strong><?php echo e($activeTeachers); ?></strong> formadores
                        </span>
                        <a href="<?php echo e(route('room.index')); ?>" class="cf-kpi-link">Turmas <i class="fa fa-angle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-sm-6 col-12">
                <a href="<?php echo e(route('classroom.index')); ?>" class="cf-mini-stat-card">
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
                        <strong class="cf-mini-stat-num"><?php echo e($activeClassrooms); ?> ativas de <?php echo e($totalClassrooms); ?></strong>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <a href="<?php echo e(route('course.index')); ?>" class="cf-mini-stat-card">
                    <div class="cf-mini-stat-icon bg-info-subtle text-info" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                        </svg>
                    </div>
                    <div class="cf-mini-stat-body">
                        <span class="cf-mini-stat-title">Oferta Formativa</span>
                        <strong class="cf-mini-stat-num"><?php echo e($activeCourses); ?> cursos ativos (<?php echo e($totalCourses); ?> total)</strong>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <a href="<?php echo e(route('teacher.index')); ?>" class="cf-mini-stat-card">
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
                        <strong class="cf-mini-stat-num"><?php echo e($totalTeachers); ?> formadores (<?php echo e($activeTeachers); ?> c/ turma)</strong>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <a href="<?php echo e(route('enrollment.index')); ?>" class="cf-mini-stat-card">
                    <div class="cf-mini-stat-icon bg-warning-subtle text-warning" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                        </svg>
                    </div>
                    <div class="cf-mini-stat-body">
                        <span class="cf-mini-stat-title">Funil de Admissão</span>
                        <strong class="cf-mini-stat-num"><?php echo e($pendingEnrollments); ?> Pend. &bull; <?php echo e($approvedEnrollments); ?> Pagos &bull; <?php echo e($enrolledStudents); ?> Matr.</strong>
                    </div>
                </a>
            </div>
        </div>

        
        <div class="row g-4 mb-4">
            
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
                                <strong class="text-primary"><?php echo e($totalEnrollmentsLast6Months); ?></strong>
                            </div>
                            <div class="cf-chart-summary-pill">
                                <small class="text-muted d-block">Matrículas (6M)</small>
                                <strong class="text-success"><?php echo e($totalStudentsLast6Months); ?></strong>
                            </div>
                            <div class="cf-chart-summary-pill">
                                <small class="text-muted d-block">Receita (6M)</small>
                                <strong class="text-dark"><?php echo e(number_format($totalRevenueLast6Months, 0, ',', '.')); ?> Kz</strong>
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

            
            <div class="col-xxl-4 col-xl-5 col-12">
                <div class="card cf-dashboard-card h-100 mb-0">
                    <div class="card-header">
                        <h5 class="card-title mb-1">Cursos Mais Procurados &amp; Turnos</h5>
                        <p class="subtitle text-muted mb-0">Top 5 cursos por volume de candidaturas</p>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between">
                        
                        <div class="mb-4">
                            <?php
                                $maxCourseEnrollments = $topCourses->max('enrollments_count') ?: 1;
                            ?>
                            <?php $__empty_1 = true; $__currentLoopData = $topCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    $pct = round(($course->enrollments_count / $maxCourseEnrollments) * 100);
                                ?>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center gap-2 mb-1">
                                        <a href="<?php echo e(route('course.show', $course->id)); ?>" class="font-w600 text-dark fs-7 text-truncate">
                                            <?php echo e($course->name); ?>

                                        </a>
                                        <span class="badge light badge-primary fs-8 flex-shrink-0">
                                            <?php echo e($course->enrollments_count); ?> <?php echo e($course->enrollments_count == 1 ? 'inscrição' : 'inscrições'); ?>

                                        </span>
                                    </div>
                                    <div class="progress room-progress-track">
                                        <div class="progress-bar bg-primary room-progress-fill" role="progressbar" style="width: <?php echo e($pct); ?>%" aria-valuenow="<?php echo e($pct); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <p class="text-muted fs-7 text-center py-3 mb-0">Ainda não existem cursos registados.</p>
                            <?php endif; ?>
                        </div>

                        
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

        
        <div class="row g-4">
            
            <div class="col-xl-6 col-12">
                <div class="card cf-dashboard-card h-100 mb-0">
                    <div class="card-header flex-wrap d-flex justify-content-between align-items-center gap-2">
                        <div>
                            <h5 class="card-title mb-0">Últimas Inscrições</h5>
                        </div>
                        <a href="<?php echo e(route('enrollment.index')); ?>" class="btn btn-outline-primary btn-xs flex-shrink-0">
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
                                    <?php $__empty_1 = true; $__currentLoopData = $recentEnrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td class="ps-4" data-label="Candidato">
                                                <a href="<?php echo e(route('enrollment.show', $enrollment->id)); ?>" class="font-w600 text-dark d-block text-truncate cf-table-cell-text">
                                                    <?php echo e($enrollment->name); ?>

                                                </a>
                                            </td>
                                            <td data-label="Curso">
                                                <span class="text-muted d-block text-truncate cf-table-cell-text">
                                                    <?php echo e($enrollment->course->name ?? 'N/D'); ?>

                                                </span>
                                            </td>
                                            <td class="text-end pe-4" data-label="Estado">
                                                <span class="badge light <?php echo e($enrollment->statusBadgeClass()); ?>">
                                                    <?php echo e($enrollment->status); ?>

                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="3" class="text-center py-4 text-muted">
                                                Nenhuma inscrição registada recentemente.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-xl-6 col-12">
                <div class="card cf-dashboard-card h-100 mb-0">
                    <div class="card-header flex-wrap d-flex justify-content-between align-items-center gap-2">
                        <div>
                            <h5 class="card-title mb-0">Últimos Pagamentos</h5>
                        </div>
                        <a href="<?php echo e(route('payment.index')); ?>" class="btn btn-outline-primary btn-xs flex-shrink-0">
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
                                    <?php $__empty_1 = true; $__currentLoopData = $recentPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td class="ps-4 text-nowrap" data-label="Recibo">
                                                <a href="<?php echo e(route('payment.show', $payment->id)); ?>" class="font-w600 text-primary">
                                                    <?php echo e($payment->reference); ?>

                                                </a>
                                            </td>
                                            <td data-label="Candidato">
                                                <span class="text-dark d-block text-truncate cf-table-cell-text">
                                                    <?php echo e($payment->enrollment->name ?? 'N/D'); ?>

                                                </span>
                                            </td>
                                            <td class="text-end pe-4 text-nowrap" data-label="Valor">
                                                <strong class="text-success"><?php echo e($payment->formattedValue()); ?></strong>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="3" class="text-center py-4 text-muted">
                                                Nenhum pagamento confirmado recentemente.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/eneto/Documents/infosi intership/projects/centroFormacao/resources/views/admin/dashboard/index.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'Ficha do Formando'); ?>

<?php $__env->startSection('content'); ?>

<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    
                    <div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center border-bottom">
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <h4 class="card-title mb-0 text-dark">Ficha do Formando: <?php echo e($student->name); ?></h4>
                                <span class="badge light badge-primary font-w600 fs-7">
                                    <?php echo e($student->formattedCode()); ?>

                                </span>
                            </div>
                            <p class="m-0 subtitle text-muted">Acompanhamento cadastral, académico e histórico financeiro do aluno oficial</p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <?php if($student->room_id): ?>
                                <a href="<?php echo e(route('room.printStudents', $student->room_id)); ?>" class="btn btn-outline-primary btn-sm" target="_blank">
                                    <i class="fa fa-print me-1"></i> Imprimir Pauta
                                </a>
                            <?php endif; ?>
                            <a href="<?php echo e(route('student.edit', $student->id)); ?>" class="btn btn-warning btn-sm">
                                <i class="fa fa-pencil me-1"></i> Editar Dados
                            </a>
                            <a href="<?php echo e(route('student.index')); ?>" class="btn btn-secondary btn-sm">
                                <i class="fa fa-arrow-left me-1"></i> Voltar
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        
                        <div class="p-4 mb-4 rounded border bg-light shadow-sm">
                            <div class="row align-items-center">
                                <div class="col-md-7 d-flex align-items-center mb-3 mb-md-0">
                                    <img src="<?php echo e($student->avatarUrl()); ?>"
                                         class="rounded-circle avatar avatar-xl me-3 border border-3 border-white shadow-sm"
                                         alt="<?php echo e($student->name); ?>"
                                         style="width: 80px; height: 80px; object-fit: cover;">
                                    <div>
                                        <h4 class="mb-1 text-dark font-w700"><?php echo e($student->name); ?></h4>
                                        <div class="text-muted fs-7">
                                            <span class="me-3"><i class="fa fa-id-card text-primary me-1"></i>BI: <strong><?php echo e($student->number_of_identify); ?></strong></span>
                                            <span class="me-3"><i class="fa fa-phone text-success me-1"></i><?php echo e($student->phone); ?></span>
                                            <span><i class="fa fa-envelope text-info me-1"></i><?php echo e($student->email); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 text-md-end">
                                    <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Estado da Matrícula</small>
                                    <span class="badge light badge-success fs-6 px-3 py-2">
                                        <i class="fa fa-check-circle me-1"></i> Matriculado Oficialmente
                                    </span>
                                </div>
                            </div>
                        </div>

                        
                        <div class="row g-4">
                            
                            <div class="col-md-6">
                                <div class="p-4 border rounded bg-white h-100 shadow-sm">
                                    <h5 class="font-w600 mb-3 text-dark border-bottom pb-2">
                                        <i class="fa fa-user text-muted me-2"></i>1. Dados Pessoais e Identificação
                                    </h5>
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Nome Completo</small>
                                            <span class="fs-6 font-w600 text-dark"><?php echo e($student->name); ?></span>
                                        </div>
                                        <div class="col-sm-6">
                                            <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Código de Aluno</small>
                                            <span class="fs-6 font-w600 text-primary"><?php echo e($student->formattedCode()); ?></span>
                                        </div>
                                        <div class="col-sm-6">
                                            <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Nº do Bilhete de Identidade</small>
                                            <span class="fs-6 font-w600 text-dark"><?php echo e($student->number_of_identify); ?></span>
                                        </div>
                                        <div class="col-sm-6">
                                            <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Telefone Principal</small>
                                            <span class="fs-6 font-w500 text-dark"><?php echo e($student->phone); ?></span>
                                        </div>
                                        <div class="col-sm-6">
                                            <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Correio Eletrónico</small>
                                            <span class="fs-6 font-w500 text-dark"><?php echo e($student->email); ?></span>
                                        </div>
                                        <div class="col-sm-6">
                                            <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Data de Matrícula</small>
                                            <span class="fs-6 text-dark">
                                                <?php echo e($student->created_at ? $student->created_at->format('d/m/Y \à\s H:i') : '-'); ?>

                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="col-md-6">
                                <div class="p-4 border rounded bg-white h-100 shadow-sm">
                                    <h5 class="font-w600 mb-3 text-dark border-bottom pb-2">
                                        <i class="fa fa-graduation-cap text-muted me-2"></i>2. Enquadramento Pedagógico
                                    </h5>
                                    <?php if($student->room): ?>
                                        <div class="row g-3">
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Turma Alocada</small>
                                                <a href="<?php echo e(route('room.show', $student->room->id)); ?>" class="fs-6 font-w600 text-primary">
                                                    <?php echo e($student->room->name); ?>

                                                </a>
                                            </div>
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Curso Ministrado</small>
                                                <span class="fs-6 font-w600 text-dark">
                                                    <?php echo e($student->room->course->name ?? ($student->course->name ?? 'N/D')); ?>

                                                </span>
                                            </div>
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Turno de Aulas</small>
                                                <span class="badge light badge-primary"><?php echo e($student->room->shift); ?></span>
                                            </div>
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Horário das Sessões</small>
                                                <?php if($student->room->start_time && $student->room->end_time): ?>
                                                    <span class="fs-6 text-dark">
                                                        <?php echo e(\Carbon\Carbon::parse($student->room->start_time)->format('H:i')); ?> às <?php echo e(\Carbon\Carbon::parse($student->room->end_time)->format('H:i')); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted">Horário não definido</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Sala de Aulas</small>
                                                <span class="fs-6 text-dark font-w500">
                                                    <?php if($student->room->classroom): ?>
                                                        <i class="fa fa-building text-muted me-1"></i> Sala <?php echo e($student->room->classroom->number_of_classroom); ?>

                                                    <?php else: ?>
                                                        <span class="text-muted">Não definida</span>
                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Formador Responsável</small>
                                                <span class="fs-6 text-dark font-w500">
                                                    <?php echo e($student->room->teacher->name ?? 'Não atribuído'); ?>

                                                </span>
                                            </div>
                                            <div class="col-12">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Ocupação da Turma</small>
                                                <?php if($student->room): ?>
                                                    <div class="progress mt-1" style="height: 10px;">
                                                        <?php $percentage = $student->room->occupancyPercentage(); ?>
                                                        <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo e($percentage); ?>%;" aria-valuenow="<?php echo e($percentage); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                    <small class="text-muted mt-1 d-block">
                                                        <?php echo e($student->room->occupiedSeats()); ?> ocupadas de <?php echo e($student->room->max_capacity); ?> vagas (<?php echo e($student->room->availableSeats()); ?> disponíveis)
                                                    </small>
                                                <?php else: ?>
                                                    <span class="text-muted">Nenhuma turma atribuída</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="p-3 bg-light rounded text-center">
                                            <p class="text-muted mb-0">Nenhuma turma atribuída atualmente a este formando.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            
                            <div class="col-md-6">
                                <div class="p-4 border rounded bg-white h-100 shadow-sm">
                                    <h5 class="font-w600 mb-3 text-dark border-bottom pb-2">
                                        <i class="fa fa-id-card-o text-muted me-2"></i>3. Candidatura de Origem
                                    </h5>
                                    <?php if($student->enrollment): ?>
                                        <div class="row g-3">
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Código da Candidatura</small>
                                                <a href="<?php echo e(route('enrollment.show', $student->enrollment->id)); ?>" class="fs-6 font-w600 text-primary">
                                                    <?php echo e($student->enrollment->formattedCode()); ?>

                                                </a>
                                            </div>
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Data da Inscrição</small>
                                                <span class="fs-6 text-dark">
                                                    <?php echo e($student->enrollment->date ? $student->enrollment->date->format('d/m/Y') : ($student->enrollment->created_at ? $student->enrollment->created_at->format('d/m/Y') : '-')); ?>

                                                </span>
                                            </div>
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Ciclo de Frequência</small>
                                                <span class="badge light badge-info font-w600">
                                                    Ciclo <?php echo e($student->enrollment->cycle ?? 1); ?>

                                                </span>
                                            </div>
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Turno Pretendido Inicial</small>
                                                <span class="fs-6 text-dark"><?php echo e($student->enrollment->shift); ?></span>
                                            </div>
                                            <div class="col-12 pt-2 border-top">
                                                <a href="<?php echo e(route('enrollment.show', $student->enrollment->id)); ?>" class="btn btn-outline-secondary btn-xs">
                                                    <i class="fa fa-external-link me-1"></i> Ver Ficha da Inscrição
                                                </a>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted mb-0">Inscrição de origem não vinculada.</p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            
                            <div class="col-md-6">
                                <div class="p-4 border rounded bg-white h-100 shadow-sm">
                                    <h5 class="font-w600 mb-3 text-dark border-bottom pb-2">
                                        <i class="fa fa-money text-muted me-2"></i>4. Regularização Financeira
                                    </h5>
                                    <?php
                                        $payments = $student->enrollment ? $student->enrollment->payments : collect();
                                        $totalPaid = $student->enrollment ? $student->enrollment->totalPaid() : 0.0;
                                        $coursePrice = $student->course ? (float) $student->course->value : 0.0;
                                    ?>
                                    <div class="row g-3 mb-3">
                                        <div class="col-sm-6">
                                            <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Preço do Curso</small>
                                            <span class="fs-6 font-w600 text-dark">
                                                <?php echo e(number_format($coursePrice, 2, ',', '.')); ?> Kz
                                            </span>
                                        </div>
                                        <div class="col-sm-6">
                                            <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Montante Liquidado</small>
                                            <span class="fs-6 font-w600 text-success">
                                                <?php echo e(number_format($totalPaid, 2, ',', '.')); ?> Kz
                                            </span>
                                        </div>
                                    </div>

                                    <?php if($payments->count() > 0): ?>
                                        <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-2">Comprovativos e Recibos Registados</small>
                                        <ul class="list-unstyled mb-0">
                                            <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                    <div>
                                                        <a href="<?php echo e(route('payment.show', $p->id)); ?>" class="font-w600 text-primary fs-7">
                                                            <?php echo e($p->reference); ?>

                                                        </a>
                                                        <small class="text-muted d-block fs-8"><?php echo e($p->formattedDate()); ?> &bull; <?php echo e($p->type_of_payment); ?></small>
                                                    </div>
                                                    <div class="text-end">
                                                        <strong class="fs-7 text-dark"><?php echo e($p->formattedValue()); ?></strong>
                                                        <span class="badge light <?php echo e($p->statusBadgeClass()); ?> d-block fs-8 mt-1"><?php echo e($p->status); ?></span>
                                                    </div>
                                                </li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    <?php else: ?>
                                        <p class="text-muted mb-0 fs-7">Nenhum pagamento registado nesta matrícula.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/eneto/Documents/infosi intership/projects/centroFormacao/resources/views/admin/student/details/index.blade.php ENDPATH**/ ?>
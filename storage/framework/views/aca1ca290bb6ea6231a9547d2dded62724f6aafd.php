<?php $__env->startSection('title', 'Detalhes da Turma'); ?>

<?php $__env->startSection('content'); ?>

<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    
                    <div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center border-bottom">
                        <div>
                            <h4 class="card-title mb-1 text-dark">Detalhes da Turma: <?php echo e($room->name); ?></h4>
                            <p class="m-0 subtitle text-muted">Informações pedagógicas, lotação e atribuições da turma</p>
                        </div>
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            
                            <a href="<?php echo e(route('room.printStudents', $room->id)); ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="fa fa-print me-1"></i> Imprimir Lista de Formandos
                            </a>
                            
                            <a href="<?php echo e(route('room.edit', $room->id)); ?>" class="btn btn-primary btn-sm">
                                <i class="fa fa-pencil me-1"></i> Editar
                            </a>
                            
                            <a href="<?php echo e(route('room.index')); ?>" class="btn btn-secondary btn-sm">
                                <i class="fa fa-arrow-left me-1"></i> Voltar
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <?php
                            $occupied   = $room->occupiedSeats();
                            $capacity   = $room->max_capacity;
                            $available  = $room->availableSeats();
                            $percentage = $room->occupancyPercentage();
                        ?>

                        
                        <div class="p-3 mb-4 rounded border bg-light">
                            <div class="row align-items-center">
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <small class="text-muted text-uppercase font-w600 d-block fs-7 mb-1">Estado da Turma</small>
                                    <div>
                                        <span class="badge light badge-primary fs-6 px-3 py-2">
                                            <?php echo e($room->status); ?>

                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="font-w600 text-dark">Ocupação: <?php echo e($occupied); ?> de <?php echo e($capacity); ?> lugares ocupados</span>
                                        <span class="font-w600 text-dark">
                                            <?php echo e($available); ?> <?php echo e($available == 1 ? 'vaga livre' : 'vagas livres'); ?> (<?php echo e($percentage); ?>%)
                                        </span>
                                    </div>
                                    <div class="progress room-progress-track">
                                        <div class="progress-bar bg-primary room-progress-fill" 
                                             role="progressbar" 
                                             style="width: <?php echo e($percentage); ?>%;" 
                                             aria-valuenow="<?php echo e($percentage); ?>" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="row g-4">
                            
                            <div class="col-md-6">
                                <div class="p-4 border rounded bg-white h-100 shadow-sm">
                                    <h5 class="font-w600 mb-3 text-dark border-bottom pb-2">
                                        <i class="fa fa-clock-o text-muted me-2"></i>Horários e Calendário
                                    </h5>
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Turno</small>
                                            <span class="fs-6 font-w600 text-dark"><?php echo e($room->shift); ?></span>
                                        </div>
                                        <div class="col-sm-6">
                                            <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Horário das Aulas</small>
                                            <span class="fs-6 font-w600 text-dark"><?php echo e(\Carbon\Carbon::parse($room->start_time)->format('H:i')); ?> às <?php echo e(\Carbon\Carbon::parse($room->end_time)->format('H:i')); ?></span>
                                        </div>
                                        <div class="col-12">
                                            <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Dias da Semana</small>
                                            <span class="fs-6 font-w600 text-dark"><?php echo e(is_array($room->days_of_week) ? implode(', ', $room->days_of_week) : $room->days_of_week); ?></span>
                                        </div>
                                        <div class="col-12">
                                            <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Período Previsto</small>
                                            <span class="fs-6 font-w600 text-dark">
                                                <?php echo e($room->start_date ? $room->start_date->format('d/m/Y') : 'Início N/D'); ?> 
                                                até 
                                                <?php echo e($room->end_date ? $room->end_date->format('d/m/Y') : 'Fim N/D'); ?>

                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="col-md-6">
                                <div class="p-4 border rounded bg-white h-100 shadow-sm">
                                    <h5 class="font-w600 mb-3 text-dark border-bottom pb-2">
                                        <i class="fa fa-building text-muted me-2"></i>Sala 
                                    </h5>
                                    <?php if($room->classroom): ?>
                                        <div class="row g-3">
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Número da Sala</small>
                                                <span class="fs-6 font-w600 text-dark">Sala <?php echo e($room->classroom->number_of_classroom); ?></span>
                                            </div>
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Capacidade da Sala</small>
                                                <span class="fs-6 font-w600 text-dark"><?php echo e($room->classroom->capacity); ?> lugares</span>
                                            </div>
                                            <div class="col-12">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Descrição / Equipamento</small>
                                                <span class="fs-6 text-dark"><?php echo e($room->classroom->description ?? 'Sem observações adicionais.'); ?></span>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted mb-0">Nenhuma sala física atribuída.</p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            
                            <div class="col-md-6">
                                <div class="p-4 border rounded bg-white h-100 shadow-sm">
                                    <h5 class="font-w600 mb-3 text-dark border-bottom pb-2">
                                        <i class="fa fa-book text-muted me-2"></i>Curso Associado
                                    </h5>
                                    <?php if($room->course): ?>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Nome do Curso</small>
                                                <span class="fs-6 font-w600 text-dark"><?php echo e($room->course->name); ?></span>
                                            </div>
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Carga Horária</small>
                                                <span class="fs-6 font-w600 text-dark"><?php echo e($room->course->duration); ?> horas</span>
                                            </div>
                                            
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted mb-0">Curso não identificado.</p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            
                            <div class="col-md-6">
                                <div class="p-4 border rounded bg-white h-100 shadow-sm">
                                    <h5 class="font-w600 mb-3 text-dark border-bottom pb-2">
                                        <i class="fa fa-user text-muted me-2"></i>Formador
                                    </h5>
                                    <?php if($room->teacher): ?>
                                        <div class="d-flex align-items-center mb-3">
                                            <?php if($room->teacher->image): ?>
                                                <img src="<?php echo e(asset('storage/' . $room->teacher->image)); ?>" class="rounded-circle me-3 room-avatar-detail" alt="<?php echo e($room->teacher->name); ?>">
                                            <?php else: ?>
                                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3 room-avatar-placeholder">
                                                    <i class="fa fa-user text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <span class="fs-6 font-w600 text-dark d-block"><?php echo e($room->teacher->name); ?></span>
                                                <small class="text-muted"><?php echo e($room->teacher->specialization); ?></small>
                                            </div>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Email</small>
                                                <span class="fs-6 font-w500 text-dark"><?php echo e($room->teacher->email); ?></span>
                                            </div>
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Telefone</small>
                                                <span class="fs-6 font-w500 text-dark"><?php echo e($room->teacher->phone); ?></span>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted mb-0">Nenhum formador atribuído a esta turma.</p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            
                            <div class="col-12">
                                <div class="p-4 border rounded bg-white shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                                        <h5 class="font-w600 mb-0 text-dark">
                                            <i class="fa fa-users text-muted me-2"></i>Alunos / Candidatos nesta Turma (<?php echo e($room->enrollments->count()); ?>)
                                        </h5>
                                    </div>
                                    <?php if($room->enrollments && $room->enrollments->count() > 0): ?>
                                        <div class="table-responsive">
                                            <table class="table table-hover style-1 custom-table mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Código</th>
                                                        <th>Nome</th>
                                                        <th>BI / Identificação</th>
                                                        <th>Contacto</th>
                                                        <th>Estado</th>
                                                        <th class="text-end">Ação</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $__currentLoopData = $room->enrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr>
                                                            <td>
                                                                <span class="badge light badge-primary font-w600">
                                                                    <?php echo e($enrollment->formattedCode()); ?>

                                                                </span>
                                                            </td>
                                                            <td>
                                                                <a href="<?php echo e(route('enrollment.show', $enrollment->id)); ?>" class="font-w600 text-dark">
                                                                    <?php echo e($enrollment->name); ?>

                                                                </a>
                                                            </td>
                                                            <td><?php echo e($enrollment->number_of_identify); ?></td>
                                                            <td><?php echo e($enrollment->phone); ?></td>
                                                            <td>
                                                                <span class="badge light <?php echo e($enrollment->statusBadgeClass()); ?>">
                                                                    <?php echo e($enrollment->status); ?>

                                                                </span>
                                                            </td>
                                                            <td class="text-end">
                                                                <a href="<?php echo e(route('enrollment.show', $enrollment->id)); ?>" class="btn btn-outline-primary btn-xs">
                                                                    <i class="fa fa-eye me-1"></i> Ver
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted mb-0">Nenhum aluno ou candidato alocado a esta turma até ao momento.</p>
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
<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/eneto/Documents/infosi intership/projects/centroFormacao/resources/views/admin/room/details/index.blade.php ENDPATH**/ ?>
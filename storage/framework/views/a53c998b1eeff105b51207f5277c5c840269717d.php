<?php $__env->startSection('title', 'Detalhes da Sala de Aula'); ?>

<?php $__env->startSection('content'); ?>

<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                
                <div class="card">
                    
                    
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h4 class="card-title mb-1">Detalhes da Sala: <?php echo e($classroom->number_of_classroom); ?></h4>
                            <p class="subtitle text-muted mb-0">Informações físicas do espaço e lista de turmas alocadas</p>
                        </div>
                        <div class="d-flex gap-2">
                            
                            <a href="<?php echo e(route('classroom.edit', $classroom->id)); ?>" class="btn btn-warning btn-sm text-white">
                                <i class="fa fa-edit me-1"></i> Editar Sala
                            </a>
                            
                            <a href="<?php echo e(route('classroom.index')); ?>" class="btn btn-primary btn-sm">
                                <i class="fa fa-arrow-left me-1"></i> Voltar
                            </a>
                        </div>
                    </div>

                    
                    <div class="card-body p-4">
                        <div class="row mb-4">
                            
                            <div class="col-md-4 mb-3">
                                <div class="p-3 border rounded bg-light">
                                    <h6 class="text-primary mb-1">
                                        <i class="fa fa-building me-1"></i> Número da Sala
                                    </h6>
                                    <p class="fw-bold mb-0 fs-16">Sala <?php echo e($classroom->number_of_classroom); ?></p>
                                </div>
                            </div>

                            
                            <div class="col-md-4 mb-3">
                                <div class="p-3 border rounded bg-light">
                                    <h6 class="text-primary mb-1">
                                        <i class="fa fa-users me-1"></i> Capacidade Física
                                    </h6>
                                    <p class="fw-bold mb-0 fs-16"><?php echo e($classroom->capacity); ?> lugares</p>
                                </div>
                            </div>

                            
                            <div class="col-md-4 mb-3">
                                <div class="p-3 border rounded bg-light">
                                    <h6 class="text-primary mb-1">
                                        <i class="fa fa-calendar me-1"></i> Turmas Alocadas
                                    </h6>
                                    <p class="fw-bold mb-0 fs-16"><?php echo e($classroom->rooms->count()); ?> turma(s)</p>
                                </div>
                            </div>

                            
                            <div class="col-md-12 mb-3">
                                <div class="p-3 border rounded bg-light">
                                    <h6 class="text-primary mb-1">
                                        <i class="fa fa-info-circle me-1"></i> Observações 
                                    </h6>
                                    <p class="mb-0"><?php echo e($classroom->description ?: 'Nenhuma observação ou equipamento registado para esta sala.'); ?></p>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        
                        <h5 class="text-primary mb-3">
                            <i class="fa fa-list me-2"></i> Turmas Atribuídas a Esta Sala
                        </h5>

                        <div class="table-responsive">
                            <table class="table table-hover style-1 custom-table">
                                <thead>
                                    <tr>
                                        <th>Nome da Turma</th>
                                        <th>Curso</th>
                                        <th>Formador</th>
                                        <th>Turno</th>
                                        <th>Horário de Aulas</th>
                                        <th>Capacidade da Turma</th>
                                        <th class="text-end">Acções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    <?php $__empty_1 = true; $__currentLoopData = $classroom->rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><strong><?php echo e($room->name); ?></strong></td>
                                            <td><?php echo e($room->course->name ?? 'N/A'); ?></td>
                                            <td><?php echo e($room->teacher->name ?? 'N/A'); ?></td>
                                            <td><span class="badge light badge-info"><?php echo e($room->shift); ?></span></td>
                                            <td>
                                                <i class="fa fa-clock-o me-1 text-muted"></i>
                                                <?php echo e(\Carbon\Carbon::parse($room->start_time)->format('H:i')); ?> - <?php echo e(\Carbon\Carbon::parse($room->end_time)->format('H:i')); ?>

                                            </td>
                                            <td><?php echo e($room->max_capacity); ?> lugares</td>
                                            <td class="text-end">
                                                <a href="<?php echo e(route('room.show', $room->id)); ?>" class="btn btn-xs btn-outline-primary">
                                                    <i class="fa fa-eye me-1"></i> Ver Turma
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">
                                                <i class="fa fa-info-circle me-1"></i> Nenhuma turma atribuída a esta sala no momento.
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

<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/eneto/Documents/infosi intership/projects/centroFormacao/resources/views/admin/classroom/detail/index.blade.php ENDPATH**/ ?>
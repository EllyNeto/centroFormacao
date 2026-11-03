<?php $__env->startSection('title', 'Gestão de Pagamentos'); ?>

<?php $__env->startSection('content'); ?>

<div class="content-body">
    <div class="container-fluid">
        
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4">
                <i class="fa fa-check-circle me-2"></i><?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-4">
                <i class="fa fa-exclamation-triangle me-2"></i><?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-sm-6">
                <div class="payment-metric-card d-flex align-items-center justify-content-between h-100">
                    <div>
                        <span class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Total Liquidado</span>
                        <h4 class="mb-0 font-w700 text-dark"><?php echo e(number_format($totalCollected, 2, ',', '.')); ?> Kz</h4>
                    </div>
                    <div class="payment-metric-icon payment-icon-success">
                        <i class="fa fa-coins"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6">
                <div class="payment-metric-card d-flex align-items-center justify-content-between h-100">
                    <div>
                        <span class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Confirmados</span>
                        <h4 class="mb-0 font-w700 text-success"><?php echo e($totalConfirmed); ?></h4>
                    </div>
                    <div class="payment-metric-icon payment-icon-primary">
                        <i class="fa fa-check-circle"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6">
                <div class="payment-metric-card d-flex align-items-center justify-content-between h-100">
                    <div>
                        <span class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Pendentes</span>
                        <h4 class="mb-0 font-w700 text-warning"><?php echo e($totalPending); ?></h4>
                    </div>
                    <div class="payment-metric-icon payment-icon-warning">
                        <i class="fa fa-clock"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6">
                <div class="payment-metric-card d-flex align-items-center justify-content-between h-100">
                    <div>
                        <span class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Anulados</span>
                        <h4 class="mb-0 font-w700 text-danger"><?php echo e($totalCanceled); ?></h4>
                    </div>
                    <div class="payment-metric-icon payment-icon-danger">
                        <i class="fa fa-ban"></i>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    
                    <div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center border-bottom">
                        <div>
                            <h4 class="card-title mb-0 text-dark">Histórico de Pagamentos</h4>
                            <p class="m-0 subtitle text-muted">Controlo de liquidações, emissão de recibos e sincronização de inscrições</p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <a href="<?php echo e(route('payment.create')); ?>" class="btn btn-primary btn-sm">
                                <i class="fa fa-plus me-1"></i> Registar Pagamento
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table id="example3" class="display table table-hover style-1 custom-table">
                                <thead>
                                    <tr>
                                        <th>Recibo</th>
                                        <th>Candidato</th>
                                        <th>Valor</th>
                                        <th>Estado</th>
                                        <th class="text-end">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            
                                            <td>
                                                <a href="<?php echo e(route('payment.show', $payment->id)); ?>" class="font-w600 text-primary">
                                                    <?php echo e($payment->reference); ?>

                                                </a>
                                            </td>

                                            
                                            <td>
                                                <?php if($payment->enrollment): ?>
                                                    <a href="<?php echo e(route('enrollment.show', $payment->enrollment->id)); ?>" class="font-w600 text-dark d-block">
                                                        <?php echo e($payment->enrollment->name); ?>

                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted">Inscrição Não Encontrada</span>
                                                <?php endif; ?>
                                            </td>

                                            
                                            <td>
                                                <strong class="text-dark"><?php echo e($payment->formattedValue()); ?></strong>
                                            </td>

                                            
                                            <td>
                                                <span class="badge light <?php echo e($payment->statusBadgeClass()); ?> font-w600">
                                                    <?php echo e($payment->status); ?>

                                                </span>
                                            </td>

                                            
                                            <td class="text-end">
                                                <div class="d-flex justify-content-end gap-1">
                                                    
                                                    <a href="<?php echo e(route('payment.show', $payment->id)); ?>" 
                                                       class="btn btn-info btn-xs sharp" 
                                                       title="Ver Recibo Oficial">
                                                        <i class="fa fa-file-invoice"></i>
                                                    </a>

                                                    
                                                    <?php if(!$payment->isCanceled()): ?>
                                                        <a href="<?php echo e(route('payment.edit', $payment->id)); ?>" 
                                                           class="btn btn-primary btn-xs sharp" 
                                                           title="<?php echo e($payment->isConfirmed() ? 'Anular Pagamento' : 'Editar Pagamento'); ?>">
                                                            <i class="fa <?php echo e($payment->isConfirmed() ? 'fa-ban' : 'fa-pencil'); ?>"></i>
                                                        </a>
                                                    <?php endif; ?>

                                                    
                                                    <?php if($payment->isCanceled()): ?>
                                                        <button type="button" 
                                                                class="btn btn-danger btn-xs sharp" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#deleteModal<?php echo e($payment->id); ?>" 
                                                                title="Eliminar Pagamento Anulado">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>

                                                
                                                <div class="modal fade" id="deleteModal<?php echo e($payment->id); ?>" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered text-start">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Confirmar Eliminação</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p class="text-dark mb-2">
                                                                    Tem certeza de que pretende eliminar o registo de pagamento de referência 
                                                                    <strong><?php echo e($payment->reference); ?></strong>?
                                                                </p>
                                                                <p class="text-muted fs-7 mb-0">
                                                                    Montante: <strong><?php echo e($payment->formattedValue()); ?></strong> &bull; Candidato: 
                                                                    <strong><?php echo e($payment->enrollment ? $payment->enrollment->name : 'N/D'); ?></strong>.
                                                                    <br>
                                                                    <em>Nota: Se este for o único pagamento confirmado, a inscrição reverterá automaticamente para o estado Pendente.</em>
                                                                </p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                                                                <form action="<?php echo e(route('payment.destroy', $payment->id)); ?>" method="POST">
                                                                    <?php echo csrf_field(); ?>
                                                                    <?php echo method_field('DELETE'); ?>
                                                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar Pagamento</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                <i class="fa fa-info-circle me-1"></i>Nenhum pagamento registado até ao momento.
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
<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/eneto/Documents/infosi intership/projects/centroFormacao/resources/views/admin/payment/list/index.blade.php ENDPATH**/ ?>
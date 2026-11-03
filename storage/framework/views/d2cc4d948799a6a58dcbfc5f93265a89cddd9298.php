<?php $__env->startSection('title', 'Recibo de Pagamento - ' . $payment->reference); ?>

<?php $__env->startSection('content'); ?>

<div class="content-body">
    <div class="container-fluid">
        
        <div class="row mb-4 no-print">
            <div class="col-12 d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <h4 class="mb-0 text-dark font-w700">Recibo de Pagamento</h4>
                    <p class="m-0 text-muted">Comprovativo oficial de transação financeira e liquidação de propina</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    
                    <a href="<?php echo e(route('payment.show', $payment->id)); ?>" id="btnPrintReceipt" class="btn btn-outline-primary btn-sm">
                        <i class="fa fa-print me-1"></i> Imprimir Recibo
                    </a>

                    <?php if($payment->enrollment): ?>
                        <a href="<?php echo e(route('enrollment.show', $payment->enrollment->id)); ?>" class="btn btn-info btn-sm">
                            <i class="fa fa-user me-1"></i> Ver Candidatura
                        </a>
                    <?php endif; ?>

                    <?php if(!$payment->isCanceled()): ?>
                        <a href="<?php echo e(route('payment.edit', $payment->id)); ?>" class="btn btn-primary btn-sm">
                            <i class="fa fa-pencil me-1"></i> <?php echo e($payment->isConfirmed() ? 'Anular' : 'Editar'); ?>

                        </a>
                    <?php endif; ?>

                    <a href="<?php echo e(route('payment.index')); ?>" class="btn btn-secondary btn-sm">
                        <i class="fa fa-arrow-left me-1"></i> Voltar
                    </a>
                </div>
            </div>
        </div>

        
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4 no-print">
                <i class="fa fa-check-circle me-2"></i><?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        
        <div class="receipt-container">
            
            <div class="receipt-header">
                <div class="row align-items-center">
                    <div class="col-sm-8 mb-3 mb-sm-0">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fa fa-university text-primary fs-3"></i>
                            <h4 class="receipt-title mb-0">CENTRO DE FORMAÇÃO PROFISSIONAL</h4>
                        </div>
                        <p class="text-light mb-0 fs-7 opacity-75">
                            Sistema Integrado de Gestão Académica e Financeira &bull; República de Angola
                        </p>
                    </div>
                    <div class="col-sm-4 text-sm-end">
                        <span class="d-block fs-8 text-uppercase tracking-wider text-light opacity-75 mb-1">Comprovativo Nº</span>
                        <span class="receipt-number"><?php echo e($payment->reference); ?></span>
                    </div>
                </div>
            </div>

            
            <div class="receipt-body">
                
                <div class="d-flex flex-wrap justify-content-between align-items-center pb-4 mb-4 border-bottom">
                    <div>
                        <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Data e Hora da Liquidação</small>
                        <span class="fs-6 font-w600 text-dark">
                            <i class="fa fa-calendar-check-o text-muted me-1"></i><?php echo e($payment->formattedDate()); ?>

                        </span>
                    </div>
                    <div>
                        <?php if($payment->isConfirmed()): ?>
                            <span class="receipt-stamp receipt-stamp-confirmed">
                                <i class="fa fa-check-circle me-1"></i> Confirmado
                            </span>
                        <?php elseif($payment->isPending()): ?>
                            <span class="receipt-stamp receipt-stamp-pending">
                                <i class="fa fa-clock-o me-1"></i> Pendente
                            </span>
                        <?php else: ?>
                            <span class="receipt-stamp receipt-stamp-canceled">
                                <i class="fa fa-times-circle me-1"></i> Anulado
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="mb-4">
                    <h6 class="receipt-section-title">1. Identificação do Formando</h6>
                    <?php if($payment->enrollment): ?>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Nome Completo</small>
                                <span class="fs-6 font-w600 text-dark"><?php echo e($payment->enrollment->name); ?></span>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Nº de Identificação (BI)</small>
                                <span class="fs-6 text-dark"><?php echo e($payment->enrollment->number_of_identify); ?></span>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Código de Inscrição</small>
                                <span class="fs-6 font-w600 text-primary"><?php echo e($payment->enrollment->formattedCode()); ?></span>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Contacto Telefónico</small>
                                <span class="fs-6 text-dark"><?php echo e($payment->enrollment->phone); ?></span>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Correio Eletrónico</small>
                                <span class="fs-6 text-dark"><?php echo e($payment->enrollment->email); ?></span>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">Inscrição desassociada ou não encontrada.</p>
                    <?php endif; ?>
                </div>

                
                <div class="mb-4">
                    <h6 class="receipt-section-title">2. Dados Pedagógicos do Curso</h6>
                    <?php if($payment->enrollment && $payment->enrollment->course): ?>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Curso Formativo</small>
                                <span class="fs-6 font-w600 text-dark"><?php echo e($payment->enrollment->course->name); ?></span>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Carga Horária</small>
                                <span class="fs-6 text-dark"><?php echo e($payment->enrollment->course->duration); ?> Horas</span>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Turno Solicitado</small>
                                <span class="badge light badge-primary"><?php echo e($payment->enrollment->shift); ?></span>
                            </div>
                            <?php if($payment->enrollment->room): ?>
                                <div class="col-12">
                                    <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">Turma Atribuída</small>
                                    <span class="fs-6 text-dark">
                                        <?php echo e($payment->enrollment->room->name); ?> 
                                        (<?php echo e($payment->enrollment->room->classroom ? 'Sala ' . $payment->enrollment->room->classroom->number_of_classroom : 'Sala N/D'); ?>)
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">Informações de curso indisponíveis.</p>
                    <?php endif; ?>
                </div>

                
                <div class="receipt-amount-box">
                    <small class="text-muted d-block text-uppercase font-w700 fs-8 mb-1 tracking-wider">
                        Montante Total Liquidado Nesta Transação
                    </small>
                    <div class="receipt-amount-display">
                        <?php echo e($payment->formattedValue()); ?>

                    </div>
                    <div class="mt-2 text-muted fs-7">
                        Método: <strong><i class="<?php echo e($payment->typeIcon()); ?> me-1"></i><?php echo e($payment->type_of_payment); ?></strong> &bull; 
                        Moeda: <strong><?php echo e($payment->currency); ?></strong>
                    </div>
                </div>

                
                                <?php if($payment->enrollment): ?>
                    <div class="p-3 rounded bg-light border mb-4">
                        <div class="row g-2 text-center">
                            <div class="col-sm-4 border-end">
                                <small class="text-muted d-block fs-8 text-uppercase font-w600">Preço do Curso</small>
                                <span class="fs-6 font-w600 text-dark">
                                    <?php echo e($payment->enrollment->course ? number_format($payment->enrollment->course->value, 2, ',', '.') . ' Kz' : '-'); ?>

                                </span>
                            </div>
                            <div class="col-sm-4 border-end">
                                <small class="text-muted d-block fs-8 text-uppercase font-w600">Total Liquidado (Ciclo <?php echo e($payment->enrollment->cycle); ?>)</small>
                                <span class="fs-6 font-w600 text-success">
                                    <?php echo e(number_format($payment->enrollment->totalPaid(), 2, ',', '.')); ?> Kz
                                </span>
                            </div>
                            <div class="col-sm-4">
                                <small class="text-muted d-block fs-8 text-uppercase font-w600">Saldo Remanescente</small>
                                <span class="fs-6 font-w700 <?php echo e($payment->enrollment->remainingBalance() > 0 ? 'text-danger' : 'text-muted'); ?>">
                                    <?php echo e(number_format($payment->enrollment->remainingBalance(), 2, ',', '.')); ?> Kz
                                </span>
                            </div>
                        </div>
                    </div>

                    
                    <?php if($payment->previous_room_id && $payment->previousRoom): ?>
                        <div class="p-3 rounded border border-warning bg-light mb-4">
                            <small class="text-muted d-block text-uppercase font-w600 fs-8 mb-1">
                                <i class="fa fa-history me-1"></i> Turma do Ciclo Anterior
                            </small>
                            <span class="fs-7 text-dark font-w600"><?php echo e($payment->previousRoom->name); ?></span>
                            <?php if($payment->previousRoom->course): ?>
                                <small class="text-muted d-block"><?php echo e($payment->previousRoom->course->name); ?></small>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    
                    <?php if($payment->isCanceled() && $payment->cancellation_reason): ?>
                        <div class="alert alert-danger mb-4">
                            <strong><i class="fa fa-ban me-1"></i> Motivo da Anulação:</strong>
                            <?php echo e($payment->cancellation_reason); ?>

                            <?php if($payment->canceled_at): ?>
                                <br><small class="text-muted">Anulado em <?php echo e($payment->canceled_at->format('d/m/Y H:i')); ?></small>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                
                <div class="receipt-signatures">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="signature-line"></div>
                            <small class="text-muted font-w600 text-uppercase fs-8">O Responsável Financeiro</small>
                        </div>
                        <div class="col-6">
                            <div class="signature-line"></div>
                            <small class="text-muted font-w600 text-uppercase fs-8">O Formando / Depositante</small>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <small class="text-muted fs-8">
                            Este documento é emitido por computador e serve como comprovativo oficial de pagamento.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/eneto/Documents/infosi intership/projects/centroFormacao/resources/views/admin/payment/details/index.blade.php ENDPATH**/ ?>
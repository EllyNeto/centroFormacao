<?php $__env->startSection('title', 'Registar Pagamento'); ?>

<?php $__env->startSection('content'); ?>

<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    
                    <div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center border-bottom">
                        <div>
                            <h4 class="card-title mb-0 text-dark">Registar Novo Pagamento</h4>
                            <p class="m-0 subtitle text-muted">Efetue o registo da liquidação de propina associada à candidatura do formando</p>
                        </div>
                        <a href="<?php echo e(route('payment.index')); ?>" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left me-1"></i> Voltar à Lista
                        </a>
                    </div>

                    <div class="card-body p-4">
                        
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger alert-dismissible fade show mb-4">
                                <h6 class="alert-heading font-w600 text-danger mb-2">
                                    <i class="fa fa-exclamation-triangle me-2"></i>Por favor, corrija os seguintes erros:
                                </h6>
                                <ul class="mb-0 ps-3">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        
                        <?php if(session('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show mb-4">
                                <i class="fa fa-ban me-2"></i><strong>Operação não permitida:</strong> <?php echo e(session('error')); ?>

                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form action="<?php echo e(route('payment.store')); ?>" method="POST">
                            <?php echo csrf_field(); ?>

                            <div class="row g-4">
                                
                                <div class="col-lg-6">
                                    <div class="p-3 border rounded bg-light mb-3">
                                        <h5 class="font-w600 text-dark mb-3 border-bottom pb-2">
                                            <i class="fa fa-user me-2 text-primary"></i>1. Seleção do Formando
                                        </h5>

                                        <div class="mb-3">
                                            <label for="enrollment_id" class="form-label font-w600 text-dark">
                                               Inscrição <span class="text-danger">*</span>
                                            </label>
                                            <select name="enrollment_id"
                                                    id="enrollment_id"
                                                    class="form-control select2 <?php $__errorArgs = ['enrollment_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                    required>
                                                <option value="">Selecione o candidato...</option>
                                                <?php $__currentLoopData = $enrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        $isSelected   = (old('enrollment_id', $selectedEnrollment ? $selectedEnrollment->id : null) == $enrollment->id);
                                                        $coursePrice  = $enrollment->course ? (float)$enrollment->course->value : 0;
                                                        $paid         = $enrollment->totalPaid();
                                                        $balance      = $enrollment->remainingBalance();
                                                        $isRepeat     = $enrollment->canRepeat();
                                                    ?>
                                                    <option value="<?php echo e($enrollment->id); ?>"
                                                            data-name="<?php echo e($enrollment->name); ?>"
                                                            data-bi="<?php echo e($enrollment->number_of_identify); ?>"
                                                            data-phone="<?php echo e($enrollment->phone); ?>"
                                                            data-course="<?php echo e($enrollment->course ? $enrollment->course->name : 'N/D'); ?>"
                                                            data-course-value="<?php echo e($coursePrice); ?>"
                                                            data-paid="<?php echo e($paid); ?>"
                                                            data-balance="<?php echo e($isRepeat ? $coursePrice : $balance); ?>"
                                                            data-is-repeat="<?php echo e($isRepeat ? '1' : '0'); ?>"
                                                            data-image="<?php echo e($enrollment->image ? asset('storage/' . $enrollment->image) : ''); ?>"
                                                            <?php echo e($isSelected ? 'selected' : ''); ?>>
                                                        <?php echo e($enrollment->formattedCode()); ?> - <?php echo e($enrollment->name); ?> (<?php echo e($enrollment->course ? $enrollment->course->name : 'Sem Curso'); ?>)<?php echo e($isRepeat ? ' — REPETIÇÃO' : ''); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <?php $__errorArgs = ['enrollment_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>

                                        
                                        <div id="candidatePreviewBox" class="candidate-preview-box">
                                            <div id="candidatePreviewPlaceholder" class="text-center py-4 text-muted">
                                                <i class="fa fa-info-circle fa-2x mb-2 d-block text-secondary"></i>
                                                <span class="fs-7">Selecione um candidato acima para visualizar os dados financeiros e do curso.</span>
                                            </div>

                                            <div id="candidatePreviewContent" class="d-none">
                                                <div class="d-flex align-items-center mb-3">
                                                    <img id="previewCandidateAvatar" 
                                                         src="" 
                                                         class="candidate-avatar-preview me-3 d-none" 
                                                         alt="Foto">
                                                    <div id="previewCandidateAvatarIcon" class="candidate-avatar-preview-placeholder me-3">
                                                        <i class="fa fa-user"></i>
                                                    </div>
                                                    <div>
                                                        <h6 id="previewCandidateName" class="mb-1 font-w700 text-dark">-</h6>
                                                        <small class="text-muted">
                                                            BI: <span id="previewCandidateBi">-</span> &bull; Tel: <span id="previewCandidatePhone">-</span>
                                                        </small>
                                                    </div>
                                                </div>

                                                <div class="row g-2 pt-2 border-top">
                                                    <div class="col-6">
                                                        <small class="text-muted d-block fs-8 text-uppercase font-w600">Curso Pretendido</small>
                                                        <strong id="previewCourseName" class="text-dark fs-7 d-block text-truncate">-</strong>
                                                    </div>
                                                    <div class="col-6 text-end">
                                                        <small class="text-muted d-block fs-8 text-uppercase font-w600">Preço da Formação</small>
                                                        <strong id="previewCoursePrice" class="text-primary fs-7 d-block">-</strong>
                                                    </div>
                                                    <div class="col-6">
                                                        <small class="text-muted d-block fs-8 text-uppercase font-w600">Já Liquidado</small>
                                                        <span id="previewTotalPaid" class="text-success font-w600 fs-7 d-block">-</span>
                                                    </div>
                                                    <div class="col-6 text-end">
                                                        <small class="text-muted d-block fs-8 text-uppercase font-w600">Saldo em Dívida</small>
                                                        <span id="previewBalance" class="text-danger font-w700 fs-7 d-block">-</span>
                                                    </div>
                                                </div>

                                                
                                                <div id="repeatCourseAlert" class="alert alert-warning mt-3 mb-0 py-2 d-none">
                                                    <i class="fa fa-refresh me-1"></i>
                                                    <strong>Repetição de curso:</strong> O ciclo anterior foi concluído. Este pagamento iniciará um novo ciclo nesta inscrição.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                
                                <div class="col-lg-6">
                                    <div class="p-3 border rounded bg-light mb-3">
                                        <h5 class="font-w600 text-dark mb-3 border-bottom pb-2">
                                            <i class="fa fa-credit-card me-2 text-primary"></i>2. Detalhes do Pagamento
                                        </h5>

                                        
                                        <div class="mb-3">
                                            <label for="type_of_payment" class="form-label font-w600 text-dark">
                                                Forma de Pagamento <span class="text-danger">*</span>
                                            </label>
                                            <select name="type_of_payment"
                                                    id="type_of_payment"
                                                    class="form-control default-select wide <?php $__errorArgs = ['type_of_payment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                    required>
                                                <option value="Transferência" <?php echo e(old('type_of_payment') == 'Transferência' ? 'selected' : ''); ?>>
                                                    Transferência Bancária (IBAN)
                                                </option>
                                                <option value="Multicaixa" <?php echo e(old('type_of_payment', 'Multicaixa') == 'Multicaixa' ? 'selected' : ''); ?>>
                                                    Multicaixa (TPA / Express)
                                                </option>
                                                <option value="Numerário" <?php echo e(old('type_of_payment') == 'Numerário' ? 'selected' : ''); ?>>
                                                    Numerário (Em Dinheiro)
                                                </option>
                                            </select>
                                            <?php $__errorArgs = ['type_of_payment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>

                                        
                                        <div class="mb-3">
                                            <label for="value_display" class="form-label font-w600 text-dark">
                                                Montante a Liquidar (Kz)
                                            </label>
                                            <input type="hidden"
                                                   name="value"
                                                   id="payment_value"
                                                   value="<?php echo e($selectedEnrollment ? ($selectedEnrollment->canRepeat() ? (float)$selectedEnrollment->course->value : $selectedEnrollment->remainingBalance()) : 0); ?>">
                                            <input type="hidden" name="currency" value="AOA">
                                            <div class="input-group">
                                                <input type="text"
                                                       id="value_display"
                                                       class="form-control bg-light font-w600"
                                                       value="<?php echo e($selectedEnrollment ? number_format($selectedEnrollment->canRepeat() ? (float)$selectedEnrollment->course->value : $selectedEnrollment->remainingBalance(), 2, ',', '.') : '—'); ?>"
                                                       readonly>
                                                <span class="input-group-text font-w600">AOA / Kz</span>
                                            </div>
                                            <small class="text-muted">O valor é fixado automaticamente ao preço do curso.</small>
                                        </div>

                                        
                                        <div class="mb-3">
                                            <label for="reference" class="form-label font-w600 text-dark">
                                                Referência / Nº de Recibo <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group payment-reference-input-group">
                                                <input type="text" 
                                                       name="reference" 
                                                       id="reference" 
                                                       class="form-control <?php $__errorArgs = ['reference'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                       placeholder="Ex: REC-2026-0001 ou Nº Transação Bancária" 
                                                       value="<?php echo e(old('reference', $suggestedReference)); ?>" 
                                                       required>
                                                <button type="button" 
                                                        id="btnGenerateReference" 
                                                        class="btn btn-outline-secondary" 
                                                        title="Gerar nova referência aleatória">
                                                    <i class="fa fa-refresh me-1"></i> Gerar Nova
                                                </button>
                                            </div>
                                            <small class="text-muted fs-8">Insira o código do talão bancário ou mantenha a referência gerada pelo centro.</small>
                                            <?php $__errorArgs = ['reference'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>

                                        
                                        <div class="mb-3">
                                            <label for="date" class="form-label font-w600 text-dark">
                                                Data e Hora da Operação <span class="text-danger">*</span>
                                            </label>
                                            <input type="datetime-local" 
                                                   name="date" 
                                                   id="date" 
                                                   class="form-control <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                   value="<?php echo e(old('date', now()->format('Y-m-d\TH:i'))); ?>" 
                                                   required>
                                            <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>

                                        
                                        <div class="mb-3">
                                            <label for="status" class="form-label font-w600 text-dark">
                                                Estado da Transação <span class="text-danger">*</span>
                                            </label>
                                            <select name="status" 
                                                    id="status" 
                                                    class="form-control default-select wide <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                    required>
                                                <option value="Confirmado" <?php echo e(old('status', 'Confirmado') == 'Confirmado' ? 'selected' : ''); ?>>
                                                    Confirmado
                                                </option>
                                                <option value="Pendente" <?php echo e(old('status') == 'Pendente' ? 'selected' : ''); ?>>
                                                    Pendente
                                                </option>
                                            </select>
                                            
                                            <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                                <a href="<?php echo e(route('payment.index')); ?>" class="btn btn-secondary">
                                    Cancelar
                                </a>
                                <button type="submit" id="btnSubmitPayment" class="btn btn-primary">
                                    <i class="fa fa-check me-1"></i> Confirmar e Registar Pagamento
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/eneto/Documents/infosi intership/projects/centroFormacao/resources/views/admin/payment/create/index.blade.php ENDPATH**/ ?>
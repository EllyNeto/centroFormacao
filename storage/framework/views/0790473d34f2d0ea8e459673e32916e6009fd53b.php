<?php $__env->startSection('title', 'Registar Novo Utilizador'); ?>

<?php $__env->startSection('content'); ?>
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-8 col-lg-10 mx-auto">
                <div class="card">
                    <div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center border-bottom">
                        <div>
                            <h4 class="card-title mb-1 text-dark">Registar Novo Utilizador</h4>
                            <p class="m-0 subtitle text-muted">Crie uma nova conta administrativa para acesso ao sistema</p>
                        </div>
                        <a href="<?php echo e(route('user.index')); ?>" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left me-1"></i> Voltar à Lista
                        </a>
                    </div>

                    <form action="<?php echo e(route('user.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <div class="card-body p-4">
                            <?php if($errors->any()): ?>
                                <div class="alert alert-danger alert-dismissible fade show mb-4">
                                    <h6 class="alert-heading font-w600"><i class="fa fa-exclamation-triangle me-2"></i>Por favor corrija os seguintes erros:</h6>
                                    <ul class="mb-0 ps-3">
                                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label text-dark font-w600">Nome Completo <span class="text-danger">*</span></label>
                                    <input type="text"
                                           name="name"
                                           id="name"
                                           class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           value="<?php echo e(old('name')); ?>"
                                           placeholder="Ex: Maria da Conceição"
                                           required>
                                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label text-dark font-w600">Endereço de E-mail <span class="text-danger">*</span></label>
                                    <input type="email"
                                           name="email"
                                           id="email"
                                           class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           value="<?php echo e(old('email')); ?>"
                                           placeholder="utilizador@centroformacao.ao"
                                           required>
                                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="col-md-6">
                                    <label for="password" class="form-label text-dark font-w600">Palavra-passe <span class="text-danger">*</span></label>
                                    <input type="password"
                                           name="password"
                                           id="password"
                                           class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           placeholder="Mínimo 6 caracteres"
                                           required>
                                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label text-dark font-w600">Confirmar Palavra-passe <span class="text-danger">*</span></label>
                                    <input type="password"
                                           name="password_confirmation"
                                           id="password_confirmation"
                                           class="form-control"
                                           placeholder="Repita a palavra-passe"
                                           required>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer d-flex justify-content-end gap-2 border-top">
                            <a href="<?php echo e(route('user.index')); ?>" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save me-1"></i> Registar Utilizador
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/eneto/Documents/infosi intership/projects/centroFormacao/resources/views/admin/user/create/index.blade.php ENDPATH**/ ?>
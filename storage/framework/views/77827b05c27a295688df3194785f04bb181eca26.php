<?php $__env->startSection('title', 'Adicionar Nova Sala'); ?>

<?php $__env->startSection('content'); ?>

<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                
                <div class="card">
                    
                    
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h4 class="card-title">Adicionar Nova Sala de Aula</h4>
                        <a href="<?php echo e(route('classroom.index')); ?>" class="btn btn-primary">
                            <i class="fa fa-arrow-left me-2"></i> Voltar à Listagem
                        </a>
                    </div>
                    
                    
                    <form action="<?php echo e(route('classroom.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        
                        <div class="card-body">
                            
                            <?php if($errors->any()): ?>
                                <div class="alert alert-danger alert-dismissible fade show mb-4">
                                    <ul class="mb-0 ps-3">
                                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <div class="row">
                                
                                <div class="col-xl-6 col-sm-6 mb-3">
                                    <label for="number_of_classroom" class="form-label text-primary">
                                        Número da Sala <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" min="1" name="number_of_classroom" id="number_of_classroom" 
                                           class="form-control <?php $__errorArgs = ['number_of_classroom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           value="<?php echo e(old('number_of_classroom')); ?>" 
                                           placeholder="Ex: 101" required>
                                    <?php $__errorArgs = ['number_of_classroom'];
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

                                
                                <div class="col-xl-6 col-sm-6 mb-3">
                                    <label for="capacity" class="form-label text-primary">
                                        Capacidade de Alunos <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" min="1" name="capacity" id="capacity" 
                                           class="form-control <?php $__errorArgs = ['capacity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           value="<?php echo e(old('capacity')); ?>" 
                                           placeholder="Ex: 30" required>
                                    <small class="text-muted d-block mt-1">
                                        
                                    </small>
                                    <?php $__errorArgs = ['capacity'];
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

                                
                                <div class="col-xl-12 col-sm-12 mb-3">
                                    <label for="description" class="form-label text-primary">Observações </label>
                                    <textarea name="description" id="description" 
                                              class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                              rows="4" 
                                              placeholder="Ex: Sala equipada com 25 computadores, projetor multimédia e ar condicionado."><?php echo e(old('description')); ?></textarea>
                                    <?php $__errorArgs = ['description'];
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
                            </div>
                        </div>

                        
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save me-1"></i> Guardar Sala
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/eneto/Documents/infosi intership/projects/centroFormacao/resources/views/admin/classroom/create/index.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'Registar Candidatura'); ?>

<?php $__env->startSection('content'); ?>

<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    
                    <div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center border-bottom">
                        <div>
                            <h4 class="card-title mb-1 text-dark">Registar Nova Candidatura</h4>
                            <p class="m-0 subtitle text-muted">Preencha os dados do candidato e selecione o curso e turma pretendidos</p>
                        </div>
                        <a href="<?php echo e(route('enrollment.index')); ?>" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left me-1"></i> Voltar à Listagem
                        </a>
                    </div>
                    
                    <form action="<?php echo e(route('enrollment.store')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        
                        <div class="card-body p-4">
                            
                            <?php if($errors->any()): ?>
                                <div class="alert alert-danger alert-dismissible fade show mb-4 enrollment-alert">
                                    <h6 class="alert-heading font-w600"><i class="fa fa-exclamation-triangle me-2"></i>Por favor corrija os seguintes erros:</h6>
                                    <ul class="mb-0 ps-3">
                                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <div class="row g-4">
                                
                                <div class="col-xl-3 col-lg-4 col-md-5">
                                    <label class="form-label text-dark font-w600 d-block">Fotografia do Candidato</label>
                                    
                                    <div class="photo-upload-container">
                                        
                                        <div class="candidate-photo-preview-card">
                                            <img id="imagePreview" src="" alt="Pré-visualização" class="candidate-photo-img-hidden">
                                            <svg id="defaultAvatarSvg" width="100%" height="100%" viewBox="0 0 160 180" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect width="160" height="180" fill="#E2E8F0"/>
                                                <circle cx="80" cy="70" r="35" fill="#94A3B8"/>
                                                <path d="M25 160C25 125 50 115 80 115C110 115 135 125 135 160V180H25V160Z" fill="#94A3B8"/>
                                            </svg>
                                        </div>

                                        
                                        <div class="mb-1">
                                            <label for="image" class="file-input-wrapper">
                                                <span class="file-input-btn">Selecionar Foto</span>
                                                <span id="fileNameDisplay" class="file-input-name">Nenhum ficheiro selecionado</span>
                                            </label>
                                            <input type="file" name="image" id="image" class="d-none" accept="image/*">
                                        </div>
                                        <small class="candidate-photo-helper">Formatos aceites: JPG, PNG, WEBP (Máx: 4MB)</small>
                                    </div>
                                </div>

                                
                                <div class="col-xl-9 col-lg-8 col-md-7">
                                    <div class="row g-3">
                                        
                                        <div class="col-md-6">
                                            <label for="name" class="form-label text-dark font-w600">Nome Completo <span class="text-danger">*</span></label>
                                            <input type="text" name="name" id="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('name')); ?>" placeholder="Ex: António Manuel Silva" required>
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
                                            <label for="number_of_identify" class="form-label text-dark font-w600">Número do Bilhete de Identidade<span class="text-danger">*</span></label>
                                            <input type="text" name="number_of_identify" id="number_of_identify" class="form-control <?php $__errorArgs = ['number_of_identify'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('number_of_identify')); ?>" placeholder="Ex: 007654321LA045" required>
                                            <?php $__errorArgs = ['number_of_identify'];
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
                                            <label for="email" class="form-label text-dark font-w600">Endereço de Email <span class="text-danger">*</span></label>
                                            <input type="email" name="email" id="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('email')); ?>" placeholder="candidato@email.com" required>
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
                                            <label for="phone" class="form-label text-dark font-w600">Telefone <span class="text-danger">*</span></label>
                                            <input type="text" name="phone" id="phone" class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('phone')); ?>" placeholder="Ex: 923 123 456" required>
                                            <?php $__errorArgs = ['phone'];
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
                                            <label for="course_id" class="form-label text-dark font-w600">Curso Pretendido <span class="text-danger">*</span></label>
                                            <select id="course_id" name="course_id" class="form-control select2 <?php $__errorArgs = ['course_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                                <option value="">Selecione o Curso...</option>
                                                <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($course->id); ?>" <?php echo e(old('course_id') == $course->id ? 'selected' : ''); ?>

                                                            data-open-rooms="<?php echo e($course->open_rooms_count ?? 0); ?>">
                                                        <?php echo e($course->name); ?>

                                                        <?php if(($course->open_rooms_count ?? 0) == 0): ?>
                                                            — ⚠ Sem turmas abertas
                                                        <?php endif; ?>
                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <?php $__errorArgs = ['course_id'];
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

                                        
                                        <div class="col-md-6">
                                            <label for="shift" class="form-label text-dark font-w600">Turno Pretendido <span class="text-danger">*</span></label>
                                            <select id="shift" name="shift" class="form-control <?php $__errorArgs = ['shift'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                                <option value="Manhã" <?php echo e(old('shift', 'Manhã') == 'Manhã' ? 'selected' : ''); ?>>Manhã</option>
                                                <option value="Tarde" <?php echo e(old('shift') == 'Tarde' ? 'selected' : ''); ?>>Tarde</option>
                                                <option value="Pós-Laboral" <?php echo e(old('shift') == 'Pós-Laboral' ? 'selected' : ''); ?>>Pós-Laboral</option>
                                            </select>
                                            <?php $__errorArgs = ['shift'];
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

                                        
                                        <div class="col-12 d-none" id="waitlistOptionCard">
                                            <div class="alert alert-warning border mb-0 p-3 rounded">
                                                <div class="form-check d-flex align-items-start gap-2 mb-0">
                                                    <input class="form-check-input mt-1" type="checkbox" name="join_waitlist" id="join_waitlist" value="1" <?php echo e(old('join_waitlist') ? 'checked' : ''); ?>>
                                                    <label class="form-check-label text-dark" for="join_waitlist">
                                                        <strong class="d-block mb-1">
                                                            <i class="fa fa-clock-o me-1"></i> Ir para a Lista de Espera
                                                        </strong>
                                                        <span class="fs-7 text-muted d-block">
                                                            Este curso não possui turmas abertas neste momento. Ao confirmar esta opção, o aluno ficará registado na <strong>Lista de Espera</strong> e será adicionado automaticamente logo que uma turma for criada para este curso.
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="card-footer d-flex justify-content-end gap-2 border-top">
                            <a href="<?php echo e(route('enrollment.index')); ?>" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save me-1"></i> Guardar Candidatura
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/eneto/Documents/infosi intership/projects/centroFormacao/resources/views/admin/enrollment/create/index.blade.php ENDPATH**/ ?>
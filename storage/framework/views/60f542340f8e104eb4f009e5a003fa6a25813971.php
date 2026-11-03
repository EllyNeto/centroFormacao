<?php $__env->startSection('title', 'Adicionar Nova Turma'); ?>

<?php $__env->startSection('content'); ?>

<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h4 class="card-title mb-1">Adicionar Nova Turma</h4>
                            <p class="m-0 subtitle text-muted">Defina o curso, formador, sala física, horários e dias de formação</p>
                        </div>
                        <a href="<?php echo e(route('room.index')); ?>" class="btn btn-primary btn-sm">
                            <i class="fa fa-arrow-left me-1"></i> Voltar à Listagem
                        </a>
                    </div>
                    
                    
                    <form action="<?php echo e(route('room.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        
                        <div class="card-body">
                            
                            <?php if($errors->any()): ?>
                                <div class="alert alert-danger alert-dismissible fade show mb-4 room-form-alert">
                                    <h6 class="alert-heading font-w600"><i class="fa fa-exclamation-triangle me-2"></i>Por favor corrija os seguintes erros:</h6>
                                    <ul class="mb-0 ps-3">
                                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <div class="row">
                                
                                <div class="col-xl-6 col-sm-6">
                                    
                                    <div class="mb-3">
                                        <label for="name" class="form-label text-primary font-w600">Nome da Turma <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('name')); ?>" placeholder="Ex: Turma de Redes - Manhã T1" required>
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

                                    
                                    <div class="mb-3">
                                        <label for="course_id" class="form-label text-primary font-w600">Curso <span class="text-danger">*</span></label>
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
                                                <option value="<?php echo e($course->id); ?>" <?php echo e(old('course_id') == $course->id ? 'selected' : ''); ?>>
                                                   <?php echo e($course->name); ?>  
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

                                    
                                    <div class="mb-3">
                                        <label for="teacher_id" class="form-label text-primary font-w600">Formador<span class="text-danger">*</span></label>
                                        <select id="teacher_id" name="teacher_id" class="form-control select2 <?php $__errorArgs = ['teacher_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                            <option value="">Selecione o Formador...</option>
                                            <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($teacher->id); ?>" <?php echo e(old('teacher_id') == $teacher->id ? 'selected' : ''); ?>>
                                                    <?php echo e($teacher->name); ?> — <?php echo e($teacher->specialization); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php $__errorArgs = ['teacher_id'];
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
                                        <label for="classroom_id" class="form-label text-primary font-w600">Sala de Aula <span class="text-danger">*</span></label>
                                        <select id="classroom_id" name="classroom_id" class="form-control select2 <?php $__errorArgs = ['classroom_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                            <option value="" data-capacity="">Selecione a Sala Física...</option>
                                            <?php $__currentLoopData = $classrooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classroom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($classroom->id); ?>" data-capacity="<?php echo e($classroom->capacity); ?>" <?php echo e(old('classroom_id') == $classroom->id ? 'selected' : ''); ?>>
                                                    Sala <?php echo e($classroom->number_of_classroom); ?> (Capacidade: <?php echo e($classroom->capacity); ?> lugares)
                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php $__errorArgs = ['classroom_id'];
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
                                        <label for="max_capacity" class="form-label text-primary font-w600">Capacidade Máxima</label>
                                        <input type="number" id="max_capacity" name="max_capacity" class="form-control bg-light readonly-capacity <?php $__errorArgs = ['max_capacity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('max_capacity')); ?>" placeholder="Herdada da sala selecionada" readonly>
                                        <small class="text-muted d-block mt-1">
                                            <i class="fa fa-info-circle me-1"></i> Preenchido automaticamente com base na capacidade da sala física escolhida.
                                        </small>
                                        <?php $__errorArgs = ['max_capacity'];
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

                                
                                <div class="col-xl-6 col-sm-6">
                                    
                                    <div class="mb-3">
                                        <label for="shift" class="form-label text-primary font-w600">Turno <span class="text-danger">*</span></label>
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

                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="start_time" class="form-label text-primary font-w600">Hora de Início <span class="text-danger">*</span></label>
                                            <input type="time" name="start_time" id="start_time" class="form-control <?php $__errorArgs = ['start_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('start_time', '08:00')); ?>" required>
                                            <?php $__errorArgs = ['start_time'];
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

                                        <div class="col-md-6 mb-3">
                                            <label for="end_time" class="form-label text-primary font-w600">Hora de Término <span class="text-danger">*</span></label>
                                            <input type="time" name="end_time" id="end_time" class="form-control <?php $__errorArgs = ['end_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('end_time', '10:00')); ?>" required>
                                            <?php $__errorArgs = ['end_time'];
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

                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="start_date" class="form-label text-primary font-w600">Data de Início das Aulas</label>
                                            <input type="date" name="start_date" id="start_date" class="form-control <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('start_date')); ?>">
                                            <?php $__errorArgs = ['start_date'];
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

                                        <div class="col-md-6 mb-3">
                                            <label for="end_date" class="form-label text-primary font-w600">Data de Conclusão Prevista</label>
                                            <input type="date" name="end_date" id="end_date" class="form-control <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('end_date')); ?>">
                                            <?php $__errorArgs = ['end_date'];
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

                                    
                                    <div class="mb-3">
                                        <label class="form-label text-primary font-w600 d-block">Dias da Semana <span class="text-danger">*</span></label>
                                        <div class="d-flex flex-wrap gap-2 mt-1 p-3 border rounded bg-light">
                                            <?php
                                                $days = ['Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];
                                                $selectedDays = old('days_of_week', []);
                                                if (is_string($selectedDays)) {
                                                    $selectedDays = array_map('trim', explode(',', $selectedDays));
                                                }
                                            ?>
                                            <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="form-check me-3 mb-2">
                                                    <input class="form-check-input" type="checkbox" name="days_of_week[]" value="<?php echo e($day); ?>" id="day_<?php echo e($loop->index); ?>"
                                                        <?php echo e((is_array($selectedDays) && in_array($day, $selectedDays)) ? 'checked' : ''); ?>>
                                                    <label class="form-check-label text-dark" for="day_<?php echo e($loop->index); ?>">
                                                        <?php echo e($day); ?>

                                                    </label>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                        <?php $__errorArgs = ['days_of_week'];
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

                        
                        <div class="card-footer d-flex justify-content-end gap-2">
                            <a href="<?php echo e(route('room.index')); ?>" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Guardar Turma</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/eneto/Documents/infosi intership/projects/centroFormacao/resources/views/admin/room/create/index.blade.php ENDPATH**/ ?>
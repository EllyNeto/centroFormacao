<?php $__env->startSection('title', 'Adicionar Formador'); ?>

<?php $__env->startSection('content'); ?>

<div class="content-body">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xl-12">
				<div class="akademi-card">
					
					<div class="akademi-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
						<div>
							<h3 class="akademi-title mb-1">Adicionar Novo Formador</h3>
							<p class="akademi-subtitle mb-0">Preencha as informações pessoais e profissionais do formador</p>
						</div>
						<a href="<?php echo e(route('teacher.index')); ?>" class="btn-orange-back">
							<i class="fa fa-arrow-left"></i> Voltar à Listagem
						</a>
					</div>

					
					<form action="<?php echo e(route('teacher.store')); ?>" method="POST" enctype="multipart/form-data">
						<?php echo csrf_field(); ?>
						<div class="akademi-card-body">
							
							<?php if($errors->any()): ?>
								<div class="alert alert-danger alert-dismissible fade show mb-4 teacher-form-alert">
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
								
								<div class="col-xl-3 col-lg-4 col-md-4 mb-4">
									<label class="field-label d-block">Fotografia do Formador</label>
									
									<div class="photo-upload-container">
										
										<div class="photo-preview-card mb-3">
											<img id="imagePreview" src="" alt="Preview da Foto" class="photo-preview-img">
											<svg id="defaultAvatarSvg" width="100%" height="100%" viewBox="0 0 160 180" fill="none" xmlns="http://www.w3.org/2000/svg">
												<rect width="160" height="180" fill="#E2E8F0"/>
												<circle cx="80" cy="70" r="35" fill="#94A3B8"/>
												<path d="M25 160C25 125 50 115 80 115C110 115 135 125 135 160V180H25V160Z" fill="#94A3B8"/>
											</svg>
										</div>

										
										<div class="mb-1">
											<label for="image" class="file-input-wrapper">
												<span class="file-input-btn">Selecionar Ficheiro</span>
												<span id="fileNameDisplay" class="file-input-name">Nenhum ficheiro selecionado</span>
											</label>
											<input type="file" name="image" id="image" class="d-none" accept="image/*" onchange="previewTeacherImage(event)">
										</div>
										<small class="photo-helper-text">Formatos: JPG, PNG, WEBP (Máx: 4MB)</small>
									</div>
								</div>

								
								<div class="col-xl-9 col-lg-8 col-md-8">
									<div class="row">
										
										<div class="col-md-6 mb-4">
											<label for="name" class="field-label">Nome Completo <span class="text-danger">*</span></label>
											<input type="text" name="name" id="name" class="form-control form-control-akademi <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Ex: Maria dos Santos" value="<?php echo e(old('name')); ?>" required>
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

										
										<div class="col-md-6 mb-4">
											<label for="number_of_identify" class="field-label">Número do BI <span class="text-danger">*</span></label>
											<input type="text" name="number_of_identify" id="number_of_identify" class="form-control form-control-akademi <?php $__errorArgs = ['number_of_identify'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Ex: 000123456LA042" value="<?php echo e(old('number_of_identify')); ?>" required>
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

										
										<div class="col-md-6 mb-4">
											<label for="email" class="field-label">Email <span class="text-danger">*</span></label>
											<input type="email" name="email" id="email" class="form-control form-control-akademi <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="formador@exemplo.com" value="<?php echo e(old('email')); ?>" required>
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

										
										<div class="col-md-6 mb-4">
											<label for="gender" class="field-label">Género <span class="text-danger">*</span></label>
											<select name="gender" id="gender" class="form-control form-control-akademi <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
												<option value="" disabled <?php echo e(old('gender') ? '' : 'selected'); ?>>Selecione o género...</option>
												<option value="Masculino" <?php echo e(old('gender') == 'Masculino' ? 'selected' : ''); ?>>Masculino</option>
												<option value="Feminino" <?php echo e(old('gender') == 'Feminino' ? 'selected' : ''); ?>>Feminino</option>
											</select>
											<?php $__errorArgs = ['gender'];
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

										
										<div class="col-md-6 mb-4">
											<label for="specialization" class="field-label">Especialização <span class="text-danger">*</span></label>
											<input type="text" name="specialization" id="specialization" class="form-control form-control-akademi <?php $__errorArgs = ['specialization'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Ex: Engenharia de Software, Contabilidade" value="<?php echo e(old('specialization')); ?>" required>
											<?php $__errorArgs = ['specialization'];
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

										
										<div class="col-md-6 mb-4">
											<label for="phone" class="field-label">Número de Telefone <span class="text-danger">*</span></label>
											<input type="text" name="phone" id="phone" class="form-control form-control-akademi <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Ex: 923000000" value="<?php echo e(old('phone')); ?>" required>
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
									</div>
								</div>
							</div>
						</div>

						
						<div class="akademi-card-footer d-flex justify-content-end gap-2">
							<a href="<?php echo e(route('teacher.index')); ?>" class="btn btn-secondary">Cancelar</a>
							<button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Guardar Formador</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/eneto/Documents/infosi intership/projects/centroFormacao/resources/views/admin/teacher/create/index.blade.php ENDPATH**/ ?>
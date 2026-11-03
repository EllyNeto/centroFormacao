<?php $__env->startSection('title', 'Detalhes do Formador'); ?>

<?php $__env->startSection('content'); ?>

<div class="content-body">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xl-12">
				<div class="card">
					
					<div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center">
						<div>
							<h4 class="card-title mb-1">Perfil do Formador</h4>
							<p class="m-0 subtitle text-muted">Informações detalhadas e turmas lecionadas pelo formador</p>
						</div>
						<div>
							<a href="<?php echo e(route('teacher.edit', $teacher->id)); ?>" class="btn btn-primary btn-sm me-1">
								<i class="fa fa-pencil me-1"></i> Editar
							</a>
							<a href="<?php echo e(route('teacher.index')); ?>" class="btn btn-secondary btn-sm">
								<i class="fa fa-arrow-left me-1"></i> Voltar
							</a>
						</div>
					</div>

					<div class="card-body p-4">
						
						<div class="row align-items-center mb-4 pb-3 border-bottom">
							
							<div class="col-auto">
								<img src="<?php echo e($teacher->image ? asset('storage/' . $teacher->image) : asset('images/no-img-avatar.png')); ?>" 
									 alt="<?php echo e($teacher->name); ?>" 
									 class="rounded-circle shadow-sm" 
									 style="width: 110px; height: 110px; object-fit: cover; border: 3px solid #1E40AF;">
							</div>
							<div class="col">
								<h3 class="font-w600 mb-1" style="color: #1e293b;"><?php echo e($teacher->name); ?></h3>
								<div class="d-flex align-items-center flex-wrap gap-2">
									<span class="badge light badge-primary px-3 py-2 fs-6"><?php echo e($teacher->specialization); ?></span>
									<span class="text-muted"><i class="fa fa-id-card me-1 text-info"></i> BI: <?php echo e($teacher->number_of_identify); ?></span>
									<span class="text-muted"><i class="fa fa-calendar me-1"></i> Registado em: <?php echo e($teacher->created_at ? $teacher->created_at->format('d/m/Y') : 'N/A'); ?></span>
								</div>
							</div>
						</div>

						
						<div class="row g-4 mb-5">
							<div class="col-xl-4 col-md-6">
								<div class="p-3 rounded bg-light border">
									<small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Nome Completo</small>
									<span class="fs-6 font-w600 text-dark"><?php echo e($teacher->name); ?></span>
								</div>
							</div>

							<div class="col-xl-4 col-md-6">
								<div class="p-3 rounded bg-light border">
									<small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Endereço de Email</small>
									<span class="fs-6 font-w600 text-dark"><i class="fa fa-envelope text-primary me-1"></i> <?php echo e($teacher->email); ?></span>
								</div>
							</div>

							<div class="col-xl-4 col-md-6">
								<div class="p-3 rounded bg-light border">
									<small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Contacto Telefónico</small>
									<span class="fs-6 font-w600 text-dark"><i class="fa fa-phone text-success me-1"></i> <?php echo e($teacher->phone); ?></span>
								</div>
							</div>

							<div class="col-xl-4 col-md-6">
								<div class="p-3 rounded bg-light border">
									<small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Género</small>
									<span class="fs-6 font-w600 text-dark"><i class="fa fa-user text-secondary me-1"></i> <?php echo e($teacher->gender ?? 'Não informado'); ?></span>
								</div>
							</div>

							<div class="col-xl-4 col-md-6">
								<div class="p-3 rounded bg-light border">
									<small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Especialização / Área</small>
									<span class="fs-6 font-w600 text-dark"><?php echo e($teacher->specialization); ?></span>
								</div>
							</div>

							<div class="col-xl-4 col-md-6">
								<div class="p-3 rounded bg-light border">
									<small class="text-muted d-block text-uppercase font-w600 fs-7 mb-1">Número do Bilhete de Identificação (BI)</small>
									<span class="fs-6 font-w600 text-dark"><i class="fa fa-id-card text-info me-1"></i> <?php echo e($teacher->number_of_identify); ?></span>
								</div>
							</div>
						</div>

						
						<div class="mt-4">
							<h5 class="font-w600 mb-3" style="color: #1e293b;">
								<i class="fa fa-graduation-cap text-primary me-2"></i> Turmas Atribuídas a este Formador
							</h5>
							<div class="table-responsive">
								<table class="table table-bordered table-hover style-1">
									<thead class="thead-primary">
										<tr>
											<th>Código da Turma</th>
											<th>Curso</th>
											<th>Sala Física</th>
											<th>Horário / Período</th>
											<th>Estado</th>
										</tr>
									</thead>
									<tbody>
										<?php $__empty_1 = true; $__currentLoopData = $teacher->rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
											<tr>
												<td><strong><?php echo e($room->code_room ?? 'TURMA-' . $room->id); ?></strong></td>
												<td><?php echo e($room->course->name ?? 'N/A'); ?></td>
												<td>
													<?php if($room->classroom): ?>
														Sala <?php echo e($room->classroom->number_of_classroom); ?>

													<?php else: ?>
														<span class="text-muted">Não atribuída</span>
													<?php endif; ?>
												</td>
												<td>
													<div><?php echo e($room->start_time); ?> - <?php echo e($room->end_time); ?></div>
													<small class="text-muted"><?php echo e($room->shift); ?></small>
												</td>
												<td>
													<?php if($room->status == 'Aberta'): ?>
														<span class="badge light badge-success">Aberta</span>
													<?php elseif($room->status == 'Em curso'): ?>
														<span class="badge light badge-warning">Em curso</span>
													<?php elseif($room->status == 'Concluída'): ?>
														<span class="badge light badge-info">Concluída</span>
													<?php else: ?>
														<span class="badge light badge-danger"><?php echo e($room->status); ?></span>
													<?php endif; ?>
												</td>
											</tr>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
											<tr>
												<td colspan="5" class="text-center py-3 text-muted">
													<i class="fa fa-info-circle me-1"></i> Este formador ainda não possui turmas atribuídas.
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
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/eneto/Documents/infosi intership/projects/centroFormacao/resources/views/admin/teacher/details/index.blade.php ENDPATH**/ ?>
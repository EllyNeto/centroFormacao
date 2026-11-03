<?php $__env->startSection('title', 'Listar Salas de Aula'); ?>

<?php $__env->startSection('content'); ?>

<div class="content-body">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xl-12">
				
				<div class="card" id="accordion-four">
					
					
					<div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center">
						<div>
							<h4 class="card-title mb-1">Gestão de Salas de Aula</h4>
							<p class="m-0 subtitle text-muted">Listagem dos espaços físicos e capacidade de lugares do centro de formação</p>
						</div>
						
						<a href="<?php echo e(route('classroom.create')); ?>" class="btn btn-primary btn-sm">
							<i class="fa fa-plus me-1"></i> Adicionar Nova Sala
						</a>
					</div>

					
					<div class="card-body p-4">
						
						
						<?php if(session('success')): ?>
							<div class="alert alert-success alert-dismissible fade show mb-4">
								<i class="fa fa-check-circle me-2"></i> <?php echo e(session('success')); ?>

								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
						<?php endif; ?>

						
						<?php if(session('error')): ?>
							<div class="alert alert-danger alert-dismissible fade show mb-4">
								<i class="fa fa-exclamation-triangle me-2"></i> <?php echo e(session('error')); ?>

								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
						<?php endif; ?>

						
						<div class="table-responsive">
							<table id="example4" class="display table table-hover style-1 custom-table">
								<thead>
									<tr>
										<th>Sala</th>
										<th>Capacidade</th>
										<th>Turmas Alocadas</th>
										<th class="text-end">Acções</th>
									</tr>
								</thead>
								<tbody>
									
									<?php $__empty_1 = true; $__currentLoopData = $classrooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classroom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
										<tr>
											
											<td>
												<a href="<?php echo e(route('classroom.show', $classroom->id)); ?>" class="badge light badge-primary fs-14 fw-bold text-decoration-none">
													Sala <?php echo e($classroom->number_of_classroom); ?>

												</a>
											</td>

											
											<td>
												<i class="fa fa-users me-1 text-muted"></i>
												<?php echo e($classroom->capacity); ?> lugares
											</td>

											
											<td>
												<span class="badge light <?php echo e($classroom->rooms_count > 0 ? 'badge-info' : 'badge-secondary'); ?>">
													<?php echo e($classroom->rooms_count); ?> turma(s)
												</span>
											</td>

											
											<td class="text-end action-cell">
												<div class="dropdown dropup ms-auto text-end c-pointer">
													<div class="btn-link" data-bs-toggle="dropdown" data-bs-placement="top" aria-expanded="false" role="button">
														<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
															<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																<rect x="0" y="0" width="24" height="24"></rect>
																<circle fill="#000000" cx="5" cy="12" r="2"></circle>
																<circle fill="#000000" cx="12" cy="12" r="2"></circle>
																<circle fill="#000000" cx="19" cy="12" r="2"></circle>
															</g>
														</svg>
													</div>

													<div class="dropdown-menu dropdown-menu-end">
														<a class="dropdown-item" href="<?php echo e(route('classroom.show', $classroom->id)); ?>">
															<i class="fa fa-eye me-2 text-primary"></i> Ver Detalhes
														</a>
														<a class="dropdown-item" href="<?php echo e(route('classroom.edit', $classroom->id)); ?>">
															<i class="fa fa-edit me-2 text-warning"></i> Editar
														</a>
														<form action="<?php echo e(route('classroom.destroy', $classroom->id)); ?>" method="POST" class="d-inline" data-confirm="Tem a certeza que deseja eliminar a Sala <?php echo e($classroom->number_of_classroom); ?>?">
															<?php echo csrf_field(); ?>
															<?php echo method_field('DELETE'); ?>
															<button type="submit" class="dropdown-item text-danger border-0 bg-transparent w-100 text-start">
																<i class="fa fa-trash me-2"></i> Eliminar
															</button>
														</form>
													</div>
												</div>
											</td>
										</tr>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
										
										<tr>
											<td colspan="4" class="text-center py-4 text-muted">
												<i class="fa fa-info-circle me-1"></i> Nenhuma sala de aula registada até ao momento.
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

<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/eneto/Documents/infosi intership/projects/centroFormacao/resources/views/admin/classroom/list/index.blade.php ENDPATH**/ ?>
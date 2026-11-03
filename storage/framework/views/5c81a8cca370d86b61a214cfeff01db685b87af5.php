<?php $__env->startSection('title', 'Listar Formandos'); ?>

<?php $__env->startSection('content'); ?>

<div class="content-body">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xl-12">
				
				<div class="card" id="accordion-four">
					
					<div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center">
						<div>
							<h4 class="card-title mb-1 text-dark">Gestão de Formandos</h4>
							<p class="m-0 subtitle text-muted">Listagem oficial de formandos matriculados no centro de formação</p>
						</div>
						
						<a href="<?php echo e(route('student.create')); ?>" class="btn btn-primary btn-sm">
							<i class="fa fa-user-plus me-1"></i> Nova Matrícula
						</a>
					</div>

					<div class="card-body p-4">
						
						<?php if(session('success')): ?>
							<div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
								<i class="fa fa-check-circle me-2"></i> <?php echo e(session('success')); ?>

								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
						<?php endif; ?>

						
						<?php if(session('error')): ?>
							<div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
								<i class="fa fa-exclamation-triangle me-2"></i> <?php echo e(session('error')); ?>

								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
						<?php endif; ?>

						
						<?php if(session('info')): ?>
							<div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
								<i class="fa fa-info-circle me-2"></i> <?php echo e(session('info')); ?>

								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
						<?php endif; ?>

						
						<div class="table-responsive">
							<table id="example4" class="display table table-hover style-1 custom-table">
								<thead>
									<tr>
										<th>Código</th>
										<th>Formando</th>
										<th>Curso</th>
										<th>Turma</th>
										<th class="text-end">Acções</th>
									</tr>
								</thead>
								<tbody>
									<?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
										<tr>
											
											<td>
												<span class="badge light badge-primary font-w600 fs-7">
													<?php echo e($student->formattedCode()); ?>

												</span>
											</td>

											
											<td>
												<a href="<?php echo e(route('student.show', $student->id)); ?>" class="text-dark font-w600 text-hover-primary">
													<?php echo e($student->name); ?>

												</a>
											</td>

											
											<td>
												<?php if($student->course): ?>
													<span class="badge light badge-info">
														<?php echo e($student->course->name); ?>

													</span>
												<?php else: ?>
													<span class="text-muted">N/D</span>
												<?php endif; ?>
											</td>

											
											<td>
												<?php if($student->room): ?>
													<strong class="text-dark"><?php echo e($student->room->name); ?></strong>
												<?php else: ?>
													<span class="badge light badge-warning">Não alocado</span>
												<?php endif; ?>
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
														<a class="dropdown-item" href="<?php echo e(route('student.show', $student->id)); ?>">
															<i class="fa fa-eye me-2 text-primary"></i> Ver Ficha Completa
														</a>
														<a class="dropdown-item" href="<?php echo e(route('student.edit', $student->id)); ?>">
															<i class="fa fa-pencil me-2 text-warning"></i> Editar Dados
														</a>
														<?php if($student->room_id): ?>
															<a class="dropdown-item" href="<?php echo e(route('room.printStudents', $student->room_id)); ?>" target="_blank">
																<i class="fa fa-print me-2 text-info"></i> Pauta da Turma
															</a>
														<?php endif; ?>
														<form action="<?php echo e(route('student.destroy', $student->id)); ?>" method="POST" class="d-inline" data-confirm="Tem a certeza que deseja remover o formando <?php echo e($student->name); ?>? A inscrição de origem retornará ao estado Pago.">
															<?php echo csrf_field(); ?>
															<?php echo method_field('DELETE'); ?>
															<button type="submit" class="dropdown-item text-danger border-0 bg-transparent w-100 text-start">
																<i class="fa fa-trash me-2 text-danger"></i> Eliminar
															</button>
														</form>
													</div>
												</div>
											</td>
										</tr>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
										
										<tr>
											<td colspan="5" class="text-center py-5 text-muted">
												<i class="fa fa-graduation-cap fa-3x mb-3 d-block text-muted"></i>
												<h5 class="text-dark">Nenhum formando matriculado até ao momento</h5>
												<p class="text-muted fs-7">Para matricular formandos, efetive as inscrições que já possuem pagamento liquidado ('Pago').</p>
												<a href="<?php echo e(route('enrollment.index')); ?>" class="btn btn-outline-primary btn-sm mt-2">
													<i class="fa fa-list me-1"></i> Ver Candidaturas
												</a>
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
<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/eneto/Documents/infosi intership/projects/centroFormacao/resources/views/admin/student/list/index.blade.php ENDPATH**/ ?>
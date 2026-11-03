<?php $__env->startSection('title', 'Listar Turmas'); ?>

<?php $__env->startSection('content'); ?>

<div class="content-body">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xl-12">
				
				<div class="card" id="accordion-four">
					
					<div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center">
						<div>
							<h4 class="card-title mb-1">Gestão de Turmas</h4>
							<p class="m-0 subtitle text-muted">Listagem e controlo de vagas das turmas ativas e concluídas</p>
						</div>
						
						<a href="<?php echo e(route('room.create')); ?>" class="btn btn-primary btn-sm">
							<i class="fa fa-plus me-1"></i> Adicionar Nova Turma
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

						
						<div class="table-responsive">
							<table id="example4" class="display table style-1 custom-table room-table-min-width">
								<thead>
									<tr>
										<th>Turma</th>
										<th>Curso</th>
										<th>Formador</th>
										<th>Sala Física</th>
										<th>Horário</th>
										<th>Vagas</th>
										<th>Estado</th>
										<th class="text-end">Acções</th>
									</tr>
								</thead>
								<tbody>
									
									<?php $__empty_1 = true; $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
										<?php
											$occupied = $room->occupiedSeats();
											$capacity = $room->max_capacity;
											$percentage = $room->occupancyPercentage();
										?>
										<tr>
											
											<td>
												<a href="<?php echo e(route('room.show', $room->id)); ?>" class="text-dark font-w600 d-block">
													<?php echo e($room->name); ?>

												</a>
												<?php if($room->start_date && $room->end_date): ?>
													<small class="d-block text-muted">
														<i class="fa fa-calendar me-1"></i> <?php echo e($room->start_date->format('d/m/Y')); ?> - <?php echo e($room->end_date->format('d/m/Y')); ?>

													</small>
												<?php endif; ?>
											</td>

											
											<td>
												<span class="badge light badge-primary"><?php echo e($room->course->name ?? 'N/A'); ?></span>
											</td>

											
											<td>
												<div class="d-flex align-items-center">
													<?php if($room->teacher && $room->teacher->image): ?>
														<img src="<?php echo e(asset('storage/' . $room->teacher->image)); ?>" class="rounded-circle avatar avatar-sm me-2 room-avatar-sm" alt="<?php echo e($room->teacher->name); ?>">
													<?php endif; ?>
													<span><?php echo e($room->teacher->name ?? 'Não atribuído'); ?></span>
												</div>
											</td>

											
											<td>
												<?php if($room->classroom): ?>
													<span class="badge light badge-info font-w500">
														<i class="fa fa-building me-1"></i> Sala <?php echo e($room->classroom->number_of_classroom); ?>

													</span>
												<?php else: ?>
													<span class="text-muted">Sem sala</span>
												<?php endif; ?>
											</td>

											
											<td>
												<div>
													<i class="fa fa-clock-o me-1 text-muted"></i>
													<?php echo e(\Carbon\Carbon::parse($room->start_time)->format('H:i')); ?> - <?php echo e(\Carbon\Carbon::parse($room->end_time)->format('H:i')); ?>

												</div>
												<small class="text-muted"><?php echo e($room->shift); ?></small>
											</td>

											
											<td>
												<div class="d-flex align-items-center gap-2">
													<small class="font-w600 text-dark">
														<?php echo e($occupied); ?>/<?php echo e($capacity); ?>

													</small>
												</div>
												<?php if($room->isFull()): ?>
													<small class="text-danger font-w500 d-block"><i class="fa fa-exclamation-circle"></i> Lotada</small>
												<?php else: ?>
													<small class="text-success d-block"><?php echo e($room->availableSeats()); ?> livres</small>
												<?php endif; ?>
											</td>

											
											<td>
												<?php if($room->status == 'Aberta'): ?>
													<span class="badge badge-success light">Aberta</span>
												<?php elseif($room->status == 'Em curso'): ?>
													<span class="badge badge-warning light">Em curso</span>
												<?php elseif($room->status == 'Concluída'): ?>
													<span class="badge badge-info light">Concluída</span>
												<?php else: ?>
													<span class="badge badge-danger light"><?php echo e($room->status); ?></span>
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
														<a class="dropdown-item" href="<?php echo e(route('room.show', $room->id)); ?>">
															<i class="fa fa-eye me-2 text-primary"></i> Ver Detalhes
														</a>
														<a class="dropdown-item" href="<?php echo e(route('room.printStudents', $room->id)); ?>" target="_blank">
															<i class="fa fa-print me-2 text-info"></i> Imprimir Pauta
														</a>
														<a class="dropdown-item" href="<?php echo e(route('room.edit', $room->id)); ?>">
															<i class="fa fa-pencil me-2 text-warning"></i> Editar
														</a>
														<form action="<?php echo e(route('room.destroy', $room->id)); ?>" method="POST" class="d-inline" data-confirm="Tem a certeza que deseja eliminar a turma <?php echo e($room->name); ?>?">
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
											<td colspan="8" class="text-center py-4 text-muted">
												<i class="fa fa-info-circle me-1"></i> Nenhuma turma registada no sistema.
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
<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/eneto/Documents/infosi intership/projects/centroFormacao/resources/views/admin/room/list/index.blade.php ENDPATH**/ ?>
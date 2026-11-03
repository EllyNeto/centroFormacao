<?php $__env->startSection('title', 'Gestão de Utilizadores'); ?>

<?php $__env->startSection('content'); ?>
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header flex-wrap d-flex justify-content-between px-4 py-3 align-items-center border-bottom">
                        <div>
                            <h4 class="card-title mb-1 text-dark">Utilizadores do Sistema</h4>
                            <p class="m-0 subtitle text-muted">Contas administrativas com acesso autorizado à plataforma</p>
                        </div>
                        <a href="<?php echo e(route('user.create')); ?>" class="btn btn-primary btn-sm">
                            <i class="fa fa-user-plus me-1"></i> Registar Novo Utilizador
                        </a>
                    </div>

                    <div class="card-body p-4">
                        <?php if(session('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show mb-4">
                                <i class="fa fa-check-circle me-2"></i><?php echo e(session('success')); ?>

                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <?php if(session('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show mb-4">
                                <i class="fa fa-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <div class="table-responsive">
                            <table id="example4" class="display table table-hover style-1 custom-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nome Completo</th>
                                        <th>E-mail</th>
                                        <th class="text-end">Acções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <span class="badge light badge-primary font-w600">#<?php echo e($user->id); ?></span>
                                            </td>
                                            <td>
                                                <span class="font-w600 text-dark"><?php echo e($user->name); ?></span>
                                                <?php if(Auth::id() === $user->id): ?>
                                                    <span class="badge light badge-success ms-2">Sessão Atual</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($user->email); ?></td>
                                            <td class="text-end">
                                                <?php if(Auth::id() !== $user->id): ?>
                                                    <form action="<?php echo e(route('user.destroy', $user->id)); ?>" method="POST" class="d-inline" data-confirm="Tem a certeza que deseja remover este utilizador?">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="btn btn-outline-danger btn-xs">
                                                            <i class="fa fa-trash me-1"></i> Remover
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <span class="text-muted fs-8">Conta em uso</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/eneto/Documents/infosi intership/projects/centroFormacao/resources/views/admin/user/list/index.blade.php ENDPATH**/ ?>
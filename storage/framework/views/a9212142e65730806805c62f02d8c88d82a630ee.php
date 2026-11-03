<!DOCTYPE html>
<html lang="pt-AO">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Entar em contacto com administração</title>
    <link rel="shortcut icon" type="image/png" href="<?php echo e(asset('images/favicon.png')); ?>">
    <link href="<?php echo e(asset('css/style.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/auth.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet">
</head>
<body>
    <div class="cf-auth-page">
        <div class="cf-auth-card">
            <div class="cf-auth-header">
                <div class="cf-auth-logo-badge">
                    <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="#FFFFFF" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                    </svg>
                </div>
                <h1 class="cf-auth-title">Centro de Formação</h1>
                <p class="cf-auth-subtitle">Plataforma de Gestão Académica e Financeira</p>
            </div>

            <div class="cf-auth-body">
                <?php if(session('status')): ?>
                    <div class="alert alert-success py-2 fs-7 mb-3">
                        <i class="fa fa-check-circle me-1"></i> <?php echo e(session('status')); ?>

                    </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="alert alert-danger py-2 fs-7 mb-3">
                        <i class="fa fa-exclamation-circle me-1"></i> <?php echo e($errors->first()); ?>

                    </div>
                <?php endif; ?>

                <form <form action="<?php echo e(route('lead.store')); ?>" method="POST" class="mt-3">
                    <?php echo csrf_field(); ?>

                <div class="mb-3">
                        <label for="lead_email" class="cf-auth-label">Deixe o seu e‑mail para contacto futuro</label>
                        <input type="email"
                               name="lead_email"
                               id="lead_email"
                               class="form-control cf-auth-input <?php $__errorArgs = ['lead_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               placeholder="Introduza o seu e‑mail"
                               required>
                    </div>

                     <button type="submit" class="cf-auth-btn">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                            <polyline points="10 17 15 12 10 7"/>
                            <line x1="15" y1="12" x2="3" y2="12"/>
                        </svg>
                        <span>Enviar</span>
                    </button>
                </form>

                
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH /home/eneto/Documents/infosi intership/projects/centroFormacao/resources/views/auth/contact.blade.php ENDPATH**/ ?>
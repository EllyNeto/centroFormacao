<!DOCTYPE html>
<html lang="pt-AO">
<!--
    Sistema de Gestão do Centro de Formação
    Layout Principal - Baseado no template Admin Dashboard
    Modificado para atender às necessidades específicas do Centro de Formação
    
    SEGURANÇA: Este sistema deve ser protegido com autenticação adequada
    antes de ser utilizado em produção. Consulte a documentação do Laravel
    sobre sistemas de autenticação.
-->
    	
   <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="Equipe de Desenvolvimento - Centro de Formação" >
	<meta name="robots" content="noindex, nofollow" >
	<meta name="keywords" content="centro de formação, educação, gestão escolar, admin dashboard, instituição de ensino" >
	<meta name="description" content="Sistema de Gestão do Centro de Formação - Plataforma completa para administração de cursos, formandos, formadores e finanças institucionais" >
	<meta property="og:title" content="Centro de Formação : Sistema de Gestão Educacional" >
	<meta property="og:description" content="Centro de Formação - Sistema integrado de gestão educacional para instituições de ensino" >
	<meta property="og:image" content="<?php echo e(asset('images/logo.png')); ?>" >
	<meta name="format-detection" content="telephone=no">

	<!-- Mobile Specific -->
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Page Title Here -->
	<title><?php echo $__env->yieldContent('title', 'Centro de Formação : Sistema de Gestão Educacional'); ?></title>

<!-- FAVICONS ICON -->
	<link rel="shortcut icon" type="image/png" href="<?php echo e(asset('images/favicon.png')); ?>" >
	<link rel="stylesheet" href="<?php echo e(asset('vendor/chartist/css/chartist.min.css')); ?>">
	<link href="<?php echo e(asset('vendor/wow-master/css/libs/animate.css')); ?>" rel="stylesheet">
	<link href="<?php echo e(asset('vendor/bootstrap-select/dist/css/bootstrap-select.min.css')); ?>" rel="stylesheet">
	<link href="<?php echo e(asset('vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css')); ?>" rel="stylesheet">
	<link href="<?php echo e(asset('vendor/sweetalert2/dist/sweetalert2.min.css')); ?>" rel="stylesheet">
	
	<!-- Google Fonts & Material Icons -->
	<link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
	<!-- Style css -->
	<link href="<?php echo e(asset('css/teacher-form.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet">
	<link href="<?php echo e(asset('css/turma-form.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet">
	<link href="<?php echo e(asset('css/enrollment-form.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet">
	<link href="<?php echo e(asset('css/payment-form.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet">
	<link href="<?php echo e(asset('css/style.css')); ?>" rel="stylesheet">
	<!-- Datatable -->
	<link href="<?php echo e(asset('vendor/datatables/css/jquery.dataTables.min.css')); ?>" rel="stylesheet">
	<!-- Select2 CSS -->
	<link href="<?php echo e(asset('vendor/select2/css/select2.min.css')); ?>" rel="stylesheet">
	<!-- Custom Stylesheet -->
	<link href="<?php echo e(asset('vendor/jquery-nice-select/css/nice-select.css')); ?>" rel="stylesheet">
	
	<link href="<?php echo e(asset('css/custom-tables.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet">
	
	<link href="<?php echo e(asset('css/custom-selects.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet">

	<!-- Script de Inicialização e Persistência do Modo Escuro -->
	<script src="<?php echo e(asset('js/theme-init.js')); ?>?v=<?php echo e(time()); ?>"></script>

	<?php echo $__env->yieldPushContent('styles'); ?>
	
</head>
<body data-theme-version="<?php echo e($_COOKIE['version'] ?? 'light'); ?>">

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper" class="show <?php echo $__env->yieldContent('wrapper_class', ''); ?>">
		<div class="wallet-bar-close"></div>
		<?php echo $__env->make('layout.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
		<?php echo $__env->make('layout.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
		<main>
			<?php echo $__env->yieldContent('content'); ?>
		</main>
		<?php echo $__env->make('layout.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
	</div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="<?php echo e(asset('vendor/global/global.min.js')); ?>"></script>
	<script src="<?php echo e(asset('vendor/bootstrap-select/dist/js/bootstrap-select.min.js')); ?>"></script>
	<script src="<?php echo e(asset('vendor/wow-master/dist/wow.min.js')); ?>"></script>
	<script src="<?php echo e(asset('vendor/sweetalert2/dist/sweetalert2.min.js')); ?>"></script>
	<script src="<?php echo e(asset('vendor/chart.js/Chart.bundle.min.js')); ?>"></script>

	<!-- Script Datatable -->
	<script src="<?php echo e(asset('vendor/datatables/js/jquery.dataTables.min.js')); ?>"></script>
	<script src="<?php echo e(asset('js/plugins-init/datatables.init.js')); ?>?v=<?php echo e(time()); ?>"></script>
	<script src="<?php echo e(asset('vendor/jquery-nice-select/js/jquery.nice-select.min.js')); ?>"></script>

	<!-- Select2 & Form JS -->
	<script src="<?php echo e(asset('js/teacher-form.js')); ?>?v=<?php echo e(time()); ?>"></script>
	<script src="<?php echo e(asset('vendor/select2/js/select2.full.min.js')); ?>"></script>
	<script src="<?php echo e(asset('js/turma-form.js')); ?>?v=<?php echo e(time()); ?>"></script>
	<script src="<?php echo e(asset('js/enrollment-form.js')); ?>?v=<?php echo e(time()); ?>"></script>
	<script src="<?php echo e(asset('js/payment-form.js')); ?>?v=<?php echo e(time()); ?>"></script>
	<script src="<?php echo e(asset('js/dashboard-init.js')); ?>?v=<?php echo e(time()); ?>"></script>

	<!-- Currency Formatter Utility -->
	<script src="<?php echo e(asset('js/currency-formatter.js')); ?>"></script>

	<?php echo $__env->yieldPushContent('scripts'); ?>

	<script src="<?php echo e(asset('js/custom.min.js')); ?>"></script>
	<script src="<?php echo e(asset('js/dlabnav-init.js')); ?>?v=<?php echo e(time()); ?>"></script>
	<script src="<?php echo e(asset('js/highlight.min.js')); ?>"></script>
	<script src="<?php echo e(asset('js/table-dropdown.js')); ?>?v=<?php echo e(time()); ?>"></script>
	<script src="<?php echo e(asset('js/theme-init.js')); ?>?v=<?php echo e(time()); ?>"></script>
</body>
</html>
<?php /**PATH /home/eneto/Documents/infosi intership/projects/centroFormacao/resources/views/layout/main.blade.php ENDPATH**/ ?>
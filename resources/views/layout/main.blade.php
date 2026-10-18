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
	<meta property="og:image" content="{{asset('images/logo.png')}}" >
	<meta name="format-detection" content="telephone=no">

	<!-- Mobile Specific -->
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Page Title Here -->
	<title>@yield('title', 'Centro de Formação : Sistema de Gestão Educacional')</title>

<!-- FAVICONS ICON -->
	<link rel="shortcut icon" type="image/png" href="{{asset('images/favicon.png')}}" >
	<link rel="stylesheet" href="{{asset('vendor/chartist/css/chartist.min.css')}}">
	<link href="{{asset('vendor/wow-master/css/libs/animate.css')}}" rel="stylesheet">
	<link href="{{asset('vendor/bootstrap-select/dist/css/bootstrap-select.min.css')}}" rel="stylesheet">
	<link href="{{asset('vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet">
	<link href="{{asset('vendor/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet">
	
	<!-- Google Fonts & Material Icons -->
	<link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
	<!-- Style css -->
	<link href="{{ asset('css/teacher-form.css') }}?v={{ time() }}" rel="stylesheet">
	<link href="{{ asset('css/turma-form.css') }}?v={{ time() }}" rel="stylesheet">
	<link href="{{ asset('css/enrollment-form.css') }}?v={{ time() }}" rel="stylesheet">
	<link href="{{ asset('css/payment-form.css') }}?v={{ time() }}" rel="stylesheet">
	<link href="{{asset('css/style.css')}}" rel="stylesheet">
	<!-- Datatable -->
	<link href="{{asset('vendor/datatables/css/jquery.dataTables.min.css')}}" rel="stylesheet">
	<!-- Select2 CSS -->
	<link href="{{asset('vendor/select2/css/select2.min.css')}}" rel="stylesheet">
	<!-- Custom Stylesheet -->
	<link href="{{asset('vendor/jquery-nice-select/css/nice-select.css')}}" rel="stylesheet">
	{{-- Folha de Estilos Personalizada para UI/UX Institucional, Tabelas e Modo Escuro --}}
	<link href="{{asset('css/custom-tables.css')}}?v={{ time() }}" rel="stylesheet">
	{{-- Estilos para Selects Nativos com Fundo Branco Limpo --}}
	<link href="{{asset('css/custom-selects.css')}}?v={{ time() }}" rel="stylesheet">

	<!-- Script de Inicialização e Persistência do Modo Escuro -->
	<script src="{{asset('js/theme-init.js')}}?v={{ time() }}"></script>

	@stack('styles')
	
</head>
<body data-theme-version="{{ $_COOKIE['version'] ?? 'light' }}">

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper" class="show @yield('wrapper_class', '')">
		<div class="wallet-bar-close"></div>
		@include('layout.header')
		@include('layout.menu')
		<main>
			@yield('content')
		</main>
		@include('layout.footer')
	</div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="{{asset('vendor/global/global.min.js')}}"></script>
	<script src="{{asset('vendor/bootstrap-select/dist/js/bootstrap-select.min.js')}}"></script>
	<script src="{{asset('vendor/wow-master/dist/wow.min.js')}}"></script>
	<script src="{{asset('vendor/sweetalert2/dist/sweetalert2.min.js')}}"></script>
	<script src="{{asset('vendor/chart.js/Chart.bundle.min.js')}}"></script>

	<!-- Script Datatable -->
	<script src="{{asset('vendor/datatables/js/jquery.dataTables.min.js')}}"></script>
	<script src="{{asset('js/plugins-init/datatables.init.js')}}?v={{ time() }}"></script>
	<script src="{{asset('vendor/jquery-nice-select/js/jquery.nice-select.min.js')}}"></script>

	<!-- Select2 & Form JS -->
	<script src="{{ asset('js/teacher-form.js') }}?v={{ time() }}"></script>
	<script src="{{asset('vendor/select2/js/select2.full.min.js')}}"></script>
	<script src="{{ asset('js/turma-form.js') }}?v={{ time() }}"></script>
	<script src="{{ asset('js/enrollment-form.js') }}?v={{ time() }}"></script>
	<script src="{{ asset('js/payment-form.js') }}?v={{ time() }}"></script>
	<script src="{{ asset('js/dashboard-init.js') }}?v={{ time() }}"></script>

	<!-- Currency Formatter Utility -->
	<script src="{{asset('js/currency-formatter.js')}}"></script>

	@stack('scripts')

	<script src="{{asset('js/custom.min.js')}}"></script>
	<script src="{{asset('js/dlabnav-init.js')}}?v={{ time() }}"></script>
	<script src="{{asset('js/highlight.min.js')}}"></script>
	<script src="{{asset('js/table-dropdown.js')}}?v={{ time() }}"></script>
	<script src="{{asset('js/theme-init.js')}}?v={{ time() }}"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from http://akademi.dexignlab.com/xhtml/student.html by HTTrack Website Copier/3.x [XR&CO], Sun, 23 Aug 2026 17:14:09 GMT -->
<head>
    	
   <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="DexignLab" >
	<meta name="robots" content="" >
	<meta name="keywords" content="school, school admin, education, academy, admin dashboard, college, college management, education management, institute, school management, school management system, student management, teacher management, university, university management" >
	<meta name="description" content="Discover Akademi - the ultimate admin dashboard and Bootstrap 5 template. Specially designed for professionals, and for business. Akademi provides advanced features and an easy-to-use interface for creating a top-quality website with School and Education Dashboard" >
	<meta property="og:title" content="Akademi : School and Education Management Admin Dashboard Template" >
	<meta property="og:description" content="Akademi - the ultimate admin dashboard and Bootstrap 5 template. Specially designed for professionals, and for business. Akademi provides advanced features and an easy-to-use interface for creating a top-quality website with School and Education Dashboard">
	<meta property="og:image" content="social-image.png" >
	<meta name="format-detection" content="telephone=no">

	<!-- Mobile Specific -->
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Page Title Here -->
	<title>@yield('title', 'Akademi : School and Education Management Admin Dashboard Template')</title>

<!-- FAVICONS ICON -->
	<link rel="shortcut icon" type="image/png" href="{{asset('images/favicon.png')}}" >
	<link rel="stylesheet" href="{{asset('vendor/chartist/css/chartist.min.css')}}">
	<link href="{{asset('vendor/wow-master/css/libs/animate.css')}}" rel="stylesheet">
	<link href="{{asset('vendor/bootstrap-select/dist/css/bootstrap-select.min.css')}}" rel="stylesheet">
	
	<link href="{{asset('vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet">
	
	
	<!-- Style css -->
	<link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
	<!-- Style css -->
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
	{{-- Folha de Estilos Personalizada para Tabelas Centralizadas e Ampliadas --}}
    <link href="{{asset('css/custom-tables.css')}}" rel="stylesheet">

	<!-- Datatable -->
    <link href="{{asset('vendor/datatables/css/jquery.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/bootstrap-select/dist/css/bootstrap-select.min.css')}}" rel="stylesheet">
    <!-- Custom Stylesheet -->
	<link href="{{asset('vendor/jquery-nice-select/css/nice-select.css')}}" rel="stylesheet">

	@stack('styles')
	
</head>
<body data-theme-version="{{ $_COOKIE['version'] ?? 'light' }}">

    <!--*******************
        Preloader start
    ********************-->
	{{-- <div id="preloader">
		<div class="loader">
			<div class="dots">
				<div class="dot mainDot"></div>
				<div class="dot"></div>
				<div class="dot"></div>
				<div class="dot"></div>
				<div class="dot"></div>
			</div>
		</div>
	  </div> --}}
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper" class="show @yield('wrapper_class', '')">
		<div class="wallet-bar-close"></div>
		@include('layouts.header')
		@include('layouts.menu')
		<main>
			@yield('content')
		</main>
		@include('layouts.footer')
		
		<!--**********************************
           Support ticket button start
        ***********************************-->
		
        <!--**********************************
           Support ticket button end
        ***********************************-->


	</div>
    <!--**********************************
        Main wrapper end
    ***********************************-->
	<!--**********************************
		Modal
	***********************************-->
	

			<!-- Modal -->
			<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			  <div class="modal-dialog modal-dialog-center">
				<div class="modal-content">
				  <div class="modal-header">
					<h1 class="modal-title fs-5" id="exampleModalLabel">New Student Deatils</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				  </div>
				  <div class="modal-body">
					<div class="row">
						<div class="col-xl-6">
							<div class="mb-3">
							  <label for="exampleFormControlInput1" class="form-label">First Name</label>
							  <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Yatin">
							</div>
							<div class="mb-3">
							  <label for="exampleFormControlInput3" class="form-label">Current Class</label>
							  <input type="text" class="form-control" id="exampleFormControlInput3" placeholder="VII A">
							</div>
						</div>
						<div class="col-xl-6">
							<div class="mb-3">
							  <label for="exampleFormControlInput2" class="form-label">Last Name</label>
							  <input type="text" class="form-control" id="exampleFormControlInput2" placeholder="xarma">
							</div>
							 <label  class="form-label d-block">Gender</label>
							 <select class="default-select" aria-label="Default select example">
							  <option selected>Select</option>
							  <option value="1">Male</option>
							  <option value="2">Female</option>
							  <option value="3">Other</option>
							</select>
						</div>
					</div>
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Save changes</button>
				  </div>
				</div>
			  </div>
			</div>

	<!--**********************************
		Modal
	***********************************-->
    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="{{asset('vendor/global/global.min.js')}}"></script>
	<script src="{{asset('vendor/bootstrap-select/dist/js/bootstrap-select.min.js')}}"></script>
	<script src="{{asset('vendor/wow-master/dist/wow.min.js')}}"></script>

	<!-- Script Datatable -->
	<script src="{{asset('vendor/datatables/js/jquery.dataTables.min.js')}}"></script>
	<script src="{{asset('js/plugins-init/datatables.init.js')}}"></script>
	<script src="{{asset('vendor/jquery-nice-select/js/jquery.nice-select.min.js')}}"></script>

	@stack('scripts')

	<script src="{{asset('js/custom.min.js')}}"></script>
	<script src="{{asset('js/dlabnav-init.js')}}"></script>
	<script src="{{asset('js/demo.js')}}"></script>
	<script src="{{asset('js/styleSwitcher.js')}}"></script>

	
	<!-- code-highlight for Datatable-->
	<script src="{{asset('js/highlight.min.js')}}"></script>
	<script>
		hljs.highlightAll();
		hljs.configure({ ignoreUnescapedHTML: true })	
	</script>

	<script>
		document.addEventListener('DOMContentLoaded', (event) => {
			document.querySelectorAll('pre code').forEach((el) => {
				hljs.highlightElement(el);
			});
			});
	</script>
	
</body>

<!-- Mirrored from http://akademi.dexignlab.com/xhtml/student.html by HTTrack Website Copier/3.x [XR&CO], Sun, 23 Aug 2026 17:14:11 GMT -->
</html>
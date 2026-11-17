           <!--**********************************
            Sidebar start
        ***********************************-->
        <div class="dlabnav">
			<div class="dlabnav-scroll">	
				<ul class="metismenu" id="menu">
					<li><a href="{{url('./dashboard/index')}}" >
							<i class="material-symbols-outlined">home</i>
							<span class="nav-text">Dashboard</span>
						</a>
					</li>

					{{-- Seção Módulo de Curso--}} 
					<li>
					<a  href="{{ route('course.index')}}" aria-expanded="false">
						<i class="material-symbols-outlined">book</i>
						<span class="nav-text">Curso</span>
					</a>
					</li>
					{{-- Seção Módulo de Formador --}}
					<li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
						<i class="material-symbols-outlined">person</i>
						<span class="nav-text">Formador</span>
					</a>
					<ul aria-expanded="false">
						{{-- Link para a listagem de formadores 
						<li><a href="#">Listar</a></li>
						{{-- Link para registo de novo formador 
						<li><a href="#">Adicionar novo</a></li>
					</ul>
					</li>
					{{-- Seção Módulo de Turma 
					<li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
						<i class="material-symbols-outlined">groups</i>
						<span class="nav-text">Turma</span>
					</a>
						<ul aria-expanded="false">
							{{-- Link para a listagem de turmas 
							<li><a href="#">Listar</a></li>
							{{-- Link para criação de nova turma 
							<li><a href="#">Adicionar novo</a></li>
						</ul>
					</li>
					{{-- Seção Módulo de Estudante 
					<li><a href="#">
						<i class="material-symbols-outlined">school</i>
						<span class="nav-text">Formando</span>
					</a>
					{{-- Seção Módulo de pagamento 
				<li><a class="has-arrow" href="javascript:void(0);" aria-expanded="false">
					<i class="material-symbols-outlined">payments</i>
					<span class="nav-text">Pagamento</span>
				</a>
				<ul aria-expanded="false">
					{{-- Link para a listagem de pagamentos 
					<li><a href="#">Listar</a></li>
					{{-- Link para criação de novo pagamento 
					<li><a href="#">Adicionar novo</a></li>
				</ul>
			</li>
						{{-- Seção Módulo de Inscrição --}}
					{{--<li><a class="has-arrow" href="javascript:void(0);" aria-expanded="false">
						<i class="material-symbols-outlined">app_registration</i>
						<span class="nav-text">Inscrição</span>
					</a>
					<ul aria-expanded="false">
						 Link para a listagem de Inscriçãos
						<li><a href="#">Listar</a></li> --}}
						{{-- Link para criação de novo Inscrição
						<li><a href="#">Adicionar nova</a></li>
					</ul> --}}
	            </div>
        </div>
    <!--**********************************
        Sidebar end
    ***********************************-->


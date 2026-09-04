@extends('layouts.main')

@section('title', 'Formadores')

@section('content')

		<!--**********************************
            Content body start
        ***********************************-->
<div class="content-body">
    {{-- Alerta de sucesso exibido se existir mensagem gravada na sessão flash --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 me-4 ms-4" role="alert">
            <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Container principal com largura total -->
    <div class="container-fluid">
        <div class="row">
            <!-- Coluna Principal da Tabela ocupando 100% da largura -->
            <div class="col-xl-12">
                <div class="card" id="accordion-one">
                    <!-- Cabeçalho do Cartão com Título e Botão de Ação -->
                    <div class="card-header flex-wrap px-3">
                        <div>
                            <h4 class="card-title">Gestão de Formadores</h4>
                            <p class="m-0 subtitle">Lista de todos os formadores e professores registados</p>
                        </div>
                        <ul class="nav nav-tabs dzm-tabs" id="myTab" role="tablist">
                            {{-- Botão para redirecionar para a página de registo de formador --}}
                            <a href="{{ route('teacher.create') }}" class="btn btn-primary btn-sm">
                                Adicionar Novo Formador
                            </a>
                        </ul>
                    </div>

                    <!-- Conteúdo das Abas / Tabela Datatable -->
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="Preview" role="tabpanel" aria-labelledby="home-tab">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    {{-- Tabela idêntica à dos Cursos com inclusão da coluna Foto --}}
                                    <table id="example" class="display table w-100" style="width: 100%;">
                                                    <thead>
                                                        <tr>
                                                            <th>#ID</th>
                                                            <th>Foto</th>
                                                            <th>Nome Completo</th>
                                                            <th>E-mail</th>
                                                            <th>Nº do BI</th>
                                                            <th>Telefone</th>
                                                            <th>Especialidade</th>
                                                            <th>Estado</th>
                                                            <th class="text-center">Ações</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        {{-- Iteração dinâmica sobre a coleção $teachers --}}
                                                        @forelse($teachers as $teacherItem)
                                                            <tr>
                                                                <td><strong>#{{ $teacherItem->id }}</strong></td>
                                                                <td>
                                                                    {{-- Exibição da foto do formador ou imagem padrão --}}
                                                                    @if($teacherItem->image)
                                                                        <img src="{{ asset('storage/'.$teacherItem->image) }}" alt="Foto" class="avatar avatar-sm rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                                                    @else
                                                                        <img src="{{ asset('images/avatar/8.jpg') }}" alt="Sem Foto" class="avatar avatar-sm rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    {{-- Nome com link para os detalhes do formador --}}
                                                                    <a href="{{ route('teacher.show', $teacherItem->id) }}" class="text-primary font-w600">
                                                                        {{ $teacherItem->name }}
                                                                    </a>
                                                                </td>
                                                                <td>{{ $teacherItem->email }}</td>
                                                                <td>{{ $teacherItem->identity_card_number ?: 'N/D' }}</td>
                                                                <td>
                                                                    <i class="fa fa-phone text-muted me-1"></i> {{ $teacherItem->phone_number ?? $teacherItem->phone ?? 'N/D' }}
                                                                </td>
                                                                <td>
                                                                    <span class="badge badge-info light">{{ $teacherItem->specialty ?: 'Formador' }}</span>
                                                                </td>
                                                                <td>
                                                                    @if($teacherItem->status)
                                                                        <span class="badge badge-success light">Activo</span>
                                                                    @else
                                                                        <span class="badge badge-danger light">Desativo</span>
                                                                    @endif
                                                                </td>
                                                                <td class="text-center">
                                                                    <div class="d-flex justify-content-center">
                                                                        {{-- Botão Ver Detalhes --}}
                                                                        <a href="{{ route('teacher.show', $teacherItem->id) }}" class="btn btn-info shadow btn-xs sharp me-1" title="Ver Detalhes">
                                                                            <i class="fa fa-eye"></i>
                                                                        </a>
                                                                        
                                                                        {{-- Botão Editar Formador --}}
                                                                        <a href="{{ route('teacher.edit', $teacherItem->id) }}" class="btn btn-primary shadow btn-xs sharp me-1" title="Editar Formador">
                                                                            <i class="fa fa-pencil"></i>
                                                                        </a>
                        
                                                                        {{-- Formulário com confirmação para Eliminar --}}
                                                                        <form action="{{ route('teacher.destroy', $teacherItem->id) }}" method="POST" onsubmit="return confirm('Tem a certeza que deseja eliminar este formador?');" style="display: inline;">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="btn btn-danger shadow btn-xs sharp" title="Eliminar Formador">
                                                                                <i class="fa fa-trash"></i>
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="9" class="text-center py-4 text-muted">
                                                                    <i class="fa fa-user-times fs-24 mb-2 d-block"></i>
                                                                    Nenhum formador encontrado.
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>													
                                                    <tfoot>
                                                        <tr>
                                                            <th>#ID</th>
                                                            <th>Foto</th>
                                                            <th>Nome Completo</th>
                                                            <th>E-mail</th>
                                                            <th>Nº do BI</th>
                                                            <th>Telefone</th>
                                                            <th>Especialidade</th>
                                                            <th>Estado</th>
                                                            <th class="text-center">Ações</th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Preservação integral do código estático original em comentário Blade --}}
{{--
<div class="container-fluid">


                                        <div class="card contact_list text-center">
                                            <div class="card-body">
                                                <div class="user-content">
                                                    <div class="user-info">
                                                        <div class="user-img">
                                                            <img src="images/contacts/1.jpg" alt="" class="avatar avatar-xl">
                                                        </div>
                                                        <div class="user-details">
                                                            <h4 class="user-name mb-0">Dimitres Viga</h4>
                                                            <p>Teacher</p>
                                                        </div>
                                                    </div>
                                                    <div class="dropdown">
                                                        <a href="javascript:void(0);" class="btn sharp btn-light" data-bs-toggle="dropdown" aria-expanded="false">
															<svg width="24" height="6" viewBox="0 0 24 6" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path d="M12.0012 0.359985C11.6543 0.359985 11.3109 0.428302 10.9904 0.561035C10.67 0.693767 10.3788 0.888317 10.1335 1.13358C9.88829 1.37883 9.69374 1.67 9.56101 1.99044C9.42828 2.31089 9.35996 2.65434 9.35996 3.00119C9.35996 3.34803 9.42828 3.69148 9.56101 4.01193C9.69374 4.33237 9.88829 4.62354 10.1335 4.8688C10.3788 5.11405 10.67 5.3086 10.9904 5.44134C11.3109 5.57407 11.6543 5.64239 12.0012 5.64239C12.7017 5.64223 13.3734 5.36381 13.8686 4.86837C14.3638 4.37294 14.6419 3.70108 14.6418 3.00059C14.6416 2.3001 14.3632 1.62836 13.8677 1.13315C13.3723 0.637942 12.7004 0.359826 12 0.359985H12.0012ZM3.60116 0.359985C3.25431 0.359985 2.91086 0.428302 2.59042 0.561035C2.26997 0.693767 1.97881 0.888317 1.73355 1.13358C1.48829 1.37883 1.29374 1.67 1.16101 1.99044C1.02828 2.31089 0.959961 2.65434 0.959961 3.00119C0.959961 3.34803 1.02828 3.69148 1.16101 4.01193C1.29374 4.33237 1.48829 4.62354 1.73355 4.8688C1.97881 5.11405 2.26997 5.3086 2.59042 5.44134C2.91086 5.57407 3.25431 5.64239 3.60116 5.64239C4.30165 5.64223 4.97339 5.36381 5.4686 4.86837C5.9638 4.37294 6.24192 3.70108 6.24176 3.00059C6.2416 2.3001 5.96318 1.62836 5.46775 1.13315C4.97231 0.637942 4.30045 0.359826 3.59996 0.359985H3.60116ZM20.4012 0.359985C20.0543 0.359985 19.7109 0.428302 19.3904 0.561035C19.07 0.693767 18.7788 0.888317 18.5336 1.13358C18.2883 1.37883 18.0937 1.67 17.961 1.99044C17.8283 2.31089 17.76 2.65434 17.76 3.00119C17.76 3.34803 17.8283 3.69148 17.961 4.01193C18.0937 4.33237 18.2883 4.62354 18.5336 4.8688C18.7788 5.11405 19.07 5.3086 19.3904 5.44134C19.7109 5.57407 20.0543 5.64239 20.4012 5.64239C21.1017 5.64223 21.7734 5.36381 22.2686 4.86837C22.7638 4.37294 23.0419 3.70108 23.0418 3.00059C23.0416 2.3001 22.7632 1.62836 22.2677 1.13315C21.7723 0.637942 21.1005 0.359826 20.4 0.359985H20.4012Z" fill="#A098AE"/>
															</svg>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Edit</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="contact-icon">
                                                   <span class="badge badge-success light">Mathematics</span>
												   <span class="badge badge-secondary light mx-2">Science</span> 
												   <span class="badge badge-danger light">Art</span>
                                                </div>
												<div class="d-flex align-items-center">
													<a href="app-profile.html" class="btn  btn-primary btn-sm w-50 me-2"><i class="fa-solid fa-user me-2"></i>Profile</a>
													<a href="chat.html" class="btn  btn-light btn-sm w-50"><i class="fa-sharp fa-regular fa-envelope me-2"></i>Chat</a>
												</div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/column-->
                                    <!--column-->
                                    <div class="col-xl-3 col-lg-4 col-sm-6">
                                        <div class="card contact_list text-center">
                                             <div class="card-body">
                                                <div class="user-content">
                                                    <div class="user-info">
                                                        <div class="user-img">
                                                            <img src="images/contacts/2.jpg" alt="" class="avatar avatar-xl">
                                                        </div>
                                                        <div class="user-details">
                                                            <h4 class="user-name mb-0">Dimitres Viga</h4>
                                                            <p>Teacher</p>
                                                        </div>
                                                    </div>
                                                    <div class="dropdown">
                                                        <a href="javascript:void(0);" class="btn sharp btn-light" data-bs-toggle="dropdown" aria-expanded="false">
															<svg width="24" height="6" viewBox="0 0 24 6" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path d="M12.0012 0.359985C11.6543 0.359985 11.3109 0.428302 10.9904 0.561035C10.67 0.693767 10.3788 0.888317 10.1335 1.13358C9.88829 1.37883 9.69374 1.67 9.56101 1.99044C9.42828 2.31089 9.35996 2.65434 9.35996 3.00119C9.35996 3.34803 9.42828 3.69148 9.56101 4.01193C9.69374 4.33237 9.88829 4.62354 10.1335 4.8688C10.3788 5.11405 10.67 5.3086 10.9904 5.44134C11.3109 5.57407 11.6543 5.64239 12.0012 5.64239C12.7017 5.64223 13.3734 5.36381 13.8686 4.86837C14.3638 4.37294 14.6419 3.70108 14.6418 3.00059C14.6416 2.3001 14.3632 1.62836 13.8677 1.13315C13.3723 0.637942 12.7004 0.359826 12 0.359985H12.0012ZM3.60116 0.359985C3.25431 0.359985 2.91086 0.428302 2.59042 0.561035C2.26997 0.693767 1.97881 0.888317 1.73355 1.13358C1.48829 1.37883 1.29374 1.67 1.16101 1.99044C1.02828 2.31089 0.959961 2.65434 0.959961 3.00119C0.959961 3.34803 1.02828 3.69148 1.16101 4.01193C1.29374 4.33237 1.48829 4.62354 1.73355 4.8688C1.97881 5.11405 2.26997 5.3086 2.59042 5.44134C2.91086 5.57407 3.25431 5.64239 3.60116 5.64239C4.30165 5.64223 4.97339 5.36381 5.4686 4.86837C5.9638 4.37294 6.24192 3.70108 6.24176 3.00059C6.2416 2.3001 5.96318 1.62836 5.46775 1.13315C4.97231 0.637942 4.30045 0.359826 3.59996 0.359985H3.60116ZM20.4012 0.359985C20.0543 0.359985 19.7109 0.428302 19.3904 0.561035C19.07 0.693767 18.7788 0.888317 18.5336 1.13358C18.2883 1.37883 18.0937 1.67 17.961 1.99044C17.8283 2.31089 17.76 2.65434 17.76 3.00119C17.76 3.34803 17.8283 3.69148 17.961 4.01193C18.0937 4.33237 18.2883 4.62354 18.5336 4.8688C18.7788 5.11405 19.07 5.3086 19.3904 5.44134C19.7109 5.57407 20.0543 5.64239 20.4012 5.64239C21.1017 5.64223 21.7734 5.36381 22.2686 4.86837C22.7638 4.37294 23.0419 3.70108 23.0418 3.00059C23.0416 2.3001 22.7632 1.62836 22.2677 1.13315C21.7723 0.637942 21.1005 0.359826 20.4 0.359985H20.4012Z" fill="#A098AE"/>
															</svg>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Edit</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="contact-icon">
                                                   <span class="badge badge-success light">Mathematics</span>
												   <span class="badge badge-secondary light mx-2">Science</span> 
												   <span class="badge badge-danger light">Art</span>
                                                </div>
												<div class="d-flex align-items-center">
													<a href="app-profile.html" class="btn  btn-primary btn-sm w-50 me-2"><i class="fa-solid fa-user me-2"></i>Profile</a>
													<a href="chat.html" class="btn  btn-light btn-sm w-50"><i class="fa-sharp fa-regular fa-envelope me-2"></i>Chat</a>
												</div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/column-->
                                    <!--column-->
                                    <div class="col-xl-3 col-lg-4 col-sm-6">
                                        <div class="card contact_list text-center">
											<div class="card-body">
                                                <div class="user-content">
                                                    <div class="user-info">
                                                        <div class="user-img">
                                                            <img src="images/contacts/3.jpg" alt="" class="avatar avatar-xl">
                                                        </div>
                                                        <div class="user-details">
                                                            <h4 class="user-name mb-0">Dimitres Viga</h4>
                                                            <p>Teacher</p>
                                                        </div>
                                                    </div>
                                                    <div class="dropdown">
                                                        <a href="javascript:void(0);" class="btn sharp btn-light" data-bs-toggle="dropdown" aria-expanded="false">
															<svg width="24" height="6" viewBox="0 0 24 6" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path d="M12.0012 0.359985C11.6543 0.359985 11.3109 0.428302 10.9904 0.561035C10.67 0.693767 10.3788 0.888317 10.1335 1.13358C9.88829 1.37883 9.69374 1.67 9.56101 1.99044C9.42828 2.31089 9.35996 2.65434 9.35996 3.00119C9.35996 3.34803 9.42828 3.69148 9.56101 4.01193C9.69374 4.33237 9.88829 4.62354 10.1335 4.8688C10.3788 5.11405 10.67 5.3086 10.9904 5.44134C11.3109 5.57407 11.6543 5.64239 12.0012 5.64239C12.7017 5.64223 13.3734 5.36381 13.8686 4.86837C14.3638 4.37294 14.6419 3.70108 14.6418 3.00059C14.6416 2.3001 14.3632 1.62836 13.8677 1.13315C13.3723 0.637942 12.7004 0.359826 12 0.359985H12.0012ZM3.60116 0.359985C3.25431 0.359985 2.91086 0.428302 2.59042 0.561035C2.26997 0.693767 1.97881 0.888317 1.73355 1.13358C1.48829 1.37883 1.29374 1.67 1.16101 1.99044C1.02828 2.31089 0.959961 2.65434 0.959961 3.00119C0.959961 3.34803 1.02828 3.69148 1.16101 4.01193C1.29374 4.33237 1.48829 4.62354 1.73355 4.8688C1.97881 5.11405 2.26997 5.3086 2.59042 5.44134C2.91086 5.57407 3.25431 5.64239 3.60116 5.64239C4.30165 5.64223 4.97339 5.36381 5.4686 4.86837C5.9638 4.37294 6.24192 3.70108 6.24176 3.00059C6.2416 2.3001 5.96318 1.62836 5.46775 1.13315C4.97231 0.637942 4.30045 0.359826 3.59996 0.359985H3.60116ZM20.4012 0.359985C20.0543 0.359985 19.7109 0.428302 19.3904 0.561035C19.07 0.693767 18.7788 0.888317 18.5336 1.13358C18.2883 1.37883 18.0937 1.67 17.961 1.99044C17.8283 2.31089 17.76 2.65434 17.76 3.00119C17.76 3.34803 17.8283 3.69148 17.961 4.01193C18.0937 4.33237 18.2883 4.62354 18.5336 4.8688C18.7788 5.11405 19.07 5.3086 19.3904 5.44134C19.7109 5.57407 20.0543 5.64239 20.4012 5.64239C21.1017 5.64223 21.7734 5.36381 22.2686 4.86837C22.7638 4.37294 23.0419 3.70108 23.0418 3.00059C23.0416 2.3001 22.7632 1.62836 22.2677 1.13315C21.7723 0.637942 21.1005 0.359826 20.4 0.359985H20.4012Z" fill="#A098AE"/>
															</svg>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Edit</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="contact-icon">
                                                   <span class="badge badge-success light">Mathematics</span>
												   <span class="badge badge-secondary light mx-2">Science</span> 
												   <span class="badge badge-danger light">Art</span>
                                                </div>
												<div class="d-flex align-items-center">
													<a href="app-profile.html" class="btn  btn-primary btn-sm w-50 me-2"><i class="fa-solid fa-user me-2"></i>Profile</a>
													<a href="chat.html" class="btn  btn-light btn-sm w-50"><i class="fa-sharp fa-regular fa-envelope me-2"></i>Chat</a>
												</div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/column-->
									<!--column-->
									<div class="col-xl-3 col-lg-4 col-sm-6">
                                        <div class="card contact_list text-center">
                                          <div class="card-body">
                                                <div class="user-content">
                                                    <div class="user-info">
                                                        <div class="user-img">
                                                            <img src="images/contacts/4.jpg" alt="" class="avatar avatar-xl">
                                                        </div>
                                                        <div class="user-details">
                                                            <h4 class="user-name mb-0">Dimitres Viga</h4>
                                                            <p>Teacher</p>
                                                        </div>
                                                    </div>
                                                    <div class="dropdown">
                                                        <a href="javascript:void(0);" class="btn sharp btn-light" data-bs-toggle="dropdown" aria-expanded="false">
															<svg width="24" height="6" viewBox="0 0 24 6" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path d="M12.0012 0.359985C11.6543 0.359985 11.3109 0.428302 10.9904 0.561035C10.67 0.693767 10.3788 0.888317 10.1335 1.13358C9.88829 1.37883 9.69374 1.67 9.56101 1.99044C9.42828 2.31089 9.35996 2.65434 9.35996 3.00119C9.35996 3.34803 9.42828 3.69148 9.56101 4.01193C9.69374 4.33237 9.88829 4.62354 10.1335 4.8688C10.3788 5.11405 10.67 5.3086 10.9904 5.44134C11.3109 5.57407 11.6543 5.64239 12.0012 5.64239C12.7017 5.64223 13.3734 5.36381 13.8686 4.86837C14.3638 4.37294 14.6419 3.70108 14.6418 3.00059C14.6416 2.3001 14.3632 1.62836 13.8677 1.13315C13.3723 0.637942 12.7004 0.359826 12 0.359985H12.0012ZM3.60116 0.359985C3.25431 0.359985 2.91086 0.428302 2.59042 0.561035C2.26997 0.693767 1.97881 0.888317 1.73355 1.13358C1.48829 1.37883 1.29374 1.67 1.16101 1.99044C1.02828 2.31089 0.959961 2.65434 0.959961 3.00119C0.959961 3.34803 1.02828 3.69148 1.16101 4.01193C1.29374 4.33237 1.48829 4.62354 1.73355 4.8688C1.97881 5.11405 2.26997 5.3086 2.59042 5.44134C2.91086 5.57407 3.25431 5.64239 3.60116 5.64239C4.30165 5.64223 4.97339 5.36381 5.4686 4.86837C5.9638 4.37294 6.24192 3.70108 6.24176 3.00059C6.2416 2.3001 5.96318 1.62836 5.46775 1.13315C4.97231 0.637942 4.30045 0.359826 3.59996 0.359985H3.60116ZM20.4012 0.359985C20.0543 0.359985 19.7109 0.428302 19.3904 0.561035C19.07 0.693767 18.7788 0.888317 18.5336 1.13358C18.2883 1.37883 18.0937 1.67 17.961 1.99044C17.8283 2.31089 17.76 2.65434 17.76 3.00119C17.76 3.34803 17.8283 3.69148 17.961 4.01193C18.0937 4.33237 18.2883 4.62354 18.5336 4.8688C18.7788 5.11405 19.07 5.3086 19.3904 5.44134C19.7109 5.57407 20.0543 5.64239 20.4012 5.64239C21.1017 5.64223 21.7734 5.36381 22.2686 4.86837C22.7638 4.37294 23.0419 3.70108 23.0418 3.00059C23.0416 2.3001 22.7632 1.62836 22.2677 1.13315C21.7723 0.637942 21.1005 0.359826 20.4 0.359985H20.4012Z" fill="#A098AE"/>
															</svg>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Edit</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="contact-icon">
                                                   <span class="badge badge-success light">Mathematics</span>
												   <span class="badge badge-secondary light mx-2">Science</span> 
												   <span class="badge badge-danger light">Art</span>
                                                </div>
												<div class="d-flex align-items-center">
													<a href="app-profile.html" class="btn  btn-primary btn-sm w-50 me-2"><i class="fa-solid fa-user me-2"></i>Profile</a>
													<a href="chat.html" class="btn  btn-light btn-sm w-50"><i class="fa-sharp fa-regular fa-envelope me-2"></i>Chat</a>
												</div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/column-->
                                    <!--column-->
                                    <div class="col-xl-3 col-lg-4 col-sm-6">
                                        <div class="card contact_list text-center">
                                            <div class="card-body">
                                                <div class="user-content">
                                                    <div class="user-info">
                                                        <div class="user-img">
                                                            <img src="images/contacts/5.jpg" alt="" class="avatar avatar-xl">
                                                        </div>
                                                        <div class="user-details">
                                                            <h4 class="user-name mb-0">Dimitres Viga</h4>
                                                            <p>Teacher</p>
                                                        </div>
                                                    </div>
                                                    <div class="dropdown">
                                                        <a href="javascript:void(0);" class="btn sharp btn-light" data-bs-toggle="dropdown" aria-expanded="false">
															<svg width="24" height="6" viewBox="0 0 24 6" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path d="M12.0012 0.359985C11.6543 0.359985 11.3109 0.428302 10.9904 0.561035C10.67 0.693767 10.3788 0.888317 10.1335 1.13358C9.88829 1.37883 9.69374 1.67 9.56101 1.99044C9.42828 2.31089 9.35996 2.65434 9.35996 3.00119C9.35996 3.34803 9.42828 3.69148 9.56101 4.01193C9.69374 4.33237 9.88829 4.62354 10.1335 4.8688C10.3788 5.11405 10.67 5.3086 10.9904 5.44134C11.3109 5.57407 11.6543 5.64239 12.0012 5.64239C12.7017 5.64223 13.3734 5.36381 13.8686 4.86837C14.3638 4.37294 14.6419 3.70108 14.6418 3.00059C14.6416 2.3001 14.3632 1.62836 13.8677 1.13315C13.3723 0.637942 12.7004 0.359826 12 0.359985H12.0012ZM3.60116 0.359985C3.25431 0.359985 2.91086 0.428302 2.59042 0.561035C2.26997 0.693767 1.97881 0.888317 1.73355 1.13358C1.48829 1.37883 1.29374 1.67 1.16101 1.99044C1.02828 2.31089 0.959961 2.65434 0.959961 3.00119C0.959961 3.34803 1.02828 3.69148 1.16101 4.01193C1.29374 4.33237 1.48829 4.62354 1.73355 4.8688C1.97881 5.11405 2.26997 5.3086 2.59042 5.44134C2.91086 5.57407 3.25431 5.64239 3.60116 5.64239C4.30165 5.64223 4.97339 5.36381 5.4686 4.86837C5.9638 4.37294 6.24192 3.70108 6.24176 3.00059C6.2416 2.3001 5.96318 1.62836 5.46775 1.13315C4.97231 0.637942 4.30045 0.359826 3.59996 0.359985H3.60116ZM20.4012 0.359985C20.0543 0.359985 19.7109 0.428302 19.3904 0.561035C19.07 0.693767 18.7788 0.888317 18.5336 1.13358C18.2883 1.37883 18.0937 1.67 17.961 1.99044C17.8283 2.31089 17.76 2.65434 17.76 3.00119C17.76 3.34803 17.8283 3.69148 17.961 4.01193C18.0937 4.33237 18.2883 4.62354 18.5336 4.8688C18.7788 5.11405 19.07 5.3086 19.3904 5.44134C19.7109 5.57407 20.0543 5.64239 20.4012 5.64239C21.1017 5.64223 21.7734 5.36381 22.2686 4.86837C22.7638 4.37294 23.0419 3.70108 23.0418 3.00059C23.0416 2.3001 22.7632 1.62836 22.2677 1.13315C21.7723 0.637942 21.1005 0.359826 20.4 0.359985H20.4012Z" fill="#A098AE"/>
															</svg>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Edit</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="contact-icon">
                                                   <span class="badge badge-success light">Mathematics</span>
												   <span class="badge badge-secondary light mx-2">Science</span> 
												   <span class="badge badge-danger light">Art</span>
                                                </div>
												<div class="d-flex align-items-center">
													<a href="app-profile.html" class="btn  btn-primary btn-sm w-50 me-2"><i class="fa-solid fa-user me-2"></i>Profile</a>
													<a href="chat.html" class="btn  btn-light btn-sm w-50"><i class="fa-sharp fa-regular fa-envelope me-2"></i>Chat</a>
												</div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/column-->
									<!--column-->
									<div class="col-xl-3 col-lg-4 col-sm-6">
                                        <div class="card contact_list text-center">
                                          <div class="card-body">
                                                <div class="user-content">
                                                    <div class="user-info">
                                                        <div class="user-img">
                                                            <img src="images/contacts/6.jpg" alt="" class="avatar avatar-xl">
                                                        </div>
                                                        <div class="user-details">
                                                            <h4 class="user-name mb-0">Dimitres Viga</h4>
                                                            <p>Teacher</p>
                                                        </div>
                                                    </div>
                                                    <div class="dropdown">
                                                        <a href="javascript:void(0);" class="btn sharp btn-light" data-bs-toggle="dropdown" aria-expanded="false">
															<svg width="24" height="6" viewBox="0 0 24 6" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path d="M12.0012 0.359985C11.6543 0.359985 11.3109 0.428302 10.9904 0.561035C10.67 0.693767 10.3788 0.888317 10.1335 1.13358C9.88829 1.37883 9.69374 1.67 9.56101 1.99044C9.42828 2.31089 9.35996 2.65434 9.35996 3.00119C9.35996 3.34803 9.42828 3.69148 9.56101 4.01193C9.69374 4.33237 9.88829 4.62354 10.1335 4.8688C10.3788 5.11405 10.67 5.3086 10.9904 5.44134C11.3109 5.57407 11.6543 5.64239 12.0012 5.64239C12.7017 5.64223 13.3734 5.36381 13.8686 4.86837C14.3638 4.37294 14.6419 3.70108 14.6418 3.00059C14.6416 2.3001 14.3632 1.62836 13.8677 1.13315C13.3723 0.637942 12.7004 0.359826 12 0.359985H12.0012ZM3.60116 0.359985C3.25431 0.359985 2.91086 0.428302 2.59042 0.561035C2.26997 0.693767 1.97881 0.888317 1.73355 1.13358C1.48829 1.37883 1.29374 1.67 1.16101 1.99044C1.02828 2.31089 0.959961 2.65434 0.959961 3.00119C0.959961 3.34803 1.02828 3.69148 1.16101 4.01193C1.29374 4.33237 1.48829 4.62354 1.73355 4.8688C1.97881 5.11405 2.26997 5.3086 2.59042 5.44134C2.91086 5.57407 3.25431 5.64239 3.60116 5.64239C4.30165 5.64223 4.97339 5.36381 5.4686 4.86837C5.9638 4.37294 6.24192 3.70108 6.24176 3.00059C6.2416 2.3001 5.96318 1.62836 5.46775 1.13315C4.97231 0.637942 4.30045 0.359826 3.59996 0.359985H3.60116ZM20.4012 0.359985C20.0543 0.359985 19.7109 0.428302 19.3904 0.561035C19.07 0.693767 18.7788 0.888317 18.5336 1.13358C18.2883 1.37883 18.0937 1.67 17.961 1.99044C17.8283 2.31089 17.76 2.65434 17.76 3.00119C17.76 3.34803 17.8283 3.69148 17.961 4.01193C18.0937 4.33237 18.2883 4.62354 18.5336 4.8688C18.7788 5.11405 19.07 5.3086 19.3904 5.44134C19.7109 5.57407 20.0543 5.64239 20.4012 5.64239C21.1017 5.64223 21.7734 5.36381 22.2686 4.86837C22.7638 4.37294 23.0419 3.70108 23.0418 3.00059C23.0416 2.3001 22.7632 1.62836 22.2677 1.13315C21.7723 0.637942 21.1005 0.359826 20.4 0.359985H20.4012Z" fill="#A098AE"/>
															</svg>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Edit</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="contact-icon">
                                                   <span class="badge badge-success light">Mathematics</span>
												   <span class="badge badge-secondary light mx-2">Science</span> 
												   <span class="badge badge-danger light">Art</span>
                                                </div>
												<div class="d-flex align-items-center">
													<a href="app-profile.html" class="btn  btn-primary btn-sm w-50 me-2"><i class="fa-solid fa-user me-2"></i>Profile</a>
													<a href="chat.html" class="btn  btn-light btn-sm w-50"><i class="fa-sharp fa-regular fa-envelope me-2"></i>Chat</a>
												</div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/column-->
                                    <!--column-->
                                    <div class="col-xl-3 col-lg-4 col-sm-6">
                                        <div class="card contact_list text-center">
                                          <div class="card-body">
                                                <div class="user-content">
                                                    <div class="user-info">
                                                        <div class="user-img">
                                                            <img src="images/contacts/7.jpg" alt="" class="avatar avatar-xl">
                                                        </div>
                                                        <div class="user-details">
                                                            <h4 class="user-name mb-0">Dimitres Viga</h4>
                                                            <p>Teacher</p>
                                                        </div>
                                                    </div>
                                                    <div class="dropdown">
                                                        <a href="javascript:void(0);" class="btn sharp btn-light" data-bs-toggle="dropdown" aria-expanded="false">
															<svg width="24" height="6" viewBox="0 0 24 6" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path d="M12.0012 0.359985C11.6543 0.359985 11.3109 0.428302 10.9904 0.561035C10.67 0.693767 10.3788 0.888317 10.1335 1.13358C9.88829 1.37883 9.69374 1.67 9.56101 1.99044C9.42828 2.31089 9.35996 2.65434 9.35996 3.00119C9.35996 3.34803 9.42828 3.69148 9.56101 4.01193C9.69374 4.33237 9.88829 4.62354 10.1335 4.8688C10.3788 5.11405 10.67 5.3086 10.9904 5.44134C11.3109 5.57407 11.6543 5.64239 12.0012 5.64239C12.7017 5.64223 13.3734 5.36381 13.8686 4.86837C14.3638 4.37294 14.6419 3.70108 14.6418 3.00059C14.6416 2.3001 14.3632 1.62836 13.8677 1.13315C13.3723 0.637942 12.7004 0.359826 12 0.359985H12.0012ZM3.60116 0.359985C3.25431 0.359985 2.91086 0.428302 2.59042 0.561035C2.26997 0.693767 1.97881 0.888317 1.73355 1.13358C1.48829 1.37883 1.29374 1.67 1.16101 1.99044C1.02828 2.31089 0.959961 2.65434 0.959961 3.00119C0.959961 3.34803 1.02828 3.69148 1.16101 4.01193C1.29374 4.33237 1.48829 4.62354 1.73355 4.8688C1.97881 5.11405 2.26997 5.3086 2.59042 5.44134C2.91086 5.57407 3.25431 5.64239 3.60116 5.64239C4.30165 5.64223 4.97339 5.36381 5.4686 4.86837C5.9638 4.37294 6.24192 3.70108 6.24176 3.00059C6.2416 2.3001 5.96318 1.62836 5.46775 1.13315C4.97231 0.637942 4.30045 0.359826 3.59996 0.359985H3.60116ZM20.4012 0.359985C20.0543 0.359985 19.7109 0.428302 19.3904 0.561035C19.07 0.693767 18.7788 0.888317 18.5336 1.13358C18.2883 1.37883 18.0937 1.67 17.961 1.99044C17.8283 2.31089 17.76 2.65434 17.76 3.00119C17.76 3.34803 17.8283 3.69148 17.961 4.01193C18.0937 4.33237 18.2883 4.62354 18.5336 4.8688C18.7788 5.11405 19.07 5.3086 19.3904 5.44134C19.7109 5.57407 20.0543 5.64239 20.4012 5.64239C21.1017 5.64223 21.7734 5.36381 22.2686 4.86837C22.7638 4.37294 23.0419 3.70108 23.0418 3.00059C23.0416 2.3001 22.7632 1.62836 22.2677 1.13315C21.7723 0.637942 21.1005 0.359826 20.4 0.359985H20.4012Z" fill="#A098AE"/>
															</svg>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Edit</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="contact-icon">
                                                   <span class="badge badge-success light">Mathematics</span>
												   <span class="badge badge-secondary light mx-2">Science</span> 
												   <span class="badge badge-danger light">Art</span>
                                                </div>
												<div class="d-flex align-items-center">
													<a href="app-profile.html" class="btn  btn-primary btn-sm w-50 me-2"><i class="fa-solid fa-user me-2"></i>Profile</a>
													<a href="chat.html" class="btn  btn-light btn-sm w-50"><i class="fa-sharp fa-regular fa-envelope me-2"></i>Chat</a>
												</div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/column-->
									<!--column-->
									<div class="col-xl-3 col-lg-4 col-sm-6">
                                        <div class="card contact_list text-center">
                                          <div class="card-body">
                                                <div class="user-content">
                                                    <div class="user-info">
                                                        <div class="user-img">
                                                            <img src="images/contacts/8.jpg" alt="" class="avatar avatar-xl">
                                                        </div>
                                                        <div class="user-details">
                                                            <h4 class="user-name mb-0">Dimitres Viga</h4>
                                                            <p>Teacher</p>
                                                        </div>
                                                    </div>
                                                    <div class="dropdown">
                                                        <a href="javascript:void(0);" class="btn sharp btn-light" data-bs-toggle="dropdown" aria-expanded="false">
															<svg width="24" height="6" viewBox="0 0 24 6" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path d="M12.0012 0.359985C11.6543 0.359985 11.3109 0.428302 10.9904 0.561035C10.67 0.693767 10.3788 0.888317 10.1335 1.13358C9.88829 1.37883 9.69374 1.67 9.56101 1.99044C9.42828 2.31089 9.35996 2.65434 9.35996 3.00119C9.35996 3.34803 9.42828 3.69148 9.56101 4.01193C9.69374 4.33237 9.88829 4.62354 10.1335 4.8688C10.3788 5.11405 10.67 5.3086 10.9904 5.44134C11.3109 5.57407 11.6543 5.64239 12.0012 5.64239C12.7017 5.64223 13.3734 5.36381 13.8686 4.86837C14.3638 4.37294 14.6419 3.70108 14.6418 3.00059C14.6416 2.3001 14.3632 1.62836 13.8677 1.13315C13.3723 0.637942 12.7004 0.359826 12 0.359985H12.0012ZM3.60116 0.359985C3.25431 0.359985 2.91086 0.428302 2.59042 0.561035C2.26997 0.693767 1.97881 0.888317 1.73355 1.13358C1.48829 1.37883 1.29374 1.67 1.16101 1.99044C1.02828 2.31089 0.959961 2.65434 0.959961 3.00119C0.959961 3.34803 1.02828 3.69148 1.16101 4.01193C1.29374 4.33237 1.48829 4.62354 1.73355 4.8688C1.97881 5.11405 2.26997 5.3086 2.59042 5.44134C2.91086 5.57407 3.25431 5.64239 3.60116 5.64239C4.30165 5.64223 4.97339 5.36381 5.4686 4.86837C5.9638 4.37294 6.24192 3.70108 6.24176 3.00059C6.2416 2.3001 5.96318 1.62836 5.46775 1.13315C4.97231 0.637942 4.30045 0.359826 3.59996 0.359985H3.60116ZM20.4012 0.359985C20.0543 0.359985 19.7109 0.428302 19.3904 0.561035C19.07 0.693767 18.7788 0.888317 18.5336 1.13358C18.2883 1.37883 18.0937 1.67 17.961 1.99044C17.8283 2.31089 17.76 2.65434 17.76 3.00119C17.76 3.34803 17.8283 3.69148 17.961 4.01193C18.0937 4.33237 18.2883 4.62354 18.5336 4.8688C18.7788 5.11405 19.07 5.3086 19.3904 5.44134C19.7109 5.57407 20.0543 5.64239 20.4012 5.64239C21.1017 5.64223 21.7734 5.36381 22.2686 4.86837C22.7638 4.37294 23.0419 3.70108 23.0418 3.00059C23.0416 2.3001 22.7632 1.62836 22.2677 1.13315C21.7723 0.637942 21.1005 0.359826 20.4 0.359985H20.4012Z" fill="#A098AE"/>
															</svg>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Edit</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="contact-icon">
                                                   <span class="badge badge-success light">Mathematics</span>
												   <span class="badge badge-secondary light mx-2">Science</span> 
												   <span class="badge badge-danger light">Art</span>
                                                </div>
												<div class="d-flex align-items-center">
													<a href="app-profile.html" class="btn  btn-primary btn-sm w-50 me-2"><i class="fa-solid fa-user me-2"></i>Profile</a>
													<a href="chat.html" class="btn  btn-light btn-sm w-50"><i class="fa-sharp fa-regular fa-envelope me-2"></i>Chat</a>
												</div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/column-->
                                    <!--column-->
                                    <div class="col-xl-3 col-lg-4 col-sm-6">
                                        <div class="card contact_list text-center">
                                           <div class="card-body">
                                                <div class="user-content">
                                                    <div class="user-info">
                                                        <div class="user-img">
                                                            <img src="images/contacts/9.jpg" alt="" class="avatar avatar-xl">
                                                        </div>
                                                        <div class="user-details">
                                                            <h4 class="user-name mb-0">Dimitres Viga</h4>
                                                            <p>Teacher</p>
                                                        </div>
                                                    </div>
                                                    <div class="dropdown">
                                                        <a href="javascript:void(0);" class="btn sharp btn-light" data-bs-toggle="dropdown" aria-expanded="false">
															<svg width="24" height="6" viewBox="0 0 24 6" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path d="M12.0012 0.359985C11.6543 0.359985 11.3109 0.428302 10.9904 0.561035C10.67 0.693767 10.3788 0.888317 10.1335 1.13358C9.88829 1.37883 9.69374 1.67 9.56101 1.99044C9.42828 2.31089 9.35996 2.65434 9.35996 3.00119C9.35996 3.34803 9.42828 3.69148 9.56101 4.01193C9.69374 4.33237 9.88829 4.62354 10.1335 4.8688C10.3788 5.11405 10.67 5.3086 10.9904 5.44134C11.3109 5.57407 11.6543 5.64239 12.0012 5.64239C12.7017 5.64223 13.3734 5.36381 13.8686 4.86837C14.3638 4.37294 14.6419 3.70108 14.6418 3.00059C14.6416 2.3001 14.3632 1.62836 13.8677 1.13315C13.3723 0.637942 12.7004 0.359826 12 0.359985H12.0012ZM3.60116 0.359985C3.25431 0.359985 2.91086 0.428302 2.59042 0.561035C2.26997 0.693767 1.97881 0.888317 1.73355 1.13358C1.48829 1.37883 1.29374 1.67 1.16101 1.99044C1.02828 2.31089 0.959961 2.65434 0.959961 3.00119C0.959961 3.34803 1.02828 3.69148 1.16101 4.01193C1.29374 4.33237 1.48829 4.62354 1.73355 4.8688C1.97881 5.11405 2.26997 5.3086 2.59042 5.44134C2.91086 5.57407 3.25431 5.64239 3.60116 5.64239C4.30165 5.64223 4.97339 5.36381 5.4686 4.86837C5.9638 4.37294 6.24192 3.70108 6.24176 3.00059C6.2416 2.3001 5.96318 1.62836 5.46775 1.13315C4.97231 0.637942 4.30045 0.359826 3.59996 0.359985H3.60116ZM20.4012 0.359985C20.0543 0.359985 19.7109 0.428302 19.3904 0.561035C19.07 0.693767 18.7788 0.888317 18.5336 1.13358C18.2883 1.37883 18.0937 1.67 17.961 1.99044C17.8283 2.31089 17.76 2.65434 17.76 3.00119C17.76 3.34803 17.8283 3.69148 17.961 4.01193C18.0937 4.33237 18.2883 4.62354 18.5336 4.8688C18.7788 5.11405 19.07 5.3086 19.3904 5.44134C19.7109 5.57407 20.0543 5.64239 20.4012 5.64239C21.1017 5.64223 21.7734 5.36381 22.2686 4.86837C22.7638 4.37294 23.0419 3.70108 23.0418 3.00059C23.0416 2.3001 22.7632 1.62836 22.2677 1.13315C21.7723 0.637942 21.1005 0.359826 20.4 0.359985H20.4012Z" fill="#A098AE"/>
															</svg>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Edit</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="contact-icon">
                                                   <span class="badge badge-success light">Mathematics</span>
												   <span class="badge badge-secondary light mx-2">Science</span> 
												   <span class="badge badge-danger light">Art</span>
                                                </div>
												<div class="d-flex align-items-center">
													<a href="javascript:void(0);" class="btn  btn-primary btn-sm w-50 me-2"><i class="fa-solid fa-user me-2"></i>Profile</a>
													<a href="javascript:void(0);" class="btn  btn-light btn-sm w-50"><i class="fa-sharp fa-regular fa-envelope me-2"></i>Chat</a>
												</div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/column-->
									<!--column--> 
									<div class="col-xl-3 col-lg-4 col-sm-6">
                                        <div class="card contact_list text-center">
                                            <div class="card-body">
                                                <div class="user-content">
                                                    <div class="user-info">
                                                        <div class="user-img">
                                                            <img src="images/contacts/10.jpg" alt="" class="avatar avatar-xl">
                                                        </div>
                                                        <div class="user-details">
                                                            <h4 class="user-name mb-0">Dimitres Viga</h4>
                                                            <p>Teacher</p>
                                                        </div>
                                                    </div>
                                                    <div class="dropdown">
                                                        <a href="javascript:void(0);" class="btn sharp btn-light" data-bs-toggle="dropdown" aria-expanded="false">
															<svg width="24" height="6" viewBox="0 0 24 6" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path d="M12.0012 0.359985C11.6543 0.359985 11.3109 0.428302 10.9904 0.561035C10.67 0.693767 10.3788 0.888317 10.1335 1.13358C9.88829 1.37883 9.69374 1.67 9.56101 1.99044C9.42828 2.31089 9.35996 2.65434 9.35996 3.00119C9.35996 3.34803 9.42828 3.69148 9.56101 4.01193C9.69374 4.33237 9.88829 4.62354 10.1335 4.8688C10.3788 5.11405 10.67 5.3086 10.9904 5.44134C11.3109 5.57407 11.6543 5.64239 12.0012 5.64239C12.7017 5.64223 13.3734 5.36381 13.8686 4.86837C14.3638 4.37294 14.6419 3.70108 14.6418 3.00059C14.6416 2.3001 14.3632 1.62836 13.8677 1.13315C13.3723 0.637942 12.7004 0.359826 12 0.359985H12.0012ZM3.60116 0.359985C3.25431 0.359985 2.91086 0.428302 2.59042 0.561035C2.26997 0.693767 1.97881 0.888317 1.73355 1.13358C1.48829 1.37883 1.29374 1.67 1.16101 1.99044C1.02828 2.31089 0.959961 2.65434 0.959961 3.00119C0.959961 3.34803 1.02828 3.69148 1.16101 4.01193C1.29374 4.33237 1.48829 4.62354 1.73355 4.8688C1.97881 5.11405 2.26997 5.3086 2.59042 5.44134C2.91086 5.57407 3.25431 5.64239 3.60116 5.64239C4.30165 5.64223 4.97339 5.36381 5.4686 4.86837C5.9638 4.37294 6.24192 3.70108 6.24176 3.00059C6.2416 2.3001 5.96318 1.62836 5.46775 1.13315C4.97231 0.637942 4.30045 0.359826 3.59996 0.359985H3.60116ZM20.4012 0.359985C20.0543 0.359985 19.7109 0.428302 19.3904 0.561035C19.07 0.693767 18.7788 0.888317 18.5336 1.13358C18.2883 1.37883 18.0937 1.67 17.961 1.99044C17.8283 2.31089 17.76 2.65434 17.76 3.00119C17.76 3.34803 17.8283 3.69148 17.961 4.01193C18.0937 4.33237 18.2883 4.62354 18.5336 4.8688C18.7788 5.11405 19.07 5.3086 19.3904 5.44134C19.7109 5.57407 20.0543 5.64239 20.4012 5.64239C21.1017 5.64223 21.7734 5.36381 22.2686 4.86837C22.7638 4.37294 23.0419 3.70108 23.0418 3.00059C23.0416 2.3001 22.7632 1.62836 22.2677 1.13315C21.7723 0.637942 21.1005 0.359826 20.4 0.359985H20.4012Z" fill="#A098AE"/>
															</svg>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Edit</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="contact-icon">
                                                   <span class="badge badge-success light">Mathematics</span>
												   <span class="badge badge-secondary light mx-2">Science</span> 
												   <span class="badge badge-danger light">Art</span>
                                                </div>
												<div class="d-flex align-items-center">
													<a href="app-profile.html" class="btn  btn-primary btn-sm w-50 me-2"><i class="fa-solid fa-user me-2"></i>Profile</a>
													<a href="chat.html" class="btn  btn-light btn-sm w-50"><i class="fa-sharp fa-regular fa-envelope me-2"></i>Chat</a>
												</div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/column-->
									<!--column-->
									<div class="col-xl-3 col-lg-4 col-sm-6">
                                        <div class="card contact_list text-center">
                                           <div class="card-body">
                                                <div class="user-content">
                                                    <div class="user-info">
                                                        <div class="user-img">
                                                            <img src="images/contacts/11.jpg" alt="" class="avatar avatar-xl">
                                                        </div>
                                                        <div class="user-details">
                                                            <h4 class="user-name mb-0">Dimitres Viga</h4>
                                                            <p>Teacher</p>
                                                        </div>
                                                    </div>
                                                    <div class="dropdown">
                                                        <a href="javascript:void(0);" class="btn sharp btn-light" data-bs-toggle="dropdown" aria-expanded="false">
															<svg width="24" height="6" viewBox="0 0 24 6" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path d="M12.0012 0.359985C11.6543 0.359985 11.3109 0.428302 10.9904 0.561035C10.67 0.693767 10.3788 0.888317 10.1335 1.13358C9.88829 1.37883 9.69374 1.67 9.56101 1.99044C9.42828 2.31089 9.35996 2.65434 9.35996 3.00119C9.35996 3.34803 9.42828 3.69148 9.56101 4.01193C9.69374 4.33237 9.88829 4.62354 10.1335 4.8688C10.3788 5.11405 10.67 5.3086 10.9904 5.44134C11.3109 5.57407 11.6543 5.64239 12.0012 5.64239C12.7017 5.64223 13.3734 5.36381 13.8686 4.86837C14.3638 4.37294 14.6419 3.70108 14.6418 3.00059C14.6416 2.3001 14.3632 1.62836 13.8677 1.13315C13.3723 0.637942 12.7004 0.359826 12 0.359985H12.0012ZM3.60116 0.359985C3.25431 0.359985 2.91086 0.428302 2.59042 0.561035C2.26997 0.693767 1.97881 0.888317 1.73355 1.13358C1.48829 1.37883 1.29374 1.67 1.16101 1.99044C1.02828 2.31089 0.959961 2.65434 0.959961 3.00119C0.959961 3.34803 1.02828 3.69148 1.16101 4.01193C1.29374 4.33237 1.48829 4.62354 1.73355 4.8688C1.97881 5.11405 2.26997 5.3086 2.59042 5.44134C2.91086 5.57407 3.25431 5.64239 3.60116 5.64239C4.30165 5.64223 4.97339 5.36381 5.4686 4.86837C5.9638 4.37294 6.24192 3.70108 6.24176 3.00059C6.2416 2.3001 5.96318 1.62836 5.46775 1.13315C4.97231 0.637942 4.30045 0.359826 3.59996 0.359985H3.60116ZM20.4012 0.359985C20.0543 0.359985 19.7109 0.428302 19.3904 0.561035C19.07 0.693767 18.7788 0.888317 18.5336 1.13358C18.2883 1.37883 18.0937 1.67 17.961 1.99044C17.8283 2.31089 17.76 2.65434 17.76 3.00119C17.76 3.34803 17.8283 3.69148 17.961 4.01193C18.0937 4.33237 18.2883 4.62354 18.5336 4.8688C18.7788 5.11405 19.07 5.3086 19.3904 5.44134C19.7109 5.57407 20.0543 5.64239 20.4012 5.64239C21.1017 5.64223 21.7734 5.36381 22.2686 4.86837C22.7638 4.37294 23.0419 3.70108 23.0418 3.00059C23.0416 2.3001 22.7632 1.62836 22.2677 1.13315C21.7723 0.637942 21.1005 0.359826 20.4 0.359985H20.4012Z" fill="#A098AE"/>
															</svg>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Edit</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="contact-icon">
                                                   <span class="badge badge-success light">Mathematics</span>
												   <span class="badge badge-secondary light mx-2">Science</span> 
												   <span class="badge badge-danger light">Art</span>
                                                </div>
												<div class="d-flex align-items-center">
													<a href="app-profile.html" class="btn  btn-primary btn-sm w-50 me-2"><i class="fa-solid fa-user me-2"></i>Profile</a>
													<a href="chat.html" class="btn  btn-light btn-sm w-50"><i class="fa-sharp fa-regular fa-envelope me-2"></i>Chat</a>
												</div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/column-->
									<!--column-->
                                    <div class="col-xl-3 col-lg-4 col-sm-6">
                                        <div class="card contact_list text-center">
                                          <div class="card-body">
                                                <div class="user-content">
                                                    <div class="user-info">
                                                        <div class="user-img">
                                                            <img src="images/contacts/12.jpg" alt="" class="avatar avatar-xl">
                                                        </div>
                                                        <div class="user-details">
                                                            <h4 class="user-name mb-0">Dimitres Viga</h4>
                                                            <p>Teacher</p>
                                                        </div>
                                                    </div>
                                                    <div class="dropdown">
                                                        <a href="javascript:void(0);" class="btn sharp btn-light" data-bs-toggle="dropdown" aria-expanded="false">
															<svg width="24" height="6" viewBox="0 0 24 6" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path d="M12.0012 0.359985C11.6543 0.359985 11.3109 0.428302 10.9904 0.561035C10.67 0.693767 10.3788 0.888317 10.1335 1.13358C9.88829 1.37883 9.69374 1.67 9.56101 1.99044C9.42828 2.31089 9.35996 2.65434 9.35996 3.00119C9.35996 3.34803 9.42828 3.69148 9.56101 4.01193C9.69374 4.33237 9.88829 4.62354 10.1335 4.8688C10.3788 5.11405 10.67 5.3086 10.9904 5.44134C11.3109 5.57407 11.6543 5.64239 12.0012 5.64239C12.7017 5.64223 13.3734 5.36381 13.8686 4.86837C14.3638 4.37294 14.6419 3.70108 14.6418 3.00059C14.6416 2.3001 14.3632 1.62836 13.8677 1.13315C13.3723 0.637942 12.7004 0.359826 12 0.359985H12.0012ZM3.60116 0.359985C3.25431 0.359985 2.91086 0.428302 2.59042 0.561035C2.26997 0.693767 1.97881 0.888317 1.73355 1.13358C1.48829 1.37883 1.29374 1.67 1.16101 1.99044C1.02828 2.31089 0.959961 2.65434 0.959961 3.00119C0.959961 3.34803 1.02828 3.69148 1.16101 4.01193C1.29374 4.33237 1.48829 4.62354 1.73355 4.8688C1.97881 5.11405 2.26997 5.3086 2.59042 5.44134C2.91086 5.57407 3.25431 5.64239 3.60116 5.64239C4.30165 5.64223 4.97339 5.36381 5.4686 4.86837C5.9638 4.37294 6.24192 3.70108 6.24176 3.00059C6.2416 2.3001 5.96318 1.62836 5.46775 1.13315C4.97231 0.637942 4.30045 0.359826 3.59996 0.359985H3.60116ZM20.4012 0.359985C20.0543 0.359985 19.7109 0.428302 19.3904 0.561035C19.07 0.693767 18.7788 0.888317 18.5336 1.13358C18.2883 1.37883 18.0937 1.67 17.961 1.99044C17.8283 2.31089 17.76 2.65434 17.76 3.00119C17.76 3.34803 17.8283 3.69148 17.961 4.01193C18.0937 4.33237 18.2883 4.62354 18.5336 4.8688C18.7788 5.11405 19.07 5.3086 19.3904 5.44134C19.7109 5.57407 20.0543 5.64239 20.4012 5.64239C21.1017 5.64223 21.7734 5.36381 22.2686 4.86837C22.7638 4.37294 23.0419 3.70108 23.0418 3.00059C23.0416 2.3001 22.7632 1.62836 22.2677 1.13315C21.7723 0.637942 21.1005 0.359826 20.4 0.359985H20.4012Z" fill="#A098AE"/>
															</svg>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Edit</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="contact-icon">
                                                   <span class="badge badge-success light">Mathematics</span>
												   <span class="badge badge-secondary light mx-2">Science</span> 
												   <span class="badge badge-danger light">Art</span>
                                                </div>
												<div class="d-flex align-items-center">
													<a href="app-profile.html" class="btn  btn-primary btn-sm w-50 me-2"><i class="fa-solid fa-user me-2"></i>Profile</a>
													<a href="chat.html" class="btn  btn-light btn-sm w-50"><i class="fa-sharp fa-regular fa-envelope me-2"></i>Chat</a>
												</div>
                                            </div>
                                        </div>
                                    </div>

								</div>	
							<!--/column-->
							</div>
                           
                        	<!--/Row -->
						</div>
					
						<div class="table-pagenation teach">
							<small>Showing <span>1-5</span>from <span>100</span>data</small>
							<nav>
								<ul class="pagination pagination-gutter pagination-primary no-bg">
									<li class="page-item page-indicator">
										<a class="page-link" href="javascript:void(0)">
										<i class="fa-solid fa-chevron-left"></i></a>
									</li>
									<li class="page-item "><a class="page-link" href="javascript:void(0)">1</a>
									</li>
									<li class="page-item active"><a class="page-link" href="javascript:void(0)">2</a></li>
									<li class="page-item"><a class="page-link" href="javascript:void(0)">3</a></li>
									<li class="page-item page-indicator">
										<a class="page-link" href="javascript:void(0)">
										<i class="fa-solid fa-chevron-right"></i></a>
									</li>
								</ul>
							</nav>
						</div>
					</div>
				</div>
			</div>
		</div>
--}}
		</div>
		
        <!--**********************************
            Content body end
        ***********************************-->
@endsection
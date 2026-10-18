<!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <a href="{{ route('dashboard') }}" class="brand-logo" aria-label="Página Inicial do Centro de Formação">
                <div class="cf-brand-mark">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="#FFFFFF" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                    </svg>
                </div>
                <div class="brand-title">
                    <span class="logo-text">Centro de Formação</span>
                    <small class="cf-brand-subtitle">Gestão Académica</small>
                </div>
            </a>

            <div class="nav-control">
                <div class="hamburger" role="button" tabindex="0" aria-label="Alternar menu lateral">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                    <svg width="24" height="24" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="22" y="11" width="4" height="4" rx="2" fill="#FFFFFF"/>
                        <rect x="11" width="4" height="4" rx="2" fill="#FFFFFF"/>
                        <rect x="22" width="4" height="4" rx="2" fill="#FFFFFF"/>
                        <rect x="11" y="11" width="4" height="4" rx="2" fill="#FFFFFF"/>
                        <rect x="11" y="22" width="4" height="4" rx="2" fill="#FFFFFF"/>
                        <rect width="4" height="4" rx="2" fill="#FFFFFF"/>
                        <rect y="11" width="4" height="4" rx="2" fill="#FFFFFF"/>
                        <rect x="22" y="22" width="4" height="4" rx="2" fill="#FFFFFF"/>
                        <rect y="22" width="4" height="4" rx="2" fill="#FFFFFF"/>
                    </svg>
                </div>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->

        <!--**********************************
            Header top bar start
        ***********************************-->
        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                            <div class="dashboard_bar">
                                @yield('title', 'Painel de Controlo')
                            </div>
                        </div>

                        <ul class="navbar-nav header-right align-items-center">
                            {{-- Atalhos Rápidos de Secretaria (visíveis em desktop) --}}
                            <li class="nav-item d-none d-lg-flex align-items-center me-2 gap-2">
                                <a href="{{ route('enrollment.create') }}" class="cf-header-quick-btn" title="Registar Nova Candidatura">
                                    <svg class="me-1" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="8.5" cy="7" r="4"></circle>
                                        <line x1="20" y1="8" x2="20" y2="14"></line>
                                        <line x1="23" y1="11" x2="17" y2="11"></line>
                                    </svg>
                                    <span>Nova Inscrição</span>
                                </a>
                                <a href="{{ route('payment.create') }}" class="cf-header-quick-btn cf-header-quick-btn-alt" title="Registar Pagamento de Propina">
                                    <svg class="me-1" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                                        <line x1="2" y1="10" x2="22" y2="10"></line>
                                    </svg>
                                    <span>Pagamento</span>
                                </a>
                            </li>

                            {{-- Alternador de Modo Claro / Escuro --}}
                            <li class="nav-item dropdown notification_dropdown">
                                <a class="nav-link dz-theme-mode cf-theme-toggle-btn" href="javascript:void(0);" role="button" aria-label="Alternar tema claro/escuro" title="Alternar Modo Claro / Escuro">
                                    <svg id="icon-light" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="12" cy="12" r="5"></circle>
                                        <line x1="12" y1="1" x2="12" y2="3"></line>
                                        <line x1="12" y1="21" x2="12" y2="23"></line>
                                        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                                        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                                        <line x1="1" y1="12" x2="3" y2="12"></line>
                                        <line x1="21" y1="12" x2="23" y2="12"></line>
                                        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                                        <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                                    </svg>
                                    <svg id="icon-dark" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                                    </svg>
                                    <span id="cf-theme-label" class="cf-theme-label d-none d-xl-inline">Modo Escuro</span>
                                </a>
                            </li>

                            {{-- Botão de Ecrã Inteiro --}}
                            <li class="nav-item dropdown notification_dropdown d-none d-sm-flex">
                                <a class="nav-link dz-fullscreen" href="javascript:void(0);" role="button" aria-label="Alternar ecrã inteiro" title="Ecrã Inteiro">
                                    <svg id="icon-full" viewBox="0 0 24 24" width="19" height="19" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"></path>
                                    </svg>
                                </a>
                            </li>

                            {{-- Identificação do Utilizador Autenticado e Botão de Terminar Sessão --}}
                            <li class="nav-item ps-2 d-flex align-items-center gap-2">
                                <div class="cf-header-profile" title="{{ Auth::check() ? Auth::user()->email : 'Secretaria Académica' }}">
                                    <div class="cf-header-avatar">
                                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                    </div>
                                    <div class="cf-header-info d-none d-md-block">
                                        <span class="cf-header-name">{{ Auth::check() ? Auth::user()->name : 'Administração' }}</span>
                                        <small class="cf-header-role">{{ Auth::check() ? Auth::user()->email : 'Secretaria Geral' }}</small>
                                    </div>
                                </div>

                                @auth
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline m-0">
                                        @csrf
                                        <button type="submit" class="cf-header-quick-btn cf-header-quick-btn-alt border-0" title="Terminar Sessão">
                                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                                <polyline points="16 17 21 12 16 7"></polyline>
                                                <line x1="21" y1="12" x2="9" y2="12"></line>
                                            </svg>
                                            <span class="d-none d-md-inline ms-1">Sair</span>
                                        </button>
                                    </form>
                                @endauth
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <!--**********************************
            Header top bar end
        ***********************************-->

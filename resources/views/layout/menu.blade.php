   <!--**********************************
            Sidebar start
        ***********************************-->
        <div class="dlabnav">
            <div class="dlabnav-scroll">
                <ul class="metismenu" id="menu">
                    <li class="cf-nav-section-title">
                        <span>Visão Geral</span>
                    </li>
                    <li class="{{ request()->routeIs('dashboard') ? 'mm-active' : '' }}">
                        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'mm-active' : '' }}">
                            <span class="cf-nav-icon-wrap">
                                <svg class="cf-nav-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="3" y="3" width="7" height="9" rx="2"></rect>
                                    <rect x="14" y="3" width="7" height="5" rx="2"></rect>
                                    <rect x="14" y="12" width="7" height="9" rx="2"></rect>
                                    <rect x="3" y="16" width="7" height="5" rx="2"></rect>
                                </svg>
                            </span>
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </li>

                    <li class="cf-nav-section-title">
                        <span>Infraestrutura &amp; Ensino</span>
                    </li>
                    {{-- Módulo de Salas de Aula --}}
                    <li class="{{ request()->routeIs('classroom.*') ? 'mm-active' : '' }}">
                        <a href="{{ route('classroom.index') }}" class="{{ request()->routeIs('classroom.*') ? 'mm-active' : '' }}">
                            <span class="cf-nav-icon-wrap">
                                <svg class="cf-nav-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M13 4h3a2 2 0 0 1 2 2v14"></path>
                                    <path d="M2 20h3"></path>
                                    <path d="M13 20h9"></path>
                                    <path d="M10 12v.01"></path>
                                    <path d="M13 4.562v16.157a1 1 0 0 1-1.242.97L5 20V5.562a2 2 0 0 1 1.515-1.94l4-1A2 2 0 0 1 13 4.561Z"></path>
                                </svg>
                            </span>
                            <span class="nav-text">Salas de Aula</span>
                        </a>
                    </li>

                    {{-- Módulo de Cursos --}}
                    <li class="{{ request()->routeIs('course.*') ? 'mm-active' : '' }}">
                        <a href="{{ route('course.index') }}" class="{{ request()->routeIs('course.*') ? 'mm-active' : '' }}">
                            <span class="cf-nav-icon-wrap">
                                <svg class="cf-nav-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                </svg>
                            </span>
                            <span class="nav-text">Cursos</span>
                        </a>
                    </li>

                    {{-- Módulo de Formadores --}}
                    <li class="{{ request()->routeIs('teacher.*') ? 'mm-active' : '' }}">
                        <a href="{{ route('teacher.index') }}" class="{{ request()->routeIs('teacher.*') ? 'mm-active' : '' }}">
                            <span class="cf-nav-icon-wrap">
                                <svg class="cf-nav-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="2" y="3" width="20" height="14" rx="2"></rect>
                                    <path d="M8 21h8"></path>
                                    <path d="M12 17v4"></path>
                                    <path d="m7 11 3-3 2 2 4-4"></path>
                                </svg>
                            </span>
                            <span class="nav-text">Formadores</span>
                        </a>
                    </li>

                    <li class="cf-nav-section-title">
                        <span>Gestão Pedagógica</span>
                    </li>
                    {{-- Módulo de Turmas --}}
                    <li class="{{ request()->routeIs('room.*') ? 'mm-active' : '' }}">
                        <a href="{{ route('room.index') }}" class="{{ request()->routeIs('room.*') ? 'mm-active' : '' }}">
                            <span class="cf-nav-icon-wrap">
                                <svg class="cf-nav-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                            </span>
                            <span class="nav-text">Turmas</span>
                        </a>
                    </li>

                    {{-- Módulo de Formandos --}}
                    <li class="{{ request()->routeIs('student.*') ? 'mm-active' : '' }}">
                        <a href="{{ route('student.index') }}" class="{{ request()->routeIs('student.*') ? 'mm-active' : '' }}">
                            <span class="cf-nav-icon-wrap">
                                <svg class="cf-nav-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                                    <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                                </svg>
                            </span>
                            <span class="nav-text">Formandos</span>
                        </a>
                    </li>

                    <li class="cf-nav-section-title">
                        <span>Secretaria &amp; Finanças</span>
                    </li>
                    {{-- Módulo de Inscrições / Candidaturas --}}
                    <li class="{{ request()->routeIs('enrollment.*') ? 'mm-active' : '' }}">
                        <a href="{{ route('enrollment.index') }}" class="{{ request()->routeIs('enrollment.*') ? 'mm-active' : '' }}">
                            <span class="cf-nav-icon-wrap">
                                <svg class="cf-nav-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                    <path d="m9 14 2 2 4-4"></path>
                                </svg>
                            </span>
                            <span class="nav-text">Inscrições</span>
                        </a>
                    </li>

                    {{-- Módulo de Pagamentos --}}
                    <li class="{{ request()->routeIs('payment.*') ? 'mm-active' : '' }}">
                        <a href="{{ route('payment.index') }}" class="{{ request()->routeIs('payment.*') ? 'mm-active' : '' }}">
                            <span class="cf-nav-icon-wrap">
                                <svg class="cf-nav-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                                    <line x1="2" y1="10" x2="22" y2="10"></line>
                                </svg>
                            </span>
                            <span class="nav-text">Pagamentos</span>
                        </a>
                    </li>

                    <li class="cf-nav-section-title">
                        <span>Administração</span>
                    </li>
                    {{-- Módulo de Utilizadores do Sistema --}}
                    <li class="{{ request()->routeIs('user.*') ? 'mm-active' : '' }}">
                        <a href="{{ route('user.index') }}" class="{{ request()->routeIs('user.*') ? 'mm-active' : '' }}">
                            <span class="cf-nav-icon-wrap">
                                <svg class="cf-nav-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                </svg>
                            </span>
                            <span class="nav-text">Utilizadores</span>
                        </a>
                    </li>
                </ul>

                <div class="cf-sidebar-footer">
                    <div class="cf-sidebar-status">
                        <span class="cf-status-dot"></span>
                        <div>
                            <strong>Sistema Operacional</strong>
                            <small class="d-block">Ano Académico {{ date('Y') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--**********************************
            Sidebar end
        ***********************************-->

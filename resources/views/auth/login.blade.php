<!DOCTYPE html>
<html lang="pt-AO">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sessão — Centro de Formação</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/auth.css') }}?v={{ time() }}" rel="stylesheet">
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
                @if (session('status'))
                    <div class="alert alert-success py-2 fs-7 mb-3">
                        <i class="fa fa-check-circle me-1"></i> {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger py-2 fs-7 mb-3">
                        <i class="fa fa-exclamation-circle me-1"></i> {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="cf-auth-label">Endereço de E-mail</label>
                        <input type="email"
                               name="email"
                               id="email"
                               class="form-control cf-auth-input @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               placeholder="Introduza o seu e-mail"
                               required
                               autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="cf-auth-label">Palavra-passe</label>
                        <input type="password"
                               name="password"
                               id="password"
                               class="form-control cf-auth-input @error('password') is-invalid @enderror"
                               placeholder="Introduza a sua palavra-passe"
                               required>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label fs-7 text-muted" for="remember">
                                Manter sessão iniciada
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="cf-auth-btn">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                            <polyline points="10 17 15 12 10 7"></polyline>
                            <line x1="15" y1="12" x2="3" y2="12"></line>
                        </svg>
                        <span>Entrar no Sistema</span>
                    </button>
                </form>

                {{-- <form action="{{ route('lead.store') }}" method="POST" class="mt-3">
                    @csrf
                    <div class="mb-3">
                        <label for="lead_email" class="cf-auth-label">Deixe o seu e‑mail para contacto futuro</label>
                        <input type="email"
                               name="lead_email"
                               id="lead_email"
                               class="form-control cf-auth-input @error('lead_email') is-invalid @enderror"
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
                </form> --}}

                <div class="cf-auth-footer">
                    Ainda não tem acesso ao sistema? <a href="contact"> Contactar a administação</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

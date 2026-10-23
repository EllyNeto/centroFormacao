<!DOCTYPE html>
<html lang="pt-AO">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Criar Conta — Centro de Formação</title>
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
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <line x1="20" y1="8" x2="20" y2="14"></line>
                        <line x1="23" y1="11" x2="17" y2="11"></line>
                    </svg>
                </div>
                <h1 class="cf-auth-title">Registar Utilizador</h1>
                <p class="cf-auth-subtitle">Crie uma conta de acesso à gestão académica</p>
            </div>

            <div class="cf-auth-body">
                @if ($errors->any())
                    <div class="alert alert-danger py-2 fs-7 mb-3">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="cf-auth-label">Nome Completo</label>
                        <input type="text"
                               name="name"
                               id="name"
                               class="form-control cf-auth-input @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               placeholder="Ex: João Manuel"
                               required
                               autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="cf-auth-label">Endereço de E-mail</label>
                        <input type="email"
                               name="email"
                               id="email"
                               class="form-control cf-auth-input @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               placeholder="utilizador@centroformacao.ao"
                               required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="cf-auth-label">Palavra-passe</label>
                        <input type="password"
                               name="password"
                               id="password"
                               class="form-control cf-auth-input @error('password') is-invalid @enderror"
                               placeholder="Mínimo 6 caracteres"
                               required>
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="cf-auth-label">Confirmar Palavra-passe</label>
                        <input type="password"
                               name="password_confirmation"
                               id="password_confirmation"
                               class="form-control cf-auth-input"
                               placeholder="Repita a palavra-passe"
                               required>
                    </div>

                    <button type="submit" class="cf-auth-btn">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="8.5" cy="7" r="4"></circle>
                            <line x1="20" y1="8" x2="20" y2="14"></line>
                            <line x1="23" y1="11" x2="17" y2="11"></line>
                        </svg>
                        <span>Criar Conta e Entrar</span>
                    </button>
                </form>

                <div class="cf-auth-footer">
                    Já possui uma conta?
                    <a href="{{ route('login') }}">Iniciar sessão</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

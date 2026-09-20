<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — Centro de Formação</title>

    <!-- Ícones e Fontes -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .auth-card {
            background: rgba(255, 255, 255, 0.96);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            width: 100%;
            max-width: 460px;
            padding: 40px 35px;
            backdrop-filter: blur(10px);
        }
        .auth-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .auth-logo-badge {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px auto;
            color: #ffffff;
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
        }
        .auth-title {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }
        .auth-subtitle {
            font-size: 13px;
            color: #64748b;
            margin-top: 5px;
        }
        .form-control-icon {
            position: relative;
        }
        .form-control-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 20px;
        }
        .form-control-icon input {
            padding-left: 45px;
            height: 50px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            font-size: 14px;
        }
        .form-control-icon input:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }
        .btn-auth-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            border: none;
            height: 50px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 15px;
            color: #ffffff;
            width: 100%;
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.25);
            transition: all 0.3s ease;
        }
        .btn-auth-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(79, 70, 229, 0.35);
            color: #ffffff;
        }
        .contact-admin-box {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #f1f5f9;
            text-align: center;
        }
        .btn-contact-admin {
            display: inline-block;
            color: #4f46e5;
            font-weight: 600;
            font-size: 13px;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 8px;
            background: #f0f3ff;
            transition: all 0.2s ease;
        }
        .btn-contact-admin:hover {
            background: #e0e7ff;
            color: #3730a3;
        }
    </style>
</head>

<body>
    <div class="auth-card">
        {{-- Logótipo e Título da Instituição --}}
        <div class="auth-header">
            <div class="auth-logo-badge">
                <i class="material-icons" style="font-size: 38px;">school</i>
            </div>
            <h1 class="auth-title">Centro de Formação</h1>
            <p class="auth-subtitle">Introduza as suas credenciais para aceder à plataforma</p>
        </div>

        {{-- Exibição de Alertas de Sucesso ou Estado --}}
        @if (session('status'))
            <div class="alert alert-success alert-alt fade show py-2 px-3 fs-13 mb-3">
                <i class="material-icons align-middle fs-16 me-1">check_circle</i> {{ session('status') }}
            </div>
        @endif

        {{-- Exibição de Erros de Validação do Formulário --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-alt fade show py-2 px-3 fs-13 mb-3">
                <strong>Atenção:</strong> E-mail ou palavra-passe incorretos.
            </div>
        @endif

        {{-- Formulário de Autenticação do Sistema --}}
        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Campo: Endereço de E-mail --}}
            <div class="mb-3">
                <label for="email" class="form-label font-w600 text-dark fs-13">Endereço de E-mail</label>
                <div class="form-control-icon">
                    <i class="material-icons">email</i>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="seu.email@centroformacao.co.ao" required autocomplete="email" autofocus>
                </div>
            </div>

            {{-- Campo: Palavra-passe --}}
            <div class="mb-3">
                <label for="password" class="form-label font-w600 text-dark fs-13">Palavra-passe</label>
                <div class="form-control-icon">
                    <i class="material-icons">lock</i>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="••••••••" required autocomplete="current-password">
                </div>
            </div>

            {{-- Opção Lembrar-me --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label fs-13 text-muted" for="remember">
                        Lembrar-me nesta sessão
                    </label>
                </div>
            </div>

            {{-- Botão de Submissão do Formulário --}}
            <button type="submit" class="btn btn-auth-primary">
                Entrar na Plataforma
            </button>
        </form>

        {{-- Botão de Pedido de Acesso / Contactar Administração --}}
        <div class="contact-admin-box">
            <p class="fs-13 text-muted mb-2">Não possui uma conta ou tem dificuldades de acesso?</p>
            <a href="{{ route('contact.admin') }}" class="btn-contact-admin">
                <i class="material-icons align-middle fs-16 me-1">help_outline</i> Contactar administração
            </a>
        </div>
    </div>

    <script src="{{ asset('vendor/global/global.min.js') }}"></script>
</body>
</html>

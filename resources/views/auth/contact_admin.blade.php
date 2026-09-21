<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contactar Administração — Centro de Formação</title>

    <link rel="shortcut icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
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
        .contact-card {
            background: rgba(255, 255, 255, 0.96);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 460px;
            padding: 40px 35px;
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
        }
    </style>
</head>

<body>
    <div class="contact-card">
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle mb-3" style="width: 60px; height: 60px;">
                <i class="material-icons" style="font-size: 32px;">mail_outline</i>
            </div>
            <h3 class="font-w700 text-dark m-0">Contactar Administração</h3>
            <p class="text-muted fs-13 mt-1">Introduza o seu e-mail para solicitar o acesso ou suporte à sua conta</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger py-2 px-3 fs-13 mb-3">
                Por favor introduza um e-mail válido.
            </div>
        @endif

        <form method="POST" action="{{ route('contact.admin.send') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label font-w600 text-dark fs-13">O seu Endereço de E-mail <span class="text-danger">*</span></label>
                <div class="form-control-icon">
                    <i class="material-icons">email</i>
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="exemplo@gmail.com" required autofocus>
                </div>
                <small class="text-muted d-block mt-1">A administração receberá o seu e-mail e entrará em contacto.</small>
            </div>

            <button type="submit" class="btn btn-primary font-w600 w-100 py-3 rounded-3 shadow mb-3">
                Enviar Solicitação de Acesso
            </button>

            <div class="text-center">
                <a href="{{ route('login') }}" class="text-primary font-w500 fs-13 text-decoration-none">
                    <i class="material-icons align-middle fs-16 me-1">arrow_back</i> Voltar à página de login
                </a>
            </div>
        </form>
    </div>
</body>
</html>

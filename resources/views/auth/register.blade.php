<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registo Restrito — Centro de Formação</title>

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
        .notice-card {
            background: rgba(255, 255, 255, 0.96);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 480px;
            padding: 40px 35px;
            text-align: center;
        }
        .notice-icon {
            width: 70px;
            height: 70px;
            background: #fff7ed;
            color: #f97316;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px auto;
            border: 2px solid #ffedd5;
        }
    </style>
</head>

<body>
    <div class="notice-card">
        <div class="notice-icon">
            <i class="material-icons" style="font-size: 38px;">lock</i>
        </div>

        <h2 class="font-w700 text-dark mb-2">Criação de Conta Restrita</h2>
        <p class="text-muted fs-14 mb-4">
            O registo público de contas está desativado. O acesso à plataforma do <strong>Centro de Formação</strong> é restrito aos elementos autorizados pela instituição.
        </p>

        <div class="alert alert-warning light border-warning py-3 px-3 fs-13 text-start mb-4">
            <i class="material-icons align-middle me-1">info</i>
            <strong>Nota:</strong> Para solicitar o seu acesso, entre em contacto com a administração do Centro de Formação.
        </div>

        <div class="d-grid gap-2">
            <a href="{{ route('contact.admin') }}" class="btn btn-primary btn-block font-w600 py-2">
                <i class="material-icons align-middle fs-16 me-1">help_outline</i> Contactar Administração
            </a>
            <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-block font-w500 mt-2">
                <i class="material-icons align-middle fs-16 me-1">arrow_back</i> Voltar ao Login
            </a>
        </div>
    </div>
</body>
</html>

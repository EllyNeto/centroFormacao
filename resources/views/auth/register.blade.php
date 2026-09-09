<!DOCTYPE html>
<html lang="pt" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registo - Centro de Formação</title>
    
    <!-- FAVICONS ICON -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>

<body class="body h-100">
    <div class="authincation h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6 col-lg-5">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
                                    <div class="text-center mb-4">
                                        <a href="{{ url('/') }}" class="brand-logo">
                                            <h2 class="text-primary font-w600 mb-0">Centro de Formação</h2>
                                        </a>
                                        <p class="text-muted mt-2">Crie a sua conta de acesso ao sistema</p>
                                    </div>

                                    @if ($errors->any())
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <ul class="mb-0 ps-3">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif

                                    <form action="{{ route('register') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="mb-1 text-label font-w500"><strong>Nome Completo</strong></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="material-icons">person</i></span>
                                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="O seu nome completo" value="{{ old('name') }}" required autofocus>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="mb-1 text-label font-w500"><strong>Endereço de E-mail</strong></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="material-icons">email</i></span>
                                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="exemplo@centro.com" value="{{ old('email') }}" required>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="mb-1 text-label font-w500"><strong>Palavra-passe</strong></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="material-icons">lock</i></span>
                                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Mínimo 6 caracteres" required>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="mb-1 text-label font-w500"><strong>Confirmar Palavra-passe</strong></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="material-icons">lock_outline</i></span>
                                                <input type="password" name="password_confirmation" class="form-control" placeholder="Repita a palavra-passe" required>
                                            </div>
                                        </div>

                                        <div class="text-center mt-4">
                                            <button type="submit" class="btn btn-primary btn-block w-100 py-2 font-w600">Criar Conta e Entrar</button>
                                        </div>
                                    </form>

                                    <div class="new-account mt-4 text-center">
                                        <p class="mb-0">Já tem uma conta registada? <a class="text-primary font-w600" href="{{ route('login') }}">Iniciar Sessão</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('js/custom.min.js') }}"></script>
    <script src="{{ asset('js/dzen.init.js') }}"></script>
</body>

</html>

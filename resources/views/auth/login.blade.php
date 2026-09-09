<!DOCTYPE html>
<html lang="pt" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Centro de Formação</title>
    
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
                                        <p class="text-muted mt-2">Inicie sessão para aceder ao sistema</p>
                                    </div>

                                    @if (session('success'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            {{ session('success') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif

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

                                    <form action="{{ route('login') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="mb-1 text-label font-w500"><strong>Endereço de E-mail</strong></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="material-icons">email</i></span>
                                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="exemplo@centro.com" value="{{ old('email') }}" required autofocus>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="mb-1 text-label font-w500"><strong>Palavra-passe</strong></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="material-icons">lock</i></span>
                                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" required>
                                            </div>
                                        </div>

                                        <div class="row d-flex justify-content-between mt-4 mb-2">
                                            <div class="mb-3">
                                               <div class="form-check custom-checkbox ms-1">
                                                    <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="remember">Lembrar-me</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary btn-block w-100 py-2 font-w600">Entrar no Sistema</button>
                                        </div>
                                    </form>

                                    <div class="new-account mt-4 text-center">
                                        <p class="mb-0">Ainda não tem conta? <a class="text-primary font-w600" href="{{ route('register') }}">Registar nova conta</a></p>
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

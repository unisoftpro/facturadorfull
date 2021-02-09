@extends('tenant.layouts.auth')

@section('content')
<section class="auth">
    <article class="auth__image" style="background-image: url({{ asset('images/login-fondo-1.png') }})">
        @if($company->logo)
            <img class="auth__logo" src="{{ asset('storage/uploads/logos/' . $company->logo) }}" alt="Logo" />
        @else
            <img class="auth__logo" src="{{asset('logo/700x300.jpg')}}" alt="Logo" />
        @endif
        {{-- <img src="{{  }}" alt="Fondo del login" class="auth__bg"> --}}
    </article>
    <article class="auth__form">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <h1 class="auth__title">Bienvenido a<br>{{ $company->trade_name }}</h1>
            <p>Ingresa a tu cuenta</p>
            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input type="email" name="email" id="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}">
                @if ($errors->has('email'))
                <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                @endif
            </div>
            <div class="form-group">
                <div class="d-flex justify-content-between">
                    <label for="password">Contraseña</label>
                    <a href="#">¿Has olvidado tu contraseña?</a>
                </div>
                <input type="password" name="password" id="password" class="form-control hide-password {{ $errors->has('password') ? 'is-invalid' : '' }}">
                @if ($errors->has('password'))
                <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                @endif
                <button type="button" class="btn btn-eye" id="btnEye">
                    <i class="fa fa-eye"></i>
                </button>
            </div>
            <button type="submit" class="btn btn-signin btn-block">INICIAR SESIÓN</button>
        </form>
    </article>
</section>
    {{-- <section class="body-sign">
        <div class="center-sign">
            <div class="card">
                <div class="card card-header card-primary" style="background:#0088CC">
                    <p class="card-title text-center">Acceso al Sistema</p>
                    <h1 class="display-3 position-absolute text-left font-weight-bold" style="left: 90%; margin-top: -35px; color: rgba(255,255,255,.1);">3</h1>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group mb-3">
                            <label>Correo electrónico</label>
                            <div class="input-group">
                                <input id="email" type="email" name="email" class="form-control form-control-lg" value="{{ old('email') }}">
                                <span class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                </span>
                            </div>
                            @if ($errors->has('email'))
                                <label class="error">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </label>
                            @endif
                        </div>
                        <div class="form-group mb-3 {{ $errors->has('password') ? ' error' : '' }}">
                            <label>Contraseña</label>
                            <div class="input-group">
                                <input name="password" type="password" class="form-control form-control-lg">
                                <span class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                </span>
                            </div>
                            @if ($errors->has('password'))
                                <label class="error">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </label>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="checkbox-custom checkbox-default">
                                    <input name="remember" id="RememberMe" type="checkbox" {{ old('remember') ? 'checked' : '' }}>
                                    <label for="RememberMe">Recordarme</label>
                                </div>
                            </div>
                            <div class="col-sm-4 text-right">
                                <button type="submit" class="btn btn-primary mt-2">Iniciar sesión</button>
                            </div>
                        </div>
                    </form>
                </div>
        </div>
    </section> --}}
@endsection
@push('scripts')
    <script>
        var inputPassword = document.getElementById('password');
        var btnEye = document.getElementById('btnEye');
        btnEye.addEventListener('click', function () {
            if (inputPassword.classList.contains('hide-password')) {
                inputPassword.type = 'text';
                inputPassword.classList.remove('hide-password');
                btnEye.innerHTML = '<i class="fa fa-eye-slash"></i>'
            } else {
                inputPassword.type = 'password';
                inputPassword.classList.add('hide-password');
                btnEye.innerHTML = '<i class="fa fa-eye"></i>'
            }
        });
    </script>
@endpush

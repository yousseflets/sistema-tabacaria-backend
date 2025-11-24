@extends('layouts.auth')

@section('title','Login')

@section('card')
    <div class="card-left">
        <div class="brand">Tabacaria Admin</div>
        <div class="lead">Entrar no painel administrativo</div>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}" style="max-width:420px">
            @csrf

            <div class="mb-3">
                <label class="muted">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus class="form-control">
                @error('email')<div class="muted">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="muted">Senha</label>
                <input type="password" name="password" required class="form-control">
                @error('password')<div class="muted">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="inline-flex items-center muted"><input type="checkbox" name="remember" class="me-2"> Lembrar-me</label>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <a class="link" href="{{ route('password.request') }}">Esqueceu a senha?</a>
                <button class="btn-primary">Entrar</button>
            </div>
        </form>
    </div>

    <div class="card-right">
        <picture>
            <img src="/images/logo.jpg" alt="Logo" class="logo-img" onerror="if(!this._triedSvg){this._triedSvg=true;this.src='/images/logo.svg';}else{this.style.display='none';document.getElementById('logo-fallback').style.display='block';}" />
        </picture>
        <div id="logo-fallback" class="logo-fallback" style="display:none">Laravel</div>
    </div>
@endsection


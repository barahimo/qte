@extends('layouts.app')
@section('content')
<div class="login-box">
    <div class="login-box-body border border-dark shadow">
        <h3 class="login-box-msg">L'authentification </h3>
        <br>
        <form action="{{ route('login') }}" method="post">
            @csrf
            <div class="form-group has-feedback">
                <input type="email" class="form-control sty1 p-2" placeholder="E-mail" @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control sty1 p-2" placeholder="Mot de passe"  @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
            </div>
            <div class="form-group has-feedback">
                @error('email')
                <span class="text-red" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
                @error('password')
                <span class="text-red" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="col-xs-8">
                <div class="checkbox icheck">
                    <label for="remember">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        Rester connecté
                    </label>
                    {{-- <label>
                    <input type="checkbox">
                        Rester connecté
                    </label> --}}
                </div>
            </div>
            <!-- /.col -->
            <div class="col-xs-4 m-t-1">
                <button type="submit" class="btn btn-primary btn-block btn-flat">S'identifier</button>
            </div>
            <!-- /.col --> 
        </form>
    </div>
    <!-- /.login-box-body --> 
</div>
<!-- /.login-box -->
@endsection

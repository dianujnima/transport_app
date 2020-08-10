@extends('layouts.front_auth')
@section('title', 'Login')
@section('page-heading', 'Enter your email address and password to access admin panel.')

@section('auth_content')
<form method="POST" action="{{ route('login') }}" class="custom_form" novalidate>
    <div class="heading">
        <h3 class="text-center">Login to your account</h3>
        <p class="text-center">Don't have an account? <a class="text-thm" href="{{ route('register') }}">Sign Up!</a></p>
    </div>
    @csrf
    <div class="form-group mb-3">
        <label for="emailaddress">{{ __('E-Mail Address') }}</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autofocus placeholder="Enter your email">
        @error('email')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group mb-3">
        <label for="password">{{ __('Password') }}</label>
        <input class="form-control @error('password') is-invalid @enderror" type="password" id="password" name="password" required autocomplete="current-password" placeholder="Enter your password">
        @error('password')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    @if ($errors->has('active'))
    <p class="alert alert-danger mt-2">
        <span class="help-block">
            <strong>{{ $errors->first('active') }}</strong>
        </span>
    </p>
    @endif

    <div class="form-group mb-3">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="custom-control-label" for="remember">Remember Me</label>
            <a class="tdu btn-fpswd float-right" href="{{ route('password.request') }}">Forgot Password?</a>
        </div>
    </div>

    <div class="form-group mb-0 text-center">
        <button class="btn btn-log btn-block btn-thm" type="submit">Login</button>
    </div>

</form>
@endsection
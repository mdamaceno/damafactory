@extends('layouts.auth')

@section('content')
<form class="login-form" method="POST" action="{{ route('login') }}">
    <div class="card mb-0">
        <div class="card-body">
            @csrf
            <div class="text-center mb-3">
                <i class="icon-reading icon-2x text-slate-300 border-slate-300 border-3 rounded-round p-3 mb-3 mt-1"></i>
                <h5 class="mb-0">{{ __('Login to your account') }}</h5>
                <span class="d-block text-muted">{{ __('Enter your credentials below') }}</span>
            </div>

            <div class="form-group form-group-feedback form-group-feedback-left">
                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="{{ __('Email') }}" required autofocus>
                <div class="form-control-feedback">
                    <i class="icon-user text-muted"></i>
                </div>
                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group form-group-feedback form-group-feedback-left">
                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="{{ __('Password') }}" required>
                <div class="form-control-feedback">
                    <i class="icon-lock2 text-muted"></i>
                </div>
                @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-block bg-indigo-800">{{ __('Login') }} <i class="icon-circle-right2 ml-2"></i></button>
            </div>

            <div class="text-center">
                <a href="login_password_recover.html">{{ __('Forgot Your Password?') }}</a>
            </div>
        </div>
    </div>
</form>
@endsection

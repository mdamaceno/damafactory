@extends('layouts.auth')

@section('content')
<form class="login-form" method="POST" action="{{ route('install') }}">
    <div class="card mb-0">
        <div class="card-body">
            @csrf
            <div class="text-center mb-3">
                <i class="icon-dots icon-2x text-slate-300 border-slate-300 border-3 rounded-round p-3 mb-3 mt-1"></i>
                <h5 class="mb-0">{{ __('Create an admin user') }}</h5>
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
                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" placeholder="{{ __('Name') }}" required>
                <div class="form-control-feedback">
                    <i class="icon-pen6 text-muted"></i>
                </div>
                @if ($errors->has('name'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('name') }}</strong>
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

            <div class="form-group form-group-feedback form-group-feedback-left">
                <input id="confirm-password" type="password" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" name="password_confirmation" placeholder="{{ __('Password Confirmation') }}" required>
                <div class="form-control-feedback">
                    <i class="icon-lock2 text-muted"></i>
                </div>
                @if ($errors->has('password_confirmation'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-block bg-indigo-800">{{ __('Create') }} <i class="icon-circle-right2 ml-2"></i></button>
            </div>
        </div>
    </div>
</form>
@endsection

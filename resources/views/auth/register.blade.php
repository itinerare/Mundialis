@extends('layouts.app')

@section('title')
    Register
@endsection

@section('content')
    @if (Settings::get('is_registration_open'))
        @if ($userCount)
            <div class="row">
                <div class="col-md-6 offset-md-4">
                    <h1>Register</h1>
                </div>
            </div>
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group row mb-2">
                    <label for="name" class="col-md-4 col-form-label text-md-right">Username</label>

                    <div class="col-md-6">
                        <input id="name" type="text"
                            class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name"
                            value="{{ old('name') }}" required autofocus>

                        @if ($errors->has('name'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <label for="email" class="col-md-4 col-form-label text-md-right">E-mail Address</label>

                    <div class="col-md-6">
                        <input id="email" type="email"
                            class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                            value="{{ old('email') }}" required>

                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row mb-2">
                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                    <div class="col-md-6">
                        <input id="password" type="password"
                            class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"
                            required>

                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <label for="password-confirm"
                        class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                    <div class="col-md-6">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                            required>
                    </div>
                </div>

                <div class="form-group row mb-2">
                    <label for="name" class="col-md-4 col-form-label text-md-right">Invitation Key
                        {!! add_help('An invitation key is required to create an account.') !!}</label>

                    <div class="col-md-6">
                        <input id="code" type="text"
                            class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" name="code"
                            value="{{ old('code') }}" required autofocus>

                        @if ($errors->has('code'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('code') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <div class="col-md-6 offset-md-4">
                        <div class="form-check">
                            <label class="form-check-label">
                                {!! Form::checkbox('agreement', 1, false, ['class' => 'form-check-input']) !!}
                                I have read and agree to the <a href="{{ url('info/terms') }}">Terms of Service</a> and
                                <a href="{{ url('info/privacy') }}">Privacy Policy</a>.
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Register') }}
                        </button>
                    </div>
                </div>
            </form>
        @else
            @include('auth._require_setup')
        @endif
    @else
        <p>Registration is currently closed.</p>
    @endif
@endsection

@extends('eventmie::auth.authapp')

@section('title')
    @lang('eventmie-pro::em.forgot_password')
@endsection

@section('authcontent')

<h2 class="title">@lang('eventmie-pro::em.forgot_password')</h2>

@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif

<div class="lgx-registration-form">
    <form method="POST" action="{{ route('eventmie.password.email') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <input id="email" type="email" class="wpcf7-form-control form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus placeholder="@lang('eventmie-pro::em.email')">
        @if ($errors->has('email'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif

        <button type="submit" class="lgx-btn lgx-btn-white btn-block"><i class="fas fa-paper-plane"></i> @lang('eventmie-pro::em.send_password_reset_link')</button>
    </form>

    <div class="row">
        <div class="col-md-12">
            <div class="lgx-links">
                <a class="btn btn-link text-center" href="{{ route('eventmie.login') }}">@lang('eventmie-pro::em.cancel')</a>
            </div>
        </div>
    </div>
</div>    
    
@endsection
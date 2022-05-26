@extends('eventmie::auth.authapp')

@section('title')
    @lang('eventmie-pro::em.login')
@endsection

@section('authcontent')


<h2 class="title">@lang('eventmie-pro::em.login')</h2>

@if(config('voyager.demo_mode'))
<div class="alert alert-info">
    <a href="https://eventmie-pro-docs.classiebit.com/docs/1.4/demo-accounts" target="_blank">Visit here for Demo Accounts</a>    
</div>
@endif

<div class="lgx-registration-form">
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $error}}</strong>
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('eventmie.login_post') }}">
        
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input id="email" type="email" class="wpcf7-form-control form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus placeholder="@lang('eventmie-pro::em.email')">
        @if ($errors->has('email'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif

        <input id="password" type="password" class="wpcf7-form-control form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="@lang('eventmie-pro::em.password')">
        @if ($errors->has('password'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif
        
        <div class="form-check text-left">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" checked value="1">
            <label class="form-check-label" for="remember">@lang('eventmie-pro::em.remember')</label>
        </div>
        
        <button type="submit" class="lgx-btn lgx-btn-white btn-block"><i class="fas fa-sign-in-alt"></i> @lang('eventmie-pro::em.login')</button>

        <div class="row">
            <div class="col-md-12">
                <div class="lgx-links">
                    <a class="btn btn-link pull-left" href="{{ route('eventmie.password.request') }}">@lang('eventmie-pro::em.forgot_password')</a>
                    <a class="btn btn-link pull-right" href="{{ route('eventmie.register_show') }}">@lang('eventmie-pro::em.register')</a>
                </div>
            </div>
        </div>
        
        <hr style="border-top: 2px solid #eee;">
        @if(!empty(config('services')['facebook']['client_id']) || !empty(config('services')['google']['client_id']))
        <div class="row">
            <div class="col-md-4 text-left">
                <h4 class="col-white">@lang('eventmie-pro::em.continue_with')</h4>
            </div>
            <div class="col-md-8 text-right">
                @if(!empty(config('services')['facebook']['client_id']))
                <a href="{{ route('eventmie.oauth_login', ['social' => 'facebook'])}}" class="lgx-btn lgx-btn-white lgx-btn-sm"><i class="fab fa-facebook-f"></i> Facebook</a>
                @endif
                
                @if(!empty(config('services')['google']['client_id']))
                <a href="{{ route('eventmie.oauth_login', ['social' => 'google'])}}" class="lgx-btn lgx-btn-white lgx-btn-sm"><i class="fab fa-google"></i> Google</a>
                @endif
            </div>
        </div>
        @endif
        
    </form>
</div>    

@endsection

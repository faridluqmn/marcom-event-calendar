@extends('layouts.guest')

@section('content')
<div class="auth-card">
    <div class="auth-logo">
        <div class="auth-logo-icon"></div>
        <span class="auth-logo-text">Marcom EJ</span>
    </div>
    
    <h2 class="auth-title">Welcome back</h2>
    
    @if($errors->any())
        <div class="alert-danger" style="animation: slideUpFade 0.3s ease-out;">
            <ul style="margin: 0; padding-left: 20px; font-size: 0.9rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label class="form-label">Email address</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="role@marcom.com">
        </div>
        
        <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required placeholder="••••••••">
        </div>
        
        <button type="submit" class="btn-primary">Sign in</button>
    </form>
    
    <a href="{{ route('register') }}" class="auth-link">Don't have an account? Sign up</a>
</div>
@endsection

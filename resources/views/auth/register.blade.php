@extends('layouts.guest')

@section('content')
<div class="auth-card">
    <div class="auth-logo">
        <div class="auth-logo-icon"></div>
        <span class="auth-logo-text">Marcom <span class="logo-accent">EJ</span></span>
    </div>
    
    <h2 class="auth-title">Create an account</h2>
    
    <div style="background-color: #f3f4f6; border: 1px solid #e5e7eb; color: #4b5563; padding: 12px 14px; border-radius: 8px; font-size: 0.85rem; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; color: #6b7280;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        <span>Pendaftaran akun baru saat ini dinonaktifkan. Silakan hubungi Administrator.</span>
    </div>
    
    @if($errors->any())
        <div class="alert-danger" style="animation: slideUpFade 0.3s ease-out; margin-bottom: 20px;">
            <ul style="margin: 0; padding-left: 20px; font-size: 0.9rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="John Doe" disabled style="background-color: #f9fafb; cursor: not-allowed;">
        </div>

        <div class="form-group">
            <label class="form-label">Email address</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="name@example.com" disabled style="background-color: #f9fafb; cursor: not-allowed;">
        </div>
        
        <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" disabled style="background-color: #f9fafb; cursor: not-allowed;">
        </div>

        <div class="form-group">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" disabled style="background-color: #f9fafb; cursor: not-allowed;">
        </div>
        
        <button type="button" class="btn-primary" disabled style="width: 100%; justify-content: center;">Create account</button>
    </form>
    
    <a href="{{ route('login') }}" class="auth-link">Already have an account? Sign in</a>
</div>
@endsection

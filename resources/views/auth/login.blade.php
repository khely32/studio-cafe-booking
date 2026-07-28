@extends('layouts.app')

@section('styles')
<style>
    .login-page {
        min-height: 100vh; display: flex; align-items: center; justify-content: center;
        padding: 40px 24px; position: relative; overflow: hidden;
        background: linear-gradient(135deg, var(--espresso), #3a3028);
    }
    .login-page::before {
        content: ''; position: absolute; inset: 0;
        background: radial-gradient(ellipse at 30% 40%, rgba(201,169,110,0.1), transparent 50%),
                    radial-gradient(ellipse at 70% 60%, rgba(139,158,139,0.08), transparent 40%);
    }
    .login-box {
        width: 100%; max-width: 440px; position: relative; z-index: 1;
        background: #fff; border-radius: var(--radius-xl);
        padding: 48px 40px; box-shadow: var(--shadow-xl);
    }
    .login-brand {
        text-align: center; margin-bottom: 36px;
    }
    .login-brand .logo {
        width: 56px; height: 56px; border-radius: 50%;
        background: linear-gradient(135deg, var(--gold), var(--gold-dark));
        display: inline-flex; align-items: center; justify-content: center;
        font-family: 'Playfair Display', serif; font-size: 20px;
        font-weight: 700; color: #fff;
        box-shadow: 0 4px 20px rgba(201,169,110,0.3);
        margin-bottom: 16px;
    }
    .login-brand h2 {
        font-family: 'Playfair Display', serif;
        font-size: 24px; font-weight: 700;
    }
    .login-brand p { font-size: 14px; color: var(--charcoal-light); margin-top: 4px; }
    .login-btn {
        width: 100%; padding: 14px;
        background: linear-gradient(135deg, var(--gold), var(--gold-dark));
        color: #fff; border: none; border-radius: 100px;
        font-size: 15px; font-weight: 600; cursor: pointer;
        font-family: 'DM Sans', sans-serif;
        box-shadow: 0 4px 16px rgba(201,169,110,0.3);
        transition: all 0.3s;
    }
    .login-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 24px rgba(201,169,110,0.4); }
    .error-text { color: #C62828; font-size: 13px; margin-top: 6px; }
    .login-footer { text-align: center; margin-top: 24px; }
    .login-footer a { font-size: 14px; color: var(--gold-dark); }
    .login-footer a:hover { color: var(--gold); }
</style>
@endsection

@section('content')
<div class="login-page">
    <div class="login-box">
        <div class="login-brand">
            <div class="logo">56</div>
            <h2>Welcome Back</h2>
            <p>Sign in to manage your studio bookings</p>
        </div>

        @if(session('warning'))
        <div class="alert alert-error">{{ session('warning') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="admin@5630studiocafe.com">
                @error('email') <div class="error-text">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required placeholder="Enter your password">
                @error('password') <div class="error-text">{{ $message }}</div> @enderror
            </div>
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:24px;">
                <input type="checkbox" id="remember" name="remember" style="accent-color:var(--gold);">
                <label for="remember" style="font-size:13px;color:var(--charcoal-light);margin:0;">Remember me</label>
            </div>
            <button type="submit" class="login-btn">Sign In</button>
        </form>
        <div class="login-footer">
            <a href="{{ route('home') }}">← Back to Home</a>
        </div>
    </div>
</div>
@endsection

@extends('auth.layout')
@section('title', 'Sign In - 56\'30 Studio Cafe')

@section('content')
<div class="auth-box">
    <div class="brand">
        <div class="logo">56</div>
        <h2>Welcome Back</h2>
        <p>Sign in to manage your studio bookings</p>
    </div>

    @if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if(session('warning'))
    <div class="alert-error">{{ session('warning') }}</div>
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
            <div class="pw-wrap">
                <input type="password" name="password" id="password" class="form-control" required placeholder="Enter your password">
                <span class="pw-toggle" onclick="togglePassword('password')"><span class="eye-icon">👁</span></span>
            </div>
            @error('password') <div class="error-text">{{ $message }}</div> @enderror
        </div>
        <div class="remember-row">
            <label><input type="checkbox" name="remember"> Remember me</label>
            <a href="{{ route('password.forgot') }}" class="forgot-link">Forgot password?</a>
        </div>
        <button type="submit" class="btn-submit">Sign In</button>
    </form>
    <div class="auth-links">
        Don't have an account? <a href="{{ route('register') }}">Create one</a>
        <span class="divider">·</span>
        <a href="{{ route('home') }}">← Home</a>
    </div>
</div>
@endsection

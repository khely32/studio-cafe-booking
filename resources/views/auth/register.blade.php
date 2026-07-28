@extends('auth.layout')
@section('title', 'Create Account - 56\'30 Studio Cafe')

@section('content')
<div class="auth-box">
    <div class="brand">
        <div class="logo">56</div>
        <h2>Create Account</h2>
        <p>Join 56'30 Studio Cafe to start booking</p>
    </div>

    @if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus placeholder="Juan Dela Cruz">
            @error('name') <div class="error-text">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="you@email.com">
            @error('email') <div class="error-text">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>Password</label>
            <div class="pw-wrap">
                <input type="password" name="password" id="password" class="form-control" required placeholder="Min. 8 characters" minlength="8">
                <span class="pw-toggle" onclick="togglePassword('password')"><span class="eye-icon">👁</span></span>
            </div>
            @error('password') <div class="error-text">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>Confirm Password</label>
            <div class="pw-wrap">
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required placeholder="Re-enter password" minlength="8">
                <span class="pw-toggle" onclick="togglePassword('password_confirmation')"><span class="eye-icon">👁</span></span>
            </div>
        </div>
        <button type="submit" class="btn-submit" style="margin-top:4px;">Create Account</button>
    </form>
    <div class="auth-links">
        Already have an account? <a href="{{ route('login') }}">Sign in</a>
        <span class="divider">·</span>
        <a href="{{ route('home') }}">← Home</a>
    </div>
</div>
@endsection

@extends('auth.layout')
@section('title', 'Forgot Password - 56\'30 Studio Cafe')

@section('content')
<div class="auth-box">
    <div class="brand">
        <div class="logo">56</div>
        <h2>Forgot Password?</h2>
        <p>Enter your email and we'll send you a reset link</p>
    </div>

    @if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('password.send') }}">
        @csrf
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="you@email.com">
            @error('email') <div class="error-text">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn-submit">Send Reset Link</button>
    </form>
    <div class="auth-links">
        Remember your password? <a href="{{ route('login') }}">Sign in</a>
        <span class="divider">·</span>
        <a href="{{ route('home') }}">← Home</a>
    </div>
</div>
@endsection

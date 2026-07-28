@extends('auth.layout')
@section('title', 'Reset Password - 56\'30 Studio Cafe')

@section('content')
<div class="auth-box">
    <div class="brand">
        <div class="logo">56</div>
        <h2>Reset Password</h2>
        <p>Enter your new password below</p>
    </div>

    @if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('password.reset') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email_display" class="form-control" value="{{ $email }}" disabled>
        </div>
        <div class="form-group">
            <label>New Password</label>
            <div class="pw-wrap">
                <input type="password" name="password" id="password" class="form-control" required placeholder="Min. 8 characters" minlength="8">
                <span class="pw-toggle" onclick="togglePassword('password')"><span class="eye-icon">👁</span></span>
            </div>
            @error('password') <div class="error-text">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>Confirm New Password</label>
            <div class="pw-wrap">
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required placeholder="Re-enter password" minlength="8">
                <span class="pw-toggle" onclick="togglePassword('password_confirmation')"><span class="eye-icon">👁</span></span>
            </div>
        </div>
        <button type="submit" class="btn-submit">Reset Password</button>
    </form>
    <div class="auth-links">
        <a href="{{ route('login') }}">← Back to Sign In</a>
    </div>
</div>
@endsection

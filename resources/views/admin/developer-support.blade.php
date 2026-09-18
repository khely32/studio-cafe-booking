@extends('admin.layout')
@section('title', 'Developer Support')

@section('styles')
<style>
    .dev-page {
        padding: 28px 32px;
        background: #F4F6F8;
        min-height: calc(100vh - 64px);
    }

    @media (max-width: 768px) {
        .dev-page { padding: 20px 16px; }
    }

    .dev-card {
        max-width: 520px;
        margin: 0 auto;
        background: #fff;
        border: 1px solid #E5E7EB;
        border-radius: 18px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.03);
        padding: 36px 32px;
        text-align: center;
    }

    .dev-icon {
        width: 72px; height: 72px;
        margin: 0 auto 18px;
        border-radius: 50%;
        background: linear-gradient(135deg, #C8A96A, #A8894A);
        color: #fff;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 8px 24px rgba(200,169,106,0.35);
    }
    .dev-icon svg { width: 30px; height: 30px; }

    .dev-title {
        font-size: 22px; font-weight: 700;
        color: var(--gray-900);
        letter-spacing: -0.3px;
    }
    .dev-desc {
        font-size: 14px; color: var(--gray-500);
        line-height: 1.6; margin-top: 6px;
    }

    .dev-divider {
        height: 1px; background: #F3F2EF;
        margin: 22px 0;
    }

    .dev-name {
        font-size: 18px; font-weight: 700;
        color: var(--gray-900);
    }
    .dev-role {
        font-size: 13px; font-weight: 600;
        color: #8B6F47;
        letter-spacing: 0.5px;
        margin-top: 3px;
    }

    .dev-actions {
        display: flex; flex-direction: column;
        gap: 10px;
        margin-top: 22px;
    }
    .dev-btn {
        display: flex; align-items: center; gap: 12px;
        width: 100%; padding: 12px 16px;
        border-radius: 12px;
        border: 1px solid #E5E7EB;
        background: #fff;
        font-size: 14px; font-weight: 600; color: var(--gray-900);
        text-decoration: none; font-family: inherit; cursor: pointer;
        transition: all 0.2s ease;
        text-align: left;
    }
    .dev-btn:hover {
        border-color: #C8A96A;
        box-shadow: 0 4px 14px rgba(200,169,106,0.18);
        transform: translateY(-1px);
    }
    .dev-btn-icon {
        width: 38px; height: 38px;
        border-radius: 10px;
        flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        color: #fff;
    }
    .dev-btn-icon svg { width: 18px; height: 18px; }
    .dev-btn-icon.call    { background: #8B6F47; }
    .dev-btn-icon.email   { background: #C8A96A; }
    .dev-btn-icon.msg     { background: #A8894A; }

    .dev-btn-meta {
        margin-left: auto;
        text-align: right;
        font-size: 12px; font-weight: 500; color: var(--gray-500);
        word-break: break-word;
    }
    .dev-btn-label { display: block; }
    .dev-btn-value {
        display: block; font-size: 12px; color: var(--gray-500);
        font-weight: 400; margin-top: 1px;
        word-break: break-word;
    }

    @media (max-width: 420px) {
        .dev-card { padding: 28px 18px; }
        .dev-btn { padding: 12px 12px; }
        .dev-btn-value { font-size: 11px; }
    }
</style>
@endsection

@section('content')
<div class="dev-page">
    <div class="dev-card">
        <div class="dev-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
        </div>
        <h1 class="dev-title">Developer Support</h1>
        <p class="dev-desc">Need help with the website? Contact the web developer for technical assistance.</p>

        <div class="dev-divider"></div>

        <div class="dev-name">{{ $developer['name'] }}</div>
        <div class="dev-role">{{ $developer['role'] }}</div>

        <div class="dev-actions">
            <a class="dev-btn" href="tel:{{ $developer['phone'] }}">
                <span class="dev-btn-icon call">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                </span>
                <span>
                    <span class="dev-btn-label">Call Developer</span>
                    <span class="dev-btn-value">{{ $developer['phone'] }}</span>
                </span>
                <span class="dev-btn-meta">Call</span>
            </a>

            <a class="dev-btn" href="mailto:{{ $developer['email'] }}">
                <span class="dev-btn-icon email">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                </span>
                <span>
                    <span class="dev-btn-label">Email Developer</span>
                    <span class="dev-btn-value">{{ $developer['email'] }}</span>
                </span>
                <span class="dev-btn-meta">Email</span>
            </a>

            <a class="dev-btn" href="https://wa.me/639932574463" target="_blank" rel="noopener">
                <span class="dev-btn-icon msg">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                </span>
                <span>
                    <span class="dev-btn-label">Message Developer</span>
                    <span class="dev-btn-value">WhatsApp</span>
                </span>
                <span class="dev-btn-meta">Chat</span>
            </a>
        </div>
    </div>
</div>
@endsection
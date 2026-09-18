@extends('admin.layout')
@section('title', 'Developer Support')

@section('styles')
<style>
    .dev-page {
        background: #F5F2EC;
        min-height: calc(100vh - 64px);
        padding: 28px 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    @media (max-width: 768px) {
        .dev-page { padding: 20px 16px; }
    }

    .dev-card {
        width: 100%;
        max-width: 448px;
        background: #FAF8F5;
        border: 1px solid #E3DAC9;
        border-radius: 24px;
        padding: 32px;
        box-shadow: 0 25px 60px rgba(45,38,33,0.14);
        position: relative;
        overflow: hidden;
    }
    .dev-accent {
        position: absolute;
        top: -48px; right: -48px;
        width: 128px; height: 128px;
        border-radius: 50%;
        background: rgba(194,155,56,0.10);
        filter: blur(24px);
        pointer-events: none;
    }

    .dev-icon {
        width: 64px; height: 64px;
        margin: 0 auto 20px;
        border-radius: 16px;
        background: #2C221E;
        color: #D4AF37;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 8px 20px rgba(44,34,30,0.25);
        border: 1px solid #3D302A;
    }
    .dev-icon svg { width: 28px; height: 28px; }

    .dev-eyebrow {
        display: flex; align-items: center; justify-content: center;
        gap: 6px;
        color: #A37B2C;
        font-size: 11px; font-weight: 600;
        letter-spacing: 0.14em; text-transform: uppercase;
        margin-bottom: 6px;
    }
    .dev-eyebrow svg { width: 13px; height: 13px; }

    .dev-title {
        text-align: center;
        font-family: Georgia, 'Times New Roman', serif;
        font-size: 24px; font-weight: 700;
        color: #2C221E;
        letter-spacing: -0.2px;
    }
    .dev-desc {
        text-align: center;
        font-size: 12px; color: #7A6E65;
        max-width: 300px; margin: 6px auto 0;
        line-height: 1.65;
    }

    .dev-divider {
        height: 1px; background: #E3DAC9;
        margin: 24px 0;
    }

    .dev-profile {
        text-align: center;
        background: rgba(250,248,245,0.72);
        border: 1px solid rgba(227,218,201,0.6);
        border-radius: 16px;
        padding: 14px;
        margin-bottom: 24px;
    }
    .dev-name {
        font-size: 16px; font-weight: 700; color: #2C221E;
    }
    .dev-role {
        font-size: 12px; font-weight: 500; color: #A37B2C;
        margin-top: 2px;
    }

    .dev-actions {
        display: flex; flex-direction: column;
        gap: 12px;
    }
    .dev-btn {
        display: flex; align-items: center; justify-content: space-between;
        gap: 12px;
        padding: 14px;
        border-radius: 16px;
        border: 1px solid #E3DAC9;
        background:#FAF8F5;
        text-decoration: none;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(45,38,33,0.05);
    }
    .dev-btn:hover {
        background: #F0EAE1;
        border-color: #D9CCB4;
    }
    .dev-btn-left { display: flex; align-items: center; gap: 14px; min-width: 0; }
    .dev-btn-icon {
        width: 42px; height: 42px; border-radius: 12px;
        background: #2C221E; color: #E8DCC4;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        transition: transform 0.2s ease;
    }
    .dev-btn:hover .dev-btn-icon { transform: scale(1.06); }
    .dev-btn-icon svg { width: 18px; height: 18px; }
    .dev-btn-label { font-size: 12px; font-weight: 700; color: #2C221E; }
    .dev-btn-value { font-size: 12px; color: #7A6E65; }

    .dev-btn-arrow {
        font-size: 12px; font-weight: 600; color: #A37B2C;
        white-space: nowrap;
        transition: transform 0.2s ease;
    }
    .dev-btn:hover .dev-btn-arrow { transform: translateX(2px); }

    @media (max-width: 420px) {
        .dev-card { padding: 24px 18px; }
    }
</style>
@endsection

@section('content')
<div class="dev-page">
    <div class="dev-card anim anim-d1">
        <div class="dev-accent"></div>

        <div class="dev-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
        </div>

        <div class="dev-eyebrow">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l1.9 5.8a2 2 0 0 0 1.3 1.3L21 12l-5.8 1.9a2 2 0 0 0-1.3 1.3L12 21l-1.9-5.8a2 2 0 0 0-1.3-1.3L3 12l5.8-1.9a2 2 0 0 0 1.3-1.3z"/></svg>
            Technical Support
        </div>
        <h2 class="dev-title">Developer Support</h2>
        <p class="dev-desc">Having trouble with the studio-cafe platform? Contact our web developer for assistance.</p>

        <div class="dev-divider"></div>

        <div class="dev-profile">
            <div class="dev-name">{{ $developer['name'] }}</div>
            <div class="dev-role">{{ $developer['role'] }}</div>
        </div>

        <div class="dev-actions">

            {{-- Call --}}
            <a class="dev-btn" href="tel:{{ $developer['phone'] }}">
                <span class="dev-btn-left">
                    <span class="dev-btn-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    </span>
                    <span>
                        <span class="dev-btn-label">Call Developer</span>
                        <span class="dev-btn-value">{{ $developer['phone'] }}</span>
                    </span>
                </span>
                <span class="dev-btn-arrow">Call →</span>
            </a>

            {{-- Email --}}
            <a class="dev-btn" href="mailto:{{ $developer['email'] }}">
                <span class="dev-btn-left">
                    <span class="dev-btn-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    </span>
                    <span>
                        <span class="dev-btn-label">Email Developer</span>
                        <span class="dev-btn-value">{{ $developer['email'] }}</span>
                    </span>
                </span>
                <span class="dev-btn-arrow">Email →</span>
            </a>

            {{-- WhatsApp --}}
            <a class="dev-btn" href="https://wa.me/639932574463" target="_blank" rel="noopener">
                <span class="dev-btn-left">
                    <span class="dev-btn-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                    </span>
                    <span>
                        <span class="dev-btn-label">Message Developer</span>
                        <span class="dev-btn-value">WhatsApp</span>
                    </span>
                </span>
                <span class="dev-btn-arrow">Chat →</span>
            </a>

        </div>
    </div>
</div>
@endsection
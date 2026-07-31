<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', '56\'30 Studio Cafe')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            padding: 40px 24px; position: relative; overflow: hidden;
            background: #000000;
        }
        .bg-gradient {
            position: fixed; inset: 0; z-index: 0;
            background: linear-gradient(135deg, #1A1A1A 0%, #2A2A2A 25%, #0F0F0F 50%, #1A1A1A 75%, #000000 100%);
            background-size: 400% 400%; animation: gradientShift 12s ease infinite;
        }
        @keyframes gradientShift { 0%{background-position:0% 50%} 50%{background-position:100% 50%} 100%{background-position:0% 50%} }
        .orb { position: fixed; border-radius: 50%; filter: blur(80px); z-index: 0; }
        .orb-1 { width: 400px; height: 400px; top: -100px; left: -100px; background: radial-gradient(circle, rgba(255,255,255,0.06), transparent 70%); animation: float1 18s ease-in-out infinite; }
        .orb-2 { width: 350px; height: 350px; bottom: -80px; right: -80px; background: radial-gradient(circle, rgba(255,255,255,0.05), transparent 70%); animation: float2 22s ease-in-out infinite; }
        .orb-3 { width: 300px; height: 300px; top: 50%; left: 50%; transform: translate(-50%,-50%); background: radial-gradient(circle, rgba(255,255,255,0.04), transparent 70%); animation: float3 15s ease-in-out infinite; }
        .orb-4 { width: 250px; height: 250px; top: 20%; right: 15%; background: radial-gradient(circle, rgba(255,255,255,0.03), transparent 70%); animation: float4 20s ease-in-out infinite; }
        @keyframes float1 { 0%,100%{transform:translate(0,0) scale(1)} 33%{transform:translate(80px,60px) scale(1.1)} 66%{transform:translate(-40px,100px) scale(0.9)} }
        @keyframes float2 { 0%,100%{transform:translate(0,0) scale(1)} 33%{transform:translate(-70px,-50px) scale(1.15)} 66%{transform:translate(50px,-80px) scale(0.85)} }
        @keyframes float3 { 0%,100%{transform:translate(-50%,-50%) scale(1)} 50%{transform:translate(-40%,-60%) scale(1.2)} }
        @keyframes float4 { 0%,100%{transform:translate(0,0) scale(1)} 50%{transform:translate(-60px,40px) scale(1.1)} }
        .particles { position: fixed; inset: 0; z-index: 0; pointer-events: none; }
        .particle { position: absolute; border-radius: 50%; animation: drift linear infinite; }
        @keyframes drift { 0%{transform:translateY(100vh) scale(0);opacity:0} 10%{opacity:1} 90%{opacity:1} 100%{transform:translateY(-10vh) scale(1);opacity:0} }
        .grain { position: fixed; inset: 0; z-index: 1; pointer-events: none; opacity: 0.03; background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='1'/%3E%3C/svg%3E"); background-size: 256px 256px; }

        .auth-box {
            width: 100%; max-width: 440px; position: relative; z-index: 10;
            background: rgba(255,255,255,0.97); border-radius: 28px;
            padding: 40px; box-shadow: 0 20px 60px rgba(0,0,0,0.5), 0 0 120px rgba(255,255,255,0.02);
            backdrop-filter: blur(20px);
        }
        .brand { text-align: center; margin-bottom: 28px; }
        .brand .logo {
            width: 56px; height: 56px; border-radius: 16px;
            background: #000000;
            display: inline-flex; align-items: center; justify-content: center;
            font-family: 'Poppins', sans-serif; font-size: 18px; font-weight: 700;
            color: #fff; margin-bottom: 14px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.25);
            animation: logoPulse 3s ease-in-out infinite;
        }
        @keyframes logoPulse { 0%,100%{box-shadow:0 4px 24px rgba(0,0,0,0.25)} 50%{box-shadow:0 4px 36px rgba(0,0,0,0.35)} }
        .brand h2 { font-family: 'Poppins', sans-serif; font-size: 22px; font-weight: 700; color: #111; }
        .brand p { font-size: 13px; color: #6B7280; margin-top: 4px; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; color: #374151; }
        .form-control {
            width: 100%; padding: 11px 14px; border: 2px solid #D1D5DB;
            border-radius: 10px; font-size: 14px; font-family: 'Inter', sans-serif;
            transition: all 0.3s; background: #fff; color: #111;
        }
        .form-control:focus { outline: none; border-color: #000000; box-shadow: 0 0 0 4px rgba(0,0,0,0.06); }
        .form-control::placeholder { color: #9CA3AF; }
        .pw-wrap { position: relative; }
        .pw-wrap .form-control { padding-right: 44px; }
        .pw-toggle { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #9CA3AF; font-size: 18px; user-select: none; padding: 4px; line-height: 1; }
        .pw-toggle:hover { color: #000000; }
        .btn-submit {
            width: 100%; padding: 13px; border: none; border-radius: 100px;
            background: #000000;
            color: #fff; font-size: 15px; font-weight: 600; cursor: pointer;
            font-family: 'Inter', sans-serif;
            box-shadow: 0 4px 16px rgba(0,0,0,0.2);
            transition: all 0.3s; position: relative; overflow: hidden;
        }
        .btn-submit::before {
            content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.25), transparent);
            transition: left 0.5s;
        }
        .btn-submit:hover::before { left: 100%; }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 24px rgba(0,0,0,0.3);
        }
        .error-text { color: #DC2626; font-size: 12px; margin-top: 4px; }
        .alert-error { background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA; padding: 10px 14px; border-radius: 10px; margin-bottom: 16px; font-size: 13px; }
        .alert-success { background: #F0FDF4; color: #16A34A; border: 1px solid #BBF7D0; padding: 10px 14px; border-radius: 10px; margin-bottom: 16px; font-size: 13px; }
        .auth-links { text-align: center; margin-top: 20px; font-size: 13px; color: #6B7280; }
        .auth-links a { color: #000000; text-decoration: none; font-weight: 600; }
        .auth-links a:hover { text-decoration: underline; }
        .auth-links .divider { display: inline; margin: 0 8px; color: #D1D5DB; }
        .remember-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
        .remember-row label { font-size: 13px; color: #6B7280; margin: 0; display: flex; align-items: center; gap: 6px; cursor: pointer; }
        .remember-row input { accent-color: #000000; width: 15px; height: 15px; }
        .forgot-link { font-size: 13px; color: #000000; text-decoration: none; font-weight: 500; }
        .forgot-link:hover { text-decoration: underline; }
    </style>
    <style>
        /* ===== CAMERA SHUTTER ANIMATION ===== */
        .shutter-overlay {
            position: fixed; inset: 0; z-index: 9999;
            background: #0A0A0A;
            display: flex; align-items: center; justify-content: center;
            transition: opacity 0.5s ease 0.2s;
        }
        .shutter-overlay.open {
            opacity: 0; pointer-events: none;
        }
        .shutter-container {
            position: relative; width: 300px; height: 300px;
        }
        .shutter-blade {
            position: absolute; top: 50%; left: 50%;
            width: 160px; height: 160px;
            transform-origin: 0% 0%;
            background: #1A1A1A;
            border: 1px solid #333;
            transition: transform 1s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .shutter-overlay.open .shutter-blade {
            transform: rotate(var(--rotate-out)) !important;
            opacity: 0;
        }
        .shutter-blade:nth-child(1) { --rotate-out: -120deg; transform: rotate(0deg) skewX(30deg); }
        .shutter-blade:nth-child(2) { --rotate-out: -120deg; transform: rotate(45deg) skewX(30deg); }
        .shutter-blade:nth-child(3) { --rotate-out: -120deg; transform: rotate(90deg) skewX(30deg); }
        .shutter-blade:nth-child(4) { --rotate-out: -120deg; transform: rotate(135deg) skewX(30deg); }
        .shutter-blade:nth-child(5) { --rotate-out: -120deg; transform: rotate(180deg) skewX(30deg); }
        .shutter-blade:nth-child(6) { --rotate-out: -120deg; transform: rotate(225deg) skewX(30deg); }
        .shutter-blade:nth-child(7) { --rotate-out: -120deg; transform: rotate(270deg) skewX(30deg); }
        .shutter-blade:nth-child(8) { --rotate-out: -120deg; transform: rotate(315deg) skewX(30deg); }
        .shutter-center {
            position: absolute; top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            z-index: 10;
            transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.5s;
        }
        .shutter-overlay.open .shutter-center {
            transform: translate(-50%, -50%) scale(3);
            opacity: 0;
        }
        .shutter-center svg {
            width: 80px; height: 80px;
            filter: drop-shadow(0 0 20px rgba(0,0,0,0.3));
        }
        .shutter-flash {
            position: fixed; inset: 0; z-index: 10000;
            background: #fff; opacity: 0;
            pointer-events: none;
            transition: opacity 0.1s;
        }
        .shutter-flash.flash {
            opacity: 1;
            animation: flashFade 0.6s ease 0.1s forwards;
        }
        @keyframes flashFade {
            0% { opacity: 0.9; }
            100% { opacity: 0; }
        }
        .shutter-ring {
            position: absolute; top: 50%; left: 50%;
            width: 120px; height: 120px;
            border: 3px solid rgba(0,0,0,0.1);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.5s;
        }
        .shutter-overlay.open .shutter-ring {
            transform: translate(-50%, -50%) scale(4);
            opacity: 0;
        }
        .shutter-ring-outer {
            position: absolute; top: 50%; left: 50%;
            width: 200px; height: 200px;
            border: 1px solid rgba(201,169,110,0.15);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: transform 1s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.5s;
        }
        .shutter-overlay.open .shutter-ring-outer {
            transform: translate(-50%, -50%) scale(3);
            opacity: 0;
        }

        /* Auth box hidden initially */
        .auth-box {
            opacity: 0;
            transform: translateY(30px) scale(0.96);
            pointer-events: none;
        }
        .auth-box.reveal {
            animation: authReveal 0.7s ease forwards;
            pointer-events: all;
        }
        @keyframes authReveal {
            0% { opacity: 0; transform: translateY(30px) scale(0.96); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="bg-gradient"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
    <div class="orb orb-4"></div>
    <div class="grain"></div>
    <div class="particles" id="particles"></div>

    <!-- CAMERA SHUTTER -->
    <div class="shutter-overlay" id="shutter">
        <div class="shutter-ring-outer"></div>
        <div class="shutter-ring"></div>
        <div class="shutter-container">
            <div class="shutter-blade"></div>
            <div class="shutter-blade"></div>
            <div class="shutter-blade"></div>
            <div class="shutter-blade"></div>
            <div class="shutter-blade"></div>
            <div class="shutter-blade"></div>
            <div class="shutter-blade"></div>
            <div class="shutter-blade"></div>
            <div class="shutter-center">
                <svg viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="40" cy="40" r="38" stroke="#555" stroke-width="2"/>
                    <circle cx="40" cy="40" r="28" stroke="#555" stroke-width="1.5" opacity="0.6"/>
                    <circle cx="40" cy="40" r="18" stroke="#777" stroke-width="1" opacity="0.4"/>
                    <circle cx="40" cy="40" r="8" fill="#555" opacity="0.8"/>
                    <circle cx="40" cy="40" r="3" fill="#777"/>
                </svg>
            </div>
        </div>
    </div>
    <div class="shutter-flash" id="shutterFlash"></div>

    @yield('content')

    <script>
    (function() {
        const c = document.getElementById('particles');
        const colors = ['#000','#333','#555','#777','#999'];
        for (let i = 0; i < 25; i++) {
            const p = document.createElement('div');
            p.className = 'particle';
            const s = Math.random() * 6 + 2;
            const color = colors[Math.floor(Math.random() * colors.length)];
            p.style.cssText = `width:${s}px;height:${s}px;left:${Math.random()*100}%;background:radial-gradient(circle,${color}80,${color}00 70%);animation-duration:${Math.random()*15+10}s;animation-delay:${Math.random()*15}s;opacity:${Math.random()*0.5+0.2}`;
            c.appendChild(p);
        }

        // Camera shutter animation sequence
        const shutter = document.getElementById('shutter');
        const flash = document.getElementById('shutterFlash');
        const authBox = document.querySelector('.auth-box');

        // Step 1: Flash (camera click) after 0.8s
        setTimeout(function() {
            flash.classList.add('flash');
        }, 800);

        // Step 2: Open shutter after 1.2s
        setTimeout(function() {
            shutter.classList.add('open');
        }, 1200);

        // Step 3: Reveal auth form after 1.6s
        setTimeout(function() {
            if (authBox) authBox.classList.add('reveal');
        }, 1600);

        // Step 4: Remove shutter from DOM after animation
        setTimeout(function() {
            if (shutter) shutter.style.display = 'none';
            if (flash) flash.style.display = 'none';
        }, 2500);
    })();
    function togglePassword(id) {
        const input = document.getElementById(id);
        const icon = input.nextElementSibling.querySelector('.eye-icon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.textContent = '🙈';
        } else {
            input.type = 'password';
            icon.textContent = '👁';
        }
    }
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $poll->question }} - 56'30 Studio Cafe</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            padding: 40px 20px;
            background: linear-gradient(135deg, #3E2723, #6F4E37, #3E2723);
            position: relative;
        }
        body::before {
            content: ''; position: fixed; inset: 0;
            background:
                radial-gradient(ellipse at 20% 30%, rgba(201,169,110,0.08), transparent 50%),
                radial-gradient(ellipse at 80% 70%, rgba(255,255,255,0.03), transparent 40%);
            pointer-events: none;
        }
        body::after {
            content: ''; position: fixed; inset: 0; opacity: 0.035;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 512 512' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='6' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
            background-size: 512px 512px; pointer-events: none;
        }
        .poll-card {
            background: rgba(255,255,255,0.92); backdrop-filter: blur(24px);
            border-radius: 20px;
            border: 1px solid rgba(201,169,110,0.15);
            box-shadow: 0 24px 80px rgba(0,0,0,0.25);
            max-width: 560px; width: 100%; padding: 40px;
            position: relative; z-index: 1;
        }
        .brand { text-align: center; margin-bottom: 28px; }
        .brand h2 {
            font-family: 'Poppins', sans-serif; font-size: 20px; font-weight: 700;
            background: linear-gradient(135deg, #8B6F47, #C9A96E);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .brand p { font-size: 12px; color: #9C8E7C; margin-top: 2px; }
        h1 {
            font-family: 'Poppins', sans-serif; font-size: 26px; font-weight: 700;
            text-align: center; margin-bottom: 8px; color: #3E2723;
        }
        .desc { text-align: center; color: #7A6C5C; font-size: 14px; margin-bottom: 32px; }
        .option {
            display: flex; align-items: center; gap: 12px;
            padding: 14px 16px;
            border: 2px solid rgba(224,212,196,0.5);
            border-radius: 14px; margin-bottom: 10px; cursor: pointer;
            transition: all 0.2s;
            background: rgba(255,255,255,0.5);
        }
        .option:hover { border-color: #C9A96E; background: rgba(255,253,249,0.8); }
        .option.selected { border-color: #C9A96E; background: linear-gradient(135deg, rgba(201,169,110,0.1), rgba(201,169,110,0.05)); }
        .option input { accent-color: #C9A96E; width: 18px; height: 18px; }
        .option span { font-size: 15px; font-weight: 500; color: #4A3C2E; }
        .btn-vote {
            width: 100%; padding: 14px; border: none; border-radius: 100px;
            background: linear-gradient(135deg, #8B6F47, #C9A96E);
            color: #fff; font-size: 15px; font-weight: 600;
            font-family: 'Inter', sans-serif; cursor: pointer;
            margin-top: 24px; transition: all 0.25s;
            box-shadow: 0 4px 16px rgba(139,111,71,0.3);
        }
        .btn-vote:hover { transform: translateY(-1px); box-shadow: 0 6px 24px rgba(139,111,71,0.4); }
        .btn-vote:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
        .alert { padding: 12px 18px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; text-align: center; }
        .alert-error { background: #FEE2E2; color: #991B1B; border: 1px solid #FECACA; }
        .results-bar { height: 8px; background: rgba(224,212,196,0.5); border-radius: 100px; overflow: hidden; margin-top: 8px; }
        .results-fill { height: 100%; background: linear-gradient(135deg, #8B6F47, #C9A96E); border-radius: 100px; }
        .results-label { font-size: 12px; color: #7A6C5C; margin-top: 4px; }
        .closed-msg { text-align: center; padding: 40px; }
        .closed-msg p { color: #7A6C5C; font-size: 16px; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #C4B8A8; }
    </style>
</head>
<body>
    <div class="poll-card">
        <div class="brand">
            <h2>56'30 Studio Cafe</h2>
            <p>We value your opinion</p>
        </div>
        <h1>{{ $poll->question }}</h1>
        @if($poll->description)<p class="desc">{{ $poll->description }}</p>@endif

        @if(session('success'))
        <div class="alert" style="background:#D1FAE5;color:#065F46;border:1px solid #A7F3D0;">Thank you for voting!</div>
        @endif

        @if($poll->is_closed)
        <div class="closed-msg">
            <p>This poll is closed.</p>
        </div>
        @else
        @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        <form method="POST" action="{{ route('polls.vote', $poll) }}">
            @csrf
            @foreach($poll->options as $opt)
            <label class="option" onclick="this.classList.toggle('selected')">
                <input type="{{ $poll->allow_multiple ? 'checkbox' : 'radio' }}" name="options[]" value="{{ $opt->id }}">
                <span>{{ $opt->text }}</span>
            </label>
            @endforeach
            <button type="submit" class="btn-vote">Submit Vote</button>
        </form>
        @endif

        <p class="footer">56'30 Studio Cafe &copy; {{ date('Y') }}</p>
    </div>
</body>
</html>
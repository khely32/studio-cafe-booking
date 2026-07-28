<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $poll->question }} - 56'30 Studio Cafe</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DM Sans', sans-serif; background: #FAF6F1; color: #2C2C2C; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px; }
        .poll-card { background: #fff; border-radius: 20px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); max-width: 560px; width: 100%; padding: 40px; }
        .brand { text-align: center; margin-bottom: 28px; }
        .brand h2 { font-family: 'Playfair Display', serif; font-size: 18px; color: #C9A96E; }
        .brand p { font-size: 12px; color: #aaa; margin-top: 2px; }
        h1 { font-family: 'Playfair Display', serif; font-size: 26px; text-align: center; margin-bottom: 8px; }
        .desc { text-align: center; color: #888; font-size: 14px; margin-bottom: 32px; }
        .option { display: flex; align-items: center; gap: 12px; padding: 14px 16px; border: 2px solid #F0E8DD; border-radius: 12px; margin-bottom: 10px; cursor: pointer; transition: all 0.2s; }
        .option:hover { border-color: #C9A96E; background: #FFFDF9; }
        .option.selected { border-color: #C9A96E; background: linear-gradient(135deg, rgba(201,169,110,0.08), rgba(201,169,110,0.04)); }
        .option input { accent-color: #C9A96E; width: 18px; height: 18px; }
        .option span { font-size: 15px; font-weight: 500; }
        .btn-vote { width: 100%; padding: 14px; border: none; border-radius: 100px; background: linear-gradient(135deg, #C9A96E, #A8884D); color: #fff; font-size: 15px; font-weight: 600; font-family: 'DM Sans', sans-serif; cursor: pointer; margin-top: 24px; transition: all 0.25s; box-shadow: 0 4px 16px rgba(201,169,110,0.3); }
        .btn-vote:hover { transform: translateY(-1px); box-shadow: 0 6px 24px rgba(201,169,110,0.4); }
        .btn-vote:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
        .alert { padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; text-align: center; }
        .alert-error { background: #FFEBEE; color: #C62828; }
        .results-bar { height: 8px; background: #F0E8DD; border-radius: 100px; overflow: hidden; margin-top: 8px; }
        .results-fill { height: 100%; background: linear-gradient(135deg, #C9A96E, #A8884D); border-radius: 100px; }
        .results-label { font-size: 12px; color: #888; margin-top: 4px; }
        .closed-msg { text-align: center; padding: 40px; }
        .closed-msg p { color: #888; font-size: 16px; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #ccc; }
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
        <div class="alert" style="background:#E8F5E9;color:#2E7D32;">Thank you for voting!</div>
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

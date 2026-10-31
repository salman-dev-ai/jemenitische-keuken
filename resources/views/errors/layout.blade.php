{{--
    Jemenitische Keuken – gedeelde foutpagina-layout
    Verwacht: $code, $copy[nl|en|ar][title|text]
    Optioneel: $actions (eerste = primaire knop), $livewire (bool), $autoRetry (seconden)
    Bewust géén Vite/Tailwind-afhankelijkheid: foutpagina's moeten ook werken als de build stuk is.
--}}
@php
    $actions   = $actions ?? ['home', 'back'];
    $livewire  = $livewire ?? false;
    $autoRetry = $autoRetry ?? null;

    // Respecteer een Retry-After header (bv. artisan down --retry=60 of throttle)
    $retryAfter = null;
    if ($autoRetry) {
        $headers = (isset($exception) && method_exists($exception, 'getHeaders')) ? $exception->getHeaders() : [];
        $ra = (int) ($headers['Retry-After'] ?? 0);
        $retryAfter = ($ra > 0 && $ra <= 300) ? $ra : (int) $autoRetry;
    }

    $homeUrl  = url('/');
    $loginUrl = \Illuminate\Support\Facades\Route::has('login') ? route('login') : url('/login');

    $ui = [
        'nl' => ['home' => 'Naar de startpagina', 'back' => 'Ga terug', 'retry' => 'Probeer opnieuw', 'login' => 'Inloggen',
                 'retryIn' => 'Automatisch opnieuw proberen over', 'sec' => 'sec', 'lift' => 'Til de stolp op',
                 'report' => 'Meld dit probleem', 'email' => 'E-mailadres (optioneel)', 'message' => 'Wat probeerde je te doen?',
                 'send' => 'Verstuur melding', 'thanks' => 'Bedankt, we kijken ernaar.'],
        'en' => ['home' => 'Go to homepage', 'back' => 'Go back', 'retry' => 'Try again', 'login' => 'Sign in',
                 'retryIn' => 'Retrying automatically in', 'sec' => 'sec', 'lift' => 'Lift the lid',
                 'report' => 'Report this problem', 'email' => 'Email (optional)', 'message' => 'What were you trying to do?',
                 'send' => 'Send report', 'thanks' => 'Thank you, we will look into it.'],
        'ar' => ['home' => 'إلى الصفحة الرئيسية', 'back' => 'رجوع', 'retry' => 'حاول مرة أخرى', 'login' => 'تسجيل الدخول',
                 'retryIn' => 'إعادة المحاولة تلقائيًا بعد', 'sec' => 'ث', 'lift' => 'ارفع الغطاء',
                 'report' => 'أبلغ عن هذه المشكلة', 'email' => 'البريد الإلكتروني (اختياري)', 'message' => 'ماذا كنت تحاول أن تفعل؟',
                 'send' => 'إرسال البلاغ', 'thanks' => 'شكرًا لك، سنراجع الأمر.'],
    ];
@endphp
<!DOCTYPE html>
<html lang="nl" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="robots" content="noindex">
    <meta name="theme-color" content="#350302">
    <title>{{ $code }} – {{ $copy['nl']['title'] }} | Jemenitische Keuken</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&family=Rajdhani:wght@500;600;700&display=swap" rel="stylesheet">

    <style>
        :root{--bg:#350302;--bg-lift:#4b0905;--orange:#c96600;--orange-hi:#ec8d22;--white:#fff;--muted:#ecd3bf;--line:rgba(255,255,255,.16)}
        *{box-sizing:border-box}
        html{color-scheme:dark}
        body{margin:0;min-height:100vh;min-height:100dvh;display:grid;grid-template-rows:auto 1fr auto;
             background:radial-gradient(120% 70% at 50% 0%,var(--bg-lift),var(--bg) 62%) var(--bg);color:var(--white);
             font-family:'Rajdhani','Cairo',system-ui,sans-serif;font-size:1.125rem;line-height:1.55}
        [x-cloak]{display:none!important}
        :focus-visible{outline:3px solid var(--orange-hi);outline-offset:3px}
        .sr{position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0);white-space:nowrap}

        header{display:flex;align-items:center;justify-content:space-between;gap:1rem;
               padding:max(1rem,env(safe-area-inset-top)) clamp(1rem,4vw,2.5rem) 1rem}
        .brand{display:flex;flex-direction:column;line-height:1.05;text-decoration:none;color:inherit}
        .brand-ar{color:var(--orange);font-family:'Cairo',sans-serif;font-weight:700;font-size:1.5rem}
        .brand-nl{font-weight:700;letter-spacing:.06em;text-transform:uppercase;font-size:1rem}
        .lang{display:flex;gap:.25rem}
        .lang button{font:inherit;font-weight:600;color:var(--muted);background:none;border:1px solid transparent;
                     border-radius:.375rem;padding:.25rem .7rem;min-height:2.5rem;cursor:pointer}
        .lang button:hover{color:var(--white)}
        .lang button[aria-pressed=true]{color:var(--white);border-color:var(--orange)}

        main{display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;
             padding:1rem clamp(1rem,4vw,2.5rem) 2rem}
        .stage{background:none;border:0;padding:0;cursor:pointer;width:min(26rem,86vw)}
        .cloche{display:block;width:100%;height:auto;overflow:visible}
        .cloche .lid{transition:transform .9s cubic-bezier(.34,1.45,.5,1)}
        .cloche.is-open .lid{transform:translateY(-78px)}
        .cloche .steam{opacity:.25;transition:opacity 1.2s ease .3s}
        .cloche.is-open .steam{opacity:1}
        .cloche .code{font-family:'Rajdhani',sans-serif;font-weight:700;font-size:84px;fill:var(--orange-hi)}

        h1{margin:.5rem 0 .75rem;font-weight:700;font-size:clamp(1.9rem,6vw,3.25rem);line-height:1.1;
           letter-spacing:.03em;text-transform:uppercase;text-wrap:balance}
        .lead{max-width:36rem;margin:0 auto 1.75rem;color:var(--muted);text-wrap:pretty}
        html[lang=ar] :is(h1,.btn,.lang button){letter-spacing:0}

        .actions{display:flex;flex-wrap:wrap;gap:.75rem;justify-content:center}
        .btn{font:inherit;font-weight:700;letter-spacing:.04em;text-decoration:none;cursor:pointer;
             display:inline-flex;align-items:center;justify-content:center;min-height:3rem;padding:.5rem 1.6rem;
             border-radius:999px;border:2px solid var(--orange);transition:background .2s,border-color .2s}
        .btn-primary{background:var(--orange);color:#1c0100}
        .btn-primary:hover{background:var(--orange-hi);border-color:var(--orange-hi)}
        .btn-ghost{background:transparent;color:var(--white)}
        .btn-ghost:hover{background:rgba(255,255,255,.08)}
        .btn:disabled{opacity:.6;cursor:wait}

        .retry{margin-top:1.5rem;width:min(22rem,90vw);color:var(--muted);font-size:1rem}
        .bar{height:4px;margin-top:.5rem;background:var(--line);border-radius:4px;overflow:hidden}
        .bar span{display:block;height:100%;background:var(--orange);transition:width 1s linear}

        .report{margin-top:1.75rem;width:min(26rem,100%)}
        .link{font:inherit;background:none;border:0;padding:.5rem;color:var(--orange-hi);cursor:pointer;
              text-decoration:underline;text-underline-offset:4px}
        .rp{margin-top:.75rem;padding:1.25rem;border:1px solid var(--line);border-radius:.75rem;
            background:rgba(0,0,0,.18);text-align:start}
        .rp label{display:block;margin-bottom:.9rem;font-weight:600}
        .rp input,.rp textarea{display:block;width:100%;margin-top:.3rem;font:inherit;color:var(--white);
            background:rgba(255,255,255,.06);border:1px solid var(--line);border-radius:.5rem;padding:.6rem .75rem}
        .rp input:focus,.rp textarea:focus{outline:none;border-color:var(--orange-hi);box-shadow:0 0 0 3px rgba(236,141,34,.35)}
        .rp-err{margin:-.5rem 0 .75rem;color:#ffb4a2;font-size:1rem}
        .rp-ok{margin:0;text-align:center;font-weight:700;color:var(--orange-hi)}

        footer{display:flex;flex-wrap:wrap;justify-content:center;gap:1.25rem;border-top:1px solid var(--line);
               padding:1.25rem 1rem calc(1.25rem + env(safe-area-inset-bottom));color:var(--orange-hi);font-weight:600}

        @media (prefers-reduced-motion:reduce){.cloche .lid,.cloche .steam,.bar span{transition:none}}
    </style>
    <noscript><style>.cloche .lid{transform:translateY(-78px)}.cloche .steam{opacity:1}</style></noscript>

    @if ($livewire) @livewireStyles @endif
</head>

<body x-data="errorPage(@js(['copy' => $copy, 'ui' => $ui, 'retry' => $retryAfter ?? 0]))">

<header>
    <a class="brand" href="{{ $homeUrl }}">
        <span class="brand-ar">المطبخ اليمني</span>
        <span class="brand-nl">Jemenitische Keuken</span>
    </a>
    <nav class="lang" aria-label="Taal / Language / اللغة">
        <button type="button" :aria-pressed="lang === 'nl'" @click="setLang('nl')">NL</button>
        <button type="button" :aria-pressed="lang === 'en'" @click="setLang('en')">EN</button>
        <button type="button" :aria-pressed="lang === 'ar'" @click="setLang('ar')">عربي</button>
    </nav>
</header>

<main>
    <button type="button" class="stage" @click="open = !open" :aria-label="ui[lang].lift">
        @include('errors.partials.cloche', ['code' => $code])
    </button>

    <h1><span class="sr">{{ $code }} – </span><span x-text="copy[lang].title">{{ $copy['nl']['title'] }}</span></h1>
    <p class="lead" x-text="copy[lang].text">{{ $copy['nl']['text'] }}</p>

    <div class="actions">
        @foreach ($actions as $i => $action)
            @php $cls = $i === 0 ? 'btn btn-primary' : 'btn btn-ghost'; @endphp
            @if ($action === 'home')
                <a href="{{ $homeUrl }}" class="{{ $cls }}" x-text="ui[lang].home">{{ $ui['nl']['home'] }}</a>
            @elseif ($action === 'login')
                <a href="{{ $loginUrl }}" class="{{ $cls }}" x-text="ui[lang].login">{{ $ui['nl']['login'] }}</a>
            @elseif ($action === 'back')
                <button type="button" class="{{ $cls }}" @click="goBack('{{ $homeUrl }}')" x-text="ui[lang].back">{{ $ui['nl']['back'] }}</button>
            @elseif ($action === 'retry')
                <button type="button" class="{{ $cls }}" @click="location.reload()" x-text="ui[lang].retry">{{ $ui['nl']['retry'] }}</button>
            @endif
        @endforeach
    </div>

    @if ($retryAfter)
        <div class="retry" x-cloak x-show="total > 0" role="status">
            <span x-text="ui[lang].retryIn"></span>
            <strong x-text="left"></strong>
            <span x-text="ui[lang].sec"></span>
            <div class="bar"><span :style="`width:${((total - left) / total) * 100}%`"></span></div>
        </div>
    @endif

    {{-- @if ($livewire)
        <div class="report">
            <button type="button" class="link" @click="report = !report" :aria-expanded="report" x-text="ui[lang].report">
                {{ $ui['nl']['report'] }}
            </button>
            <div class="rp" x-cloak x-show="report" x-transition.opacity>
                <livewire:error-report :status="$code" />
            </div>
        </div>
    @endif --}}
</main>

<footer>
    <span>The Origin Of Mandi</span>
    <span>أصل المندي</span>
</footer>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('errorPage', (cfg) => ({
            lang: 'nl', open: false, report: false,
            copy: cfg.copy, ui: cfg.ui, total: cfg.retry, left: cfg.retry,

            init() {
                let saved = null;
                try { saved = localStorage.getItem('jk-lang'); } catch (e) {}
                const nav = (navigator.language || 'nl').slice(0, 2);
                this.setLang(['nl', 'en', 'ar'].includes(saved) ? saved : (['en', 'ar'].includes(nav) ? nav : 'nl'));

                setTimeout(() => this.open = true, 500);   // de stolp gaat één keer omhoog

                if (this.total > 0) {
                    const timer = setInterval(() => {
                        if (--this.left <= 0) { clearInterval(timer); location.reload(); }
                    }, 1000);
                }
            },

            setLang(l) {
                this.lang = l;
                document.documentElement.lang = l;
                document.documentElement.dir = l === 'ar' ? 'rtl' : 'ltr';
                try { localStorage.setItem('jk-lang', l); } catch (e) {}
            },

            goBack(home) { history.length > 1 ? history.back() : location.assign(home); },
        }));
    });
</script>

@if ($livewire)
    {{-- Livewire levert Alpine zelf mee --}}
    @livewireScripts
@else
    {{-- Pagina's zonder Livewire (419/429/503): Alpine los laden --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.9/dist/cdn.min.js"></script>
@endif
</body>
</html>

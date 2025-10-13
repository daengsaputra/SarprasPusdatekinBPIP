<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SARPRAS PUSDATEKIN' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --brand-blue: #1d4ed8;
            --brand-blue-dark: #1e3a8a;
            --brand-cyan: #38bdf8;
            --brand-ink: #020617;
            --brand-cloud: #f8fafc;
            --brand-slate: #0f172a;
            --surface-1: linear-gradient(140deg, #0b1220 0%, #05060a 55%, #020205 100%);
            --surface-2: rgba(15, 23, 42, 0.78);
            --surface-3: rgba(15, 23, 42, 0.55);
            --border-soft: rgba(148, 163, 184, 0.18);
            --text-primary: #e2e8f0;
            --text-secondary: rgba(226, 232, 240, 0.72);
            --text-muted: rgba(148, 163, 184, 0.75);
        }
        [data-theme="light"] {
            --surface-1: linear-gradient(160deg, #ffffff 0%, #eef2ff 40%, #e2e8f0 100%);
            --surface-2: rgba(241, 245, 249, 0.92);
            --surface-3: rgba(226, 232, 240, 0.78);
            --border-soft: rgba(15, 23, 42, 0.08);
            --text-primary: #0f172a;
            --text-secondary: rgba(30, 41, 59, 0.78);
            --text-muted: rgba(71, 85, 105, 0.68);
        }
        body {
            min-height: 100vh;
            background: var(--surface-1);
            color: var(--text-primary);
            transition: background .35s ease, color .35s ease;
        }
        a { color: var(--brand-cyan); }
        a:hover { color: #7dd3fc; }
        .navbar {
            background: rgba(2, 6, 23, 0.82);
            backdrop-filter: blur(6px);
            border-bottom: 1px solid rgba(59, 130, 246, 0.25);
        }
        [data-theme="light"] .navbar {
            background: rgba(248, 250, 252, 0.9);
            border-bottom: 1px solid rgba(15, 23, 42, 0.08);
        }
        .navbar-brand {
            color: var(--text-primary) !important;
            letter-spacing: 0.18em;
        }
        .nav-link { color: var(--text-secondary) !important; }
        .nav-link:hover, .nav-link:focus { color: var(--text-primary) !important; }
        .btn-cta {
            background: linear-gradient(120deg, var(--brand-blue), var(--brand-blue-dark));
            border: none;
            color: #fff !important;
            box-shadow: 0 12px 30px rgba(30, 64, 175, 0.35);
        }
        .btn-cta:hover {
            background: linear-gradient(120deg, var(--brand-blue-dark), #1d4ed8);
        }
        /* Theme Slider Toggle */
        .theme-toggle-slider {
            cursor: pointer;
            width: 56px;
            height: 28px;
            border-radius: 999px;
            background-color: rgba(15, 23, 42, 0.6);
            border: 1px solid var(--border-soft);
            position: relative;
            position: fixed;
            top: 1.2rem;
            right: 1.5rem;
            z-index: 1100;
            transition: background-color .2s ease;
        }
        .theme-toggle-slider input { display: none; }
        .theme-toggle-slider .slider-knob {
            position: absolute;
            top: 2px;
            left: 2px;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background-color: var(--brand-cyan);
            transition: transform .3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--brand-ink);
        }
        .theme-toggle-slider .slider-knob svg {
            width: 14px;
            height: 14px;
        }
        .theme-toggle-slider .icon-sun { display: none; }
        .theme-toggle-slider .icon-moon { display: block; }

        .theme-toggle-slider input:checked + .slider-knob {
            transform: translateX(28px);
            background-color: #f59e0b;
        }
        .theme-toggle-slider input:checked + .slider-knob .icon-sun { display: block; }
        .theme-toggle-slider input:checked + .slider-knob .icon-moon { display: none; }

        [data-theme="light"] .theme-toggle-slider {
            background-color: rgba(226, 232, 240, 0.8);
        }
        main.container {
            padding-top: 4rem;
            padding-bottom: 5rem;
        }
        footer {
            background: rgba(2, 6, 23, 0.82);
            border-top: 1px solid var(--border-soft);
            color: var(--text-muted);
        }
        [data-theme="light"] footer {
            background: rgba(248, 250, 252, 0.95);
        }
    </style>
    @stack('styles')
</head>
<body data-theme="dark">
<nav class="navbar navbar-expand-lg py-3">
    <div class="container">
        <a class="navbar-brand fw-semibold text-uppercase" href="{{ url('/') }}">SARPRAS PUSDATEKIN</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#landingNav" aria-controls="landingNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="landingNav">
            <ul class="navbar-nav ms-auto gap-2 align-items-lg-center">
                <li class="nav-item"><a class="nav-link" href="#fitur">Fitur</a></li>
                <li class="nav-item"><a class="nav-link" href="#stok">Stok</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('assets.loanable') }}">Data Barang</a></li>
                <li class="nav-item"><a class="btn btn-cta px-4" href="{{ route('login') }}">Login</a></li>
            </ul>
        </div>
    </div>
</nav>

<label for="themeToggleInput" class="theme-toggle-slider" aria-label="Toggle theme">
    <input type="checkbox" id="themeToggleInput">
    <span class="slider-knob">
        <svg class="icon-sun" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.25a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zM7.5 12a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM18.894 6.106a.75.75 0 010 1.06l-1.591 1.59a.75.75 0 11-1.06-1.06l1.59-1.59a.75.75 0 011.06 0zM21.75 12a.75.75 0 01-.75.75h-2.25a.75.75 0 010-1.5H21a.75.75 0 01.75.75zM17.894 17.894a.75.75 0 01-1.06 0l-1.59-1.591a.75.75 0 111.06-1.06l1.59 1.59a.75.75 0 010 1.06zM12 18.75a.75.75 0 01-.75.75v2.25a.75.75 0 011.5 0V19.5a.75.75 0 01-.75-.75zM5.106 17.894a.75.75 0 010-1.06l1.59-1.59a.75.75 0 111.06 1.06l-1.59 1.59a.75.75 0 01-1.06 0zM3 12a.75.75 0 01.75-.75h2.25a.75.75 0 010 1.5H3.75A.75.75 0 013 12zM6.106 5.106a.75.75 0 011.06 0l1.59 1.591a.75.75 0 01-1.06 1.06l-1.59-1.59a.75.75 0 010-1.06z"></path></svg>
        <svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M9.528 1.718a.75.75 0 01.162.819A8.97 8.97 0 009 6a9 9 0 009 9 8.97 8.97 0 004.463-.69a.75.75 0 01.981.981A10.503 10.503 0 0118 19.5a10.5 10.5 0 01-10.5-10.5c0-1.563.34-3.056.942-4.432a.75.75 0 01.819-.162z" clip-rule="evenodd"></path></svg>
    </span>
</label>

<main class="container">
    @yield('content')
</main>

<footer class="text-center small py-4">
    &copy; {{ now()->year }} SARPRAS PUSDATEKIN &ndash; Sarana Prasarana BPIP.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const bodyEl = document.body;
    const toggleInput = document.getElementById('themeToggleInput');
    const storedTheme = localStorage.getItem('landingTheme');

    const applyTheme = (theme) => {
        bodyEl.dataset.theme = theme;
        if (toggleInput) {
            toggleInput.checked = theme === 'light';
        }
        localStorage.setItem('landingTheme', theme);
    };

    // Terapkan tema saat halaman dimuat
    const initialTheme = storedTheme || 'dark';
    applyTheme(initialTheme);               

    if (toggleInput) {
        toggleInput.addEventListener('change', () => {
            const newTheme = toggleInput.checked ? 'light' : 'dark';
            applyTheme(newTheme);
        });
    }
</script>
@stack('scripts')
</body>
</html>

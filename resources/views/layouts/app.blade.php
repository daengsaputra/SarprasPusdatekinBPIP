<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SARPRAS PUSDATEKIN – Sarana Prasarana Aset Pusdatekin' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding-top: 4.5rem; padding-bottom: 3.25rem; }
        .running-info {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg,#0f172a,#1e293b);
            color: #f8fafc;
            z-index: 1035;
            overflow: hidden;
            font-size: 0.9rem;
        }
        .running-info::before,
        .running-info::after {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: 80px;
            pointer-events: none;
        }
        .running-info::before { left: 0; background: linear-gradient(90deg,#0f172a,transparent); }
        .running-info::after { right: 0; background: linear-gradient(270deg,#0f172a,transparent); }
        .running-track {
            display: inline-flex;
            white-space: nowrap;
            gap: 3rem;
            padding: 0.6rem 0;
            animation: ticker 20s linear infinite;
        }
        .running-track span { font-weight: 600; letter-spacing: .02em; }
        @keyframes ticker {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
    </style>
    @stack('styles')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="{{ route('landing') }}">SARPRAS PUSDATEKIN</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-lg-center">
        @auth
          <li class="nav-item d-lg-none"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
          <li class="nav-item d-lg-none"><a class="nav-link" href="{{ route('assets.loanable') }}">Data Barang Peminjaman</a></li>
          <li class="nav-item d-lg-none"><a class="nav-link" href="{{ route('assets.index') }}">Data Barang Aset</a></li>
          <li class="nav-item d-lg-none"><a class="nav-link" href="{{ route('loans.index') }}">Peminjaman</a></li>
        @endauth
      </ul>
      <div class="ms-auto d-flex align-items-center gap-3 text-white-50">
        <span class="d-none d-md-inline">{{ now()->format('Y-m-d') }}</span>
        @guest
          <a href="{{ route('assets.loanable') }}" class="btn btn-outline-primary px-4">Data Barang</a>
          <a href="{{ route('login') }}" class="btn btn-outline-light px-4">Login</a>
        @else
          <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <button class="btn btn-outline-light px-4" type="submit">Logout ({{ auth()->user()->name }}) – {{ strtoupper(auth()->user()->role ?? '') }}</button>
          </form>
        @endguest
      </div>
    </div>
  </div>
</nav>

@auth
  @php
      $assetRouteModel = request()->route('asset');
      if ($assetRouteModel && ! $assetRouteModel instanceof \App\Models\Asset) {
          $assetRouteModel = null;
      }
      $kindParam = request()->input('kind');
      $assetKindFromRoute = $assetRouteModel->kind ?? null;
      $loanableMenuActive = request()->routeIs('assets.loanable')
          || (request()->routeIs('assets.import*') && $kindParam === \App\Models\Asset::KIND_LOANABLE)
          || (request()->routeIs('assets.create') && $kindParam === \App\Models\Asset::KIND_LOANABLE)
          || (request()->routeIs(['assets.edit','assets.update','assets.destroy','assets.photo.destroy']) && $assetKindFromRoute === \App\Models\Asset::KIND_LOANABLE);
      $inventoryMenuActive = !$loanableMenuActive && request()->routeIs(['assets.index','assets.create','assets.edit','assets.import','assets.import.*','assets.export','assets.template','assets.photo.destroy','assets.store','assets.update','assets.destroy']);
  @endphp
  <div class="d-flex" style="min-height: calc(100vh - 56px);">
    <aside class="bg-dark text-white" style="width: 240px; position: fixed; top:56px; bottom:0; left:0;">
      <div class="p-2 small text-uppercase text-white-50">Menu</div>
      <ul class="nav flex-column mb-auto">
        <li class="nav-item my-1"><a class="nav-link text-white {{ request()->routeIs('dashboard') ? 'bg-secondary rounded-1' : 'text-white-50' }}" href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="nav-item my-1"><a class="nav-link text-white {{ $loanableMenuActive ? 'bg-secondary rounded-1' : 'text-white-50' }}" href="{{ route('assets.loanable') }}">Data Barang - Peminjaman</a></li>
        <li class="my-1"><a class="nav-link text-white {{ $inventoryMenuActive ? 'bg-secondary rounded-1' : 'text-white-50' }}" href="{{ route('assets.index') }}">Data Barang - Aset</a></li>
        @if(auth()->user() && auth()->user()->role==='admin')
          <li class="my-1"><a class="nav-link text-white {{ request()->routeIs('users.*') ? 'bg-secondary rounded-1' : 'text-white-50' }}" href="{{ route('users.index') }}">Anggota</a></li>
        @endif
        <li class="my-1"><a class="nav-link text-white {{ request()->routeIs('loans.*') ? 'bg-secondary rounded-1' : 'text-white-50' }}" href="{{ route('loans.index') }}">Peminjaman</a></li>
        <li class="my-1"><a class="nav-link text-white {{ request()->routeIs('reports.returns') ? 'bg-secondary rounded-1' : 'text-white-50' }}" href="{{ route('reports.returns') }}">Pengembalian</a></li>
        <li class="my-1"><a class="nav-link text-white {{ request()->routeIs('reports.loans') ? 'bg-secondary rounded-1' : 'text-white-50' }}" href="{{ route('reports.loans') }}">Laporan</a></li>
      </ul>
      @php
        $logoCandidates = ['images/pusdatin-logo.png','images/pusdatin-logo.webp','images/pusdatin-logo.jpg','images/pusdatin-logo.jpeg','images/logo-sarpras.svg'];
        $logo = null;
        foreach ($logoCandidates as $candidate) {
            if (file_exists(public_path($candidate))) {
                $logo = $candidate;
                break;
            }
        }
      @endphp
      <div class="position-absolute" style="left:12px; right:12px; bottom:12px;">
        <img src="{{ asset($logo ?? 'images/logo-sarpras.svg') }}" alt="Pusdatin" style="height:32px;width:auto;opacity:1;">
      </div>
    </aside>
    <main class="container" style="margin-left:240px;">
      @if(session('success'))
          <div class="alert alert-success alert-auto" data-autohide="5000">{{ session('success') }}</div>
      @endif
      @if(session('error'))
          <div class="alert alert-danger alert-auto" data-autohide="5000">{{ session('error') }}</div>
      @endif
      @if(session('info'))
          <div class="alert alert-info alert-auto" data-autohide="5000">{{ session('info') }}</div>
      @endif
      {{ $slot ?? '' }}
      @yield('content')
    </main>
  </div>
@else
<main class="container">
    @if(session('success'))
        <div class="alert alert-success alert-auto" data-autohide="5000">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-auto" data-autohide="5000">{{ session('error') }}</div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-auto" data-autohide="5000">{{ session('info') }}</div>
    @endif

    {{ $slot ?? '' }}
    @yield('content')
 </main>
 @php
   $logoCandidates = ['images/pusdatin-logo.png','images/pusdatin-logo.webp','images/pusdatin-logo.jpg','images/pusdatin-logo.jpeg','images/logo-sarpras.svg'];
   $logo = null;
   foreach ($logoCandidates as $candidate) {
       if (file_exists(public_path($candidate))) {
           $logo = $candidate;
           break;
       }
   }
 @endphp
 <div class="d-none d-md-block" style="position:fixed; left:12px; bottom:12px; z-index:1030;">
   <img src="{{ asset($logo ?? 'images/logo-sarpras.svg') }}" alt="Pusdatin" style="height:32px;width:auto;opacity:1;">
 </div>
@endauth

@php
  $loanableTotals = [
    'total' => \App\Models\Asset::where('kind', \App\Models\Asset::KIND_LOANABLE)->sum('quantity_total'),
    'available' => \App\Models\Asset::where('kind', \App\Models\Asset::KIND_LOANABLE)->sum('quantity_available'),
    'borrowed' => \App\Models\Loan::where('status','borrowed')->sum('quantity')
  ];
@endphp
<div class="running-info">
  <div class="running-track">
    <span>Info persediaan: Total barang peminjaman {{ number_format($loanableTotals['total']) }} unit • Sedang dipinjam {{ number_format($loanableTotals['borrowed']) }} unit • Tersedia {{ number_format(max($loanableTotals['available'], 0)) }} unit</span>
    <span>Info persediaan: Total barang peminjaman {{ number_format($loanableTotals['total']) }} unit • Sedang dipinjam {{ number_format($loanableTotals['borrowed']) }} unit • Tersedia {{ number_format(max($loanableTotals['available'], 0)) }} unit</span>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.alert.alert-auto').forEach((alertEl) => {
      const timeout = parseInt(alertEl.getAttribute('data-autohide'), 10) || 5000;
      setTimeout(() => {
        alertEl.classList.add('fade');
        alertEl.style.transition = 'opacity 0.2s ease';
        alertEl.style.opacity = '0';
        setTimeout(() => alertEl.remove(), 200);
      }, timeout);
    });
  });
</script>
@stack('scripts')
</body>
</html>

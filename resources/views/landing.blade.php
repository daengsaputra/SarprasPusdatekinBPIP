  @extends('layouts.landing')

  @php($title = 'SARPRAS PUSDATEKIN • Sarana Prasarana BPIP')

  @push('styles')
  <style>
    .marquee-wrapper {
      position: relative;
      overflow: hidden;
      background: var(--surface-3);
      border: 1px solid var(--border-soft);
      border-radius: 999px;
      padding: .75rem 0;
      box-shadow: 0 12px 25px rgba(2, 6, 23, 0.35);
    }
    .marquee-track {
      display: inline-flex;
      white-space: nowrap;
      gap: 3rem;
      animation: marquee-slide 20s linear infinite;
      padding-left: 100%;
    }
    .marquee-track span {
      font-weight: 600;
      letter-spacing: .03em;
      color: var(--text-secondary);
    }
    @keyframes marquee-slide {
      0% { transform: translateX(0); }
      100% { transform: translateX(-50%); }
    }
  </style>
  @endpush
  @push('styles')
  <style>
    .hero-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 2.5rem;
      align-items: center;
    }
    .hero-chip {
      display: inline-flex;
      align-items: center;
      gap: .75rem;
      background: rgba(59, 130, 246, 0.18);
      padding: .6rem 1.2rem;
      border-radius: 999px;
      color: rgba(191, 219, 254, 0.95);
      font-weight: 600;
      letter-spacing: .12em;
      text-transform: uppercase;
      font-size: .75rem;
    }
    .hero-chip svg {
      width: 20px;
      height: 20px;
      color: var(--brand-cyan);
    }
    .hero-heading {
      font-size: clamp(2.4rem, 5vw, 3.4rem);
      font-weight: 700;
      color: var(--text-primary);
      letter-spacing: .02em;
    }
    .hero-subtext {
      color: var(--text-secondary);
      max-width: 520px;
      font-size: 1.05rem;
    }
    .hero-image {
      position: relative;
      border-radius: 28px;
      overflow: hidden;
      box-shadow: 0 25px 70px rgba(15, 23, 42, 0.55);
      border: 1px solid rgba(59, 130, 246, 0.25);
    }
  
    .hero-image::after {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(180deg, rgba(15, 23, 42, 0.05), rgba(2, 6, 23, 0.55));
      pointer-events: none;
    }

    .metric-card {
      background: var(--surface-2);
      border: 1px solid var(--border-soft);
      border-radius: 18px;
      padding: 1.6rem;
      box-shadow: 0 18px 40px rgba(2, 6, 23, 0.45);
    }
    .metric-card .label {
      text-transform: uppercase;
      letter-spacing: .18em;
      font-size: .72rem;
      color: var(--text-muted);
    }
    .metric-card p {
      color: var(--text-secondary);
    }
    .metric-value {
      color: var(--text-primary);
      font-size: clamp(2rem, 4vw, 2.6rem);
      font-weight: 700;
      margin-bottom: .35rem;
    }
    .metric-value.text-available { color: var(--brand-cyan); }
    .metric-value.text-used { color: #fbbf24; }
      .list-scroll {
      max-height: 320px;
      overflow-y: auto;
      padding-right: .35rem;
    }
    .list-scroll::-webkit-scrollbar { width: 6px; }
    .list-scroll::-webkit-scrollbar-thumb {
      background: rgba(59, 130, 246, 0.35);
      border-radius: 8px;
    }
    .asset-thumb {
      width: 52px;
      height: 52px;
      border-radius: 14px;
      border: 1px solid rgba(148, 163, 184, 0.2);
      background: rgba(15, 23, 42, 0.9);
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      color: var(--brand-cyan);
      font-weight: 700;
      letter-spacing: .04em;
      flex-shrink: 0;
    }
      .asset-thumb img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .asset-divider {
      border-color: rgba(148, 163, 184, 0.08);
    }
    .feature-card {
      background: rgba(15, 23, 42, 0.72);
      border: 1px solid rgba(148, 163, 184, 0.12);
      border-radius: 18px;
      padding: 1.6rem;
      height: 100%;
      transition: transform .2s ease, box-shadow .2s ease;
    }
    .feature-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 22px 40px rgba(2, 6, 23, 0.6);
    }
  
    .feature-title {
      color: var(--text-primary);
    }
    .feature-desc {
      color: var(--text-secondary);
    }
    .feature-card svg {
      width: 36px;
      height: 36px;
      color: var(--brand-cyan);
      margin-bottom: 1rem;
    }
    .asset-name .fw-semibold,
    .loan-asset-name {
      color: var(--text-primary);
    }
    .btn-landing-fill {
      background: var(--brand-cyan);
      color: var(--brand-ink);
      border-color: var(--brand-cyan);
    }
    .btn-landing-fill:hover {
      background: #0ea5e9;
      border-color: #0ea5e9;
      color: var(--brand-ink);
    }
    .btn-landing-outline {
      color: var(--text-primary);
      border-color: var(--border-soft);
    }
    .btn-landing-outline:hover {
      background: var(--surface-3);
      border-color: rgba(148, 163, 184, 0.25);
      color: var(--text-primary);
    }
  </style>
  @endpush

  @section('content')
  <div class="hero-grid">
    <div>
      <span class="hero-chip">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M4 5a1 1 0 0 1 1-1h4V2h6v2h4a1 1 0 0 1 1 1v3h-2V6H6v3H4zM4 9h2v2H4zm14 0h2v2h-2z"/><path d="M5 11h14a1 1 0 0 1 1 1v7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-7a1 1 0 0 1 1-1zm6 6v2h2v-2zm-4 0v2h2v-2zm8 0v2h2v-2z"/></svg>
        SARPRAS PUSDATEKIN
      </span>
      <h1 class="hero-heading mt-3">SARPRAS PUSDATEKIN BPIP</h1>
      <p class="hero-subtext mt-3">
        Sarana Prasarana Pusdatekin BPIP tersaji lengkap mulai dari perangkat TIK, dokumentasi, hingga perlengkapan operasional.
        Pantau ketersediaan, jadwalkan peminjaman, dan dukung setiap kegiatan dengan alat yang selalu siap.
      </p>
      <div class="d-flex flex-wrap gap-3 mt-4">
        <a class="btn btn-lg btn-landing-fill px-4" href="{{ route('assets.loanable') }}">Lihat Koleksi Sarpras</a>
        <a class="btn btn-lg btn-landing-outline px-4" href="{{ route('login') }}">Masuk Dashboard</a>
      </div>
    </div>
    <div class="hero-image">
      {{-- Fallback: Tampilkan ikon jika gambar utama tidak ada --}}
      <div class="d-flex align-items-center justify-content-center" style="min-height: 320px; background: rgba(15, 23, 42, 0.5);">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 120px; height: 120px; color: rgba(59, 130, 246, 0.4);">
          <path d="M4 5a1 1 0 0 1 1-1h4V2h6v2h4a1 1 0 0 1 1 1v3h-2V6H6v3H4zM4 9h2v2H4zm14 0h2v2h-2z"/><path d="M5 11h14a1 1 0 0 1 1 1v7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-7a1 1 0 0 1 1-1zm6 6v2h2v-2zm-4 0v2h2v-2zm8 0v2h2v-2z"/>
        </svg>
      </div>
    </div>
  </div>

  @php($summaryData = $summary ?? [])

  <div class="row g-3 mt-5" id="stok">
    <div class="col-md-4">
      <div class="metric-card h-100">
        <div class="label mb-2">Total Sarpras</div>
        <div class="metric-value">{{ number_format(data_get($summaryData, 'total', 0)) }}</div>
        <p class="mb-0 text-muted">Unit sarana prasarana terdaftar.</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="metric-card h-100">
        <div class="label mb-2">Siap Dipinjam</div>
        <div class="metric-value text-available">{{ number_format(data_get($summaryData, 'available', 0)) }}</div>
        <p class="mb-0 text-muted">Perangkat yang tersedia saat ini.</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="metric-card h-100">
        <div class="label mb-2">Sedang Digunakan</div>
        <div class="metric-value text-used">{{ number_format(data_get($summaryData, 'in_use', max(data_get($summaryData, 'total', 0) - data_get($summaryData, 'available', 0), 0))) }}</div>
        <p class="mb-0 text-muted">Unit dalam status peminjaman aktif.</p>
      </div>
    </div>
  </div>

  <div class="row g-4 mt-3">
    <div class="col-lg-6">
      <div class="glass-panel h-100">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="mb-0">Sarpras Tersedia</h5>
          <span class="badge rounded-pill text-bg-primary">{{ number_format(data_get($summaryData, 'available', 0)) }} unit</span>
        </div>
        <div class="list-scroll">
          @forelse(($availableAssets ?? []) as $asset)
            <div class="d-flex justify-content-between align-items-center py-2">
              <div class="d-flex align-items-center gap-3 flex-grow-1 min-w-0">
                <div class="asset-thumb">
                  @if($asset->photo)
                    <img src="{{ asset('storage/'.$asset->photo) }}" alt="Foto {{ $asset->name }}" loading="lazy">
                  @else
                    {{ mb_strtoupper(mb_substr($asset->name ?? '?', 0, 1)) }}
                  @endif
                </div>
                <div class="min-w-0 asset-name">
                  <div class="fw-semibold text-truncate">{{ $asset->name }}</div>
                  <div class="text-muted small text-truncate">{{ $asset->category ?? 'Kategori belum diatur' }}</div>
                </div>
              </div>
              <span class="badge rounded-pill text-bg-success px-3">{{ $asset->quantity_available }} unit</span>
            </div>
            @unless($loop->last)
              <hr class="asset-divider">
            @endunless
          @empty
            <p class="feature-desc mb-0">Belum ada sarpras siap pinjam untuk ditampilkan.</p>
          @endforelse
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="glass-panel h-100">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="mb-0">Peminjaman Aktif</h5>
          <span class="badge rounded-pill text-bg-warning">{{ number_format(data_get($summaryData, 'in_use', 0)) }} unit</span>
        </div>
        <div class="list-scroll">
          @forelse(($activeLoans ?? []) as $loan)
            <div class="d-flex justify-content-between align-items-start py-2">
              <div class="pe-3">
                <div class="fw-semibold loan-asset-name">{{ $loan->asset->name ?? 'Sarana tidak ditemukan' }}</div>
                <div class="text-muted small">
                  Peminjam: {{ $loan->borrower_name }}
                  @if($loan->unit)
                    &middot; {{ $loan->unit }}
                  @endif
                </div>
                <div class="text-muted small">
                  Pinjam: {{ optional($loan->loan_date)->format('d M Y') ?? '-' }}
                  @if($loan->return_date_planned)
                    &middot; Kembali: {{ optional($loan->return_date_planned)->format('d M Y') }}
                  @endif
                </div>
              </div>
              <span class="badge rounded-pill text-bg-warning px-3">{{ $loan->quantity }} unit</span>
            </div>
            @unless($loop->last)
              <hr class="asset-divider">
            @endunless
          @empty
            <p class="feature-desc mb-0">Belum ada catatan peminjaman aktif.</p>
          @endforelse
        </div>
      </div>
    </div>
  </div>

  <div id="fitur" class="row g-3 mt-5">
    <div class="col-md-4">
      <div class="feature-card">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M3 7h18M5 11h14M7 15h10M9 19h6"/></svg>
        <h5 class="feature-title">Inventaris Sarpras Terpusat</h5>
        <p class="feature-desc mb-0">Laptop, proyektor, kamera, dan perlengkapan operasional tercatat rapi dalam satu sistem.</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="feature-card">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 8v4l3 3m6 1A9 9 0 113 12a9 9 0 0118 0z"/></svg>
        <h5 class="feature-title">Peminjaman Transparan</h5>
        <p class="feature-desc mb-0">Alur peminjaman dan pengembalian yang jelas disertai pengingat jadwal otomatis.</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="feature-card">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M3 7h18M5 11h14M7 15h10M9 19h6"/></svg>
        <h5 class="feature-title">Analitik Real-time</h5>
        <p class="feature-desc mb-0">Laporan stok dan utilisasi sarpras membantu pengambilan keputusan yang cepat dan akurat.</p>
      </div>
    </div>
  </div>
  <div class="marquee-wrapper mt-5" aria-live="polite">
    <div class="marquee-track">
      <span>Informasi Sarpras: Laptop siap pakai {{ number_format(data_get($summaryData, 'available', 0)) }} unit • Kamera dokumentasi tersedia • Ruang rapat portable siap digunakan • Hubungi admin SARPRAS PUSDATEKIN untuk dukungan.</span>
      <span>Informasi Sarpras: Laptop siap pakai {{ number_format(data_get($summaryData, 'available', 0)) }} unit • Kamera dokumentasi tersedia • Ruang rapat portable siap digunakan • Hubungi admin SARPRAS PUSDATEKIN untuk dukungan.</span>
    </div>
  </div>

  @endsection
@php($title = 'Dashboard')
@extends('layouts.app')

@push('styles')
<style>
  /* Define CSS variables for light mode */
  :root {
    --bg-indigo: linear-gradient(135deg,#4338ca,#312e81);
    --bg-cyan: linear-gradient(135deg,#0ea5e9,#0284c7);
    --bg-emerald: linear-gradient(135deg,#10b981,#059669);
    --bg-amber: linear-gradient(135deg,#f59e0b,#d97706);
    --bg-slate: linear-gradient(135deg,#64748b,#475569);
    --text-color: #fff; /* Default text color for light mode */
  }

  /* Dark mode using prefers-color-scheme */
  @media (prefers-color-scheme: dark) {
    :root {
      --bg-indigo: linear-gradient(135deg,#312e81,#4338ca);
      --bg-cyan: linear-gradient(135deg,#0284c7,#0ea5e9);
      --bg-emerald: linear-gradient(135deg,#059669,#10b981);
      --bg-amber: linear-gradient(135deg,#d97706,#f59e0b);
      --bg-slate: linear-gradient(135deg,#475569,#64748b);
      --text-color: #fff; /* Default text color for dark mode */
    }
  }

  .metric-card { position: relative; overflow: hidden; border: 0; color: #fff; }
  .metric-card .icon-bg { position: absolute; right: 8px; bottom: 8px; opacity: .15; width: 64px; height: 64px; }
  .metric-card .value { font-size: 2.8rem; font-weight: 800; line-height: 1; }
  .metric-card .label { font-weight: 600; letter-spacing: .02em; display:flex; align-items:center; gap:.4rem; }
  .metric-card .label .mini-icon{ width:18px; height:18px; opacity:1 }

  .bg-indigo { background: var(--bg-indigo); color:var(--text-color); }
  .bg-cyan { background: var(--bg-cyan); color:var(--text-color); }
  .bg-emerald { background: var(--bg-emerald); color:var(--text-color); }
  .bg-amber { background: var(--bg-amber); color:var(--text-color); }
  .bg-slate { background: var(--bg-slate); color:var(--text-color); }

  .metric-link { text-decoration: none; display: block; }
  .metric-card:hover { filter: brightness(1.03); transform: translateY(-1px); transition: .15s ease; }
  .quick-actions .btn { font-weight: 700; }
  .ico { width:28px; height:28px; margin-right:8px; opacity:.9 }

  @media (prefers-color-scheme: dark) {
    .modal-content {
      color: var(--bs-body-color);
    }
  }

</style>
@endpush

@section('content')
<div class="row g-3 align-items-stretch">
  <div class="col-md-3 col-lg-3 col-xl-2">
    <a class="metric-link" href="{{ route('assets.loanable') }}">
      <div class="card metric-card bg-cyan">
        <div class="card-body">
          <div class="value">{{ $counts['assets_loanable'] }}</div>
          <div class="label"><svg class="mini-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M3 7l9-4 9 4-9 4-9-4zm0 8l9 4 9-4-9-4-9 4z"/></svg><span>Barang Peminjaman</span></div>
          <svg class="icon-bg" viewBox="0 0 24 24" fill="currentColor">
            <path d="M3 7l9-4 9 4-9 4-9-4zm0 4l9 4 9-4v6l-9 4-9-4v-6z"/>
          </svg>
        </div>
      </div>
    </a>
  </div>
  <div class="col-md-3 col-lg-3 col-xl-2">
    <a class="metric-link" href="{{ route('assets.index') }}">
      <div class="card metric-card bg-indigo">
        <div class="card-body">
          <div class="value">{{ $counts['assets'] }}</div>
          <div class="label"><svg class="mini-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M20.59 7.41L12 16 7.41 11.41 2 16.83 3.41 18.24 7.41 14.24 12 18.83 22 8.83z"/></svg><span>Barang Aset</span></div>
          <svg class="icon-bg" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 3l9 5v8l-9 5-9-5V8l9-5zm0 2.18L5 9v6l7 3.82L19 15V9l-7-3.82z"/>
          </svg>
        </div>
      </div>
    </a>
  </div>
  <div class="col-md-3 col-lg-3 col-xl-2">
    <a class="metric-link" href="{{ route('users.index') }}">
      <div class="card metric-card bg-emerald">
        <div class="card-body">
          <div class="value">{{ $counts['users'] }}</div>
          <div class="label"><svg class="mini-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M16 11c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 3-1.34 3-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg><span>Anggota</span></div>
          <svg class="icon-bg" viewBox="0 0 24 24" fill="currentColor">
            <path d="M16 11c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3zM8 11c1.66 0 3-1.34 3-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3z"/>
          </svg>
        </div>
      </div>
    </a>
  </div>
  <div class="col-md-3 col-lg-3 col-xl-2">
    <a class="metric-link" href="{{ route('loans.index') }}">
      <div class="card metric-card bg-amber">
        <div class="card-body">
          <div class="value">{{ $counts['loans_active'] }}</div>
          <div class="label">
            <svg class="mini-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M3 6h13l3 5h2v7h-2a3 3 0 11-6 0H9a3 3 0 11-6 0H1V6h2z"/></svg>
            <span>Sedang Dipinjam</span>
          </div>
          <svg class="icon-bg" viewBox="0 0 24 24" fill="currentColor">
            <path d="M3 6h13l3 5h2v7h-2a3 3 0 11-6 0H9a3 3 0 11-6 0H1V6h2zm3 12a1 1 0 100 2 1 1 0 000-2zm10 0a1 1 0 100 2 1 1 0 000-2z"/>
          </svg>
        </div>
      </div>
    </a>
  </div>
  <div class="col-md-3 col-lg-3 col-xl-2">
    <a class="metric-link" href="{{ route('reports.returns') }}">
      <div class="card metric-card bg-slate">
        <div class="card-body">
          <div class="value">{{ $counts['loans_returned'] }}</div>
          <div class="label">
            <svg class="mini-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M12 22a10 10 0 100-20 10 10 0 000 20zm-1-6l7-7-1.4-1.4L11 13.2 8.4 10.6 7 12l4 4z"/></svg>
            <span>Sudah Dikembalikan</span>
          </div>
          <svg class="icon-bg" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 22a10 10 0 100-20 10 10 0 000 20zm-1-6l7-7-1.4-1.4L11 13.2 8.4 10.6 7 12l4 4z"/>
          </svg>
        </div>
      </div>
    </a>
  </div>
</div>

<div class="mt-4 quick-actions d-flex flex-wrap gap-2">
  <a href="{{ route('assets.index') }}" class="btn btn-outline-primary">
    <svg class="ico" viewBox="0 0 24 24" fill="currentColor"><path d="M3 7l9-4 9 4-9 4-9-4zm0 8l9 4 9-4-9-4-9 4z"/></svg>
    Kelola Aset
  </a>
  <a href="{{ route('loans.index') }}" class="btn btn-outline-success">
    <svg class="ico" viewBox="0 0 24 24" fill="currentColor"><path d="M7 18a2 2 0 11.001 3.999A2 2 0 017 18zm10 0a2 2 0 11.001 3.999A2 2 0 0117 18zM3 6h13l3 5H7l-4-5z"/></svg>
    Kelola Peminjaman
  </a>
  <a href="{{ route('loans.create') }}" class="btn btn-success">
    <svg class="ico" viewBox="0 0 24 24" fill="currentColor"><path d="M11 11V6h2v5h5v2h-5v5h-2v-5H6v-2h5z"/></svg>
    + Pinjam Aset
  </a>
  <a href="{{ route('assets.create') }}" class="btn btn-primary">
    <svg class="ico" viewBox="0 0 24 24" fill="currentColor"><path d="M11 11V6h2v5h5v2h-5v5h-2v-5H6v-2h5z"/></svg>
    + Tambah Aset
  </a>
  </div>

  <div class="row mt-4">
    <div class="col-lg-6 col-md-8">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title mb-0">Grafik Peminjaman per Unit Kerja</h5>
            <div class="d-flex align-items-center gap-2">
              <div class="btn-group btn-group-sm" role="group" aria-label="Mode grafik">
                <button class="btn btn-outline-primary active" id="btnModeQty">Jumlah Barang</button>
                <button class="btn btn-outline-primary" id="btnModeTx">Jumlah Transaksi</button>
              </div>
              <div class="form-check form-switch ms-2">
                <input class="form-check-input" type="checkbox" role="switch" id="togglePct">
                <label class="form-check-label small" for="togglePct">Persentase</label>
              </div>
            </div>
          </div>
          <div style="height:240px">
            <canvas id="chartLoansByUnit"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mt-4">
    <div class="col-lg-6">
      <div class="card h-100">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">Anggota Terdaftar</h6>
            <a href="{{ route('users.index') }}" class="small">Lihat semua</a>
          </div>
          <div class="row row-cols-2 g-3">
            @foreach($latestUsers as $u)
              <div class="col">
                <div class="d-flex align-items-center gap-2">
                  <div class="rounded-circle bg-secondary" style="width:36px;height:36px"></div>
                  <div>
                    <div class="fw-semibold small">{{ $u->name }}</div>
                    <div class="text-muted small">{{ $u->email }}</div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
  const ctx = document.getElementById('chartLoansByUnit');
  const labels = @json($chart['labels'] ?? []);
  const dataQty = @json($chart['qty'] ?? []);
  const dataTx = @json($chart['tx'] ?? []);
  if (ctx && labels.length) {
    const sum = arr => arr.reduce((a,b)=>a+(+b||0),0);
    const toPct = arr => { const s = sum(arr)||1; return arr.map(v=> Math.round((v*1000)/s)/10 ); };
    const dataQtyPct = toPct(dataQty);
    const dataTxPct = toPct(dataTx);
    const chart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels,
        datasets: [{
          label: 'Jumlah barang dipinjam',
          data: dataQty,
          backgroundColor: ['#2563eb','#10b981','#f59e0b','#64748b','#0ea5e9','#a78bfa'],
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { 
          y: { beginAtZero: true, ticks: { precision:0 } },
          x: { ticks: { autoSkip: false, maxRotation: 0, minRotation: 0 } }
        }
      }
    });
    const btnQty = document.getElementById('btnModeQty');
    const btnTx = document.getElementById('btnModeTx');
    const togglePct = document.getElementById('togglePct');
    const setMode = (mode) => {
      const usePct = togglePct.checked;
      if (mode === 'tx') {
        chart.data.datasets[0].data = usePct ? dataTxPct : dataTx;
        chart.data.datasets[0].label = usePct ? 'Persentase transaksi (%)' : 'Jumlah transaksi';
        btnTx.classList.add('active'); btnQty.classList.remove('active');
      } else {
        chart.data.datasets[0].data = usePct ? dataQtyPct : dataQty;
        chart.data.datasets[0].label = usePct ? 'Persentase barang (%)' : 'Jumlah barang dipinjam';
        btnQty.classList.add('active'); btnTx.classList.remove('active');
      }
      // axis format
      chart.options.scales.y.max = usePct ? 100 : undefined;
      chart.options.scales.y.ticks = usePct ? { callback: v => v+'%', stepSize: 20 } : { precision:0 };
      chart.update();
    }
    btnModeQty?.addEventListener('click', (e)=>{ e.preventDefault(); setMode('qty'); });
    btnModeTx?.addEventListener('click', (e)=>{ e.preventDefault(); setMode('tx'); });
    togglePct?.addEventListener('change', ()=>{
      const mode = btnTx.classList.contains('active') ? 'tx' : 'qty';
      setMode(mode);
    });
  }
</script>
@if(session('show_loans_popup'))
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var modalEl = document.getElementById('modalActiveLoans');
    if (modalEl) {
      var m = new bootstrap.Modal(modalEl);
      m.show();
    }

    // Filtering and simple pagination for loans table
    var tbody = document.querySelector('#modalActiveLoans tbody');
    var cbOverdueOnly = document.getElementById('cbOverdueOnly');
    var btnShowAll = document.getElementById('btnShowAllLoans');
    var info = document.getElementById('loansShownInfo');
    if (tbody) {
      var DEFAULT_LIMIT = 15;
      var showAll = false;
      var applyFilter = function() {
        var rows = Array.from(tbody.querySelectorAll('tr'));
        var overdueOnly = cbOverdueOnly && cbOverdueOnly.checked;
        var shown = 0, total = 0;
        rows.forEach(function(r){
          var isOver = r.getAttribute('data-overdue') === '1';
          var pass = !overdueOnly || isOver;
          if (pass) {
            total++;
            if (!showAll && shown >= DEFAULT_LIMIT) { r.style.display = 'none'; }
            else { r.style.display = ''; shown++; }
          } else {
            r.style.display = 'none';
          }
        });
        if (info) info.textContent = (showAll ? 'Menampilkan semua' : ('Menampilkan ' + shown + ' dari ' + total));
        if (btnShowAll) btnShowAll.style.display = (showAll || total <= DEFAULT_LIMIT) ? 'none' : '';
      };
      cbOverdueOnly && cbOverdueOnly.addEventListener('change', function(){ showAll = false; applyFilter(); });
      btnShowAll && btnShowAll.addEventListener('click', function(e){ e.preventDefault(); showAll = true; applyFilter(); });
      applyFilter();
    }
  });
</script>
@endif
@endpush

@if(($activeLoans ?? collect())->count())
  <div class="modal fade" id="modalActiveLoans" tabindex="-1" aria-labelledby="modalActiveLoansLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalActiveLoansLabel">Pengingat: Aset Sedang Dipinjam</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-0">
          <div class="p-2 d-flex align-items-center justify-content-between border-bottom" id="loansToolbar">
            <div class="form-check form-check-sm m-0">
              <input class="form-check-input" type="checkbox" id="cbOverdueOnly">
              <label class="form-check-label small" for="cbOverdueOnly">Tampilkan yang terlambat saja</label>
            </div>
            <div class="d-flex align-items-center gap-2 small">
              <span id="loansShownInfo"></span>
              <button class="btn btn-sm btn-outline-secondary" id="btnShowAllLoans">Tampilkan semua</button>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-sm mb-0 align-middle">
              <thead class="table-secondary">
                <tr>
                  <th>Aset</th>
                  <th>Peminjam</th>
                  <th>Unit</th>
                  <th class="text-center">Qty</th>
                  <th>Pinjam</th>
                  <th>Rencana Kembali</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
              @foreach($activeLoans as $ln)
                @php($over = $ln->return_date_planned && now()->isAfter($ln->return_date_planned))
                <tr data-overdue="{{ $over ? 1 : 0 }}">
                  <td>{{ $ln->asset->name ?? '-' }}</td>
                  <td>{{ $ln->borrower_name }}</td>
                  <td class="text-nowrap">{{ $ln->unit ?? '-' }}</td>
                  <td class="text-center">{{ (int) $ln->quantity }}</td>
                  <td class="text-nowrap">{{ optional($ln->loan_date)->format('Y-m-d') }}</td>
                  <td class="text-nowrap">{{ optional($ln->return_date_planned)->format('Y-m-d') }}</td>
                  <td>
                    @if($over)
                      <span class="badge bg-danger">Terlambat</span>
                    @else
                      <span class="badge bg-warning text-dark">Dipinjam</span>
                    @endif
                  </td>
                </tr>
              @endforeach
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <a href="{{ route('loans.index') }}" class="btn btn-primary">Kelola Peminjaman</a>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>
@endif

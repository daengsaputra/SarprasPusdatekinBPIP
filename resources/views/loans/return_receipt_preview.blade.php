@php($title = 'Bukti Pengembalian')
@extends('layouts.app')

@push('styles')
<style>
  body { background: #f8fafc; }
  .receipt-wrapper { max-width: 780px; margin: 0 auto; }
  .receipt-wrapper .card { border-radius: 14px; border: 1px solid #e2e8f0; box-shadow: 0 12px 30px rgba(15,23,42,0.08); }
  .receipt-wrapper .card + .card { margin-top: 12px; }
  .meta-grid label { text-transform: uppercase; font-size: .65rem; letter-spacing: .05em; color: #64748b; }
  .meta-grid .value { font-weight: 600; font-size: .95rem; color: #0f172a; }
  .signature-box {
    min-height: 64px;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    font-weight: 600;
    font-size: 1rem;
    border-top: 1px dashed #94a3b8;
    padding-top: 0.75rem;
  }
  @media print {
    body { background: #fff; margin: 0; }
    .receipt-wrapper { max-width: 100%; padding: 0 12px; }
    .receipt-wrapper .card { box-shadow: none; border: 1px solid #d0d7e2; }
    .receipt-wrapper .btn { display: none !important; }
  }
</style>
@endpush

@section('content')
<div class="receipt-wrapper d-flex flex-column gap-3">
  <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
    <div>
      <h1 class="h4 mb-1">Bukti Pengembalian Barang</h1>
      <div class="text-muted">ID Peminjaman: <strong>{{ $loan->id }}</strong></div>
    </div>
    <div class="d-flex gap-2">
      <a class="btn btn-outline-primary" target="_blank" href="{{ route('loans.return.receipt', ['loan' => $loan, 'download' => 1]) }}">Download PDF</a>
      <button type="button" class="btn btn-primary" onclick="window.print()">Cetak Halaman</button>
    </div>
  </div>

  <div class="card">
    <div class="card-body">
      <div class="row g-3 meta-grid">
        <div class="col-md-6">
          <div class="text-uppercase text-muted small">Nama Peminjam</div>
          <div class="value fw-semibold">{{ $loan->borrower_name }}</div>
        </div>
        <div class="col-md-6">
          <div class="text-uppercase text-muted small">Unit Kerja</div>
          <div class="value fw-semibold">{{ $loan->unit }}</div>
        </div>
        @if($loan->borrower_contact)
        <div class="col-md-6">
          <div class="text-uppercase text-muted small">Kontak</div>
          <div class="value">{{ $loan->borrower_contact }}</div>
        </div>
        @endif
        <div class="col-md-3">
          <div class="text-uppercase text-muted small">Tanggal Pinjam</div>
          <div class="value">{{ optional($loan->loan_date)->format('Y-m-d') }}</div>
        </div>
        <div class="col-md-3">
          <div class="text-uppercase text-muted small">Tanggal Kembali</div>
          <div class="value">{{ optional($loan->return_date_actual)->format('Y-m-d') }}</div>
        </div>
        <div class="col-md-3">
          <div class="text-uppercase text-muted small">Petugas</div>
          <div class="value">{{ $officer }}</div>
        </div>
        <div class="col-md-3">
          <div class="text-uppercase text-muted small">Dicetak</div>
          <div class="value">{{ $printed_at->format('Y-m-d H:i') }}</div>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped table-borderless mb-0">
          <thead class="table-light">
            <tr>
              <th style="width:160px">Kode Barang</th>
              <th>Nama Barang</th>
              <th class="text-center" style="width:120px">Jumlah</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>{{ $loan->asset->code }}</td>
              <td>{{ $loan->asset->name }}</td>
              <td class="text-center">{{ $loan->quantity }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="row mt-3 g-4">
    <div class="col-md-6 text-center">
      <div class="text-muted">Peminjam</div>
      <div class="signature-box">{{ $loan->borrower_name }}</div>
    </div>
    <div class="col-md-6 text-center">
      <div class="text-muted">Petugas</div>
      <div class="signature-box">{{ $officer }}</div>
    </div>
  </div>
</div>
@endsection







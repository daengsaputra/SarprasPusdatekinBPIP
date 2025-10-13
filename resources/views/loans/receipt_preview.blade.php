@php($title = 'Bukti Peminjaman')
@extends('layouts.app')

@push('styles')
<style>
  @media print {
    nav.navbar, aside, header, footer { display: none !important; }
    body { background: #fff; }
    .card { box-shadow: none !important; border: none !important; }
  }
  .signature-box {
    min-height: 64px;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    font-weight: 600;
    font-size: 1rem;
    border-top: 1px dashed #6c757d;
    padding-top: 0.75rem;
  }
</style>
@endpush

@section('content')
<div class="d-flex flex-column gap-3">
  <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
    <div>
      <h1 class="h4 mb-1">Bukti Peminjaman Barang</h1>
      <div class="text-muted">Kode Pinjam: <strong>{{ $batch }}</strong></div>
    </div>
    <div class="d-flex gap-2">
      <a class="btn btn-outline-primary" target="_blank" href="{{ route('loans.receipt', ['batch' => $batch, 'download' => 1]) }}">Download PDF</a>
      <button type="button" class="btn btn-primary" onclick="window.print()">Cetak Halaman</button>
    </div>
  </div>

  <div class="card">
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-6">
          <div class="text-uppercase text-muted small">Nama Peminjam</div>
          <div class="fw-semibold">{{ $borrower }}</div>
        </div>
        <div class="col-md-6">
          <div class="text-uppercase text-muted small">Unit Kerja</div>
          <div class="fw-semibold">{{ $unit }}</div>
        </div>
        @if($contact)
        <div class="col-md-6">
          <div class="text-uppercase text-muted small">Kontak</div>
          <div>{{ $contact }}</div>
        </div>
        @endif
        <div class="col-md-3">
          <div class="text-uppercase text-muted small">Tanggal Pinjam</div>
          <div>{{ \Illuminate\Support\Carbon::parse($loan_date)->format('Y-m-d') }}</div>
        </div>
        <div class="col-md-3">
          <div class="text-uppercase text-muted small">Estimasi Kembali</div>
          <div>{{ $return_plan ? \Illuminate\Support\Carbon::parse($return_plan)->format('Y-m-d') : '-' }}</div>
        </div>
        <div class="col-md-3">
          <div class="text-uppercase text-muted small">Petugas</div>
          <div>{{ $officer }}</div>
        </div>
        <div class="col-md-3">
          <div class="text-uppercase text-muted small">Dicetak</div>
          <div>{{ $printed_at->format('Y-m-d H:i') }}</div>
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
              <th class="text-center" style="width:60px">No</th>
              <th style="width:140px">Kode Barang</th>
              <th>Nama Barang</th>
              <th class="text-center" style="width:100px">Jumlah</th>
            </tr>
          </thead>
          <tbody>
            @foreach($items as $index => $row)
              <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $row->asset->code }}</td>
                <td>{{ $row->asset->name }}</td>
                <td class="text-center">{{ $row->quantity }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="row mt-3 g-4">
    <div class="col-md-6 text-center">
      <div class="text-muted">Peminjam</div>
      <div class="signature-box">{{ $borrower }}</div>
    </div>
    <div class="col-md-6 text-center">
      <div class="text-muted">Petugas</div>
      <div class="signature-box">{{ $officer }}</div>
    </div>
  </div>
</div>
@endsection


@php($title = 'Daftar Peminjaman')
@extends('layouts.app')

@section('content')
@if(session('receipt_batch'))
  @php($batch = session('receipt_batch'))
  <div class="alert alert-success d-flex justify-content-between align-items-center">
    <div>
      Peminjaman berhasil. Bukti peminjaman siap dicetak.
    </div>
    <div class="d-flex gap-2">
      <a class="btn btn-sm btn-primary" target="_blank" href="{{ route('loans.receipt', ['batch' => $batch, 'preview' => 1]) }}">Cetak Bukti</a>
      <button type="button" class="btn btn-sm btn-outline-secondary" onclick="this.closest('.alert').remove()">Tutup</button>
    </div>
  </div>
@endif
@if(session('return_receipt_id'))
  @php($rid = session('return_receipt_id'))
  <div class="alert alert-info d-flex justify-content-between align-items-center">
    <div>
      Pengembalian berhasil. Bukti pengembalian siap dicetak.
    </div>
    <div class="d-flex gap-2">
      <a class="btn btn-sm btn-primary" target="_blank" href="{{ route('loans.return.receipt', ['loan' => $rid, 'preview' => 1]) }}">Cetak Bukti</a>
      <button type="button" class="btn btn-sm btn-outline-secondary" onclick="this.closest('.alert').remove()">Tutup</button>
    </div>
  </div>
@endif

<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h4">Daftar Peminjaman</h1>
  <a href="{{ route('loans.create') }}" class="btn btn-success">+ Pinjam Aset</a>
  </div>

<form method="GET" class="row g-2 align-items-end mb-3">
  <div class="col-md-3">
    <label class="form-label">Cari</label>
    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="aset/peminjam">
  </div>
  <div class="col-md-2">
    <label class="form-label">Status</label>
    <select name="status" class="form-select">
      <option value="">Semua</option>
      <option value="borrowed" {{ request('status')==='borrowed'?'selected':'' }}>Sedang dipinjam</option>
      <option value="returned" {{ request('status')==='returned'?'selected':'' }}>Sudah dikembalikan</option>
    </select>
  </div>
  <div class="col-md-3">
    <label class="form-label">Unit Kerja</label>
    <select name="unit" class="form-select">
      <option value="">Semua</option>
      @foreach(($units ?? config('bpip.units')) as $u)
        <option value="{{ $u }}" {{ request('unit')===$u?'selected':'' }}>{{ $u }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-2">
    <label class="form-label">Dari</label>
    <input type="date" name="from" value="{{ request('from') }}" class="form-control">
  </div>
  <div class="col-md-2">
    <label class="form-label">Sampai</label>
    <input type="date" name="to" value="{{ request('to') }}" class="form-control">
  </div>
  <div class="col-md-2">
    <button class="btn btn-outline-primary w-100" type="submit">Terapkan</button>
  </div>
  <div class="col-md-2">
    <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
  </div>
</form>

<div class="table-responsive">
<table class="table table-striped align-middle">
  <thead>
    <tr>
      @php($sort=request('sort'))
      @php($dir=request('dir','desc'))
      @php($link=function($key,$label) use($sort,$dir){
        $next = ($sort===$key && $dir==='asc') ? 'desc' : 'asc';
        $q = array_merge(request()->all(), ['sort'=>$key,'dir'=>$next]);
        $arrow = $sort===$key ? ($dir==='asc'?'&uarr;':'&darr;') : '&bull;';
        return '<a href="'.route('loans.index',$q).'" class="text-decoration-none">'.$label.' <span class="text-muted">'.$arrow.'</span></a>';
      })
      <th>{!! $link('loan_date','Tanggal') !!}</th>
      <th>{!! $link('asset','Aset') !!}</th>
      <th>{!! $link('borrower_name','Peminjam') !!}</th>
      <th>{!! $link('quantity','Jumlah') !!}</th>
      <th>{!! $link('status','Status') !!}</th>
      <th>{!! $link('return_date_planned','Rencana Kembali') !!}</th>
      <th>{!! $link('return_date_actual','Kembali') !!}</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    @forelse($loans as $loan)
      <tr>
        <td>{{ $loan->loan_date?->format('Y-m-d') }}</td>
        <td>{{ $loan->asset->name }}</td>
        <td>{{ $loan->borrower_name }}</td>
        <td>{{ $loan->quantity }}</td>
        <td>
          @php($statusLabel = $loan->status === 'borrowed' ? 'dipinjam' : ($loan->status === 'returned' ? 'sudah kembali' : $loan->status))
          <span class="badge {{ $loan->status==='borrowed'?'text-bg-warning':'text-bg-success' }}">{{ $statusLabel }}</span>
        </td>
        <td>{{ $loan->return_date_planned?->format('Y-m-d') ?? '-' }}</td>
        <td>{{ $loan->return_date_actual?->format('Y-m-d') ?? '-' }}</td>
        <td class="d-flex gap-2">
          @if($loan->status==='borrowed')
            <a href="{{ route('loans.return.form', $loan) }}" class="btn btn-sm btn-outline-success">Kembalikan</a>
          @endif
          @if($loan->batch_code)
            <a href="{{ route('loans.receipt', ['batch' => $loan->batch_code, 'preview' => 1]) }}" class="btn btn-sm btn-outline-secondary">Bukti</a>
  @endif
          @if($loan->status==='returned')
          <form method="POST" action="{{ route('loans.destroy', $loan) }}" onsubmit="return confirm('Hapus data peminjaman ini?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
          </form>
          <a href="{{ route('loans.return.receipt', ['loan' => $loan, 'preview' => 1]) }}" class="btn btn-sm btn-outline-secondary">Bukti Kembali</a>
          @endif
        </td>
      </tr>
    @empty
      <tr><td colspan="8" class="text-center">Belum ada data peminjaman.</td></tr>
    @endforelse
  </tbody>
</table>
</div>

{{ $loans->links() }}

@endsection

@push('scripts')
@if(session('receipt_batch'))
<script>
  // Auto open print-ready PDF in a new tab
  (function(){
    var url = @json(route('loans.receipt', ['batch' => session('receipt_batch'), 'preview' => 1]));
    // slight delay to ensure page renders before opening
    setTimeout(function(){ window.open(url, '_blank'); }, 300);
  })();
  </script>
@endif
@if(session('return_receipt_id'))
<script>
  (function(){
    var url = @json(route('loans.return.receipt', ['loan' => session('return_receipt_id'), 'preview' => 1]));
    setTimeout(function(){ window.open(url, '_blank'); }, 300);
  })();
  </script>
@endif
@endpush



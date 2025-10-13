@php($contextKind = request('kind'))
@php($forcedKind = in_array($contextKind, [\App\Models\Asset::KIND_INVENTORY, \App\Models\Asset::KIND_LOANABLE], true) ? $contextKind : null)
@php($title = $forcedKind === \App\Models\Asset::KIND_LOANABLE ? 'Import Barang Peminjaman' : ($forcedKind === \App\Models\Asset::KIND_INVENTORY ? 'Import Aset Inventaris' : 'Import Aset'))
@php($backRoute = $forcedKind === \App\Models\Asset::KIND_LOANABLE ? route('assets.loanable') : route('assets.index'))
@extends('layouts.app')

@section('content')
<h1 class="h4 mb-3">{{ $title }} dari Excel</h1>

<div class="alert alert-info">
  Gunakan template agar kolom sesuai:
  <a class="btn btn-sm btn-outline-primary" href="{{ route('assets.template') }}">Download Template</a>
  @if($forcedKind)
    <span class="ms-2 badge rounded-pill text-bg-secondary">{{ $forcedKind === \App\Models\Asset::KIND_LOANABLE ? 'Khusus barang peminjaman' : 'Khusus aset inventaris' }}</span>
  @endif
</div>

<form method="POST" action="{{ route('assets.import') }}" enctype="multipart/form-data" class="row g-3">
  @csrf
  @if($forcedKind)
    <input type="hidden" name="kind" value="{{ $forcedKind }}">
  @endif
  <div class="col-md-6">
    <label class="form-label">File Excel (.xlsx/.csv)</label>
    <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" required>
    @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-12 d-flex gap-2">
    <button class="btn btn-success" type="submit">Import</button>
    <a class="btn btn-secondary" href="{{ $backRoute }}">Kembali</a>
  </div>
</form>

<hr class="my-4">
<div>
  <h6>Catatan:</h6>
  <ul>
    <li>Kolom yang didukung: <code>code, name, category, description, quantity_total, status, kind</code>.</li>
    <li>Status gunakan <code>active</code> atau <code>inactive</code> (boleh juga "aktif"/"nonaktif").</li>
    <li>Jika kolom <code>kind</code> dikosongkan, data akan dianggap {{ $forcedKind === \App\Models\Asset::KIND_INVENTORY ? 'aset inventaris' : 'barang peminjaman' }}{{ $forcedKind ? '' : ' secara default (loanable)' }}.</li>
    <li>Jika <code>code</code> sudah ada: data akan diupdate, stok tersedia disesuaikan otomatis dengan pinjaman yang sedang berjalan.</li>
  </ul>
</div>
@endsection
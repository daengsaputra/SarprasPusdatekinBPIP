@php($contextKind = request('kind'))
@php($forcedKind = in_array($contextKind, [\App\Models\Asset::KIND_INVENTORY, \App\Models\Asset::KIND_LOANABLE], true) ? $contextKind : null)
@php($title = $forcedKind === \App\Models\Asset::KIND_LOANABLE ? 'Tambah Barang Peminjaman' : ($forcedKind === \App\Models\Asset::KIND_INVENTORY ? 'Tambah Aset Inventaris' : 'Tambah Aset'))
@php($cancelKind = $forcedKind ?? old('kind'))
@extends('layouts.app')

@section('content')
<h1 class="h4 mb-3">{{ $title }}</h1>

<form method="POST" action="{{ route('assets.store') }}" class="row g-3" enctype="multipart/form-data">
  @csrf
  <div class="col-md-4">
    <label class="form-label">Kode</label>
    <input type="text" name="code" value="{{ old('code') }}" class="form-control @error('code') is-invalid @enderror" required>
    @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-8">
    <label class="form-label">Nama</label>
    <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-4">
    <label class="form-label">Kategori</label>
    <input type="text" name="category" value="{{ old('category') }}" class="form-control @error('category') is-invalid @enderror">
    @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  @if($forcedKind)
    <div class="col-md-4">
      <label class="form-label">Jenis Data</label>
      <input type="hidden" name="kind" value="{{ $forcedKind }}">
      <input type="text" class="form-control" value="{{ $forcedKind === \App\Models\Asset::KIND_LOANABLE ? 'Data Barang Peminjaman' : 'Data Barang Aset' }}" readonly>
      <div class="form-text">Jenis data mengikuti halaman asal.</div>
    </div>
  @else
    <div class="col-md-4">
      <label class="form-label">Jenis Data</label>
      <select name="kind" class="form-select @error('kind') is-invalid @enderror">
        <option value="{{ \App\Models\Asset::KIND_INVENTORY }}" {{ old('kind', \App\Models\Asset::KIND_INVENTORY)===\App\Models\Asset::KIND_INVENTORY ? 'selected' : '' }}>Data Barang Aset</option>
        <option value="{{ \App\Models\Asset::KIND_LOANABLE }}" {{ old('kind')===\App\Models\Asset::KIND_LOANABLE ? 'selected' : '' }}>Data Barang Peminjaman</option>
      </select>
      @error('kind')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
  @endif
  <div class="col-12">
    <label class="form-label">Deskripsi</label>
    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-4">
    <label class="form-label">Jumlah Stok</label>
    <input type="number" min="0" name="quantity_total" value="{{ old('quantity_total', 0) }}" class="form-control @error('quantity_total') is-invalid @enderror" required>
    @error('quantity_total')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-4">
    <label class="form-label">Status</label>
    <select name="status" class="form-select @error('status') is-invalid @enderror">
      <option value="active" {{ old('status','active')==='active'?'selected':'' }}>Aktif</option>
      <option value="inactive" {{ old('status')==='inactive'?'selected':'' }}>Nonaktif</option>
    </select>
    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-4">
    <label class="form-label">Foto (opsional)</label>
    <input type="file" name="photo" accept="image/*" class="form-control @error('photo') is-invalid @enderror">
    @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
    <div class="form-text">Maks {{ (int) config('bpip.asset_photo_max_kb', config('bpip.user_photo_max_kb')) }} KB; format: {{ implode(', ', config('bpip.asset_photo_mimes', config('bpip.user_photo_mimes'))) }}</div>
  </div>
  @php($cancelRoute = ($cancelKind === \App\Models\Asset::KIND_LOANABLE) ? route('assets.loanable') : route('assets.index'))
  <div class="col-12 d-flex gap-2">
    <button class="btn btn-primary" type="submit">Simpan</button>
    <a href="{{ $cancelRoute }}" class="btn btn-secondary">Batal</a>
  </div>
</form>
@endsection
@php($title = 'Edit Aset')
@extends('layouts.app')

@section('content')
<h1 class="h4 mb-3">Edit Aset</h1>

<form method="POST" action="{{ route('assets.update', $asset) }}" class="row g-3" enctype="multipart/form-data">
  @csrf
  @method('PUT')
  <div class="col-md-4">
    <label class="form-label">Kode</label>
    <input type="text" name="code" value="{{ old('code', $asset->code) }}" class="form-control @error('code') is-invalid @enderror" required>
    @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-8">
    <label class="form-label">Nama</label>
    <input type="text" name="name" value="{{ old('name', $asset->name) }}" class="form-control @error('name') is-invalid @enderror" required>
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-4">
    <label class="form-label">Kategori</label>
    <input type="text" name="category" value="{{ old('category', $asset->category) }}" class="form-control @error('category') is-invalid @enderror">
    @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-4">
    <label class="form-label">Jenis Data</label>
    <select name="kind" class="form-select @error('kind') is-invalid @enderror">
      <option value="{{ \App\Models\Asset::KIND_INVENTORY }}" {{ old('kind', $asset->kind)===\App\Models\Asset::KIND_INVENTORY ? 'selected' : '' }}>Data Barang Aset</option>
      <option value="{{ \App\Models\Asset::KIND_LOANABLE }}" {{ old('kind', $asset->kind)===\App\Models\Asset::KIND_LOANABLE ? 'selected' : '' }}>Data Barang Peminjaman</option>
    </select>
    @error('kind')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-12">
    <label class="form-label">Deskripsi</label>
    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $asset->description) }}</textarea>
    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-4">
    <label class="form-label">Jumlah Stok</label>
    <input type="number" min="0" name="quantity_total" value="{{ old('quantity_total', $asset->quantity_total) }}" class="form-control @error('quantity_total') is-invalid @enderror" required>
    @error('quantity_total')<div class="invalid-feedback">{{ $message }}</div>@enderror
    <div class="form-text">Tersedia saat ini: {{ $asset->quantity_available }}</div>
  </div>
  <div class="col-md-4">
    <label class="form-label">Status</label>
    <select name="status" class="form-select @error('status') is-invalid @enderror">
      <option value="active" {{ old('status', $asset->status)==='active'?'selected':'' }}>Aktif</option>
      <option value="inactive" {{ old('status', $asset->status)==='inactive'?'selected':'' }}>Nonaktif</option>
    </select>
    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-4">
    <label class="form-label">Foto (opsional)</label>
    <input type="file" name="photo" accept="image/*" class="form-control @error('photo') is-invalid @enderror">
    @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
    <div class="form-text">Maks {{ (int) config('bpip.asset_photo_max_kb', config('bpip.user_photo_max_kb')) }} KB; format: {{ implode(', ', config('bpip.asset_photo_mimes', config('bpip.user_photo_mimes'))) }}</div>
  </div>
  @if($asset->photo)
    <div class="col-12">
      <label class="form-label">Foto saat ini</label>
      <div class="d-flex align-items-center gap-3">
        <img src="{{ asset('storage/'.$asset->photo) }}" alt="Foto aset" style="width:96px; height:96px; object-fit:cover; border-radius:6px; border:1px solid #dee2e6;">
        <form method="POST" action="{{ route('assets.photo.destroy', $asset) }}" onsubmit="return confirm('Hapus foto aset ini?')">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-outline-danger">Hapus Foto</button>
        </form>
      </div>
    </div>
  @endif
  @php($cancelRoute = $asset->kind === \App\Models\Asset::KIND_LOANABLE ? route('assets.loanable') : route('assets.index'))
  <div class="col-12 d-flex gap-2">
    <button class="btn btn-primary" type="submit">Simpan</button>
    <a href="{{ $cancelRoute }}" class="btn btn-secondary">Batal</a>
  </div>
</form>
@endsection

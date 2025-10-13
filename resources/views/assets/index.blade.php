@php($context = $context ?? 'inventory')
@php($isLoanable = $context === 'loanable')
@php($title = $isLoanable ? 'Data Barang Peminjaman Peralatan' : 'Data Barang Aset')
@php($listRoute = $isLoanable ? 'assets.loanable' : 'assets.index')
@php($exportParams = request()->except('page'))
@php($exportParams = $isLoanable ? array_merge($exportParams, ['kind' => \App\Models\Asset::KIND_LOANABLE]) : $exportParams)
@php($exportUrl = route('assets.export', $exportParams))
@php($importUrl = $isLoanable ? route('assets.import.form', ['kind' => \App\Models\Asset::KIND_LOANABLE]) : route('assets.import.form'))
@php($createUrl = $isLoanable ? route('assets.create', ['kind' => \App\Models\Asset::KIND_LOANABLE]) : route('assets.create'))
@extends('layouts.app')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-2">
  <div>
    <h1 class="h4 mb-0">{{ $title }}</h1>
    @if($isLoanable)
      <p class="text-muted small mb-0">Menampilkan daftar peralatan yang siap dipinjam atau dinonaktifkan.</p>
    @endif
  </div>
  <div class="d-flex gap-2">
    <a href="{{ $exportUrl }}" class="btn btn-outline-success">Export Excel</a>
    @auth
      <a href="{{ $importUrl }}" class="btn btn-outline-primary">Import Excel</a>
      <a href="{{ $createUrl }}" class="btn btn-primary">+ Tambah {{ $isLoanable ? 'Barang Peminjaman' : 'Aset' }}</a>
    @endauth
  </div>
</div>

@php($statusValue = request('status', ''))
@php($availableChecked = request('available') === '1')

<form method="GET" action="{{ route($listRoute) }}" class="row g-2 align-items-end mb-3">
  <input type="hidden" name="filter" value="1">
  <div class="col-md-4">
    <label class="form-label">Cari</label>
    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="kode/nama/deskripsi">
  </div>
  <div class="col-md-3">
    <label class="form-label">Kategori</label>
    <select name="category" class="form-select">
      <option value="">-- semua --</option>
      @foreach(($categories ?? []) as $cat)
        <option value="{{ $cat }}" {{ request('category')===$cat?'selected':'' }}>{{ $cat }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-3">
    <label class="form-label">Status</label>
    <select name="status" class="form-select">
      <option value="" {{ $statusValue === '' ? 'selected' : '' }}>Semua</option>
      <option value="active" {{ $statusValue === 'active' ? 'selected' : '' }}>Aktif</option>
      <option value="inactive" {{ $statusValue === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
    </select>
  </div>
  <div class="col-md-2 form-check mt-4 pt-2">
    <input class="form-check-input" type="checkbox" name="available" value="1" id="chkAvail" {{ $availableChecked ? 'checked' : '' }}>
    <label class="form-check-label" for="chkAvail">Hanya yang tersedia</label>
  </div>
  <div class="col-md-2">
    <button class="btn btn-outline-primary w-100" type="submit">Terapkan</button>
  </div>
</form>

<div class="table-responsive">
<table class="table table-striped align-middle">
  <thead>
    <tr>
      <th>Foto</th>
      @php($s=request('sort'))
      @php($d=request('dir','asc'))
      @php($next=function($key){ return (request('sort')===$key && request('dir','asc')==='asc')?'desc':'asc'; })
      <th>
        @php($q=array_merge(request()->all(),['sort'=>'code','dir'=>$next('code')]))
        @php($arrow=$s==='code' ? ($d==='asc'?'&uarr;':'&darr;') : '&#8597;')
        <a href="{{ route($listRoute,$q) }}" class="text-decoration-none">Kode <span class="text-muted">{!! $arrow !!}</span></a>
      </th>
      <th>
        @php($q=array_merge(request()->all(),['sort'=>'name','dir'=>$next('name')]))
        @php($arrow=$s==='name' ? ($d==='asc'?'&uarr;':'&darr;') : '&#8597;')
        <a href="{{ route($listRoute,$q) }}" class="text-decoration-none">Nama <span class="text-muted">{!! $arrow !!}</span></a>
      </th>
      <th>
        @php($q=array_merge(request()->all(),['sort'=>'category','dir'=>$next('category')]))
        @php($arrow=$s==='category' ? ($d==='asc'?'&uarr;':'&darr;') : '&#8597;')
        <a href="{{ route($listRoute,$q) }}" class="text-decoration-none">Kategori <span class="text-muted">{!! $arrow !!}</span></a>
      </th>
      <th>
        @php($q=array_merge(request()->all(),['sort'=>'qty_available','dir'=>$next('qty_available')]))
        @php($arrow=$s==='qty_available' ? ($d==='asc'?'&uarr;':'&darr;') : '&#8597;')
        <a href="{{ route($listRoute,$q) }}" class="text-decoration-none">Stok <span class="text-muted">{!! $arrow !!}</span></a>
      </th>
      <th>
        @php($q=array_merge(request()->all(),['sort'=>'status','dir'=>$next('status')]))
        @php($arrow=$s==='status' ? ($d==='asc'?'&uarr;':'&darr;') : '&#8597;')
        <a href="{{ route($listRoute,$q) }}" class="text-decoration-none">Status <span class="text-muted">{!! $arrow !!}</span></a>
      </th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    @forelse($assets as $asset)
      <tr>
        <td>
          @if($asset->photo)
            <img src="{{ asset('storage/'.$asset->photo) }}" alt="Foto" style="width:48px;height:48px;object-fit:cover;border-radius:4px;border:1px solid #dee2e6;">
          @else
            <span class="text-muted">-</span>
          @endif
        </td>
        <td>{{ $asset->code }}</td>
        <td>{{ $asset->name }}</td>
        <td>{{ $asset->category ?? '-' }}</td>
        <td>{{ $asset->quantity_available }} / {{ $asset->quantity_total }}</td>
        <td>
          @php($statusLabel = $asset->status === 'active' ? 'aktif' : ($asset->status === 'inactive' ? 'tidak aktif' : $asset->status))
          <span class="badge {{ $asset->status === 'active' ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $statusLabel }}</span>
        </td>
        <td class="d-flex flex-wrap gap-2">
          @auth
            <a class="btn btn-sm btn-outline-primary" href="{{ route('assets.edit', $asset) }}">Edit</a>
            <form method="POST" action="{{ route('assets.destroy', $asset) }}" onsubmit="return confirm('Hapus aset ini?')">
              @csrf
              @method('DELETE')
              <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
            </form>
          @else
            <span class="text-muted small">Login untuk mengelola</span>
          @endauth
        </td>
      </tr>
    @empty
      <tr>
        <td colspan="7" class="text-center">
          {{ $isLoanable ? 'Belum ada peralatan peminjaman.' : 'Belum ada data aset.' }}
        </td>
      </tr>
    @endforelse
  </tbody>
</table>
</div>

{{ $assets->links() }}
@endsection
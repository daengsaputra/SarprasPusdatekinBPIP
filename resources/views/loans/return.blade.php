@php($title = 'Pengembalian Aset')
@extends('layouts.app')

@section('content')
<h1 class="h4 mb-3">Pengembalian Aset</h1>

<div class="card mb-3">
  <div class="card-body">
    <div><strong>Aset:</strong> {{ $loan->asset->code }} - {{ $loan->asset->name }}</div>
    <div><strong>Peminjam:</strong> {{ $loan->borrower_name }} ({{ $loan->borrower_contact ?? '-' }})</div>
    <div><strong>Jumlah:</strong> {{ $loan->quantity }}</div>
    <div><strong>Tanggal Pinjam:</strong> {{ $loan->loan_date?->format('Y-m-d') }}</div>
    <div><strong>Rencana Kembali:</strong> {{ $loan->return_date_planned?->format('Y-m-d') ?? '-' }}</div>
  </div>
  </div>

<form method="POST" action="{{ route('loans.return.update', $loan) }}" class="row g-3">
  @csrf
  <div class="col-md-4">
    <label class="form-label">Tanggal Kembali</label>
    <input type="date" name="return_date_actual" value="{{ old('return_date_actual', now()->format('Y-m-d')) }}" class="form-control @error('return_date_actual') is-invalid @enderror" required>
    @error('return_date_actual')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-12">
    <label class="form-label">Catatan</label>
    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $loan->notes) }}</textarea>
    @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-12 d-flex gap-2">
    <button class="btn btn-success" type="submit">Proses Pengembalian</button>
    <a href="{{ route('loans.index') }}" class="btn btn-secondary">Batal</a>
  </div>
</form>
@endsection


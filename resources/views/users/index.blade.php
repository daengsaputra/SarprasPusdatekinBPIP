@php($title = 'Daftar Anggota')
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h4">Daftar Anggota</h1>
  <a href="{{ route('users.create') }}" class="btn btn-primary">Tambah Anggota</a>
</div>

<div class="row row-cols-1 row-cols-md-3 g-3">
  @foreach($users as $u)
    <div class="col">
      <div class="card h-100">
        <div class="card-body d-flex gap-3 align-items-center">
          @if($u->photo)
            <img src="{{ asset('storage/'.$u->photo) }}" alt="Foto {{ $u->name }}" class="rounded-circle" style="width:48px;height:48px;object-fit:cover;">
          @else
            <?php $parts = preg_split('/\s+/', trim($u->name)); $initials = strtoupper(mb_substr($parts[0]??'',0,1).mb_substr($parts[1]??'',0,1)); ?>
            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width:48px;height:48px;font-weight:600;">
              {{ $initials ?: '?' }}
            </div>
          @endif
          <div>
            <div class="fw-semibold">{{ $u->name }}</div>
            <div class="text-muted small">{{ $u->email }} • {{ strtoupper($u->role) }}</div>
          </div>
          <div class="ms-auto d-flex gap-1">
            <a href="{{ route('users.edit', $u) }}" class="btn btn-sm btn-outline-primary">Edit</a>
            <form method="POST" action="{{ route('users.reset', $u) }}" onsubmit="return confirm('Reset password untuk {{ $u->name }}?');">
              @csrf
              <button type="submit" class="btn btn-sm btn-outline-warning">Reset</button>
            </form>
            <form method="POST" action="{{ route('users.destroy', $u) }}" onsubmit="return confirm('Hapus anggota {{ $u->name }}?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  @endforeach
</div>

<div class="mt-3">{{ $users->links() }}</div>
@endsection

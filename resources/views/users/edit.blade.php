@php($title = 'Edit Anggota')
@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-7 col-lg-6">
    <div class="card shadow-sm">
      <div class="card-body p-4">
        <h1 class="h5 mb-3">Edit Anggota</h1>
        <form method="POST" action="{{ route('users.update', $user) }}" class="row gy-3" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div class="col-12">
            <label class="form-label">Nama</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-12">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-12 col-md-6">
            <label class="form-label">Role</label>
            <select name="role" class="form-select @error('role') is-invalid @enderror" required>
              <option value="petugas" {{ old('role', $user->role)==='petugas' ? 'selected' : '' }}>Petugas</option>
              <option value="admin" {{ old('role', $user->role)==='admin' ? 'selected' : '' }}>Admin</option>
            </select>
            @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-12 col-md-6"></div>
          <div class="col-12">
            <label class="form-label">Foto</label>
            <div class="d-flex align-items-center gap-3 mb-2">
              @if($user->photo)
                <img src="{{ asset('storage/'.$user->photo) }}" alt="Foto {{ $user->name }}" class="rounded-circle" style="width:64px;height:64px;object-fit:cover;">
              @else
                <?php $parts = preg_split('/\s+/', trim($user->name)); $initials = strtoupper(mb_substr($parts[0]??'',0,1).mb_substr($parts[1]??'',0,1)); ?>
                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width:64px;height:64px;font-weight:600;">
                  {{ $initials ?: '?' }}
                </div>
              @endif
              <div class="text-muted small">Upload file baru untuk mengganti foto.</div>
            </div>
            <input type="file" name="photo" accept="image/*" class="form-control @error('photo') is-invalid @enderror">
            @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-12 col-md-6">
            <label class="form-label">Password Baru</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Kosongkan jika tidak diubah">
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-12 col-md-6">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Kosongkan jika tidak diubah">
          </div>
          <div class="col-12 d-flex flex-wrap gap-2 mt-2 align-items-center">
            <button class="btn btn-primary" type="submit">Simpan</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
            <form method="POST" action="{{ route('users.reset', $user) }}" onsubmit="return confirm('Reset password untuk {{ $user->name }}?');" class="ms-auto">
              @csrf
              <button type="submit" class="btn btn-warning">Reset Password</button>
            </form>
            @if($user->photo)
            <form method="POST" action="{{ route('users.photo.destroy', $user) }}" onsubmit="return confirm('Hapus foto untuk {{ $user->name }}?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-outline-secondary">Hapus Foto</button>
            </form>
            @endif
            <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Hapus anggota {{ $user->name }}?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger">Hapus</button>
            </form>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

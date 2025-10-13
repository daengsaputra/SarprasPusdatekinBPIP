@php($title = 'Reset Password')
@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-6 col-lg-5">
    <div class="card shadow-sm">
      <div class="card-body p-4">
        <h1 class="h4 mb-3">Reset Password</h1>
        <form method="POST" action="{{ route('password.update') }}" class="row gy-3">
          @csrf
          <input type="hidden" name="token" value="{{ $token }}">
          <input type="hidden" name="email" value="{{ $email }}">
          <div class="col-12">
            <label class="form-label">Password Baru</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-12">
            <label class="form-label">Ulangi Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
          </div>
          <div class="col-12 d-grid">
            <button class="btn btn-primary" type="submit">Reset</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection


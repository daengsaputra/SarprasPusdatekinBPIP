@php($title = 'Lupa Password')
@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-6 col-lg-5">
    <div class="card shadow-sm">
      <div class="card-body p-4">
        <h1 class="h4 mb-3">Lupa Password</h1>
        <p class="text-muted">Masukkan username atau email untuk membuat tautan reset password.</p>

        @if(session('status'))
          <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @if(session('reset_link'))
          <div class="alert alert-info">
            <div class="fw-semibold mb-1">Tautan reset (salin dan buka):</div>
            <div class="small"><a href="{{ session('reset_link') }}">{{ session('reset_link') }}</a></div>
          </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="row gy-3">
          @csrf
          <div class="col-12">
            <label class="form-label">Username atau Email</label>
            <input type="text" name="identifier" value="{{ old('identifier') }}" class="form-control @error('identifier') is-invalid @enderror" required autofocus>
            @error('identifier')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-12 d-grid">
            <button class="btn btn-primary" type="submit">Buat Tautan Reset</button>
          </div>
          <div class="col-12 text-center">
            <a href="{{ route('login') }}" class="small">Kembali ke login</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection


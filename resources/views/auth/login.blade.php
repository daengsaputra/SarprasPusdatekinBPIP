@php($title = 'Masuk')
@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-6 col-lg-5">
    <div class="card shadow-sm">
      <div class="card-body p-4">
        <h1 class="h4 mb-3">Masuk</h1>
        <form method="POST" action="{{ route('login.post') }}" class="gy-3 row">
          @csrf
          <div class="col-12 mb-3">
            <label class="form-label">Username atau Email</label>
            <input type="text" name="identifier" value="{{ old('identifier') }}" class="form-control @error('identifier') is-invalid @enderror" required autofocus>
            @error('identifier')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-12 mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-12 mb-3 form-check">
            <input type="checkbox" name="remember" id="remember" class="form-check-input">
            <label for="remember" class="form-check-label">Ingat saya</label>
          </div>
          <div class="col-12 d-grid">
            <button class="btn btn-primary" type="submit">Masuk</button>
          </div>
          <div class="col-12 text-center mt-2">
            <a href="{{ route('password.request') }}" class="small">Lupa password?</a>
          </div>
        </form>
        
      </div>
    </div>
  </div>
</div>
@endsection

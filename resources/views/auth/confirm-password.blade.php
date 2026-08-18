@extends('layouts.guest')

@section('content')
<div class="container d-flex flex-column">
    <div class="row align-items-center justify-content-center
        min-vh-100 g-0">
        <div class="col-12 col-md-8 col-lg-6 py-8 py-md-11">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h1 class="mb-2">Konfirmasi Kata Sandi</h1>
                        <p class="text-muted">Ini adalah area aplikasi yang aman. Mohon konfirmasi kata sandi Anda sebelum melanjutkan.</p>
                    </div>

                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="password" class="form-label">Kata Sandi</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                Konfirmasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

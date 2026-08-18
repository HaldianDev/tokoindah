@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 520px; margin: 40px auto 60px;">
    <div style="background: white; border: 1px solid var(--border-color); border-radius: var(--radius); padding: 2.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.03);">
        <div style="text-align: center; margin-bottom: 2rem;">
            <div style="width: 60px; height: 60px; background: #DCFCE7; color: var(--success); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin: 0 auto 1rem;">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <h2 style="font-size: 1.8rem; font-weight: 800; color: var(--primary);">Buat Akun Baru</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Daftar untuk menikmati belanja & fitur angsuran/kredit</p>
        </div>

        @if ($errors->any())
            <div class="alert-box alert-danger" style="padding: 0.8rem; font-size: 0.85rem; margin-bottom: 1.5rem;">
                <ul style="margin-left: 15px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; font-weight: 700; font-size: 0.9rem; margin-bottom: 6px;">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border-color); border-radius: 8px; font-size: 0.95rem; outline: none;" placeholder="Nama Lengkap Anda">
            </div>

            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; font-weight: 700; font-size: 0.9rem; margin-bottom: 6px;">Nomor Telepon/WhatsApp</label>
                <input type="tel" name="phone" value="{{ old('phone') }}" required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border-color); border-radius: 8px; font-size: 0.95rem; outline: none;" placeholder="Contoh: 081234567890">
            </div>

            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; font-weight: 700; font-size: 0.9rem; margin-bottom: 6px;">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border-color); border-radius: 8px; font-size: 0.95rem; outline: none;" placeholder="nama@email.com">
            </div>

            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; font-weight: 700; font-size: 0.9rem; margin-bottom: 6px;">Password</label>
                <input type="password" name="password" required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border-color); border-radius: 8px; font-size: 0.95rem; outline: none;" placeholder="Minimal 8 Karakter">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 700; font-size: 0.9rem; margin-bottom: 6px;">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border-color); border-radius: 8px; font-size: 0.95rem; outline: none;" placeholder="Ulangi Password">
            </div>

            <button type="submit" style="width: 100%; background: var(--accent); color: white; border: none; padding: 0.85rem; border-radius: 8px; font-weight: 700; font-size: 1rem; cursor: pointer; transition: var(--transition);">
                <i class="fa-solid fa-user-check"></i> Mendaftar Sekarang
            </button>
        </form>

        <div style="text-align: center; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color); font-size: 0.9rem; color: var(--text-muted);">
            Sudah punya akun? <a href="{{ route('login') }}" style="color: var(--accent); font-weight: 700; text-decoration: none;">Masuk di sini</a>
        </div>
    </div>
</div>
@endsection
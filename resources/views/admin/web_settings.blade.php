@extends('layouts.dashboard')

@section('page_title', 'Pengaturan Website')
@section('role_label', '👑 Area Admin')
@section('role_color', '#1D4ED8')
@section('avatar_gradient', '#3B82F6, #6D28D9')

@section('sidebar_nav')
    <div class="sidebar-section-label">Menu Utama</div>
    <a href="{{ route('admin.dashboard') }}" class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <div class="sidebar-item-icon"><i class="fa-solid fa-gauge-high"></i></div>
        Dashboard
    </a>
    <a href="{{ route('admin.settings') }}" class="sidebar-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
        <div class="sidebar-item-icon"><i class="fa-solid fa-user-cog"></i></div>
        Pengaturan Akun
    </a>
    <a href="{{ route('admin.web_settings') }}" class="sidebar-item {{ request()->routeIs('admin.web_settings') ? 'active' : '' }}">
        <div class="sidebar-item-icon" style="background:rgba(99,102,241,0.12);color:#818CF8;"><i class="fa-solid fa-gear"></i></div>
        Pengaturan Website
    </a>
@endsection

@section('content')
<div class="dash-section active">
    <div class="section-header">
        <div>
            <h1 class="section-title"><i class="fa-solid fa-gear" style="color:#6366F1;"></i> Pengaturan Website</h1>
            <p class="section-subtitle">Kelola informasi toko, logo, konten beranda, halaman tentang kami, dan tarif ongkir</p>
        </div>
    </div>

    @if (session('success'))
        <div class="flash-alert flash-success">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.web_settings.update') }}" enctype="multipart/form-data">
        @csrf

        {{-- Informasi Toko --}}
        <div class="table-card" style="margin-bottom:1.5rem;">
            <div class="table-card-header" style="background:#F8FAFC;">
                <div class="table-card-title">
                    <div class="table-card-title-icon" style="background:rgba(99,102,241,0.1);color:#6366F1;"><i class="fa-solid fa-store"></i></div>
                    Informasi Toko
                </div>
            </div>
            <div style="padding:1.5rem;display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1.25rem;">
                <div class="form-group">
                    <label class="form-label">Nama Toko / Brand <span style="color:#EF4444;">*</span></label>
                    <input type="text" name="site_name" class="form-control" required value="{{ old('site_name', $settings->site_name) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Nomor WhatsApp <span style="color:#EF4444;">*</span></label>
                    <input type="text" name="whatsapp_number" class="form-control" required value="{{ old('whatsapp_number', $settings->whatsapp_number) }}" placeholder="6281234567890">
                </div>
                <div class="form-group">
                    <label class="form-label">No. Telepon</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $settings->phone) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $settings->email) }}">
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Alamat Toko</label>
                    <textarea name="store_address" rows="2" class="form-control">{{ old('store_address', $settings->store_address) }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Tarif Ongkir per Kg (Rp) <span style="color:#EF4444;">*</span></label>
                    <input type="number" name="shipping_cost_per_kg" class="form-control" required min="0" value="{{ old('shipping_cost_per_kg', $settings->shipping_cost_per_kg) }}">
                    <small style="color:#64748B;font-size:0.72rem;">Tarif dasar per kilogram untuk kalkulasi ongkos kirim kurir.</small>
                </div>
                <div class="form-group">
                    <label class="form-label">Logo Toko</label>
                    <input type="file" name="logo_file" accept="image/*" class="form-control" style="padding:0.4rem;">
                    @if($settings->logo)
                    <div style="margin-top:6px;display:flex;align-items:center;gap:8px;">
                        <img src="{{ $settings->logo }}" style="height:36px;border-radius:6px;" alt="Logo saat ini">
                        <span style="font-size:0.72rem;color:#64748B;">Logo saat ini</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Hero Beranda --}}
        <div class="table-card" style="margin-bottom:1.5rem;">
            <div class="table-card-header" style="background:#F8FAFC;">
                <div class="table-card-title">
                    <div class="table-card-title-icon" style="background:rgba(5,150,105,0.1);color:#059669;"><i class="fa-solid fa-home"></i></div>
                    Konten Beranda (Hero Section)
                </div>
            </div>
            <div style="padding:1.5rem;display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1.25rem;">
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Judul Hero</label>
                    <input type="text" name="hero_title" class="form-control" value="{{ old('hero_title', $settings->hero_title) }}">
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Subtitle Hero</label>
                    <textarea name="hero_subtitle" rows="2" class="form-control">{{ old('hero_subtitle', $settings->hero_subtitle) }}</textarea>
                </div>
            </div>
        </div>

        {{-- About Us --}}
        <div class="table-card" style="margin-bottom:1.5rem;">
            <div class="table-card-header" style="background:#F8FAFC;">
                <div class="table-card-title">
                    <div class="table-card-title-icon" style="background:rgba(217,119,6,0.1);color:#D97706;"><i class="fa-solid fa-info-circle"></i></div>
                    Konten Tentang Kami
                </div>
            </div>
            <div style="padding:1.5rem;display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1.25rem;">
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Judul About</label>
                    <input type="text" name="about_title" class="form-control" value="{{ old('about_title', $settings->about_title) }}">
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Deskripsi About</label>
                    <textarea name="about_description" rows="3" class="form-control">{{ old('about_description', $settings->about_description) }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Visi</label>
                    <textarea name="about_vision" rows="2" class="form-control">{{ old('about_vision', $settings->about_vision) }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Misi</label>
                    <textarea name="about_mission" rows="2" class="form-control">{{ old('about_mission', $settings->about_mission) }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Gambar About</label>
                    <input type="file" name="about_file" accept="image/*" class="form-control" style="padding:0.4rem;">
                    @if($settings->about_image)
                    <div style="margin-top:6px;">
                        <img src="{{ $settings->about_image }}" style="height:60px;border-radius:8px;" alt="About Image">
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:1rem;">
            <button type="submit" class="btn-action btn-primary" style="padding:0.75rem 2rem;">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Semua Pengaturan
            </button>
        </div>
    </form>
</div>
@endsection
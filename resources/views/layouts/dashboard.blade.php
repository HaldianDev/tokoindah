@php
    $appSettings = \App\Models\WebSetting::getSettings();
@endphp
<!DOCTYPE html>
<html lang="id" class="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page_title', 'Dashboard') — {{ $appSettings->site_name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0F172A',
                        accent: '#0284C7',
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            --sidebar-w: 260px;
            --topbar-h: 64px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #F1F5F9;
            color: #0F172A;
            min-height: 100vh;
        }

        /* ====== SIDEBAR ====== */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: linear-gradient(180deg, #090E17 0%, #0F172A 50%, #111C33 100%);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: transform 0.3s cubic-bezier(0.4,0,0.2,1);
            border-right: 1px solid rgba(255,255,255,0.07);
            box-shadow: 6px 0 30px rgba(0,0,0,0.2);
        }
        .sidebar.collapsed { transform: translateX(calc(-1 * var(--sidebar-w))); }

        .sidebar-brand {
            padding: 1.4rem 1.5rem 1.2rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }
        .sidebar-brand-icon {
            width: 40px; height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, #0284C7 0%, #3B82F6 50%, #6366F1 100%);
            box-shadow: 0 4px 14px rgba(2,132,199,0.35);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
            color: #fff;
            flex-shrink: 0;
        }
        .sidebar-brand-text { display: flex; flex-direction: column; }
        .sidebar-brand-name {
            font-size: 1.05rem; font-weight: 800;
            color: #fff;
            letter-spacing: -0.3px;
            line-height: 1.2;
        }
        .sidebar-brand-name span { color: #38BDF8; }
        .sidebar-brand-role {
            font-size: 0.68rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.08em;
            margin-top: 2px;
            color: #94A3B8;
        }

        /* User info in sidebar */
        .sidebar-user {
            padding: 0.9rem 1.1rem;
            margin: 0.75rem 0.9rem;
            background: rgba(255,255,255,0.03);
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.06);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .sidebar-user-avatar {
            width: 38px; height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #0284C7 0%, #6366F1 100%);
            box-shadow: 0 3px 10px rgba(2,132,199,0.3);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem; font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }
        .sidebar-user-name {
            font-size: 0.85rem; font-weight: 700; color: #F1F5F9;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .sidebar-user-email {
            font-size: 0.7rem; color: #94A3B8;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }

        /* Nav section label */
        .sidebar-section-label {
            padding: 1.1rem 1.5rem 0.4rem;
            font-size: 0.65rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.1em;
            color: #64748B;
        }

        /* Nav items */
        .sidebar-nav { flex: 1; overflow-y: auto; padding: 0.5rem 0.75rem; }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }

        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.7rem 0.9rem;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            color: #94A3B8;
            font-size: 0.88rem;
            font-weight: 600;
            margin-bottom: 3px;
            border: 1px solid transparent;
            background: transparent;
            width: 100%;
            text-align: left;
            position: relative;
            text-decoration: none;
        }
        .sidebar-item:hover {
            background: rgba(255,255,255,0.06);
            color: #F8FAFC;
            transform: translateX(3px);
        }
        .sidebar-item.active {
            background: linear-gradient(90deg, rgba(2,132,199,0.2) 0%, rgba(99,102,241,0.08) 100%);
            color: #38BDF8;
            border-color: rgba(56,189,248,0.25);
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        }
        .sidebar-item.active::before {
            content: '';
            position: absolute;
            left: 0; top: 50%;
            transform: translateY(-50%);
            width: 3px; height: 70%;
            background: linear-gradient(180deg, #38BDF8 0%, #818CF8 100%);
            border-radius: 0 3px 3px 0;
            box-shadow: 0 0 10px rgba(56,189,248,0.6);
        }
        .sidebar-item-icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.85rem;
            flex-shrink: 0;
            transition: all 0.2s;
        }
        .sidebar-item.active .sidebar-item-icon {
            background: rgba(56,189,248,0.18);
            color: #38BDF8;
        }
        .sidebar-item:not(.active) .sidebar-item-icon {
            background: rgba(255,255,255,0.04);
        }
        .sidebar-item-badge {
            margin-left: auto;
            background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
            color: white;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 50px;
            min-width: 20px;
            text-align: center;
        }

        /* Divider */
        .sidebar-divider {
            height: 1px;
            background: rgba(255,255,255,0.06);
            margin: 0.5rem 0;
        }

        /* Sidebar footer */
        .sidebar-footer {
            padding: 1rem 0.75rem;
            border-top: 1px solid rgba(255,255,255,0.07);
        }
        .sidebar-footer form { margin: 0; }
        .sidebar-footer-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.9rem;
            border-radius: 10px;
            border: none;
            background: rgba(239,68,68,0.1);
            color: #F87171;
            font-size: 0.88rem; font-weight: 600;
            width: 100%; cursor: pointer;
            transition: all 0.2s;
            font-family: inherit;
        }
        .sidebar-footer-btn:hover { background: rgba(239,68,68,0.2); }

        /* ====== TOPBAR ====== */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: var(--topbar-h);
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid #E2E8F0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            z-index: 900;
            transition: left 0.3s cubic-bezier(0.4,0,0.2,1);
            box-shadow: 0 1px 10px rgba(0,0,0,0.05);
        }
        .topbar.sidebar-collapsed { left: 0; }

        .topbar-left { display: flex; align-items: center; gap: 1rem; }
        .topbar-right { display: flex; align-items: center; gap: 0.75rem; }

        .topbar-toggle {
            width: 38px; height: 38px;
            border-radius: 10px;
            border: 1px solid #E2E8F0;
            background: transparent;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            color: #64748B;
            font-size: 1rem;
            transition: all 0.2s;
        }
        .topbar-toggle:hover { background: #F1F5F9; color: #0F172A; }

        .topbar-page-title {
            font-size: 1rem; font-weight: 700; color: #0F172A;
        }
        .topbar-breadcrumb {
            font-size: 0.78rem; color: #94A3B8; font-weight: 500;
        }

        .topbar-btn {
            width: 38px; height: 38px;
            border-radius: 10px;
            border: 1px solid #E2E8F0;
            background: transparent;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            color: #64748B;
            font-size: 0.9rem;
            transition: all 0.2s;
            text-decoration: none;
        }
        .topbar-btn:hover { background: #F1F5F9; color: #0F172A; }

        /* ====== MAIN CONTENT ====== */
        .dashboard-main {
            margin-left: var(--sidebar-w);
            margin-top: var(--topbar-h);
            min-height: calc(100vh - var(--topbar-h));
            padding: 2rem;
            transition: margin-left 0.3s cubic-bezier(0.4,0,0.2,1);
        }
        .dashboard-main.sidebar-collapsed { margin-left: 0; }

        /* ====== SECTION PANELS ====== */
        .dash-section { display: none; animation: fadeSlideIn 0.3s ease; }
        .dash-section.active { display: block; }

        @keyframes fadeSlideIn {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ====== STATS CARD ====== */
        .stat-card {
            background: #fff;
            border: 1px solid #E2E8F0;
            border-radius: 16px;
            padding: 1.4rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .stat-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.06); transform: translateY(-2px); }
        .stat-card-label { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #94A3B8; }
        .stat-card-value { font-size: 2rem; font-weight: 900; color: #0F172A; line-height: 1.1; margin-top: 4px; }
        .stat-card-sub { font-size: 0.75rem; font-weight: 600; margin-top: 4px; display: flex; align-items: center; gap: 4px; }
        .stat-card-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        /* ====== TABLE CARD ====== */
        .table-card {
            background: #fff;
            border: 1px solid #E2E8F0;
            border-radius: 16px;
            overflow: hidden;
        }
        .table-card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #E2E8F0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .table-card-title {
            font-size: 0.95rem; font-weight: 700; color: #0F172A;
            display: flex; align-items: center; gap: 0.6rem;
        }
        .table-card-title-icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.85rem;
        }
        .dash-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
        .dash-table th {
            padding: 0.8rem 1.25rem;
            background: #F8FAFC;
            color: #64748B;
            font-size: 0.7rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.08em;
            text-align: left;
            border-bottom: 1px solid #E2E8F0;
            white-space: nowrap;
        }
        .dash-table td { padding: 0.9rem 1.25rem; border-bottom: 1px solid #F1F5F9; color: #334155; vertical-align: middle; }
        .dash-table tbody tr:last-child td { border-bottom: none; }
        .dash-table tbody tr:hover td { background: #F8FAFC; }

        /* ====== BADGES ====== */
        .badge {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 3px 10px;
            border-radius: 50px;
            font-size: 0.72rem; font-weight: 700;
            white-space: nowrap;
        }
        .badge-emerald { background: #D1FAE5; color: #065F46; }
        .badge-amber   { background: #FEF3C7; color: #92400E; }
        .badge-rose    { background: #FEE2E2; color: #991B1B; }
        .badge-blue    { background: #DBEAFE; color: #1E40AF; }
        .badge-sky     { background: #E0F2FE; color: #0369A1; }
        .badge-indigo  { background: #E0E7FF; color: #3730A3; }
        .badge-gray    { background: #F1F5F9; color: #475569; }

        /* ====== SECTION PAGE HEADER ====== */
        .section-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.75rem;
        }
        .section-title { font-size: 1.4rem; font-weight: 800; color: #0F172A; line-height: 1.2; }
        .section-subtitle { font-size: 0.85rem; color: #64748B; margin-top: 3px; }

        /* ====== ACTION BUTTONS ====== */
        .btn-action {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 0.55rem 1.1rem;
            border-radius: 10px;
            font-size: 0.85rem; font-weight: 600;
            border: none; cursor: pointer;
            transition: all 0.2s;
            font-family: inherit;
            text-decoration: none;
        }
        .btn-action:hover { transform: translateY(-1px); }
        .btn-primary { background: #0284C7; color: #fff; box-shadow: 0 2px 8px rgba(2,132,199,0.3); }
        .btn-primary:hover { background: #0369A1; }
        .btn-success { background: #059669; color: #fff; box-shadow: 0 2px 8px rgba(5,150,105,0.3); }
        .btn-success:hover { background: #047857; }
        .btn-outline {
            background: transparent; color: #475569;
            border: 1px solid #CBD5E1;
        }
        .btn-outline:hover { background: #F8FAFC; border-color: #94A3B8; }
        .btn-danger { background: #EF4444; color: #fff; }
        .btn-danger:hover { background: #DC2626; }
        .btn-sm { padding: 0.35rem 0.8rem; font-size: 0.78rem; }
        .btn-icon {
            width: 32px; height: 32px; padding: 0;
            border-radius: 8px; justify-content: center;
        }

        /* Modal styles carried over */
        .modal-overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15,23,42,0.65);
            backdrop-filter: blur(4px);
            display: none; align-items: center; justify-content: center;
            z-index: 10000; padding: 1rem;
        }
        .modal-overlay.active { display: flex !important; }
        .modal-card {
            background: #fff;
            border-radius: 18px;
            padding: 2rem;
            width: 100%;
            position: relative;
            box-shadow: 0 25px 60px -12px rgba(0,0,0,0.3);
            max-height: 90vh; overflow-y: auto;
        }
        .modal-close {
            position: absolute; top: 1rem; right: 1.25rem;
            font-size: 1.5rem; background: none; border: none;
            cursor: pointer; color: #64748B; line-height: 1;
        }
        .modal-close:hover { color: #EF4444; }

        /* Form input styles */
        .form-group { margin-bottom: 1rem; }
        .form-label {
            display: block; margin-bottom: 5px;
            font-size: 0.82rem; font-weight: 700; color: #374151;
        }
        .form-control {
            width: 100%; padding: 0.6rem 0.9rem;
            border: 1px solid #CBD5E1; border-radius: 9px;
            font-size: 0.875rem; color: #0F172A;
            background: #fff; outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            font-family: inherit;
        }
        .form-control:focus { border-color: #0284C7; box-shadow: 0 0 0 3px rgba(2,132,199,0.1); }

        /* Grid helpers */
        .grid-stats-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem; }
        .grid-stats-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem; }
        .space-y { display: flex; flex-direction: column; gap: 1.5rem; }

        /* Mobile sidebar overlay */
        .sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
        .sidebar-overlay.visible { display: block; }

        /* Flash alert */
        .flash-alert {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.85rem 1.25rem;
            border-radius: 12px;
            font-size: 0.875rem; font-weight: 600;
            margin-bottom: 1.5rem;
        }
        .flash-success { background: #D1FAE5; color: #065F46; border: 1px solid #A7F3D0; }
        .flash-error   { background: #FEE2E2; color: #991B1B; border: 1px solid #FCA5A5; }

        /* Scrollbar for dark sidebar */
        .sidebar-nav { scrollbar-width: thin; scrollbar-color: #334155 transparent; }

        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(calc(-1 * var(--sidebar-w))); }
            .sidebar.mobile-open { transform: translateX(0); }
            .topbar { left: 0 !important; }
            .dashboard-main { margin-left: 0 !important; }
            .grid-stats-3 { grid-template-columns: repeat(2, 1fr); }
        }
        /* For cashier and other two-column layouts */
        @media (max-width: 900px) {
            .cashier-grid {
                grid-template-columns: 1fr !important;
            }
        }
        @media (max-width: 640px) {
            .grid-stats-3, .grid-stats-2, .responsive-grid-2-col { grid-template-columns: 1fr; }
            .dashboard-main { padding: 1.25rem; }
            .dash-table td, .dash-table th { padding: 0.7rem 0.85rem; }
        }

        /* select box */
        select.form-control { cursor: pointer; }

        /* Progress bar */
        .progress-bar { height: 6px; border-radius: 50px; background: #E2E8F0; overflow: hidden; }
        .progress-fill { height: 100%; border-radius: 50px; transition: width 0.4s; }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .swal2-toast {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-size: 0.88rem !important;
        }
    </style>
    @stack('head_scripts')
</head>
<body class="">

<!-- Sidebar overlay for mobile -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<!-- ====== SIDEBAR ====== -->
<aside class="sidebar" id="sidebar">
    <!-- Brand -->
    <a href="{{ route('store.index') }}" class="sidebar-brand">
        <div class="sidebar-brand-icon" style="background: linear-gradient(135deg,#0284C7,#38BDF8);">
            <i class="fa-solid fa-house" style="color:#fff;"></i>
        </div>
        <div class="sidebar-brand-text">
            <div class="sidebar-brand-name">{{ $appSettings->site_name }}</div>
            <div class="sidebar-brand-role" style="color: @yield('role_color', '#38BDF8');">
                @yield('role_label', 'Dashboard')
            </div>
        </div>
    </a>

    <!-- User info -->
    @auth
    <div class="sidebar-user">
        <div class="sidebar-user-avatar" style="background: linear-gradient(135deg, @yield('avatar_gradient', '#0284C7, #38BDF8'));">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <div style="min-width: 0;">
            <div class="sidebar-user-name">{{ Auth::user()->name }}</div>
            <div class="sidebar-user-email">{{ Auth::user()->email }}</div>
        </div>
    </div>
    @endauth

    <!-- Nav items -->
    <nav class="sidebar-nav" id="sidebarNav">
        @yield('sidebar_nav')
    </nav>

    <!-- Footer -->
    <div class="sidebar-footer">
        
        @auth
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-footer-btn needs-confirmation" data-message="Anda yakin ingin keluar?">
                <i class="fa-solid fa-arrow-right-from-bracket" style="font-size:0.85rem;"></i>
                Keluar
            </button>
        </form>
        @endauth
    </div>
</aside>

<!-- ====== TOPBAR ====== -->
<div class="topbar" id="topbar">
    <div class="topbar-left">
        <button class="topbar-toggle" onclick="toggleSidebar()" title="Toggle Sidebar">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div>
            <div class="topbar-page-title" id="topbarTitle">@yield('page_title', 'Dashboard')</div>
            <div class="topbar-breadcrumb" id="topbarBreadcrumb">{{ $appSettings->site_name }} / @yield('page_title', 'Dashboard')</div>
        </div>
    </div>
    <div class="topbar-right">
        @yield('topbar_actions')
    </div>
</div>

<!-- ====== MAIN CONTENT ====== -->
<main class="dashboard-main" id="dashboardMain">

    @yield('content')
</main>

<script>
// Pastikan selalu light mode & bersihkan settingan tema sebelumnya
localStorage.removeItem('theme');
document.documentElement.classList.remove('dark');
document.body.classList.remove('dark');

// ====== Sidebar Toggle ======
let sidebarOpen = window.innerWidth >= 1024;

function toggleSidebar() {
    if (window.innerWidth >= 1024) {
        sidebarOpen = !sidebarOpen;
        document.getElementById('sidebar').classList.toggle('collapsed', !sidebarOpen);
        document.getElementById('topbar').classList.toggle('sidebar-collapsed', !sidebarOpen);
        document.getElementById('dashboardMain').classList.toggle('sidebar-collapsed', !sidebarOpen);
    } else {
        document.getElementById('sidebar').classList.toggle('mobile-open');
        document.getElementById('sidebarOverlay').classList.toggle('visible', document.getElementById('sidebar').classList.contains('mobile-open'));
    }
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('mobile-open');
    document.getElementById('sidebarOverlay').classList.remove('visible');
}

// ====== Section Switcher ======
function switchSection(sectionId, title, breadcrumb) {
    // Hide all sections
    document.querySelectorAll('.dash-section').forEach(s => s.classList.remove('active'));
    // Show target
    const target = document.getElementById(sectionId);
    if (target) target.classList.add('active');

    // Update topbar
    if (title) document.getElementById('topbarTitle').textContent = title;
    if (breadcrumb) document.getElementById('topbarBreadcrumb').textContent = '{{ $appSettings->site_name }} / ' + breadcrumb;

    // Update sidebar active state
    // Note: Active state for sidebar items is now managed by Blade on page load
    // The current switchSection function should only manage dash-section visibility and topbar
    // document.querySelectorAll('.sidebar-item[data-section]').forEach(btn => {
    //     btn.classList.toggle('active', btn.dataset.section === sectionId);
    // });

    // Close mobile sidebar
    if (window.innerWidth < 1024) closeSidebar();

    // Save active section
    sessionStorage.setItem('activeDashSection', sectionId);
}

// Init: restore last section or activate first
document.addEventListener('DOMContentLoaded', function() {
    const saved = sessionStorage.getItem('activeDashSection');
    const firstBtn = document.querySelector('.sidebar-item[data-section]');
    const firstSection = firstBtn ? firstBtn.dataset.section : null;
    const targetSection = (saved && document.getElementById(saved)) ? saved : firstSection;

    if (targetSection) {
        const btn = document.querySelector(`.sidebar-item[data-section="${targetSection}"]`);
        const title = btn ? btn.dataset.title : document.title;
        const breadcrumb = btn ? btn.dataset.breadcrumb : '';
        switchSection(targetSection, title, breadcrumb);
    }
    
    // Swal Session Handler
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3500,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    @if(session('success'))
        Toast.fire({
            icon: 'success',
            title: '{{ session('success') }}'
        })
    @endif
    @if(session('error'))
        Toast.fire({
            icon: 'error',
            title: '{{ session('error') }}'
        })
    @endif

    // Swal confirmation for forms
    document.body.addEventListener('submit', function(e) {
        const form = e.target;
        if (form.matches('form.needs-confirmation')) {
            e.preventDefault();
            const message = form.dataset.message || 'Anda yakin ingin melanjutkan?';
            
            Swal.fire({
                title: 'Konfirmasi Tindakan',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    });
});
</script>
@stack('scripts')
</body>
</html>

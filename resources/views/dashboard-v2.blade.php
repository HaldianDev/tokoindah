@php
    $appSettings = \App\Models\WebSetting::getSettings();
@endphp
@php
    $appSettings = \App\Models\WebSetting::getSettings();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-Role Dashboard | {{ $appSettings->site_name }}</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }
        
        /* Sidebar Styles */
        #sidebar {
            width: 250px;
            min-height: 100vh;
            background-color: #212529;
            transition: all 0.3s;
            z-index: 1000;
        }
        
        .sidebar-link {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: 0.2s;
        }
        
        .sidebar-link:hover, .sidebar-link.active {
            color: #fff;
            background-color: rgba(255,255,255,0.1);
        }
        
        /* Main Content Adjustments */
        #main-wrapper {
            display: flex;
            width: 100%;
        }
        
        #content-area {
            flex-grow: 1;
            transition: all 0.3s;
        }

        /* Card Customization */
        .stat-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        /* Mobile Sidebar Toggle */
        @media (max-width: 767.98px) {
            #sidebar {
                display: none;
            }
        }
    </style>
</head>
<body>

<div id="main-wrapper">
    <!-- Desktop Sidebar -->
    <aside id="sidebar" class="d-none d-md-flex flex-column p-3 text-white">
        <a href="#" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <i class="bi bi-box-seam-fill fs-3 me-2 text-primary"></i>
            <span class="fs-4 fw-bold">{{ $appSettings->site_name }}</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="#" class="sidebar-link active">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="#" class="sidebar-link">
                    <i class="bi bi-box"></i> Produk
                </a>
            </li>
            <li>
                <a href="#" class="sidebar-link">
                    <i class="bi bi-cart"></i> Pesanan
                </a>
            </li>
            <li>
                <a href="#" class="sidebar-link">
                    <i class="bi bi-people"></i> Pelanggan
                </a>
            </li>
            <li>
                <a href="#" class="sidebar-link">
                    <i class="bi bi-gear"></i> Pengaturan
                </a>
            </li>
        </ul>
        <hr>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
                <strong>User Profile</strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                <li><a class="dropdown-item" href="#">Profil</a></li>
                <li><a class="dropdown-item" href="#">Logout</a></li>
            </ul>
        </div>
    </aside>

    <!-- Content Area -->
    <div id="content-area">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top">
            <div class="container-fluid">
                <!-- Mobile Toggle Button (Offcanvas) -->
                <button class="btn btn-outline-dark d-md-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
                    <i class="bi bi-list"></i>
                </button>
                
                <span class="navbar-brand d-md-none fw-bold">{{ $appSettings->site_name }}</span>

                <div class="ms-auto d-flex align-items-center gap-3">
                    <!-- Role Switcher Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle fw-bold" type="button" id="roleSwitcher" data-bs-toggle="dropdown">
                            <i class="bi bi-person-badge me-1"></i> Ganti Role
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><a class="dropdown-item role-btn" href="#" data-role="pembeli"><i class="bi bi-bag me-2"></i> Pembeli</a></li>
                            <li><a class="dropdown-item role-btn" href="#" data-role="admin"><i class="bi bi-shield-lock me-2"></i> Admin</a></li>
                            <li><a class="dropdown-item role-btn" href="#" data-role="owner"><i class="bi bi-gem me-2"></i> Owner</a></li>
                        </ul>
                    </div>
                    
                    <div class="vr mx-2 d-none d-sm-block"></div>
                    
                    <span class="d-none d-sm-inline fw-semibold text-muted">Role Aktif: <span id="activeRoleName" class="text-primary fw-bold">Admin</span></span>
                </div>
            </div>
        </nav>

        <!-- Main Dashboard Content -->
        <main class="p-3 p-md-4">
            
            <!-- SECTION: PEMBELI -->
            <section id="role-pembeli" class="role-section d-none">
                <h4 class="fw-bold mb-4">Dashboard Pembeli</h4>
                
                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card stat-card p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted mb-1 small fw-bold">Total Belanja</p>
                                    <h5 class="mb-0 fw-bold">Rp 12.500.000</h5>
                                </div>
                                <div class="icon-box bg-primary-subtle text-primary">
                                    <i class="bi bi-wallet2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card stat-card p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted mb-1 small fw-bold">Pesanan Diproses</p>
                                    <h5 class="mb-0 fw-bold">3 Pesanan</h5>
                                </div>
                                <div class="icon-box bg-warning-subtle text-warning">
                                    <i class="bi bi-box-seam"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tracker Status -->
                <div class="card stat-card mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Status Pesanan Terakhir #ORD-2026-001</h6>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 75%"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2 small text-muted">
                            <span>Dibayar</span>
                            <span>Dikemas</span>
                            <span class="fw-bold text-primary">Dikirim</span>
                            <span>Selesai</span>
                        </div>
                    </div>
                </div>

                <!-- History Table -->
                <div class="card stat-card">
                    <div class="card-header bg-white py-3">
                        <h6 class="fw-bold mb-0">Riwayat Transaksi</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID Pesanan</th>
                                        <th>Tanggal</th>
                                        <th>Produk</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>#ORD-001</td>
                                        <td>14 Aug 2026</td>
                                        <td>Keramik Granit 60x60</td>
                                        <td>Rp 5.200.000</td>
                                        <td><span class="badge bg-success">Selesai</span></td>
                                    </tr>
                                    <tr>
                                        <td>#ORD-002</td>
                                        <td>15 Aug 2026</td>
                                        <td>Keramik Mozaik Dapur</td>
                                        <td>Rp 2.100.000</td>
                                        <td><span class="badge bg-primary">Dikirim</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECTION: ADMIN -->
            <section id="role-admin" class="role-section">
                <h4 class="fw-bold mb-4">Dashboard Admin</h4>

                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card stat-card p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted mb-1 small fw-bold">Pesanan Masuk</p>
                                    <h5 class="mb-0 fw-bold">45 Baru</h5>
                                </div>
                                <div class="icon-box bg-info-subtle text-info">
                                    <i class="bi bi-cart-plus"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card stat-card p-3 border-start border-danger border-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted mb-1 small fw-bold">Stok Menipis</p>
                                    <h5 class="mb-0 fw-bold text-danger">8 Produk</h5>
                                </div>
                                <div class="icon-box bg-danger-subtle text-danger">
                                    <i class="bi bi-exclamation-triangle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Management Table -->
                <div class="card stat-card">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0">Manajemen Pesanan</h6>
                        <button class="btn btn-sm btn-outline-primary">Lihat Semua</button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Pelanggan</th>
                                        <th>ID Pesanan</th>
                                        <th>Metode</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="fw-bold small">Andi Wijaya</div>
                                            </div>
                                        </td>
                                        <td>#ADM-992</td>
                                        <td>Transfer Bank</td>
                                        <td><span class="badge bg-warning text-dark">Menunggu Verifikasi</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-success"><i class="bi bi-check-lg"></i></button>
                                            <button class="btn btn-sm btn-danger"><i class="bi bi-x-lg"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Budi Santoso</td>
                                        <td>#ADM-993</td>
                                        <td>Cash / Offline</td>
                                        <td><span class="badge bg-info">Diproses</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-primary"><i class="bi bi-truck"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECTION: OWNER -->
            <section id="role-owner" class="role-section d-none">
                <h4 class="fw-bold mb-4">Dashboard Owner</h4>

                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card stat-card p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted mb-1 small fw-bold">Total Revenue</p>
                                    <h5 class="mb-0 fw-bold text-success">Rp 450.2M</h5>
                                </div>
                                <div class="icon-box bg-success-subtle text-success">
                                    <i class="bi bi-graph-up-arrow"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card stat-card p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted mb-1 small fw-bold">Net Profit</p>
                                    <h5 class="mb-0 fw-bold text-primary">Rp 120.5M</h5>
                                </div>
                                <div class="icon-box bg-primary-subtle text-primary">
                                    <i class="bi bi-cash-stack"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <!-- Chart Placeholder -->
                    <div class="col-12 col-lg-8">
                        <div class="card stat-card h-100">
                            <div class="card-header bg-white py-3">
                                <h6 class="fw-bold mb-0">Statistik Penjualan Tahunan</h6>
                            </div>
                            <div class="card-body">
                                <div style="height: 300px;" class="d-flex align-items-center justify-content-center bg-light border rounded">
                                    <canvas id="revenueChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Top Products -->
                    <div class="col-12 col-lg-4">
                        <div class="card stat-card h-100">
                            <div class="card-header bg-white py-3">
                                <h6 class="fw-bold mb-0">Top Selling Products</h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <div class="small fw-bold">Keramik Putih 60x60</div>
                                        <span class="badge bg-primary rounded-pill">1.2k Sold</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <div class="small fw-bold">Granit Grey 80x80</div>
                                        <span class="badge bg-primary rounded-pill">850 Sold</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <div class="small fw-bold">Mozaik Dapur Pastel</div>
                                        <span class="badge bg-primary rounded-pill">420 Sold</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </main>
    </div>
</div>

<!-- Mobile Sidebar (Offcanvas) -->
<div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="mobileSidebar">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title fw-bold">{{ $appSettings->site_name }}</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="#" class="sidebar-link active">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <!-- ... same links as desktop ... -->
            <li><a href="#" class="sidebar-link"><i class="bi bi-box"></i> Produk</a></li>
            <li><a href="#" class="sidebar-link"><i class="bi bi-cart"></i> Pesanan</a></li>
        </ul>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // 1. Role Switching Logic
    const roleButtons = document.querySelectorAll('.role-btn');
    const sections = document.querySelectorAll('.role-section');
    const activeRoleName = document.getElementById('activeRoleName');

    roleButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const role = this.getAttribute('data-role');
            
            // Update Active Name
            activeRoleName.innerText = role.charAt(0).toUpperCase() + role.slice(1);

            // Hide all sections
            sections.forEach(sec => sec.classList.add('d-none'));

            // Show selected section
            const targetSection = document.getElementById(`role-${role}`);
            if(targetSection) {
                targetSection.classList.remove('d-none');
            }

            // Optional: Re-init chart if Owner is selected
            if(role === 'owner') {
                renderChart();
            }
        });
    });

    // 2. Chart.js Implementation
    let myChart = null;
    function renderChart() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        // Destroy existing chart if any (to avoid overlap)
        if(myChart) {
            myChart.destroy();
        }

        myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Revenue (dalam Juta)',
                    data: [12, 19, 15, 25, 22, 30],
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    // Init chart on load if default role is owner (it's admin now)
    // renderChart(); 
</script>

</body>
</html>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Peminjaman Barang Lab RSI - @yield('title')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <style>
        /* ===== ROOT VARIABLES ===== */
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --info-color: #17a2b8;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
        }

        /* ===== GLOBAL STYLES ===== */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }

        /* ===== SIDEBAR STYLES ===== */
        .sidebar-wrapper {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: 250px;
            background: linear-gradient(180deg, #2c3e50 0%, #1a252f 100%);
            z-index: 1000;
            overflow-y: auto;
        }

        .content-wrapper-with-sidebar {
            margin-left: 250px;
            width: calc(100% - 250px);
            min-height: 100vh;
            padding: 20px;
        }

        .sidebar-wrapper a {
            color: #ecf0f1;
            text-decoration: none;
            transition: all 0.3s;
            border-radius: 5px;
            padding: 10px 15px;
            margin: 5px 10px;
            display: flex;
            align-items: center;
        }

        .sidebar-wrapper a:hover {
            background-color: #34495e;
            color: white;
            transform: translateX(5px);
        }

        .sidebar-wrapper a.active {
            background-color: var(--primary-color);
            color: white;
            font-weight: 500;
        }

        /* ===== NAVBAR STYLES ===== */
        .navbar-custom {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 15px 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* ===== CARD STYLES ===== */
        .card-custom {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .card-header-custom {
            background: white;
            border-bottom: 1px solid #eee;
            padding: 20px;
            font-weight: 600;
            font-size: 1.1rem;
        }

        /* ===== BUTTON STYLES ===== */
        .btn-custom {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-custom-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-custom-primary:hover {
            background: #2980b9;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
        }

        .btn-custom-success {
            background: var(--success-color);
            color: white;
        }

        .btn-custom-success:hover {
            background: #27ae60;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3);
        }

        .btn-custom-warning {
            background: var(--warning-color);
            color: white;
        }

        .btn-custom-warning:hover {
            background: #e67e22;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(243, 156, 18, 0.3);
        }

        .btn-custom-danger {
            background: var(--danger-color);
            color: white;
        }

        .btn-custom-danger:hover {
            background: #c0392b;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
        }

        .btn-custom-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-custom-secondary:hover {
            background: #545b62;
            color: white;
            transform: translateY(-2px);
        }

        .btn-custom-outline {
            background: transparent;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
        }

        .btn-custom-outline:hover {
            background: var(--primary-color);
            color: white;
        }

        .btn-sm-custom {
            padding: 6px 12px;
            font-size: 0.875rem;
            border-radius: 6px;
        }

        /* ===== TABLE STYLES ===== */
        .table-custom {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }

        .table-custom thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            padding: 15px;
            font-weight: 600;
            color: #495057;
        }

        .table-custom tbody td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }

        .table-custom tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* ===== BADGE STYLES ===== */
        .badge-custom {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 500;
        }

        /* ===== FORM STYLES ===== */
        .form-control-custom {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 12px 15px;
            transition: all 0.3s;
        }

        .form-control-custom:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        .form-label-custom {
            font-weight: 500;
            margin-bottom: 8px;
            color: #495057;
        }

        /* ===== ALERT STYLES ===== */
        .alert-custom {
            border-radius: 10px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 20px;
        }

        /* ===== ACTION BUTTONS GROUP ===== */
        .btn-action-group {
            display: flex;
            gap: 8px;
        }

        .btn-action {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .btn-action:hover {
            transform: translateY(-2px);
        }

        /* ===== STATS CARDS ===== */
        .stat-card {
            border-left: 4px solid;
            padding-left: 20px;
        }

        .stat-card-primary {
            border-left-color: var(--primary-color);
        }

        .stat-card-success {
            border-left-color: var(--success-color);
        }

        .stat-card-warning {
            border-left-color: var(--warning-color);
        }

        .stat-card-danger {
            border-left-color: var(--danger-color);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .sidebar-wrapper {
                width: 70px;
            }

            .sidebar-wrapper a span:not(.bi) {
                display: none;
            }

            .content-wrapper-with-sidebar {
                margin-left: 70px;
                padding: 15px;
            }

            .navbar-custom {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
        }
    </style>

    @stack('styles')
    <!-- Di bagian bawah layout.app.blade.php, sebelum </body> -->
    <script>
        // Toggle Sidebar
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar-wrapper');
            const content = document.querySelector('.content-wrapper-with-sidebar');

            if (sidebar.style.width === '70px' || sidebar.style.width === '70px') {
                sidebar.style.width = '250px';
                content.style.marginLeft = '250px';
                content.style.width = 'calc(100% - 250px)';
            } else {
                sidebar.style.width = '70px';
                content.style.marginLeft = '70px';
                content.style.width = 'calc(100% - 70px)';

                // Sembunyikan teks menu
                document.querySelectorAll('.sidebar-wrapper a span:not(.bi)').forEach(span => {
                    span.style.display = 'none';
                });
            }
        }

        // Auto hide text on small screens
        window.addEventListener('resize', function() {
            if (window.innerWidth <= 768) {
                document.querySelector('.sidebar-wrapper').style.width = '70px';
                document.querySelector('.content-wrapper-with-sidebar').style.marginLeft = '70px';
                document.querySelector('.content-wrapper-with-sidebar').style.width = 'calc(100% - 70px)';

                document.querySelectorAll('.sidebar-wrapper a span:not(.bi)').forEach(span => {
                    span.style.display = 'none';
                });
            } else {
                document.querySelector('.sidebar-wrapper').style.width = '250px';
                document.querySelector('.content-wrapper-with-sidebar').style.marginLeft = '250px';
                document.querySelector('.content-wrapper-with-sidebar').style.width = 'calc(100% - 250px)';

                document.querySelectorAll('.sidebar-wrapper a span:not(.bi)').forEach(span => {
                    span.style.display = 'inline';
                });
            }
        });
    </script>
</head>

<body>
    @auth
        <!-- LAYOUT UNTUK USER YANG SUDAH LOGIN (DENGAN SIDEBAR) -->
        <div class="container-fluid p-0">
            <!-- Sidebar -->
            <div class="sidebar-wrapper">
                <div class="position-sticky pt-4">
                    <div class="text-center mb-4">
                        <h4 class="text-white mb-1">
                            <i class="bi bi-hospital"></i> Lab RSI
                        </h4>
                        <p class="text-secondary small mb-0">Sistem Peminjaman Barang</p>
                    </div>

                    <ul class="nav flex-column">
                        <li class="nav-item mb-2">
                            <a class="nav-link {{ request()->routeIs('barang.*') ? 'active text-dark bg-white rounded' : '' }}"
                                href="{{ route('barang.index') }}">
                                <i class="bi bi-box-seam me-2"></i> Data Barang
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link {{ request()->routeIs('peminjaman.*') ? 'active text-dark bg-white rounded' : '' }}"
                                href="{{ route('peminjaman.index') }}">
                                <i class="bi bi-calendar-check me-2"></i> Peminjaman
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link {{ request()->routeIs('mahasiswa.*') ? 'active text-dark bg-white rounded' : '' }}"
                                href="{{ route('mahasiswa.index') }}">
                                <i class="bi bi-people me-2"></i> Data Mahasiswa
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link {{ request()->routeIs('laporan.*') ? 'active text-dark bg-white rounded' : '' }}"
                                href="{{ route('laporan.index') }}">
                                <i class="bi bi-file-earmark-text me-2"></i> Laporan
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link {{ request()->routeIs('admin.*') ? 'active text-dark bg-white rounded' : '' }}"
                                href="{{ route('admin.manage') }}">
                                <i class="bi bi-person-gear me-2"></i> Manajemen Admin
                            </a>
                        </li>
                        <li class="nav-item mt-4 pt-3 border-top">
                            <a class="nav-link" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="content-wrapper-with-sidebar">
                <!-- Top Navbar -->
                <nav class="navbar-custom">
                    <div class="container-fluid">
                        <h4 class="mb-0">@yield('title', 'Dashboard')</h4>
                        <div class="d-flex align-items-center">
                            <span class="me-3 text-muted">
                                <i class="bi bi-person-circle me-1"></i>
                                {{ Auth::user()->name }}
                            </span>
                            <span class="badge bg-primary">
                                {{ Auth::user()->role ?? 'Admin' }}
                            </span>
                        </div>
                    </div>
                </nav>

                <!-- Page Content -->
                <div class="container-fluid">


                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    @else
        <!-- LAYOUT UNTUK GUEST (BELUM LOGIN) - TANPA SIDEBAR -->
        <div class="auth-wrapper">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6">
                        <div class="auth-card">
                            <div class="auth-header">
                                <h2><i class="bi bi-hospital me-2"></i> Lab RSI</h2>
                                <p class="mb-0">Sistem Peminjaman Barang</p>
                            </div>
                            <div class="card-body p-5">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);

            // Confirm delete
            function confirmDelete(formId) {
                if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                    document.getElementById(formId).submit();
                }
            }

            // Sidebar active link
            $(document).ready(function() {
                var currentUrl = window.location.pathname;
                $('.sidebar-wrapper a').each(function() {
                    if ($(this).attr('href') === currentUrl ||
                        (currentUrl.startsWith($(this).attr('href')) && $(this).attr('href') !== '/')) {
                        $(this).addClass('active');
                    } else {
                        $(this).removeClass('active');
                    }
                });
            });
        </script>

        @stack('scripts')
    </body>

    </html>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Panel Karyawan - Bengkel Las Asyraf')</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Favicon -->
    <link href="{{ asset('img/favicon.ico') }}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Roboto:wght@500;700;800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">

    <!-- Bootstrap CSS -->
    <link href="{{ asset('weldork-css/bootstrap.min.css') }}?v={{ filemtime(public_path('weldork-css/bootstrap.min.css')) }}" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
            color: #334155;
        }

        /* Sidebar Styling */
        .employee-sidebar {
            width: 260px;
            min-height: 100vh;
            background: #1e293b;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 12px rgba(0, 0, 0, 0.05);
        }

        .employee-sidebar .sidebar-brand {
            padding: 1.25rem 1.25rem;
            background: #0f172a;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .employee-sidebar .nav-link {
            color: rgba(255, 255, 255, 0.72);
            padding: 0.85rem 1.25rem;
            font-size: 0.9rem;
            font-weight: 500;
            border-left: 3px solid transparent;
            transition: all 0.2s ease-in-out;
            display: flex;
            align-items: center;
        }

        .employee-sidebar .nav-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.06);
            border-left-color: rgba(245, 158, 11, 0.5);
        }

        .employee-sidebar .nav-link.active {
            color: #ffffff;
            background: rgba(245, 158, 11, 0.15);
            border-left-color: #f59e0b;
            font-weight: 600;
        }

        .employee-sidebar .nav-link i {
            width: 24px;
            margin-right: 12px;
            text-align: center;
            font-size: 1rem;
        }

        /* Content Area */
        .employee-content {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .employee-topnav {
            background: #ffffff;
            padding: 0.85rem 1.75rem;
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .employee-main {
            padding: 1.75rem;
            flex-grow: 1;
        }

        /* Responsive Mobile Drawer */
        @media (max-width: 991.98px) {
            .employee-sidebar {
                margin-left: -260px;
            }

            .employee-sidebar.show {
                margin-left: 0;
                box-shadow: 10px 0 25px rgba(0, 0, 0, 0.25);
            }

            .employee-content {
                margin-left: 0;
            }

            .sidebar-backdrop {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(15, 23, 42, 0.5);
                z-index: 999;
            }

            .sidebar-backdrop.show {
                display: block;
            }
        }

        /* Cards & Components */
        .card-kpi {
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card-kpi:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        }

        /* Stat Icon Box */
        .stat-icon-box {
            width: 46px;
            height: 46px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            font-size: 1.25rem;
            flex-shrink: 0;
        }
        .stat-icon-box.warning {
            background-color: #fef3c7 !important;
            color: #d97706 !important;
        }
        .stat-icon-box.primary {
            background-color: #e0f2fe !important;
            color: #0284c7 !important;
        }
        .stat-icon-box.info {
            background-color: #cffafe !important;
            color: #0891b2 !important;
        }
        .stat-icon-box.success {
            background-color: #dcfce7 !important;
            color: #16a34a !important;
        }

        /* Form Controls */
        .form-control, .form-select {
            color: #334155 !important;
            border-color: #cbd5e1;
        }
        .form-control:focus, .form-select:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 0.25rem rgba(245, 158, 11, 0.2);
        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- Mobile Backdrop -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <!-- Sidebar -->
    @include('components.employee.sidebar')

    <!-- Content Wrapper -->
    <div class="employee-content">
        <!-- Top Navbar -->
        @include('components.employee.topnav')

        <!-- Main Content -->
        <main class="employee-main">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle fs-5 me-2"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle fs-5 me-2"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle mobile sidebar
        const sidebar = document.querySelector('.employee-sidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        const toggleBtn = document.getElementById('employeeSidebarToggle');

        function toggleSidebar() {
            if (sidebar && backdrop) {
                sidebar.classList.toggle('show');
                backdrop.classList.toggle('show');
            }
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', toggleSidebar);
        }
        if (backdrop) {
            backdrop.addEventListener('click', toggleSidebar);
        }
    </script>
    @stack('scripts')
</body>

</html>

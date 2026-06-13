<div class="admin-sidebar">
    <div class="sidebar-brand">
        <h4><i class="fas fa-fire me-2"></i>BENGKEL ASYRAF</h4>
        <small class="text-muted">Panel Admin</small>
    </div>
    <nav class="mt-3">
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Request::is('admin/dashboard*') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="{{ route('admin.orders.index') }}" class="nav-link {{ Request::is('admin/orders*') ? 'active' : '' }}">
            <i class="fas fa-clipboard-list"></i> Pesanan
        </a>
        <a href="{{ route('admin.payments.index') }}" class="nav-link {{ Request::is('admin/payments*') ? 'active' : '' }}">
            <i class="fas fa-money-bill-wave"></i> Pembayaran
        </a>
        <a href="{{ route('admin.catalog.index') }}" class="nav-link {{ Request::is('admin/catalog*') ? 'active' : '' }}">
            <i class="fas fa-boxes"></i> Katalog Produk
        </a>
        <a href="{{ route('admin.reports.index') }}" class="nav-link {{ Request::is('admin/reports*') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i> Laporan Transaksi
        </a>

        <hr class="border-secondary mx-3">

        <a href="{{ route('home') }}" class="nav-link" target="_blank">
            <i class="fas fa-external-link-alt"></i> Lihat Website
        </a>
    </nav>
</div>

<style>
    .sidebar {
        width: 280px;
        background-color: #071324;
        color: white;
        padding: 24px 0;
        display: flex;
        flex-direction: column;
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        overflow-y: auto;
        z-index: 100;
        transform: translateX(0);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: none;
    }

    body:not(.sidebar-collapsed) .sidebar {
        box-shadow: 6px 0 24px rgba(7, 19, 36, 0.18);
    }

    body.sidebar-collapsed .sidebar {
        transform: translateX(-100%);
        box-shadow: none;
    }

    .logo-section {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 0 24px;
        margin-bottom: 20px;
    }

    .logo {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .logo-text {
        flex: 1;
    }

    .logo-text h1 {
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 4px;
        color: white;
    }

    .logo-text p {
        font-size: 12px;
        font-weight: 400;
        opacity: 0.8;
        color: white;
    }

    .divider {
        height: 1px;
        background-color: rgba(255, 255, 255, 0.1);
        margin: 0 24px 20px;
    }

    .menu-section {
        padding: 0 24px;
        margin-bottom: 20px;
    }

    .menu-title {
        font-size: 11px;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.5);
        margin-bottom: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .menu-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        cursor: pointer;
        transition: all 0.2s;
        border-radius: 6px;
        margin-bottom: 4px;
        text-decoration: none;
        color: rgba(255, 255, 255, 0.8);
    }

    .menu-item.active {
        background-color: #701229;
        color: white;
    }

    .menu-item:hover:not(.active) {
        background-color: rgba(255, 255, 255, 0.05);
        color: white;
    }

    .menu-icon {
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .menu-icon i {
        font-size: 16px;
    }

    .menu-text {
        font-size: 14px;
        font-weight: 500;
    }

    @media (max-width: 991.98px) {
        .sidebar {
            width: 260px;
        }
    }

    @media (max-width: 576px) {
        .sidebar {
            width: 240px;
        }
    }
</style>

<div class="sidebar">
    <div class="logo-section">
        <div class="logo">
            <img alt="Logo" src="{{ asset('assets/media/logos/logo.png') }}" />
        </div>
        <div class="logo-text">
            <h1>BPKAD</h1>
            <p>Provinsi Kalimantan Timur</p>
        </div>
    </div>
    
    <div class="divider"></div>
    
    <div class="menu-section">
        <div class="menu-title">Menu Utama</div>
        <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <div class="menu-icon">
                <i class="fas fa-th-large"></i>
            </div>
            <div class="menu-text">Dashboard</div>
        </a>
    </div>
    
    <div class="menu-section">
        <div class="menu-title">Manajemen Aset</div>
        <a href="{{ route('admin.asset.index') }}" class="menu-item {{ request()->routeIs('admin.asset.index') ? 'active' : '' }}">
            <div class="menu-icon">
                <i class="fas fa-boxes"></i>
            </div>
            <div class="menu-text">Daftar Asset</div>
        </a>
        <a href="{{ route('admin.asset.peta-persebaran') }}" class="menu-item {{ request()->routeIs('admin.asset.peta-persebaran') ? 'active' : '' }}">
            <div class="menu-icon">
                <i class="fas fa-map-marked-alt"></i>
            </div>
            <div class="menu-text">Peta Persebaran Tanah</div>
        </a>
        <a href="{{ route('admin.asset.rekapitulasi') }}" class="menu-item {{ request()->routeIs('admin.asset.rekapitulasi') ? 'active' : '' }}">
            <div class="menu-icon">
                <i class="fas fa-chart-bar"></i>
            </div>
            <div class="menu-text">Rekapitulasi Asset Tanah</div>
        </a>
      <a href="{{ route('admin.dokumen-asset.index') }}" class="menu-item {{ request()->routeIs('admin.dokumen-asset.*') ? 'active' : '' }}">
        <div class="menu-icon"><i class="fas fa-file-alt"></i></div>
        <div class="menu-text">Kelola Dokumen</div>
    </a>
    <a href="{{ route('admin.kategori-asset.index') }}" class="menu-item {{ request()->routeIs('admin.kategori-asset.*') ? 'active' : '' }}">
            <div class="menu-icon">
                <i class="fas fa-tags"></i>
            </div>
            <div class="menu-text">Kategori Asset</div>
        </a>
        <a href="{{ route('admin.status-hukum-asset.index') }}" class="menu-item {{ request()->routeIs('admin.status-hukum-asset.*') ? 'active' : '' }}">
            <div class="menu-icon">
                <i class="fas fa-gavel"></i>
            </div>
            <div class="menu-text">Status Hukum</div>
        </a>
        <a href="{{ route('admin.unit-kerja.index') }}" class="menu-item {{ request()->routeIs('admin.unit-kerja.*') ? 'active' : '' }}">
            <div class="menu-icon">
                <i class="fas fa-building"></i>
            </div>
            <div class="menu-text">Unit Kerja Pengelola</div>
        </a>
    </div>
    
    <div class="menu-section">
        <div class="menu-title">Konfigurasi</div>
        <a href="{{ route('admin.hak-akses.role.index') }}" class="menu-item {{ request()->is('admin/hak-akses/role*') ? 'active' : '' }}">
            <div class="menu-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div class="menu-text">Role</div>
        </a>
       
        <a href="{{ route('admin.hak-akses.user.index') }}" class="menu-item {{ request()->is('admin/hak-akses/user*') ? 'active' : '' }}">
            <div class="menu-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="menu-text">User</div>
        </a>
        
    </div>
</div>

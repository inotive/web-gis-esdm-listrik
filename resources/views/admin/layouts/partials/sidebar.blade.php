@php
    // Helper kecil untuk tandai item aktif berdasarkan pola nama route
    if (!function_exists('nav_active')) {
        function nav_active(...$patterns)
        {
            foreach ($patterns as $p) {
                if (request()->routeIs($p)) {
                    return 'active';
                }
            }
            return '';
        }
    }
@endphp

<aside class="sidebar" aria-label="Sidebar navigasi">
    <!-- Topbar di dalam sidebar (sinkron dgn header) -->
    <div class="sidebar-topbar">
        <div class="brand">
            <img class="logo" src="{{ asset('assets/media/logos/logo.png') }}" alt="Logo Dinas ESDM" />
            <div class="brand-text">
                <strong>Dinas ESDM</strong>
                <span>Provinsi Kalimantan Timur</span>
            </div>
        </div>
    </div>

    <!-- Menu utama -->
    <nav class="menu-section" aria-label="Menu Utama Sidebar">
        <div class="menu-title">Menu Utama</div>

        <a class="menu-item {{ nav_active('admin.dashboard') }}" href="{{ route('admin.dashboard') }}"
            @if (nav_active('admin.dashboard')) aria-current="page" @endif>
            <span class="menu-icon"><i class="ri-dashboard-line" aria-hidden="true"></i></span>
            <span class="menu-label">Dashboards</span>
        </a>

        <a class="menu-item {{ request()->routeIs('admin.survey.*') ? 'active' : '' }}"
            href="{{ route('admin.survey.index') }}">
            <span class="menu-icon"><i class="ri-map-2-line" aria-hidden="true"></i></span>
            <span class="menu-label">Peta Persebaran</span>
        </a>

        <a class="menu-item"
            href="">
            <span class="menu-icon"><i class="ri-file-text-line" aria-hidden="true"></i></span>
            <span class="menu-label">Dokumen</span>
        </a>

        {{-- <a class="menu-item {{ nav_active('admin.pemukiman.*') }}" href="{{ route('admin.pemukiman.index') }}">
            <span class="menu-icon"><i class="ri-home-2-line" aria-hidden="true"></i></span>
            <span class="menu-label">Pemukiman Tanpa Listrik</span>
        </a> --}}

        {{-- <a class="menu-item {{ nav_active('admin.gis.*') }}" href="{{ route('admin.gis.index') }}">
            <span class="menu-icon"><i class="ri-stack-line" aria-hidden="true"></i></span>
            <span class="menu-label">GIS &amp; Layer Management</span>
        </a> --}}
    </nav>

    <!-- separator -->
    <hr class="menu-sep" />

    <!-- Konfigurasi -->
    <nav class="menu-section" aria-label="Konfigurasi">
        <div class="menu-title">Konfigurasi</div>

        <a class="menu-item {{ nav_active('admin.data-wilayah.*') }}" href="{{ route('admin.data-wilayah.index') }}">
            <span class="menu-icon"><i class="ri-map-pin-line" aria-hidden="true"></i></span>
            <span class="menu-label">Data Wilayah</span>
        </a>

        <a class="menu-item {{ nav_active('admin.pelanggan.*') }}" href="{{ route('admin.pelanggan.index') }}">
            <span class="menu-icon"><i class="ri-team-line" aria-hidden="true"></i></span>
            <span class="menu-label">Data Pelanggan</span>
        </a>

        <a class="menu-item {{ request()->routeIs('admin.infrastruktur.*') ? 'active' : '' }}"
            href="{{ route('admin.infrastruktur.index') }}">
            <span class="menu-icon"><i class="ri-plug-line"></i></span>
            <span class="menu-label">Data Infrastruktur Jaringan</span>
        </a>

        <a class="menu-item {{ nav_active('admin.gardu.*') }}" href="{{ route('admin.gardu.index') }}">
            <span class="menu-icon"><i class="ri-base-station-line" aria-hidden="true"></i></span>
            <span class="menu-label">Data Gardu</span>
        </a>

        <a class="menu-item {{ nav_active('admin.pembangkit.*') }}" href="{{ route('admin.pembangkit.index') }}">
            <span class="menu-icon"><i class="ri-building-4-line" aria-hidden="true"></i></span>
            <span class="menu-label">Data Pembangkit Lokal</span>
        </a>

        <a class="menu-item {{ nav_active('admin.jalan.*') }}" href="{{ route('admin.jalan.index') }}">
            <span class="menu-icon"><i class="ri-road-map-line" aria-hidden="true"></i></span>
            <span class="menu-label">Data Jalan &amp; Aksesbilitas</span>
        </a>

        <a class="menu-item {{ nav_active('admin.skoring.*') }}" href="{{ route('admin.skoring.index') }}">
            <span class="menu-icon"><i class="ri-slideshow-2-line" aria-hidden="true"></i></span>
            <span class="menu-label">Variabel Skoring &amp; Bobot</span>
        </a>

        <a class="menu-item {{ nav_active('admin.hak-akses.user.*', 'admin.hak-akses.role.*', 'admin.hak-akses.permission.*') }}"
            href="{{ route('admin.hak-akses.user.index') }}">
            <span class="menu-icon"><i class="ri-user-settings-line" aria-hidden="true"></i></span>
            <span class="menu-label">Manajemen Pengguna</span>
        </a>
        <a class="menu-item {{ nav_active('admin.hak-akses.role.*', 'admin.hak-akses.role.*', 'admin.hak-akses.permission.*') }}"
            href="{{ route('admin.hak-akses.role.index') }}">
            <span class="menu-icon"><i class="ri-user-settings-line" aria-hidden="true"></i></span>
            <span class="menu-label">Role</span>
        </a>
    </nav>
</aside>

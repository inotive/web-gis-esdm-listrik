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

    // Get user role
    $userRole = Auth::check() ? Auth::user()->roles()->first()->name ?? null : null;
@endphp

<aside class="sidebar" aria-label="Sidebar navigasi">
    <!-- Topbar di dalam sidebar (sinkron dgn header) -->
    <div class="sidebar-topbar">
        <a href="{{ url('/') }}" class="brand" style="text-decoration: none; color: inherit;">
            <img class="logo" src="{{ asset('assets/media/logos/logo.png') }}" alt="Logo Dinas ESDM" />
            <div class="brand-text">
                <strong>Dinas ESDM</strong>
                <span>Provinsi Kalimantan Timur</span>
            </div>
        </a>
    </div>

    <!-- Menu utama -->
    <nav class="menu-section" aria-label="Menu Utama Sidebar">
        <div class="menu-title">Menu Utama</div>

        @can('dashboard.view')
            <a class="menu-item {{ nav_active('admin.dashboard') }}" href="{{ route('admin.dashboard') }}"
                @if (nav_active('admin.dashboard')) aria-current="page" @endif>
                <span class="menu-icon"><i class="ri-dashboard-line" aria-hidden="true"></i></span>
                <span class="menu-label">Dashboards</span>
            </a>
        @endcan

        <a class="menu-item {{ request()->routeIs('landing') ? 'active' : '' }}" href="{{ route('landing') }}">
            <span class="menu-icon"><i class="ri-map-2-line" aria-hidden="true"></i></span>
            <span class="menu-label">Peta Persebaran</span>
        </a>

        <a class="menu-item {{ nav_active('admin.dokumen.*') }}" href="{{ route('admin.dokumen.index') }}">
            <span class="menu-icon"><i class="ri-file-text-line" aria-hidden="true"></i></span>
            <span class="menu-label">Dokumen</span>
        </a>


        @if (!in_array($userRole ?? null, ['desa', 'perusahaan']))
            <a class="menu-item {{ nav_active('admin.permohonan.*') }}" href="{{ route('admin.permohonan.index') }}">
                <span class="menu-icon"><i class="ri-file-list-3-line" aria-hidden="true"></i></span>
                <span class="menu-label">Perizinan dan Permohonan</span>
            </a>
        @endif

        <a class="menu-item {{ nav_active('admin.rekap-data.*') }}" href="{{ route('admin.rekap-data.index') }}">
            <span class="menu-icon"><i class="ri-file-text-line" aria-hidden="true"></i></span>
            <span class="menu-label">Rekap Data</span>
        </a>

        @if (in_array($userRole ?? null, ['desa', 'perusahaan']))
            <div class="menu-title">Layanan</div>
            <a class="menu-item {{ nav_active('admin.pengajuan-permohonan.*') }}"
                href="{{ route('admin.pengajuan-permohonan.index') }}">
                <span class="menu-icon"><i class="ri-file-add-line" aria-hidden="true"></i></span>
                <span class="menu-label">Pengajuan Permohonan</span>
            </a>
        @endif


        {{-- <a class="menu-item {{ nav_active('admin.pemukiman.*') }}" href="{{ route('admin.pemukiman.index') }}">
            <span class="menu-icon"><i class="ri-home-2-line" aria-hidden="true"></i></span>sad
            <span class="menu-label">Pemukiman Tanpa Listrik</span>
        </a> --}}

        {{-- <a class="menu-item {{ nav_active('admin.gis.*') }}" href="{{ route('admin.gis.index') }}">
            <span class="menu-icon"><i class="ri-stack-line" aria-hidden="true"></i></span>
            <span class="menu-label">GIS &amp; Layer Management</span>
        </a> --}}
    </nav>

    <!-- separator -->
    @if (!in_array($userRole ?? null, ['desa', 'perusahaan']))
        <hr class="menu-sep" />
    @endif

    <!-- Konfigurasi -->
    @if (!in_array($userRole ?? null, ['desa', 'perusahaan']))
        <nav class="menu-section" aria-label="Konfigurasi">
            <div class="menu-title">Konfigurasi</div>

            <!-- <a class="menu-item {{ nav_active('admin.data-wilayah.*') }}" href="{{ route('admin.data-wilayah.index') }}">
            <span class="menu-icon"><i class="ri-map-pin-line" aria-hidden="true"></i></span>
            <span class="menu-label">Data Wilayah</span>
        </a> -->

            <a class="menu-item {{ nav_active('admin.desa.*') }}" href="{{ route('admin.desa.index') }}">
                <span class="menu-icon"><i class="ri-home-3-line" aria-hidden="true"></i></span>
                <span class="menu-label">Data Desa</span>
            </a>

            <a class="menu-item {{ nav_active('admin.perusahaan.*') }}" href="{{ route('admin.perusahaan.index') }}">
                <span class="menu-icon"><i class="ri-building-line" aria-hidden="true"></i></span>
                <span class="menu-label">Data Perusahaan</span>
            </a>

            <!-- <a class="menu-item {{ nav_active('admin.pelanggan.*') }}" href="{{ route('admin.pelanggan.index') }}">
            <span class="menu-icon"><i class="ri-team-line" aria-hidden="true"></i></span>
            <span class="menu-label">Data Pelanggan</span>
        </a> -->

            <a class="menu-item {{ nav_active('admin.data-infrastruktur.*', 'admin.infrastruktur.*', 'admin.gardu.*', 'admin.pembangkit.*') }}"
                href="{{ route('admin.data-infrastruktur.index') }}">
                <span class="menu-icon"><i class="ri-plug-line"></i></span>
                <span class="menu-label">Data Infrastruktur</span>
            </a>

            <a class="menu-item {{ nav_active('admin.jalan.*') }}" href="{{ route('admin.jalan.index') }}">
                <span class="menu-icon"><i class="ri-road-map-line" aria-hidden="true"></i></span>
                <span class="menu-label">Data Jalan &amp; Aksesbilitas</span>
            </a>

            <!-- <a class="menu-item {{ nav_active('admin.skoring.*') }}" href="{{ route('admin.skoring.index') }}">
            <span class="menu-icon"><i class="ri-slideshow-2-line" aria-hidden="true"></i></span>
            <span class="menu-label">Variabel Skoring &amp; Bobot</span>
        </a> -->
            <a class="menu-item {{ nav_active('admin.kategori-permohonan.*') }}"
                href="{{ route('admin.kategori-permohonan.index') }}">
                <span class="menu-icon"><i class="ri-file-list-3-line" aria-hidden="true"></i></span>
                <span class="menu-label">Kategori Permohonan</span>
            </a>


            @can('user.view')
                <a class="menu-item {{ nav_active('admin.hak-akses.user.*', 'admin.hak-akses.user.*', 'admin.hak-akses.permission.*') }}"
                    href="{{ route('admin.hak-akses.user.index') }}">
                    <span class="menu-icon"><i class="ri-user-settings-line" aria-hidden="true"></i></span>
                    <span class="menu-label">Manajemen Pengguna</span>
                </a>
            @endcan
            @can('role.view')
                <a class="menu-item {{ nav_active('admin.hak-akses.role.*', 'admin.hak-akses.role.*', 'admin.hak-akses.permission.*') }}"
                    href="{{ route('admin.hak-akses.role.index') }}">
                    <span class="menu-icon"><i class="ri-user-settings-line" aria-hidden="true"></i></span>
                    <span class="menu-label">Role</span>
                </a>
            @endcan
        </nav>
    @endif
</aside>

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

    <a class="menu-item active" href="#">
      <span class="menu-icon"><i class="ri-dashboard-line"></i></span>
      <span class="menu-label">Dashboards</span>
    </a>

    <a class="menu-item" href="#">
      <span class="menu-icon"><i class="ri-clipboard-line"></i></span>
      <span class="menu-label">Hasil Survey Lapangan</span>
    </a>

    <a class="menu-item" href="#">
      <span class="menu-icon"><i class="ri-home-2-line"></i></span>
      <span class="menu-label">Pemukiman Tanpa Listrik</span>
    </a>

    <a class="menu-item" href="#">
      <span class="menu-icon"><i class="ri-stack-line"></i></span>
      <span class="menu-label">GIS &amp; Layer Management</span>
    </a>
  </nav>

  <!-- separator -->
  <hr class="menu-sep" />

  <!-- konfigurasi -->
  <nav class="menu-section">
    <div class="menu-title">Konfigurasi</div>

    <a class="menu-item" href="{{ route('admin.data-wilayah.index') }}"><span class="menu-icon"><i class="ri-map-pin-line"></i></span><span class="menu-label">Data Wilayah</span></a>
    <a class="menu-item" href="{{ route('admin.pelanggan.index') }}"><span class="menu-icon"><i class="ri-team-line"></i></span><span class="menu-label">Data Pelanggan</span></a>
    <a class="menu-item" href="#"><span class="menu-icon"><i class="ri-plug-line"></i></span><span class="menu-label">Data Infrastruktur Jaringan</span></a>
    <a class="menu-item" href="#"><span class="menu-icon"><i class="ri-base-station-line"></i></span><span class="menu-label">Data Gardu</span></a>
    <a class="menu-item" href="#"><span class="menu-icon"><i class="ri-building-4-line"></i></span><span class="menu-label">Data Pembangkit Lokal</span></a>
    <a class="menu-item" href="#"><span class="menu-icon"><i class="ri-road-map-line"></i></span><span class="menu-label">Data Jalan &amp; Aksesbilitas</span></a>
    <a class="menu-item" href="#"><span class="menu-icon"><i class="ri-slideshow-2-line"></i></span><span class="menu-label">Variabel Skoring &amp; Bobot</span></a>
    <a class="menu-item" href="{{ route('admin.hak-akses.user.index')}}"><span class="menu-icon"><i class="ri-user-settings-line"></i></span><span class="menu-label">Manajemen Pengguna</span></a>
    <a class="menu-item" href="#"><span class="menu-icon"><i class="ri-settings-3-line"></i></span><span class="menu-label">Pengaturan Sistem</span></a>
  </nav>
</aside>

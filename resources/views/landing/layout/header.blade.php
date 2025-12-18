<header class="navbar">
  <div class="nav-left">
    @auth
      <button class="nav-burger" id="sidebarToggle" aria-label="Buka sidebar">
        <i class="ri-menu-line"></i>
      </button>
    @endauth
    <span class="brand-logo">
      {{-- Ganti file logo sesuai lokasi Anda di public/ --}}
      <img src="{{ asset('assets/media/logos/logo.png') }}" alt="Logo" onerror="this.style.display='none'">
    </span>
    <span class="brand-title">Dinas Energi dan Sumber Daya Mineral</span>
  </div>
  <div class="nav-right">
    @guest
      <a href="{{ route('login') }}" aria-label="Masuk">Login</a>
    @else
      <span class="nav-user">{{ auth()->user()->name }}</span>
    @endguest
  </div>
</header>

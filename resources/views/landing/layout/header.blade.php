<header class="navbar">
  <div class="nav-left">
    <span class="brand-logo">
      {{-- Ganti file logo sesuai lokasi Anda di public/ --}}
      <img src="{{ asset('assets/media/logos/logo.png') }}" alt="Logo" onerror="this.style.display='none'">
    </span>
    <span class="brand-title">ESDM</span>
  </div>
  <div class="nav-right">
    <a href="{{ route('login') }}" aria-label="Masuk">Login</a>
  </div>
</header>

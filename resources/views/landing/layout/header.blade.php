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
      <a href="{{ route('login') }}" class="btn-login" aria-label="Masuk">
        <span>Masuk</span>
      </a>
    @else
      <div class="user-menu">
        <button class="user-avatar" id="userMenuToggle" aria-label="Menu pengguna">
          <i class="ri-user-line"></i>
        </button>
        <div class="user-dropdown" id="userDropdown">
          <div class="user-info">
            <div class="user-icon">
              <i class="ri-user-fill"></i>
            </div>
            <div class="user-details">
              <div class="user-name">{{ auth()->user()->name }}</div>
              <div class="user-email">{{ auth()->user()->email }}</div>
            </div>
          </div>
          <div class="dropdown-divider"></div>
          <a href="{{ route('logout') }}" 
             onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
             class="dropdown-item">
            <i class="ri-logout-box-line"></i>
            <span>Keluar</span>
          </a>
        </div>
      </div>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
      </form>
    @endguest
  </div>
</header>

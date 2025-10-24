<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title', 'Dashboard ESDM - Provinsi Kalimantan Timur')</title>

  <!-- Inter font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <!-- Remix Icon -->
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">

  <style>
    :root{
      --bg-app:#F5F7FA; --bg-card:#FFFFFF; --text:#1F2937; --text-dim:#6B7280; --text-muted:#94A3B8;
      --line:#E5E7EB; --accent:#10B981; --accent-2:#22C55E; --accent-soft:#E8FFF4; --active-soft:#ECFDF5;
      --sidebar-w:270px; --radius-card:20px; --radius-small:10px;
      --shadow-1:0 1px 2px rgba(0,0,0,.04), 0 6px 20px rgba(2,6,23,.06);
      --shadow-2:0 6px 18px rgba(2,6,23,.06);
    }
    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0; font-family:'Inter', system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
      color:var(--text); background:var(--bg-app); letter-spacing:-.1px;
    }

    /* ========= Layout ========= */
    .layout{ display:flex; height:100vh; max-width:none; margin:0; gap:0; }
    .sidebar{
      width:var(--sidebar-w); flex:0 0 var(--sidebar-w); background:var(--bg-card);
      border-right:1px solid var(--line); padding:0; overflow:auto; display:flex; flex-direction:column;
      box-shadow:var(--shadow-2);
    }
    .sidebar-topbar{
      background:#fff; border-bottom:1px solid var(--line); padding:14px 16px; display:flex;
      align-items:center; gap:12px; flex-shrink:0;
    }
    .brand{ display:flex; align-items:center; gap:12px; min-width:260px; }
    .brand-logo{
      width:40px; height:40px; border-radius:8px; overflow:hidden; position:relative;
      background:linear-gradient(135deg,#00A859 0%,#128C7E 45%,#0D463E 100%);
      box-shadow:inset 0 0 0 2px rgba(255,255,255,.35);
    }
    .brand-logo:after{
      content:""; position:absolute; inset:8px; background:#F7D835; border-radius:6px; opacity:.9;
      box-shadow:0 0 0 2px rgba(0,0,0,.06) inset;
    }
    .brand-text strong{display:block; font-size:14px; line-height:1.1}
    .brand-text span{display:block; font-size:12px; color:var(--text-dim)}
    .search{ flex:1; display:flex; align-items:center; gap:10px; height:40px; padding:0 12px;
      border:1px solid #DBDFE9; border-radius:10px; background:#FCFCFD; max-width:600px; }
    .search i{font-size:18px; color:#94A3B8}
    .search input{ border:none; outline:none; flex:1; height:100%; background:transparent; font-size:14px; color:#0f172a; }
    .actions{ display:flex; align-items:center; gap:10px; }
    .btn-icon{ width:38px; height:38px; display:grid; place-items:center; border-radius:10px; border:1px solid var(--line); background:#fff; cursor:pointer; }
    .avatar{ width:38px; height:38px; border-radius:999px; overflow:hidden; border:2px solid var(--accent-2); background:#f9fafb; }

    .menu-section{ padding:6px 12px 10px; display:block; }
    .menu-title{ font-size:12px; color:#9CA3AF; text-transform:uppercase; letter-spacing:.4px; padding:8px 10px 6px; font-weight:600; }
    .menu-item{ display:flex; align-items:center; gap:12px; padding:10px 12px; margin:6px 0; border-radius:10px; color:#374151; text-decoration:none; transition:background .18s, transform .12s, color .12s; }
    .menu-item:hover{ background:#F5F7FA; transform:translateX(2px); color:#0F172A; }
    .menu-icon{ width:36px; height:36px; display:grid; place-items:center; border-radius:8px; background:#F6F7F9; color:#0F766E; flex-shrink:0; font-size:18px; }
    .menu-item .menu-label{ font-weight:600; font-size:14px; }
    .menu-item.active{ background:var(--active-soft); color:#0F5132; font-weight:700; position:relative; padding-left:14px; }
    .menu-item.active::before{ content:""; position:absolute; left:8px; top:8px; bottom:8px; width:4px; border-radius:6px; background:linear-gradient(180deg,var(--accent),var(--accent-2)); }
    .menu-item.active .menu-icon{ background:linear-gradient(180deg,#E8FFF4,#ECFDF5); color:var(--accent); }
    .menu-sep{ border:none; height:1px; background:linear-gradient(90deg, rgba(0,0,0,0.03), rgba(0,0,0,0)); margin:6px 12px; }
    .sidebar-footer{
      margin-top:auto; padding:14px 12px; border-top:1px dashed #EFF3F6;
      display:flex; align-items:center; justify-content:space-between; gap:8px;
    }
    .logo { width:40px; height:50px; }
    .profile-mini{ display:flex; align-items:center; gap:10px; }
    .avatar-mini{ width:40px; height:40px; border-radius:999px; border:2px solid var(--accent-2); }
    .profile-mini .name{ font-weight:700; font-size:13px; }
    .profile-mini .role{ font-size:12px; color:var(--text-dim); }
    .btn-logout{ border:none; background:transparent; color:var(--text-dim); font-weight:600; cursor:pointer; display:flex; align-items:center; gap:8px; }
    .btn-logout i{ font-size:20px; }

    .main{ flex:1; min-width:0; overflow-y:auto; padding:20px; }

    /* ========= Topbar ========= */
    .topbar{ display:flex; align-items:center; justify-content:space-between; gap:18px; padding:6px 4px 18px; margin-bottom:6px; }
    .topbar-left{ flex:1; display:flex; align-items:center; gap:16px; }
    .topbar .search{ max-width:480px; width:100%; box-shadow:inset 0 1px 2px rgba(15, 23, 42, .04); }
    .topbar .actions{ gap:12px; }
    .topbar .btn-icon{ background:#F8FAFC; border-color:#E2E8F0; transition:background .18s ease, border-color .18s ease; }
    .topbar .btn-icon:hover{ background:#EEF2F6; border-color:#CBD5E1; }

    /* ========= Content Header ========= */
    .page-head{ display:flex; justify-content:space-between; align-items:center; padding:6px 4px 2px 4px; margin-bottom:8px; }
    .page-meta{ color:#6B7280; font-size:13px; }
    .page-title{ font-size:28px; font-weight:800; margin-top:6px; }
    .page-actions{ display:flex; align-items:center; gap:12px; }
    .btn{ display:inline-flex; align-items:center; gap:8px; border:none; cursor:pointer; padding:10px 14px; border-radius:10px; font-weight:600; }
    .btn-primary{ background:var(--accent-2); color:#fff; box-shadow:0 6px 14px rgba(34,197,94,.22); }
    .btn-add{ box-shadow:0 12px 24px rgba(16,185,129,.18); white-space:nowrap; }
    .date-pill{ display:flex; align-items:center; gap:8px; border:1px solid #DDE3EA; padding:10px 12px; border-radius:10px; background:#FCFCFD; min-width:210px; justify-content:center; }
    .date-pill i{ color:#64748B; }

    /* ========= Cards ========= */
    .card{ background:var(--bg-card); border:1px solid var(--line); border-radius:var(--radius-card); box-shadow:var(--shadow-1); }
    .card-body{ padding:22px; }
    .card-header{ display:flex; justify-content:space-between; align-items:flex-start; gap:12px; padding:22px; border-bottom:1px solid var(--line); border-top-left-radius:var(--radius-card); border-top-right-radius:var(--radius-card); background:#fff; }
    .card-title{ font-weight:700; font-size:15px; color:#111827; }

    /* Area Chart */
    .chart-shell{
      position:relative; height:260px; border-radius:16px; margin:16px 0 16px; padding:14px 18px 18px;
      background:linear-gradient(180deg, rgba(34,197,94,.18) 0%, rgba(16,185,129,.08) 100%);
      box-shadow:inset 0 1px 2px rgba(15,23,42,.05);
    }
    .chart-shell canvas{ width:100%; height:100%; }

    /* Statistic chips */
    .stats{ display:grid; grid-template-columns:repeat(3,1fr); gap:14px; margin-top:10px; }
    .stat{ border:1px solid var(--line); border-radius:16px; padding:14px; display:flex; gap:12px; align-items:flex-start; background:#fff; }
    .stat .ico{ width:34px; height:34px; border-radius:8px; display:grid; place-items:center; background:#F6F7F9; color:#0F766E; }
    .stat .label{ font-size:14px; color:#334155; margin-bottom:4px; }
    .stat .val{ font-weight:700; font-size:18px; color:#0B7A4B; }

    /* Bars (Demografi) */
    .bars-wrap{ padding:10px 10px 8px; }
    .bar-grid{ display:flex; align-items:flex-end; justify-content:space-between; gap:18px; height:300px; margin:18px 8px 8px; overflow-x:auto; }
    .bar-col{ width:92px; display:flex; flex-direction:column; align-items:center; flex:0 0 92px; }
    .bar-stack{ display:flex; align-items:flex-end; gap:6px; margin-bottom:12px; }
    .bar{ width:24px; border-top-left-radius:6px; border-top-right-radius:6px; }
    .b-green{ background:linear-gradient(180deg,#7BD89E 0%, #10B981 100%); }
    .b-blue{ background:linear-gradient(180deg,#52A7E7 0%, #447ADB 100%); }
    .b-gray{ background:linear-gradient(180deg,#E5E7EB 0%, #C9CED6 100%); }
    .bar-label{ text-align:center; font-size:12px; font-weight:600; line-height:1.2; color:#475569; }

    .legend{ display:flex; gap:20px; padding:8px 4px 20px; flex-wrap:wrap; }
    .legend i{ font-size:14px; }
    .legend .dot{ width:14px; height:14px; border-radius:4px; display:inline-block; margin-right:8px; vertical-align:middle; }
    .lg-green{ background:#34D399; } .lg-blue{ background:#60A5FA; } .lg-gray{ background:#CBD5E1; }

    .muted{ color:var(--text-dim); }
    .num-xxl{ font-size:34px; font-weight:800; color:#047857; letter-spacing:-.5px; }

    /* Footer kecil */
    .app-footer{ margin-top:18px; color:var(--text-dim); font-size:12px; text-align:center; padding:8px 0; }

    /* Responsif */
    @media (max-width:1200px){ .stats{ grid-template-columns:repeat(2,1fr); } }
    @media (max-width:900px){
      .layout{ flex-direction:column; }
      .sidebar{ position:relative; top:auto; height:auto; width:100%; }
      .topbar{ flex-wrap:wrap; gap:12px; }
      .topbar-left{ flex:1 1 100%; }
      .topbar .actions{ width:100%; justify-content:flex-end; }
    }
    @media (max-width:640px){
      .sidebar-topbar{ padding:12px; }
      .brand{ min-width:auto; }
      .search{ max-width:none; }
      .stats{ grid-template-columns:1fr; }
      .page-title{ font-size:24px; }
      .page-actions{ flex-direction:column; align-items:flex-start; gap:10px; }
      .topbar .actions{ justify-content:flex-start; }
    }
  </style>

  @stack('styles')
</head>
<body>
  <div class="layout">
    @include('admin.layouts.partials.sidebar')

    <main class="main">
      @include('admin.layouts.partials.header')

      @yield('content')

      @include('admin.layouts.partials.footer')
    </main>
  </div>

  @stack('scripts')
</body>
</html>

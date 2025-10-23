<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title','ASET PEMPROV KALTIM')</title>

  {{-- ArcGIS CSS --}}
  <link rel="stylesheet" href="https://js.arcgis.com/4.29/esri/themes/light/main.css">

  <style>
    :root{
      --nav-h:64px;        /* tinggi navbar */
      --brand:#0b2a63;
      --ink:#0f172a;
      --muted:#64748b;
      --card:#f8fafc;
      --bd:#e5e7eb;
      --panel-gap:12px;    /* jarak panel dari navbar */
      --panel-w:300px;     /* lebar panel detail (kecil) */
    }

    *{box-sizing:border-box}
    html,body{height:100%;margin:0;font-family:ui-sans-serif,system-ui,-apple-system,"Segoe UI",Roboto,Ubuntu,"Helvetica Neue",Arial}

    /* NAVBAR */
    .navbar{
      position:sticky;top:0;z-index:50;height:var(--nav-h);
      display:flex;align-items:center;justify-content:space-between;
      padding:0 18px;background:#0b2a63;color:#fff;
      box-shadow:0 2px 10px rgba(0,0,0,.15)
    }
    .nav-left{display:flex;align-items:center;gap:12px}
    .brand-logo{width:40px;height:40px;border-radius:50%;overflow:hidden;display:inline-flex;align-items:center;justify-content:center;background:#ffffff1a}
    .brand-logo img{width:100%;height:100%;object-fit:contain}
    .brand-title{font-weight:800;letter-spacing:.3px;font-size:18px;white-space:nowrap}
    .nav-right a{
      display:inline-flex;align-items:center;gap:8px;height:38px;padding:0 14px;border-radius:10px;
      text-decoration:none;color:#0b2a63;background:#fff;font-weight:700;
      box-shadow:0 2px 6px rgba(0,0,0,.15)
    }
    .nav-right a:hover{filter:brightness(.96)}

    /* MAP WRAP */
    #mapWrap{height:calc(100vh - var(--nav-h));width:100%;background:#f3f4f6}
    #viewDiv{height:100%;width:100%}

    /* DETAIL PANEL – kecil & tidak menembus header */
    .detail-panel{
      position:fixed;
      right:14px;
      top:calc(var(--nav-h) + var(--panel-gap)); /* bawah navbar */
      bottom:14px;
      width:var(--panel-w);
      max-height:calc(100vh - var(--nav-h) - (var(--panel-gap) + 14px));
      background:#fff;border:1px solid var(--bd);border-radius:14px;
      box-shadow:0 12px 28px rgba(0,0,0,.18);
      padding:12px;display:none;z-index:40;overflow:auto
    }
    .detail-panel.show{display:block}
    .dp-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:8px}
    .dp-title{font-weight:800;color:var(--ink);font-size:16px}
    .dp-close{border:none;background:#f1f5f9;width:32px;height:32px;border-radius:10px;cursor:pointer}

    .dp-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px}
    .dp-item{background:var(--card);border:1px solid var(--bd);border-radius:10px;padding:8px}
    .dp-item.full{grid-column:1 / -1}
    .dp-label{font-size:11px;color:var(--muted);margin-bottom:3px}
    .dp-value{font-size:13px;font-weight:700;color:var(--ink);word-break:break-word}
    .dp-link{font-size:13px;font-weight:700}

    /* ArcGIS widgets */
    .esri-ui.bottom-right>.esri-component{box-shadow:0 8px 20px rgba(0,0,0,.18);border-radius:12px;overflow:hidden}

    /* Mobile */
    @media (max-width:640px){
      :root{ --panel-w:calc(100vw - 28px); }
      .detail-panel{ left:14px; right:14px; }
      .dp-grid{grid-template-columns:1fr}
    }
  </style>

  @stack('styles')
</head>
<body>

  {{-- HEADER --}}
  @include('landing.layout.header')

  {{-- MAIN CONTENT --}}
  <main id="mapWrap">
    @yield('content')
  </main>

  {{-- ArcGIS JS --}}
  <script src="https://js.arcgis.com/4.29/"></script>

  @stack('scripts')
</body>
</html>

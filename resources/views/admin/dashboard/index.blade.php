@extends('admin.layouts.app')

@section('title', 'Dashboard ESDM - Data Wilayah')

@section('content')
  <!-- Header halaman -->
  <div class="page-head">
    <div>
      <div class="page-meta">Selasa, 22 September 2025</div>
      <div class="page-title">Data Wilayah</div>
    </div>
    <div class="page-actions">
      <div class="date-pill">
        <i class="ri-calendar-line"></i>
        <span>September 2025</span>
      </div>
      <button class="btn btn-primary btn-add">
        <i class="ri-add-line"></i>
        Tambah Data Wilayah
      </button>
    </div>
  </div>

  <!-- Kartu: Jumlah Daya Tersambung -->
  <section class="card">
    <div class="card-header">
      <div>
        <div class="card-title">Jumlah Daya Tersambung</div>
        <div class="num-xxl">3.242.475.602,00 VA</div>
      </div>
      <button class="btn btn-primary"><i class="ri-bar-chart-2-line"></i> Selengkapnya</button>
    </div>
    <div class="card-body">
      <div class="chart-shell">
        <canvas id="powerTrendChart" aria-label="Grafik tren daya tersambung" height="260"></canvas>
      </div>

      <!-- Legend -->
      <div class="legend">
        <span><span class="dot lg-green"></span>Tahun Ini</span>
        <span><span class="dot" style="background:#94A3B8"></span>Tahun Lalu</span>
      </div>

      <!-- Statistik 6 kolom -->
      <div class="stats">
        <div class="stat">
          <div class="ico"><i class="ri-home-4-line"></i></div>
          <div>
            <div class="label">Rumah Tangga</div>
            <div class="val">1.784.192.900,00 VA</div>
          </div>
        </div>

        <div class="stat">
          <div class="ico"><i class="ri-factory-line"></i></div>
          <div>
            <div class="label">Industri</div>
            <div class="val">351.272.400,00 VA</div>
          </div>
        </div>

        <div class="stat">
          <div class="ico"><i class="ri-store-2-line"></i></div>
          <div>
            <div class="label">Usaha</div>
            <div class="val">738.134.900,00 VA</div>
          </div>
        </div>

        <div class="stat">
          <div class="ico"><i class="ri-community-line"></i></div>
          <div>
            <div class="label">Sosial</div>
            <div class="val">738.134.900,00 VA</div>
          </div>
        </div>

        <div class="stat">
          <div class="ico"><i class="ri-government-line"></i></div>
          <div>
            <div class="label">Gedung Kantor Pemerintah</div>
            <div class="val">738.134.900,00 VA</div>
          </div>
        </div>

        <div class="stat">
          <div class="ico"><i class="ri-train-line"></i></div>
          <div>
            <div class="label">Traksi, Curah, Layanan Khusus</div>
            <div class="val">11.739.650,00 VA</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Kartu: Demografi Wilayah -->
  <section class="card" style="margin-top:18px;">
    <div class="card-header">
      <div class="card-title">Demografi Wilayah</div>
    </div>
    <div class="card-body bars-wrap">
      <div class="bar-grid">
        <!-- 1 -->
        <div class="bar-col">
          <div class="bar-stack">
            <div class="bar b-green" style="height:220px"></div>
            <div class="bar b-blue"  style="height:126px"></div>
            <div class="bar b-gray"  style="height:150px"></div>
          </div>
          <div class="bar-label">Kab. Berau</div>
        </div>

        <!-- 2 -->
        <div class="bar-col">
          <div class="bar-stack">
            <div class="bar b-green" style="height:60px"></div>
            <div class="bar b-blue"  style="height:40px"></div>
            <div class="bar b-gray"  style="height:110px"></div>
          </div>
          <div class="bar-label">Kota Balikpapan</div>
        </div>

        <!-- 3 -->
        <div class="bar-col">
          <div class="bar-stack">
            <div class="bar b-green" style="height:150px"></div>
            <div class="bar b-blue"  style="height:210px"></div>
            <div class="bar b-gray"  style="height:115px"></div>
          </div>
          <div class="bar-label">Kota Bontang</div>
        </div>

        <!-- 4 -->
        <div class="bar-col">
          <div class="bar-stack">
            <div class="bar b-green" style="height:150px"></div>
            <div class="bar b-blue"  style="height:98px"></div>
            <div class="bar b-gray"  style="height:115px"></div>
          </div>
          <div class="bar-label">Kota Samarinda</div>
        </div>

        <!-- 5 -->
        <div class="bar-col">
          <div class="bar-stack">
            <div class="bar b-green" style="height:240px"></div>
            <div class="bar b-blue"  style="height:140px"></div>
            <div class="bar b-gray"  style="height:80px"></div>
          </div>
          <div class="bar-label">Kab. Kutai Barat</div>
        </div>

        <div class="bar-col">
          <div class="bar-stack">
            <div class="bar b-green" style="height:190px"></div>
            <div class="bar b-blue"  style="height:130px"></div>
            <div class="bar b-gray"  style="height:100px"></div>
          </div>
          <div class="bar-label">Kab. Kutai Timur</div>
        </div>

        <div class="bar-col">
          <div class="bar-stack">
            <div class="bar b-green" style="height:230px"></div>
            <div class="bar b-blue"  style="height:120px"></div>
            <div class="bar b-gray"  style="height:70px"></div>
          </div>
          <div class="bar-label">Kab. Paser</div>
        </div>

        <div class="bar-col">
          <div class="bar-stack">
            <div class="bar b-green" style="height:150px"></div>
            <div class="bar b-blue"  style="height:80px"></div>
            <div class="bar b-gray"  style="height:60px"></div>
          </div>
          <div class="bar-label">Kab. Penajam<br> Paser Utara</div>
        </div>

        <div class="bar-col">
          <div class="bar-stack">
            <div class="bar b-green" style="height:110px"></div>
            <div class="bar b-blue"  style="height:70px"></div>
            <div class="bar b-gray"  style="height:140px"></div>
          </div>
          <div class="bar-label">Kab. Mahakam Ulu</div>
        </div>
      </div>

      <div class="legend">
        <span><span class="dot lg-green"></span>Terlayani Listrik PLN</span>
        <span><span class="dot lg-blue"></span>Terlayani Listrik Non PLN</span>
        <span><span class="dot lg-gray"></span>Listrik Mandiri</span>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.6/dist/chart.umd.min.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const canvas = document.getElementById('powerTrendChart');
      if (!canvas) return;

      const ctx = canvas.getContext('2d');
      const gradientCurrent = ctx.createLinearGradient(0, 0, 0, 260);
      gradientCurrent.addColorStop(0, 'rgba(34, 197, 94, 0.35)');
      gradientCurrent.addColorStop(1, 'rgba(34, 197, 94, 0.05)');

      const gradientPrev = ctx.createLinearGradient(0, 0, 0, 260);
      gradientPrev.addColorStop(0, 'rgba(148, 163, 184, 0.25)');
      gradientPrev.addColorStop(1, 'rgba(148, 163, 184, 0.05)');

      new Chart(ctx, {
        type: 'line',
        data: {
          labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
          datasets: [
            {
              label: 'Tahun Ini',
              data: [2100, 2300, 2550, 2680, 2920, 3150, 3380, 3610, 3800, 4020, 4280, 4520],
              borderColor: '#22C55E',
              backgroundColor: gradientCurrent,
              fill: true,
              tension: 0.45,
              pointRadius: 0,
              pointHoverRadius: 4,
              pointHoverBackgroundColor: '#16A34A',
              pointHoverBorderColor: '#fff',
              pointHoverBorderWidth: 2
            },
            {
              label: 'Tahun Lalu',
              data: [1800, 1950, 2100, 2280, 2440, 2620, 2780, 2950, 3100, 3260, 3420, 3600],
              borderColor: '#94A3B8',
              backgroundColor: gradientPrev,
              fill: true,
              tension: 0.45,
              pointRadius: 0,
              pointHoverRadius: 4,
              pointHoverBackgroundColor: '#64748B',
              pointHoverBorderColor: '#fff',
              pointHoverBorderWidth: 2,
              borderDash: [6, 6]
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false },
            tooltip: {
              backgroundColor: '#0F172A',
              titleColor: '#F8FAFC',
              bodyColor: '#E2E8F0',
              borderColor: 'rgba(15, 23, 42, 0.4)',
              borderWidth: 1,
              padding: 10,
              callbacks: {
                label: function (context) {
                  const value = context.parsed.y || 0;
                  return `${context.dataset.label}: ${value.toLocaleString('id-ID')} VA`;
                }
              }
            }
          },
          scales: {
            x: {
              grid: { display: false },
              ticks: { color: '#94A3B8', font: { weight: 600, size: 12 } }
            },
            y: {
              beginAtZero: true,
              grid: { color: 'rgba(148, 163, 184, 0.2)', drawBorder: false },
              ticks: {
                color: '#94A3B8',
                font: { weight: 600 },
                callback: function (value) {
                  if (value >= 1000) return `${Math.round(value / 100) / 10}k`;
                  return value;
                }
              }
            }
          }
        }
      });
    });
  </script>
@endpush

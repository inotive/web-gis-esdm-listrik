<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web GIS ESDM Listrik - Dinas ESDM Kalimantan Timur</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --white: #ffffff;
            --gray-900: #181d27;
            --gray-800: #262b36;
            --gray-700: #414651;
            --gray-600: #535862;
            --gray-500: #717680;
            --gray-200: #e5e7eb;
            --gray-100: #f3f4f6;
            --gray-50: #f9fafb;

            /* Green Palette from Login Page */
            --primary: #059669;
            /* Emerald 600 */
            --primary-dark: #047857;
            /* Emerald 700 */
            --primary-light: #10B981;
            /* Emerald 500 */
            --primary-bg: rgba(5, 150, 105, 0.08);

            --max-width: 1280px;
            --header-height: 80px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-font-smoothing: antialiased;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--gray-700);
            line-height: 1.5;
            background-color: var(--white);
        }

        a {
            text-decoration: none;
            color: inherit;
            transition: color 0.2s;
        }

        button {
            cursor: pointer;
            border: none;
            font-family: inherit;
        }

        .container {
            max-width: var(--max-width);
            margin: 0 auto;
            padding: 0 24px;
        }

        /* Navbar */
        .navbar {
            height: var(--header-height);
            background-color: var(--white);
            border-bottom: 1px solid var(--gray-200);
            position: sticky;
            top: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
        }

        .nav-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-img {
            height: 48px;
            width: auto;
        }

        .logo-text {
            display: flex;
            flex-direction: column;
        }

        .logo-title {
            font-weight: 700;
            color: var(--gray-900);
            font-size: 16px;
            line-height: 1.2;
        }

        .logo-subtitle {
            font-size: 13px;
            color: var(--gray-600);
            font-weight: 500;
        }

        .nav-links {
            display: flex;
            gap: 32px;
            align-items: center;
        }

        .nav-link {
            font-weight: 500;
            color: var(--gray-600);
            font-size: 15px;
        }

        .nav-link:hover {
            color: var(--primary);
        }

        .btn-login {
            background-color: var(--primary);
            color: var(--white);
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: background-color 0.2s;
        }

        .btn-login:hover {
            background-color: var(--primary-dark);
        }

        /* Hero Section Revamp */
        .hero {
            background-color: var(--primary);
            color: var(--white);
            position: relative;
            overflow: hidden;
            min-height: 80vh;
            display: flex;
            align-items: center;
            width: 100%;
            padding: 120px 0 60px;
            /* Account for navbar */
        }

        /* Abstract Background Shapes */
        .hero-shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: 1;
            opacity: 0.6;
            animation: pulse 10s infinite alternate;
        }

        .shape-1 {
            background: rgba(255, 255, 255, 0.15);
            width: 500px;
            height: 500px;
            top: -100px;
            right: -100px;
        }

        .shape-2 {
            background: rgba(16, 185, 129, 0.4);
            /* Primary Light */
            width: 300px;
            height: 300px;
            bottom: -50px;
            left: -50px;
            animation-delay: -2s;
        }

        .shape-3 {
            background: rgba(4, 120, 87, 0.3);
            /* Primary Dark */
            width: 150px;
            height: 150px;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .hero-container {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 60px;
            align-items: center;
            width: 100%;
            max-width: var(--max-width);
            margin: 0 auto;
            padding: 0 24px;
        }

        .hero-text-content {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            text-align: left;
        }

        .hero-badge {
            background-color: rgba(255, 255, 255, 0.15);
            color: var(--white);
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 32px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .hero-badge i {
            font-size: 16px;
        }

        .hero-title {
            font-size: 56px;
            font-weight: 800;
            margin-bottom: 24px;
            line-height: 1.1;
            letter-spacing: -0.03em;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .text-highlight {
            color: #d1fae5;
            /* Very light green */
            position: relative;
            display: inline-block;
        }

        /* Underline effect */
        .text-highlight::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 4px;
            width: 100%;
            height: 8px;
            background-color: rgba(255, 255, 255, 0.2);
            z-index: -1;
            border-radius: 4px;
        }

        .hero-desc {
            font-size: 20px;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 48px;
            line-height: 1.6;
            max-width: 600px;
            font-weight: 400;
        }

        .hero-actions {
            display: flex;
            gap: 16px;
            margin-bottom: 60px;
        }

        .btn-hero-primary {
            background-color: var(--white);
            color: var(--primary);
            padding: 16px 36px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 16px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .btn-hero-primary:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            background-color: #f0fdf4;
        }

        .btn-hero-secondary {
            background-color: transparent;
            color: var(--white);
            padding: 16px 36px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .btn-hero-secondary:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-color: var(--white);
        }

        .hero-stats {
            display: flex;
            gap: 48px;
            padding-top: 32px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            width: 100%;
            max-width: 500px;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 800;
        }

        .stat-label {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
        }

        /* Hero Visual (Right Side) */
        .hero-visual {
            position: relative;
            height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .visual-circle {
            position: absolute;
            width: 450px;
            height: 450px;
            border-radius: 50%;
            border: 2px dashed rgba(255, 255, 255, 0.2);
            animation: spin 60s linear infinite;
        }

        .visual-circle-inner {
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .visual-logo {
            width: 120px;
            height: auto;
            filter: drop-shadow(0 0 20px rgba(255, 255, 255, 0.2));
            animation: float 6s ease-in-out infinite;
        }

        .floating-card {
            position: absolute;
            background: rgba(255, 255, 255, 0.95);
            color: var(--gray-900);
            padding: 16px 20px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            animation: float 5s ease-in-out infinite;
            z-index: 5;
            max-width: 200px;
        }

        .floating-card i {
            font-size: 24px;
            color: var(--primary);
            background: #ecfdf5;
            padding: 8px;
            border-radius: 10px;
        }

        .fc-1 {
            top: 50px;
            left: 0;
            animation-delay: 0s;
        }

        .fc-2 {
            bottom: 80px;
            right: 20px;
            animation-delay: -2.5s;
        }

        .fc-3 {
            top: 40%;
            right: -40px;
            animation-delay: -1s;
        }

        /* Animations */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 0.6;
            }

            100% {
                transform: scale(1.1);
                opacity: 0.4;
            }
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* Mobile Responsive for Hero */
        @media (max-width: 1024px) {
            .hero-title {
                font-size: 48px;
            }

            .hero-container {
                grid-template-columns: 1fr;
                text-align: center;
                gap: 40px;
            }

            .hero-text-content {
                align-items: center;
                text-align: center;
            }

            .hero-badge {
                margin-left: auto;
                margin-right: auto;
            }

            .hero-actions {
                justify-content: center;
            }

            .hero-stats {
                justify-content: center;
                margin: 0 auto;
                border-top: 1px solid rgba(255, 255, 255, 0.2);
            }

            .hero-visual {
                height: 400px;
                display: none;
                /* Optional: hide detailed visual on mobile if too cluttered */
            }
        }

        @media (max-width: 768px) {
            .hero {
                padding-top: 100px;
                min-height: auto;
                padding-bottom: 80px;
            }

            .hero-title {
                font-size: 36px;
            }

            .hero-actions {
                flex-direction: column;
                width: 100%;
                gap: 12px;
            }

            .btn-hero-primary,
            .btn-hero-secondary {
                width: 100%;
                justify-content: center;
            }

            .hero-desc {
                font-size: 16px;
                margin-bottom: 32px;
            }
        }

        /* Section Styling */
        .section {
            padding: 100px 0;
        }

        .section-header {
            text-align: center;
            margin-bottom: 60px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .section-tag {
            color: var(--primary);
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 12px;
            display: block;
        }

        .section-title {
            font-size: 36px;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 16px;
        }

        .section-desc {
            color: var(--gray-600);
            font-size: 18px;
        }

        /* Alur Permohonan */
        .alur-section {
            background-color: var(--white);
            padding-top: 40px;
            /* Reduced top padding to move it up */
        }

        .steps-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 32px;
            position: relative;
        }

        /* Connecting line */
        .steps-grid::before {
            content: '';
            position: absolute;
            top: 40px;
            left: 50px;
            right: 50px;
            height: 2px;
            background-color: var(--gray-200);
            z-index: 0;
            display: block;
        }

        /* Hide line on mobile */
        @media (max-width: 900px) {
            .steps-grid {
                grid-template-columns: 1fr;
                gap: 48px;
            }

            .steps-grid::before {
                display: none;
            }
        }

        .step-card {
            background-color: var(--white);
            position: relative;
            z-index: 1;
            text-align: center;
        }

        .step-icon-wrapper {
            width: 80px;
            height: 80px;
            background-color: var(--white);
            border: 2px solid var(--gray-200);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            color: var(--primary);
            font-size: 32px;
            transition: all 0.3s ease;
        }

        .step-card:hover .step-icon-wrapper {
            border-color: var(--primary);
            background-color: var(--primary-bg);
            transform: scale(1.1);
        }

        .step-number {
            position: absolute;
            top: 0;
            right: 0;
            background-color: var(--gray-900);
            color: var(--white);
            width: 24px;
            height: 24px;
            border-radius: 50%;
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            transform: translate(50%, -50%);
            /* Adjust positioning relative to wrapper */
        }

        /* Adjust wrapper for number positioning */
        .step-icon-container {
            position: relative;
            width: 80px;
            height: 80px;
            margin: 0 auto 24px;
        }

        .step-icon-container .step-number {
            top: 0;
            right: 0;
        }

        .step-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 12px;
        }

        .step-text {
            font-size: 15px;
            color: var(--gray-600);
            line-height: 1.6;
        }

        /* FAQ Section */
        .faq-section {
            background-color: var(--gray-50);
            padding-top: 20px;

        }

        .faq-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .faq-item {
            background-color: var(--white);
            border-radius: 12px;
            margin-bottom: 16px;
            border: 1px solid var(--gray-200);
            overflow: hidden;
            transition: border-color 0.2s;
        }

        .faq-item:hover {
            border-color: var(--primary-light);
        }

        .faq-question {
            padding: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            font-weight: 600;
            font-size: 18px;
            color: var(--gray-900);
            background: none;
            width: 100%;
            text-align: left;
        }

        .faq-question i {
            color: var(--gray-500);
            transition: transform 0.3s;
        }

        .faq-item.active .faq-question i {
            transform: rotate(180deg);
            color: var(--primary);
        }

        .faq-item.active .faq-question {
            color: var(--primary);
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            padding: 0 24px;
            transition: max-height 0.3s ease-out, padding 0.3s ease;
            color: var(--gray-600);
            line-height: 1.6;
        }

        .faq-item.active .faq-answer {
            max-height: 200px;
            /* Adjust based on content */
            padding-bottom: 24px;
        }

        /* Footer */
        .footer {
            background-color: var(--gray-900);
            color: var(--white);
            padding: 60px 0 24px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 64px;
            margin-bottom: 60px;
        }

        .footer-brand h3 {
            font-size: 20px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .footer-brand p {
            color: var(--gray-500);
            line-height: 1.6;
            max-width: 300px;
        }

        .footer-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--white);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 24px;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 12px;
        }

        .footer-links a {
            color: var(--gray-500);
            font-size: 15px;
        }

        .footer-links a:hover {
            color: var(--white);
        }

        .footer-bottom {
            border-top: 1px solid var(--gray-800);
            padding-top: 24px;
            text-align: center;
            color: var(--gray-600);
            font-size: 14px;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 32px;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .nav-links {
                display: none;
                /* Add mobile menu logic if needed, hiding for now */
            }
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="container nav-content">
            <div class="logo-area">
                <img src="{{ asset('assets/media/logos/logo.png') }}" alt="Logo ESDM" class="logo-img">
                <div class="logo-text">
                    <span class="logo-title">Dinas Energi Dan Sumber Daya Mineral</span>
                    <span class="logo-subtitle">Provinsi Kalimantan Timur</span>
                </div>
            </div>
            <div class="nav-links">
                {{-- <a href="#home" class="nav-link">Beranda</a>
                <a href="#alur" class="nav-link">Alur Permohonan</a>
                <a href="#faq" class="nav-link">FAQ</a> --}}
                <a href="{{ route('login') }}" class="btn-login">Masuk</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <!-- Abstract Background -->
        <div class="hero-shape shape-1"></div>
        <div class="hero-shape shape-2"></div>
        <div class="hero-shape shape-3"></div>

        <div class="hero-container">
            <div class="hero-text-content">
                <div class="hero-badge">
                    <i class="ri-government-line"></i> Dinas Energi Dan Sumber Daya Mineral
                </div>
                <h1 class="hero-title">
                    Sistem Informasi <br>
                    <span class="text-highlight">Geografis ESDM</span>
                </h1>
                <p class="hero-desc">
                    Portal terpadu untuk pemetaan infrastruktur energi dan layanan permohonan kelistrikan di Kalimantan
                    Timur. Transparan, terpercaya, dan mudah diakses.
                </p>
                <div class="hero-actions">
                    {{-- <a href="#alur" class="btn-hero-primary">
                        Alur <i class="ri-arrow-right-line"></i>
                    </a> --}}
                    {{-- <a href="#alur" class="btn-hero-secondary">
                        <i class="ri-play-circle-line"></i> Alur
                    </a> --}}
                </div>

                {{-- <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-value">10K+</span>
                        <span class="stat-label">Data Titik</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">24/7</span>
                        <span class="stat-label">Akses Online</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">100%</span>
                        <span class="stat-label">Transparan</span>
                    </div>
                </div> --}}
            </div>

            <div class="hero-visual">
                <!-- Decorative Orbit -->
                <div class="visual-circle"></div>

                <div class="visual-circle-inner">
                    <img src="{{ asset('assets/media/logos/logo.png') }}" alt="ESDM Logo" class="visual-logo">
                </div>

                <!-- Floating Cards -->
                <div class="floating-card fc-1">
                    <i class="ri-map-2-line"></i>
                    <div style="font-size: 14px;">
                        <div>Website Interaktif</div>
                        {{-- <div style="font-size: 12px; color: var(--gray-500); font-weight: normal;">Real-time GIS</div> --}}
                    </div>
                </div>

                <div class="floating-card fc-2">
                    <i class="ri-flashlight-line"></i>
                    <div style="font-size: 14px;">
                        <div>Infrastruktur Listrik</div>
                        {{-- <div style="font-size: 12px; color: var(--gray-500); font-weight: normal;">Layanan Terpadu</div> --}}
                    </div>
                </div>

                <div class="floating-card fc-3">
                    <i class="ri-shield-check-line"></i>
                    <div style="font-size: 14px;">
                        <div>Permohonan Dan Perizinan</div>
                        {{-- <div style="font-size: 12px; color: var(--gray-500); font-weight: normal;">Data Valid</div> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Alur Permohonan Section -->
    <section id="alur" class="section alur-section">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Bagaimana Caranya?</span>
                <h2 class="section-title">Alur Permohonan Layanan</h2>
                <p class="section-desc">Ikuti langkah-langkah mudah berikut untuk mengajukan permohonan layanan
                    kelistrikan.</p>
            </div>

            <div class="steps-grid">
                <!-- Step 1 -->
                <div class="step-card">
                    <div class="step-icon-container">
                        <div class="step-icon-wrapper">
                            <i class="ri-login-circle-line"></i>
                        </div>
                        <div class="step-number">1</div>
                    </div>
                    {{-- <h3 class="step-title">Masuk ke sistem</h3> --}}
                    <p class="step-text">Login sebagai Desa/Perusahaan</p>
                </div>

                <!-- Step 2 -->
                <div class="step-card">
                    <div class="step-icon-container">
                        <div class="step-icon-wrapper">
                            <i class="ri-cursor-line"></i>
                        </div>
                        <div class="step-number">2</div>
                    </div>
                    {{-- <h3 class="step-title">Isi Formulir</h3> --}}
                    <p class="step-text">Pilih layanan ESDM.</p>
                </div>

                <!-- Step 3 -->
                <div class="step-card">
                    <div class="step-icon-container">
                        <div class="step-icon-wrapper">
                            <i class="ri-file-edit-line"></i>
                        </div>
                        <div class="step-number">3</div>
                    </div>
                    {{-- <h3 class="step-title">Verifikasi</h3> --}}
                    <p class="step-text">Isi data permohonan lalu simpan</p>
                </div>

                <!-- Step 4 -->
                <div class="step-card">
                    <div class="step-icon-container">
                        <div class="step-icon-wrapper">
                            <i class="ri-checkbox-circle-line"></i>
                        </div>
                        <div class="step-number">4</div>
                    </div>
                    {{-- <h3 class="step-title">Selesai</h3> --}}
                    <p class="step-text">Permohonan Anda berhasil diajukan</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="section faq-section">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Tanya Jawab</span>
                <h2 class="section-title">Pertanyaan Umum (FAQ)</h2>
                <p class="section-desc">Jawaban atas pertanyaan yang sering diajukan oleh pemohon.</p>
            </div>

            <div class="faq-container">
                {{-- <div class="faq-item">
                    <button class="faq-question">
                        Apa saja persyaratan dokumen yang dibutuhkan?
                        <i class="ri-arrow-down-s-line"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Persyaratan dokumen bergantung pada jenis layanan yang diajukan. Secara umum meliputi KTP,
                            NPWP, dan dokumen legalitas perusahaan jika mewakili badan usaha. Detail lengkap dapat
                            dilihat pada formulir permohonan.</p>
                    </div>
                </div> --}}

                <div class="faq-item">
                    <button class="faq-question">
                        Berapa lama proses verifikasi permohonan?
                        <i class="ri-arrow-down-s-line"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Proses verifikasi dokumen estimasinya memakan waktu 3-5 hari kerja setelah dokumen dinyatakan
                            lengkap oleh sistem.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        Apakah layanan ini dipungut biaya?
                        <i class="ri-arrow-down-s-line"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Informasi mengenai biaya retribusi atau biaya layanan lainnya akan tertera pada jenis layanan
                            yang dipilih sesuai dengan peraturan daerah yang berlaku.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">

        <div class="footer-bottom">
            &copy; {{ date('Y') }} Dinas ESDM Provinsi Kalimantan Timur. All rights reserved.
        </div>

    </footer>

    <script>
        // Simple script for FAQ accordion
        document.querySelectorAll('.faq-question').forEach(button => {
            button.addEventListener('click', () => {
                const faqItem = button.parentElement;
                const isActive = faqItem.classList.contains('active');

                // Close all other items
                document.querySelectorAll('.faq-item').forEach(item => {
                    item.classList.remove('active');
                });

                // Toggle current item
                if (!isActive) {
                    faqItem.classList.add('active');
                }
            });
        });
    </script>
</body>

</html>

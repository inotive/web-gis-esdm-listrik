@extends('layouts.auth')

@section('content')
    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta charset="utf-8" />
        <title>Daftar Akun - Dinas ESDM Kalimantan Timur</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css" />
        <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <style>
            * {
                -webkit-font-smoothing: antialiased;
                box-sizing: border-box;
            }

            :root {
                --white: rgba(255, 255, 255, 1);
                --gray-900: rgba(24, 29, 39, 1);
                --gray-600: rgba(83, 88, 98, 1);
                --gray-700: rgba(65, 70, 81, 1);
                --gray-500: rgba(113, 118, 128, 1);
                --gray-300: rgba(213, 215, 218, 1);
                --gray-100: #F3F4F6;
                --primary: #059669;
                --primary-dark: #047857;
                --primary-light: #10B981;
                --primary-bg: rgba(5, 150, 105, 0.08);
                --shadow-xs: 0px 1px 2px 0px rgba(10, 13, 18, 0.05);
                --border-radius: 12px;
                --transition: all 0.3s ease;
            }

            html,
            body {
                margin: 0;
                padding: 0;
                height: 100%;
                font-family: "Inter", Helvetica, Arial, sans-serif;
                background-color: #f8f9fa;
                overflow: hidden;
            }

            .login-container {
                display: flex;
                height: 100vh;
                width: 100%;
                overflow: hidden;
            }

            .login-form-section {
                flex: 1;
                position: relative;
                display: flex;
                flex-direction: column;
                align-items: center;
                padding: 40px;
                background-color: var(--white);
                overflow-y: auto; /* Allow scroll for register form */
            }

            .login-image-section {
                flex: 1.2;
                display: flex;
                align-items: center;
                justify-content: center;
                background-color: #f0f2f5;
                overflow: hidden;
                position: relative;
            }

            .login-image-section::before {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(135deg, rgba(5, 150, 105, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%);
                z-index: 1;
            }

            .login-image {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .header {
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 2rem;
            }

            .logo-container {
                display: flex;
                align-items: center;
                gap: 16px;
            }

            .logo {
                width: 48px;
                height: 58px;
            }

            .logo-text {
                display: flex;
                flex-direction: column;
            }

            .logo-title {
                font-weight: 700;
                color: var(--gray-900);
                font-size: 15px;
                max-width: 220px;
                line-height: 1.3;
            }

            .logo-subtitle {
                font-weight: 500;
                color: var(--gray-600);
                font-size: 13px;
                margin-top: 2px;
            }

            .login-content {
                max-width: 480px;
                width: 100%;
                margin: 0 auto;
                background: transparent;
                padding-bottom: 60px;
            }

            .login-title {
                font-size: 32px;
                font-weight: 700;
                color: var(--gray-900);
                margin-bottom: 8px;
            }

            .login-subtitle {
                font-size: 16px;
                color: var(--gray-600);
                margin-bottom: 32px;
                line-height: 1.5;
            }

            .form-group {
                margin-bottom: 20px;
            }

            .form-row {
                display: flex;
                gap: 20px;
            }
            .form-row .form-group {
                flex: 1;
            }

            .form-label {
                display: block;
                font-size: 14px;
                font-weight: 600;
                color: var(--gray-700);
                margin-bottom: 8px;
            }

            .form-input, .form-select, .form-textarea {
                width: 100%;
                padding: 14px 16px;
                border: 2px solid var(--gray-300);
                border-radius: var(--border-radius);
                font-size: 15px;
                transition: var(--transition);
                background-color: var(--white);
                font-family: inherit;
            }

            .form-select {
                appearance: none;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' width='24' height='24'%3E%3Cpath fill='none' d='M0 0h24v24H0z'/%3E%3Cpath d='M12 13.172l4.95-4.95 1.414 1.414L12 16 5.636 9.636 7.05 8.222z' fill='%236B7280'/%3E%3C/svg%3E");
                background-repeat: no-repeat;
                background-position: right 12px center;
                padding-right: 40px;
            }

            .form-input:focus, .form-select:focus, .form-textarea:focus {
                outline: none;
                border-color: var(--primary);
                box-shadow: 0 0 0 4px var(--primary-bg);
            }

            .password-wrapper {
                position: relative;
            }

            .password-toggle {
                position: absolute;
                right: 16px;
                top: 50%;
                transform: translateY(-50%);
                cursor: pointer;
                color: var(--gray-500);
            }

            .role-selector {
                display: flex;
                gap: 24px;
                margin-bottom: 32px;
            }

            .role-option {
                display: flex;
                align-items: center;
                gap: 8px;
                cursor: pointer;
            }

            .role-radio {
                width: 18px;
                height: 18px;
                accent-color: var(--primary);
                cursor: pointer;
            }

            .login-button {
                width: 100%;
                padding: 16px 24px;
                background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
                color: white;
                border: none;
                border-radius: var(--border-radius);
                font-size: 16px;
                font-weight: 600;
                cursor: pointer;
                transition: var(--transition);
                box-shadow: 0 4px 14px 0 rgba(5, 150, 105, 0.35);
                margin-top: 24px;
            }

            .login-button:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px 0 rgba(5, 150, 105, 0.45);
            }

            .footer {
                margin-top: auto;
                text-align: center;
                font-size: 14px;
                color: var(--gray-500);
                padding-top: 20px;
            }

            .login-link {
                text-align: center;
                margin-top: 24px;
                font-size: 15px;
                color: var(--gray-600);
            }

            .login-link a {
                color: var(--primary);
                text-decoration: none;
                font-weight: 600;
            }

            @media (max-width: 1024px) {
                .login-container {
                    flex-direction: column;
                    overflow-y: auto;
                }
                .login-image-section {
                    display: none;
                }
                .login-form-section {
                    padding: 24px;
                    overflow-y: visible;
                }
            }
        </style>
    </head>

    <body>
        <div class="login-container">
            <div class="login-form-section">
                
                <div class="header">
                    <div class="logo-container">
                        <img class="logo" src="{{ asset('assets/media/logos/logo.png') }}" alt="Logo Dinas ESDM" />
                        <div class="logo-text">
                            <div class="logo-title">Dinas Energi dan Sumber Daya Mineral</div>
                            <div class="logo-subtitle">Provinsi Kalimantan Timur</div>
                        </div>
                    </div>
                </div>

                <div class="login-content">
                    <h1 class="login-title">Register</h1>
                    <p class="login-subtitle">Lengkapi data diri Anda untuk membuat akun baru pada sistem.</p>

                    <form id="registerForm" method="POST" action="{{ route('register.perform') }}">
                        @csrf
                        
                        <div class="form-group">
                            <label class="form-label">Daftar Sebagai</label>
                            <div class="role-selector">
                                <label class="role-option">
                                    <input type="radio" name="identity_type" value="desa" class="role-radio" checked onchange="toggleForm('desa')">
                                    <span style="font-weight: 500">Desa</span>
                                </label>
                                <label class="role-option">
                                    <input type="radio" name="identity_type" value="perusahaan" class="role-radio" onchange="toggleForm('perusahaan')">
                                    <span style="font-weight: 500">Perusahaan</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input class="form-input" type="text" name="name" placeholder="Masukkan Nama Lengkap" required />
                        </div>

                        <div class="form-group">
                            <label class="form-label">Username</label>
                            <input class="form-input" type="text" name="username" placeholder="Masukkan Username" required />
                        </div>

                        <div class="form-group">
                            <label class="form-label" id="jabatanLabel">Jabatan</label>
                            <input class="form-input" type="text" name="jabatan" placeholder="Masukkan Nama Desa/Perusahaan" required />
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input class="form-input" type="email" name="email" placeholder="email@mail.com" required />
                        </div>

                        <div class="form-group">
                            <label class="form-label">No. Hp</label>
                            <input class="form-input" type="text" name="phone" placeholder="Masukkan Nomor HP" required />
                        </div>

                        <!-- Wilayah -->
                        <div class="form-group">
                            <label class="form-label">Kabupaten/Kota</label>
                            <select class="form-select" name="regency_id" id="regencySelect" required>
                                <option value="">Pilih Kabupaten/Kota</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Kecamatan</label>
                            <select class="form-select" name="district_id" id="districtSelect" required disabled>
                                <option value="">Pilih Kabupaten/Kota terlebih dahulu</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Kelurahan/Desa</label>
                            <select class="form-select" name="village_id" id="villageSelect" required disabled>
                                <option value="">Pilih Kecamatan terlebih dahulu</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label" id="addressLabel">Alamat Kantor Desa</label>
                            <textarea class="form-textarea" name="address" rows="3" placeholder="Cth: Jl. Pahlawan No.78 RT.002" required style="resize: vertical"></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Kata Sandi</label>
                            <div class="password-wrapper">
                                <input class="form-input" type="password" name="password" id="password" required minlength="8" />
                                <div class="password-toggle" onclick="togglePassword('password')">
                                    <i class="ri-eye-off-line"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Ulangi Kata Sandi</label>
                            <div class="password-wrapper">
                                <input class="form-input" type="password" name="password_confirmation" id="password_confirm" required minlength="8" />
                                <div class="password-toggle" onclick="togglePassword('password_confirm')">
                                    <i class="ri-eye-off-line"></i>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="login-button">
                            <span>Daftar</span>
                        </button>

                        <div class="login-link">
                            Sudah Memiliki Akun? <a href="{{ route('login') }}">Login</a>
                        </div>
                    </form>
                </div>

                <div class="footer">
                    <p>© 2025 <a href="#" style="color:var(--primary);text-decoration:none;">Dinas ESDM Kalimantan Timur</a></p>
                </div>
            </div>

            <div class="login-image-section">
                <img class="login-image" src="{{ asset('assets/bg.png') }}" alt="Background Kalimantan Timur" />
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            // --- Form Logic ---
            function toggleForm(role) {
                const addressLabel = document.getElementById('addressLabel');
                const jabatanLabel = document.getElementById('jabatanLabel'); // Optional if needed to change

                if (role === 'desa') {
                    addressLabel.innerText = 'Alamat Kantor Desa';
                    // jabatanLabel.innerText = 'Jabatan';
                } else {
                    addressLabel.innerText = 'Alamat Perusahaan';
                    // jabatanLabel.innerText = 'Jabatan';
                }
            }

            function togglePassword(id) {
                const input = document.getElementById(id);
                const icon = input.nextElementSibling.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('ri-eye-off-line', 'ri-eye-line');
                } else {
                    input.type = 'password';
                    icon.classList.replace('ri-eye-line', 'ri-eye-off-line');
                }
            }

            // --- Region Dropdown Logic ---
            const regencySelect = document.getElementById('regencySelect');
            const districtSelect = document.getElementById('districtSelect');
            const villageSelect = document.getElementById('villageSelect');

            // Load Regencies on startup
            fetch('{{ route("ajax.regions.regencies") }}')
                .then(res => res.json())
                .then(data => {
                    data.forEach(item => {
                        const opt = new Option(item.name, item.id);
                        regencySelect.add(opt);
                    });
                })
                .catch(err => console.error(err));

            regencySelect.addEventListener('change', function() {
                const regencyId = this.value;
                districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                villageSelect.innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';
                villageSelect.disabled = true;

                if (regencyId) {
                    districtSelect.disabled = false;
                    fetch(`{{ route("ajax.regions.districts") }}?regency_id=${regencyId}`)
                        .then(res => res.json())
                        .then(data => {
                            data.forEach(item => {
                                districtSelect.add(new Option(item.name, item.id));
                            });
                        });
                } else {
                    districtSelect.disabled = true;
                }
            });

            districtSelect.addEventListener('change', function() {
                const districtId = this.value;
                villageSelect.innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';

                if (districtId) {
                    villageSelect.disabled = false;
                    fetch(`{{ route("ajax.regions.villages") }}?district_id=${districtId}`)
                        .then(res => res.json())
                        .then(data => {
                            data.forEach(item => {
                                villageSelect.add(new Option(item.name, item.id));
                            });
                        });
                } else {
                    villageSelect.disabled = true;
                }
            });

            // SweetAlert Error Handling
            @if($errors->any())
                let errorMsg = '';
                @foreach($errors->all() as $error)
                    errorMsg += '{{ $error }}\n';
                @endforeach
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Mendaftar',
                    text: errorMsg,
                    confirmButtonColor: '#059669'
                });
            @endif
        </script>
    </body>
    </html>
@endsection

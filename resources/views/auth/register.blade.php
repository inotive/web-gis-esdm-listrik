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

            .validation-feedback {
                display: flex;
                align-items: center;
                gap: 6px;
                font-size: 13px;
                margin-top: 6px;
                font-weight: 500;
            }

            .validation-feedback.success {
                color: #059669;
            }

            .validation-feedback.error {
                color: #DC2626;
            }

            .validation-feedback.warning {
                color: #F59E0B;
            }

            .form-input.valid {
                border-color: #059669;
            }

            .form-input.invalid {
                border-color: #DC2626;
            }

            .validation-icon {
                font-size: 16px;
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

                    <form id="registerForm" method="POST" action="{{ route('register.perform') }}" enctype="multipart/form-data">
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



                        <div class="form-group" id="companyNameGroup" style="display: none;">
                            <label class="form-label">Nama Perusahaan (Nama PT)</label>
                            <input class="form-input" type="text" name="company_name" placeholder="Masukkan Nama Perusahaan" />
                        </div>

                        <div class="form-group">
                            <label class="form-label">Username</label>
                            <input class="form-input" type="text" name="username" id="username" placeholder="Masukkan Username" required />
                            <div id="usernameValidation" class="validation-feedback" style="display:none;"></div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" id="jabatanLabel">Jabatan</label>
                            <input class="form-input" type="text" name="jabatan" placeholder="Masukkan Jabatan" required />
                        </div>
                        
                        <!-- Rest of the form -->

                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input class="form-input" type="email" name="email" id="email" placeholder="email@mail.com" required />
                            <div id="emailValidation" class="validation-feedback" style="display:none;"></div>
                        </div>
                        
                        <!-- ... -->
                        


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

                        <div class="form-group" id="documentGroup">
                            <label class="form-label">Dokumen Pendukung (Opsional)</label>
                            <div style="margin-bottom: 8px; font-size: 13px; color: var(--gray-600);">Upload dokumen pendukung (contoh: SK Kepala Desa / Surat Resmi)</div>
                            <input class="form-input" type="file" name="document_verification" accept=".pdf" />
                            <div style="font-size: 12px; color: var(--gray-500); margin-top: 4px;">Hanya format PDF yang diperbolehkan</div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Kata Sandi</label>
                            <div class="password-wrapper">
                                <input class="form-input" type="password" name="password" id="password" placeholder="Minimal 8 karakter" required minlength="8" />
                                <div class="password-toggle" onclick="togglePassword('password')">
                                    <i class="ri-eye-off-line"></i>
                                </div>
                            </div>
                            <div id="passwordValidation" class="validation-feedback" style="display:none;"></div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Ulangi Kata Sandi</label>
                            <div class="password-wrapper">
                                <input class="form-input" type="password" name="password_confirmation" id="password_confirm" placeholder="Ulangi kata sandi" required minlength="8" />
                                <div class="password-toggle" onclick="togglePassword('password_confirm')">
                                    <i class="ri-eye-off-line"></i>
                                </div>
                            </div>
                            <div id="passwordConfirmValidation" class="validation-feedback" style="display:none;"></div>
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
            function toggleForm(role) {
                const addressLabel = document.getElementById('addressLabel');
                const companyGroup = document.getElementById('companyNameGroup');
                const companyInput = document.querySelector('input[name="company_name"]');
                const documentGroup = document.getElementById('documentGroup');

                if (role === 'desa') {
                    addressLabel.innerText = 'Alamat Kantor Desa';
                    if (companyGroup) {
                        companyGroup.style.display = 'none';
                        if (companyInput) {
                            companyInput.required = false;
                            companyInput.value = '';
                        }
                    }
                    if (documentGroup) documentGroup.style.display = 'block';

                    if (jabatanInput) jabatanInput.placeholder = 'Masukkan Jabatan (Cth: Kepala Desa)';
                } else {
                    addressLabel.innerText = 'Alamat Perusahaan';
                    if (companyGroup) {
                        companyGroup.style.display = 'block';
                        if (companyInput) companyInput.required = true;
                    }
                    if (documentGroup) documentGroup.style.display = 'none';
                    
                    if (jabatanInput) jabatanInput.placeholder = 'Masukkan Jabatan (Cth: Direktur)';
                }
            }

            function togglePassword(id) {
                const input = document.getElementById(id);
                const wrapper = input.parentElement;
                const toggleBtn = wrapper.querySelector('.password-toggle');
                const icon = toggleBtn.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('ri-eye-off-line');
                    icon.classList.add('ri-eye-line');
                } else {
                    input.type = 'password';
                    icon.classList.remove('ri-eye-line');
                    icon.classList.add('ri-eye-off-line');
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

            // ========== LIVE VALIDATION ==========
            
            // Username validation
            const usernameInput = document.getElementById('username');
            const usernameValidation = document.getElementById('usernameValidation');
            let usernameTimeout;
            let usernameValid = false;

            usernameInput.addEventListener('input', function() {
                const username = this.value.trim();
                
                // Clear previous timeout
                clearTimeout(usernameTimeout);
                
                // Reset if empty
                if (username.length === 0) {
                    usernameValidation.style.display = 'none';
                    this.classList.remove('valid', 'invalid');
                    usernameValid = false;
                    return;
                }

                // Check minimum length
                if (username.length < 3) {
                    showValidation(usernameValidation, 'error', 'Username minimal 3 karakter');
                    this.classList.remove('valid');
                    this.classList.add('invalid');
                    usernameValid = false;
                    return;
                }

                // Check format (alphanumeric and underscore only)
                const usernameRegex = /^[a-zA-Z0-9_]+$/;
                if (!usernameRegex.test(username)) {
                    showValidation(usernameValidation, 'error', 'Username hanya boleh huruf, angka, dan underscore');
                    this.classList.remove('valid');
                    this.classList.add('invalid');
                    usernameValid = false;
                    return;
                }

                // Show loading
                showValidation(usernameValidation, 'warning', 'Memeriksa ketersediaan...');
                this.classList.remove('valid', 'invalid');

                // Debounce AJAX call
                usernameTimeout = setTimeout(() => {
                    fetch(`/api/check-username?username=${encodeURIComponent(username)}`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.available) {
                                showValidation(usernameValidation, 'success', '✓ Username tersedia');
                                usernameInput.classList.remove('invalid');
                                usernameInput.classList.add('valid');
                                usernameValid = true;
                            } else {
                                showValidation(usernameValidation, 'error', '✗ Username sudah digunakan');
                                usernameInput.classList.remove('valid');
                                usernameInput.classList.add('invalid');
                                usernameValid = false;
                            }
                        })
                        .catch(err => {
                            showValidation(usernameValidation, 'error', 'Gagal memeriksa username');
                            usernameInput.classList.remove('valid', 'invalid');
                            usernameValid = false;
                        });
                }, 500);
            });

            // Email validation
            const emailInput = document.getElementById('email');
            const emailValidation = document.getElementById('emailValidation');
            let emailTimeout;
            let emailValid = false;

            emailInput.addEventListener('input', function() {
                const email = this.value.trim();
                
                // Clear previous timeout
                clearTimeout(emailTimeout);
                
                // Reset if empty
                if (email.length === 0) {
                    emailValidation.style.display = 'none';
                    this.classList.remove('valid', 'invalid');
                    emailValid = false;
                    return;
                }

                // Check email format
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    showValidation(emailValidation, 'error', 'Format email tidak valid');
                    this.classList.remove('valid');
                    this.classList.add('invalid');
                    emailValid = false;
                    return;
                }

                // Show loading
                showValidation(emailValidation, 'warning', 'Memeriksa ketersediaan...');
                this.classList.remove('valid', 'invalid');

                // Debounce AJAX call
                emailTimeout = setTimeout(() => {
                    fetch(`/api/check-email?email=${encodeURIComponent(email)}`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.available) {
                                showValidation(emailValidation, 'success', '✓ Email tersedia');
                                emailInput.classList.remove('invalid');
                                emailInput.classList.add('valid');
                                emailValid = true;
                            } else {
                                showValidation(emailValidation, 'error', '✗ Email sudah terdaftar');
                                emailInput.classList.remove('valid');
                                emailInput.classList.add('invalid');
                                emailValid = false;
                            }
                        })
                        .catch(err => {
                            showValidation(emailValidation, 'error', 'Gagal memeriksa email');
                            emailInput.classList.remove('valid', 'invalid');
                            emailValid = false;
                        });
                }, 500);
            });

            // Password validation
            const passwordInput = document.getElementById('password');
            const passwordValidation = document.getElementById('passwordValidation');
            const passwordConfirmInput = document.getElementById('password_confirm');
            const passwordConfirmValidation = document.getElementById('passwordConfirmValidation');
            let passwordValid = false;
            let passwordConfirmValid = false;

            passwordInput.addEventListener('input', function() {
                const password = this.value;
                
                if (password.length === 0) {
                    passwordValidation.style.display = 'none';
                    this.classList.remove('valid', 'invalid');
                    passwordValid = false;
                    return;
                }

                // Check minimum length
                if (password.length < 8) {
                    showValidation(passwordValidation, 'error', 'Password minimal 8 karakter');
                    this.classList.remove('valid');
                    this.classList.add('invalid');
                    passwordValid = false;
                    return;
                }

                // Check for at least one letter and one number
                const hasLetter = /[a-zA-Z]/.test(password);
                const hasNumber = /[0-9]/.test(password);

                if (!hasLetter || !hasNumber) {
                    showValidation(passwordValidation, 'warning', 'Password harus kombinasi huruf dan angka');
                    this.classList.remove('valid');
                    this.classList.add('invalid');
                    passwordValid = false;
                    return;
                }

                // Password is strong
                showValidation(passwordValidation, 'success', '✓ Password kuat');
                this.classList.remove('invalid');
                this.classList.add('valid');
                passwordValid = true;

                // Re-validate password confirmation if it has value
                if (passwordConfirmInput.value.length > 0) {
                    passwordConfirmInput.dispatchEvent(new Event('input'));
                }
            });

            // Password confirmation validation
            passwordConfirmInput.addEventListener('input', function() {
                const password = passwordInput.value;
                const passwordConfirm = this.value;
                
                if (passwordConfirm.length === 0) {
                    passwordConfirmValidation.style.display = 'none';
                    this.classList.remove('valid', 'invalid');
                    passwordConfirmValid = false;
                    return;
                }

                if (password !== passwordConfirm) {
                    showValidation(passwordConfirmValidation, 'error', '✗ Password tidak cocok');
                    this.classList.remove('valid');
                    this.classList.add('invalid');
                    passwordConfirmValid = false;
                } else {
                    showValidation(passwordConfirmValidation, 'success', '✓ Password cocok');
                    this.classList.remove('invalid');
                    this.classList.add('valid');
                    passwordConfirmValid = true;
                }
            });

            // Helper function to show validation message
            function showValidation(element, type, message) {
                element.style.display = 'flex';
                element.className = `validation-feedback ${type}`;
                
                let icon = '';
                if (type === 'success') icon = '<i class="ri-checkbox-circle-fill validation-icon"></i>';
                else if (type === 'error') icon = '<i class="ri-close-circle-fill validation-icon"></i>';
                else if (type === 'warning') icon = '<i class="ri-loader-4-line validation-icon"></i>';
                
                element.innerHTML = icon + '<span>' + message + '</span>';
            }

            // Form submit validation
            document.getElementById('registerForm').addEventListener('submit', function(e) {
                if (!usernameValid) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Username Tidak Valid',
                        text: 'Pastikan username tersedia dan valid',
                        confirmButtonColor: '#059669'
                    });
                    return false;
                }

                if (!emailValid) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Email Tidak Valid',
                        text: 'Pastikan email valid dan belum terdaftar',
                        confirmButtonColor: '#059669'
                    });
                    return false;
                }

                if (!passwordValid) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Password Tidak Valid',
                        text: 'Password harus minimal 8 karakter dengan kombinasi huruf dan angka',
                        confirmButtonColor: '#059669'
                    });
                    return false;
                }

                if (!passwordConfirmValid) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Password Tidak Cocok',
                        text: 'Pastikan password dan konfirmasi password sama',
                        confirmButtonColor: '#059669'
                    });
                    return false;
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

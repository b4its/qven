@auth
    @php
        $user = Auth::user();
        
        // Tentukan prefix dan URL tujuan berdasarkan role
        if ($user->role === 'superadmin') {
            $redirectUrl = route('filament.superadmin.pages.dashboard');
        } elseif ($user->role === 'admin') {
            $redirectUrl = route('filament.admin.pages.dashboard');
        } elseif ($user->role === 'karyawan') {
            $redirectUrl = route('filament.karyawan.pages.dashboard');
        } else {
            // Role 'penerima' atau fallback
            // WAJIB: Masukkan parameter tenant untuk menghindari UrlGenerationException
            $redirectUrl = route('filament.penerima.pages.dashboard', ['tenant' => $user->vendor_id]);
        }
    @endphp

    <script>
        window.location.href = "{!! $redirectUrl !!}";
    </script>
@endauth

@guest
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - QVen</title>
    
    <!-- Mempertahankan file CSS eksternal bawaan template kamu -->
    <link rel="stylesheet" href="style.css">
    
    <!-- Font Awesome untuk icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    background-color: #eaf2fd; /* Warna background biru muda sesuai gambar */
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

.container {
    width: 100%;
    display: flex;
    justify-content: center;
    padding: 20px;
}

.login-card {
    background: #ffffff;
    width: 100%;
    max-width: 800px;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.card-content {
    display: flex;
    padding: 40px 30px;
    align-items: center;
}

.logo-section {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.logo-qven {
    width: 70%;
    max-width: 210px;
    height: auto;
}

/* Styling untuk placeholder logo */
.logo-placeholder {
    width: 100%;
    max-width: 300px;
    height: 250px;
    background-color: #f8fafc;
    border: 2px dashed #cbd5e1;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 8px;
    color: #64748b;
    font-weight: 500;
    text-align: center;
    padding: 20px;
}

.logo-qven {
    width: 70%;
    max-width: 210px;
    height: auto;
    display: block;
}

.form-section {
    flex: 1;
    padding: 0 40px;
}

.form-section h2 {
    font-size: 24px;
    color: #0f172a;
    font-weight: 700;
    margin-bottom: 4px;
}

.subtitle {
    font-size: 13px;
    color: #64748b;
    margin-bottom: 28px;
}

.input-group {
    margin-bottom: 20px;
}

.input-group label {
    display: block;
    font-size: 11px;
    font-weight: 700;
    color: #1d4ed8; /* Biru gelap untuk label */
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.input-wrapper i {
    position: absolute;
    left: 14px;
    color: #94a3b8;
    font-size: 14px;
}

.input-wrapper input {
    width: 100%;
    padding: 12px 15px 12px 38px;
    border: 1px solid transparent;
    background-color: #f1f5f9;
    border-radius: 6px;
    font-size: 13px;
    color: #334155;
    outline: none;
    transition: all 0.3s ease;
}

.input-wrapper input:focus {
    background-color: #fff;
    border-color: #bfdbfe;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.input-wrapper input::placeholder {
    color: #94a3b8;
}

.login-btn {
    width: 100%;
    padding: 12px;
    background: linear-gradient(90deg, #1e3a8a 0%, #2563eb 100%);
    border: none;
    border-radius: 6px;
    color: white;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    margin-top: 5px;
    transition: opacity 0.3s;
}

.login-btn:hover {
    opacity: 0.9;
}

.register-link {
    text-align: center;
    font-size: 12px;
    color: #64748b;
    margin-top: 15px;
}

.register-link a {
    color: #1d4ed8;
    font-weight: 700;
    text-decoration: none;
}

.card-footer {
    background: linear-gradient(90deg, #1e3a8a 0%, #2563eb 100%);
    padding: 12px;
    text-align: center;
}

.card-footer p {
    color: white;
    font-size: 11px;
    font-weight: 500;
    letter-spacing: 0.3px;
}

/* Responsif untuk layar kecil / mobile */
@media (max-width: 768px) {
    .card-content {
        flex-direction: column;
        padding: 30px 20px;
    }
    .form-section {
        padding: 0 10px;
        width: 100%;
        margin-top: 30px;
    }
}

    </style>
</head>
<body>
    <div class="container">
        <div class="login-card">
            <div class="card-content">
                <div class="logo-section">
                    <!-- Menggunakan fungsi asset() Laravel agar gambar aset logo tidak pecah/404 -->
                    <img src="{{ asset('assets/logo/Logo-mono.png') }}" alt="Logo QVen" class="logo-qven">
                </div>
                
                <div class="form-section">
                    <h2>Selamat Datang</h2>
                    <p class="subtitle">Masuk ke Akun untuk melanjutkan</p>

                    <!-- MENYESUAIKAN: Menambahkan method, action, dan token CSRF Laravel -->
                    <form id="loginForm" method="POST" action="{{ route('auth.login.store') }}">
                        @csrf

                        <div class="input-group">
                            <!-- MENYESUAIKAN: Label diubah ke EMAIL & input type/name disesuaikan dengan backend Laravel -->
                            <label for="email">EMAIL</label>
                            <div class="input-wrapper">
                                <i class="fa-regular fa-envelope"></i>
                                <input type="email" id="email" name="email" placeholder="Masukkan Email Anda" required>
                            </div>
                        </div>

                        <div class="input-group">
                            <label for="password">PASSWORD</label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-lock"></i>
                                <!-- MENYESUAIKAN: Menambahkan atribut name="password" agar terbaca oleh Auth Laravel -->
                                <input type="password" id="password" name="password" placeholder="Masukkan Sandi Anda" required>
                            </div>
                        </div>

                        <button type="submit" class="login-btn">Masuk &rarr;</button>
                    </form>

                    <!-- MENYESUAIKAN: Link daftar dan halaman utama diarahkan ke Route Laravel -->
                    <p class="register-link">Belum punya Akun ? <a href="{{ route('auth.register.index') }}">Daftar</a></p>
                    <p class="register-link" style="margin-top: 5px;"><a href="{{ route('welcome') }}" style="color: #64748b; font-weight: 400;">&larr; Halaman Utama</a></p>
                </div>
            </div>
            
            <div class="card-footer">
                <p>Quality Vendor and Nutrition Makan Bergizi Gratis</p>
            </div>
        </div>
    </div>

    <!-- Mempertahankan file JS eksternal bawaan template kamu -->
    <script src="script.js"></script>
</body>
</html>
@endguest
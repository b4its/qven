@auth
    @php
        // Ambil data user
        $user = Auth::user();
        
        // Tentukan prefix berdasarkan role
        if ($user->role === 'superadmin') {
            $routePrefix = 'superadmin';
        } elseif ($user->role === 'admin') {
            $routePrefix = 'admin';
        } elseif ($user->role === 'karyawan') {
            $routePrefix = 'karyawan';
        } else {
            $routePrefix = 'penerima';
        }

        // Buat URL tujuan
        $redirectUrl = route('filament.' . $routePrefix . '.pages.dashboard');
    @endphp

    <script>
        window.location.href = "{{ $redirectUrl }}";
    </script>
@endauth

@guest
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar QVen - Integrated</title>
    @include('favicon.default')
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
    body { background-color: #F0F9FF; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px 0; }
    .container { width: 100%; display: flex; justify-content: center; padding: 20px; }
    .register-card { background: #ffffff; width: 100%; max-width: 800px; border-radius: 16px; box-shadow: 0 10px 30px rgba(59, 130, 246, 0.15); overflow: hidden; display: flex; flex-direction: column; }
    .card-content { display: flex; padding: 30px; align-items: stretch; }
    .logo-section { flex: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 20px; background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%); border-radius: 12px; margin-right: 20px; }
    .logo-qven { width: 100%; max-width: 200px; height: auto; object-fit: contain; margin-bottom: 20px; }
    
    .form-section { flex: 1.5; padding: 0 10px; position: relative; }
    .form-section h2 { font-size: 24px; color: #1e40af; font-weight: 700; margin-bottom: 5px; }
    .subtitle { font-size: 12px; color: #64748b; margin-bottom: 20px; }

    /* Progress Steps */
    .progress-container { display: flex; justify-content: space-between; margin-bottom: 25px; position: relative; }
    .progress-container::before { content: ''; position: absolute; top: 50%; left: 0; right: 0; height: 2px; background: #e2e8f0; transform: translateY(-50%); z-index: 1; }
    .progress-step { position: relative; z-index: 2; background: white; width: 28px; height: 28px; border-radius: 50%; border: 2px solid #e2e8f0; display: flex; justify-content: center; align-items: center; font-size: 11px; font-weight: 700; color: #94a3b8; transition: all 0.3s ease; }
    .progress-step.active { border-color: #2563eb; background: #2563eb; color: white; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2); }
    .progress-step.completed { border-color: #2563eb; background: #2563eb; color: white; }

    /* Form Steps (Multi-step Logic) */
    .form-step { display: none; animation: fadeIn 0.4s ease; }
    .form-step.active { display: block; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    .input-group { margin-bottom: 15px; }
    .input-group label { display: block; font-size: 10px; font-weight: 700; color: #3b82f6; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
    .input-wrapper { position: relative; display: flex; align-items: center; }
    .input-wrapper i { position: absolute; left: 14px; color: #94a3b8; font-size: 14px; }
    .input-wrapper .right-icon { left: auto; right: 14px; cursor: pointer; color: #3b82f6; }
    
    .input-wrapper input, .input-wrapper select, .input-wrapper textarea { width: 100%; padding: 10px 14px 10px 38px; border: 2px solid #f1f5f9; background-color: #f8fafc; border-radius: 8px; font-size: 12px; color: #334155; outline: none; transition: all 0.3s ease; }
    .input-wrapper textarea { resize: none; padding-top: 12px; }
    .input-wrapper i.fa-map-pin { top: 14px; transform: none; } /* Align icon for textarea */
    
    .input-wrapper select { cursor: pointer; appearance: none; background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%2394a3b8%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E"); background-repeat: no-repeat; background-position: right 12px top 50%; background-size: 10px auto; padding-right: 30px; }
    .input-wrapper input:focus, .input-wrapper select:focus, .input-wrapper textarea:focus { background-color: #fff; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15); }
    .input-wrapper.clickable { cursor: pointer; }
    .input-wrapper.clickable input { cursor: pointer; }

    .btn-group { display: flex; gap: 10px; margin-top: 20px; }
    .btn { flex: 1; padding: 12px; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.3s; display: flex; justify-content: center; align-items: center; gap: 8px; }
    .btn-outline { background: white; border: 2px solid #e2e8f0; color: #64748b; }
    .btn-outline:hover { background: #f8fafc; border-color: #cbd5e1; }
    .btn-primary { background: linear-gradient(90deg, #1e40af 0%, #3b82f6 100%); color: white; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); }
    .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 6px 15px rgba(59, 130, 246, 0.4); }

    .login-link { text-align: center; font-size: 11px; color: #64748b; margin-top: 20px; }
    .login-link a { color: #3b82f6; font-weight: 700; text-decoration: none; }
    .card-footer { background: linear-gradient(90deg, #1e40af 0%, #3b82f6 100%); padding: 12px; text-align: center; }
    .card-footer p { color: white; font-size: 11px; font-weight: 500; letter-spacing: 0.3px; }

    /* MODALS */
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); display: none; justify-content: center; align-items: center; z-index: 1000; }
    .modal-overlay.active { display: flex; }
    .modal-card { background: white; border-radius: 16px; overflow: hidden; width: 90%; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
    .modal-header { padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; }
    .header-title { display: flex; align-items: center; gap: 10px; }
    .header-title .dot { width: 10px; height: 10px; background: #3b82f6; border-radius: 50%; }
    .header-title h3 { font-size: 16px; color: #1e293b; font-weight: 600; }
    .close-modal { background: none; border: none; font-size: 24px; cursor: pointer; color: #94a3b8; transition: color 0.3s; }
    .close-modal:hover { color: #ef4444; }

    /* MAP MODAL SPECIFIC */
    .map-modal-size { max-width: 600px; }
    #map { width: 100%; height: 350px; }
    .map-footer { padding: 15px 20px; display: flex; gap: 10px; background: #f8fafc; border-top: 1px solid #f1f5f9; }
    
    @media (max-width: 768px) {
        .card-content { flex-direction: column; padding: 20px; }
        .logo-section { margin-right: 0; margin-bottom: 20px; }
        .form-section { padding: 0; width: 100%; }
    }
</style>
</head>
<body>
    <div class="container">
        <div class="register-card">
            <div class="card-content">
                <div class="logo-section">
                    <img src="{{ asset('assets/logo/Logo-mono.png') }}" alt="Logo QVen" class="logo-qven">
                    <h3 style="color: #1e40af; font-size: 16px; font-weight: 700; text-align: center;">Portal Registrasi</h3>
                    <p style="color: #64748b; font-size: 11px; text-align: center; margin-top: 5px;">Akses layanan gizi terintegrasi</p>
                </div>
                
                <div class="form-section">
                    <h2>Buat Akun</h2>
                    <p class="subtitle">Lengkapi data diri Anda (Langkah <span id="currentStepText">1</span> dari 3)</p>

                    <!-- Progress Indicator -->
                    <div class="progress-container">
                        <div class="progress-step active" id="stepIndicator1">1</div>
                        <div class="progress-step" id="stepIndicator2">2</div>
                        <div class="progress-step" id="stepIndicator3">3</div>
                    </div>

                    <form id="registerForm" method="post" action="{{ route('auth.register.create') }}">
                        @csrf
                        
                        <!-- STEP 1: Data Diri -->
                        <div class="form-step active" id="step1">
                            <div class="input-group">
                                <label>EMAIL</label>
                                <div class="input-wrapper">
                                    <i class="fa-regular fa-envelope"></i>
                                    <input type="email" name="email" placeholder="Masukkan Email Anda" required>
                                </div>
                            </div>
                            <div class="input-group">
                                <label>NAMA LENGKAP</label>
                                <div class="input-wrapper">
                                    <i class="fa-regular fa-user"></i>
                                    <input type="text" name="username" placeholder="Masukkan Nama Anda" required>
                                </div>
                            </div>

                            <div class="btn-group">
                                <button type="button" class="btn btn-primary" onclick="nextStep(1)">Selanjutnya <i class="fa-solid fa-arrow-right"></i></button>
                            </div>
                        </div>

                        <!-- STEP 2: Lokasi & Alamat -->
                        <div class="form-step" id="step2">
                            <div class="input-group">
                                <label>TITIK KOORDINAT LOKASI</label>
                                <div class="input-wrapper clickable" id="openMap">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <input type="text" id="lokasiInput" name="lokasi" placeholder="Klik ikon di kanan untuk pilih lokasi" readonly required>
                                    <i class="fa-solid fa-crosshairs right-icon"></i>
                                </div>
                            </div>
                            
                            <div class="input-group">
                                <label>NIK</label>
                                <div class="input-wrapper">
                                    <i class="fa-regular fa-id-card"></i>
                                    <input type="text" name="nik" placeholder="Masukkan NIK (16 Digit)" maxlength="16" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                                </div>
                            </div>

                            <div class="btn-group">
                                <button type="button" class="btn btn-outline" onclick="prevStep(2)"><i class="fa-solid fa-arrow-left"></i> Kembali</button>
                                <button type="button" class="btn btn-primary" onclick="nextStep(2)">Selanjutnya <i class="fa-solid fa-arrow-right"></i></button>
                            </div>
                        </div>

                        <!-- STEP 3: Penugasan & Keamanan -->
                        <div class="form-step" id="step3">
                            <div class="input-group">
                                <label>VENDOR</label>
                                <div class="input-wrapper">
                                    <i class="fa-solid fa-store"></i>
                                    <select name="vendor_id" required>
                                        <option value="" disabled selected hidden>Pilih Vendor...</option>
                                        @forelse ($vendorList as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @empty
                                            <option value="" disabled>Belum ada vendor tersedia</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="input-group">
                                <label>INSTANSI PENERIMA</label>
                                <div class="input-wrapper">
                                    <i class="fa-solid fa-building"></i>
                                    <select name="instansi_penerima_id" required>
                                        <option value="" disabled selected hidden>Pilih Instansi...</option>
                                        @forelse ($instansiList as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @empty
                                            <option value="" disabled>Belum ada instansi tersedia</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>

                            <div class="btn-group">
                                <button type="button" class="btn btn-outline" onclick="prevStep(3)"><i class="fa-solid fa-arrow-left"></i> Kembali</button>
                                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Daftar Sekarang</button>
                            </div>
                        </div>

                    </form>
                    <p class="login-link">Sudah Punya Akun? <a href="{{ route('auth.login.index') }}">Masuk Disini</a></p>
                </div>
            </div>
            <div class="card-footer">
                <p>Quality Vendor and Nutrition Makan Bergizi Gratis</p>
            </div>
        </div>
    </div>

    <!-- Modal Map -->
    <div class="modal-overlay" id="mapModal">
        <div class="modal-card map-modal-size">
            <div class="modal-header">
                <div class="header-title">
                    <span class="dot"></span>
                    <h3>Pilih Lokasi Anda</h3>
                </div>
                <button class="close-modal" id="closeModal">&times;</button>
            </div>
            <div class="modal-body">
                <div id="map"></div>
            </div>
            <div class="modal-footer map-footer">
                <button type="button" class="btn btn-outline" id="cancelMap">Batal</button>
                <button type="button" class="btn btn-primary" id="saveCoords">Simpan Koordinat</button>
            </div>
        </div>
    </div>

    @include('notification.default')

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // --- MULTI-STEP FORM LOGIC ---
        function validateStep(stepIndex) {
            const stepElement = document.getElementById(`step${stepIndex}`);
            const inputs = stepElement.querySelectorAll('input[required], select[required], textarea[required]');
            let isValid = true;

            inputs.forEach(input => {
                if (!input.checkValidity()) {
                    input.reportValidity();
                    isValid = false;
                }
            });
            return isValid;
        }

        function nextStep(currentStepIndex) {
            if (validateStep(currentStepIndex)) {
                document.getElementById(`step${currentStepIndex}`).classList.remove('active');
                document.getElementById(`stepIndicator${currentStepIndex}`).classList.add('completed');
                
                const nextIndex = currentStepIndex + 1;
                document.getElementById(`step${nextIndex}`).classList.add('active');
                document.getElementById(`stepIndicator${nextIndex}`).classList.add('active');
                document.getElementById('currentStepText').innerText = nextIndex;
            }
        }

        function prevStep(currentStepIndex) {
            document.getElementById(`step${currentStepIndex}`).classList.remove('active');
            document.getElementById(`stepIndicator${currentStepIndex}`).classList.remove('active');
            
            const prevIndex = currentStepIndex - 1;
            document.getElementById(`step${prevIndex}`).classList.add('active');
            document.getElementById(`stepIndicator${prevIndex}`).classList.remove('completed');
            document.getElementById('currentStepText').innerText = prevIndex;
        }

        // --- PASSWORD TOGGLE LOGIC ---
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('togglePasswordIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // --- MAP MODAL LOGIC ---
        document.addEventListener('DOMContentLoaded', () => {
            const mapModal = document.getElementById('mapModal');
            const openMapBtn = document.getElementById('openMap');
            const lokasiInput = document.getElementById('lokasiInput');
            const closeModalBtn = document.getElementById('closeModal');
            const cancelMapBtn = document.getElementById('cancelMap');
            const saveCoordsBtn = document.getElementById('saveCoords');

            let map;
            let marker;
            let tempCoords = null;

            const openMapFunc = () => {
                mapModal.classList.add('active');
                // Beri waktu sedikit untuk transisi CSS selesai sebelum render peta
                setTimeout(() => {
                    if (!map) {
                        map = L.map('map').setView([-0.0227, 109.3425], 13); // Default Pontianak
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
                        
                        map.on('click', (e) => {
                            const lat = e.latlng.lat;
                            const lng = e.latlng.lng;
                            if (marker) {
                                marker.setLatLng([lat, lng]);
                            } else {
                                marker = L.marker([lat, lng]).addTo(map);
                            }
                            tempCoords = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                        });
                    }
                    map.invalidateSize();
                }, 300);
            };

            const closeMapFunc = () => { 
                mapModal.classList.remove('active'); 
            };

            openMapBtn.addEventListener('click', openMapFunc);
            closeModalBtn.addEventListener('click', closeMapFunc);
            cancelMapBtn.addEventListener('click', closeMapFunc);
            
            saveCoordsBtn.addEventListener('click', () => {
                if(tempCoords) {
                    lokasiInput.value = tempCoords;
                    closeMapFunc();
                } else {
                    alert("Silakan klik pada peta untuk memilih lokasi Anda.");
                }
            });
        });
    </script>
</body>
</html>
@endguest
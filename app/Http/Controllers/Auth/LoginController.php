<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("authenticate.login");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi input form
        $credentials = $request->validate([
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // 2. Coba melakukan autentikasi
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            
            // 3. Regenerasi session untuk keamanan (mencegah session fixation)
            $request->session()->regenerate();
            
            // 4. Ambil data user yang berhasil login dari database
            $user = Auth::user();

            Notification::make()
                ->title('Login Berhasil')
                ->body('Selamat datang kembali.')
                ->success()
                ->send();

            // 5. Redirect sesuai role & kebutuhan Tenant
            if ($user->role === 'superadmin') {
                return redirect()->route('filament.superadmin.pages.dashboard');
            } elseif ($user->role === 'admin') {
                return redirect()->route('filament.admin.pages.dashboard');
            } elseif ($user->role === 'karyawan') {
                return redirect()->route('filament.karyawan.pages.dashboard');
            } else {
                // Fallback untuk role 'penerima' yang mewajibkan parameter tenant (ID Vendor)
                return redirect()->route('filament.penerima.pages.dashboard', [
                    'tenant' => $user->vendor_id
                ]);
            }
        } // <--- KURUNG KURAWAL TUTUP INI SEBELUMNYA HILANG DI KODEMU

        // 6. Jika gagal login, kembalikan ke form dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
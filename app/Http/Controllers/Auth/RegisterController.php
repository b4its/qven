<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CalonPenerima;
use App\Models\InstansiPenerima;
use App\Models\Profile;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $vendorList = Vendor::all();
        $instansiList = InstansiPenerima::all();
        return view("authenticate.register", [
            'instansiList'=> $instansiList,
            'vendorList'=> $vendorList
            ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // 1. Validasi disesuaikan dengan 'name="username"' dari form HTML
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,name'], // Asumsi tabel users kolom name
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'nik'      => ['required', 'numeric', 'digits_between:1,12'],
            'lokasi'    => ['required', 'string'],
            'instansi_penerima_id' => ['required', 'string'],
            'vendor_id' => ['required', 'string'],
        ]);

        try {
            // 2. Gunakan DB Transaction seperti yang kamu rencanakan
            DB::beginTransaction();
            
            CalonPenerima::create([
                'name'  => $validated['username'], // Map dari input 'username' ke kolom 'name'
                'email' => $validated['email'],
                'nik'   => $validated['nik'], 
                'alamat'   => $validated['lokasi'], 
                'status'   => 0, 
                'instansi_penerima_id' => $validated['instansi_penerima_id'],// Set status awal sebagai pending
                'vendor_id' => $validated['vendor_id'],// Set status awal sebagai pending
            ]);

            DB::commit();

            return redirect()
                ->route('auth.register.index')
                ->with('success', 'Registrasi berhasil! Silakan untuk menunggu konfirmasi dari vendor.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Jika gagal insert ke DB, kembalikan ke halaman register beserta errornya
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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

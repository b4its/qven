<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Panel;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;


use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
class User extends Authenticatable implements FilamentUser, HasTenants
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'vendor_id',
        'instansi_penerima_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Menentukan cabang mana saja yang bisa diakses user ini
    public function getTenants(Panel $panel): Collection
    {
        if ($this->role === 'superadmin') {
            return Vendor::all(); // Superadmin bisa akses semua cabang
        }

        // User biasa hanya bisa akses cabang tempat dia bernaung
        return Vendor::where('id', $this->vendor_id)->get();
    }

    // Izin untuk mengakses tenant tertentu
    public function canAccessTenant(Model $tenant): bool
    {
        if ($this->role === 'superadmin') {
            return true;
        }

        return $this->vendor_id === $tenant->id;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // Izinkan superadmin mengakses semua panel (admin, superadmin, kasir, dll)
        if ($this->role === 'superadmin') {
            return true;
        }

        return match($panel->getId()) {
            'superadmin' => $this->role === 'superadmin',
            'admin' => $this->role === 'admin',
            'karyawan' => $this->role === 'karyawan',
            'penerima' => $this->role === 'penerima',
            default => false,
        };
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function vendor(): BelongsTo
    {
        // Jika nama kolom di tabel users adalah vendor_id
        return $this->belongsTo(Vendor::class); 
    }
    public function instansiPenerima(): BelongsTo
    {
        // Jika nama kolom di tabel users adalah vendor_id
        return $this->belongsTo(InstansiPenerima::class); 
    }
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    // Relasi ke Bukti Penerimaan
    public function buktiPenerimaan(): HasMany
    {
        return $this->hasMany(BuktiKotakDiterima::class);
    }

    public function aktifitases(): HasMany
    {
        return $this->hasMany(Aktifitas::class);
    }

    protected static function boot()
    {
        parent::boot(); 

        // 1. Log saat data dibuat (Create)
        static::created(function ($user) {
            // Gunakan null safe operator dan fallback ke 'Sistem' jika Auth::user() null (misal dari Seeder)
            $actorName = Auth::user()?->name ?? 'Sistem';

            KotakMBG::catatLogAktifitas(
                $user,                          // $model: Instance user yang dibuat
                'created',                      // $action
                'Pembuatan Akun Pengguna',      // $title
                "Akun dengan nama {$user->name} telah berhasil dibuat oleh {$actorName}.", // $description
                null,                           // $oldData
                $user->getAttributes(),         // $newData (Gunakan getAttributes agar mendapat data bersih)
            );
        });

        // 2. Log saat data diperbarui (Update) - BARU DITAMBAHKAN
        static::updated(function ($user) {
            $actorName = Auth::user()?->name ?? 'Sistem';

            KotakMBG::catatLogAktifitas(
                $user,                          // $model: Instance user yang diupdate
                'updated',                      // $action
                'Pembaruan Akun Pengguna',      // $title
                "Informasi akun dengan nama {$user->name} telah diperbarui oleh {$actorName}.", // $description
                $user->getOriginal(),           // $oldData (Data sebelum disimpan)
                $user->getChanges(),            // $newData (Hanya kolom yang mengalami perubahan)
            );
        });

        // 3. Log saat data dihapus (Delete)
        static::deleted(function ($user) {
            $actorName = Auth::user()?->name ?? 'Sistem';

            KotakMBG::catatLogAktifitas(
                $user,                          // $model: Instance user yang dihapus
                'deleted',                      // $action
                'Penghapusan Akun Pengguna',    // $title
                "Akun dengan nama {$user->name} - (Email: {$user->email}) telah dihapus dari sistem oleh {$actorName}.", // $description
                $user->getOriginal(),           // $oldData
                null                            // $newData (karena dihapus, data baru kosong)
            );
        });
    }
}
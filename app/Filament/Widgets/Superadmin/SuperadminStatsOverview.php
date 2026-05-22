<?php

namespace App\Filament\Widgets\Superadmin;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\KotakMBG;
use App\Models\CalonPenerima;
use App\Models\BuktiKotakDiterima;
use App\Models\Aktifitas;
use Illuminate\Support\Carbon;

class SuperadminStatsOverview extends StatsOverviewWidget
{
    // HAPUS kata 'static' di sini agar sesuai dengan parent class Filament/Livewire
    protected ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        // Menghitung aktivitas hari ini untuk memberikan konteks pada tren
        $aktifitasHariIni = Aktifitas::whereDate('created_at', Carbon::today())->count();
        $countAdmin = User::where('role', 'admin')->count();
        $countKaryawan = User::where('role', 'karyawan')->count();

        return [
            Stat::make('Total Kotak MBG', KotakMBG::count())
                ->description('Jumlah seluruh kotak terdaftar')
                ->descriptionIcon('heroicon-m-cube')
                ->color('success')
                ->chart([7, 10, 13, 15, 20, 25, 30]), // Sparkline visualisasi tren (opsional)

            Stat::make('Admin Vendor', $countAdmin)
                ->description('Total data Admin Vendor')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),

            Stat::make('Karyawan Vendor', $countKaryawan)
                ->description('Total data Karyawan Vendor')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),

            Stat::make('Calon Penerima', CalonPenerima::count())
                ->description('Total data calon penerima manfaat')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),

            Stat::make('Distribusi Selesai', BuktiKotakDiterima::count())
                ->description('Bukti penerimaan kotak MBG yang valid')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('warning'),

            Stat::make('Aktivitas Sistem', Aktifitas::count())
                ->description($aktifitasHariIni . ' aktivitas baru hari ini')
                ->descriptionIcon($aktifitasHariIni > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-minus')
                ->color($aktifitasHariIni > 0 ? 'primary' : 'gray')
                ->chart([5, 12, 8, 15, 22, 18, $aktifitasHariIni]), // Menampilkan grafik fluktuasi aktivitas
        ];
    }
}
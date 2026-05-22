<?php

namespace App\Filament\Widgets\Admin;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use App\Models\KotakMBG;
use App\Models\CalonPenerima;
use App\Models\BuktiKotakDiterima;
use App\Models\Aktifitas;
use Illuminate\Support\Carbon;

class AdminStatsOverview extends StatsOverviewWidget
{
    protected ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        // Jika widget ini harus mengabaikan tenant dan menghitung total global:
        // Gunakan query builder biasa. Jika model Anda menggunakan trait IsTenant, 
        // Anda mungkin perlu menggunakan query tanpa scope bawaan Filament.

        $countKaryawan = User::where('role', 'karyawan')->count();

        return [

            Stat::make('Karyawan', $countKaryawan)
                ->description('Total data Karyawan Vendor')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),

            Stat::make('Calon Penerima', CalonPenerima::count())
                ->description('Total data calon penerima manfaat')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
            
            Stat::make('Total Kotak MBG', KotakMBG::count())
                ->description('Jumlah seluruh kotak terdaftar')
                ->descriptionIcon('heroicon-m-cube')
                ->color('success')
                ->chart([7, 10, 13, 15, 20, 25, 30]), 

            Stat::make('Distribusi Selesai', BuktiKotakDiterima::count())
                ->description('Bukti penerimaan kotak MBG yang valid')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('warning'),
        ];
    }
}
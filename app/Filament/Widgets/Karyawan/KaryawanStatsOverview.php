<?php

namespace App\Filament\Widgets\Karyawan;

use App\Models\BuktiKotakDiterima;
use App\Models\KotakMBG;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;



class KaryawanStatsOverview extends StatsOverviewWidget
{
    protected ?string $pollingInterval = '15s';

    protected function getStats(): array
    {

        return [
            
            Stat::make('Total Kotak MBG', KotakMBG::count())
                ->description('Jumlah seluruh kotak terdaftar')
                ->descriptionIcon('heroicon-m-cube')
                ->color('success')
                ->chart([7, 10, 13, 15, 20, 25, 30]), // Sparkline visualisasi tren (opsional)

            Stat::make('Distribusi Selesai', BuktiKotakDiterima::count())
                ->description('Bukti penerimaan kotak MBG yang valid')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('warning'),
        ];
    }
}

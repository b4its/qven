<?php

namespace App\Filament\Widgets\Penerima;

use App\Models\BuktiKotakDiterima;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class PenerimaStatsOverview extends StatsOverviewWidget
{
    
    protected function getStats(): array
    {
        $countBukitKotakDiterima = BuktiKotakDiterima::where('user_id', Auth::user()->id)->count();
        return [
            //
            Stat::make('Bukti Kotak Diterima', $countBukitKotakDiterima)
                ->description('Total Kotak MBG yang telah diterima')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
        ];
    }
}

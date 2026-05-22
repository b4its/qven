<?php

namespace App\Filament\Resources\Karyawan\KaryawanKotakMBGS\Tables;

use App\Filament\Tables\Actions\DetailKotakViewAction;
use App\Models\KotakMBG;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KaryawanKotakMBGSTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(
                KotakMBG::query()
                    ->selectRaw('kotak_mbg.*, ROW_NUMBER() OVER (ORDER BY created_at desc) as row_num')
                    ->orderBy('created_at', 'desc')
            )
            ->columns([
                TextColumn::make('row_num')
                    ->label('No')
                    ->sortable(),

                TextColumn::make("code")
                    ->label('Kode')
                    ->searchable(),

                TextColumn::make("name")
                    ->label('Nama Kotak MBG')
                    ->searchable(),
                    
                TextColumn::make("status")
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pending' => 'warning',
                        'Diterima' => 'success',
                        default => 'gray',
                    })
                    ->icons([
                        'heroicon-m-clock' => 'Pending',
                        'heroicon-m-check-circle' => 'Diterima',
                    ])
            ])
            ->filters([
                //
            ])
            ->recordActions([
                DetailKotakViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

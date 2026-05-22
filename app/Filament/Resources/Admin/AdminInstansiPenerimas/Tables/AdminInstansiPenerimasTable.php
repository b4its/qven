<?php

namespace App\Filament\Resources\Admin\AdminInstansiPenerimas\Tables;

use App\Models\InstansiPenerima;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AdminInstansiPenerimasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(
                InstansiPenerima::query()
                    // Pastikan nama tabel 'users' sesuai migrasi
                    ->selectRaw('instansi_penerima.*, ROW_NUMBER() OVER (ORDER BY created_at desc) as row_num')
                    ->where('vendor_id', auth()->user()->vendor_id)
                    ->orderBy('created_at', 'desc')
            )
            ->columns([
                //
                TextColumn::make('row_num')
                    ->label('No')
                    ->sortable(),
                TextColumn::make("vendor.name")->label('Vendor'),
                TextColumn::make("user.name")->label('Nama Pengelola'),
                TextColumn::make("name"),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

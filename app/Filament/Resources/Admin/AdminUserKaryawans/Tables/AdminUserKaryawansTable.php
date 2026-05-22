<?php

namespace App\Filament\Resources\Admin\AdminUserKaryawans\Tables;

use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AdminUserKaryawansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                return $query
                    ->selectRaw('users.*, ROW_NUMBER() OVER (ORDER BY created_at DESC) as row_num');
            })
            ->columns([
                TextColumn::make('row_num')
                    ->label('No')
                    ->sortable(),
                TextColumn::make("name"),
                TextColumn::make("email"),  
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                ->label('Edit')
                ->color('warning')
                ->modalHeading('Edit Karyawan'),
                DeleteAction::make()
                    ->button()
                    ->color('danger') // default abu-abu (tidak merah)
                    ->requiresConfirmation() // pastikan tampil popup konfirmasi
                    ->modalHeading('Konfirmasi Hapus')
                    ->modalDescription('apakah yakin ingin menghapus data ini?')
                    ->modalSubmitActionLabel('Ya, Hapus'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

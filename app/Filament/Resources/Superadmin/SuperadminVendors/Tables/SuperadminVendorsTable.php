<?php

namespace App\Filament\Resources\Superadmin\SuperadminVendors\Tables;

use App\Models\User;
use App\Models\Vendor;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SuperadminVendorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(
                Vendor::query()
                    // Pastikan nama tabel 'users' sesuai migrasi
                    ->selectRaw('vendor.*, ROW_NUMBER() OVER (ORDER BY created_at desc) as row_num')
                    ->orderBy('created_at', 'desc')
            )
            ->columns([
                //
                TextColumn::make('row_num')
                    ->label('No')
                    ->sortable(),
                TextColumn::make("name")->label('Nama'),
                TextColumn::make("singkatan")->label('Singkatan'),
                TextColumn::make("lokasi"),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('open_branch')
                    ->label('Lihat Vendor')
                    // Gunakan nama asli dari Heroicons (m = mini, o = outline, s = solid)
                    ->icon('heroicon-m-arrow-right-on-rectangle') 
                    // Atur warna lewat method color, bukan nama icon
                    ->color('cyan') // 'warning' biasanya berwarna orange/amber di Filament
                    ->url(fn (Vendor $record): string => route('filament.admin.pages.dashboard', ['tenant' => $record->id])),
                    
                EditAction::make()
                    ->label('Edit')
                    ->modalHeading('Edit Vendor')
                    ->using(function (Model $record, array $data): Model {
                        return DB::transaction(function () use ($record, $data) {
                            // 1. Ambil data sebelum di-update
                            $oldUserId = $record->user_id; 
                            $newUserId = $data['user_id'] ?? null;

                            // 2. Update data vendor itu sendiri
                            $record->update($data);

                            // 3. Jika user-nya berubah atau dihapus
                            if ($oldUserId != $newUserId) {
                                
                                // PENGAMAN: Set NULL semua user yang telanjur membawa vendor_id ini
                                User::where('vendor_id', $record->id)->update(['vendor_id' => null]);

                                // Lepaskan juga user lama berdasarkan user_id (jika ada)
                                if ($oldUserId) {
                                    User::where('id', $oldUserId)->update(['vendor_id' => null]);
                                }

                                // Pasangkan ke user yang baru dipilih
                                if ($newUserId) {
                                    User::where('id', $newUserId)->update(['vendor_id' => $record->id]);
                                }
                            } else {
                                // Skenario jika user tidak berubah di form, tapi di DB ternyata belum sinkron
                                if ($newUserId) {
                                    User::where('id', $newUserId)->update(['vendor_id' => $record->id]);
                                }
                            }

                            return $record;
                        });
                    }),
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

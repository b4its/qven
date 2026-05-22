<?php

namespace App\Filament\Resources\Superadmin\SuperadminInstansiPenerimas\Tables;

use App\Models\InstansiPenerima;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SuperadminInstansiPenerimasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(
                InstansiPenerima::query()
                    // Pastikan nama tabel 'users' sesuai migrasi
                    ->selectRaw('instansi_penerima.*, ROW_NUMBER() OVER (ORDER BY created_at desc) as row_num')
                    ->orderBy('created_at', 'desc')
            )
            ->columns([
                //
                TextColumn::make('row_num')
                    ->label('No')
                    ->sortable(),
                
                TextColumn::make("vendor.name")
                    ->label('Vendor')
                    ->default('-'), // Menampilkan '-' jika data vendor null
                
                TextColumn::make("user.name")
                    ->label('Nama Pengelola')
                    ->default('Tidak Ada Pengelola'), // Menampilkan teks kustom jika null
                
                TextColumn::make("name")
                    ->label('Nama Instansi') // Opsional: menambahkan label
                    ->default('-'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Edit')
                    ->modalHeading('Edit Instansi Penerima')
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
                                User::where('instansi_penerima_id', $record->id)->update(['instansi_penerima_id' => null]);

                                // Lepaskan juga user lama berdasarkan user_id (jika ada)
                                if ($oldUserId) {
                                    User::where('id', $oldUserId)->update(['instansi_penerima_id' => null]);
                                }

                                // Pasangkan ke user yang baru dipilih
                                if ($newUserId) {
                                    User::where('id', $newUserId)->update(['instansi_penerima_id' => $record->id]);
                                }
                            } else {
                                // Skenario jika user tidak berubah di form, tapi di DB ternyata belum sinkron
                                if ($newUserId) {
                                    User::where('id', $newUserId)->update(['instansi_penerima_id' => $record->id]);
                                }
                            }

                            return $record;
                        });
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

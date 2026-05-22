<?php

namespace App\Filament\Resources\Admin\AdminCalonPenerimas\Tables;

use App\Mail\NotifikasiPendaftaran;
use App\Models\CalonPenerima;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AdminCalonPenerimasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(
                CalonPenerima::query()
                    ->selectRaw('calon_penerima.*, ROW_NUMBER() OVER (ORDER BY created_at desc) as row_num')
                    ->orderBy('created_at', 'desc') // urutkan tampilannya dari terbaru
            )
            ->columns([
                //
                TextColumn::make('row_num')
                    ->label('No')
                    ->sortable(),

                TextColumn::make('name')
                ->label('Name')
                ->searchable()
                ->sortable(),

                TextColumn::make('email')
                ->searchable()
                ->label('Email'),


                TextColumn::make('status')
                    ->label('Status')
                    ->badge() // Mengubah tampilan menjadi badge
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        0 => 'Pending',
                        1 => 'Rejected',
                        2 => 'Approved',
                        default => 'Unknown',
                    })
                    ->color(fn (int $state): string => match ($state) {
                        0 => 'gray',     // Abu-abu untuk Pending
                        1 => 'danger',   // Merah untuk Rejected
                        2 => 'success',  // Hijau untuk Approved
                        default => 'gray',
                    })
                    ->icon(fn (int $state): string => match ($state) {
                        0 => 'heroicon-m-clock',
                        1 => 'heroicon-m-x-circle',
                        2 => 'heroicon-m-check-circle',
                        default => 'heroicon-m-question-mark-circle',
                    }),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make('view_details')
                    ->label('Detail')
                    ->icon('heroicon-m-eye')
                    ->button()
                    ->color('gray') // 'secondary' biasanya di-map ke 'gray' di Filament v3
                    ->infolist([
                        Section::make('Detail Calon Penerima')
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Nama Lengkap'),
                                TextEntry::make('email')
                                    ->label('Email'),
                                TextEntry::make('nik')
                                    ->label('NIK'),
                                TextEntry::make('status')
                                    ->badge()
                                    ->color(fn (int $state): string => match ($state) {
                                        0 => 'gray',
                                        1 => 'danger',
                                        2 => 'success',
                                        default => 'gray',
                                    })
                                    ->formatStateUsing(fn (int $state): string => match ($state) {
                                        0 => 'Pending',
                                        1 => 'Rejected',
                                        2 => 'Approved',
                                        default => 'Unknown',
                                    }),
                                TextEntry::make('created_at')
                                    ->label('Tanggal Daftar')
                                    ->dateTime('d M Y H:i'),
                            ])->columns(2)
                    ]),
                Action::make('approve_status')
                    ->label('Approve')
                    ->icon('heroicon-m-check-circle') // Tambahkan icon untuk UI yang lebih pro
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Approve')
                    ->modalDescription('Apakah Anda yakin ingin menyetujui calon penerima ini?')
                    ->visible(fn (CalonPenerima $record) => $record->status === 0) // UX: Hanya muncul jika belum approved
                    ->action(function (CalonPenerima $record) {
                        $userExists = User::where('email', $record->email)->exists();

                        if ($userExists) {
                            Notification::make()
                                ->title('Gagal')
                                ->body('Email sudah terdaftar di sistem user.')
                                ->danger()
                                ->send();
                            return; // Berhenti di sini, jangan lanjutkan ke bawah
                        }
                        DB::transaction(function () use ($record) {
                            // 1. Update Status
                            $record->update(['status' => 2]);

                            $default_password = 'penerima123';

                            // 2. Buat User Baru
                            $new_user = User::create([
                                'name' => $record->name,
                                'email' => $record->email,
                                'password' => bcrypt($default_password),
                                'role' => 'penerima',
                            ]);

                            // 3. Kirim Email
                            // Pastikan class NotifikasiPendaftaran sudah mendukung ShouldQueue agar tidak berat
                            Mail::to($record->email)->send(new NotifikasiPendaftaran(
                                $new_user->email, 
                                $new_user->name, 
                                $default_password
                            ));
                        });

                    }),

                Action::make('reject_status')
                    ->label('Reject')
                    ->icon('heroicon-m-x-circle')
                    ->color('danger') // Gunakan danger untuk aksi negatif/tolak
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Reject')
                    ->modalDescription('Apakah Anda yakin ingin menolak calon penerima ini?')
                    ->visible(fn (CalonPenerima $record) => $record->status === 0) // UX: Hanya muncul jika belum rejected
                    ->action(function (CalonPenerima $record) {
                        $record->update(['status' => 1]);

                        Notification::make()
                            ->title('Penerima Ditolak')
                            ->warning()
                            ->send();
                    }),

                DeleteAction::make()
                    ->button()
                    ->color('danger') // default abu-abu (tidak merah)
                    ->visible(fn (CalonPenerima $record): bool => in_array($record->status_perilisan_dana, [1, 2]))
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

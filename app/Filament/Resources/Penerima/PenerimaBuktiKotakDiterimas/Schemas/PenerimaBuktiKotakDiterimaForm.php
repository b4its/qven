<?php

namespace App\Filament\Resources\Penerima\PenerimaBuktiKotakDiterimas\Schemas;

use App\Models\KotakMBG;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PenerimaBuktiKotakDiterimaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                
                // 1. Hidden fields
                Hidden::make('user_id')
                    ->default(fn () => Auth::id()),
                    
                Hidden::make('kotak_mbg_id'),

                // 2. Input Kode dengan Tombol Aksi (Suffix Action)
                TextInput::make('code')
                    ->label('Kode Kotak MBG')
                    ->required()
                    ->maxLength(255)
                    ->exists(table: KotakMBG::class, column: 'code')
                    ->validationMessages([
                        'exists' => 'Verifikasi Gagal: Kode MBG tidak terdaftar di sistem.',
                    ])
                    ->helperText('Masukkan kode lalu klik tombol Cek Verifikasi di sebelah kanan.')
                    // Menambahkan tombol aksi tepat di dalam kolom input (sebelah kanan)
                    ->suffixAction(
                        Action::make('verifyCode')
                            ->icon('heroicon-m-check-badge')
                            ->label('Cek Verifikasi')
                            // Tombol ini akan mengeksekusi closure di bawah ini saat diklik
                            ->action(function (Get $get, Set $set) {
                                // Mengambil nilai yang sedang diketik oleh user
                                $inputCode = $get('code');

                                // Cegah hit database jika kolom kosong
                                if (blank($inputCode)) {
                                    Notification::make()
                                        ->title('Kode Kosong')
                                        ->body('Harap masukkan kode MBG terlebih dahulu.')
                                        ->warning()
                                        ->send();
                                    return;
                                }

                                // Pencarian eksak ke tabel kotak_mbg
                                $kotak = KotakMBG::where('code', $inputCode)->first();

                                if ($kotak) {
                                    // Set ID relasi
                                    $set('kotak_mbg_id', $kotak->id);
                                    
                                    // Notifikasi sukses
                                    Notification::make()
                                        ->title('Verifikasi Valid')
                                        ->body("Kode cocok! Kotak: {$kotak->name}")
                                        ->success()
                                        ->send();
                                } else {
                                    // Kosongkan ID relasi jika salah
                                    $set('kotak_mbg_id', null);
                                    
                                    // Notifikasi gagal
                                    Notification::make()
                                        ->title('Verifikasi Gagal')
                                        ->body("Kode '{$inputCode}' tidak ditemukan di sistem.")
                                        ->danger()
                                        ->send();
                                }
                            })
                    ),

                Textarea::make("feedback")
                    ->label("Feedback")
                    ->rows(3)
                    ->columnSpanFull(),

                FileUpload::make('imageUrl')
                    ->label('Bukti Foto Kotak MBG Diterima')
                    ->disk('public_folder')
                    ->directory(fn ($record) => $record?->id 
                        ? "media/kotak_mbg/bukti_kotak_diterima/post/{$record->id}" 
                        : "media/kotak_mbg/bukti_kotak_diterima/post/temp"
                    )
                    ->getUploadedFileNameForStorageUsing(function ($file, $record) {
                        $ext = $file->getClientOriginalExtension();
                        $datetime = now()->format('Ymd_His');
                        $id = $record?->id ?? 'new';
                        return "{$datetime}_{$id}.{$ext}";
                    })
                    ->visibility('public')
                    ->columnSpanFull()
                    ->preserveFilenames(false)
                    ->deleteUploadedFileUsing(fn ($file) => Storage::disk('public_folder')->delete($file))
            ]);
    }
}
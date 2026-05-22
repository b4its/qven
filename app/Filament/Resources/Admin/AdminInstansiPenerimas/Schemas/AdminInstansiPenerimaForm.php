<?php

namespace App\Filament\Resources\Admin\AdminInstansiPenerimas\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class AdminInstansiPenerimaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
                Select::make('user_id')
                    ->label('Pilih Perwakilan Instansi Penerima')
                    ->relationship(
                        name: 'user', 
                        titleAttribute: 'name',
                        // Membaca state vendor_id yang sedang dipilih secara live
                        modifyQueryUsing: function ($query, Get $get) {
                            $vendorId = $get('vendor_id');

                            return $query->where('role', 'penerima')
                                ->when($vendorId, function ($q) use ($vendorId) {
                                    // Hanya tampilkan user yang memiliki vendor_id kecocokan
                                    return $q->where('vendor_id', $vendorId);
                                })
                                ->when(!$vendorId, function ($q) {
                                    // Jika vendor belum dipilih, kosongkan pilihan user (opsional)
                                    return $q->whereNull('id'); 
                                });
                        }
                    )
                    // Penting: Jangan gunakan ->preload() di sini jika datanya bergantung pada vendor_id, 
                    // atau gunakan preload() dengan catatan datanya tidak terlalu besar.
                    ->columnSpanFull()
                    ->live(),

                TextInput::make("name")
                    ->label("Name")
                    ->required(),

                TextInput::make('location')
                    ->label('Lokasi Koordinat')
                    ->required()
                    ->id('input-lokasi-koordinat') // Beri ID agar mudah dimanipulasi via Vanilla JS/Alpine jika diperlukan
                    ->hint('Klik ikon bumi untuk mengambil koordinat')
                    ->suffixAction(
                        Action::make('ambilKoordinat')
                            ->icon('heroicon-m-globe-alt')
                            ->action(function ($component, $action) {
                                // Jalankan deteksi lokasi otomatis terlebih dahulu
                                $component->getLivewire()->js("
                                    navigator.geolocation.getCurrentPosition(
                                        function(position) {
                                            // JIKA BERHASIL: Langsung set nilainya
                                            \$wire.set('data.lokasi', position.coords.latitude + ',' + position.coords.longitude);
                                        }, 
                                        function(error) {
                                            // JIKA GAGAL: Beritahu user dan buka modal peta secara programmatic
                                            alert('Gagal mengambil lokasi otomatis: ' + error.message + '. Silakan pilih manual pada peta.');
                                            
                                            // Memicu modal Action Filament untuk terbuka
                                            \$wire.mountFormComponentAction('" . $component->getStatePath() . "', 'ambilKoordinat');
                                        }
                                    );
                                ");
                            })
                            // --- KONFIGURASI MODAL FALLBACK PILIH MANUAL ---
                            ->modalHeading('Pilih Lokasi')
                            ->modalDescription('Klik pada peta untuk menandai lokasi koordinat tempat Anda.')
                            ->modalSubmitActionLabel('Simpan Koordinat')
                            ->form([
                                // Kita gunakan ViewField untuk menyisipkan HTML Peta Leaflet + CDN nya
                                ViewField::make('map_picker')
                                    ->view('filament.components.map-fallback')
                            ])
                            ->action(function (array $data, $component) {
                                // Ketika tombol 'Simpan Koordinat' di modal diklik, pindahkan nilai dari peta ke input utama
                                if (!empty($data['map_picker'])) {
                                    $component->state($data['map_picker']);
                                }
                            })
                    )
            ]);
    }
}

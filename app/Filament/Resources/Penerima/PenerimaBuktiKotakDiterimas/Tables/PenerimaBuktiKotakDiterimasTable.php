<?php

namespace App\Filament\Resources\Penerima\PenerimaBuktiKotakDiterimas\Tables;

use App\Models\BuktiKotakDiterima;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

// Pastikan semua komponen di-import dari namespace Infolists, bukan Forms/Schemas
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Components\KeyValueEntry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class PenerimaBuktiKotakDiterimasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(
                BuktiKotakDiterima::query()
                    ->selectRaw('bukti_kotak_diterima.*, ROW_NUMBER() OVER (ORDER BY created_at desc) as row_num')
                    ->where('user_id', Auth::user()->id)
                    ->orderBy('created_at', 'desc')
            )
            ->columns([
                TextColumn::make('row_num')
                    ->label('No')
                    ->sortable(),

                TextColumn::make('code')
                    ->label('Kode Kotak MBG')
                    ->sortable()
                    ->searchable()
                    ->copyable(),

                TextColumn::make('kotakMbg.name')
                    ->label('Nama Menu')
                    ->sortable()
                    ->searchable(),
                    
                TextColumn::make('created_at')
                    ->label('Waktu Terima')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                \Filament\Actions\ViewAction::make()
                    ->label('Detail')
                    ->icon('heroicon-m-eye')
                    ->modalHeading('Validasi & Detail Integritas MBG')
                    ->modalWidth('7xl') // Diperlebar agar Grid Gizi terlihat rapi
                    ->infolist([
                        
                        // ROW 1: PERBANDINGAN DATA LOGISTIK VS PENERIMA (2 Kolom)
                        Grid::make(2)
                            ->schema([
                                
                                // --- BAGIAN 1: DETAIL KOTAK MBG (Relasi) ---
                                Section::make('Informasi Kotak MBG')
                                    ->description('Data terpusat dari logistik makanan bergizi.')
                                    ->icon('heroicon-m-cube')
                                    ->columnSpan(1)
                                    ->schema([
                                        TextEntry::make('kotakMbg.name')
                                            ->label('Nama Menu')
                                            ->weight('bold')
                                            ->size('lg'),
                                            
                                        TextEntry::make('kotakMbg.code')
                                            ->label('Kode Logistik')
                                            ->fontFamily('mono')
                                            ->copyable(),
                                            
                                        TextEntry::make('kotakMbg.status')
                                            ->label('Status Kotak')
                                            ->badge()
                                            ->color(fn ($state) => match ($state) {
                                                'dikirim' => 'warning',
                                                'diterima' => 'success',
                                                default => 'gray',
                                            }),
                                            
                                        TextEntry::make('kotakMbg.deskripsi_gizi')
                                            ->label('Ringkasan Gizi')
                                            ->markdown(),
                                            
                                        TextEntry::make('kotakMbg.deskripsi_kelayakan')
                                            ->label('Status Kelayakan')
                                            ->markdown(),
                                            
                                        TextEntry::make('kotakMbg.imagesUrl')
                                            ->label('Foto Asli (Dari Dapur)')
                                            ->html() // Mengizinkan tag HTML
                                            ->formatStateUsing(function ($state) {
                                                if (empty($state)) {
                                                    return '<span class="text-gray-500">Tidak ada gambar</span>';
                                                }
                                                
                                                // Gunakan asset() agar merujuk ke /public/media/...
                                                return new HtmlString('
                                                    <a href="' . asset($state) . '" target="_blank">
                                                        <img src="' . asset($state) . '" 
                                                            style="width: 100%; height: 250px; object-fit: cover; border-radius: 8px; border: 1px solid #e5e7eb;" 
                                                            alt="Foto Dapur" />
                                                    </a>
                                                ');
                                            }),
                                    ]),

                                // --- BAGIAN 2: BUKTI PENERIMAAN (Data Saat Ini) ---
                                Section::make('Bukti Kotak Diterima')
                                    ->description('Laporan dan ulasan yang dikirimkan oleh penerima.')
                                    ->icon('heroicon-m-check-badge')
                                    ->columnSpan(1)
                                    ->schema([
                                        TextEntry::make('user.name')
                                            ->label('Nama Penerima'),
                                            
                                        TextEntry::make('created_at')
                                            ->label('Waktu Laporan Masuk')
                                            ->dateTime('d F Y, H:i:s'),

                                        TextEntry::make('feedback')
                                            ->label('Ulasan/Feedback')
                                            ->markdown()
                                            ->default('Tidak ada ulasan.'),

                                        TextEntry::make('imageUrl')
                                            ->label('Foto Bukti Penerimaan')
                                            ->html() // Mengizinkan tag HTML
                                            ->formatStateUsing(function ($state) {
                                                if (empty($state)) {
                                                    return '<span class="text-gray-500">Tidak ada gambar</span>';
                                                }
                                                
                                                // Gunakan asset() agar merujuk ke /public/media/...
                                                return new HtmlString('
                                                    <a href="' . asset($state) . '" target="_blank">
                                                        <img src="' . asset($state) . '" 
                                                            style="width: 100%; height: 250px; object-fit: cover; border-radius: 8px; border: 1px solid #e5e7eb;" 
                                                            alt="Foto Dapur" />
                                                    </a>
                                                ');
                                            }),
                                            
                                        TextEntry::make('blockchainHash')
                                            ->label('Hash Integritas (SHA-256)')
                                            ->fontFamily('mono')
                                            ->color('gray')
                                            ->copyable()
                                            ->limit(35)
                                            ->tooltip(fn ($record) => $record->blockchainHash),
                                    ]),
                            ]), // End Grid Row 1

                        // ROW 2: DETAIL GIZI (Full Width, Collapsible)
                        Section::make('Rincian Kandungan Gizi')
                            ->description('Spesifikasi nutrisi lengkap yang terkait dengan Kotak MBG ini.')
                            ->icon('heroicon-m-beaker')
                            ->collapsible()
                            ->collapsed(true) // Default tertutup agar tidak memakan layar, user bisa klik untuk buka
                            ->schema([
                                
                                // View Custom untuk JSON Utama
                                ViewEntry::make('kotakMbg.json_gizi')
                                    ->hiddenLabel()
                                    ->view('filament.forms.components.gizi-display') // Pastikan view ini mendukung var $getState() di bladenya
                                    ->columnSpanFull(),

                                // Grid Gizi Makro & Mikro (5 Kolom agar rapi mendatar)
                                Grid::make(5)
                                    ->schema([
                                        // Akses relasi langsung dengan dot notation
                                        TextEntry::make('kotakMbg.kandunganGizi.kalori')->label('Kalori')->suffix(' kcal')->weight('bold'),
                                        TextEntry::make('kotakMbg.kandunganGizi.protein')->label('Protein')->suffix(' g'),
                                        TextEntry::make('kotakMbg.kandunganGizi.lemak')->label('Lemak')->suffix(' g'),
                                        TextEntry::make('kotakMbg.kandunganGizi.karbohidrat')->label('Karbohidrat')->suffix(' g'),
                                        TextEntry::make('kotakMbg.kandunganGizi.serat')->label('Serat')->suffix(' g'),
                                        TextEntry::make('kotakMbg.kandunganGizi.kalsium')->label('Kalsium')->suffix(' mg'),
                                        TextEntry::make('kotakMbg.kandunganGizi.zat_besi')->label('Zat Besi')->suffix(' mg'),
                                        TextEntry::make('kotakMbg.kandunganGizi.natrium')->label('Natrium')->suffix(' mg'),
                                        TextEntry::make('kotakMbg.kandunganGizi.kalium')->label('Kalium')->suffix(' mg'),
                                        TextEntry::make('kotakMbg.kandunganGizi.zinc')->label('Zinc')->suffix(' mg'),
                                    ]),
                                
                                // Grid untuk Lemak dan Vitamin Tambahan (2 Kolom)
                                Grid::make(2)
                                    ->schema([
                                        // Box Lemak Tambahan
                                        Section::make('Kandungan Lemak Tambahan')
                                            ->columnSpan(1)
                                            ->schema([
                                                TextEntry::make('kotakMbg.kandunganGizi.detail_lemak')
                                                    ->hiddenLabel()
                                                    ->markdown(),
                                                    
                                                KeyValueEntry::make('kotakMbg.kandunganGizi.json_lemak')
                                                    ->hiddenLabel()
                                                    ->keyLabel('Komponen')
                                                    ->valueLabel('Nilai'),
                                            ]),

                                        // Box Vitamin Tambahan
                                        Section::make('Kandungan Vitamin Tambahan')
                                            ->columnSpan(1)
                                            ->schema([
                                                TextEntry::make('kotakMbg.kandunganGizi.detail_vitamin')
                                                    ->hiddenLabel()
                                                    ->markdown(),
                                                    
                                                KeyValueEntry::make('kotakMbg.kandunganGizi.json_vitamin')
                                                    ->hiddenLabel()
                                                    ->keyLabel('Jenis Vitamin')
                                                    ->valueLabel('Nilai'),
                                            ]),
                                    ]),
                            ]), // End Section Gizi
                    ]), // End Infolist
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
<?php
namespace App\Filament\Widgets\Superadmin;

use App\Models\Vendor;
use App\Models\Aktifitas;
use Filament\Actions\Action;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;

use Filament\Infolists\Components\KeyValueEntry;
use Illuminate\Support\HtmlString;

class SuperadminAktifitasVendor extends TableWidget
{
    protected static ?string $heading = 'Pelacakan Vendor';
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            // 1. Ubah Query Utama menjadi Vendor
            ->query(fn (): Builder => Vendor::query()->latest())
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Vendor')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('singkatan')
                    ->label('Singkatan')
                    ->searchable(),

                TextColumn::make('lokasi')
                    ->label('Lokasi')
                    ->searchable()
                    ->wrap(),
                // Menghitung jumlah aktifitas yang dimiliki vendor ini tanpa relasi eksplisit
                TextColumn::make('total_aktifitas')
                    ->label('Total Aktifitas')
                    ->getStateUsing(fn (Vendor $record): int => Aktifitas::where('vendor_id', $record->id)->count())
                    ->badge()
                    ->color('info'),
            ])
            ->actions([
                // 2. Action Custom dengan Modal Infolist
                Action::make('detail_lacak')
                    ->label('Detail Lacak')
                    ->icon('heroicon-o-magnifying-glass')
                    ->color('primary')
                    ->modalHeading(fn (Vendor $record) => 'Jejak Hulu ke Hilir: ' . $record->name)
                    ->modalDescription('Seluruh rekam jejak aktifitas yang terjadi pada vendor ini beserta relasinya.')
                    ->modalWidth('7xl')
                    ->modalSubmitAction(false) // Sembunyikan tombol submit karena ini hanya view
                    ->modalCancelActionLabel('Tutup')
                    ->infolist([
                        // 3. Menggunakan RepeatableEntry untuk melooping child aktifitas
                        RepeatableEntry::make('riwayat_aktifitas')
                            ->label('') // Kosongkan label luar
                            ->getStateUsing(function (Vendor $record) {
                                // Ambil semua aktifitas untuk vendor ini, urut dari yang paling baru
                                return Aktifitas::with('user')
                                    ->where('vendor_id', $record->id)
                                    ->latest()
                                    ->get();
                            })
                            ->schema([
                                // Tampilan per item aktifitas
                                Section::make(fn ($record) => $record->title ?? 'Aktifitas Sistem')
                                    ->description(fn ($record) => $record->description ?? '')
                                    ->schema([
                                        Grid::make(4)->schema([
                                            TextEntry::make('created_at')
                                                ->label('Waktu')
                                                ->dateTime('d M Y, H:i:s'),
                                                
                                            TextEntry::make('user.name')
                                                ->label('Aktor (User)')
                                                ->default('Sistem / CLI'),

                                            TextEntry::make('action')
                                                ->label('Aksi')
                                                ->badge()
                                                ->color(fn (string $state): string => match ($state) {
                                                    'created' => 'success',
                                                    'updated' => 'warning',
                                                    'deleted' => 'danger',
                                                    default   => 'gray',
                                                }),

                                            TextEntry::make('table_name')
                                                ->label('Modul/Tabel')
                                                ->formatStateUsing(fn ($state) => strtoupper($state)),
                                        ]),

                                        Grid::make(2)->schema([
                                            TextEntry::make('ip_address')
                                                ->label('Alamat IP & Lokasi')
                                                ->formatStateUsing(fn ($record) => "{$record->ip_address} | {$record->location}"),

                                            TextEntry::make('transaction_hash')
                                                ->label('Blockchain Tx Hash')
                                                ->formatStateUsing(fn ($state) => new HtmlString("<code style='word-break: break-all;'>{$state}</code>")),
                                        ]),

                                        // Payload data dibuat bisa di-collapse (dilipat) agar scroll tidak terlalu panjang
                                        Section::make('Perubahan Data (Payload)')
                                            ->schema([
                                                Grid::make(2)->schema([
                                                    TextEntry::make('old_data')
                                                        ->label('Data Lama (Old)')
                                                        ->formatStateUsing(fn ($state) => new HtmlString("<pre style='font-size: 0.75rem; white-space: pre-wrap; word-break: break-all; background: #f3f4f6; padding: 10px; border-radius: 6px;'>" . json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "</pre>")),
                                                        
                                                    TextEntry::make('new_data')
                                                        ->label('Data Baru (New)')
                                                        ->formatStateUsing(fn ($state) => new HtmlString("<pre style='font-size: 0.75rem; white-space: pre-wrap; word-break: break-all; background: #f3f4f6; padding: 10px; border-radius: 6px;'>" . json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "</pre>")),
                                                                                                    ])
                                            ])
                                            ->collapsed() 
                                            ->hidden(fn ($record) => empty($record->old_data) && empty($record->new_data)),
                                    ])
                                    ->compact() // Buat margin section lebih rapat
                            ])
                    ])
            ]);
    }
}
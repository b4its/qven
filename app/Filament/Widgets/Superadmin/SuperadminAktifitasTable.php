<?php

namespace App\Filament\Widgets\Superadmin;

use App\Models\Aktifitas;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\KeyValueEntry; // Tambahkan import ini
use Filament\Infolists\Infolist;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class SuperadminAktifitasTable extends TableWidget
{
    protected int | string | array $columnSpan = 1;

    protected static ?string $heading = 'Semua Log Aktivitas Sistem';
    
    protected static ?int $sort = 4;


    public function table(Table $table): Table
    {
        return $table
            ->query(
                Aktifitas::query()->latest()
            )
            ->defaultPaginationPageOption(5)
            ->columns([
                TextColumn::make('title')
                    ->label('Aktivitas')
                    ->searchable(isIndividual: true)
                    ->sortable()
                    ->weight('bold')
                    ->color('primary')
                    ->description(fn (Aktifitas $record): string => 'Tabel: ' . Str::headline($record->table_name)),

                TextColumn::make('description')
                    ->label('Total Keseluruhan Rincian')
                    ->searchable(isIndividual: true)
                    ->wrap()
                    ->limit(120),

                TextColumn::make('action')
                    ->label('Aksi')
                    ->badge()
                    ->searchable(isIndividual: true)
                    ->color(fn (string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        default   => 'gray',
                    }),
            ])
            ->actions([
                ViewAction::make('lihat_detail')
                    ->label('Detail Data')
                    ->icon('heroicon-m-eye')
                    ->modalHeading('Detail Log Aktivitas Sistem')
                    ->modalWidth('5xl')
                    ->infolist(function ($infolist) {
                        return $infolist
                            ->schema([
                                Section::make('Informasi Utama')
                                    ->columns(2)
                                    ->schema([
                                        TextEntry::make('title')->label('Judul Aktivitas')->weight('bold'),
                                        TextEntry::make('action')
                                            ->label('Tipe Aksi')
                                            ->badge()
                                            ->color(fn (string $state): string => match ($state) {
                                                'created' => 'success',
                                                'updated' => 'warning',
                                                'deleted' => 'danger',
                                                default   => 'gray',
                                            }),
                                        TextEntry::make('table_name')->label('Tabel Terdampak'),
                                        TextEntry::make('user.name')->label('Nama Pengguna')->default('Sistem / Anonymous'),
                                        TextEntry::make('description')->label('Deskripsi Lengkap')->columnSpanFull(),
                                    ]),

                                Section::make('Jejak Kredensial & Lokasi')
                                    ->columns(2)
                                    ->schema([
                                        TextEntry::make('ip_address')->label('IP Address'),
                                        TextEntry::make('location')->label('Kordinat Lokasi (Lat, Long)')->default('Tidak diketahui'),
                                        TextEntry::make('user_agent')->label('User Agent (Browser/OS)')->columnSpanFull(),
                                    ]),

                                // FOKUS JSON: Diganti menggunakan KeyValueEntry sebagai repeater associative array
                                Section::make('Perbandingan Data (JSON)')
                                    ->columns(2)
                                    ->schema([
                                        KeyValueEntry::make('old_data')
                                            ->label('Data Lama (Old)'),
                                        
                                        KeyValueEntry::make('new_data')
                                            ->label('Data Baru (New)'),
                                    ]),

                                Section::make('Integritas Keamanan')
                                    ->schema([
                                        TextEntry::make('transaction_hash')
                                            ->label('Transaction Hash (SHA-256)')
                                            ->fontFamily('mono')
                                            ->copyable()
                                            ->copyMessage('Hash berhasil disalin!')
                                            ->columnSpanFull(),
                                    ]),
                            ]);
                    }),
            ]);
    }

}
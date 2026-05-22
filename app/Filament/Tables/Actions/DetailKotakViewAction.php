<?php

namespace App\Filament\Tables\Actions;

use Filament\Actions\ViewAction;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;
use Carbon\Carbon;

class DetailKotakViewAction extends ViewAction
{
    public static function getDefaultName(): ?string
    {
        return 'view_kotak_mbg';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('View Detail')
            ->modalHeading('Detail Kotak MBG & Kandungan Gizi')
            ->modalWidth('4xl')
            ->form([
                // Menampilkan Gambar Kotak MBG (Dapur)
                Placeholder::make('tampil_gambar')
                    ->label('Gambar Kotak MBG (Dari Dapur)')
                    ->content(function (?Model $record) {
                        if (! $record || empty($record->imagesUrl)) {
                            return 'Tidak ada gambar';
                        }
                        
                        return new HtmlString('
                        <a href="'. asset($record->imagesUrl) . '" target="_blank">
                            <div style="display: flex; justify-content: center; width: 100%;">
                                <img src="' . asset($record->imagesUrl) . '" style="max-width: 100%; height: 250px; object-fit: cover; border-radius: 8px;" alt="Gambar Kotak MBG" />
                            </div>
                        </a>
                        ');
                    })
                    ->columnSpanFull(),

                // Data Utama Kotak MBG
                TextInput::make('code')->label('Kode MBG')->disabled(),
                TextInput::make('name')->label('Nama Kotak')->disabled(),
                TextInput::make('status')->label('Status Sistem')->disabled(),
                TextInput::make('blockchainHash')->label('Blockchain Hash (Fabric)')->disabled(),
                
                MarkdownEditor::make('deskripsi_gizi')
                    ->label('Deskripsi Gizi')
                    ->disabled(),

                MarkdownEditor::make('deskripsi_kelayakan')
                    ->label('Deskripsi Kelayakan')
                    ->disabled(),

                // =========================================================
                // TAMPILAN JIKA KOTAK BELUM DITERIMA (PENDING)
                // =========================================================
                Placeholder::make('peringatan_belum_diterima')
                    ->hiddenLabel()
                    // KUNCI PERBAIKAN: Cek spesifik ID dari relasi atau cek status pending
                    ->visible(function (?Model $record) {
                        return !$record || empty($record->buktiPenerimaan->id) || strtolower($record->status) === 'pending';
                    })
                    ->content(new HtmlString("
                        <div style='padding: 16px; border: 1px solid rgba(245, 158, 11, 0.5); border-radius: 8px; background-color: rgba(245, 158, 11, 0.1); display: flex; align-items: flex-start; color: var(--text-color, inherit);'>
                            <svg style='width: 24px; height: 24px; color: #f59e0b; margin-right: 12px; flex-shrink: 0;' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'></path></svg>
                            <div>
                                <h4 style='font-weight: 600; margin: 0 0 4px 0; color: #f59e0b;'>Status: Belum Diterima</h4>
                                <p style='font-size: 0.875rem; margin: 0; opacity: 0.8;'>Belum ada laporan penerimaan dari target untuk kotak MBG ini. Distribusi mungkin masih dalam perjalanan.</p>
                            </div>
                        </div>
                    "))
                    ->columnSpanFull(),

                // =========================================================
                // SECTION BUKTI PENERIMAAN (HANYA MUNCUL JIKA SUDAH ADA BUKTI/ID VALID)
                // =========================================================
                Section::make('Detail Bukti Kotak Diterima')
                    ->description('Laporan status distribusi ke target penerima.')
                    ->icon('heroicon-m-check-badge')
                    // KUNCI PERBAIKAN: Section ini hanya dirender jika buktiPenerimaan memiliki ID yang valid
                    ->visible(function (?Model $record) {
                        return $record && !empty($record->buktiPenerimaan->id) && strtolower($record->status) !== 'pending';
                    })
                    ->schema([
                        Placeholder::make('bukti_penerimaan_display')
                            ->hiddenLabel()
                            ->content(function (?Model $record) {
                                $bukti = $record->buktiPenerimaan;
                                
                                $namaPenerima = $bukti->user ? $bukti->user->name : 'Sistem / Tidak Diketahui';
                                $waktu = $bukti->created_at ? \Carbon\Carbon::parse($bukti->created_at)->format('d M Y, H:i:s') : '-';
                                $feedback = $bukti->feedback ?: 'Tidak ada ulasan yang diberikan.';
                                $hash = $bukti->blockchainHash ?: 'Menunggu hashing...';
                                $imageUrl = $bukti->imageUrl ? asset($bukti->imageUrl) : asset('assets/no-image.png');

                                return new HtmlString("
                                    <div style='padding: 16px; border: 1px solid rgba(16, 185, 129, 0.5); border-radius: 8px; background-color: rgba(16, 185, 129, 0.1); color: var(--text-color, inherit);'>
                                        <div style='display: flex; align-items: center; margin-bottom: 16px;'>
                                            <svg style='width: 24px; height: 24px; color: #10b981; margin-right: 8px;' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'></path></svg>
                                            <h4 style='font-weight: 600; margin: 0; font-size: 1.125rem; color: #10b981;'>Kotak Telah Diterima</h4>
                                        </div>
                                        
                                        <div style='display: flex; flex-wrap: wrap; gap: 16px; font-size: 0.875rem;'>
                                            <div style='flex: 1 1 60%; min-width: 250px;'>
                                                <p style='margin: 0 0 6px 0;'><strong style='color: #10b981; opacity: 0.9;'>Oleh:</strong> <span style='opacity: 0.9;'>{$namaPenerima}</span></p>
                                                <p style='margin: 0 0 6px 0;'><strong style='color: #10b981; opacity: 0.9;'>Waktu:</strong> <span style='opacity: 0.9;'>{$waktu}</span></p>
                                                <p style='margin: 0 0 6px 0;'><strong style='color: #10b981; opacity: 0.9;'>Ulasan:</strong><br><span style='opacity: 0.9;'>{$feedback}</span></p>
                                                <div style='margin-top: 12px;'>
                                                    <strong style='color: #10b981; opacity: 0.9; display: block; margin-bottom: 4px;'>Hash Laporan:</strong> 
                                                    <div style='font-family: monospace; font-size: 0.75rem; word-break: break-all; padding: 6px 8px; background-color: rgba(0,0,0, 0.1); border-radius: 4px; opacity: 0.8;'>{$hash}</div>
                                                </div>
                                            </div>
                                            
                                            <div style='flex: 0 0 auto;'>
                                                <a href='{$imageUrl}' target='_blank' style='display: block;'>
                                                    <img src='{$imageUrl}' style='height: 120px; width: auto; border-radius: 6px; border: 2px solid rgba(16, 185, 129, 0.5); object-fit: cover;' alt='Foto Bukti' />
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                ");
                            })
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                // =========================================================
                // SECTION GIZI
                // =========================================================
                Section::make('Data Gizi Utama')
                    ->description('Detail Kandungan Gizi')
                    ->schema([
                        ViewField::make('json_gizi') 
                            ->hiddenLabel()
                            ->view('filament.forms.components.gizi-display')
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(true)
                    ->columnSpanFull(),

                Repeater::make('detail_kandungan_gizi')
                    ->label('Kandungan Gizi Mikro & Makro')
                    ->schema([
                        TextInput::make('kalori')->label('Kalori')->suffix('kcal'),
                        TextInput::make('protein')->label('Protein')->suffix('g'),
                        TextInput::make('lemak')->label('Lemak')->suffix('g'),
                        TextInput::make('karbohidrat')->label('Karbohidrat')->suffix('g'),
                        TextInput::make('serat')->label('Serat')->suffix('g'),
                        TextInput::make('kalsium')->label('Kalsium')->suffix('mg'),
                        TextInput::make('zat_besi')->label('Zat Besi')->suffix('mg'),
                        TextInput::make('natrium')->label('Natrium')->suffix('mg'),
                        TextInput::make('kalium')->label('Kalium')->suffix('mg'),
                        TextInput::make('zinc')->label('Zinc')->suffix('mg'),
                        
                        MarkdownEditor::make('detail_lemak')
                            ->label('Data Lemak Tambahan')
                            ->columnSpanFull(),
                        
                        Repeater::make('wrap_json_lemak')
                            ->label('Data Kandungan Lemak Tambahan')
                            ->schema([
                                KeyValue::make('json_lemak')
                                    ->hiddenLabel()
                                    ->disabled()
                                    ->columnSpanFull(),
                            ])
                            ->addable(false)->deletable(false)->reorderable(false)
                            ->collapsible()->collapsed(true)->columnSpanFull()
                            ->itemLabel('Detail Kandungan Lemak Tambahan'),

                        MarkdownEditor::make('detail_vitamin')
                            ->label('Data Kandungan Vitamin Tambahan')
                            ->columnSpanFull(),
                            
                        Repeater::make('wrap_json_vitamin')
                            ->label('Data Kandungan Vitamin Tambahan')
                            ->schema([
                                KeyValue::make('json_vitamin')
                                    ->hiddenLabel()
                                    ->disabled()
                                    ->columnSpanFull(),
                            ])
                            ->addable(false)->deletable(false)->reorderable(false)
                            ->collapsible()->collapsed(true)->columnSpanFull()
                            ->itemLabel('Detail Kandungan Vitamin Tambahan'),
                    ])
                    ->columns(6)
                    ->disabled()
                    ->addable(false)->deletable(false)->reorderable(false)
                    ->collapsible()->collapsed(true)
                    ->columnSpanFull()
                    ->itemLabel('Rincian Detail Kandungan Gizi'),
            ])
            ->mutateRecordDataUsing(function (array $data, Model $record): array {
                $record->load(['kandunganGizi', 'buktiPenerimaan', 'buktiPenerimaan.user']);

                // Inject Data Dummy Repeater
                $data['wrap_json_gizi'] = $record->json_gizi ? [[ 'json_gizi' => $record->json_gizi ]] : [];

                if ($record->kandunganGizi) {
                    $data['detail_kandungan_gizi'] = [
                        [
                            'kalori' => $record->kandunganGizi->kalori,
                            'protein' => $record->kandunganGizi->protein,
                            'lemak' => $record->kandunganGizi->lemak,
                            'karbohidrat' => $record->kandunganGizi->karbohidrat,
                            'serat' => $record->kandunganGizi->serat,
                            'kalsium' => $record->kandunganGizi->kalsium,
                            'zat_besi' => $record->kandunganGizi->zat_besi,
                            'natrium' => $record->kandunganGizi->natrium,
                            'kalium' => $record->kandunganGizi->kalium,
                            'zinc' => $record->kandunganGizi->zinc,
                            'detail_lemak' => $record->kandunganGizi->detail_lemak,
                            'detail_vitamin' => $record->kandunganGizi->detail_vitamin,
                            'wrap_json_lemak' => $record->kandunganGizi->json_lemak ? [[ 'json_lemak' => $record->kandunganGizi->json_lemak ]] : [],
                            'wrap_json_vitamin' => $record->kandunganGizi->json_vitamin ? [[ 'json_vitamin' => $record->kandunganGizi->json_vitamin ]] : [],
                        ]
                    ];
                } else {
                    $data['detail_kandungan_gizi'] = [];
                }

                return $data;
            });
    }
}
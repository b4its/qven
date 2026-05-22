<?php

namespace App\Filament\Resources\Superadmin\SuperadminKotakMBGS\Tables;

use App\Filament\Tables\Actions\DetailKotakViewAction;
use App\Models\KotakMBG;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\KeyValue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Collection;

class SuperadminKotakMBGSTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(
                KotakMBG::query()
                    ->selectRaw('kotak_mbg.*, ROW_NUMBER() OVER (ORDER BY created_at desc) as row_num')
                    ->orderBy('created_at', 'desc')
            )
            ->columns([
                TextColumn::make('row_num')
                    ->label('No')
                    ->sortable(),

                TextColumn::make("code")
                    ->label('Kode')
                    ->searchable(),

                TextColumn::make("name")
                    ->label('Nama Kotak MBG')
                    ->searchable(),

                TextColumn::make("status")
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pending' => 'warning',
                        'Diterima' => 'success',
                        default => 'gray',
                    })
                    ->icons([
                        'heroicon-m-clock' => 'Pending',
                        'heroicon-m-check-circle' => 'Diterima',
                    ])
            ])
            ->filters([
                //
            ])
            ->recordActions([
                DetailKotakViewAction::make(),
                
                DeleteAction::make()
                    ->button()
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Hapus')
                    ->modalDescription('Apakah yakin ingin menghapus data ini beserta kandungan gizinya?')
                    ->modalSubmitActionLabel('Ya, Hapus')
                    ->before(function (KotakMBG $record) {
                        // Tembak langsung ke root folder public/
                        if (! empty($record->imagesUrl)) {
                            $imagePath = public_path($record->imagesUrl);
                            
                            // Cek apakah file benar-benar ada sebelum dihapus biar nggak error
                            if (File::exists($imagePath)) {
                                File::delete($imagePath);
                            }
                        }
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->before(function (Collection $records) {
                            // Hapus file fisik secara massal langsung dari folder public/
                            foreach ($records as $record) {
                                if (! empty($record->imagesUrl)) {
                                    $imagePath = public_path($record->imagesUrl);
                                    
                                    if (File::exists($imagePath)) {
                                        File::delete($imagePath);
                                    }
                                }
                            }
                        }),
                ]),
            ]);
    }
}
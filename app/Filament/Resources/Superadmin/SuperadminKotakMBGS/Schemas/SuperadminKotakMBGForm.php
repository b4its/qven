<?php

namespace App\Filament\Resources\Superadmin\SuperadminKotakMBGS\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

class SuperadminKotakMBGForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make("name")
                    ->label("Name")
                    ->columnSpanFull()
                    ->required(),

                FileUpload::make('imagesUrl')
                    ->label('Gambar Kotak MBG')
                    ->disk('public_folder')
                    ->directory(fn ($record) => $record?->id 
                        ? "media/kotak_mbg/post/{$record->id}" 
                        : "media/kotak_mbg/post/temp"
                    )
                    ->getUploadedFileNameForStorageUsing(function ($file, $record) {
                        $ext = $file->getClientOriginalExtension();
                        $datetime = now()->format('Ymd_His');
                        $id = $record?->id ?? 'new';
                        
                        // PERBAIKAN: Cukup kembalikan nama file-nya saja.
                        return "{$datetime}_{$id}.{$ext}";
                    })
                    ->visibility('public')
                    ->columnSpanFull()
                    ->preserveFilenames(false)
                    ->deleteUploadedFileUsing(fn ($file) => Storage::disk('public_folder')->delete($file))
            ]);
    }
}
<?php

namespace App\Filament\Resources\Karyawan\KaryawanKotakMBGS\Pages;

use App\Filament\Resources\Karyawan\KaryawanKotakMBGS\KaryawanKotakMBGResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKaryawanKotakMBG extends EditRecord
{
    protected static string $resource = KaryawanKotakMBGResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\Superadmin\SuperadminUserKaryawans\Pages;

use App\Filament\Resources\Superadmin\SuperadminUserKaryawans\SuperadminUserKaryawanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSuperadminUserKaryawan extends EditRecord
{
    protected static string $resource = SuperadminUserKaryawanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

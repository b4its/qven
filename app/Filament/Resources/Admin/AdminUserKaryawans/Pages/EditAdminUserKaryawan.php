<?php

namespace App\Filament\Resources\Admin\AdminUserKaryawans\Pages;

use App\Filament\Resources\Admin\AdminUserKaryawans\AdminUserKaryawanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAdminUserKaryawan extends EditRecord
{
    protected static string $resource = AdminUserKaryawanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

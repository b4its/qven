<?php

namespace App\Filament\Resources\Admin\AdminUserPenerimas\Pages;

use App\Filament\Resources\Admin\AdminUserPenerimas\AdminUserPenerimaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAdminUserPenerima extends EditRecord
{
    protected static string $resource = AdminUserPenerimaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

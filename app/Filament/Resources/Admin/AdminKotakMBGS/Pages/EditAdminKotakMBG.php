<?php

namespace App\Filament\Resources\Admin\AdminKotakMBGS\Pages;

use App\Filament\Resources\Admin\AdminKotakMBGS\AdminKotakMBGResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAdminKotakMBG extends EditRecord
{
    protected static string $resource = AdminKotakMBGResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

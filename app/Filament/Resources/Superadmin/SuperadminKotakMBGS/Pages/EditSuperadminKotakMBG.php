<?php

namespace App\Filament\Resources\Superadmin\SuperadminKotakMBGS\Pages;

use App\Filament\Resources\Superadmin\SuperadminKotakMBGS\SuperadminKotakMBGResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSuperadminKotakMBG extends EditRecord
{
    protected static string $resource = SuperadminKotakMBGResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\Superadmin\SuperadminVendors\Pages;

use App\Filament\Resources\Superadmin\SuperadminVendors\SuperadminVendorResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSuperadminVendor extends EditRecord
{
    protected static string $resource = SuperadminVendorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\Superadmin\SuperadminUserAdmins\Pages;

use App\Filament\Resources\Superadmin\SuperadminUserAdmins\SuperadminUserAdminResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSuperadminUserAdmin extends EditRecord
{
    protected static string $resource = SuperadminUserAdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

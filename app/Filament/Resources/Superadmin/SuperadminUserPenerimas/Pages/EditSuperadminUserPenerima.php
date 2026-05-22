<?php

namespace App\Filament\Resources\Superadmin\SuperadminUserPenerimas\Pages;

use App\Filament\Resources\Superadmin\SuperadminUserPenerimas\SuperadminUserPenerimaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSuperadminUserPenerima extends EditRecord
{
    protected static string $resource = SuperadminUserPenerimaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

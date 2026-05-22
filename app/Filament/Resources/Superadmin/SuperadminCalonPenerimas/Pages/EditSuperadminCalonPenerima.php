<?php

namespace App\Filament\Resources\Superadmin\SuperadminCalonPenerimas\Pages;

use App\Filament\Resources\Superadmin\SuperadminCalonPenerimas\SuperadminCalonPenerimaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSuperadminCalonPenerima extends EditRecord
{
    protected static string $resource = SuperadminCalonPenerimaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

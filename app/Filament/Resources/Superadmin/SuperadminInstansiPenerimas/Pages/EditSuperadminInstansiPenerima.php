<?php

namespace App\Filament\Resources\Superadmin\SuperadminInstansiPenerimas\Pages;

use App\Filament\Resources\Superadmin\SuperadminInstansiPenerimas\SuperadminInstansiPenerimaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSuperadminInstansiPenerima extends EditRecord
{
    protected static string $resource = SuperadminInstansiPenerimaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\Admin\AdminCalonPenerimas\Pages;

use App\Filament\Resources\Admin\AdminCalonPenerimas\AdminCalonPenerimaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAdminCalonPenerima extends EditRecord
{
    protected static string $resource = AdminCalonPenerimaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\Admin\AdminInstansiPenerimas\Pages;

use App\Filament\Resources\Admin\AdminInstansiPenerimas\AdminInstansiPenerimaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAdminInstansiPenerima extends EditRecord
{
    protected static string $resource = AdminInstansiPenerimaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

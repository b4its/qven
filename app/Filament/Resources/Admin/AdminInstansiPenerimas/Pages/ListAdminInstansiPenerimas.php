<?php

namespace App\Filament\Resources\Admin\AdminInstansiPenerimas\Pages;

use App\Filament\Resources\Admin\AdminInstansiPenerimas\AdminInstansiPenerimaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAdminInstansiPenerimas extends ListRecords
{
    protected static string $resource = AdminInstansiPenerimaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

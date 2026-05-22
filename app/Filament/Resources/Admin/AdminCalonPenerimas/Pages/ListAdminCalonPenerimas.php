<?php

namespace App\Filament\Resources\Admin\AdminCalonPenerimas\Pages;

use App\Filament\Resources\Admin\AdminCalonPenerimas\AdminCalonPenerimaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAdminCalonPenerimas extends ListRecords
{
    protected static ?string $title = "Daftar Calon Penerima";
    protected static string $resource = AdminCalonPenerimaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}

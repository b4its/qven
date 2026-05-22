<?php

namespace App\Filament\Resources\Admin\AdminUserPenerimas\Pages;

use App\Filament\Resources\Admin\AdminUserPenerimas\AdminUserPenerimaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAdminUserPenerimas extends ListRecords
{
    protected static string $resource = AdminUserPenerimaResource::class;

    protected static ?string $title = 'Daftar Penerima';

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}

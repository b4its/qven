<?php

namespace App\Filament\Resources\Superadmin\SuperadminUserPenerimas\Pages;

use App\Filament\Resources\Superadmin\SuperadminUserPenerimas\SuperadminUserPenerimaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSuperadminUserPenerimas extends ListRecords
{
    protected static ?string $title = 'Daftar Penerima';
    protected static string $resource = SuperadminUserPenerimaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}

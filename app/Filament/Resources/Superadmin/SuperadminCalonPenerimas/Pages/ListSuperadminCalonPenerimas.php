<?php

namespace App\Filament\Resources\Superadmin\SuperadminCalonPenerimas\Pages;

use App\Filament\Resources\Superadmin\SuperadminCalonPenerimas\SuperadminCalonPenerimaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSuperadminCalonPenerimas extends ListRecords
{
    protected static ?string $title = 'Daftar Calon Penerima';
    protected static string $resource = SuperadminCalonPenerimaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}

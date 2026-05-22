<?php

namespace App\Filament\Resources\Superadmin\SuperadminAktifitas\Pages;

use App\Filament\Resources\Superadmin\SuperadminAktifitas\SuperadminAktifitasResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSuperadminAktifitas extends ListRecords
{
    protected static string $resource = SuperadminAktifitasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

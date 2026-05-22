<?php

namespace App\Filament\Resources\Superadmin\SuperadminAktifitas\Pages;

use App\Filament\Resources\Superadmin\SuperadminAktifitas\SuperadminAktifitasResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSuperadminAktifitas extends EditRecord
{
    protected static string $resource = SuperadminAktifitasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

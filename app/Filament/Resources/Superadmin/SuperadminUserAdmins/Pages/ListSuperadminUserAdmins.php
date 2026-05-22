<?php

namespace App\Filament\Resources\Superadmin\SuperadminUserAdmins\Pages;

use App\Filament\Resources\Superadmin\SuperadminUserAdmins\SuperadminUserAdminResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSuperadminUserAdmins extends ListRecords
{
    protected static ?string $title = 'Daftar Admin';
    protected static string $resource = SuperadminUserAdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambahkan Admin')
            ->mutateFormDataUsing(function (array $data): array {
                    $data['role'] = "admin";
                    return $data;
                }),
        ];
    }
}

<?php

namespace App\Filament\Resources\Admin\AdminUserKaryawans\Pages;

use App\Filament\Resources\Admin\AdminUserKaryawans\AdminUserKaryawanResource;
use Filament\Actions\CreateAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;

class ListAdminUserKaryawans extends ListRecords
{
    protected static string $resource = AdminUserKaryawanResource::class;

    protected static ?string $title = 'Daftar Karyawan';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambahkan Karyawan')
            ->mutateFormDataUsing(function (array $data): array {
                    $data['role'] = "karyawan";
                    $data['vendor_id'] = Filament::getTenant()->id;
                    return $data;
                }),
        ];
    }
}

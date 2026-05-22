<?php

namespace App\Filament\Resources\Superadmin\SuperadminUserKaryawans\Pages;

use App\Filament\Resources\Superadmin\SuperadminUserKaryawans\SuperadminUserKaryawanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSuperadminUserKaryawans extends ListRecords
{
    protected static string $resource = SuperadminUserKaryawanResource::class;
    protected static ?string $title = 'Daftar Karyawan';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambahkan Karyawan')
            ->mutateFormDataUsing(function (array $data): array {
                    $data['role'] = "karyawan";
                    return $data;
                }),
        ];
    }
}

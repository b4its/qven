<?php

namespace App\Filament\Resources\Superadmin\SuperadminVendors\Pages;

use App\Filament\Resources\Superadmin\SuperadminVendors\SuperadminVendorResource;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ListSuperadminVendors extends ListRecords
{
    protected static ?string $title = "Daftar Vendor";
    protected static string $resource = SuperadminVendorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Vendor')
                ->using(function (array $data, string $model): Model {
                    return DB::transaction(function () use ($data, $model) {
                        
                        // 1. Buat record Vendor baru dari data form
                        $vendor = $model::create($data);

                        // 2. Cari User berdasarkan user_id yang dipilih dari form
                        if (!empty($data['user_id'])) {
                            $user = User::find($data['user_id']);
                            
                            if ($user) {
                                // 3. Update data user tersebut untuk mengisi vendor_id-nya
                                $user->update([
                                    'vendor_id' => $vendor->id,
                                ]);
                            }
                        }

                        return $vendor;
                    });
                }),
        ];
    }
}
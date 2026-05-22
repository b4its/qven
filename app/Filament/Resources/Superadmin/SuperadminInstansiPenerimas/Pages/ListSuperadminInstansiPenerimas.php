<?php

namespace App\Filament\Resources\Superadmin\SuperadminInstansiPenerimas\Pages;

use App\Filament\Resources\Superadmin\SuperadminInstansiPenerimas\SuperadminInstansiPenerimaResource;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ListSuperadminInstansiPenerimas extends ListRecords
{
    protected static ?string $title = "Daftar Instansi Penerima";
    protected static string $resource = SuperadminInstansiPenerimaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambahkan Instansi Penerima')
                ->using(function (array $data, string $model): Model {
                    return DB::transaction(function () use ($data, $model) {
                        
                        // 1. Buat record instansi baru dari data form
                        $instansi = $model::create($data);

                        // 2. Cari User berdasarkan user_id yang dipilih dari form
                        if (!empty($data['user_id'])) {
                            $user = User::find($data['user_id']);
                            
                            if ($user) {
                                // 3. Update data user tersebut untuk mengisi instansi_id-nya
                                $user->update([
                                    'instansi_penerima_id' => $instansi->id,
                                ]);
                            }
                        }

                        return $instansi;
                    });
                }),

        ];
    }
}

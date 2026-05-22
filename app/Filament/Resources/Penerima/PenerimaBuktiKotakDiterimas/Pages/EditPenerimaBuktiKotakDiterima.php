<?php

namespace App\Filament\Resources\Penerima\PenerimaBuktiKotakDiterimas\Pages;

use App\Filament\Resources\Penerima\PenerimaBuktiKotakDiterimas\PenerimaBuktiKotakDiterimaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPenerimaBuktiKotakDiterima extends EditRecord
{
    protected static string $resource = PenerimaBuktiKotakDiterimaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

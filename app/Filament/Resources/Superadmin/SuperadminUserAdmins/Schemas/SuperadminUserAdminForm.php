<?php

namespace App\Filament\Resources\Superadmin\SuperadminUserAdmins\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Hash;
use Illuminate\Validation\Rules\Password;


class SuperadminUserAdminForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
                TextInput::make('name')
                    ->label('Nama')
                    ->maxLength(255)
                    ->required(),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->required(),

                TextInput::make('password')
                    ->label(fn (string $operation): string => $operation === 'create' ? 'Password' : 'Password Baru')
                    ->password()
                    ->revealable()
                    ->columnSpanFull()
                    // Hanya wajib diisi saat membuat user baru (create)
                    ->required(fn (string $operation): bool => $operation === 'create')
                    // Jangan timpa password lama jika input dikosongkan saat edit
                    ->dehydrated(fn (?string $state) => filled($state))
                    ->rule(Password::default()),   

                Select::make('vendor_id')
                    ->label('Pilih Vendor')
                    ->relationship(
                        name: 'vendor', 
                        titleAttribute: 'name',
                        // Filter: Hanya tampilkan user dengan role umkm
                    )
                    ->searchable()
                    ->preload()
                    ->columnSpanFull()
                    ->live(), 
            ]);
    }
}

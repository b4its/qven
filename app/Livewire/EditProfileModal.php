<?php

namespace App\Livewire;

use App\Models\KotakMBG;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Livewire\Component;

class EditProfileModal extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            $this->form->fill([
                'name' => $user->name,
                'email' => $user->email,
                'nik' => $user->profile?->nik,
                'alamat' => $user->profile?->alamat,
            ]);
        }
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(table: 'users', ignorable: Auth::user()), 
                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->label('Password Baru'),
                
                // Hapus ->numeric() agar mask() dan maxLength() bekerja sempurna
                TextInput::make('nik')
                    ->label('NIK')
                    ->placeholder('Masukkan 17 digit NIK Anda')
                    ->mask('99999999999999999')
                    ->maxLength(17),

                Textarea::make('alamat')
                    ->label('Alamat')
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        if (!Auth::check()) {
            return;
        }

        $data = $this->form->getState();
        $user = Auth::user();

        // Tangkap data lama sebelum diubah untuk keperluan Log
        $oldData = [
            'name' => $user->name,
            'email' => $user->email,
            'nik' => $user->profile?->nik,
            'alamat' => $user->profile?->alamat,
        ];

        $userData = Arr::only($data, ['name', 'email', 'password']);
        $profileData = Arr::only($data, ['nik', 'alamat']);

        $user->update($userData);

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        // Tangkap data baru setelah pembaruan
        $newData = [
            'name' => $userData['name'],
            'email' => $userData['email'],
            'nik' => $profileData['nik'] ?? null,
            'alamat' => $profileData['alamat'] ?? null,
        ];

        // Sesuaikan variabel log agar mencatat aktivitas Edit Profile yang valid
        KotakMBG::catatLogAktifitas(
            $user, // Menggunakan instance $user sebagai $record subject
            'updated',
            'Pembaruan Profil Pengguna',
            "Profil pengguna dengan email {$user->email} telah diperbarui.",
            $oldData,
            $newData
        );

        Notification::make()
            ->title('Profile updated!')
            ->success()
            ->send();

        $this->dispatch('close-modal', id: 'edit-profile-modal');
    }

    public function render()
    {
        if (!Auth::check()) {
            return <<<'HTML'
            <div></div>
            HTML;
        }

        return <<<'HTML'
        <div x-data x-on:hashchange.window="if(location.hash === '#edit-profile') { $dispatch('open-modal', { id: 'edit-profile-modal' }); history.replaceState(null, '', location.pathname + location.search); }">
            <x-filament::modal id="edit-profile-modal" width="md">
                <x-slot name="heading">
                    Edit Profile
                </x-slot>

                <form wire:submit="save">
                    {{ $this->form }}

                    <div class="flex justify-end gap-x-3" style="margin-top:1em;">
                        <x-filament::button color="gray" type="button" x-on:click="$dispatch('close-modal', { id: 'edit-profile-modal' })">
                            Batalkan
                        </x-filament::button>
                        <x-filament::button type="submit">
                            Simpan
                        </x-filament::button>
                    </div>
                </form>
            </x-filament::modal>
        </div>
        HTML;
    }
}
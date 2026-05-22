<?php

namespace App\Filament\Resources\Superadmin\SuperadminUserAdmins;

use App\Filament\Resources\Superadmin\SuperadminUserAdmins\Pages\CreateSuperadminUserAdmin;
use App\Filament\Resources\Superadmin\SuperadminUserAdmins\Pages\EditSuperadminUserAdmin;
use App\Filament\Resources\Superadmin\SuperadminUserAdmins\Pages\ListSuperadminUserAdmins;
use App\Filament\Resources\Superadmin\SuperadminUserAdmins\Schemas\SuperadminUserAdminForm;
use App\Filament\Resources\Superadmin\SuperadminUserAdmins\Tables\SuperadminUserAdminsTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SuperadminUserAdminResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'users';
    protected static ?string $slug = 'list-admin';

    public static function form(Schema $schema): Schema
    {
        return SuperadminUserAdminForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SuperadminUserAdminsTable::configure($table);
    }
    

    public static function getNavigationGroup(): string
    {
        return 'Akun';
    }

    public static function getNavigationLabel(): string
    {
        return 'Admin';
    }

    public static function getNavigationIcon(): string
    {
    return 'heroicon-o-user';
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    protected static ?int $navigationSort = 1;

    public static function getPages(): array
    {
        return [
            'index' => ListSuperadminUserAdmins::route('/'),
            // 'create' => CreateSuperadminUserAdmin::route('/create'),
            // 'edit' => EditSuperadminUserAdmin::route('/{record}/edit'),
        ];
    }
}

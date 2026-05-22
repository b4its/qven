<?php

namespace App\Filament\Resources\Admin\AdminUserKaryawans;

use App\Filament\Resources\Admin\AdminUserKaryawans\Pages\CreateAdminUserKaryawan;
use App\Filament\Resources\Admin\AdminUserKaryawans\Pages\EditAdminUserKaryawan;
use App\Filament\Resources\Admin\AdminUserKaryawans\Pages\ListAdminUserKaryawans;
use App\Filament\Resources\Admin\AdminUserKaryawans\Schemas\AdminUserKaryawanForm;
use App\Filament\Resources\Admin\AdminUserKaryawans\Tables\AdminUserKaryawansTable;
use App\Models\User;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
class AdminUserKaryawanResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'users';

    public static function form(Schema $schema): Schema
    {
        return AdminUserKaryawanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AdminUserKaryawansTable::configure($table);
    }
    public static function getEloquentQuery(): Builder
    {
        // Karena basis modelnya sudah User, langsung tembak nama kolomnya di sini
        return parent::getEloquentQuery()
            ->where('vendor_id', Filament::getTenant()->id)
            ->where('role', 'karyawan');
    }

    protected static ?string $slug = 'list-karyawan';
    public static function getNavigationGroup(): string
    {
        return 'Akun';
    }

    public static function getNavigationLabel(): string
    {
        return 'Karyawan';
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


    public static function getPages(): array
    {
        return [
            'index' => ListAdminUserKaryawans::route('/'),
            // 'create' => CreateAdminUserKaryawan::route('/create'),
            // 'edit' => EditAdminUserKaryawan::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources\Superadmin\SuperadminUserKaryawans;

use App\Filament\Resources\Superadmin\SuperadminUserKaryawans\Pages\CreateSuperadminUserKaryawan;
use App\Filament\Resources\Superadmin\SuperadminUserKaryawans\Pages\EditSuperadminUserKaryawan;
use App\Filament\Resources\Superadmin\SuperadminUserKaryawans\Pages\ListSuperadminUserKaryawans;
use App\Filament\Resources\Superadmin\SuperadminUserKaryawans\Schemas\SuperadminUserKaryawanForm;
use App\Filament\Resources\Superadmin\SuperadminUserKaryawans\Tables\SuperadminUserKaryawansTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SuperadminUserKaryawanResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'users';
    protected static ?string $slug = 'list-karyawan';

    public static function form(Schema $schema): Schema
    {
        return SuperadminUserKaryawanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SuperadminUserKaryawansTable::configure($table);
    }

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
    protected static ?int $navigationSort = 2;

    public static function getPages(): array
    {
        return [
            'index' => ListSuperadminUserKaryawans::route('/'),
            // 'create' => CreateSuperadminUserKaryawan::route('/create'),
            // 'edit' => EditSuperadminUserKaryawan::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources\Admin\AdminUserPenerimas;

use App\Filament\Resources\Admin\AdminUserPenerimas\Pages\CreateAdminUserPenerima;
use App\Filament\Resources\Admin\AdminUserPenerimas\Pages\EditAdminUserPenerima;
use App\Filament\Resources\Admin\AdminUserPenerimas\Pages\ListAdminUserPenerimas;
use App\Filament\Resources\Admin\AdminUserPenerimas\Schemas\AdminUserPenerimaForm;
use App\Filament\Resources\Admin\AdminUserPenerimas\Tables\AdminUserPenerimasTable;
use App\Models\User;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AdminUserPenerimaResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'users';
    protected static ?string $title = 'Daftar Penerima';
    protected static ?string $slug = 'list-penerima';

    public static function form(Schema $schema): Schema
    {
        return AdminUserPenerimaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AdminUserPenerimasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        // Karena basis modelnya sudah User, langsung tembak nama kolomnya di sini
        return parent::getEloquentQuery()
            ->where('vendor_id', Filament::getTenant()->id)
            ->where('role', 'penerima');
    }

    public static function getNavigationGroup(): string
    {
        return 'Akun';
    }

    public static function getNavigationLabel(): string
    {
        return 'Penerima';
    }

    public static function getNavigationIcon(): string
    {
    return 'heroicon-o-user';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAdminUserPenerimas::route('/'),
            // 'create' => CreateAdminUserPenerima::route('/create'),
            // 'edit' => EditAdminUserPenerima::route('/{record}/edit'),
        ];
    }
}

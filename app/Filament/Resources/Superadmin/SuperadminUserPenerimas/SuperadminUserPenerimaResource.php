<?php

namespace App\Filament\Resources\Superadmin\SuperadminUserPenerimas;

use App\Filament\Resources\Superadmin\SuperadminUserPenerimas\Pages\CreateSuperadminUserPenerima;
use App\Filament\Resources\Superadmin\SuperadminUserPenerimas\Pages\EditSuperadminUserPenerima;
use App\Filament\Resources\Superadmin\SuperadminUserPenerimas\Pages\ListSuperadminUserPenerimas;
use App\Filament\Resources\Superadmin\SuperadminUserPenerimas\Schemas\SuperadminUserPenerimaForm;
use App\Filament\Resources\Superadmin\SuperadminUserPenerimas\Tables\SuperadminUserPenerimasTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SuperadminUserPenerimaResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'users';
    protected static ?string $slug = 'list-penerima';

    public static function form(Schema $schema): Schema
    {
        return SuperadminUserPenerimaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SuperadminUserPenerimasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
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

    protected static ?int $navigationSort = 3;

    public static function getPages(): array
    {
        return [
            'index' => ListSuperadminUserPenerimas::route('/'),
            // 'create' => CreateSuperadminUserPenerima::route('/create'),
            // 'edit' => EditSuperadminUserPenerima::route('/{record}/edit'),
        ];
    }
}

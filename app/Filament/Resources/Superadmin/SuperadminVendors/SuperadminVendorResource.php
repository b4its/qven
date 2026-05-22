<?php

namespace App\Filament\Resources\Superadmin\SuperadminVendors;

use App\Filament\Resources\Superadmin\SuperadminVendors\Pages\CreateSuperadminVendor;
use App\Filament\Resources\Superadmin\SuperadminVendors\Pages\EditSuperadminVendor;
use App\Filament\Resources\Superadmin\SuperadminVendors\Pages\ListSuperadminVendors;
use App\Filament\Resources\Superadmin\SuperadminVendors\Schemas\SuperadminVendorForm;
use App\Filament\Resources\Superadmin\SuperadminVendors\Tables\SuperadminVendorsTable;
use App\Models\Vendor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SuperadminVendorResource extends Resource
{
    protected static ?string $model = Vendor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'vendor';
    protected static ?string $slug = 'vendor';

    public static function form(Schema $schema): Schema
    {
        return SuperadminVendorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SuperadminVendorsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }



    public static function getNavigationLabel(): string
    {
        return 'Vendor';
    }

    public static function getNavigationIcon(): string
    {
    return 'heroicon-o-user';
    }
    public static function getPages(): array
    {
        return [
            'index' => ListSuperadminVendors::route('/'),
            // 'create' => CreateSuperadminVendor::route('/create'),
            // 'edit' => EditSuperadminVendor::route('/{record}/edit'),
        ];
    }
}

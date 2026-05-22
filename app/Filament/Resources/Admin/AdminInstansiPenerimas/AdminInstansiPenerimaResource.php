<?php

namespace App\Filament\Resources\Admin\AdminInstansiPenerimas;

use App\Filament\Resources\Admin\AdminInstansiPenerimas\Pages\CreateAdminInstansiPenerima;
use App\Filament\Resources\Admin\AdminInstansiPenerimas\Pages\EditAdminInstansiPenerima;
use App\Filament\Resources\Admin\AdminInstansiPenerimas\Pages\ListAdminInstansiPenerimas;
use App\Filament\Resources\Admin\AdminInstansiPenerimas\Schemas\AdminInstansiPenerimaForm;
use App\Filament\Resources\Admin\AdminInstansiPenerimas\Tables\AdminInstansiPenerimasTable;
use App\Models\AdminInstansiPenerima;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AdminInstansiPenerimaResource extends Resource
{
    protected static ?string $model = AdminInstansiPenerima::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'instansi_penerima';
    protected static ?string $slug = 'instansi-penerima';

    public static function form(Schema $schema): Schema
    {
        return AdminInstansiPenerimaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AdminInstansiPenerimasTable::configure($table);
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
            'index' => ListAdminInstansiPenerimas::route('/'),
            // 'create' => CreateAdminInstansiPenerima::route('/create'),
            // 'edit' => EditAdminInstansiPenerima::route('/{record}/edit'),
        ];
    }
}

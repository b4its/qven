<?php

namespace App\Filament\Resources\Admin\AdminCalonPenerimas;

use App\Filament\Resources\Admin\AdminCalonPenerimas\Pages\CreateAdminCalonPenerima;
use App\Filament\Resources\Admin\AdminCalonPenerimas\Pages\EditAdminCalonPenerima;
use App\Filament\Resources\Admin\AdminCalonPenerimas\Pages\ListAdminCalonPenerimas;
use App\Filament\Resources\Admin\AdminCalonPenerimas\Schemas\AdminCalonPenerimaForm;
use App\Filament\Resources\Admin\AdminCalonPenerimas\Tables\AdminCalonPenerimasTable;
use App\Models\CalonPenerima;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AdminCalonPenerimaResource extends Resource
{
    protected static ?string $model = CalonPenerima::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'calon_penerima';
    protected static ?string $slug = 'calon-penerima';

    public static function form(Schema $schema): Schema
    {
        return AdminCalonPenerimaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AdminCalonPenerimasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Calon Penerima';
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-user-group';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAdminCalonPenerimas::route('/'),
            // 'create' => CreateAdminCalonPenerima::route('/create'),
            // 'edit' => EditAdminCalonPenerima::route('/{record}/edit'),
        ];
    }
}

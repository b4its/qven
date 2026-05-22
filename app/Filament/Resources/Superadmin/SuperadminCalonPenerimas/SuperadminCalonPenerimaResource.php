<?php

namespace App\Filament\Resources\Superadmin\SuperadminCalonPenerimas;

use App\Filament\Resources\Superadmin\SuperadminCalonPenerimas\Pages\CreateSuperadminCalonPenerima;
use App\Filament\Resources\Superadmin\SuperadminCalonPenerimas\Pages\EditSuperadminCalonPenerima;
use App\Filament\Resources\Superadmin\SuperadminCalonPenerimas\Pages\ListSuperadminCalonPenerimas;
use App\Filament\Resources\Superadmin\SuperadminCalonPenerimas\Schemas\SuperadminCalonPenerimaForm;
use App\Filament\Resources\Superadmin\SuperadminCalonPenerimas\Tables\SuperadminCalonPenerimasTable;
use App\Models\CalonPenerima;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SuperadminCalonPenerimaResource extends Resource
{
    protected static ?string $model = CalonPenerima::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $slug = 'calon-penerima';
    protected static ?string $recordTitleAttribute = 'calon_penerima';

    public static function form(Schema $schema): Schema
    {
        return SuperadminCalonPenerimaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SuperadminCalonPenerimasTable::configure($table);
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
    protected static ?int $navigationSort = 10;

    public static function getPages(): array
    {
        return [
            'index' => ListSuperadminCalonPenerimas::route('/'),
            // 'create' => CreateSuperadminCalonPenerima::route('/create'),
            // 'edit' => EditSuperadminCalonPenerima::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources\Superadmin\SuperadminInstansiPenerimas;

use App\Filament\Resources\Superadmin\SuperadminInstansiPenerimas\Pages\CreateSuperadminInstansiPenerima;
use App\Filament\Resources\Superadmin\SuperadminInstansiPenerimas\Pages\EditSuperadminInstansiPenerima;
use App\Filament\Resources\Superadmin\SuperadminInstansiPenerimas\Pages\ListSuperadminInstansiPenerimas;
use App\Filament\Resources\Superadmin\SuperadminInstansiPenerimas\Schemas\SuperadminInstansiPenerimaForm;
use App\Filament\Resources\Superadmin\SuperadminInstansiPenerimas\Tables\SuperadminInstansiPenerimasTable;
use App\Models\InstansiPenerima;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SuperadminInstansiPenerimaResource extends Resource
{
    protected static ?string $model = InstansiPenerima::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'InstansiPenerima';
    protected static ?string $slug = 'instansi-penerima';

    public static function form(Schema $schema): Schema
    {
        return SuperadminInstansiPenerimaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SuperadminInstansiPenerimasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
        public static function getNavigationLabel(): string
    {
        return 'Instansi Penerima';
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-rectangle-group'; 
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSuperadminInstansiPenerimas::route('/'),
            // 'create' => CreateSuperadminInstansiPenerima::route('/create'),
            // 'edit' => EditSuperadminInstansiPenerima::route('/{record}/edit'),
        ];
    }
}

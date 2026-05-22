<?php

namespace App\Filament\Resources\Admin\AdminKotakMBGS;

use App\Filament\Resources\Admin\AdminKotakMBGS\Pages\CreateAdminKotakMBG;
use App\Filament\Resources\Admin\AdminKotakMBGS\Pages\EditAdminKotakMBG;
use App\Filament\Resources\Admin\AdminKotakMBGS\Pages\ListAdminKotakMBGS;
use App\Filament\Resources\Admin\AdminKotakMBGS\Schemas\AdminKotakMBGForm;
use App\Filament\Resources\Admin\AdminKotakMBGS\Tables\AdminKotakMBGSTable;
use App\Models\KotakMBG;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Facades\Filament;
class AdminKotakMBGResource extends Resource
{
    protected static ?string $model = KotakMBG::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'kotak_mbg';

    public static function form(Schema $schema): Schema
    {
        return AdminKotakMBGForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AdminKotakMBGSTable::configure($table);
    }

    protected static ?string $slug = 'kotak-mbg';

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Kotak MBG';
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-rectangle-group'; 
    }

    public static function getEloquentQuery(): Builder
    {
        // Gunakan parent::getEloquentQuery() untuk memulai query dasar
        return parent::getEloquentQuery()
            ->whereHas('kotak_mbg', function (Builder $query) {
                $query->where('vendor_id', Filament::getTenant()->id);
            });
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAdminKotakMBGS::route('/'),
            // 'create' => CreatedminKotakMBG::route('/create'),
            // 'edit' => EditAdminKotakMBG::route('/{record}/edit'),
        ];
    }
}

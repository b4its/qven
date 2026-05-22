<?php

namespace App\Filament\Resources\Superadmin\SuperadminKotakMBGS;

use App\Filament\Resources\Superadmin\SuperadminKotakMBGS\Pages\CreateSuperadminKotakMBG;
use App\Filament\Resources\Superadmin\SuperadminKotakMBGS\Pages\EditSuperadminKotakMBG;
use App\Filament\Resources\Superadmin\SuperadminKotakMBGS\Pages\ListSuperadminKotakMBGS;
use App\Filament\Resources\Superadmin\SuperadminKotakMBGS\Schemas\SuperadminKotakMBGForm;
use App\Filament\Resources\Superadmin\SuperadminKotakMBGS\Tables\SuperadminKotakMBGSTable;
use App\Models\KotakMBG;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SuperadminKotakMBGResource extends Resource
{
    protected static ?string $model = KotakMBG::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'kotak_mbg';

    public static function form(Schema $schema): Schema
    {
        return SuperadminKotakMBGForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SuperadminKotakMBGSTable::configure($table);
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
    
    protected static ?int $navigationSort = 11;

    public static function getPages(): array
    {
        return [
            'index' => ListSuperadminKotakMBGS::route('/'),
            // 'create' => CreateSuperadminKotakMBG::route('/create'),
            // 'edit' => EditSuperadminKotakMBG::route('/{record}/edit'),
        ];
    }
}

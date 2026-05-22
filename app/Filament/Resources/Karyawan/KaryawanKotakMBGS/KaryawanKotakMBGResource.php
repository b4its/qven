<?php

namespace App\Filament\Resources\Karyawan\KaryawanKotakMBGS;

use App\Filament\Resources\Karyawan\KaryawanKotakMBGS\Pages\CreateKaryawanKotakMBG;
use App\Filament\Resources\Karyawan\KaryawanKotakMBGS\Pages\EditKaryawanKotakMBG;
use App\Filament\Resources\Karyawan\KaryawanKotakMBGS\Pages\ListKaryawanKotakMBGS;
use App\Filament\Resources\Karyawan\KaryawanKotakMBGS\Schemas\KaryawanKotakMBGForm;
use App\Filament\Resources\Karyawan\KaryawanKotakMBGS\Tables\KaryawanKotakMBGSTable;
use App\Models\KotakMBG;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KaryawanKotakMBGResource extends Resource
{
    protected static ?string $model = KotakMBG::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'kotak_mbg';
    protected static ?string $slug = 'kotak-mbg';

    public static function form(Schema $schema): Schema
    {
        return KaryawanKotakMBGForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KaryawanKotakMBGSTable::configure($table);
    }

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

    public static function getPages(): array
    {
        return [
            'index' => ListKaryawanKotakMBGS::route('/'),
            // 'create' => CreateKaryawanKotakMBG::route('/create'),
            // 'edit' => EditKaryawanKotakMBG::route('/{record}/edit'),
        ];
    }
}

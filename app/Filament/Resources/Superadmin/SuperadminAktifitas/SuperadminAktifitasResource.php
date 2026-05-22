<?php

namespace App\Filament\Resources\Superadmin\SuperadminAktifitas;

use App\Filament\Resources\Superadmin\SuperadminAktifitas\Pages\CreateSuperadminAktifitas;
use App\Filament\Resources\Superadmin\SuperadminAktifitas\Pages\EditSuperadminAktifitas;
use App\Filament\Resources\Superadmin\SuperadminAktifitas\Pages\ListSuperadminAktifitas;
use App\Filament\Resources\Superadmin\SuperadminAktifitas\Schemas\SuperadminAktifitasForm;
use App\Filament\Resources\Superadmin\SuperadminAktifitas\Tables\SuperadminAktifitasTable;
use App\Models\SuperadminAktifitas;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SuperadminAktifitasResource extends Resource
{
    protected static ?string $model = SuperadminAktifitas::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'aktifitas';

    public static function form(Schema $schema): Schema
    {
        return SuperadminAktifitasForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SuperadminAktifitasTable::configure($table);
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
            'index' => ListSuperadminAktifitas::route('/'),
            // 'create' => CreateSuperadminAktifitas::route('/create'),
            // 'edit' => EditSuperadminAktifitas::route('/{record}/edit'),
        ];
    }
}

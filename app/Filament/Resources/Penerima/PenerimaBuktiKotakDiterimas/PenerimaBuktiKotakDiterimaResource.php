<?php

namespace App\Filament\Resources\Penerima\PenerimaBuktiKotakDiterimas;

use App\Filament\Resources\Penerima\PenerimaBuktiKotakDiterimas\Pages\CreatePenerimaBuktiKotakDiterima;
use App\Filament\Resources\Penerima\PenerimaBuktiKotakDiterimas\Pages\EditPenerimaBuktiKotakDiterima;
use App\Filament\Resources\Penerima\PenerimaBuktiKotakDiterimas\Pages\ListPenerimaBuktiKotakDiterimas;
use App\Filament\Resources\Penerima\PenerimaBuktiKotakDiterimas\Schemas\PenerimaBuktiKotakDiterimaForm;
use App\Filament\Resources\Penerima\PenerimaBuktiKotakDiterimas\Tables\PenerimaBuktiKotakDiterimasTable;
use App\Models\BuktiKotakDiterima;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PenerimaBuktiKotakDiterimaResource extends Resource
{
    protected static ?string $model = BuktiKotakDiterima::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'bukti_kotak_diterima';
    protected static ?string $slug = 'bukti-kotak-diterima';

    public static function form(Schema $schema): Schema
    {
        return PenerimaBuktiKotakDiterimaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PenerimaBuktiKotakDiterimasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        // Gunakan parent::getEloquentQuery() untuk memulai query dasar
        return parent::getEloquentQuery()
            ->whereHas('bukti_kotak_mbg', function (Builder $query) {
                $query->where('instansi_penerima_id', Filament::getTenant()->id);
            });
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
            'index' => ListPenerimaBuktiKotakDiterimas::route('/'),
            // 'create' => CreatePenerimaBuktiKotakDiterima::route('/create'),
            // 'edit' => EditPenerimaBuktiKotakDiterima::route('/{record}/edit'),
        ];
    }
}

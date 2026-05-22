<?php

namespace App\Filament\Resources\Admin\AdminUserPenerimas\Tables;

use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AdminUserPenerimasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                return $query
                    ->selectRaw('users.*, ROW_NUMBER() OVER (ORDER BY created_at DESC) as row_num');
            })
            ->columns([
                TextColumn::make('row_num')
                    ->label('No')
                    ->sortable(),
                TextColumn::make("name"),
                TextColumn::make("email"),  
            ])
            ->filters([
                //
            ])
            ->recordActions([
               ViewAction::make('view_details')
                    ->label('Detail')
                    ->icon('heroicon-m-eye')
                    ->button()
                    ->color('gray') // 'secondary' biasanya di-map ke 'gray' di Filament v3
                    ->infolist([
                        Section::make('Detail Penerima')
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Nama Lengkap'),
                                TextEntry::make('email')
                                    ->label('Email'),
                                TextEntry::make('nik')
                                    ->label('NIK'),
                                TextEntry::make('created_at')
                                    ->label('Tanggal Daftar')
                                    ->dateTime('d M Y H:i'),
                            ])->columns(2)
                    ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Bookings\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class ServicesRelationManager extends RelationManager
{
    protected static string $relationship = 'services';

    protected static ?string $title = 'Layanan yang Dipesan';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->disabled(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Layanan'),
                Tables\Columns\TextColumn::make('pivot.price')
                    ->label('Harga')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('pivot.duration')
                    ->label('Durasi')
                    ->suffix(' menit'),
            ])
            ->headerActions([
                // Tables\Actions\AttachAction::make(), // matikan dulu biar tidak bisa tambah dari sini
            ])
            ->actions([
                // Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }
}
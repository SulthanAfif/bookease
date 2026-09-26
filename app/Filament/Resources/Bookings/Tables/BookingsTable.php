<?php

namespace App\Filament\Resources\Bookings\Tables;

use App\Mail\BookingStatusUpdated;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;

class BookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable(),
                TextColumn::make('staff.name')
                    ->label('Staff')
                    ->searchable(),
                TextColumn::make('services.name')
                    ->label('Layanan')
                    ->badge()
                    ->separator(',')
                    ->limitList(2),
                TextColumn::make('booking_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('start_time')
                    ->label('Mulai')
                    ->time('H:i'),
                TextColumn::make('end_time')
                    ->label('Selesai')
                    ->time('H:i'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('total_price')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('booking_date', 'desc')
            ->recordActions([
                EditAction::make(),

                Action::make('confirm')
                    ->label('Confirm')
                    ->icon('heroicon-o-check-circle')
                    ->color('info')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Booking')
                    ->modalDescription('Yakin ingin mengonfirmasi booking ini? Customer akan menerima email.')
                    ->action(function ($record) {
                        $old = $record->status;
                        $record->update(['status' => 'confirmed']);
                        self::notifyCustomer($record, $old);
                    }),

                Action::make('complete')
                    ->label('Complete')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn ($record) => in_array($record->status, ['pending', 'confirmed']))
                    ->requiresConfirmation()
                    ->modalHeading('Selesaikan Booking')
                    ->modalDescription('Tandai booking ini sebagai selesai? Customer akan menerima email.')
                    ->action(function ($record) {
                        $old = $record->status;
                        $record->update(['status' => 'completed']);
                        self::notifyCustomer($record, $old);
                    }),

                Action::make('cancel')
                    ->label('Cancel')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => in_array($record->status, ['pending', 'confirmed']))
                    ->requiresConfirmation()
                    ->modalHeading('Batalkan Booking')
                    ->modalDescription('Yakin ingin membatalkan booking ini? Customer akan menerima email.')
                    ->action(function ($record) {
                        $old = $record->status;
                        $record->update(['status' => 'cancelled']);
                        self::notifyCustomer($record, $old);
                    }),

                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected static function notifyCustomer($record, string $oldStatus): void
    {
        try {
            $record->loadMissing(['user', 'staff', 'services']);
            if ($record->user?->email) {
                Mail::to($record->user->email)->send(new BookingStatusUpdated($record, $oldStatus));
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }
}

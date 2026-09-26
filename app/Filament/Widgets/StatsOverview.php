<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Service;
use App\Models\Staff;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $todayBookings = Booking::whereDate('booking_date', today())
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        $todayRevenue = Booking::whereDate('booking_date', today())
            ->whereIn('status', ['confirmed', 'completed'])
            ->sum('total_price');

        $totalBookings = Booking::count();
        $activeServices = Service::where('is_active', true)->count();
        $activeStaff = Staff::where('is_active', true)->count();

        return [
            Stat::make('Booking Hari Ini', $todayBookings)
                ->description('Pending & Confirmed')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('success'),

            Stat::make('Pendapatan Hari Ini', 'Rp ' . number_format($todayRevenue, 0, ',', '.'))
                ->description('Confirmed & Completed')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),

            Stat::make('Total Booking', $totalBookings)
                ->description('Semua waktu')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('primary'),

            Stat::make('Layanan Aktif', $activeServices)
                ->description('Staff aktif: ' . $activeStaff)
                ->descriptionIcon('heroicon-m-scissors')
                ->color('info'),
        ];
    }
}
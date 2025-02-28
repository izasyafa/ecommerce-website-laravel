<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use App\Models\Order;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use App\Filament\Resources\OrderResource;
use Filament\Tables\Columns\SelectColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget
{
    protected array | string | int $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                OrderResource::getEloquentQuery()
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('id')
                    ->label('Order ID'),

                TextColumn::make('user.name')
                    ->label('Customer'),

                TextColumn::make('grand_total')
                    ->numeric()
                    ->money('IDR'),

                BadgeColumn::make('status')
                    ->colors([
                        'new' => 'info',
                        'processing' => 'warning',
                        'shipped' => 'success',
                        'delivered' => 'success',
                        'canceled' => 'danger',
                    ])
                    ->icons([
                        'new' => 'heroicon-m-sparkles',
                        'processing' => 'heroicon-m-arrow-path',
                        'shipped' => 'heroicon-m-truck',
                        'delivered' => 'heroicon-m-check-badge',
                        'canceled' => 'heroicon-m-x-circle',
                    ]),

                TextColumn::make('payment_status'),

                TextColumn::make('payment_method'),

                TextColumn::make('created_at')
                    ->label('Order date')
                    ->dateTime()
            ])
            ->actions([
                Action::make('View Order')
                ->url(fn  (Order $record): string => OrderResource::getUrl('edit', ['record' => $record]))
                ->icon('heroicon-m-eye')
            ]);
    }
}

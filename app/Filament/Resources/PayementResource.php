<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PayementResource\Pages;
use App\Filament\Resources\PayementResource\RelationManagers;
use App\Models\Payement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class PayementResource extends Resource
{
    protected static ?string $model = Payement::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Papers Management';
    protected static ?string $navigationLabel = 'Payments';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Payment Details')
                ->schema([
                    Forms\Components\TextInput::make('amount')
                        ->numeric()
                        ->required()
                        ->label('Amount'),

                    Forms\Components\Select::make('payment_method') // Use exact model key: change to 'payement_method' if it's misspelled in DB
                        ->options([
                            'cash' => 'Cash',
                            'bank_transfer' => 'Bank Transfer',
                            'credit_card' => 'Credit Card',
                        ])
                        ->required()
                        ->label('Payment Method'),

                    Forms\Components\Select::make('status')
                        ->options([
                            'pending' => 'Pending',
                            'completed' => 'Completed',
                            'failed' => 'Failed',
                        ])
                        ->required()
                        ->label('Status'),

                    Forms\Components\TextInput::make('reference')
                        ->required()
                        ->maxLength(255)
                        ->label('Reference'),
                ]),

            Forms\Components\Section::make('Associations')
                ->schema([
                    Forms\Components\Select::make('unit_id')
                        ->relationship('unit', 'name')
                        ->required()
                        ->label('Unit'),

                    Forms\Components\Select::make('manager_id')
                        ->relationship('manager', 'first_name')
                        ->required()
                        ->label('Manager'),
                ]),
        ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->searchable()
                    ->label('Payment ID'),

                Tables\Columns\TextColumn::make('amount')
                    ->money('usd', true)
                    ->sortable()
                    ->label('Amount'),

                Tables\Columns\TextColumn::make('unit.name')
                    ->sortable()
                    ->searchable()
                    ->label('Unit Name'),

                Tables\Columns\TextColumn::make('manager.first_name')
                    ->sortable()
                    ->searchable()
                    ->label('Manager'),

                Tables\Columns\TextColumn::make('payement_method') // Update to 'payement_method' if the typo is in your DB
                    ->sortable()
                    ->searchable()
                    ->label('Payment Method'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'completed',
                        'warning' => 'pending',
                        'danger' => 'failed',
                    ])
                    ->sortable()
                    ->searchable()
                    ->label('Status'),

                Tables\Columns\TextColumn::make('reference')
                    ->sortable()
                    ->searchable()
                    ->label('Reference'),

                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime()
                    ->label('Created At'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->sortable()
                    ->dateTime()
                    ->label('Updated At'),
            ])

            ->filters([
                Tables\Filters\Filter::make('created_today')
                    ->label('Aujourd\'hui')
                    ->query(fn(Builder $query): Builder => $query->whereDate('created_at', now()->toDateString())),
                Tables\Filters\Filter::make('created_this_week')
                    ->label('Cette semaine')
                    ->query(fn(Builder $query): Builder => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])),
                Tables\Filters\Filter::make('created_this_month')
                    ->label('Ce mois-ci')
                    ->query(fn(Builder $query): Builder => $query->whereMonth('created_at', now()->month)),
                Tables\Filters\Filter::make('created_this_year')
                    ->label('Cette année')
                    ->query(fn(Builder $query): Builder => $query->whereYear('created_at', now()->year)),
            ])
            ->actions([
                Tables\Actions\CreateAction::make()
                    ->label('Create')
                    ->icon('heroicon-o-plus')
                    ->visible(fn() => \Illuminate\Support\Facades\Auth::user()->role === 'ultra_admin'),
                Tables\Actions\EditAction::make()
                    ->visible(fn() => \Illuminate\Support\Facades\Auth::user()->role === 'ultra_admin'),
                Tables\Actions\ViewAction::make()
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->visible(fn() => \Illuminate\Support\Facades\Auth::user()->role === 'ultra_admin' || 'admin')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn() => \Illuminate\Support\Facades\Auth::user()->role === 'ultra_admin'),
                    Tables\Actions\BulkAction::make('exportCsv')
                        ->visible(fn() => \Illuminate\Support\Facades\Auth::user()->role === 'ultra_admin')
                        ->label('Export to CSV')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function (Collection $records) {
                            $csvData = $records->map(function ($record) {
                                return $record->only([
                                    'unit_id',
                                    'manager_id',
                                    'amount',
                                    'payement_method',
                                    'reference',
                                    'status',
                                ]);
                            });

                            $filename = 'export-payments-' . now()->format('Y-m-d_H-i-s') . '.csv';

                            $stream = fopen('php://temp', 'r+');
                            fputcsv($stream, array_keys($csvData->first() ?? []));
                            foreach ($csvData as $row) {
                                fputcsv($stream, $row);
                            }
                            rewind($stream);

                            return response()->streamDownload(function () use ($stream) {
                                fpassthru($stream);
                            }, $filename, [
                                'Content-Type' => 'text/csv',
                                'Content-Disposition' => "attachment; filename={$filename}",
                            ]);
                        }),
                ]),
            ]);
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
            'index' => Pages\ListPayements::route('/'),
            'create' => Pages\CreatePayement::route('/create'),
            'edit' => Pages\EditPayement::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return \Illuminate\Support\Facades\Auth::user()->role === 'ultra_admin' || 'admin';
    }

    public static function canView($record): bool
    {
        return \Illuminate\Support\Facades\Auth::user()->role === 'ultra_admin' || 'admin';
    }

    public static function canCreate(): bool
    {
        return \Illuminate\Support\Facades\Auth::user()->role === 'ultra_admin';
    }

    public static function canEdit($record): bool
    {
        return \Illuminate\Support\Facades\Auth::user()->role === 'ultra_admin';
    }

    public static function canDelete($record): bool
    {
        return \Illuminate\Support\Facades\Auth::user()->role === 'ultra_admin';
    }
}

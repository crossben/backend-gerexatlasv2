<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UnitResource\Pages;
use App\Models\Unit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;


class UnitResource extends Resource
{
    protected static ?string $model = Unit::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Management';
    protected static ?string $navigationLabel = 'Units';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Associations')
                ->description('Link this unit to a building and manager.')
                ->schema([
                    Forms\Components\Select::make('building_id')
                        ->relationship('building', 'name')
                        ->required()
                        ->label('Building Name')
                        ->helperText('Select the building associated with this unit.'),

                    Forms\Components\Select::make('manager_id')
                        ->relationship('manager', 'first_name')
                        ->label('Manager')
                        ->searchable()
                        ->preload()
                        ->helperText('Assign a manager to this unit.'),
                ]),

            Forms\Components\Section::make('Unit Information')
                ->description('Basic and tenant information.')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->label('Unit Name'),

                    Forms\Components\Select::make('type')
                        ->options([
                            'apartment' => 'Apartment',
                            'office' => 'Office',
                            'commercial' => 'Commercial',
                        ])
                        ->required()
                        ->label('Type'),

                    Forms\Components\TextInput::make('tenant_name')
                        ->label('Tenant Name'),

                    Forms\Components\TextInput::make('tenant_email')
                        ->email()
                        ->label('Tenant Email'),

                    Forms\Components\TextInput::make('tenant_phone')
                        ->label('Tenant Phone'),
                ]),

            Forms\Components\Section::make('Contract Details')
                ->schema([
                    Forms\Components\DatePicker::make('start_date')
                        ->label('Start Date'),

                    Forms\Components\DatePicker::make('end_date')
                        ->label('End Date'),

                    Forms\Components\TextInput::make('rent_amount')
                        ->numeric()
                        ->label('Rent Amount'),

                    Forms\Components\Select::make('contract_type')
                        ->options([
                            'fixed' => 'Fixed-Term',
                            'month_to_month' => 'Month-to-Month',
                        ])
                        ->label('Contract Type'),
                ]),

            Forms\Components\Section::make('Status and Reference')
                ->description('Current occupancy or availability status.')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->options([
                            'available' => 'Available',
                            'occupied' => 'Occupied',
                            'under_maintenance' => 'Under Maintenance',
                        ])
                        ->required()
                        ->label('Status'),

                    Forms\Components\Textarea::make('reference')
                        ->required()
                        ->maxLength(255)
                        ->label('Reference'),
                ]),
        ]);
    }


    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('building.name')
                ->sortable()
                ->searchable()
                ->label('Building Name'),

            Tables\Columns\TextColumn::make('name')
                ->sortable()
                ->searchable()
                ->label('Unit Name'),

            Tables\Columns\TextColumn::make('type')
                ->sortable()
                ->searchable()
                ->label('Type'),

            Tables\Columns\TextColumn::make('tenant_name')
                ->sortable()
                ->searchable()
                ->label('Tenant Name'),

            Tables\Columns\TextColumn::make('tenant_email')
                ->label('Tenant Email')
                ->sortable(),

            Tables\Columns\TextColumn::make('tenant_phone')
                ->label('Tenant Phone')
                ->sortable(),

            Tables\Columns\TextColumn::make('start_date')
                ->date()
                ->label('Start Date'),

            Tables\Columns\TextColumn::make('end_date')
                ->date()
                ->label('End Date'),

            Tables\Columns\TextColumn::make('rent_amount')
                ->money('usd', true)
                ->label('Rent Amount'),

            Tables\Columns\TextColumn::make('contract_type')
                ->label('Contract Type')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('status')
                ->badge()
                ->colors([
                    'success' => 'available',
                    'warning' => 'occupied',
                    'danger' => 'under_maintenance',
                ])
                ->label('Status'),

            Tables\Columns\TextColumn::make('reference')
                ->label('Reference'),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->label('Created At')
                ->toggleable(isToggledHiddenByDefault: true),
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
                                    'building_id',
                                    'manager_id',
                                    'name',
                                    'type',
                                    'tenant_name',
                                    'tenant_email',
                                    'tenant_phone',
                                    'start_date',
                                    'end_date',
                                    'rent_amount',
                                    'contract_type',
                                    'reference',
                                    'status',
                                    'created_at',
                                ]);
                            });

                            $filename = 'export-units-' . now()->format('Y-m-d_H-i-s') . '.csv';

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
            'index' => Pages\ListUnits::route('/'),
            'create' => Pages\CreateUnit::route('/create'),
            'edit' => Pages\EditUnit::route('/{record}/edit'),
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

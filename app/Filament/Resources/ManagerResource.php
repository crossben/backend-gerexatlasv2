<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ManagerResource\Pages;
use App\Models\Manager;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;



class ManagerResource extends Resource
{
    protected static ?string $model = Manager::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Human Resources';
    protected static ?string $navigationLabel = 'Managers';
    protected static ?string $modelLabel = 'Manager';
    protected static ?string $pluralModelLabel = 'Managers';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Manager Information')
                ->description('Basic details about the manager.')
                ->schema([
                    Forms\Components\TextInput::make('first_name')
                        ->required()
                        ->label('First Name')
                        ->helperText('Enter the first name of the manager.'),

                    Forms\Components\TextInput::make('last_name')
                        ->required()
                        ->label('Last Name')
                        ->helperText('Enter the last name of the manager.'),

                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->required()
                        ->label('Email')
                        ->helperText('Provide a valid email address.'),

                    Forms\Components\TextInput::make('phone')
                        ->tel()
                        ->label('Phone')
                        ->helperText('Include the country code if applicable.'),

                    Forms\Components\TextInput::make('address')
                        ->label('Address'),

                    Forms\Components\TextInput::make('city')
                        ->label('City'),

                    Forms\Components\TextInput::make('country')
                        ->label('Country'),

                    Forms\Components\TextInput::make('role')
                        ->label('Role')
                        ->required()
                        ->helperText('Define the role or title of the manager.'),
                ]),

            Forms\Components\Section::make('Sensitive Information')
                ->description('Sensitive information about the manager.')
                ->schema([
                    Forms\Components\TextInput::make('password')
                        ->password()
                        ->required()
                        ->label('Password')
                        ->helperText('Enter a secure password.')
                        ->dehydrateStateUsing(fn($state) => $state ? \Illuminate\Support\Facades\Hash::make($state) : null)
                        ->dehydrated(fn($state) => filled($state)),

                    Forms\Components\TextInput::make('reference')
                        ->label('Reference')
                        ->helperText('Enter a reference for the manager.'),

                    Forms\Components\Select::make('status')
                        ->options([
                            'active' => 'Active',
                            'inactive' => 'Inactive',
                            'suspended' => 'Suspended',
                        ])
                        ->required()
                        ->label('Status')
                        ->helperText('Select the status of the manager.'),
                ]),

            Forms\Components\Section::make('System Info')
                ->schema([
                    Forms\Components\TextInput::make('buildings_count')
                        ->numeric()
                        ->disabled()
                        ->dehydrated(false)
                        ->label('Buildings Managed')
                        ->helperText('This value is automatically calculated.'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('first_name')
                ->label('First Name')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('last_name')
                ->label('Last Name')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('email')
                ->label('Email')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('phone')
                ->label('Phone')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('address')
                ->label('Address')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('city')
                ->label('City')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('country')
                ->label('Country')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('role')
                ->label('Role')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('reference')
                ->label('Reference')
                ->sortable()
                ->searchable(),

            Tables\Columns\BadgeColumn::make('status')
                ->label('Status')
                ->colors([
                    'success' => 'active',
                    'warning' => 'inactive',
                    'danger' => 'suspended',
                ])
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('buildings_count')
                ->label('Buildings Managed')
                ->numeric()
                ->sortable(),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Created At')
                ->dateTime()
                ->sortable(),

            Tables\Columns\TextColumn::make('updated_at')
                ->label('Updated At')
                ->dateTime()
                ->sortable(),
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
                        ->label('Export to CSV')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->visible(fn() => \Illuminate\Support\Facades\Auth::user()->role === 'ultra_admin')
                        ->action(function (Collection $records) {
                            $csvData = $records->map(function ($record) {
                                return $record->only([
                                    'first_name',
                                    'last_name',
                                    'email',
                                    'phone',
                                    'password',
                                    'role',
                                    'status',
                                    'reference',
                                    'address',
                                    'city',
                                    'country',
                                    'buildings_count',
                                ]);
                            });

                            $filename = 'export-managers-' . now()->format('Y-m-d_H-i-s') . '.csv';

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
                        })
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
            'index' => Pages\ListManagers::route('/'),
            'create' => Pages\CreateManager::route('/create'),
            'edit' => Pages\EditManager::route('/{record}/edit'),
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

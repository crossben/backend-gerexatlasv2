<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BuildingResource\Pages;
use App\Filament\Resources\BuildingResource\RelationManagers;
use App\Models\Building;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class BuildingResource extends Resource
{
    protected static ?string $model = Building::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Management';
    protected static ?string $navigationLabel = 'Buildings';

    protected static ?string $modelLabel = 'Building';
    protected static ?string $pluralModelLabel = 'Buildings';
    public static function form(Form $form): Form
    {
        return $form->schema([
            // Basic Info Section
            Forms\Components\Section::make('Basic Information')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->label('Name'),

                    Forms\Components\TextInput::make('type')
                        ->required()
                        ->label('Type'),
                ]),

            // Location Section
            Forms\Components\Section::make('Location')
                ->schema([
                    Forms\Components\TextInput::make('city')
                        ->label('City'),

                    Forms\Components\TextInput::make('address')
                        ->label('Address'),
                ]),

            // Management Section
            Forms\Components\Section::make('Management')
                ->schema([
                    Forms\Components\Select::make('manager_id')
                        ->label('Manager')
                        ->required()
                        ->relationship('manager', 'first_name')
                        ->searchable()
                        ->preload()
                        ->placeholder('Select a manager'),
                ]),

            // Description Section
            Forms\Components\Section::make('Additional Details')
                ->schema([
                    Forms\Components\Textarea::make('description')
                        ->label('Description')
                        ->maxLength(500)
                        ->placeholder('Enter description here'),

                    Forms\Components\Textarea::make('reference')
                        ->label('Reference')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Enter reference here'),

                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'active' => 'Active',
                            'inactive' => 'Inactive',
                            'suspended' => 'Suspended',
                        ])
                        ->required()
                        ->placeholder('Select a status')
                        ->default('active'),
                ]),
        ]);
    }


    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')
                ->sortable()
                ->searchable()
                ->label('Name'),

            Tables\Columns\TextColumn::make('type')
                ->sortable()
                ->searchable()
                ->label('Type'),

            Tables\Columns\TextColumn::make('city')
                ->sortable()
                ->searchable()
                ->label('City'),

            Tables\Columns\TextColumn::make('address')
                ->sortable()
                ->searchable()
                ->label('Address'),

            Tables\Columns\TextColumn::make('manager.first_name')
                ->sortable()
                ->searchable()
                ->label('Manager'),

            Tables\Columns\TextColumn::make('description')
                ->limit(50)
                ->label('Description'),

            Tables\Columns\TextColumn::make('reference')
                ->label('Reference'),

            Tables\Columns\BadgeColumn::make('status')
                ->colors([
                    'primary' => 'active',
                    'secondary' => 'inactive',
                    'danger' => 'suspended',
                ])
                ->label('Status'),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->label('Created At')
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
                        ->action(function (Collection $records) {
                            $csvData = $records->map(function ($record) {
                                return $record->only([
                                    'id',
                                    'name',
                                    'type',
                                    'city',
                                    'address',
                                    'status',
                                    'reference',
                                    'created_at',
                                ]);
                            });

                            $filename = 'export-buildings-' . now()->format('Y-m-d_H-i-s') . '.csv';

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
            'index' => Pages\ListBuildings::route('/'),
            'create' => Pages\CreateBuilding::route('/create'),
            'edit' => Pages\EditBuilding::route('/{record}/edit'),
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

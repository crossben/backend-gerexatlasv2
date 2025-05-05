<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContractResource\Pages;
use App\Filament\Resources\ContractResource\RelationManagers;
use App\Models\Contract;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists\Components\TextEntry;

class ContractResource extends Resource
{
    protected static ?string $model = Contract::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Papers Management';
    protected static ?string $navigationLabel = 'Contracts';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Tenant & Unit Selection Section
                Forms\Components\Section::make('Tenant & Unit Details')
                    ->schema([
                        Forms\Components\Select::make('tenant_id')
                            ->relationship('tenant', 'name')
                            ->required()
                            ->preload()
                            ->searchable(true)
                            ->label('Tenant'),

                        Forms\Components\Select::make('unit_id')
                            ->relationship('unit', 'name')
                            ->required()
                            ->preload()
                            ->searchable(true)
                            ->label('Unit'),
                    ]),
                // Lease Dates Section
                Forms\Components\Section::make('Lease Dates')
                    ->schema([
                        Forms\Components\DatePicker::make('start_date')
                            ->required()
                            ->label('Start Date'),

                        Forms\Components\DatePicker::make('end_date')
                            ->label('End Date'),
                    ]),
                // Rent Information Section
                Forms\Components\Section::make('Rent Information')
                    ->schema([
                        Forms\Components\TextInput::make('rent_amount')
                            ->required()
                            ->label('Rent Amount'),
                    ]),
                Forms\Components\Section::make('Important Documents')
                    ->schema([
                        Forms\Components\RichEditor::make('contract_body')
                            ->label('Contract Body')
                            ->required()
                            ->columnSpan('full')
                            ->disableToolbarButtons([
                                'attachFiles',
                                'codeBlock',
                                'h1',
                                'h2',
                                'h3',
                                'h4',
                                'h5',
                                'h6',
                            ]),
                        Forms\Components\Select::make('contract_type')
                            ->required()
                            ->label('Contract Type')
                            ->searchable(true)
                            ->options([
                                'type1' => 'Type 1',
                                'type2' => 'Type 2',
                                'type3' => 'Type 3',
                                'type4' => 'Type 4',
                            ])
                            ->searchable(),
                        Forms\Components\Textarea::make('reference')
                            ->label('Reference')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'pending' => 'Pending',
                            ])
                            ->searchable()
                            ->required()
                            ->placeholder('Enter status here'),
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
                    ->label('ID'),
                Tables\Columns\TextColumn::make('tenant.name')
                    ->sortable()
                    ->searchable()
                    ->label('Tenant Name'),
                Tables\Columns\TextColumn::make('unit.name')
                    ->sortable()
                    ->searchable()
                    ->label('Unit Name'),
                Tables\Columns\TextColumn::make('start_date')
                    ->sortable()
                    ->date('Y-m-d')
                    ->label('Start Date'),
                Tables\Columns\TextColumn::make('end_date')
                    ->sortable()
                    ->date('Y-m-d')
                    ->label('End Date'),
                Tables\Columns\TextColumn::make('rent_amount')
                    ->sortable()
                    ->searchable()
                    ->label('Rent Amount'),
                // Tables\Columns\TextColumn::make('pdf_url')
                //     ->url(fn($record) => $record->pdf_url)
                //     ->label('PDF URL')
                //     ->sortable()
                //     ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->date('Y-m-d')
                    ->label('Created At'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->sortable()
                    ->date('Y-m-d')
                    ->label('Updated At'),
                Tables\Columns\TextColumn::make('contract_body')
                    ->html()
                    ->limit(50)
                    ->sortable()
                    ->searchable()
                    ->label('Contract Body'),
                Tables\Columns\TextColumn::make('contract_type')
                    ->html()
                    ->sortable()
                    ->searchable()
                    ->label('Contract Type')
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
                        ->visible(fn() => \Illuminate\Support\Facades\Auth::user()->role === 'ultra_admin')
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
            'index' => Pages\ListContracts::route('/'),
            'create' => Pages\CreateContract::route('/create'),
            'edit' => Pages\EditContract::route('/{record}/edit'),
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

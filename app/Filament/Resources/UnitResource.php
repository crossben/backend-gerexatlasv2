<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UnitResource\Pages;
use App\Models\Unit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UnitResource extends Resource
{
    protected static ?string $model = Unit::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Building Management';
    protected static ?string $navigationLabel = 'Units';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Associations')
                    ->description('Link this unit to a building and tenant.')
                    ->schema([
                        Forms\Components\Select::make('building_id')
                            ->relationship('building', 'name')
                            ->required()
                            ->label('Building Name')
                            ->helperText('Select the building associated with this unit.'),

                        Forms\Components\Select::make('tenant_id')
                            ->relationship('tenant', 'name')
                            ->label('Tenant Name')
                            ->helperText('Select the tenant associated with this unit, if applicable.'),
                    ]),

                Forms\Components\Section::make('Unit Information')
                    ->description('Basic information about the unit.')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->label('Unit Name')
                            ->helperText('Enter the name or identifier for the unit.'),

                        Forms\Components\TextInput::make('surface')
                            ->required()
                            ->label('Surface')
                            ->helperText('Specify the unit\'s surface area in square meters.'),

                        Forms\Components\Select::make('type')
                            ->options([
                                'apartment' => 'Apartment',
                                'office' => 'Office',
                                'commercial' => 'Commercial',
                            ])
                            ->required()
                            ->label('Type')
                            ->helperText('Choose the appropriate category for the unit.'),
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
                            ->label('Status')
                            ->helperText('Select the current operational status of the unit.'),
                        Forms\Components\Textarea::make('reference')
                            ->label('Reference')
                            ->required()
                            ->maxLength(255),
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
                Tables\Columns\TextColumn::make('building.name')
                    ->sortable()
                    ->searchable()
                    ->label('Building Name'),
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label('Unit Name'),
                Tables\Columns\TextColumn::make('tenant.name')
                    ->sortable()
                    ->searchable()
                    ->label('Tenant Name'),
                Tables\Columns\TextColumn::make('contract.contract_type')
                    ->sortable()
                    ->searchable()
                    ->label('Contract Type'),
                Tables\Columns\TextColumn::make('surface')
                    ->sortable()
                    ->searchable()
                    ->label('Surface'),
                Tables\Columns\TextColumn::make('type')
                    ->sortable()
                    ->searchable()
                    ->label('Type'),
                Tables\Columns\TextColumn::make('status')
                    ->sortable()
                    ->searchable()
                    ->label('Status'),
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
}

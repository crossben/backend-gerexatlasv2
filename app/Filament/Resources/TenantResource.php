<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TenantResource\Pages;
use App\Models\Tenant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'User Management';
    protected static ?string $navigationLabel = 'Tenants';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Unit Details')
                    ->description('Select the unit associated with this record.')
                    ->schema([
                        Forms\Components\Select::make('unit_id')
                            ->relationship('unit', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('Unit Name')
                            ->helperText('Choose the appropriate unit from the list.'),
                    ]),

                Forms\Components\Section::make('Personal Information')
                    ->description('Basic personal details.')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->label('Name')
                            ->helperText('Enter the full name.'),
                        Forms\Components\TextInput::make('nationality')
                            ->label('Nationality')
                            ->helperText('Specify the nationality.'),
                    ]),

                Forms\Components\Section::make('Contact Details')
                    ->description('How we can reach this person.')
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->label('Email')
                            ->helperText('Provide a valid email address.'),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->label('Phone')
                            ->helperText('Include the country code if applicable.'),
                    ]),
                Forms\Components\Section::make('Additional Details')
                    ->description('How we can reach this person.')
                    ->schema([
                        Forms\Components\Textarea::make('reference')
                            ->label('Reference')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->required()
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'suspended' => 'Suspended',
                            ])
                            ->placeholder('Enter status here'),
                    ]),
                // Forms\Components\Textarea::make('reference')
                //     ->label('Reference')
                //     ->required()
                //     ->maxLength(255),
                // Forms\Components\TextInput::make('status')
                //     ->label('Status')
                //     ->required()
                //     ->maxLength(100)
                //     ->placeholder('Enter status here'),
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
                Tables\Columns\TextColumn::make('unit.name')
                    ->sortable()
                    ->searchable()
                    ->label('Unit Name'),
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label('Name'),
                Tables\Columns\TextColumn::make('email')
                    ->sortable()
                    ->searchable()
                    ->label('Email'),
                Tables\Columns\TextColumn::make('phone')
                    ->sortable()
                    ->searchable()
                    ->label('Phone'),
                Tables\Columns\TextColumn::make('nationality')
                    ->sortable()
                    ->searchable()
                    ->label('Nationality'),
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
            'index' => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/create'),
            'edit' => Pages\EditTenant::route('/{record}/edit'),
        ];
    }
}

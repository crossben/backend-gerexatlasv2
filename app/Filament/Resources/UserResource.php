<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;


class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $chart = UserChart::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationGroup = 'Human Resources';
    protected static ?string $navigationLabel = 'Users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('User Information')
                    ->description('Basic details about the user account.')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->label('Name')
                            ->helperText('Enter the user’s full name.'),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->label('Email')
                            ->helperText('Provide a valid and active email address.'),

                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required()
                            ->minLength(8)
                            ->revealable()
                            ->label('Password')
                            ->helperText('Choose a strong password.'),

                        Forms\Components\Select::make('role')
                            ->options([
                                'ultra_admin' => 'Ultra admin',
                                'admin' => 'Admin',
                                'manager' => 'Manager',
                            ])
                            ->required()
                            ->label('Role')
                            ->helperText('Assign an appropriate role to this user.'),
                        Forms\Components\Section::make('Account Status')
                            ->description('Control the user\'s account status.')
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'active' => 'Active',
                                        'inactive' => 'Inactive',
                                        'suspended' => 'Suspended',
                                    ])
                                    ->label('Status')
                                    ->helperText('Manage the availability of this user\'s account.'),
                            ]),
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
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label('Name'),
                Tables\Columns\TextColumn::make('email')
                    ->sortable()
                    ->searchable()
                    ->label('Email'),
                Tables\Columns\TextColumn::make('role')
                    ->sortable()
                    ->searchable()
                    ->label('Role'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
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

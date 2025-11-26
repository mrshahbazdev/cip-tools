<?php

namespace App\Filament\Resources\Users; // FIX: Namespace updated to match the file path

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\TernaryFilter; // TernaryFilter ke liye zaroori
use Illuminate\Support\Carbon;
use Filament\Support\Enums\IconSize; // Icon size ke liye
use Filament\Schemas\Schema; // FIX: Schema class import kiya
use Filament\Forms\Form; // Ye use statement hata diya jayega

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    // FIX 1: Navigation Icon Type Update (BackedEnum|string|null)
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-users';
    
    // FIX 2: Navigation Group Type Update (UnitEnum|string|null)
    protected static string | \UnitEnum | null $navigationGroup = 'Project Management';
    
    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'email'; 

    // FIX 3: Signature change from Form to Schema
    public static function form(Schema $schema): Schema 
    {
        return $schema
            ->components([ // FIX: Schema method uses components()
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                TextInput::make('password')
                    ->password()
                    ->maxLength(255)
                    ->required(fn (string $operation): bool => $operation === 'create'),
                
                // Super Admin Role Management
                Toggle::make('is_super_admin')
                    ->label('Is Global Super Admin')
                    ->helperText('Agar ON hai, to user Central Dashboard par sabhi projects aur settings dekh sakta hai.')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                
                // Role Column: is_super_admin
                IconColumn::make('is_super_admin')
                    ->label('Global Admin')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filter for Super Admins
                TernaryFilter::make('is_super_admin') // Use of TernaryFilter
                    ->label('Global Admin')
                    ->default(null),
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
}
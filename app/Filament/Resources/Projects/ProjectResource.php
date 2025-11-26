<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema; // FIX: Replaced Form with Schema
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth; // Auth facade use karna

class ProjectResource extends Resource
{
    // Filament V4 uses static properties for configuration
    protected static ?string $model = Project::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-office-2';
    protected static string | \UnitEnum | null $navigationGroup = 'Project Management';
    protected static ?int $navigationSort = 1;

    // Sets the title attribute for global search results
    protected static ?string $recordTitleAttribute = 'name';

    // FIX: Implementing Global Scope to filter resources based on current user
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        // Assumption: The first user (ID 1) created via make:filament-user is the "App Owner" 
        // who needs to see all projects for management and billing.
        
        // Agar user login hai aur woh primary Filament Admin nahi hai (ID 1/2)
        // to sirf uske apne projects dikhaen (jahan woh Super Admin hai)
        if ($user && $user->id !== 1) { 
            // Note: Agar primary admin ka ID 1 ke bajaye kuch aur hai, to isay badlen.
            $query->where('super_admin_id', $user->id);
        }

        // Agar user ID 1 hai (Primary Super Admin), to woh 'parent::getEloquentQuery()' (sabhi projects) dekhega.

        return $query;
    }


    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([ // FIX: Using components() for Schema definition
                // --- Project Identity ---
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                
                TextInput::make('subdomain')
                    ->label('Subdomain Slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                // --- Owner Link ---
                Select::make('super_admin_id')
                    ->relationship('superAdmin', 'email')
                    ->searchable()
                    ->preload()
                    ->required(),

                // --- Status & Monetization ---
                Toggle::make('is_active')
                    ->label('Is Active (Paid Membership)')
                    ->helperText('Enable this when payment is confirmed (Manual or Automatic).')
                    ->default(false),
                
                DatePicker::make('trial_ends_at')
                    ->label('Trial Expiry Date')
                    ->default(Carbon::now()->addDays(30)),

                // --- Configuration ---
                Toggle::make('pays_bonus')
                    ->label('Bonus System Enabled?')
                    ->default(false)
                    ->helperText('Indicates if the Super Admin agreed to pay innovation proposer bonus.'),

                TextInput::make('slogan')
                    ->label('Project Slogan/Motto')
                    ->nullable()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Project $record): string => $record->subdomain . '.cip-tools.de')
                    ->limit(20),

                TextColumn::make('superAdmin.email')
                    ->label('Super Admin')
                    ->searchable()
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('trial_ends_at')
                    ->label('Trial Ends')
                    ->date()
                    ->sortable()
                    ->color(fn (string $state, Project $record): string => 
                        $record->is_active ? 'success' : 
                        ($record->trial_ends_at->diffInDays(Carbon::now()) <= 5 ? 'danger' : 'warning')
                    ),

                IconColumn::make('pays_bonus')
                    ->label('Bonus')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('is_active')
                    ->options([
                        true => 'Active (Paid)',
                        false => 'Inactive / Trial',
                    ])
                    ->label('Subscription Status'),

                Filter::make('trial_expiring')
                    ->query(fn (Builder $query): Builder => $query->where('trial_ends_at', '<=', Carbon::now()->addDays(10)))
                    ->label('Expiring in 10 Days'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Relation managers can be added here
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
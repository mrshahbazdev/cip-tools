<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationLabel = 'Projects';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Project Identity
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Project Name'),
                
                Forms\Components\TextInput::make('subdomain')
                    ->label('Subdomain')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->helperText('This will create: subdomain.cip-tools.de'),

                // Owner Link
                Forms\Components\Select::make('super_admin_id')
                    ->relationship('superAdmin', 'email')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Super Admin'),

                // Status & Monetization
                Forms\Components\Toggle::make('is_active')
                    ->label('Active Subscription')
                    ->helperText('Enable when payment is confirmed')
                    ->default(false),
                
                Forms\Components\DatePicker::make('trial_ends_at')
                    ->label('Trial Ends At')
                    ->default(Carbon::now()->addDays(30)),

                // Configuration
                Forms\Components\Toggle::make('pays_bonus')
                    ->label('Bonus System Enabled')
                    ->default(false)
                    ->helperText('Pay bonuses for implemented innovations'),

                Forms\Components\TextInput::make('slogan')
                    ->label('Slogan')
                    ->nullable()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Project $record): string => $record->subdomain . '.cip-tools.de')
                    ->limit(20),

                Tables\Columns\TextColumn::make('superAdmin.email')
                    ->label('Super Admin')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->sortable()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-mark'),

                Tables\Columns\TextColumn::make('trial_ends_at')
                    ->label('Trial Ends')
                    ->date()
                    ->sortable()
                    ->color(fn (Project $record): string => 
                        $record->is_active ? 'success' : 
                        ($record->trial_ends_at?->diffInDays(Carbon::now()) <= 5 ? 'danger' : 'warning')
                    ),

                Tables\Columns\IconColumn::make('pays_bonus')
                    ->label('Bonus')
                    ->boolean()
                    ->trueIcon('heroicon-o-currency-dollar')
                    ->falseIcon('heroicon-o-x-mark'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_active')
                    ->options([
                        true => 'Active (Paid)',
                        false => 'Inactive / Trial',
                    ])
                    ->label('Subscription Status'),

                Tables\Filters\Filter::make('trial_expiring')
                    ->query(fn (Builder $query): Builder => $query->where('trial_ends_at', '<=', Carbon::now()->addDays(10)))
                    ->label('Expiring in 10 Days'),
                    
                Tables\Filters\Filter::make('bonus_enabled')
                    ->query(fn (Builder $query): Builder => $query->where('pays_bonus', true))
                    ->label('Bonus System Enabled'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }
}
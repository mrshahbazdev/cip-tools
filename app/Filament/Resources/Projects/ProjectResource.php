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

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Project Name'),
                
                Forms\Components\TextInput::make('subdomain')
                    ->label('Subdomain')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                Forms\Components\Select::make('super_admin_id')
                    ->relationship('superAdmin', 'email')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Super Admin'),

                Forms\Components\Toggle::make('is_active')
                    ->label('Active Subscription')
                    ->default(false),
                
                Forms\Components\DatePicker::make('trial_ends_at')
                    ->label('Trial Ends At')
                    ->default(Carbon::now()->addDays(30)),

                Forms\Components\Toggle::make('pays_bonus')
                    ->label('Bonus System Enabled')
                    ->default(false),

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
                    ->sortable(),

                Tables\Columns\TextColumn::make('subdomain')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('superAdmin.email')
                    ->label('Super Admin')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('trial_ends_at')
                    ->label('Trial Ends')
                    ->date()
                    ->sortable(),

                Tables\Columns\IconColumn::make('pays_bonus')
                    ->label('Bonus')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_active')
                    ->options([
                        true => 'Active',
                        false => 'Inactive',
                    ])
                    ->label('Status'),
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
        return [];
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
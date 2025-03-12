<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\App\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $slug = 'customers';
    protected static ?string $navigationLabel = "Customers";
    protected static ?string $modelLabel = 'Customers';
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $activeNavigationIcon = 'heroicon-o-users';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('roles', function (Builder $query) {
            $query->where('name', 'customer');
        });
    }

    public static function getNavigationBadge(): ?string
    {
        return parent::getEloquentQuery()->whereHas('roles', function (Builder $query) {
            $query->where('name', 'customer');
        })->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make([
                    Section::make('Personal Details')
                        ->schema([
                            Forms\Components\FileUpload::make('photo_path')
                                ->label('Photo')
                                ->image()
                                ->directory('images/users/photo'),
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->maxLength(255)
                                ->columnSpanFull(),
                            Forms\Components\Textarea::make('temporary_address')
                                ->columnSpanFull()
                                ->autosize()
                                ->columnSpan(1),
                            Forms\Components\Textarea::make('permanent_address')
                                ->columnSpanFull()
                                ->autosize()
                                ->columnSpan(1),
                            Forms\Components\TextInput::make('personal_contact_number')
                                ->unique(ignoreRecord: true)
                                ->required()
                                ->numeric()
                                ->columnSpanFull(),
                        ])
                        ->columnSpan(1)
                        ->columns(2),
                    Section::make('Account Information')
                        ->schema([
                            Forms\Components\TextInput::make('email')
                                ->email()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('username')
                                ->maxLength(255),
                            Forms\Components\Select::make('roles')
                                ->relationship('roles', 'name')
                                ->multiple()
                                ->preload()
                                ->searchable(),
                        ])
                        ->columnSpan(1),
                ])->columnSpan(1),
                Group::make([
                    Section::make('Contact Information')
                        ->schema([
                            Forms\Components\TextInput::make('business_contact_number')
                                ->numeric(),
                            Forms\Components\TextInput::make('alt_contact_number')
                                ->numeric(),
                            Forms\Components\TextInput::make('google_map_location')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('facebook_link')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('insta_link')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('tiktok_link')
                                ->maxLength(255),
                        ])
                        ->columnSpan(1),
                    Forms\Components\Textarea::make('note')
                        ->label('Notes')
                        ->columnSpanFull()
                        ->autosize()
                        ->rows(8),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\ImageColumn::make('photo_path'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('username')
                    ->searchable(),
                Tables\Columns\TextColumn::make('personal_contact_number')
                    ->sortable(),
                Tables\Columns\TextColumn::make('business_contact_number')
                    ->sortable(),
                Tables\Columns\TextColumn::make('alt_contact_number')
                    ->sortable(),
                Tables\Columns\TextColumn::make('google_map_location')
                    ->searchable(),
                Tables\Columns\TextColumn::make('facebook_link')
                    ->searchable(),
                Tables\Columns\TextColumn::make('insta_link')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tiktok_link')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            // 'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}

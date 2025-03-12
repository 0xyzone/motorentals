<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Vehicle;
use Filament\Forms\Form;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\VehicleResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Filament\Resources\VehicleResource\RelationManagers;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static ?string $navigationGroup = 'Vehicle Management';
    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Owner')
                    ->relationship(
                        name: 'user',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn(Builder $query) => $query->whereDoesntHave('roles', function ($subQuery) {
                            $subQuery->where('name', 'customer');
                        })
                    )
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('vehicle_type_id')
                    ->relationship('vehicle_type', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('name')
                    ->label('Vehicle Name')
                    ->required()
                    ->maxLength(255)
                    ->autocomplete(false),
                Forms\Components\Select::make('vehicle_brand_id')
                    ->relationship('vehicleBrand', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('color')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('make_year')
                    ->native(false)
                    ->required(),
                Forms\Components\TextInput::make('lot_no')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('engine_no')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('chassis_no')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('status')
                    ->default(false)
                    ->inline(false)
                    ->label('Active?'),
                Forms\Components\TextInput::make('purchased_price')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('purchased_date')
                    ->required()
                    ->native(false),
                SpatieMediaLibraryFileUpload::make('photos')
                    ->collection('vehicle/images')
                    ->image()
                    ->multiple()
                    ->columnSpanFull()
                    ->imagePreviewHeight(150)    // Set thumbnail height
                    ->panelLayout('grid')       // grid | carousel | compact
                    ->uploadButtonPosition('center')
                    ->uploadProgressIndicatorPosition('left')
                    ->reorderable()
                    ->appendFiles()
                    ->openable()
                    ->responsiveImages()        // Enable responsive images
                    ->imageResizeTargetWidth(1024)
                    ->imageResizeTargetHeight(682)
                    ->hint('Max 5MB per image | Supported formats: JPG, PNG')
                    ->hintIcon('heroicon-o-information-circle')
                    ->placeholder('Click or drag images to upload')
                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                    // Visual enhancements:
                    ->placeholder('Drag & drop vehicle photos here or click to upload')
                    ->loadingIndicatorPosition('right')
                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                    ->validationMessages([
                        'acceptedFileTypes' => 'Only JPG and PNG images are allowed',
                    ]),
                Forms\Components\Textarea::make('note')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vehicle_type.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('brand')
                    ->searchable(),
                Tables\Columns\TextColumn::make('color')
                    ->searchable(),
                Tables\Columns\TextColumn::make('make_year')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lot_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('engine_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('chassis_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('purchased_price')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('purchased_date')
                    ->numeric()
                    ->sortable(),
                SpatieMediaLibraryImageColumn::make('photos')
                    ->simpleLightbox()
                    ->circular()
                    ->stacked()
                    ->limitedRemainingText()
                    ->collection('vehicle/images'),
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
            'index' => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicle::route('/create'),
            'edit' => Pages\EditVehicle::route('/{record}/edit'),
        ];
    }
}

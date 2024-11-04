<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FoodResource\Pages;
use App\Filament\Resources\FoodResource\RelationManagers;
use App\Models\Allergen;
use App\Models\Food;
use App\Models\Foods;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class FoodResource extends Resource
{
    protected static ?string $model = Foods::class;
    protected static ?string $navigationLabel = 'Ételek';
    protected static ?string $title = 'Ételek';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form // Updated method type
    {
        $user = Auth::user();
        $restaurantId = $user && $user->restaurant ? $user->restaurant->id : null;

        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Food Name'),

                Forms\Components\FileUpload::make('image')
                    ->label('Image')
                    ->image(),

                Forms\Components\Textarea::make('description')
                    ->label('Description'),

                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->label('Price'),

                Forms\Components\Checkbox::make('chef_recommendation')
                    ->label('Chef Recommendation'),


                Forms\Components\Select::make('allergens')
                    ->multiple()
                    ->relationship('allergens', 'name')
                    ->label('Allergens')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->label('Allergen Name')
                            ->maxLength(255),
                    ])
                    ->createOptionAction(function (Allergen $allergen) {
                        return $allergen->name;
                    })
                    ->reactive(),

                Forms\Components\Hidden::make('restaurant_id')
                    ->default($restaurantId),  // Set default to user's restaurant ID



            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Megnevezés'),
                Tables\Columns\TextColumn::make('price')->label('Ár'),
                Tables\Columns\TextColumn::make('description')->label('Leírás')->limit(50),
                Tables\Columns\TextColumn::make('chef_recommendation')->label('Séf ajánlata'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Szerkesztés'),
                Tables\Actions\DeleteAction::make()->label('Törlés'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListFood::route('/'),
            'create' => Pages\CreateFood::route('/create'),
            'edit' => Pages\EditFood::route('/{record}/edit'),
        ];
    }

}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LayerResource\Pages;
use App\Filament\Resources\LayerResource\RelationManagers;
use App\Models\Layer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LayerResource extends Resource
{
    protected static ?string $model = Layer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('geometry')
                    ->label('Arquivo GeoJSON')
                    ->acceptedFileTypes(['application/json'])
                    ->required()
                    ->disk('local') // Salva no disco padrão ou personalizado
                    ->directory('geojson'), // Diretório dentro do disco
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Nome')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Criado em')->date(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListLayers::route('/'),
            'create' => Pages\CreateLayer::route('/novo'),
            'edit' => Pages\EditLayer::route('/{record}/editar'),
        ];
    }
}

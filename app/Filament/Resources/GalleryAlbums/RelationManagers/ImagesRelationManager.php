<?php

namespace App\Filament\Resources\GalleryAlbums\RelationManagers;

use App\Filament\Resources\GalleryImages\GalleryImageResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    protected static ?string $title = 'Albüm Görselleri';

    protected static ?string $modelLabel = 'görsel';

    public function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components(GalleryImageResource::formComponents(withAlbum: false));
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns(GalleryImageResource::tableColumns(withAlbum: false))
            ->headerActions([
                CreateAction::make()->label('Görsel ekle'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

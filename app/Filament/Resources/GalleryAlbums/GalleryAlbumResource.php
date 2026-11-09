<?php

namespace App\Filament\Resources\GalleryAlbums;

use App\Filament\Resources\GalleryAlbums\Pages\CreateGalleryAlbum;
use App\Filament\Resources\GalleryAlbums\Pages\EditGalleryAlbum;
use App\Filament\Resources\GalleryAlbums\Pages\ListGalleryAlbums;
use App\Filament\Resources\GalleryAlbums\RelationManagers\ImagesRelationManager;
use App\Filament\Support\Fields;
use App\Models\GalleryAlbum;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use UnitEnum;

class GalleryAlbumResource extends Resource
{
    protected static ?string $model = GalleryAlbum::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleGroup;

    protected static string|UnitEnum|null $navigationGroup = 'Galeri';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'albüm';

    protected static ?string $pluralModelLabel = 'Galeri Albümleri';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Albüm')->columnSpan(2)->columns(2)->schema([
                    Fields::title(),
                    Fields::slug('galeri'),
                    Textarea::make('description')->label('Açıklama')->rows(3)->maxLength(500)->columnSpanFull(),
                ]),
                Section::make('Yayın')->columnSpan(1)->schema([
                    Toggle::make('is_active')->label('Aktif')->default(true),
                    TextInput::make('sort_order')->label('Sıralama')->numeric()->default(0)->minValue(0),
                    Fields::image('cover_image', 'uploads/galeri')
                        ->helperText('İsteğe bağlı. Boşsa albümün ilk görseli kullanılır.'),
                ]),
                Fields::seoSection()->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('title')->label('Albüm')->searchable()->sortable()
                    ->description(fn (GalleryAlbum $record) => '/galeri/'.$record->slug.'/'),
                TextColumn::make('images_count')->label('Görsel')->counts('images')->badge(),
                ToggleColumn::make('is_active')->label('Aktif'),
                TextColumn::make('sort_order')->label('Sıra')->sortable(),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('Görüntüle')
                    ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                    ->url(fn (GalleryAlbum $record) => $record->url(), shouldOpenInNewTab: true),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ImagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGalleryAlbums::route('/'),
            'create' => CreateGalleryAlbum::route('/create'),
            'edit' => EditGalleryAlbum::route('/{record}/edit'),
        ];
    }
}

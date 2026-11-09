<?php

namespace App\Filament\Resources\GalleryImages;

use App\Filament\Resources\GalleryImages\Pages\CreateGalleryImage;
use App\Filament\Resources\GalleryImages\Pages\EditGalleryImage;
use App\Filament\Resources\GalleryImages\Pages\ListGalleryImages;
use App\Filament\Support\Fields;
use App\Models\GalleryImage;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class GalleryImageResource extends Resource
{
    protected static ?string $model = GalleryImage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static string|UnitEnum|null $navigationGroup = 'Galeri';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'görsel';

    protected static ?string $pluralModelLabel = 'Galeri Görselleri';

    protected static ?string $recordTitleAttribute = 'title';

    /** Albüm içinden (relation manager) kullanıldığında albüm alanı gizlenir. */
    public static function formComponents(bool $withAlbum = true): array
    {
        return array_values(array_filter([
            $withAlbum
                ? Select::make('gallery_album_id')
                    ->label('Albüm')
                    ->relationship('album', 'title')
                    ->searchable()
                    ->preload()
                    ->required()
                : null,
            Fields::image('image', 'uploads/galeri', 'Görsel')->required()->columnSpanFull(),
            TextInput::make('title')->label('Başlık')->required()->maxLength(255),
            TextInput::make('alt_text')
                ->label('ALT metni')
                ->maxLength(255)
                ->helperText('Görseli tarif eden kısa metin (erişilebilirlik ve SEO). Boşsa başlık kullanılır.'),
            Textarea::make('description')->label('Açıklama')->rows(2)->maxLength(500)->columnSpanFull(),
            TextInput::make('sort_order')->label('Sıralama')->numeric()->default(0)->minValue(0),
            Toggle::make('is_active')->label('Aktif')->default(true)->inline(false),
        ]));
    }

    public static function tableColumns(bool $withAlbum = true): array
    {
        return array_values(array_filter([
            ImageColumn::make('image')->label('Görsel')->disk('public')->imageHeight(60),
            TextColumn::make('title')->label('Başlık')->searchable()->sortable()
                ->description(fn (GalleryImage $record) => $record->alt_text),
            $withAlbum ? TextColumn::make('album.title')->label('Albüm')->sortable() : null,
            ToggleColumn::make('is_active')->label('Aktif'),
            TextColumn::make('sort_order')->label('Sıra')->sortable(),
        ]));
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components(static::formComponents());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns(static::tableColumns())
            ->filters([
                SelectFilter::make('gallery_album_id')->label('Albüm')->relationship('album', 'title'),
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

    public static function getPages(): array
    {
        return [
            'index' => ListGalleryImages::route('/'),
            'create' => CreateGalleryImage::route('/create'),
            'edit' => EditGalleryImage::route('/{record}/edit'),
        ];
    }
}

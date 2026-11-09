<?php

namespace App\Filament\Resources\Services;

use App\Filament\Resources\Services\Pages\CreateService;
use App\Filament\Resources\Services\Pages\EditService;
use App\Filament\Resources\Services\Pages\ListServices;
use App\Filament\Support\Fields;
use App\Models\Service;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use UnitEnum;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    protected static string|UnitEnum|null $navigationGroup = 'İçerik';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'hizmet';

    protected static ?string $pluralModelLabel = 'Hizmetler';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Grid::make(1)->columnSpan(2)->schema([
                    Section::make('Hizmet')->schema([
                        Fields::title(),
                        TextInput::make('menu_title')
                            ->label('Menü başlığı')
                            ->maxLength(255)
                            ->helperText('Boş bırakılırsa başlık kullanılır.'),
                        Fields::slug(rootLevel: true),
                        Textarea::make('excerpt')
                            ->label('Kısa giriş yazısı')
                            ->rows(3)
                            ->maxLength(500)
                            ->helperText('Hizmet kartlarında ve sayfa girişinde gösterilir.'),
                        Fields::richContent(directory: 'uploads/hizmetler'),
                    ]),
                    Fields::seoSection(withKeywords: true),
                ]),
                Grid::make(1)->columnSpan(1)->schema([
                    Section::make('Yayın')->schema([
                        Toggle::make('is_active')->label('Aktif')->default(true),
                        TextInput::make('sort_order')->label('Sıralama')->numeric()->default(0)->minValue(0),
                    ]),
                    Section::make('Görseller')->schema([
                        Fields::image('cover_image', 'uploads/hizmetler'),
                        TextInput::make('icon')
                            ->label('İkon')
                            ->maxLength(255)
                            ->helperText('Bootstrap Icons sınıfı (ör. bi-car-front) veya public klasöründeki görsel yolu (ör. images/theme/icon-ticari-arac.svg).'),
                        FileUpload::make('gallery_images')
                            ->label('Galeri görselleri (isteğe bağlı)')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->maxFiles(12)
                            ->disk('public')
                            ->visibility('public')
                            ->directory('uploads/hizmetler')
                            ->acceptedFileTypes(Fields::IMAGE_TYPES)
                            ->maxSize(Fields::IMAGE_MAX_KB),
                    ]),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                ImageColumn::make('cover_image')->label('Görsel')->disk('public')->square(),
                TextColumn::make('title')->label('Başlık')->searchable()->sortable()
                    ->description(fn (Service $record) => '/'.$record->slug.'/'),
                ToggleColumn::make('is_active')->label('Aktif'),
                TextColumn::make('sort_order')->label('Sıra')->sortable(),
                TextColumn::make('updated_at')->label('Güncellendi')->dateTime('d.m.Y H:i')->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')->label('Durum'),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('Görüntüle')
                    ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                    ->url(fn (Service $record) => $record->url(), shouldOpenInNewTab: true),
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
            'index' => ListServices::route('/'),
            'create' => CreateService::route('/create'),
            'edit' => EditService::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources\BlogPosts;

use App\Filament\Resources\BlogPosts\Pages\CreateBlogPost;
use App\Filament\Resources\BlogPosts\Pages\EditBlogPost;
use App\Filament\Resources\BlogPosts\Pages\ListBlogPosts;
use App\Filament\Support\Fields;
use App\Models\BlogPost;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class BlogPostResource extends Resource
{
    protected static ?string $model = BlogPost::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static string|UnitEnum|null $navigationGroup = 'Blog';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'blog yazısı';

    protected static ?string $pluralModelLabel = 'Blog Yazıları';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Grid::make(1)->columnSpan(2)->schema([
                    Section::make('Yazı')->schema([
                        Fields::title(),
                        Fields::slug('blog'),
                        Textarea::make('excerpt')->label('Özet')->rows(3)->maxLength(500)
                            ->helperText('Listeleme sayfasında ve paylaşımlarda kullanılır.'),
                        Fields::richContent(directory: 'uploads/blog'),
                    ]),
                    Fields::seoSection(),
                ]),
                Section::make('Yayın')->columnSpan(1)->schema([
                    ToggleButtons::make('status')
                        ->label('Yayın durumu')
                        ->options(BlogPost::STATUSES)
                        ->colors([BlogPost::STATUS_DRAFT => 'gray', BlogPost::STATUS_PUBLISHED => 'success'])
                        ->default(BlogPost::STATUS_DRAFT)
                        ->inline()
                        ->live()
                        ->required(),
                    DateTimePicker::make('published_at')
                        ->label('Yayın tarihi')
                        ->seconds(false)
                        ->native(false)
                        ->displayFormat('d.m.Y H:i')
                        ->required(fn (Get $get) => $get('status') === BlogPost::STATUS_PUBLISHED)
                        ->helperText('İleri bir tarih seçilirse yazı o tarihte görünür olur.'),
                    Select::make('blog_category_id')
                        ->label('Kategori')
                        ->relationship('category', 'name')
                        ->searchable()
                        ->preload(),
                    Fields::image('cover_image', 'uploads/blog'),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                ImageColumn::make('cover_image')->label('Görsel')->disk('public')->imageHeight(44),
                TextColumn::make('title')->label('Başlık')->searchable()->sortable()->wrap(),
                TextColumn::make('category.name')->label('Kategori')->placeholder('-'),
                TextColumn::make('status')->label('Durum')->badge()
                    ->formatStateUsing(fn (string $state) => BlogPost::STATUSES[$state] ?? $state)
                    ->color(fn (string $state) => $state === BlogPost::STATUS_PUBLISHED ? 'success' : 'gray'),
                TextColumn::make('published_at')->label('Yayın tarihi')->dateTime('d.m.Y H:i')->sortable()->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('status')->label('Durum')->options(BlogPost::STATUSES),
                SelectFilter::make('blog_category_id')->label('Kategori')->relationship('category', 'name'),
            ])
            ->recordActions([
                Action::make('view')
                    ->label(fn (BlogPost $record) => $record->isPublished() ? 'Görüntüle' : 'Önizle')
                    ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                    ->url(fn (BlogPost $record) => $record->url(), shouldOpenInNewTab: true),
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
            'index' => ListBlogPosts::route('/'),
            'create' => CreateBlogPost::route('/create'),
            'edit' => EditBlogPost::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources\Faqs;

use App\Filament\Resources\Faqs\Pages\CreateFaq;
use App\Filament\Resources\Faqs\Pages\EditFaq;
use App\Filament\Resources\Faqs\Pages\ListFaqs;
use App\Models\Faq;
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
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use UnitEnum;

/** Sıkça sorulan sorular: /sikca-sorulan-sorular/ sayfasında aktif kayıtlar kategoriye göre listelenir. */
class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQuestionMarkCircle;

    protected static string|UnitEnum|null $navigationGroup = 'İçerik';

    protected static ?int $navigationSort = 6;

    protected static ?string $modelLabel = 'soru';

    protected static ?string $pluralModelLabel = 'Sıkça Sorulan Sorular';

    protected static ?string $recordTitleAttribute = 'question';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->columns(3)
                ->schema([
                    TextInput::make('question')->label('Soru')->required()->maxLength(255)
                        ->unique(ignoreRecord: true)->columnSpanFull(),
                    Textarea::make('answer')->label('Cevap')->required()->rows(5)->maxLength(3000)->columnSpanFull()
                        ->helperText('Yalnızca işletmenin gerçekten sunduğu hizmetler ve doğrulanmış bilgiler yazılmalıdır.'),
                    Select::make('category')->label('Kategori')->options(Faq::CATEGORIES)->required(),
                    TextInput::make('sort_order')->label('Sıralama')->numeric()->default(0),
                    Toggle::make('is_active')->label('Aktif (sitede göster)')->default(true)->inline(false),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('sort_order')->label('#')->sortable(),
                TextColumn::make('question')->label('Soru')->searchable()->wrap(),
                TextColumn::make('category')->label('Kategori')->badge()
                    ->formatStateUsing(fn (?string $state) => Faq::CATEGORIES[$state] ?? 'Genel'),
                ToggleColumn::make('is_active')->label('Aktif'),
            ])
            ->filters([
                SelectFilter::make('category')->label('Kategori')->options(Faq::CATEGORIES),
                TernaryFilter::make('is_active')->label('Aktif'),
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
            'index' => ListFaqs::route('/'),
            'create' => CreateFaq::route('/create'),
            'edit' => EditFaq::route('/{record}/edit'),
        ];
    }
}

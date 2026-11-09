<?php

namespace App\Filament\Resources\Sliders;

use App\Filament\Resources\Sliders\Pages\CreateSlider;
use App\Filament\Resources\Sliders\Pages\EditSlider;
use App\Filament\Resources\Sliders\Pages\ListSliders;
use App\Filament\Support\Fields;
use App\Models\Slider;
use BackedEnum;
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
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use UnitEnum;

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPresentationChartBar;

    protected static string|UnitEnum|null $navigationGroup = 'Ana Sayfa';

    protected static ?string $modelLabel = 'slayt';

    protected static ?string $pluralModelLabel = 'Ana Sayfa Slider';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        $urlHelp = 'Site içi adres (ör. /iletisim/) veya tam adres (https://...).';

        return $schema
            ->columns(3)
            ->components([
                Section::make('İçerik')->columnSpan(2)->schema([
                    TextInput::make('subtitle')->label('Küçük başlık')->maxLength(255),
                    TextInput::make('title')->label('Ana başlık')->required()->maxLength(255)
                        ->helperText('İlk slaytın başlığı sayfanın H1 başlığıdır.'),
                    Textarea::make('description')->label('Açıklama')->rows(3)->maxLength(600),
                    Section::make('Butonlar')->columns(2)->compact()->schema([
                        TextInput::make('primary_button_text')->label('1. buton metni')->maxLength(60),
                        TextInput::make('primary_button_url')->label('1. buton bağlantısı')->maxLength(255)->helperText($urlHelp),
                        TextInput::make('secondary_button_text')->label('2. buton metni')->maxLength(60),
                        TextInput::make('secondary_button_url')->label('2. buton bağlantısı')->maxLength(255)->helperText($urlHelp),
                    ]),
                ]),
                Section::make('Görsel ve Yayın')->columnSpan(1)->schema([
                    Fields::image('image', 'uploads/slider', 'Arka plan görseli')
                        ->helperText('JPG, PNG veya WEBP. En fazla 4 MB. Önerilen: en az 1920×1080.'),
                    TextInput::make('image_alt')->label('Görsel açıklaması (ALT)')->maxLength(255),
                    Toggle::make('is_active')->label('Aktif')->default(true),
                    TextInput::make('sort_order')->label('Sıralama')->numeric()->default(0)->minValue(0),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                ImageColumn::make('image')->label('Görsel')->disk('public')->imageHeight(50),
                TextColumn::make('title')->label('Başlık')->searchable()->description(fn (Slider $record) => $record->subtitle),
                ToggleColumn::make('is_active')->label('Aktif'),
                TextColumn::make('sort_order')->label('Sıra')->sortable(),
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
            'index' => ListSliders::route('/'),
            'create' => CreateSlider::route('/create'),
            'edit' => EditSlider::route('/{record}/edit'),
        ];
    }
}

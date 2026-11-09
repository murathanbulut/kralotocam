<?php

namespace App\Filament\Resources\MenuItems;

use App\Filament\Resources\MenuItems\Pages\CreateMenuItem;
use App\Filament\Resources\MenuItems\Pages\EditMenuItem;
use App\Filament\Resources\MenuItems\Pages\ListMenuItems;
use App\Models\MenuItem;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class MenuItemResource extends Resource
{
    protected static ?string $model = MenuItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBars3;

    protected static string|UnitEnum|null $navigationGroup = 'Ayarlar';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'menü öğesi';

    protected static ?string $pluralModelLabel = 'Menü';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columns(2)->schema([
                TextInput::make('title')->label('Menü adı')->required()->maxLength(100),
                Select::make('parent_id')
                    ->label('Üst menü')
                    ->placeholder('Yok (ana menü öğesi)')
                    ->options(fn (?MenuItem $record) => MenuItem::query()
                        ->whereNull('parent_id')
                        ->when($record, fn (Builder $q) => $q->whereKeyNot($record->getKey()))
                        ->orderBy('sort_order')
                        ->pluck('title', 'id'))
                    ->helperText('Seçilirse bu öğe, üst menünün açılır listesinde gösterilir.'),
                TextInput::make('url')
                    ->label('Bağlantı')
                    ->maxLength(255)
                    ->placeholder('/iletisim/')
                    ->helperText('Site içi adres (ör. /hakkimizda/) ya da tam adres. Yalnızca açılır liste olacaksa boş bırakın.'),
                TextInput::make('sort_order')->label('Sıralama')->numeric()->default(0)->minValue(0),
                Toggle::make('open_in_new_tab')->label('Yeni sekmede aç'),
                Toggle::make('is_active')->label('Aktif')->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with('parent'))
            ->defaultSort(fn (Builder $query) => $query->orderByRaw('COALESCE(parent_id, id), parent_id IS NOT NULL, sort_order'))
            ->columns([
                TextColumn::make('title')->label('Menü adı')->searchable()
                    ->formatStateUsing(fn (MenuItem $record, string $state) => $record->parent_id ? '— '.$state : $state)
                    ->weight(fn (MenuItem $record) => $record->parent_id ? null : 'bold'),
                TextColumn::make('parent.title')->label('Üst menü')->placeholder('-'),
                TextColumn::make('url')->label('Bağlantı')->placeholder('(açılır liste)'),
                ToggleColumn::make('is_active')->label('Aktif'),
                TextColumn::make('sort_order')->label('Sıra'),
            ])
            ->filters([
                SelectFilter::make('parent_id')->label('Üst menü')
                    ->options(fn () => MenuItem::query()->whereNull('parent_id')->pluck('title', 'id')),
            ])
            ->paginated(false)
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
            'index' => ListMenuItems::route('/'),
            'create' => CreateMenuItem::route('/create'),
            'edit' => EditMenuItem::route('/{record}/edit'),
        ];
    }
}

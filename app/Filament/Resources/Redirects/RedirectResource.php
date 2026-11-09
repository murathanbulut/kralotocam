<?php

namespace App\Filament\Resources\Redirects;

use App\Filament\Resources\Redirects\Pages\CreateRedirect;
use App\Filament\Resources\Redirects\Pages\EditRedirect;
use App\Filament\Resources\Redirects\Pages\ListRedirects;
use App\Models\Redirect;
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
use Filament\Tables\Table;
use UnitEnum;

class RedirectResource extends Resource
{
    protected static ?string $model = Redirect::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowUturnRight;

    protected static string|UnitEnum|null $navigationGroup = 'Ayarlar';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'yönlendirme';

    protected static ?string $pluralModelLabel = 'Yönlendirmeler (SEO)';

    protected static ?string $recordTitleAttribute = 'from_path';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->description('Eski adresten gelen ziyaretçiler ve arama motorları yeni adrese yönlendirilir. Kalıcı taşımalar için 301 kullanın.')
                ->columns(2)
                ->schema([
                    TextInput::make('from_path')
                        ->label('Eski adres')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('tr/hizmet/eski-hizmet-adresi')
                        ->helperText('Alan adı olmadan yazın. Baştaki/sondaki "/" önemsizdir.')
                        ->unique(ignoreRecord: true),
                    TextInput::make('to_path')
                        ->label('Yeni adres')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('yeni-hizmet-adresi')
                        ->helperText('Site içi yol veya tam adres (https://...).'),
                    Select::make('status_code')
                        ->label('Tür')
                        ->options([301 => '301 - Kalıcı', 302 => '302 - Geçici'])
                        ->default(301)
                        ->required(),
                    Toggle::make('is_active')->label('Aktif')->default(true)->inline(false),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('from_path')
            ->columns([
                TextColumn::make('from_path')->label('Eski adres')->prefix('/')->searchable()->sortable(),
                TextColumn::make('to_path')->label('Yeni adres')->searchable(),
                TextColumn::make('status_code')->label('Tür')->badge(),
                TextColumn::make('hits')->label('Kullanım')->sortable(),
                ToggleColumn::make('is_active')->label('Aktif'),
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
            'index' => ListRedirects::route('/'),
            'create' => CreateRedirect::route('/create'),
            'edit' => EditRedirect::route('/{record}/edit'),
        ];
    }
}

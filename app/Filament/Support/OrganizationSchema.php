<?php

namespace App\Filament\Support;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

/**
 * Sigorta şirketi ve filo kuruluşu kaynaklarının ortak form/tablo yapısı.
 * Ön yüzde yalnızca "Doğrulandı" + "Aktif" kayıtlar listelenir; logo ayrıca kullanım hakkı onayı ister.
 */
class OrganizationSchema
{
    public static function form(Schema $schema, string $nameLabel, string $verifiedLabel, string $logoDir): Schema
    {
        return $schema->components([
            Section::make()
                ->columns(2)
                ->schema([
                    TextInput::make('name')->label($nameLabel)->required()->maxLength(150)
                        ->unique(ignoreRecord: true)->columnSpanFull(),
                    Textarea::make('description')->label('Açıklama')->rows(3)->maxLength(500)->columnSpanFull()
                        ->helperText('İsteğe bağlı. Ön yüzde kurum adının altında gösterilir.'),
                    Fields::image('logo', $logoDir, 'Logo')
                        ->helperText('PNG/WEBP, en fazla 4 MB. Kullanım hakkı onaylanmadan ön yüzde gösterilmez.'),
                    Toggle::make('logo_rights_confirmed')->label('Logo kullanım hakkı onaylandı')->inline(false),
                ]),
            Section::make('Yayın ve doğrulama')
                ->description('Doğrulanmamış kayıtlar kamuya açık listede gösterilmez.')
                ->columns(3)
                ->schema([
                    Toggle::make('is_verified')->label($verifiedLabel)->inline(false),
                    Toggle::make('is_active')->label('Aktif')->default(true)->inline(false),
                    TextInput::make('sort_order')->label('Sıralama')->numeric()->default(0),
                    Textarea::make('internal_note')->label('İç not (yalnızca panelde)')->rows(2)->maxLength(500)->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table, string $nameLabel, string $verifiedLabel): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('sort_order')->label('#')->sortable(),
                TextColumn::make('name')->label($nameLabel)->searchable()->sortable(),
                IconColumn::make('is_verified')->label($verifiedLabel)->boolean()->sortable(),
                TextColumn::make('verified_at')->label('Doğrulama tarihi')->date('d.m.Y')->placeholder('—')->toggleable(),
                IconColumn::make('logo_rights_confirmed')->label('Logo hakkı')->boolean()->toggleable(),
                ToggleColumn::make('is_active')->label('Aktif'),
                TextColumn::make('internal_note')->label('İç not')->limit(50)->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_verified')->label('Doğrulama'),
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
}

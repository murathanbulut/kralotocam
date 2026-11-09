<?php

namespace App\Filament\Resources\ContactMessages;

use App\Filament\Resources\ContactMessages\Pages\ListContactMessages;
use App\Filament\Resources\ContactMessages\Pages\ViewContactMessage;
use App\Models\ContactMessage;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use UnitEnum;

/**
 * Gelen iletişim mesajları: yalnızca görüntülenir, okundu olarak işaretlenir ve silinebilir.
 * Ziyaretçi verisi panelden düzenlenemez.
 */
class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInboxArrowDown;

    protected static string|UnitEnum|null $navigationGroup = 'İletişim';

    protected static ?string $modelLabel = 'mesaj';

    protected static ?string $pluralModelLabel = 'Gelen Mesajlar';

    protected static ?string $recordTitleAttribute = 'name';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $unread = ContactMessage::query()->where('is_read', false)->count();

        return $unread > 0 ? (string) $unread : null;
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Okunmamış mesaj';
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->columns(3)->components([
            Section::make('Mesaj')->columnSpan(2)->schema([
                TextEntry::make('service_title')->label('Hizmet')->placeholder('Belirtilmedi'),
                TextEntry::make('message')->label('Mesaj')->prose()->extraAttributes(['style' => 'white-space: pre-line']),
            ]),
            Section::make('Gönderen')->columnSpan(1)->schema([
                TextEntry::make('name')->label('Ad soyad'),
                TextEntry::make('phone')->label('Telefon')
                    ->url(fn (ContactMessage $record) => phone_href($record->phone))
                    ->copyable(),
                TextEntry::make('email')->label('E-posta')->placeholder('-')
                    ->url(fn (ContactMessage $record) => $record->email ? 'mailto:'.$record->email : null)
                    ->copyable(),
                TextEntry::make('created_at')->label('Gönderim')->dateTime('d.m.Y H:i'),
                IconEntry::make('kvkk_consent')->label('KVKK onayı')->boolean(),
                TextEntry::make('mailed_at')->label('E-posta bildirimi')
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('Gönderilmedi (SMTP yapılandırılmamış veya hata)'),
                TextEntry::make('ip_address')->label('IP adresi')->placeholder('-'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->recordClasses(fn (ContactMessage $record) => $record->is_read ? null : 'font-semibold')
            ->columns([
                IconColumn::make('is_read')->label('Okundu')->boolean(),
                TextColumn::make('name')->label('Ad soyad')->searchable(),
                TextColumn::make('phone')->label('Telefon')->searchable(),
                TextColumn::make('service_title')->label('Hizmet')->placeholder('-')->toggleable(),
                TextColumn::make('message')->label('Mesaj')->limit(60)->wrap(),
                TextColumn::make('created_at')->label('Tarih')->dateTime('d.m.Y H:i')->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_read')->label('Okunma durumu')
                    ->trueLabel('Okunmuş')->falseLabel('Okunmamış'),
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('toggleRead')
                    ->label(fn (ContactMessage $record) => $record->is_read ? 'Okunmadı yap' : 'Okundu yap')
                    ->icon(Heroicon::OutlinedCheck)
                    ->action(fn (ContactMessage $record) => $record->update(['is_read' => ! $record->is_read])),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('markRead')
                        ->label('Okundu olarak işaretle')
                        ->icon(Heroicon::OutlinedCheck)
                        ->action(fn (Collection $records) => $records->each->update(['is_read' => true]))
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListContactMessages::route('/'),
            'view' => ViewContactMessage::route('/{record}'),
        ];
    }
}

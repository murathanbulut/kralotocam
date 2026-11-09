<?php

namespace App\Filament\Support;

use App\Models\Page;
use App\Models\Service;
use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Yönetim paneli formlarında tekrar eden alanlar.
 */
class Fields
{
    /** Yüklenebilir görsel türleri. SVG, içine betik gömülebildiği için kabul edilmez. */
    public const IMAGE_TYPES = ['image/jpeg', 'image/png', 'image/webp'];

    /** Görsel boyut sınırı (KB). */
    public const IMAGE_MAX_KB = 4096;

    /** Kök seviyede (/{slug}/) kullanılamayacak adresler. */
    public const RESERVED_SLUGS = [
        'admin', 'livewire', 'filament', 'storage', 'build', 'images', 'css', 'js', 'fonts',
        'hizmetler', 'galeri', 'blog', 'iletisim', 'sitemap', 'robots', 'tr', 'en', 'up',
    ];

    public static function image(string $name = 'cover_image', string $directory = 'uploads', string $label = 'Kapak görseli'): FileUpload
    {
        return FileUpload::make($name)
            ->label($label)
            ->image()
            ->disk('public')
            ->visibility('public')
            ->directory($directory)
            ->acceptedFileTypes(self::IMAGE_TYPES)
            ->maxSize(self::IMAGE_MAX_KB)
            ->helperText('JPG, PNG veya WEBP. En fazla 4 MB. Önerilen oran 16:9 (ör. 1600×900).');
    }

    public static function richContent(string $name = 'content', string $label = 'Detaylı içerik', string $directory = 'uploads/icerik'): RichEditor
    {
        return RichEditor::make($name)
            ->label($label)
            ->fileAttachmentsDisk('public')
            ->fileAttachmentsDirectory($directory)
            ->fileAttachmentsVisibility('public')
            ->fileAttachmentsAcceptedFileTypes(self::IMAGE_TYPES)
            ->fileAttachmentsMaxSize(self::IMAGE_MAX_KB)
            ->toolbarButtons([
                ['bold', 'italic', 'underline', 'link'],
                ['h2', 'h3'],
                ['blockquote', 'bulletList', 'orderedList'],
                ['attachFiles', 'table'],
                ['undo', 'redo'],
            ])
            ->columnSpanFull();
    }

    /** Başlık alanı: oluşturma sırasında URL (slug) alanını otomatik doldurur. */
    public static function title(string $name = 'title', string $label = 'Başlık'): TextInput
    {
        return TextInput::make($name)
            ->label($label)
            ->required()
            ->maxLength(255)
            ->live(onBlur: true)
            ->afterStateUpdated(function (Set $set, ?string $state, string $operation) {
                if ($operation === 'create') {
                    $set('slug', Str::slug((string) $state));
                }
            });
    }

    public static function slug(string $prefix = '', bool $rootLevel = false): TextInput
    {
        $field = TextInput::make('slug')
            ->label('URL (slug)')
            ->required()
            ->maxLength(190)
            ->regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/')
            ->validationMessages(['regex' => 'Yalnızca küçük harf, rakam ve tire kullanın (Türkçe karakter kullanmayın).'])
            ->unique(ignoreRecord: true)
            ->prefix(rtrim(site_url($prefix), '/').'/')
            ->suffix('/')
            ->helperText('Canlıdaki adresler değiştirilirken eski adres için "Yönlendirmeler" bölümüne 301 kaydı ekleyin.');

        if ($rootLevel) {
            // Hizmetler ve kurumsal sayfalar aynı kök adres alanını paylaşır.
            $field->rules([
                fn (?Model $record): Closure => function (string $attribute, $value, Closure $fail) use ($record) {
                    if (in_array($value, self::RESERVED_SLUGS, true)) {
                        $fail('Bu adres sistem tarafından kullanılıyor.');

                        return;
                    }

                    $serviceTaken = Service::query()->where('slug', $value)
                        ->when($record instanceof Service, fn ($q) => $q->whereKeyNot($record->getKey()))->exists();
                    $pageTaken = Page::query()->where('slug', $value)
                        ->when($record instanceof Page, fn ($q) => $q->whereKeyNot($record->getKey()))->exists();

                    if ($serviceTaken || $pageTaken) {
                        $fail('Bu adres başka bir hizmet veya sayfa tarafından kullanılıyor.');
                    }
                },
            ]);
        }

        return $field;
    }

    public static function seoSection(bool $withKeywords = false): Section
    {
        return Section::make('SEO')
            ->description('Boş bırakılırsa başlık ve kısa açıklama kullanılır.')
            ->collapsible()
            ->schema(array_filter([
                TextInput::make('seo_title')
                    ->label('SEO başlığı (title)')
                    ->maxLength(70)
                    ->helperText('Önerilen: 50-60 karakter. Marka adı otomatik eklenir.'),
                Textarea::make('seo_description')
                    ->label('SEO açıklaması (meta description)')
                    ->rows(3)
                    ->maxLength(320)
                    ->helperText('Önerilen: 140-160 karakter.'),
                $withKeywords
                    ? TextInput::make('seo_keywords')->label('Anahtar kelimeler')->maxLength(255)->helperText('Virgülle ayırın.')
                    : null,
            ]));
    }
}

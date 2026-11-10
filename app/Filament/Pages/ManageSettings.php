<?php

namespace App\Filament\Pages;

use App\Filament\Support\Fields;
use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * Genel site ayarları: logo/favicon, iletişim bilgileri, sosyal medya, SEO ve ana sayfa metinleri.
 * Değerler "settings" tablosunda anahtar/değer olarak saklanır (App\Models\Setting).
 */
class ManageSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'Ayarlar';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Site Ayarları';

    protected static ?string $title = 'Site Ayarları';

    protected static ?string $slug = 'site-ayarlari';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(Setting::allValues());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Tabs::make('Ayarlar')->persistTabInQueryString()->tabs([
                    Tab::make('Genel')->icon(Heroicon::OutlinedHome)->columns(2)->schema([
                        TextInput::make('site_name')->label('Site adı')->required()->maxLength(100),
                        TextInput::make('site_tagline')->label('Kısa slogan')->maxLength(150),
                        Fields::image('logo', 'uploads/ayarlar', 'Logo')
                            ->helperText('PNG (şeffaf zemin) veya WEBP, en fazla 4 MB. Boşsa tema logosu kullanılır.'),
                        FileUpload::make('favicon')
                            ->label('Favicon')
                            ->image()
                            ->disk('public')
                            ->visibility('public')
                            ->directory('uploads/ayarlar')
                            ->acceptedFileTypes(['image/png', 'image/x-icon', 'image/vnd.microsoft.icon'])
                            ->maxSize(512)
                            ->helperText('Kare PNG veya ICO, en fazla 512 KB. Önerilen: 512×512.'),
                        Textarea::make('footer_text')->label('Footer metni')->rows(3)->maxLength(300)->columnSpanFull(),
                    ]),
                    Tab::make('İletişim')->icon(Heroicon::OutlinedPhone)->columns(2)->schema([
                        TextInput::make('phone_gsm')->label('GSM / WhatsApp (görünen)')->required()->maxLength(30),
                        TextInput::make('whatsapp')->label('WhatsApp numarası')
                            ->required()
                            ->regex('/^\d{10,15}$/')
                            ->helperText('Ülke koduyla, yalnızca rakam. Ör: 905324889603'),
                        TextInput::make('phone_landline')->label('Sabit telefon')->maxLength(30),
                        TextInput::make('email')->label('E-posta')->email()->required()->maxLength(150),
                        TextInput::make('whatsapp_message')->label('WhatsApp hazır mesajı')->maxLength(200)->columnSpanFull(),
                        Textarea::make('address')->label('Adres')->rows(2)->required()->maxLength(300)->columnSpanFull(),
                        TextInput::make('working_hours')->label('Çalışma saatleri')
                            ->maxLength(150)
                            ->helperText('Doğrulanmış bilgi girilene kadar boş bırakın; boşsa sitede gösterilmez.'),
                        TextInput::make('map_link')->label('Google Haritalar bağlantısı')->url()->maxLength(500),
                        TextInput::make('map_embed_url')->label('Harita gömme adresi (iframe src)')
                            ->url()
                            ->maxLength(1000)
                            ->columnSpanFull()
                            ->helperText('Google Haritalar > Paylaş > Harita yerleştir bölümündeki src değeri.'),
                    ]),
                    Tab::make('Sosyal Medya')->icon(Heroicon::OutlinedShare)->columns(2)->schema([
                        TextInput::make('facebook_url')->label('Facebook')->url()->maxLength(255),
                        TextInput::make('instagram_url')->label('Instagram')->url()->maxLength(255),
                        TextInput::make('x_url')->label('X (Twitter)')->url()->maxLength(255),
                        TextInput::make('youtube_url')->label('YouTube')->url()->maxLength(255),
                    ]),
                    Tab::make('SEO')->icon(Heroicon::OutlinedMagnifyingGlass)->columns(2)->schema([
                        TextInput::make('seo_home_title')->label('Ana sayfa başlığı (title)')->maxLength(70)->columnSpanFull(),
                        Textarea::make('seo_home_description')->label('Ana sayfa açıklaması')->rows(2)->maxLength(320)->columnSpanFull(),
                        Textarea::make('seo_default_description')->label('Varsayılan açıklama')->rows(2)->maxLength(320)->columnSpanFull()
                            ->helperText('Açıklaması olmayan sayfalarda kullanılır.'),
                        Fields::image('seo_og_image', 'uploads/ayarlar', 'Paylaşım görseli (Open Graph)')
                            ->helperText('Önerilen 1200×630. Boşsa varsayılan görsel kullanılır.'),
                        TextInput::make('google_site_verification')->label('Google Search Console doğrulama kodu')->maxLength(100),
                    ]),
                    Tab::make('Ana Sayfa')->icon(Heroicon::OutlinedSquares2x2)->columns(2)->schema([
                        TextInput::make('home_about_title')->label('Tanıtım başlığı')->maxLength(150),
                        Fields::image('home_about_image', 'uploads/genel', 'Tanıtım görseli'),
                        Textarea::make('home_about_text')->label('Tanıtım metni')->rows(4)->maxLength(800)->columnSpanFull()
                            ->helperText('Boşsa "Hakkımızda" sayfasının giriş metni kullanılır.'),
                    ]),
                    Tab::make('Kurumsal')->icon(Heroicon::OutlinedBuildingOffice2)->columns(2)->schema([
                        TextInput::make('founding_year')->label('Kuruluş yılı')->numeric()
                            ->minValue(1900)->maxValue((int) date('Y'))
                            ->helperText('Belgeden alınan başlangıç değeri doğrulama bekler.'),
                        Toggle::make('founding_year_verified')->label('Kuruluş yılı doğrulandı')->inline(false)
                            ->helperText('Kapalıyken kuruluş yılı ana sayfa, Hakkımızda, SEO verisi ve yapılandırılmış veride yayınlanmaz.'),
                        Textarea::make('experience_text')->label('Deneyim metni')->rows(2)->maxLength(400)->columnSpanFull()
                            ->helperText('Hakkımızda sayfasındaki "Kurumsal Geçmişimiz" alanında gösterilir. Doğrulanmamış yıl/sayı iddiası yazmayın.'),
                        Textarea::make('company_history')->label('Kurumsal geçmiş açıklaması')->rows(5)->maxLength(3000)->columnSpanFull(),
                    ]),
                ]),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Form::make([EmbeddedSchema::make('form')])
                ->id('form')
                ->livewireSubmitHandler('save')
                ->footer([
                    Actions::make([
                        Action::make('save')->label('Kaydet')->submit('save')->keyBindings(['mod+s']),
                    ])->sticky(),
                ]),
        ]);
    }

    public function save(): void
    {
        Setting::put($this->form->getState());

        Notification::make()->success()->title('Ayarlar kaydedildi')->send();
    }
}

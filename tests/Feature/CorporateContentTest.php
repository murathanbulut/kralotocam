<?php

namespace Tests\Feature;

use App\Filament\Pages\ManageSettings;
use App\Filament\Resources\FleetPartners\Pages\CreateFleetPartner;
use App\Filament\Resources\FleetPartners\Pages\EditFleetPartner;
use App\Filament\Resources\InsuranceCompanies\Pages\CreateInsuranceCompany;
use App\Filament\Resources\InsuranceCompanies\Pages\EditInsuranceCompany;
use App\Models\FleetPartner;
use App\Models\InsuranceCompany;
use App\Models\Setting;
use App\Models\User;
use App\Support\Seo;
use Database\Seeders\CorporateContentSeeder;
use Database\Seeders\ServiceSeeder;
use Database\Seeders\SiteContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Sigorta, filo ve kurumsal geçmiş: doğrulanmamış bilgiler kamuya açık gösterilmez.
 */
class CorporateContentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->seed([ServiceSeeder::class, SiteContentSeeder::class, CorporateContentSeeder::class]);
    }

    public function test_seeder_imports_source_lists_as_unverified(): void
    {
        $this->assertSame(count(CorporateContentSeeder::INSURANCE), InsuranceCompany::count());
        $this->assertSame(21, FleetPartner::count());
        // İşletme onayıyla tüm kayıtlar doğrulanmış başlar
        $this->assertSame(count(CorporateContentSeeder::INSURANCE), InsuranceCompany::published()->count());
        $this->assertSame(21, FleetPartner::published()->count());
        $this->assertStringContainsString('[unvan teyidi]', InsuranceCompany::where('name', 'Anlara sigorta')->value('internal_note'));

        // Tekrar çalıştırma yinelenen kayıt üretmez
        $this->seed(CorporateContentSeeder::class);
        $this->assertSame(21, FleetPartner::count());
    }

    public function test_pages_open_with_seo_and_hide_unverified_records(): void
    {
        InsuranceCompany::query()->update(['is_verified' => false]);
        FleetPartner::query()->update(['is_verified' => false]);

        foreach (['/anlasmali-sigortalar/' => 'Anlaşmalı Sigortalar', '/filo-cozumleri/' => 'Filo ve Kurumsal Araç Çözümleri'] as $url => $title) {
            $this->get($url)
                ->assertOk()
                ->assertSee('<h1', false)
                ->assertSee($title)
                ->assertSee('<link rel="canonical" href="'.site_url(trim($url, '/')).'"', false)
                ->assertSee('property="og:title"', false)
                ->assertSee('<meta name="description"', false)
                ->assertSee('BreadcrumbList', false)
                ->assertSee('data-org-empty', false)
                ->assertDontSee('data-org-item', false)
                ->assertDontSee('Allianz sigorta')
                ->assertDontSee('Sixt Rent a Car');
        }

        $this->get('/sitemap.xml')
            ->assertSee(site_url('anlasmali-sigortalar'), false)
            ->assertSee(site_url('filo-cozumleri'), false);
    }

    public function test_only_verified_and_active_records_are_listed(): void
    {
        InsuranceCompany::query()->update(['is_verified' => false]);
        FleetPartner::query()->update(['is_verified' => false]);
        InsuranceCompany::where('name', 'Allianz sigorta')->first()->update(['is_verified' => true]);
        InsuranceCompany::where('name', 'Axa sigorta')->first()->update(['is_verified' => true, 'is_active' => false]);
        FleetPartner::where('name', 'Sixt Rent a Car')->first()->update(['is_verified' => true, 'logo' => 'uploads/filo/sixt.png']);

        $this->get('/anlasmali-sigortalar/')
            ->assertSee('Allianz sigorta')
            ->assertDontSee('Axa sigorta')
            ->assertDontSee('Anadolu sigorta')
            ->assertDontSee('data-org-empty', false);

        // Logo kullanım hakkı onaylanmadan logo yayınlanmaz
        $this->get('/filo-cozumleri/')->assertSee('Sixt Rent a Car')->assertDontSee('uploads/filo/sixt.png');
        FleetPartner::where('name', 'Sixt Rent a Car')->first()->update(['logo_rights_confirmed' => true]);
        $this->get('/filo-cozumleri/')->assertSee('uploads/filo/sixt.png');

        $this->assertNotNull(InsuranceCompany::where('name', 'Allianz sigorta')->value('verified_at'));
    }

    public function test_admin_can_create_and_edit_organizations(): void
    {
        InsuranceCompany::query()->update(['is_verified' => false]);
        $this->actingAs(User::factory()->create(['is_admin' => true]));

        Livewire::test(CreateInsuranceCompany::class)
            ->fillForm(['name' => 'Deneme Sigorta', 'is_verified' => true, 'is_active' => true, 'sort_order' => 99])
            ->call('create')
            ->assertHasNoFormErrors();
        $this->assertTrue(InsuranceCompany::where('name', 'Deneme Sigorta')->value('is_verified'));

        Livewire::test(CreateInsuranceCompany::class)
            ->fillForm(['name' => 'Allianz sigorta'])
            ->call('create')
            ->assertHasFormErrors(['name']);

        $record = InsuranceCompany::where('name', 'Ray sigorta')->first();
        Livewire::test(EditInsuranceCompany::class, ['record' => $record->getKey()])
            ->fillForm(['description' => 'Kasko cam hasarı işlemleri.', 'is_verified' => true])
            ->call('save')
            ->assertHasNoFormErrors();
        $this->get('/anlasmali-sigortalar/')->assertSee('Ray sigorta')->assertSee('Kasko cam hasarı işlemleri.');

        Livewire::test(CreateFleetPartner::class)
            ->fillForm(['name' => 'Deneme Filo', 'is_active' => true, 'sort_order' => 50])
            ->call('create')
            ->assertHasNoFormErrors();
        $this->get('/filo-cozumleri/')->assertDontSee('Deneme Filo');

        $fleet = FleetPartner::where('name', 'Deneme Filo')->first();
        Livewire::test(EditFleetPartner::class, ['record' => $fleet->getKey()])
            ->fillForm(['is_verified' => true])
            ->call('save')
            ->assertHasNoFormErrors();
        $this->get('/filo-cozumleri/')->assertSee('Deneme Filo');
    }

    public function test_founding_year_is_hidden_until_verified(): void
    {
        $this->assertSame(2005, founding_year()); // işletme onayıyla doğrulanmış başlar
        Setting::put(['founding_year_verified' => '0']);
        $this->assertSame('2005', (string) Setting::get('founding_year'));
        $this->assertNull(founding_year());

        foreach (['/', '/hakkimizda/'] as $url) {
            $this->get($url)->assertDontSee('data-founding-year', false)->assertDontSee('foundingDate', false);
        }
        $this->assertArrayNotHasKey('foundingDate', Seo::localBusinessSchema());

        $this->actingAs(User::factory()->create(['is_admin' => true]));
        Livewire::test(ManageSettings::class)
            ->fillForm(['founding_year_verified' => true, 'experience_text' => 'Deneme deneyim metni.'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame(2005, founding_year());
        $this->assertSame((int) date('Y') - 2005, experience_years());
        $this->get('/hakkimizda/')->assertSee('data-founding-year', false)->assertSee('Deneme deneyim metni.');
        $this->get('/')->assertSee('data-founding-year', false)->assertSee('"foundingDate":"2005"', false);
    }

    public function test_corporate_menu_contains_new_links(): void
    {
        $html = $this->get('/')->getContent();

        foreach (['Hakkımızda', 'Misyonumuz', 'Vizyonumuz', 'Anlaşmalı Sigortalar', 'Filo Çözümleri'] as $label) {
            $this->assertStringContainsString($label, $html);
        }
        $this->assertStringContainsString('href="'.site_url('anlasmali-sigortalar').'"', $html);
        $this->assertStringContainsString('href="'.site_url('filo-cozumleri').'"', $html);
    }

    public function test_home_page_corporate_sections_link_to_existing_pages(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('İşimizin Merkezinde Güven Var')
            ->assertSee('Sigorta İşlemlerinizde Daha Anlaşılır Bir Süreç')
            ->assertSee('Filo ve Kurumsal Araçlar')
            ->assertSee('href="'.site_url('anlasmali-sigortalar').'" class="kral-more-link"', false)
            ->assertSee('href="'.site_url('filo-cozumleri').'" class="kral-more-link"', false)
            ->assertSee('data-founding-year', false);

        $this->get('/anlasmali-sigortalar/')->assertOk();
        $this->get('/filo-cozumleri/')->assertOk();
    }
}

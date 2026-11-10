<?php

namespace Tests\Feature;

use App\Models\Page;
use Database\Seeders\CorporateContentSeeder;
use Database\Seeders\FaqSeeder;
use Database\Seeders\LegalPagesSeeder;
use Database\Seeders\ServiceSeeder;
use Database\Seeders\SiteContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/** Yasal sayfalar onay bekleyen taslaklardır: aktif yapılana kadar yayınlanmaz ve footer'da görünmez. */
class LegalPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->seed([ServiceSeeder::class, SiteContentSeeder::class, CorporateContentSeeder::class, FaqSeeder::class, LegalPagesSeeder::class]);
    }

    public function test_drafts_are_created_once_and_stay_unpublished(): void
    {
        $this->seed(LegalPagesSeeder::class);

        foreach (Page::LEGAL_SLUGS as $slug) {
            $this->assertSame(1, Page::where('slug', $slug)->count());
            $this->assertFalse(Page::where('slug', $slug)->value('is_active'));
            $this->get("/{$slug}/")->assertNotFound();
        }

        $this->assertStringContainsString('KVKK Madde 11', Page::where('slug', 'kvkk-aydinlatma-metni')->value('content'));

        $footer = $this->get('/')->assertOk()->getContent();
        foreach (Page::LEGAL_SLUGS as $slug) {
            $this->assertStringNotContainsString(site_url($slug), $footer);
        }
        $this->assertStringContainsString('aria-label="Bilgilendirme"', $footer);
        $this->assertStringContainsString('href="'.site_url('sikca-sorulan-sorular').'"', $footer);
        $this->assertStringContainsString('Alpha Creative', $footer);
        $this->get('/iletisim/')->assertDontSee('KVKK Aydınlatma Metni</a>', false);
    }

    public function test_seeder_does_not_overwrite_edited_content(): void
    {
        Page::where('slug', 'cerez-politikasi')->update(['content' => '<p>İşletmenin onayladığı metin.</p>']);
        $this->seed(LegalPagesSeeder::class);

        $this->assertSame('<p>İşletmenin onayladığı metin.</p>', Page::where('slug', 'cerez-politikasi')->value('content'));
    }

    public function test_approved_pages_appear_in_footer_and_contact_form(): void
    {
        Page::whereIn('slug', Page::LEGAL_SLUGS)->update(['is_active' => true]);

        $html = $this->get('/')->getContent();
        foreach (Page::LEGAL_SLUGS as $slug) {
            $this->assertStringContainsString('href="'.site_url($slug).'"', $html);
            $this->get("/{$slug}/")->assertOk()->assertSee('<link rel="canonical" href="'.site_url($slug).'"', false);
        }

        $this->get('/iletisim/')->assertSee('href="'.site_url('kvkk-aydinlatma-metni').'"', false);
        $this->get('/sitemap.xml')->assertSee(site_url('gizlilik-politikasi'), false);
    }
}

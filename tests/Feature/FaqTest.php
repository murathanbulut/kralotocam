<?php

namespace Tests\Feature;

use App\Filament\Resources\Faqs\Pages\CreateFaq;
use App\Filament\Resources\Faqs\Pages\EditFaq;
use App\Models\Faq;
use App\Models\User;
use Database\Seeders\CorporateContentSeeder;
use Database\Seeders\FaqSeeder;
use Database\Seeders\ServiceSeeder;
use Database\Seeders\SiteContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class FaqTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->seed([ServiceSeeder::class, SiteContentSeeder::class, CorporateContentSeeder::class, FaqSeeder::class]);
    }

    public function test_seed_contains_source_questions_without_unverified_services(): void
    {
        $this->assertSame(15, Faq::count());
        $this->assertSame(0, Faq::where('question', 'like', '%obil servis%')->orWhere('question', 'like', '%ilecek%')->count());

        $this->seed(FaqSeeder::class); // tekrar çalıştırma çoğaltmaz
        $this->assertSame(15, Faq::count());
    }

    public function test_page_shows_active_questions_with_seo(): void
    {
        $hidden = Faq::where('question', 'Orijinal cam mı kullanılıyor?')->first();
        $hidden->update(['is_active' => false]);

        $html = $this->get('/sikca-sorulan-sorular/')
            ->assertOk()
            ->assertSee('<title>Oto Cam Sıkça Sorulan Sorular | Kral Oto Cam</title>', false)
            ->assertSee('<link rel="canonical" href="'.site_url('sikca-sorulan-sorular').'"', false)
            ->assertSee('<meta name="description"', false)
            ->assertSee('BreadcrumbList', false)
            ->assertSee('Ön camdaki taş izi tamir edilebilir mi?')
            ->assertSee('Sigorta ve Kasko')
            ->assertDontSee('Orijinal cam mı kullanılıyor?')
            ->getContent();

        // Erişilebilir akordeon: her soru bir <details>/<summary>
        $this->assertSame(14, substr_count($html, 'data-faq-item'));
        $this->assertSame(14, substr_count($html, '<summary class="wptb-item-title">'));

        // FAQPage verisi görünen sorularla birebir
        $this->assertStringContainsString('"@type":"FAQPage"', $html);
        $this->assertSame(14, substr_count($html, '"@type":"Question"'));

        $this->get('/sitemap.xml')->assertSee(site_url('sikca-sorulan-sorular'), false);
        $this->get('/')->assertSee('href="'.site_url('sikca-sorulan-sorular').'"', false);
    }

    public function test_admin_can_create_edit_and_hide_questions(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));

        Livewire::test(CreateFaq::class)
            ->fillForm(['question' => 'Deneme sorusu?', 'answer' => 'Deneme cevabı.', 'category' => 'cam', 'sort_order' => 1, 'is_active' => true])
            ->call('create')
            ->assertHasNoFormErrors();
        $this->get('/sikca-sorulan-sorular/')->assertSee('Deneme sorusu?');

        Livewire::test(CreateFaq::class)
            ->fillForm(['question' => 'Eksik kategori', 'answer' => 'x'])
            ->call('create')
            ->assertHasFormErrors(['category']);

        $faq = Faq::where('question', 'Deneme sorusu?')->first();
        Livewire::test(EditFaq::class, ['record' => $faq->getKey()])
            ->fillForm(['answer' => 'Güncellenmiş cevap.', 'is_active' => false])
            ->call('save')
            ->assertHasNoFormErrors();
        $this->get('/sikca-sorulan-sorular/')->assertDontSee('Deneme sorusu?')->assertDontSee('Güncellenmiş cevap.');
    }
}

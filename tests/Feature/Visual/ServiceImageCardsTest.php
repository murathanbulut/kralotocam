<?php

namespace Tests\Feature\Visual;

use App\Models\Service;
use Database\Seeders\ServiceSeeder;
use Database\Seeders\SiteContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * D. Görselli hizmet kartları (KRAL_FX_SERVICE_IMAGES).
 */
class ServiceImageCardsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->seed([ServiceSeeder::class, SiteContentSeeder::class]);
    }

    public function test_service_image_cards_show_all_seven_services_with_links(): void
    {
        config(['kral.features.service_image_cards' => true]);

        $html = $this->get('/')->assertOk()->assertSee('kral-svc-grid', false)->getContent();

        $this->assertSame(7, substr_count($html, 'class="grid-item kral-svc-card'));

        foreach (Service::query()->active()->get() as $service) {
            $this->assertStringContainsString('href="'.$service->url().'" class="kral-svc-card__link"', $html);
        }

        // Görseli olmayan hizmette başka hizmetin fotoğrafı kullanılmaz
        $tractor = Service::query()->where('slug', 'traktor-ve-is-makinesi-cam-degisimi')->firstOrFail();
        $this->assertNull($tractor->cover_image);
        $this->assertStringContainsString('kral-svc-card--no-image', $html);
    }

    public function test_service_icon_cards_are_used_when_image_cards_are_disabled(): void
    {
        config(['kral.features.service_image_cards' => false]);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('kral-svc-grid', false)
            ->assertSee('wptb-icon-box6 kral-service-card', false);
    }
}

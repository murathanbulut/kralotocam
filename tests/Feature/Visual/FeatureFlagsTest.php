<?php

namespace Tests\Feature\Visual;

use Database\Seeders\ServiceSeeder;
use Database\Seeders\SiteContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * config/kral.php anahtarlarının <body> sınıflarına bağımsız yansıdığını doğrular.
 */
class FeatureFlagsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->seed([ServiceSeeder::class, SiteContentSeeder::class]);
    }

    public function test_body_classes_follow_feature_flags(): void
    {
        config(['kral.features.slider_text_animation' => true, 'kral.features.cursor_follower' => false, 'kral.features.scroll_header' => true]);
        $this->get('/')->assertSee('<body class="is-home fx-slider-text fx-scroll-header">', false);

        config(['kral.features.slider_text_animation' => false, 'kral.features.cursor_follower' => false, 'kral.features.scroll_header' => false]);
        $this->get('/')->assertSee('<body class="is-home">', false);
    }
}

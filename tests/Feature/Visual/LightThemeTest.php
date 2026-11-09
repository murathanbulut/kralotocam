<?php

namespace Tests\Feature\Visual;

use Database\Seeders\ServiceSeeder;
use Database\Seeders\SiteContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * E. İsteğe bağlı açık tema (KRAL_FX_LIGHT_THEME).
 */
class LightThemeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->seed([ServiceSeeder::class, SiteContentSeeder::class]);
    }

    public function test_light_theme_is_optional_and_never_the_default(): void
    {
        config(['kral.features.light_theme' => true]);

        $html = $this->get('/')->assertOk()->getContent();
        $this->assertStringContainsString('data-theme-toggle', $html);
        $this->assertStringContainsString("localStorage.getItem('kral-theme') === 'light'", $html);
        $this->assertStringContainsString('light-theme', $html);
        // Sunucu tarafında hiçbir zaman açık tema ile başlamaz
        $this->assertStringContainsString('<html lang="tr">', $html);
        $this->assertStringNotContainsString('prefers-color-scheme', $html);

        config(['kral.features.light_theme' => false]);

        $html = $this->get('/')->assertOk()->getContent();
        $this->assertStringNotContainsString('data-theme-toggle', $html);
        $this->assertStringNotContainsString('kral-theme', $html);
        $this->assertStringNotContainsString('light-theme', $html);
    }
}

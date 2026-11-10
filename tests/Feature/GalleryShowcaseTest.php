<?php

namespace Tests\Feature;

use App\Models\GalleryAlbum;
use App\Models\GalleryImage;
use Database\Seeders\ServiceSeeder;
use Database\Seeders\SiteContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Galeri vitrini (yatay carousel): yalnızca yönetim panelindeki aktif kayıtlar gösterilir.
 */
class GalleryShowcaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->seed([ServiceSeeder::class, SiteContentSeeder::class]);
    }

    public function test_gallery_index_shows_every_active_image_once_in_the_showcase(): void
    {
        $expected = GalleryImage::query()->active()->whereHas('album', fn ($q) => $q->active())->get();

        $html = $this->get('/galeri/')
            ->assertOk()
            ->assertSee('data-gallery-showcase', false)
            ->assertSee('data-showcase-prev', false)
            ->assertSee('data-showcase-next', false)
            ->getContent();

        $this->assertSame($expected->count(), substr_count($html, 'class="swiper-slide kral-showcase__slide"'));

        foreach ($expected as $image) {
            $this->assertStringContainsString('src="'.media_url($image->image).'"', $html);
            $this->assertStringContainsString('alt="'.e($image->alt()).'"', $html);
        }

        // Tek lightbox grubu, albüm adları kartlarda
        $this->assertStringContainsString('data-fslightbox="galeri"', $html);
        $this->assertStringContainsString('kral-showcase__album', $html);
    }

    public function test_inactive_images_and_albums_are_not_shown(): void
    {
        $image = GalleryImage::query()->firstOrFail();
        $image->update(['is_active' => false]);

        $album = GalleryAlbum::query()->whereHas('images', fn ($q) => $q->where('is_active', true))
            ->whereKeyNot($image->gallery_album_id)->firstOrFail();
        $album->update(['is_active' => false]);
        $hidden = $album->images()->pluck('image');

        $html = $this->get('/galeri/')->assertOk()->getContent();

        $this->assertStringNotContainsString(media_url($image->image), $html);
        foreach ($hidden as $path) {
            $this->assertStringNotContainsString(media_url($path), $html);
        }
    }

    public function test_album_page_shows_only_its_own_images_in_its_own_group(): void
    {
        $album = GalleryAlbum::query()->where('slug', 'adas-ve-kamera-kalibrasyonu')->firstOrFail();
        $others = GalleryImage::query()->where('gallery_album_id', '!=', $album->id)->pluck('image');

        $html = $this->get($album->url())->assertOk()->getContent();

        $this->assertSame($album->activeImages()->count(), substr_count($html, 'class="swiper-slide kral-showcase__slide"'));
        $this->assertStringContainsString('data-fslightbox="album-'.$album->id.'"', $html);
        foreach ($others as $path) {
            $this->assertStringNotContainsString('src="'.media_url($path).'"', $html);
        }
        // Albüm filtresi korunur
        $this->assertMatchesRegularExpression('/class="active"\s+aria-current="page"\s*>'.preg_quote($album->title, '/').'</u', $html);
    }
}

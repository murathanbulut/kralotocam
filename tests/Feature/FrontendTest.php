<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Service;
use App\Models\User;
use Database\Seeders\ServiceSeeder;
use Database\Seeders\SiteContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FrontendTest extends TestCase
{
    use RefreshDatabase;

    public const SERVICE_URLS = [
        '/binek-arac-cam-degisimi/',
        '/ticari-arac-cam-degisimi/',
        '/traktor-ve-is-makinesi-cam-degisimi/',
        '/oto-cam-onarim-ve-tamiri/',
        '/adas-ve-kamera-kalibrasyonlari/',
        '/cam-filmi/',
        '/dikiz-ve-yan-aynalar-onarim-ve-degisimleri/',
    ];

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->seed([ServiceSeeder::class, SiteContentSeeder::class]);
    }

    public function test_home_page_renders_hero_menu_and_sections(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('ESKİŞEHİR • OTO CAM SERVİSİ')
            ->assertSee('<h1 class="wptb-item--title">Net Görüş. Güvenli Yolculuk.</h1>', false)
            ->assertSee('Cam Hasarımı Değerlendirin')
            ->assertSee('Hizmetlerimizi İnceleyin')
            ->assertSee('"@type":"AutoRepair"', false)
            ->assertSee('<link rel="canonical" href="http://localhost/">', false)
            ->assertSee('Hizmet Sürecimiz')
            ->assertSee('Atölyemizden Kareler');

        // Menü yapısı
        foreach (['Ana Sayfa', 'Kurumsal', 'Hakkımızda', 'Misyonumuz', 'Vizyonumuz', 'Hizmetler', 'Galeri', 'Blog', 'İletişim'] as $label) {
            $response->assertSee('>'.$label.'</a>', false);
        }

        // Sektörle ilgisiz demo menüleri bulunmamalı
        foreach (['Ürünler', 'Mağaza', 'Sepet', 'Portföy', 'Kimono'] as $demo) {
            $response->assertDontSee($demo);
        }
    }

    public function test_home_page_has_single_h1(): void
    {
        $html = $this->get('/')->getContent();

        $this->assertSame(1, substr_count($html, '<h1'));
    }

    public function test_all_seven_service_pages_render_with_breadcrumb_and_contact_button(): void
    {
        $this->assertSame(7, Service::query()->active()->count());

        foreach (self::SERVICE_URLS as $url) {
            $service = Service::query()->where('slug', trim($url, '/'))->firstOrFail();

            $this->get($url)
                ->assertOk()
                ->assertSee('<h1 class="wptb-item--title">'.e($service->title).'</h1>', false)
                ->assertSee('wptb-breadcrumb', false)
                ->assertSee('"@type":"BreadcrumbList"', false)
                ->assertSee('"@type":"Service"', false)
                ->assertSee('İlgili Hizmetler')
                ->assertSee('Bilgi ve Randevu Talebi')
                ->assertSee('<link rel="canonical" href="http://localhost'.$url.'">', false);
        }
    }

    public function test_corporate_pages_render(): void
    {
        foreach (['/hakkimizda/' => 'Hakkımızda', '/misyonumuz/' => 'Misyonumuz', '/vizyonumuz/' => 'Vizyonumuz'] as $url => $title) {
            $this->get($url)->assertOk()->assertSee('<h1 class="wptb-item--title">'.$title.'</h1>', false);
        }
    }

    public function test_urls_without_trailing_slash_are_redirected_permanently(): void
    {
        $this->get('/iletisim')->assertRedirect('http://localhost/iletisim/')->assertStatus(301);
        $this->get('/cam-filmi')->assertRedirect('http://localhost/cam-filmi/')->assertStatus(301);
        $this->get('/blog?page=2')->assertRedirect('http://localhost/blog/?page=2');
    }

    public function test_old_live_site_urls_redirect_to_new_urls(): void
    {
        $map = [
            '/tr/hizmet/binek-arac-cam-degisimi' => '/binek-arac-cam-degisimi/',
            '/tr/hizmet/dikiz-ve-yan-ayna-onarimi' => '/dikiz-ve-yan-aynalar-onarim-ve-degisimleri/',
            '/tr/sayfa/hakkimizda-16' => '/hakkimizda/',
            '/tr/sayfa/vizyonumuz-12' => '/vizyonumuz/',
            '/tr/galeri/kral-oto-cam-galerisi' => '/galeri/',
            '/tr/haberler' => '/blog/',
            '/tr/iletisim' => '/iletisim/',
            '/tr/' => '/',
        ];

        foreach ($map as $from => $to) {
            $this->get($from)->assertStatus(301)->assertRedirect('http://localhost'.$to);
        }
    }

    public function test_unknown_and_inactive_urls_return_404(): void
    {
        $this->get('/olmayan-sayfa/')->assertNotFound()->assertSee('Sayfa Bulunamadı');
        $this->get('/tr/projeler')->assertNotFound();
        $this->get('/cerez-politikasi/')->assertNotFound(); // pasif taslak sayfa
        $this->get('/galeri/olmayan-album/')->assertNotFound();
        $this->get('/blog/olmayan-yazi/')->assertNotFound();
    }

    public function test_gallery_lists_albums_with_lightbox_links(): void
    {
        $this->get('/galeri/')
            ->assertOk()
            ->assertSee('data-fslightbox=', false)
            ->assertSee('ADAS Kalibrasyon ve Sistem Kontrolü')
            ->assertSee('alt="Teknisyen ADAS kalibrasyon panosu önünde diyagnostik cihazı kullanıyor"', false)
            // görseli olmayan albümler gösterilmez
            ->assertDontSee('Cam Filmi Uygulamaları');

        $this->get('/galeri/adas-ve-kamera-kalibrasyonu/')->assertOk()->assertSee('ADAS Kamera Kalibrasyon Ekipmanları');
    }

    public function test_draft_blog_posts_are_hidden_but_admin_can_preview(): void
    {
        $draft = BlogPost::query()->firstOrFail();

        $this->get('/blog/')->assertOk()->assertDontSee($draft->title);
        $this->get($draft->url())->assertNotFound();

        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)->get('/blog/'.$draft->slug.'/')
            ->assertOk()
            ->assertSee('taslak')
            ->assertSee('<meta name="robots" content="noindex, nofollow">', false);
    }

    public function test_published_blog_post_is_listed_and_detail_renders(): void
    {
        $post = BlogPost::query()->firstOrFail();
        $post->update(['status' => BlogPost::STATUS_PUBLISHED, 'published_at' => now()->subDay(), 'content' => '<p>Deneme içerik</p>']);

        $this->get('/blog/')->assertOk()->assertSee($post->title);
        $this->get('/')->assertSee($post->title);
        $this->get('/blog/'.$post->slug.'/')
            ->assertOk()
            ->assertSee('Deneme içerik', false)
            ->assertSee('"@type":"BlogPosting"', false);
        $this->get('/blog/kategori/'.$post->category->slug.'/')->assertOk()->assertSee($post->title);
        $this->get('/sitemap.xml')->assertSee($post->url(), false);
    }

    public function test_scheduled_blog_post_is_not_visible_before_publish_date(): void
    {
        $post = BlogPost::query()->firstOrFail();
        $post->update(['status' => BlogPost::STATUS_PUBLISHED, 'published_at' => now()->addWeek()]);

        $this->get('/blog/')->assertDontSee($post->title);
        $this->get($post->url())->assertNotFound();
    }

    public function test_sitemap_and_robots(): void
    {
        $sitemap = $this->get('/sitemap.xml');
        $sitemap->assertOk()->assertHeader('Content-Type', 'application/xml; charset=UTF-8');

        foreach (self::SERVICE_URLS as $url) {
            $sitemap->assertSee('<loc>http://localhost'.$url.'</loc>', false);
        }
        $sitemap->assertSee('<loc>http://localhost/hakkimizda/</loc>', false)
            ->assertDontSee('cerez-politikasi');

        // Test/lokal ortamda dizine eklemeyi engeller
        $this->get('/robots.txt')->assertOk()->assertSee('Disallow: /');
    }

    public function test_contact_page_renders_business_information(): void
    {
        $this->get('/iletisim/')
            ->assertOk()
            ->assertSee('Teksan Sanayi Sitesi, A4 Blok No: 1, Odunpazarı / Eskişehir')
            ->assertSee('0532 488 96 03')
            ->assertSee('0222 228 22 22')
            ->assertSee('info@kralotocam.com')
            ->assertSee('https://wa.me/905324889603', false)
            ->assertSee('google.com/maps', false)
            ->assertSee('name="website"', false)
            ->assertSee('name="service_id"', false);
    }
}

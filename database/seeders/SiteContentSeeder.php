<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\GalleryAlbum;
use App\Models\GalleryImage;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Redirect;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Slider;
use Database\Seeders\Concerns\CopiesSeedImages;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class SiteContentSeeder extends Seeder
{
    use CopiesSeedImages;

    public function run(): void
    {
        $this->settings();
        $this->pages();
        $this->menu();
        $this->sliders();
        $this->gallery();
        $this->blog();
        $this->redirects();

        Cache::flush();
    }

    private function settings(): void
    {
        Setting::put([
            'home_about_image' => $this->seedImage('binek-arac-on-cam-montaji.jpg', 'uploads/genel'),
        ]);
    }

    /**
     * Kurumsal sayfalar. Referanslar klasöründe SEO/kurumsal içerik dokümanı bulunmadığından
     * yalnızca doğrulanmış bilgiler (işletme adı, adres, hizmetler) kullanılmıştır.
     * Misyon ve vizyon metinleri işletme tarafından onaylanmalıdır (README'ye bakın).
     */
    private function pages(): void
    {
        $cover = $this->seedImage('arac-cami-hasar-degerlendirmesi.jpg', 'uploads/sayfalar');

        $pages = [
            [
                'title' => 'Hakkımızda',
                'slug' => 'hakkimizda',
                'cover_image' => $cover,
                'excerpt' => 'Kral Oto Cam; Eskişehir Teksan Sanayi Sitesi\'nde araç camı onarımı ve değişimi, ADAS kamera kalibrasyonu, cam filmi ile dikiz ve yan ayna uygulamalarında hizmet veren bir oto cam servisidir.',
                'seo_title' => 'Hakkımızda | Kral Oto Cam Eskişehir',
                'seo_description' => 'Kral Oto Cam, Eskişehir Teksan Sanayi Sitesi\'nde oto cam onarımı ve değişimi, ADAS kalibrasyonu, cam filmi ve ayna uygulamaları sunar.',
                'content' => <<<'HTML'
<p>Araç camı, sürüş güvenliğinin en önemli parçalarından biridir. Net bir görüş alanı; hem sürücünün hem de yoldaki diğer insanların güvenliği için gereklidir. Kral Oto Cam olarak bu bilinçle, aracınızdaki cam hasarı için doğru çözümü sizinle birlikte belirlemeyi amaçlıyoruz.</p>
<h2>Neler Yapıyoruz?</h2>
<ul>
<li><a href="/binek-arac-cam-degisimi/">Binek araç cam değişimi</a></li>
<li><a href="/ticari-arac-cam-degisimi/">Ticari araç cam değişimi</a></li>
<li><a href="/traktor-ve-is-makinesi-cam-degisimi/">Traktör ve iş makinesi cam değişimi</a></li>
<li><a href="/oto-cam-onarim-ve-tamiri/">Oto cam onarım ve tamiri</a></li>
<li><a href="/adas-ve-kamera-kalibrasyonlari/">ADAS ve kamera kalibrasyonu</a></li>
<li><a href="/cam-filmi/">Cam filmi</a></li>
<li><a href="/dikiz-ve-yan-aynalar-onarim-ve-degisimleri/">Dikiz ve yan ayna onarımı ve değişimi</a></li>
</ul>
<h2>Yaklaşımımız</h2>
<p>Her çatlak cam değişimi gerektirmez. Bu nedenle önce hasarı değerlendiriyor, onarımın mümkün olduğu durumlarda onarımı, gerekli olduğunda ise aracınıza uygun camla değişimi öneriyoruz. Ön camında kamera bulunan araçlarda, değişim sonrasında sürüş destek sistemlerinin doğru çalışması için ADAS kalibrasyonunu da sürecin bir parçası olarak ele alıyoruz.</p>
<h2>Bize Ulaşın</h2>
<p>Teksan Sanayi Sitesi, A4 Blok No: 1, Odunpazarı / Eskişehir adresindeki servisimize gelebilir, hasarın fotoğrafını WhatsApp üzerinden gönderebilir veya bizi arayarak bilgi alabilirsiniz.</p>
HTML,
            ],
            [
                'title' => 'Misyonumuz',
                'slug' => 'misyonumuz',
                'cover_image' => $cover,
                'excerpt' => 'Araç sahiplerine cam hasarlarında doğru tespit, araca uygun çözüm ve özenli işçilikle güvenli bir sürüş deneyimi sunmak.',
                'seo_title' => 'Misyonumuz | Kral Oto Cam',
                'seo_description' => 'Kral Oto Cam misyonu: cam hasarlarında doğru tespit, araca uygun çözüm ve özenli işçilikle güvenli sürüşe katkı sağlamak.',
                'content' => <<<'HTML'
<p>Misyonumuz; Eskişehir'deki araç sahiplerine, cam hasarlarında doğru tespit ve araca uygun çözümlerle güvenli ve net bir görüş alanı sağlamaktır.</p>
<ul>
<li>Hasarı değerlendirip mümkün olduğunda onarımı, gerektiğinde değişimi önermek</li>
<li>Aracın modeline ve donanımlarına uygun cam ve malzemeyle çalışmak</li>
<li>Ön kamera bulunan araçlarda ADAS kalibrasyon ihtiyacını gözetmek</li>
<li>Müşterilerimizi süreç hakkında açık ve anlaşılır biçimde bilgilendirmek</li>
</ul>
HTML,
            ],
            [
                'title' => 'Vizyonumuz',
                'slug' => 'vizyonumuz',
                'cover_image' => $cover,
                'excerpt' => 'Gelişen araç teknolojilerini takip eden, güvenilir ve tercih edilen bir oto cam servisi olmak.',
                'seo_title' => 'Vizyonumuz | Kral Oto Cam',
                'seo_description' => 'Kral Oto Cam vizyonu: gelişen araç teknolojilerini takip eden, güvenilir ve tercih edilen bir oto cam servisi olmak.',
                'content' => <<<'HTML'
<p>Vizyonumuz; gelişen araç teknolojilerini yakından takip ederek Eskişehir'de güvenilir ve tercih edilen bir oto cam servisi olmaktır.</p>
<p>Araçlardaki sürüş destek sistemleri yaygınlaştıkça ön camın görevi de değişiyor. Bu değişime uyum sağlamak, cam onarımı ve değişiminde olduğu kadar ADAS kalibrasyonu gibi yeni ihtiyaçlarda da doğru çözümler sunmak hedefimizdir.</p>
HTML,
            ],
            [
                'title' => 'Çerez Politikası',
                'slug' => 'cerez-politikasi',
                'is_active' => false,
                'excerpt' => null,
                'seo_title' => 'Çerez Politikası | Kral Oto Cam',
                'seo_description' => null,
                'content' => '<p><strong>TASLAK:</strong> Bu sayfanın hukuki metni işletme tarafından sağlanmalı ve onaylandıktan sonra yayına alınmalıdır.</p>',
            ],
            [
                'title' => 'KVKK Aydınlatma Metni',
                'slug' => 'kvkk-aydinlatma-metni',
                'is_active' => false,
                'excerpt' => null,
                'seo_title' => 'KVKK Aydınlatma Metni | Kral Oto Cam',
                'seo_description' => null,
                'content' => '<p><strong>TASLAK:</strong> 6698 sayılı KVKK kapsamındaki aydınlatma metni işletme tarafından sağlanmalı ve onaylandıktan sonra yayına alınmalıdır.</p>',
            ],
        ];

        foreach ($pages as $i => $page) {
            Page::query()->updateOrCreate(['slug' => $page['slug']], $page + [
                'sort_order' => $i + 1,
                'is_active' => $page['is_active'] ?? true,
            ]);
        }
    }

    private function menu(): void
    {
        MenuItem::query()->delete();

        $order = 0;
        $add = function (string $title, ?string $url, ?MenuItem $parent = null) use (&$order) {
            return MenuItem::create([
                'parent_id' => $parent?->id,
                'title' => $title,
                'url' => $url,
                'sort_order' => ++$order,
                'is_active' => true,
            ]);
        };

        $add('Ana Sayfa', '/');

        $corporate = $add('Kurumsal', null);
        $add('Hakkımızda', '/hakkimizda/', $corporate);
        $add('Misyonumuz', '/misyonumuz/', $corporate);
        $add('Vizyonumuz', '/vizyonumuz/', $corporate);

        $services = $add('Hizmetler', '/hizmetler/');
        Service::query()->active()->ordered()->get()->each(
            fn (Service $s) => $add($s->label(), '/'.$s->slug.'/', $services)
        );

        $add('Galeri', '/galeri/');
        $add('Blog', '/blog/');
        $add('İletişim', '/iletisim/');
    }

    private function sliders(): void
    {
        Slider::query()->delete();

        $slides = [
            [
                'subtitle' => 'ESKİŞEHİR • OTO CAM SERVİSİ',
                'title' => 'Net Görüş. Güvenli Yolculuk.',
                'description' => 'Ön cam çatlağı, taş izi veya kırık araç camı için doğru çözümü birlikte belirleyelim. Kral Oto Cam; Eskişehir\'de araç camı onarımı ve değişimi, ADAS kamera kalibrasyonu ve cam filmi uygulamalarında profesyonel servis desteği sunar.',
                'image' => $this->seedImage('binek-arac-on-cam-montaji.jpg', 'uploads/slider'),
                'image_alt' => 'Kral Oto Cam atölyesinde ön cam montajı',
                'primary_button_text' => 'Cam Hasarımı Değerlendirin',
                'primary_button_url' => '/iletisim/#iletisim-formu',
                'secondary_button_text' => 'Hizmetlerimizi İnceleyin',
                'secondary_button_url' => '/hizmetler/',
            ],
            [
                'subtitle' => 'CAM HASARI MI VAR?',
                'title' => 'Her Çatlak, Cam Değişimi Gerektirmez.',
                'description' => 'Taş izi ve küçük çatlaklar, hasarın boyutuna ve konumuna göre çoğu zaman onarılabilir. Hasarınızı erkenden değerlendirelim.',
                'image' => $this->seedImage('on-cam-tas-izi-onarimi.jpg', 'uploads/slider'),
                'image_alt' => 'Ön camda taş izi onarımı',
                'primary_button_text' => 'Oto Cam Onarımı',
                'primary_button_url' => '/oto-cam-onarim-ve-tamiri/',
                'secondary_button_text' => 'WhatsApp ile Fotoğraf Gönderin',
                'secondary_button_url' => 'https://wa.me/905324889603',
            ],
            [
                'subtitle' => 'TEKNOLOJİYE UYUMLU SERVİS',
                'title' => 'Ön Cam Değişiminden Sonra ADAS Kontrolünü Unutmayın.',
                'description' => 'Ön kamerası bulunan araçlarda cam değişiminden sonra sürüş destek sistemlerinin doğru çalışması için kalibrasyon gerekebilir.',
                'image' => $this->seedImage('adas-kamera-kalibrasyon-ekipmanlari.jpg', 'uploads/slider'),
                'image_alt' => 'ADAS kamera kalibrasyon ekipmanları',
                'primary_button_text' => 'ADAS Kalibrasyonu',
                'primary_button_url' => '/adas-ve-kamera-kalibrasyonlari/',
                'secondary_button_text' => 'Bize Ulaşın',
                'secondary_button_url' => '/iletisim/',
            ],
        ];

        foreach ($slides as $i => $slide) {
            Slider::create($slide + ['sort_order' => $i + 1, 'is_active' => true]);
        }
    }

    /**
     * Önerilen 8 albüm. Görseller referans klasöründeki 6 gerçek atölye görselidir;
     * görseli olmayan albümler ön yüzde gösterilmez (yönetim panelinden görsel eklenebilir).
     * Not: canlı sitede "Araç Yan Cam Filmi Uygulaması" başlığıyla yayınlanan görsel
     * aslında yan ayna değişimini gösterdiği için "Dikiz ve Yan Ayna" albümüne alınmıştır.
     */
    private function gallery(): void
    {
        GalleryImage::query()->delete();
        GalleryAlbum::query()->delete();

        $albums = [
            ['Binek Araç Cam Değişimi', 'binek-arac-cam-degisimi', 'Binek araçlarda ön, yan ve arka cam değişimi uygulamaları.', [
                ['binek-arac-on-cam-montaji.jpg', 'Binek Araç Ön Cam Montajı', 'Vantuzlarla yeni ön camın araca yerleştirilmesi.', 'Teknisyen vantuz kullanarak gri bir otomobile ön cam montajı yapıyor'],
            ]],
            ['Ticari Araç Cam Değişimi', 'ticari-arac-cam-degisimi', 'Panelvan, minibüs ve ticari araç cam değişimleri.', []],
            ['Traktör ve İş Makinesi Camları', 'traktor-ve-is-makinesi-camlari', 'Traktör ve iş makinesi kabin camı uygulamaları.', []],
            ['Oto Cam Onarım ve Tamiri', 'oto-cam-onarim-ve-tamiri', 'Ön camdaki taş izi ve küçük çatlakların onarımı.', [
                ['on-cam-tas-izi-onarimi.jpg', 'Ön Cam Taş İzi Onarımı', 'Taş izinin onarım aparatıyla tamir edilmesi.', 'Teknisyen ön camdaki taş izini onarım aparatıyla tamir ediyor'],
            ]],
            ['ADAS ve Kamera Kalibrasyonu', 'adas-ve-kamera-kalibrasyonu', 'Ön cam değişimi sonrası ADAS ve ön kamera kalibrasyonu.', [
                ['adas-kalibrasyon-sistem-kontrolu.jpg', 'ADAS Kalibrasyon ve Sistem Kontrolü', 'Kalibrasyon hedef panosu ve diyagnostik cihazı ile sistem kontrolü.', 'Teknisyen ADAS kalibrasyon panosu önünde diyagnostik cihazı kullanıyor'],
                ['adas-kamera-kalibrasyon-ekipmanlari.jpg', 'ADAS Kamera Kalibrasyon Ekipmanları', 'Ön kamera kalibrasyonu için konumlandırılmış hedef panosu.', 'Beyaz SUV önüne yerleştirilmiş ADAS kamera kalibrasyon ekipmanı'],
            ]],
            ['Cam Filmi Uygulamaları', 'cam-filmi-uygulamalari', 'Araç cam filmi uygulamaları.', []],
            ['Dikiz ve Yan Ayna Onarımı', 'dikiz-ve-yan-ayna-onarimi', 'Dikiz ve yan ayna onarım ve değişim uygulamaları.', [
                ['yan-ayna-degisimi.jpg', 'Yan Ayna Değişimi', 'Hasarlı yan ayna gövdesinin değiştirilmesi.', 'Teknisyen beyaz bir aracın yan aynasını değiştiriyor'],
            ]],
            ['Servis ve Müşteri Deneyimi', 'servis-ve-musteri-deneyimi', 'Atölyemizden servis ve müşteri bilgilendirme anları.', [
                ['arac-cami-hasar-degerlendirmesi.jpg', 'Araç Camı Hasar Değerlendirmesi', 'Müşteriye cam hasarı ve uygulanacak işlem hakkında bilgi verilmesi.', 'Teknisyen müşteriye aracın ön camındaki hasarı açıklıyor'],
            ]],
        ];

        foreach ($albums as $i => [$title, $slug, $description, $images]) {
            $album = GalleryAlbum::create([
                'title' => $title,
                'slug' => $slug,
                'description' => $description,
                'seo_title' => $title.' | Galeri',
                'seo_description' => $description,
                'sort_order' => $i + 1,
                'is_active' => true,
            ]);

            foreach ($images as $j => [$file, $imageTitle, $imageDescription, $alt]) {
                $album->images()->create([
                    'title' => $imageTitle,
                    'description' => $imageDescription,
                    'alt_text' => $alt,
                    'image' => $this->seedImage($file, 'uploads/galeri'),
                    'sort_order' => $j + 1,
                    'is_active' => true,
                ]);
            }
        }
    }

    /**
     * Blog: Kaynak dokümanda makale metni bulunmadığından yazılar TASLAK olarak eklenir
     * ve ön yüzde yayınlanmaz. İçerik eklendikten sonra yönetim panelinden yayına alınabilir.
     */
    private function blog(): void
    {
        BlogPost::query()->delete();
        BlogCategory::query()->delete();

        $categories = collect([
            ['Cam Onarımı ve Değişimi', 'cam-onarimi-ve-degisimi'],
            ['ADAS ve Sürüş Güvenliği', 'adas-ve-surus-guvenligi'],
            ['Sigorta ve Kasko', 'sigorta-ve-kasko'],
            ['Cam Filmi', 'cam-filmi'],
        ])->mapWithKeys(fn ($c, $i) => [$c[1] => BlogCategory::create([
            'name' => $c[0], 'slug' => $c[1], 'sort_order' => $i + 1, 'is_active' => true,
        ])]);

        $posts = [
            ['Ön Camdaki Taş İzi Tamir Edilir mi?', 'on-camdaki-tas-izi-tamir-edilir-mi', 'cam-onarimi-ve-degisimi',
                'Ön camdaki taş izinin hangi durumlarda onarılabileceği ve ne zaman cam değişimi gerektiği.'],
            ['Ön Cam Değişimi Sonrası ADAS Kalibrasyonu Neden Önemli?', 'on-cam-degisimi-sonrasi-adas-kalibrasyonu-neden-onemli', 'adas-ve-surus-guvenligi',
                'Ön kamerası bulunan araçlarda cam değişiminden sonra kalibrasyonun sürüş destek sistemlerine etkisi.'],
            ['Kaskodan Cam Değişimi Nasıl Yapılır?', 'kaskodan-cam-degisimi-nasil-yapilir', 'sigorta-ve-kasko',
                'Kasko kapsamında cam değişimi sürecinde izlenen adımlar ve gerekli bilgiler.'],
            ['Araç Cam Filmi Seçerken Nelere Dikkat Edilmeli?', 'arac-cam-filmi-secerken-nelere-dikkat-edilmeli', 'cam-filmi',
                'Cam filmi seçiminde ışık geçirgenliği, mevzuat ve kullanım ihtiyaçları.'],
        ];

        foreach ($posts as [$title, $slug, $category, $excerpt]) {
            BlogPost::create([
                'blog_category_id' => $categories[$category]->id,
                'title' => $title,
                'slug' => $slug,
                'excerpt' => $excerpt,
                'content' => '<p><strong>TASLAK:</strong> Bu yazının metni henüz hazırlanmadı. Kaynak dokümanda makale metni bulunmadığı için içerik eklenmemiştir. Metin eklenip kontrol edildikten sonra durumu "Yayında" olarak değiştirin.</p>',
                'status' => BlogPost::STATUS_DRAFT,
                'published_at' => null,
                'seo_title' => $title,
                'seo_description' => $excerpt,
            ]);
        }
    }

    /**
     * Canlı sitedeki (referans HTML'de tespit edilen) eski adreslerin yeni adreslere 301 yönlendirmesi.
     * /tr/hizmet/{slug} ve /tr/sayfa/{slug}-{id} kalıpları ayrıca RedirectResolver tarafından çözülür.
     */
    private function redirects(): void
    {
        $map = [
            'tr' => '/',
            'en' => '/',
            'tr/hizmetler' => 'hizmetler',
            'tr/hizmet/binek-arac-cam-degisimi' => 'binek-arac-cam-degisimi',
            'tr/hizmet/ticari-arac-cam-degisimi' => 'ticari-arac-cam-degisimi',
            'tr/hizmet/traktor-ve-is-makinesi-cam-degisimi' => 'traktor-ve-is-makinesi-cam-degisimi',
            'tr/hizmet/dikiz-ve-yan-ayna-onarimi' => 'dikiz-ve-yan-aynalar-onarim-ve-degisimleri',
            'tr/sayfa/hakkimizda' => 'hakkimizda',
            'tr/sayfa/hakkimizda-16' => 'hakkimizda',
            'tr/sayfa/vizyonumuz' => 'vizyonumuz',
            'tr/sayfa/vizyonumuz-12' => 'vizyonumuz',
            'tr/galeri' => 'galeri',
            'tr/galeri/kral-oto-cam-galerisi' => 'galeri',
            'tr/galeri/kral-oto-cam-galeri' => 'galeri',
            'tr/videolar' => 'galeri',
            'tr/haberler' => 'blog',
            'tr/iletisim' => 'iletisim',
        ];

        foreach ($map as $from => $to) {
            Redirect::query()->updateOrCreate(['from_path' => $from], ['to_path' => $to, 'status_code' => 301, 'is_active' => true]);
        }
    }
}

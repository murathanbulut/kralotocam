# Kral Oto Cam – Kurumsal Web Sitesi (Laravel)

**kralotocam.com** için Laravel + Filament ile geliştirilmiş, yönetim panelli kurumsal web sitesi.
Ön yüz, `referanslar/` klasöründeki mevcut sitenin tema dosyalarına (koyu tema, kırmızı `#B90808`,
Sora / Playfair Display / DM Sans yazı tipleri, `wptb-*` bileşenleri) sadık kalınarak Bootstrap 5 ile hazırlanmıştır.

> Tüm çalışma **yalnızca lokal ortamda** yapılmıştır. Canlı siteye, sunucuya, hosting hesabına veya uzak Git
> depolarına hiçbir müdahale yapılmamıştır. `referanslar/` klasöründeki dosyalar değiştirilmemiş/silinmemiştir.

---

## 1. Hızlı Başlangıç

Gereksinimler: PHP 8.3+ (`pdo_sqlite`, `intl`, `fileinfo`, `zip`), Composer 2, Node.js 20+ / npm.
Geliştirme ortamında doğrulanan sürümler: PHP 8.4.18, Composer 2.8.9, Node 24.12, npm 11.6.

```bash
composer install
```

```bash
cp .env.example .env
```

```bash
php artisan key:generate
```

`.env` içinde ilk yönetici hesabı için `ADMIN_EMAIL` ve `ADMIN_PASSWORD` değerlerini doldurun
(kaynak kodda sabit kimlik bilgisi yoktur; boş bırakırsanız aşağıdaki `kral:admin` komutunu kullanın).

```bash
php artisan migrate --seed
```

```bash
php artisan storage:link
```

```bash
npm install
```

```bash
npm run build
```

```bash
php artisan serve
```

- Site: http://127.0.0.1:8000/
- Yönetim paneli: http://127.0.0.1:8000/admin

Yönetici hesabını etkileşimli oluşturmak / parolasını değiştirmek için:

```bash
php artisan kral:admin
```

Geliştirme sırasında CSS/JS değişikliklerini anında görmek için `npm run build` yerine `npm run dev` çalıştırabilirsiniz.

Testler:

```bash
php artisan test
```

### MySQL kullanmak isterseniz

Varsayılan veritabanı SQLite'tır (`database/database.sqlite`). XAMPP/MariaDB için `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kralotocam
DB_USERNAME=root
DB_PASSWORD=
```

ardından `php artisan migrate --seed`.

### E-posta (SMTP)

`MAIL_MAILER=log` iken iletişim formu mesajları yalnızca veritabanına kaydedilir; **e-posta gönderilmiş gibi
davranılmaz** (panelde "E-posta bildirimi: Gönderilmedi" görünür). Gerçek bildirim için `.env` içinde
`MAIL_MAILER=smtp`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS` ve
`CONTACT_MAIL_TO` değerlerini girin.

---

## 2. Referans Analizi Özeti

`referanslar/` klasöründe canlı sitenin `/assets/css/` dizin listesi ve ana sayfanın kaydedilmiş HTML'i
("Kurumsal Web Sitesi - 216.html") bulunuyor. Site, hazır bir kurumsal tema scripti üzerine kurulmuş;
ana sayfadaki içeriklerin bir kısmı Kral Oto Cam'e ait, bir kısmı tema demosuna ait.

| Durum | Dosyalar / içerik |
|---|---|
| **Kullanıldı** | `global, header, footer, components, blog, sidebar, portfolio, style, responsive, animation` CSS'leri (`*-css.txt` → `resources/css/theme/`), `logo.png`, 6 gerçek atölye görseli (1672×941 PNG → 1600px JPG'ye sıkıştırıldı), 2 kırmızı hizmet ikonu (SVG), dekoratif katmanlar (`layer-3`, `texture-4/5`, `light-2-light`), 4 hizmet kartı metni, slider başlıkları, işletme iletişim bilgileri, eski URL yapısı |
| **Eksik** | `bootstrap.min.css`, `contact.css`, `shop.css`, `preloader.css` (boş), Swiper/Fancybox CSS, bootstrap-icons font dosyaları, canlı slider görselleri (uzak URL), `bg-5.png`, `../img/*` görselleri, SEO/kurumsal içerik dokümanı, 3 hizmetin ikon/görseli |
| **Demo / ilgisiz (kullanılmadı)** | "Kimono"/fotoğrafçılık metinleri, `kresim1.png` (fotoğraf stüdyosu), "ABC Şirketi" müşteri yorumları ve kişi fotoğrafları, haberler, Instagram demoları (`php*.jpg`, `studio_demosayfa`), `footerlogo.svg` ("KIMONO"), "Lostik LTD ŞTI." telif satırı, Projeler/Kadromuz/Bayilik/Hesap No menüleri, İngilizce dil seçici |
| **Eksik kaynaklar nasıl çözüldü** | Bootstrap 5.3, Bootstrap Icons, Swiper 11, fslightbox npm paketlerinden; yazı tipleri derleme sırasında yerel olarak (Bunny Fonts) indirilir; eksik `../img` referansları CSS'te devre dışı bırakıldı |

Tespit edilen tutarsızlık: canlı sitede **"Araç Yan Cam Filmi Uygulaması"** başlığıyla yayınlanan görsel
(`MTZhZjE5MTc4Y2Y2M2M.png`) aslında **yan ayna değişimini** gösteriyor. Bu görsel "Dikiz ve Yan Ayna" albümüne
"Yan Ayna Değişimi" adıyla alındı; Cam Filmi için gerçek görsel bulunmuyor.

---

## 3. Teknik Altyapı

| Katman | Kullanılan |
|---|---|
| Framework | Laravel 13.40 (PHP 8.4) |
| Yönetim paneli | Filament 5.11 (Livewire 4) – `/admin` |
| Veritabanı | SQLite (lokal), MySQL/MariaDB uyumlu |
| Ön yüz | Blade layout + component'ler, Bootstrap 5.3, referans tema CSS'i, Vanilla JS |
| Kütüphaneler | Swiper 11 (slider), fslightbox (galeri lightbox), Bootstrap Icons |
| Derleme | Vite 8 + laravel-vite-plugin (yazı tipleri yerel sunulur) |

Ön yüzde Tailwind **kullanılmadı**; Filament kendi panel stillerini kullanır.

### Önemli dizinler

```
app/Http/Controllers/        Home, Slug (hizmet + kurumsal), Service, Gallery, Blog, Contact, Seo
app/Http/Middleware/EnsureTrailingSlash.php   /iletisim -> /iletisim/ (301)
app/Support/Seo.php          title/description/canonical/OG/JSON-LD/breadcrumb
app/Support/RedirectResolver.php  eski canlı adreslerin 301 yönlendirmesi
app/Filament/                yönetim paneli kaynakları, Site Ayarları sayfası, panel widget'ı
app/helpers.php              site_url(), setting(), media_url(), phone_href(), whatsapp_url()
resources/css/theme/         referans tema CSS dosyaları (değiştirilmeden kopyalandı)
resources/css/kral.css       Kral Oto Cam uyarlamaları
resources/js/app.js          menü, yapışkan header, slider, lightbox, görünme animasyonları
resources/views/components/  layouts.app, page-heading, service-card, blog-card, gallery-item, contact-cta…
database/seeders/            hizmet, kurumsal sayfa, menü, slider, galeri, blog (taslak), yönlendirme verileri
database/seeders/images/     referans görsellerinden üretilen web görselleri
public/images/theme/         logo, favicon (logodan kırpıldı), tema ikon/katmanları
```

---

## 4. Sayfalar ve URL Yapısı

Kanonik adresler sonu `/` ile biter; eğik çizgisiz istekler 301 ile yönlendirilir.

| Sayfa | URL |
|---|---|
| Ana Sayfa | `/` |
| Hizmetler (liste) | `/hizmetler/` |
| Binek Araç Cam Değişimi | `/binek-arac-cam-degisimi/` |
| Ticari Araç Cam Değişimi | `/ticari-arac-cam-degisimi/` |
| Traktör ve İş Makinesi Cam Değişimi | `/traktor-ve-is-makinesi-cam-degisimi/` |
| Oto Cam Onarım ve Tamiri | `/oto-cam-onarim-ve-tamiri/` |
| ADAS ve Kamera Kalibrasyonu | `/adas-ve-kamera-kalibrasyonlari/` |
| Cam Filmi | `/cam-filmi/` |
| Dikiz ve Yan Ayna Onarımı ve Değişimi | `/dikiz-ve-yan-aynalar-onarim-ve-degisimleri/` |
| Hakkımızda / Misyonumuz / Vizyonumuz | `/hakkimizda/`, `/misyonumuz/`, `/vizyonumuz/` |
| Galeri / Albüm | `/galeri/`, `/galeri/{albüm}/` |
| Blog / Kategori / Yazı | `/blog/`, `/blog/kategori/{kategori}/`, `/blog/{yazı}/` |
| İletişim | `/iletisim/` |
| Sitemap / Robots | `/sitemap.xml`, `/robots.txt` |

**Eski canlı adresler (301):** `/tr/`, `/en/`, `/tr/hizmetler`, `/tr/hizmet/{slug}`, `/tr/sayfa/{slug}-{id}`,
`/tr/galeri…`, `/tr/videolar`, `/tr/haberler`, `/tr/iletisim`. Liste panelde **Ayarlar > Yönlendirmeler**
bölümünden yönetilir; `/tr/hizmet/*` ve `/tr/sayfa/*` kalıpları ayrıca otomatik çözülür.
Canlı sitedeki "dikiz-ve-yan-ayna-onarimi" adresi yeni "dikiz-ve-yan-aynalar-onarim-ve-degisimleri" adresine yönlenir.

### Ana sayfa bölümleri
Üst iletişim bandı → logo/menü → hero slider (3 slayt, ilki istenen metinle) → güven/avantajlar →
kurumsal tanıtım → 7 hizmet kartı → "Onarım mı, değişim mi?" bilgilendirme → 4 adımlı hizmet süreci →
galeri önizlemesi (lightbox) → blog önizlemesi → iletişim/WhatsApp çağrısı → footer + sabit telefon/WhatsApp butonları.

---

## 5. Yönetim Paneli (`/admin`)

| Grup | Bölüm |
|---|---|
| İçerik | Hizmetler (başlık, menü başlığı, slug, kısa giriş, detaylı içerik, kapak, ikon, galeri görselleri, SEO title/description/keywords, sıralama, aktif/pasif), Kurumsal Sayfalar |
| Ana Sayfa | Slider (küçük başlık, başlık, açıklama, görsel, ALT, 2 buton) |
| Galeri | Albümler (+ albüm içinden görsel ekleme), Galeri Görselleri (başlık, açıklama, ALT, albüm, sıralama) |
| Blog | Yazılar (kategori, yayın durumu, yayın tarihi – ileri tarihli yayın, SEO, taslak önizleme), Kategoriler |
| İletişim | Gelen Mesajlar (okunmamış rozeti, okundu işaretleme, silme; düzenleme yok) |
| Ayarlar | Site Ayarları (logo, favicon, iletişim, WhatsApp, harita, sosyal medya, SEO, ana sayfa metinleri), Menü, Yönlendirmeler |

- Yalnızca `is_admin = 1` kullanıcılar panele girebilir (diğerleri 403). Giriş denemeleri Filament tarafından hız sınırlıdır.
- Dosya yükleme: yalnızca JPG/PNG/WEBP, en fazla 4 MB (favicon: PNG/ICO, 512 KB). SVG yüklemesi XSS riski nedeniyle kapalıdır.
- Hizmet ve kurumsal sayfa slug'ları aynı kök adres alanını paylaştığı için çakışma ve sistem adresleri (`galeri`, `blog`, `admin`…) doğrulanır.
- Panel Türkçedir; birincil renk tema kırmızısıdır.

---

## 6. SEO

- Sayfa bazlı `title`, `meta description`, `keywords` (hizmetlerde), canonical, `robots`
- Open Graph + Twitter Card (varsayılan paylaşım görseli `public/images/seo/og-default.jpg`)
- JSON-LD: `AutoRepair` (LocalBusiness), `WebSite`, `Service`, `BlogPosting`, `BreadcrumbList`
- Görünür breadcrumb, sayfa başına tek H1 (ana sayfada ilk slayt başlığı), H2/H3 hiyerarşisi
- Dinamik `sitemap.xml` (aktif hizmetler, sayfalar, görselli albümler, yayındaki yazılar)
- `robots.txt`: **production** dışında tüm siteyi engeller (lokal/test ortamı dizine eklenmesin diye);
  `APP_ENV=production` olduğunda `/admin` hariç açılır ve sitemap'i bildirir
- Hizmetler arası iç bağlantılar (ilgili hizmetler, içerik içi bağlantılar, footer)
- Görsellerde anlamlı ALT metinleri, `loading="lazy"`, boyut öznitelikleri

---

## 7. Güvenlik ve Veri

- İletişim formu: FormRequest doğrulaması, HTML temizleme, **bal küpü** alanı, **zaman tuzağı** (şifreli
  zaman damgası; 3 sn'den hızlı gönderimler reddedilir), IP başına dakikada 3 / saatte 10 hız sınırı, CSRF, KVKK onay kutusu
- IP ve tarayıcı bilgisi yalnızca kötüye kullanım takibi için saklanır
- Yönetici parolası kaynak kodda yok; `.env` veya `php artisan kral:admin`
- Panelden girilen zengin metin içerikleri ön yüzde HTML olarak basılır; bu alanlar yalnızca yöneticiler tarafından düzenlenebilir

---

## 8. Testler

`php artisan test` → **27 test** (ön yüz, iletişim formu, yönetim paneli). Kapsananlar:
menü yapısı ve demo menülerin yokluğu, 7 hizmet sayfası (H1, breadcrumb, schema, canonical, iletişim butonu),
kurumsal sayfalar, 301 yönlendirmeler (sondaki "/" ve eski canlı adresler), 404'ler, galeri lightbox bağlantıları,
taslak/ileri tarihli/yayındaki blog yazıları ve yönetici önizlemesi, sitemap/robots, iletişim formu
(başarılı kayıt, SMTP yokken e-posta gönderilmemesi, SMTP varken bildirim, doğrulama, bal küpü, zaman tuzağı,
HTML temizleme, hız sınırı), panel erişim kontrolü, tüm panel sayfalarının açılması, ayar kaydetme,
hizmet düzenleme ve slug çakışma doğrulaması.

Tarayıcıda ayrıca kontrol edildi: masaüstü dropdown, mobil hamburger menü ve alt menüler, hero slider,
mobil/tablet/masaüstü görünümler (375 px'de yatay taşma yok), galeri lightbox, iletişim formu, panel girişi ve formları.

---

## 9. Eksikler ve Yapılması Gerekenler

**İşletme onayı / içerik gerekenler**
1. **SEO/kurumsal içerik dokümanı `referanslar/` klasöründe yok.** Hakkımızda, Misyonumuz, Vizyonumuz metinleri ile
   Oto Cam Onarım, ADAS, Cam Filmi hizmet metinleri yalnızca doğrulanmış bilgilerle ve genel ifadelerle yazıldı;
   kuruluş yılı, garanti, sigorta anlaşması, müşteri sayısı, yorum gibi doğrulanamayan bilgiler **eklenmedi**.
   Yayın öncesi işletme tarafından gözden geçirilmeli.
2. **Blog yazıları taslak:** 4 yazı (Taş İzi, ADAS Kalibrasyonu, Kaskodan Cam Değişimi, Cam Filmi) başlık ve özetle
   **taslak** olarak eklendi; makale metni olmadığı için yayında değiller. Metin eklenip "Yayında" yapılmalı.
3. **Çerez Politikası ve KVKK Aydınlatma Metni** sayfaları pasif taslak olarak oluşturuldu; hukuki metin sağlanmalı.
   Canlıdaki `/tr/sayfa/cerez-politikasi-19` adresi, sayfa yayına alınınca otomatik olarak yeni adrese yönlenir.
4. **Çalışma saatleri** ve **sosyal medya hesapları** doğrulanmadığı için boş (sitede gösterilmiyor). Canlı sitedeki
   sosyal medya bağlantıları yalnızca `facebook.com/`, `instagram.com/` gibi genel adreslerdi.
5. Google Haritalar konumu adres aramasıyla gösteriliyor; işletmenin Google İşletme Profili bağlantısı varsa
   Site Ayarları > İletişim bölümüne girilmeli.

**Görsel eksikleri**
6. Ticari Araç, Traktör/İş Makinesi ve Cam Filmi için gerçek uygulama görseli yok (ilgili galeri albümleri
   görsel eklenene kadar ön yüzde gizli). Canlı slider görselleri referansta bulunmadığından slider'da mevcut
   atölye görselleri kullanıldı.
7. Mevcut 6 atölye görseli yapay zekâ ile üretilmiş görünüyor; gerçek atölye fotoğraflarıyla değiştirilmesi önerilir.

**Teknik notlar**
8. İngilizce sürüm (`/en/`) kapsam dışıdır; eski `/en/` adresi ana sayfaya yönlenir.
9. Canlı sitedeki demo içerikli sayfalar (Projeler, Kadromuz, Bayilik, Hesap No, SSS, Referanslar, Müşteri Yorumları)
   taşınmadı; bu adresler 404 döner. Gerekirse panelden yönlendirme eklenebilir.
10. Derlenmiş CSS ~590 KB (gzip ~90 KB); büyük kısmı referans temanın `components.css` dosyasıdır. Kullanılmayan
    tema kurallarının ayıklanması ileride performans için faydalı olur.
11. Yayına alırken: `APP_ENV=production`, `APP_DEBUG=false`, doğru `APP_URL`, SMTP ayarları, `php artisan storage:link`,
    `npm run build`, `php artisan optimize`. Sunucu LiteSpeed/Apache ise `public/.htaccess` kullanılır
    (Laravel'in "sondaki eğik çizgiyi kaldır" kuralı kanonik adreslerle çakıştığı için bilinçli olarak çıkarıldı).
12. Laravel iskeletiyle gelen `CLAUDE.md` / `AGENTS.md` dosyaları Laravel Boost kurulumunu öneriyor; bu proje için
    gerekli değildir, isterseniz silebilirsiniz.

---

## 10. Lisans Notları

- **Referans tema:** "Kurumsal Web Sitesi - 216" adlı hazır tema scriptinin CSS dosyaları ve dekoratif görselleri
  yeniden kullanıldı. **Bu tema dosyalarının kullanım hakkı (lisans) proje kapsamında doğrulanamadı**; tema
  satıcısından/önceki ajanstan lisansın teyit edilmesi gerekir. CSS dosyaları içeriği değiştirilmeden
  `resources/css/theme/` altına alındı (yalnızca referansta olmayan 4 görsel `url()` bağlantısı devre dışı bırakıldı).
- **Font Awesome 5 Pro** (ticari lisans) referans sitede kullanılıyordu; lisans doğrulanamadığından **kaldırıldı**,
  yerine Bootstrap Icons (MIT) kullanıldı.
- **jQuery Fancybox v3** (GPLv3/ticari) yerine **fslightbox** (MIT) kullanıldı. WOW.js yerine küçük bir
  IntersectionObserver betiği yazıldı.
- Bootstrap, Bootstrap Icons, Swiper, fslightbox: MIT. Yazı tipleri (Sora, Playfair Display, DM Sans): SIL Open Font License.
- Laravel ve Filament: MIT.
- Logo ve atölye görselleri Kral Oto Cam'e aittir.

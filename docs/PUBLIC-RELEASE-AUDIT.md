# Public GitHub Yayını — Lisans ve Dağıtım Hakkı Denetimi

Tarih: 10.11.2026 · Denetlenen sürüm: `feature/faq-legal-home-content` (`cdaacf7`)
Bu belge yalnızca tespit ve plan içerir. Kod, tasarım, Git geçmişi veya uzak depo değiştirilmedi.

> Not: Bu bir teknik denetimdir, hukuki görüş değildir. "Belirsiz" olarak işaretlenen her dosya için
> hak sahibinden yazılı izin veya lisans belgesi alınmadan public dağıtım yapılmamalıdır.

---

## 1. Lisansı doğrulanan dosyalar (public dağıtıma uygun)

### Kendi kodumuz (Alpha Creative)
Laravel uygulama kodu (`app/`, `routes/`, `config/`, `database/migrations`, `database/seeders/*.php`, `tests/`),
Blade şablonlarının mantığı, `resources/css/kral.css`, `resources/css/features/*`, `resources/css/light/*`,
`resources/js/app.js` ve `resources/js/features/*`, `scripts/visual/*`.

- JS: Referans `theme.js` (jQuery) ve `cursor-effect.js` davranışları vanilla JS ile yeniden yazılmış.
  Satır karşılaştırmasında yalnızca `return;`, `} else {`, `e.preventDefault();` gibi genel ifadeler ortak; kopya kod tespit edilmedi.
- Not: Blade şablonları referans HTML yapısını ve `wptb-*` sınıf adlarını kullanıyor (13 dosya, 97 sınıf kullanımı).
  Sınıf adları tek başına düşük risklidir, ancak bölüm 3'teki yeniden yazımda bunlar da değiştirilmelidir.

### npm paketleri (yalnızca `package.json` ile; `node_modules` Git dışı)
| Paket | Sürüm | Lisans | Kullanım |
|---|---|---|---|
| bootstrap | 5.3.8 | MIT | Izgara ve bileşenler |
| bootstrap-icons | 1.13.2 | MIT | İkonlar |
| swiper | 11.2.10 | MIT | Hero slider |
| fslightbox | 3.8.0 | MIT (basic) | Galeri lightbox |
| vite, laravel-vite-plugin, concurrently, pngjs | — | MIT | Derleme ve geliştirme |
| pixelmatch | 7.2.0 | ISC | Görsel regresyon (geliştirme) |
| puppeteer-core | 24.43.1 | Apache-2.0 | Görsel regresyon (geliştirme) |

### Composer paketleri (üretim)
101 paket MIT; 6 paket BSD-3-Clause (league/commonmark, vlucas/phpdotenv vb.); phpoption Apache-2.0;
chillerlan/php-qrcode MIT/Apache-2.0; nette/* BSD-3-Clause/GPL (çoklu lisans, BSD seçilebilir).
Laravel ve Filament: MIT. `vendor/` Git dışıdır; lisans bildirimleri paketlerin içinde korunur.

### Yazı tipleri
Sora, Playfair Display, DM Sans — SIL Open Font License 1.1. Derleme sırasında indirilip `public/build`'e konuyor;
`public/build` Git dışı, depoda font dosyası yok.

### İşletmeye ait içerik (işletme onayıyla yayınlanabilir)
- `public/images/theme/logo.png`: Kral Oto Cam logosu (canlı siteden; referanstaki `logo.png` ile birebir aynı).
  Telif ve marka işletmeye aittir; public depoda bulunması için işletmenin onayı yeterlidir.
- `public/images/theme/favicon.png`: Logodan kırpıldı (aynı koşul).
- Hizmet metinleri, iletişim bilgileri, sigorta/filo listeleri ve SSS metinleri: işletmenin onayladığı içerik.

---

## 2. Yayın hakkı belirsiz dosyalar (public dağıtımı ENGELLER)

### 2.1 `referanslar/` — 76 dosya, ~16 MB (ilk commit `3381703`'ten beri her commit'te)
Kayıtlı tema demo sayfası ("Kurumsal Web Sitesi - 216"), tema CSS/JS'leri (`main.css`, `theme.js`, `cursor-effect.js` vb.),
demo görselleri, bir `.zip` ve **Font Awesome Pro 5.14.0** (`fontAwesome5Pro.css`).
Font Awesome Pro'nun dosya başlığında "Commercial License" yazıyor; bu dosya **kesin olarak** dağıtılamaz.
Klasörde hiçbir lisans dosyası yok. Dosyalar bir dizin listesinden ("Index of /assets/css/") kaydedilmiş görünüyor;
bu, bir dağıtım izni anlamına gelmez.

### 2.2 `resources/css/theme/` — 10 dosya, ~16.000 satır: TİCARİ TEMA KAYNAK KODU (lisansı belirsiz)
Referans `*-css.txt` dosyalarıyla satır satır karşılaştırıldı:

| Dosya | Satır | Referanstan farklı satır | Sitede eşleşen kural |
|---|---|---|---|
| components.css | 6.609 | 1 | 109 / 925 |
| global.css | 2.685 | 0 | 47 / 586 (renk/yazı değişkenleri dahil) |
| style.css | 1.616 | 3 | 15 / 248 |
| animation.css | 1.292 | 0 | 0 / 143 kural + 53 keyframes (**fadeInUp, ken-burns, spin, dotAnimatedBottomToTop kullanılıyor**) |
| header.css | 1.229 | 0 | 77 / 172 |
| portfolio.css | 772 | 0 | 6 / 142 |
| blog.css | 721 | 0 | 1 / 102 |
| sidebar.css | 399 | 0 | 6 / 57 |
| responsive.css | 344 | 0 | 18 / 68 |
| footer.css | 313 | 0 | 16 / 50 |
| **Toplam** | **~16.000** | **4** | **295 / 2.493 (%12)** |

Ölçüm yöntemi: 12 sayfa × masaüstü (1440px) ve mobil (390px), her seçici için DOM'da eşleşen öğe var mı.

**Tasarıma etkisi:** Sitenin iskeleti bu dosyalara dayanır: header ve dropdown menüler, mobil menü paneli,
hero slider düzeni ve ken-burns efekti, bölüm başlıkları (`wptb-heading`), "Devamı" yuvarlak butonu
ve `btn-two` animasyonlu butonlar, hizmet kartı yapısı (`wptb-icon-box6`), footer yerleşimi,
sayfa başlığı (page heading), blog kartları, kenar çubuğu, akordeon görünümü,
renk/yazı tipi değişkenleri (`:root`) ve kaydırınca görünme animasyonları.
Altın sarısı renk sistemi ve sonradan yapılan revizyonlar `kral.css`'te (bizim kodumuz); ancak tema dosyaları
çıkarılırsa site temel düzenini kaybeder.

### 2.3 `public/images/theme/` — tema dekor görselleri (lisansı belirsiz)
| Dosya | Kaynak | Kullanım |
|---|---|---|
| `layer-3.png` | Referanstaki dosyayla birebir aynı | Sayfa başlığı dekor katmanı |
| `light-2-light.png` | Birebir aynı | Kurumsal bölüm ışık efekti (CSS ile altına çevrilmiş) |
| `texture-4.png` | Birebir aynı | Arka plan dokusu |
| `texture-5.png` | Birebir aynı | Arka plan dokusu |
| `icon-ticari-arac.svg`, `icon-is-makinesi.svg` | Referanstaki `MTY2ZTQ0*.svg` hizmet ikonlarından yeniden renklendirildi | Ticari araç ve iş makinesi hizmet ikonları |

### 2.4 `database/seeders/images/` ve `public/images/seo/` — fotoğraflar (hak sahibi teyit edilmeli)
| Dosya | Kaynak |
|---|---|
| `binek-arac-on-cam-montaji.jpg` | Canlı sitedeki atölye görseli (1672×941 PNG → 1600px JPG) |
| `arac-cami-hasar-degerlendirmesi.jpg` | Canlı site atölye görseli (aynı işlem) |
| `on-cam-tas-izi-onarimi.jpg` | Canlı site atölye görseli |
| `adas-kalibrasyon-sistem-kontrolu.jpg` | Canlı site atölye görseli |
| `adas-kamera-kalibrasyon-ekipmanlari.jpg` | Canlı site atölye görseli |
| `yan-ayna-degisimi.jpg` | Canlı site görseli (canlıda "cam filmi" başlığıyla yayınlanmıştı) |
| `public/images/seo/page-heading.jpg` | `arac-cami-hasar-degerlendirmesi.jpg` ile aynı dosya |
| `public/images/seo/og-default.jpg` | Atölye görsellerinden üretildi |

Bu fotoğrafların işletmeye mi, tema/ajans sağlayıcısına mı, bir stok kaynağına mı ait olduğu (veya yapay zekâ ile mi
üretildiği) bilinmiyor. Canlı sitede yayınlanıyor olmaları public depoda dağıtım hakkı anlamına gelmez.

### 2.5 `docs/baseline-screenshots/` — 11 ekran görüntüsü
Tema tasarımını ve 2.4'teki fotoğrafları içeriyor; aynı belirsizlik geçerli.

---

## 3. Değiştirilmesi gereken bileşenler

| # | Bileşen | Neden | Özgün alternatif |
|---|---|---|---|
| 1 | `resources/css/theme/*` (kullanılan ~295 kural + ~10 keyframes) | Ticari tema kodu | Sıfırdan yazılmış `resources/css/base/*` (`kral-*` sınıfları) |
| 2 | Blade'deki `wptb-*` yapıları (13 dosya) | Temanın HTML iskeleti | Aynı görünümü veren kendi bileşen yapımız |
| 3 | `layer-3`, `light-2-light`, `texture-4/5` | Tema görselleri | CSS gradyanları ve kendi çizdiğimiz SVG desenleri |
| 4 | `icon-ticari-arac.svg`, `icon-is-makinesi.svg` | Tema ikonlarından türetilmiş | bootstrap-icons (`bi-truck`, `bi-cone-striped` vb.) veya yeniden çizilmiş SVG |
| 5 | 6 fotoğraf + seo görselleri | Hak sahibi belirsiz | İşletmenin kendi çektiği fotoğraflar veya lisanslı stok; public depoda yer tutucu |
| 6 | `docs/baseline-screenshots/` | 2.4'teki fotoğraflar | Public pakete alınmaz |

**Gerçekten özgün yeniden yazılabilecek bileşenler** (kendi kodumuzla düşük riskle):
header ve dropdown, mobil menü paneli, bölüm başlığı, `btn-two` buton animasyonu, "Devamı" yuvarlak butonu,
hizmet kartı, sayfa başlığı, footer ızgarası, blog kartı, kenar çubuğu, akordeon, kaydırınca görünme animasyonları
(fadeInUp), ken-burns slider efekti. Bunlar standart CSS teknikleriyle (flex/grid, transition, keyframes)
yazılabilir; ayırt edici sanat eseri içermezler.

**Yeniden yazılamayıp değiştirilmesi gerekenler:** dekor görselleri (yeni tasarım gerekir) ve fotoğraflar (yeni kaynak gerekir).

---

## 4. Tasarımı koruma stratejisi

### Aşama A — Hemen yayın: "kod public, lisanslı varlıklar private"
Tasarım ve çalışan fonksiyonlar hiç değişmeden public yayın yapılabilir:

1. Tema CSS'leri, dekor görselleri, türetilmiş SVG'ler ve fotoğraflar **özel bir "tema paketi"** olarak ayrılır
   (ayrı private GitHub deposu veya sunucuya elle kopyalanan zip).
2. Public depo bu dosyaları içermez. `README` canlı/kurulum ortamında tema paketinin nasıl ekleneceğini anlatır.
3. Tema paketi olmadan derlemenin kırılmaması için `app.css` tema içe aktarmalarını
   isteğe bağlı bir yoldan alır (paket yoksa düz ama çalışan bir görünüm). Bu, siteye dokunmayan küçük bir derleme değişikliğidir.
4. Canlı sitede tema paketi kurulu olacağı için görünüm bugünkünün aynısı kalır.

### Aşama B — Kalıcı çözüm: özgün yeniden yazım (temiz oda yöntemi)
1. **Spesifikasyon:** Kullanılan 295 kuralın *görsel sonucu*, tema kaynağı okunmadan,
   canlı sayfadaki ölçülerden (hesaplanan stiller ve ekran görüntüleri) bir tasarım spesifikasyonuna dökülür
   (boşluklar, yazı boyutları, renkler, kırılma noktaları, animasyon süreleri).
2. **Yazım:** CSS bu spesifikasyondan, yeni dosyalarda (`resources/css/base/*`) ve yeni sınıf adlarıyla sıfırdan yazılır.
   Tema dosyalarından satır kopyalanmaz, sadece sınıf adı veya değer değiştirilerek "yeniden yazılmış" sayılmaz.
3. **Doğrulama:** Mevcut `scripts/visual` (puppeteer + pixelmatch) ile 11 temel ekranda
   ve masaüstü/mobilde önce-sonra karşılaştırması yapılır; fark eşiği kabul edilene kadar ayarlanır.
4. **Geçiş:** Bileşen bileşen, ayrı commit'lerle ilerlenir (header → hero → başlıklar/butonlar → kartlar → footer → iç sayfalar).
   Her adımda testler ve görsel karşılaştırma çalışır; tema dosyası ancak hiçbir kural kullanılmadığında devreden çıkar.
5. **Görseller:** Dekor katmanları CSS/SVG ile yeniden üretilir. Fotoğraflar işletmenin onayladığı kaynaklarla değiştirilir.

Tahmini kapsam: ~300 kural ve ~10 animasyon, 13 Blade dosyası, 4 dekor görseli, 2 ikon.

---

## 5. GitHub'a güvenli yayın için sonraki adımlar

1. **Karar:** Aşama A ile hemen mi yayınlanacak, yoksa Aşama B bitene kadar mı beklenecek?
2. **Geçmiş korunarak yayın (geçmiş yeniden yazılmaz):** Yerel 32 commit, 4 branch ve 4 tag olduğu gibi kalır.
   Public depo için ayrı, geçmişsiz (orphan) bir yayın branch'i oluşturulur.
   Bu branch yalnızca yayına uygun dosyaları tek bir ilk commit olarak içerir:
   `referanslar/`, tema paketi, fotoğraflar ve `docs/baseline-screenshots/` hariç.
   Remote şu an boş olduğu için force push gerekmez. Yerel geçmiş hiçbir zaman public depoya gönderilmez.
3. **Yayın öncesi otomatik kontrol:** Yayın branch'inde
   `git ls-files | grep -E 'referanslar|theme/|seeders/images|baseline-screenshots|\.env$|\.sqlite'` boş dönmeli
   ve bir gizli anahtar taraması (ör. gitleaks) temiz geçmelidir.
4. **İşletmeden alınacak onaylar:**
   - Logo ve favicon'un public depoda bulunması.
   - 6 atölye fotoğrafının sahibi ve kullanım hakkı.
   - Tema satın alındıysa lisans belgesi; kaynak kodun public paylaşımına izin veriyorsa Aşama B gerekmez.
5. **Anahtar ve şifre:** Lokal `php artisan key:generate`; yönetici şifresi `php artisan kral:admin` ile
   işletme tarafından yenilenir; canlı sunucuda ayrı APP_KEY kullanılır.
6. **README** public sunuma göre güncellenir: Kral Oto Cam, geliştirici Alpha Creative, teknolojiler,
   tema paketi kurulumu, test komutları.
7. Tüm kontroller ve testler geçince kullanıcı onayıyla `main` push edilir.

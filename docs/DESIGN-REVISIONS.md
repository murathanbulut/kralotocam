# Tasarım Revizeleri (renk, topbar, footer, galeri)

Mevcut tasarım korunarak yapılan altı revize. Yerleşim, animasyonlar, slider, header, hizmet bölümleri,
Filament paneli ve içerikler değiştirilmedi.

## Git

| | |
|---|---|
| Revize öncesi kontrol noktası | `checkpoint/before-design-revisions` etiketi → `456e15d` (`feature/visual-enhancements` dalının son hali) |
| Revize dalı | `feature/kralotocam-design-revisions` (yalnızca lokal; push ve merge yapılmadı) |
| Yedek | `backups/20261110-115144/` (veritabanı, yüklenen dosyalar, `.env` kopyası; Git dışı) |
| Referans görüntüler | `docs/revision-baseline-screenshots/` (15 görünüm, Git dışı) |

| Revize | Commit | |
|---|---|---|
| — | `4d89d23` | chore: galeri albümü ve topbar görünümleri görsel kontrole eklendi |
| 1 | `6a26ea8` | style: make topbar transparent |
| 2 | `7fe78e1` | ui: hide theme toggle |
| 3 | `7ba070e` | style: unify brand gold accents |
| 3 (düzeltme) | `af70364` | fix: keep section deco letters readable over the gold band |
| 4 | — | Ayrı commit gerekmedi (aşağıya bakın) |
| — | `c74372f` | chore: ekran görüntüsü betiği header durumunun oturmasını bekliyor |
| 5 | `97bff41` | content: simplify footer credit |
| 6 | `2edd35e` | feat: gallery horizontal showcase |
| — | `18b759c` | chore: track empty tests/Unit directory |

## Revizeler

1. **Saydam topbar.** Siyah zemin temanın `.header .header-top { background-color: var(--color-black) }` kuralından
   geliyordu; yalnızca bu zemin `kral.css` içinde `.header .header-top.kral-topbar { background: transparent }` ile
   geçersiz kılındı. Yükseklik, padding, alt çizgi ve HTML aynı; fark yalnızca topbar şeridinde (y 0–29).
2. **Tema düğmesi gizlendi.** `KRAL_FX_THEME_TOGGLE=false` (varsayılan). Düğme topbar'da ve mobil menüde gösterilmez;
   daha önce kaydedilmiş açık tema tercihi uygulanmaz, herkes koyu temayla açar. Açık tema CSS/JS'i ve localStorage
   kaydı korunur — tekrar kullanmak için `.env` içinde `KRAL_FX_THEME_TOGGLE=true` + `php artisan config:clear`.
3. **Altın sarısı vurgu.** Logodaki baskın altın: **`#FDC300`** (rgb 253, 195, 0) — `referanslar/.../logo.png`
   piksel analizi (en sık ton, 259 piksel). Tek merkez: `resources/css/kral.css` → `:root { --kral-accent: #FDC300 }`.
   Temanın `--color-one`, `--link-color-hover` ve eski `--kral-gold` bu değişkene bağlı.
   Altın zemin üzerindeki metin/ikonlar koyu (`--kral-accent-contrast: #151515`); ölçülen kontrast 10,6–18,3:1.
   Hata/uyarı kırmızıları (form hataları, alert-danger) korundu. Fotoğraflara dokunulmadı; yalnızca iki arayüz ikonu
   (`public/images/theme/icon-*.svg`) altına boyandı ve dekoratif parıltı katmanı CSS filtresiyle altına çevrildi.
   Rengi değiştirmek için tek satır yeterli: `--kral-accent` ve `--kral-accent-rgb`.
4. **"Her Çatlak Cam Değişimi Gerektirmez".** Bölümdeki kırmızı detaylar (vurgulu kart kenarlığı, ikonlar, bağlantılar,
   başlık bandı) merkezî değişkene bağlı olduğu için Revize 3 ile altına döndü; kart boyutu, gölge ve yerleşim aynı.
   Tarayıcıda bütün sayfaların hesaplanmış CSS renkleri tarandı: kırmızı vurgu kalmadı. Değişiklik gerektirmediği için
   boş commit açılmadı; bu bölümün renkleri Revize 3 ile birlikte geri alınır.
5. **Footer.** "Alpha Creative tarafından yapılmıştır" → "Alpha Creative"; bağlantı korundu.
6. **Galeri vitrini.** `/galeri/` tüm aktif görselleri tek yatay vitrinde (kartlarda albüm adı), her albüm sayfası
   kendi görsel grubunu gösterir; albüm filtresi korundu ("Tümü" eklendi). Dosyalar:
   `resources/views/components/gallery-showcase.blade.php`, `resources/js/features/gallery-showcase.js`,
   `resources/css/features/gallery-showcase.css`.

   Swiper yapılandırması: `slidesPerView: 'auto'` (kart genişliği `min(560px, 82vw)` → masaüstünde 2 tam + 1 kısmi kart),
   `spaceBetween` 16 / 24 (768px+), Navigation (altın oklar), Pagination `progressbar`, Keyboard, A11y (Türkçe),
   `grabCursor`, `watchOverflow` (kaydırılacak görsel yoksa oklar gizlenir), `centerInsufficientSlides`,
   `loop: false` (lightbox her görseli bir kez görsün). Karta tıklayınca mevcut fslightbox o görselle açılır.
   Yalnızca yönetim panelindeki aktif kayıtlar kullanılır; boş albümler gösterilmez, yer tutucu görsel eklenmez.

## Geri dönüş

- Revize öncesine dönmek (veri silinmez):

  ```bash
  git switch feature/visual-enhancements
  ```

  ```bash
  npm run build
  ```

- Tek bir revizeyi geri almak, örn. galeri:

  ```bash
  git revert 2edd35e
  ```

  ardından `npm run build`. Renk revizesini geri alırken düzeltme commit'iyle birlikte alın:
  `git revert af70364 7ba070e`.
- Veritabanı/yüklemeler gerekirse `backups/20261110-115144/` içinden geri kopyalanabilir.

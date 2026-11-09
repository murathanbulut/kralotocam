# Görsel Geliştirmeler – Açma/Kapama ve Geri Alma Rehberi

Mevcut koyu tasarım korunarak eklenen beş isteğe bağlı geliştirme. Hepsi birbirinden bağımsızdır.

## Git durumu

| | |
|---|---|
| Başlangıç noktası (çalışan site) | `3381703` – `main` dalı |
| Geliştirme dalı | `feature/visual-enhancements` (yalnızca lokal, push edilmedi, `main`'e merge edilmedi) |
| Araçlar + referans görüntüler | `c8b9f3b` chore: add visual regression tooling, baseline screenshots and feature flags |
| Ortak yükleyici | `dfd032e` refactor: auto-load self-contained visual feature modules |
| A. Slider yazı animasyonları | `0e91440` feat: add slider text transitions |
| B. İmleç efekti | `b923854` feat: add cursor follower |
| C. Kaydırmaya duyarlı header | `0fb6352` feat: add scroll-aware header |
| D. Görselli hizmet kartları | `fd8e7a2` feat: add service card images |
| E. İsteğe bağlı açık tema | `ba430b0` feat: add optional light theme |

Ayrıca: `backup/visual-enhancements-v1` etiketi, özelliklerin ilk (birbirine bağımlı) sürümünü saklar.
Veritabanı, yüklenen görseller ve `.env` kopyası Git dışında `backups/20261109-165958/` klasöründedir.

## 1. Bir özelliği kapatmak (önerilen, kod değişmez)

`.env` içinde ilgili değeri `false` yapın, ardından:

```bash
php artisan config:clear
```

| Anahtar | Özellik |
|---|---|
| `KRAL_FX_SLIDER_TEXT` | A. Slayt değişiminde küçük başlık → başlık → açıklama → butonlar sırayla belirir |
| `KRAL_FX_CURSOR` | B. Masaüstünde fareyi takip eden halka (referans temadaki `bnz-pointer`) |
| `KRAL_FX_SCROLL_HEADER` | C. Aşağı kaydırınca gizlenen, yukarı kaydırınca üstten gelen header |
| `KRAL_FX_SERVICE_IMAGES` | D. "Hizmetlerimiz" bölümünde görselli kartlar (kapalıysa eski ikonlu kartlar) |
| `KRAL_FX_LIGHT_THEME` | E. Üst banttaki ☀ düğmesiyle seçilebilen açık tema |

Beşi birden kapalıyken site, başlangıçtaki referans görüntülerle **piksel piksel aynıdır** (doğrulandı).

## 2. Bir özelliğin kodunu tamamen kaldırmak

Her özellik kendi dosyalarında durur (`resources/js/features/*.js`, `resources/css/features/*.css`);
ortak listeler düzenlenmez. Bu yüzden tek bir commit diğerlerini etkilemeden geri alınabilir:

```bash
git revert fd8e7a2
```

(Örnek: görselli hizmet kartlarını kaldırır.) Her beş commit için geri alma denendi; çakışma çıkmadı,
derleme ve testler başarılı oldu. Ardından `npm run build` çalıştırın.

## 3. Başlangıç sürümüne dönmek

Değişiklikler ayrı dalda olduğu için `main` dalına geçmek yeterlidir (veri silinmez):

```bash
git switch main
```

```bash
npm run build
```

Tekrar geliştirmelere dönmek için `git switch feature/visual-enhancements`. Beğenilirse birleştirme
kullanıcı onayıyla yapılmalıdır (`git switch main` sonra `git merge feature/visual-enhancements`).

## 4. Görsel regresyon kontrolü

Kurulu Chrome ile 11 görünüm (masaüstü/mobil ana sayfa, slider, açılır menü, hizmetler, footer,
hizmet detayı, galeri, iletişim, mobil menü) çekilir ve piksel bazında karşılaştırılır:

```bash
node scripts/visual/capture.mjs docs/current-screenshots http://127.0.0.1:8001
```

```bash
node scripts/visual/compare.mjs docs/baseline-screenshots docs/current-screenshots docs/diff-screenshots
```

Açık tema görüntüleri için sona `--theme=light` ekleyin. Referans görüntüler: `docs/baseline-screenshots/`.

### Son karşılaştırma sonucu (tüm özellikler açık, koyu tema)

| Görünüm | Sonuç | Açıklama |
|---|---|---|
| Ana sayfa (masaüstü/mobil), Hizmetler bölümü | Farklı | İstenen değişiklik: görselli hizmet kartları |
| Slider, açılır menü, hizmet detayı, galeri, iletişim | 999 px | Yalnızca üst bandın sağ ucu: tema düğmesi ve yanındaki telefon numaralarının kayması. Ana menü satırı aynı |
| Mobil menü | Farklı | İstenen değişiklik: mobil menüdeki "Tema" düğmesi |
| Footer | 0,16% | Yalnızca kenar yumuşatma: hizmet bölümünün yüksekliği değiştiği için footer yarım piksel kaydı; yerleşim aynı |
| Hizmet detayı (mobil) | Aynı | |

## Notlar

- **Hizmet görselleri:** Kartlar her hizmetin panelden yönetilen "Kapak görseli"ni kullanır. Yedi görselin
  hepsi kontrol edildi ve ilgili hizmetle uyumlu (Ticari Araç, Traktör/İş Makinesi ve Cam Filmi görselleri
  panelden yüklendi). Görseli olmayan bir hizmette başka hizmetin fotoğrafı yerine ikonlu bir alan gösterilir.
- **Açık tema:** Varsayılan her zaman koyu temadır; sistem teması dikkate alınmaz. Header, mobil menü,
  hero, sayfa başlığı bandı, çağrı kutusu ve footer açık temada da koyu kalır (beyaz logo ve fotoğraf
  üzerindeki yazılar okunabilsin diye). Referans temanın kendi `light.css` dosyası yeniden kapsanarak kullanıldı.
- **Erişilebilirlik:** "Hareketi azalt" ayarı açık olan ziyaretçilerde slider animasyonu, imleç ve header
  geçişleri devre dışıdır.

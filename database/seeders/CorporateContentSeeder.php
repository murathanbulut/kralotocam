<?php

namespace Database\Seeders;

use App\Models\FleetPartner;
use App\Models\InsuranceCompany;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Database\Seeder;

/**
 * Sigorta, filo ve kurumsal geçmiş başlangıç verisi.
 * Kaynak: Kral_Oto_Cam_Profesyonel_Web_SEO_Paketi.docx (bölüm 7.1, 7.2, 4 ve 17).
 *
 * - İsimler belgedeki yazımıyla aktarılır; belgedeki "[unvan teyidi]" notları iç nota taşınır.
 * - Liste ve kuruluş yılı (2005) işletme tarafından 10.11.2026'da onaylandı: kayıtlar doğrulanmış başlar.
 *   Panelde doğrulama kaldırılan kayıt ön yüzden gizlenir. Logolar ayrıca kullanım hakkı onayı ister.
 * - Tekrar çalıştırılabilir: mevcut kayıtlar ve panelde yapılan değişiklikler korunur.
 */
class CorporateContentSeeder extends Seeder
{
    /** Belge 7.1 — mevcut siteden alınan ham liste (36 kayıt; görev metnindeki "35" yerine belgede 36 isim bulunur). */
    public const INSURANCE = [
        'Ak sigorta', 'Allianz sigorta', 'Ana sigorta', 'Anadolu sigorta',
        ['Anlara sigorta', 'unvan teyidi'], ['Arek sigorta', 'unvan teyidi'],
        'Atlas Mutuel sigorta', 'Axa sigorta', 'Bereket sigorta', 'Corpus sigorta', 'Doğa sigorta',
        ['Amaa sigorta', 'unvan teyidi'], 'Ethica sigorta', 'Eureko sigorta', 'Fiba sigorta',
        ['Gig sigorta', 'yazım/unvan teyidi'], 'Hdi katılım sigorta', 'Hdi sigorta', 'Hepiyi sigorta',
        'Koru sigorta', 'Magdeburger sigorta', 'Mapfre sigorta', 'Neova sigorta', 'Orient sigorta',
        'Pay sigorta', 'Prive sigorta', 'Quick sigorta', 'Ray sigorta', 'Referans sigorta',
        'Sompo sigorta', 'Türk nippon sigorta', 'Türkiye Katılım sigorta', 'Türkiye sigorta',
        'Unico sigorta', 'Zurich sigorta', 'Şeker sigorta',
    ];

    /** Belge 7.2 — eski içerikten bire bir alınan liste (21 kayıt). */
    public const FLEET = [
        'Avis (Otokoç)', 'Borlease', 'Burgan Leasing', 'Derindere', 'Dokay Filo – Adnan Özen',
        'Giray Filo', 'Hedef Filo', 'Kaya Ulaşım Filo', ['Leaseplan – Ayvens', 'güncel kurumsal ad teyidi'],
        'Maygold Filo – Aytel', 'Metal Oto', 'Nascar Filo', 'Optimum – Çizgi Rent a Car',
        'Optimum – Garanti Filo', 'Optimum – Rentgo', 'Sixt Rent a Car', 'T Filo – Topçuoğlu',
        'Teb Arval', 'YKT Filo', 'Intercity – Ekim Turizm', 'Zeplincar',
    ];

    public function run(): void
    {
        $this->organizations(InsuranceCompany::class, self::INSURANCE, 'Belge 7.1: aktif anlaşma ve unvan, yayından önce işletme tarafından onaylanmalı.');
        $this->organizations(FleetPartner::class, self::FLEET, 'Belge 7.2: aktif sözleşme ve logo kullanım onayı alınmalı.');
        $this->pages();
        $this->menu();
        $this->settings();
    }

    /** @param class-string<\Illuminate\Database\Eloquent\Model> $model */
    private function organizations(string $model, array $rows, string $baseNote): void
    {
        foreach ($rows as $i => $row) {
            [$name, $flag] = is_array($row) ? $row : [$row, null];

            $model::query()->firstOrCreate(['name' => $name], [
                'is_verified' => true,
                'is_active' => true,
                'sort_order' => $i + 1,
                'internal_note' => trim($baseNote.($flag ? " Belgede işaretli: [{$flag}]." : '').' İşletme tarafından 10.11.2026 tarihinde onaylandı.'),
            ]);
        }
    }

    private function pages(): void
    {
        $pages = [
            [
                'slug' => 'anlasmali-sigortalar',
                'template' => Page::TEMPLATE_INSURANCE,
                'title' => 'Anlaşmalı Sigortalar',
                'excerpt' => 'Kral Oto Cam, araç camı hasarlarında sigorta ve kasko süreçleri hakkında bilgi almanıza yardımcı olur. Poliçeniz ve hasar durumunuz doğrultusunda izlenecek süreç hakkında bizimle iletişime geçebilirsiniz.',
                'seo_title' => 'Anlaşmalı Sigortalar ve Kasko Cam Hasarı | Kral Oto Cam',
                'seo_description' => 'Kasko cam teminatı, başvuru adımları ve sigorta süreçleri hakkında bilgi alın. Eskişehir Kral Oto Cam.',
                'content' => <<<'HTML'
<p>Araç camınızda kırık, çatlak veya taş izi oluştuğunda kasko poliçenizdeki cam teminatı önem kazanır. Ancak cam hasarı işlemlerinin kapsamı, muafiyet durumu, kullanılabilecek cam seçeneği ve başvuru prosedürü sigorta şirketine ve poliçeye göre değişebilir.</p>
<h2>Kasko ile Cam Değişimi Nasıl İlerler?</h2>
<ol>
<li>Sigorta şirketi, poliçe ve araç bilgileriniz alınır.</li>
<li>Cam hasarı ve planlanan işlem değerlendirilir.</li>
<li>Cam teminatı ve varsa anlaşmalı servis koşulları kontrol edilir.</li>
<li>Gerekli onay ve evrak adımları netleştirilir.</li>
<li>Onay koşullarına göre onarım veya değişim işlemi planlanır.</li>
</ol>
<h2>Hangi Bilgiler Gerekebilir?</h2>
<p>Araç plakası, ruhsat bilgileri, sigorta şirketi ve poliçe bilgileri; hasar fotoğrafı ve gerekli görüldüğünde ek evrak.</p>
HTML,
            ],
            [
                'slug' => 'filo-cozumleri',
                'template' => Page::TEMPLATE_FLEET,
                'title' => 'Filo ve Kurumsal Araç Çözümleri',
                'excerpt' => 'Kral Oto Cam, kurumsal araçların cam onarım ve değişim ihtiyaçlarında hizmet seçenekleri sunar. Araç türüne, hasarın durumuna ve operasyonel gereksinimlere göre uygun çözüm hakkında bilgi almak için bizimle iletişime geçebilirsiniz.',
                'seo_title' => 'Filo Araçları İçin Oto Cam Hizmetleri | Kral Oto Cam Eskişehir',
                'seo_description' => 'Eskişehir\'de kurumsal filo araçlarına yönelik oto cam onarımı, değişimi ve randevu koordinasyonu hakkında bilgi alın.',
                'content' => <<<'HTML'
<h2>Kurumsal Araçlar İçin Hizmet Kapsamı</h2>
<ul>
<li>Binek ve uygun ticari filo araçlarında cam hasarı değerlendirmesi</li>
<li>Onarıma uygun taş izi hasarlarında cam tamiri</li>
<li>Gerektiğinde ön, yan ve arka cam değişimi</li>
<li>Uygun modellerde ADAS/kamera kalibrasyonu değerlendirmesi</li>
<li>İlgili sözleşme koşullarına bağlı evrak ve randevu koordinasyonu</li>
</ul>
<h2>Filo Operasyonunda Nasıl Çalışıyoruz?</h2>
<p>Araç bilgileri ve kurum prosedürü alındıktan sonra gerekli servis süreci belirlenir. İlgili parça bulunurluğu ve operasyon koşullarına göre randevu planlanır. İşlem sonrası araç ve yapılmış servis kalemlerine ilişkin gerekli bilgilendirme sağlanır.</p>
HTML,
            ],
        ];

        $order = (int) Page::query()->max('sort_order');
        foreach ($pages as $page) {
            // Var olan sayfa korunur (panelde düzenlenmiş olabilir); yalnızca şablon bağlanır.
            $existing = Page::query()->where('template', $page['template'])->orWhere('slug', $page['slug'])->first();
            if ($existing) {
                $existing->update(['template' => $page['template']]);

                continue;
            }
            Page::query()->create($page + ['sort_order' => ++$order, 'is_active' => true]);
        }
    }

    /** Kurumsal açılır menüsüne iki bağlantı eklenir; diğer menü öğelerine dokunulmaz. */
    private function menu(): void
    {
        $corporate = MenuItem::query()->whereNull('parent_id')->where('title', 'Kurumsal')->first();
        if (! $corporate) {
            return;
        }

        $order = (int) $corporate->children()->max('sort_order');
        foreach (['Anlaşmalı Sigortalar' => '/anlasmali-sigortalar/', 'Filo Çözümleri' => '/filo-cozumleri/'] as $title => $url) {
            if (! $corporate->children()->where('url', $url)->exists()) {
                $corporate->children()->create(['title' => $title, 'url' => $url, 'sort_order' => ++$order, 'is_active' => true]);
            }
        }
    }

    private function settings(): void
    {
        $stored = Setting::query()->pluck('value', 'key');

        $defaults = [
            // Belge: "Mevcut sitenin 2005 kuruluş yılı beyanı" — doğrulama gerektirir.
            'founding_year' => '2005',
            'founding_year_verified' => '1', // işletme onayı: 10.11.2026
        ];

        Setting::put(array_diff_key($defaults, $stored->all()));
    }
}

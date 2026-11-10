<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

/**
 * KVKK Aydınlatma Metni, Gizlilik Politikası ve Çerez Politikası TASLAKLARI.
 *
 * - Sayfalar PASİF (is_active = false) oluşturulur/tutulur: işletme ve hukuk danışmanı onayı
 *   olmadan yayına alınmaz. Onaydan sonra panelde "Aktif" yapılınca footer bağlantısı kendiliğinden görünür.
 * - Metinler sitenin GERÇEK davranışına göre yazıldı (Kasım 2026 itibarıyla):
 *   iletişim formu alanları, IP/tarayıcı kaydı, veritabanında saklama, info@ adresine e-posta,
 *   yalnızca iki zorunlu birinci taraf çerez, İletişim sayfasındaki Google Haritalar, analitik/pazarlama kodu yok,
 *   fotoğraf yükleme yok, otomatik silme/saklama süresi yok, çerez onay aracı yok.
 * - Köşeli parantezli [ ... ] alanlar işletme tarafından doldurulmalıdır.
 * - Var olan kayıt çoğaltılmaz; yalnızca içeriği hâlâ eski "TASLAK:" yer tutucusuysa güncellenir
 *   (panelde düzenlenmiş metin ezilmez).
 */
class LegalPagesSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->pages() as $slug => $page) {
            $existing = Page::query()->where('slug', $slug)->first();

            if (! $existing) {
                Page::query()->create($page + [
                    'slug' => $slug,
                    'is_active' => false,
                    'sort_order' => (int) Page::query()->max('sort_order') + 1,
                ]);

                continue;
            }

            if ($this->isPlaceholder($existing->content)) {
                $existing->update(array_intersect_key($page, array_flip(['title', 'content', 'seo_title', 'seo_description'])));
            }
        }
    }

    private function isPlaceholder(?string $content): bool
    {
        $text = trim(strip_tags((string) $content));

        return $text === '' || (str_starts_with($text, 'TASLAK:') && mb_strlen($text) < 300);
    }

    /** @return array<string, array<string, string>> */
    private function pages(): array
    {
        $controller = <<<'HTML'
<p><strong>Veri sorumlusu:</strong> [Resmî ticari unvan — işletme tarafından doldurulacak] (“Kral Oto Cam”)<br>
<strong>Adres:</strong> Teksan Sanayi Sitesi, A4 Blok No: 1, Odunpazarı / Eskişehir<br>
<strong>E-posta:</strong> info@kralotocam.com<br>
<strong>Telefon:</strong> 0532 488 96 03 · 0222 228 22 22<br>
<strong>MERSİS / Vergi no:</strong> [işletme tarafından doldurulacak]<br>
<strong>KEP adresi:</strong> [varsa işletme tarafından doldurulacak]</p>
HTML;

        return [
            'kvkk-aydinlatma-metni' => [
                'title' => 'KVKK Aydınlatma Metni',
                'seo_title' => 'KVKK Aydınlatma Metni | Kral Oto Cam',
                'seo_description' => 'Kral Oto Cam web sitesi ve iletişim kanalları üzerinden işlenen kişisel veriler hakkında 6698 sayılı KVKK kapsamında aydınlatma metni.',
                'content' => <<<HTML
<p>Bu aydınlatma metni, 6698 sayılı Kişisel Verilerin Korunması Kanunu’nun (“KVKK”) 10. maddesi uyarınca, kralotocam.com web sitesi ve sitede yer alan iletişim kanalları aracılığıyla işlenen kişisel verileriniz hakkında sizi bilgilendirmek amacıyla hazırlanmıştır.</p>
<h2>1. Veri Sorumlusu</h2>
{$controller}
<h2>2. İşlenen Kişisel Veriler</h2>
<p><strong>İletişim formu:</strong> Ad soyad, telefon numarası, e-posta adresi (isteğe bağlı), ilgilendiğiniz hizmet (isteğe bağlı), mesajınız ve formu gönderirken verdiğiniz onay bilgisi.</p>
<p><strong>Form ile birlikte kaydedilen teknik bilgiler:</strong> IP adresi, tarayıcı ve cihaz bilgisi (user-agent) ile gönderim tarihi ve saati.</p>
<p><strong>Site kullanımı:</strong> Sitenin güvenli çalışması için oluşturulan oturum kaydı (IP adresi, tarayıcı bilgisi ve son işlem zamanı). Ayrıntılar için <a href="/cerez-politikasi/">Çerez Politikası</a>’na bakabilirsiniz.</p>
<p>Web sitemizde fotoğraf veya dosya yükleme alanı bulunmamaktadır. Telefon veya WhatsApp üzerinden bizimle paylaştığınız bilgi ve fotoğraflar, talebinizin değerlendirilmesi amacıyla işlenir; WhatsApp kullanımı ayrıca WhatsApp/Meta’nın kendi koşullarına tabidir.</p>
<h2>3. İşleme Amaçları</h2>
<ul>
<li>İletişim ve bilgi taleplerinize yanıt verilmesi, hasar ön değerlendirmesi ve randevu planlaması,</li>
<li>Talep edilen hizmete ilişkin teklif ve bilgilendirme yapılması,</li>
<li>Formun kötüye kullanımının (spam, otomatik gönderim) önlenmesi ve site güvenliğinin sağlanması,</li>
<li>Yasal yükümlülüklerin yerine getirilmesi ve yetkili kurumların taleplerinin karşılanması.</li>
</ul>
<h2>4. Hukuki Sebepler ve Toplama Yöntemi</h2>
<p>Kişisel verileriniz, web sitesindeki iletişim formu, telefon ve WhatsApp kanalları aracılığıyla elektronik ortamda toplanır ve KVKK’nın 5. maddesinin 2. fıkrasında yer alan; bir sözleşmenin kurulması veya ifasıyla doğrudan ilgili olması (c), veri sorumlusunun hukuki yükümlülüğünü yerine getirmesi (ç) ve temel hak ve özgürlüklerinize zarar vermemek kaydıyla veri sorumlusunun meşru menfaati (f) hukuki sebeplerine dayanılarak işlenir. [Hukuki sebeplerin ve formdaki onay kutusu metninin hukuk danışmanınca teyidi gerekir.]</p>
<h2>5. Kişisel Verilerin Aktarılması</h2>
<p>Kişisel verileriniz pazarlama amacıyla üçüncü kişilerle paylaşılmaz. Verileriniz; sitenin barındırıldığı hizmet sağlayıcının sunucularında saklanır ve form bildirimleri e-posta hizmeti aracılığıyla işletmemizin e-posta adresine iletilir. Hukuki yükümlülük hâlinde yetkili kamu kurum ve kuruluşlarıyla paylaşılabilir.</p>
<p>[Barındırma ve e-posta hizmet sağlayıcılarının adı ile sunucuların yurt içinde mi yurt dışında mı bulunduğu işletme tarafından belirtilmelidir. Yurt dışında ise KVKK m. 9 kapsamındaki aktarım şartları ayrıca değerlendirilmelidir.]</p>
<h2>6. Saklama Süresi</h2>
<p>İletişim formu kayıtları, yönetim panelinde yetkili kişilerce silinene kadar saklanır; sitede otomatik silme işlemi bulunmamaktadır. Oturum kayıtları, oturum süresi (120 dakika) dolduktan sonra sistemin olağan temizleme işlemiyle silinir. [Form kayıtları için uygulanacak saklama süresi işletme tarafından belirlenmeli ve bu bölüme yazılmalıdır.]</p>
<h2>7. KVKK Madde 11 Kapsamındaki Haklarınız</h2>
<p>Veri sahibi olarak; kişisel verilerinizin işlenip işlenmediğini öğrenme, işlenmişse buna ilişkin bilgi talep etme, işlenme amacını ve amacına uygun kullanılıp kullanılmadığını öğrenme, yurt içinde veya yurt dışında aktarıldığı üçüncü kişileri bilme, eksik veya yanlış işlenmişse düzeltilmesini isteme, KVKK’nın 7. maddesinde öngörülen şartlar çerçevesinde silinmesini veya yok edilmesini isteme, düzeltme ve silme işlemlerinin aktarıldığı üçüncü kişilere bildirilmesini isteme, münhasıran otomatik sistemlerle analiz edilmesi suretiyle aleyhinize bir sonucun ortaya çıkmasına itiraz etme ve kanuna aykırı işleme nedeniyle zarara uğramanız hâlinde zararın giderilmesini talep etme haklarına sahipsiniz.</p>
<h2>8. Başvuru Yöntemi</h2>
<p>Haklarınıza ilişkin taleplerinizi, Veri Sorumlusuna Başvuru Usul ve Esasları Hakkında Tebliğ’e uygun olarak; kimliğinizi tespit edici bilgilerle birlikte yazılı olarak yukarıdaki adrese iletebilir, [varsa KEP adresi] üzerinden veya daha önce bize bildirdiğiniz ve sistemimizde kayıtlı e-posta adresinizden info@kralotocam.com adresine gönderebilirsiniz. Başvurunuz, niteliğine göre en kısa sürede ve en geç 30 gün içinde ücretsiz olarak sonuçlandırılır; işlemin ayrıca bir maliyet gerektirmesi hâlinde Kişisel Verileri Koruma Kurulu’nca belirlenen tarife uygulanabilir. Başvurunuzun reddedilmesi, verilen cevabı yetersiz bulmanız veya süresinde cevap verilmemesi hâlinde Kişisel Verileri Koruma Kurulu’na şikâyette bulunabilirsiniz.</p>
<p><em>Son güncelleme: [onay tarihi]</em></p>
HTML,
            ],
            'gizlilik-politikasi' => [
                'title' => 'Gizlilik Politikası',
                'seo_title' => 'Gizlilik Politikası | Kral Oto Cam',
                'seo_description' => 'Kral Oto Cam web sitesinde hangi bilgilerin toplandığı, nasıl kullanıldığı ve nasıl korunduğu hakkında gizlilik politikası.',
                'content' => <<<HTML
<p>Bu Gizlilik Politikası, kralotocam.com web sitesini ziyaret ettiğinizde ve bizimle iletişime geçtiğinizde bilgilerinizin nasıl işlendiğini açıklar. Kişisel verilerin işlenmesine ilişkin ayrıntılı bilgi <a href="/kvkk-aydinlatma-metni/">KVKK Aydınlatma Metni</a>’nde yer alır.</p>
<h2>Sorumlu</h2>
{$controller}
<h2>Topladığımız Bilgiler</h2>
<ul>
<li><strong>İletişim formu:</strong> Ad soyad, telefon, isteğe bağlı e-posta ve hizmet bilgisi, mesajınız; ayrıca IP adresi, tarayıcı bilgisi ve gönderim zamanı.</li>
<li><strong>Site kullanımı:</strong> Güvenli oturum ve form koruması için gerekli çerezler ile sunucuda tutulan oturum kaydı.</li>
<li><strong>Telefon ve WhatsApp:</strong> Bu kanallardan bizimle paylaştığınız bilgiler ve fotoğraflar.</li>
</ul>
<p>Sitemizde ziyaretçi istatistiği (analitik), reklam veya pazarlama amaçlı takip kodu bulunmamaktadır. Fotoğraf veya dosya yükleme alanı yoktur.</p>
<h2>Bilgileri Nasıl Kullanıyoruz?</h2>
<p>Bilgilerinizi yalnızca talebinize yanıt vermek, hizmetle ilgili bilgilendirme ve randevu planlaması yapmak, formun kötüye kullanımını önlemek ve yasal yükümlülükleri yerine getirmek için kullanırız. Bilgileriniz satılmaz ve pazarlama amacıyla üçüncü kişilerle paylaşılmaz.</p>
<h2>Saklama ve Erişim</h2>
<p>Form mesajları sitenin veritabanında saklanır ve yalnızca parola korumalı yönetim panelinden yetkili kişilerce görüntülenir; aynı mesajın bir kopyası işletmemizin e-posta adresine gönderilir. Kayıtlar yetkili kişilerce silinene kadar saklanır; otomatik silme bulunmamaktadır. [Saklama süresi işletme tarafından belirlenmelidir.]</p>
<h2>Spam Koruması</h2>
<p>İletişim formunda otomatik gönderimleri ayırt etmek için görünmeyen bir kontrol alanı kullanılır ve aynı IP adresinden kısa sürede yapılabilecek gönderim sayısı sınırlandırılır.</p>
<h2>Üçüncü Taraf Bağlantı ve İçerikler</h2>
<ul>
<li><strong>Google Haritalar:</strong> İletişim sayfasındaki harita Google tarafından sağlanır. Harita yüklendiğinde Google, IP adresiniz gibi bilgileri kendi gizlilik politikasına göre işleyebilir ve çerez kullanabilir.</li>
<li><strong>WhatsApp:</strong> WhatsApp bağlantıları sizi WhatsApp uygulamasına/sitesine yönlendirir; yazışmalar WhatsApp/Meta koşullarına tabidir.</li>
<li><strong>Sosyal medya bağlantıları:</strong> Yalnızca bağlantıdır; tıklamadığınız sürece bu sitelere veri gönderilmez.</li>
</ul>
<h2>Haklarınız</h2>
<p>KVKK kapsamındaki haklarınız ve başvuru yöntemi için <a href="/kvkk-aydinlatma-metni/">KVKK Aydınlatma Metni</a>’ni inceleyebilirsiniz.</p>
<h2>Değişiklikler</h2>
<p>Bu politika, sitedeki uygulamalar değiştiğinde güncellenir. Güncel metin her zaman bu sayfada yayınlanır.</p>
<p><em>Son güncelleme: [onay tarihi]</em></p>
HTML,
            ],
            'cerez-politikasi' => [
                'title' => 'Çerez Politikası',
                'seo_title' => 'Çerez Politikası | Kral Oto Cam',
                'seo_description' => 'Kral Oto Cam web sitesinde kullanılan zorunlu çerezler ve üçüncü taraf içerikler hakkında bilgi.',
                'content' => <<<'HTML'
<p>Bu Çerez Politikası, kralotocam.com web sitesinde kullanılan çerezleri ve benzeri teknolojileri açıklar.</p>
<h2>Çerez Nedir?</h2>
<p>Çerezler, bir web sitesini ziyaret ettiğinizde tarayıcınıza kaydedilen küçük metin dosyalarıdır.</p>
<h2>Sitemizde Kullanılan Çerezler</h2>
<p>Sitemiz yalnızca çalışması ve güvenliği için zorunlu olan iki birinci taraf çerez kullanır. Analitik, reklam veya pazarlama çerezi kullanılmaz.</p>
<table>
<thead><tr><th>Çerez</th><th>Amaç</th><th>Süre</th></tr></thead>
<tbody>
<tr><td>kral-oto-cam-session</td><td>Ziyaret sırasında oturumun sürdürülmesi ve iletişim formunun güvenli çalışması</td><td>Son işlemden itibaren 120 dakika</td></tr>
<tr><td>XSRF-TOKEN</td><td>Formların sahte isteklere (CSRF) karşı korunması</td><td>Son işlemden itibaren 120 dakika</td></tr>
</tbody>
</table>
<p>Bu çerezler sitenin temel işlevleri için zorunlu olduğundan kapatılmaları hâlinde iletişim formu çalışmayabilir.</p>
<h2>Üçüncü Taraf İçerikler</h2>
<p><strong>Google Haritalar:</strong> İletişim sayfasında Google Haritalar gömülü olarak gösterilir. Bu sayfayı ziyaret ettiğinizde harita Google sunucularından yüklenir ve Google kendi çerezlerini kullanabilir; bu çerezler Kral Oto Cam tarafından kontrol edilmez. Ayrıntılar için Google’ın gizlilik politikasını inceleyebilirsiniz. Diğer sayfalarda üçüncü taraf çerez kullanılmaz.</p>
<h2>Çerezleri Nasıl Yönetebilirsiniz?</h2>
<p>Sitemizde ayrı bir çerez tercih aracı bulunmamaktadır. Çerezleri tarayıcınızın ayarlarından görüntüleyebilir, silebilir veya engelleyebilirsiniz.</p>
<p><em>Son güncelleme: [onay tarihi]</em></p>
HTML,
            ],
        ];
    }
}

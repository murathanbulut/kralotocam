<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

/**
 * SSS başlangıç verisi. Kaynak: Kral_Oto_Cam_Profesyonel_Web_SEO_Paketi.docx, bölüm 9 (17 soru).
 * - 16 (mobil servis) ve 17 (silecek değişimi) belgede "doğrulanmadı" olarak işaretli olduğu için eklenmedi.
 * - Cevaplardaki editoryal/iç notlar (ör. "kaynak içerikte yer almaktadır", OEM ifadesi uyarısı)
 *   yayın metninden çıkarıldı; anlam korunarak müşteriye yönelik cümle kullanıldı.
 * Tekrar çalıştırılabilir: soru metni eşleşen kayıtlar korunur (panel düzenlemeleri ezilmez).
 */
class FaqSeeder extends Seeder
{
    public const ITEMS = [
        ['cam', 'Ön camdaki taş izi tamir edilebilir mi?', 'Bazı lokal taş çarpması hasarları onarılabilir. Uygunluk; izin boyutu, derinliği, cam kenarına yakınlığı, sürücünün görüş alanında bulunması ve camın genel durumuna göre değerlendirilir. İnceleme yapılmadan kesin onarım sözü verilmez.'],
        ['cam', 'Hangi durumlarda ön camın değiştirilmesi gerekir?', 'İlerlemiş çatlaklar, onarıma uygun olmayan hasarlar, kritik görüş alanını etkileyen durumlar veya cam yapısını bozan kırıklar değişim gerektirebilir. Kesin karar araç ve cam üzerindeki incelemeyle belirlenir.'],
        ['cam', 'Oto cam değişimi ne kadar sürer?', 'Süre, araç modeli, camın tedarik durumu, montaj yöntemi ve varsa ADAS işlemlerine göre değişir. Ön camlarda kullanılan yapıştırıcının güvenli sürüşe dönüş süresi de dikkate alınır. Randevu sırasında tahmini süre paylaşılır.'],
        ['cam', 'Cam değişiminden hemen sonra araç kullanılabilir mi?', 'Her zaman değil. Kullanılan yapıştırıcı sisteminin belirlediği minimum güvenli sürüşe dönüş süresine uyulmalıdır. Aracı ne zaman güvenli şekilde kullanabileceğiniz işlem sonrası bildirilir.'],
        ['sigorta', 'Kasko ön cam değişimini karşılar mı?', 'Cam teminatı bulunan poliçelerde işlem kapsam dahilinde olabilir. Ancak muafiyet, cam tipi, servis anlaşması ve ödeme koşulları poliçeye göre değişir. İşlem öncesinde sigorta şirketi ve poliçe koşulları kontrol edilmelidir.'],
        ['sigorta', 'Kaskodan cam değişimi yaptırmak hasarsızlık indirimini etkiler mi?', 'Bu durum ilgili sigorta ürününe, poliçe özel şartlarına ve hasar kaydının işlenme biçimine bağlıdır. Kesin bilgi için sigorta şirketinizden veya yetkili acentenizden yazılı teyit alın.'],
        ['adas', 'Cam değişimi sonrası ADAS kalibrasyonu gerekir mi?', 'Ön cama bağlı kamera bulunan bazı araçlarda üretici prosedürleri kalibrasyon veya kontrol gerektirebilir. Gerekli işlem araç marka-modeline ve sürüş destek sistemine göre değişir.'],
        ['adas', 'ADAS kalibrasyonu her araçta aynı şekilde mi yapılır?', 'Hayır. Bazı sistemlerde statik, bazılarında dinamik kalibrasyon veya bunların birleşimi uygulanabilir. İşlem üreticinin servis prosedürüne göre belirlenmelidir.'],
        ['cam', 'Yan cam ve arka cam tamir edilebilir mi?', 'Bu camların yapısı araç modeline göre değişebilir. Özellikle temperli camın kırılması hâlinde çoğu durumda cam değişimi gerekir. Uygun işlem, cam türü ve hasara göre değerlendirilir.'],
        ['cam', 'Orijinal cam mı kullanılıyor?', 'Kullanılacak camın markası, üretim standardı, tedarik durumu ve araçla uyumu işlem öncesinde netleştirilir.'],
        ['diger', 'Cam filmi her araca uygulanabilir mi?', 'Uygun film seçimi cam özelliklerine, sürücünün görüş koşullarına ve yürürlükteki trafik mevzuatına bağlıdır. Uygulama öncesinde izin verilen koşullar değerlendirilmelidir.'],
        ['diger', 'Traktör ve iş makinesi camları için servis veriyor musunuz?', 'Evet, traktör ve iş makinesi cam değişimi hizmeti veriyoruz. Model, cam ölçüsü ve parça bulunurluğu paylaşılırsa yapılabilecek işlem netleştirilir.'],
        ['diger', 'Sadece ayna camını değiştirmek mümkün mü?', 'Bazı araçlarda evet. Hasar ayna camıyla sınırlıysa yalnızca cam değişimi mümkün olabilir. Elektrikli mekanizma, gövde veya bağlantılar hasarlıysa farklı işlem gerekebilir.'],
        ['iletisim', 'Hasar fotoğrafı göndererek fiyat alabilir miyim?', 'Fotoğraf ve araç bilgileri ön değerlendirmeyi kolaylaştırır. Kesin fiyat; camın teknik özellikleri, parça seçimi, hasarın durumu ve gerekli ek işlemler netleştirildikten sonra verilir.'],
        ['iletisim', "Eskişehir'de servis adresiniz nerede?", 'Kral Oto Cam, Teksan Sanayi Sitesi A4 Blok No: 1, Odunpazarı / Eskişehir adresinde hizmet vermektedir. Telefon: 0532 488 96 03 ve 0222 228 22 22.'],
    ];

    public function run(): void
    {
        foreach (self::ITEMS as $i => [$category, $question, $answer]) {
            Faq::query()->firstOrCreate(['question' => $question], [
                'answer' => $answer,
                'category' => $category,
                'sort_order' => ($i + 1) * 10,
                'is_active' => true,
            ]);
        }
    }
}

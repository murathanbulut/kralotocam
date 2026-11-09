<?php

namespace Database\Seeders;

use App\Models\Service;
use Database\Seeders\Concerns\CopiesSeedImages;
use Illuminate\Database\Seeder;

/**
 * Yedi hizmet. "excerpt" alanlarının dördü canlı sitedeki hizmet kartlarından birebir alınmıştır
 * (binek, ticari, traktör/iş makinesi, dikiz ve yan ayna). Diğer metinler doğrulanamayan
 * iddia (yıl, garanti, sigorta anlaşması, müşteri sayısı vb.) içermeyecek şekilde yazılmıştır.
 */
class ServiceSeeder extends Seeder
{
    use CopiesSeedImages;

    public function run(): void
    {
        foreach ($this->services() as $i => $data) {
            $data['cover_image'] = $this->seedImage($data['cover_image'] ?? null, 'uploads/hizmetler');
            $data['gallery_images'] = array_values(array_filter(array_map(
                fn ($file) => $this->seedImage($file, 'uploads/hizmetler'),
                $data['gallery_images'] ?? []
            )));
            $data['sort_order'] = $i + 1;
            $data['is_active'] = true;

            Service::query()->updateOrCreate(['slug' => $data['slug']], $data);
        }
    }

    private function services(): array
    {
        return [
            [
                'title' => 'Binek Araç Cam Değişimi',
                'slug' => 'binek-arac-cam-degisimi',
                'icon' => 'bi-car-front',
                'cover_image' => 'binek-arac-on-cam-montaji.jpg',
                'excerpt' => 'Eskişehir\'de ön, yan ve arka araç camı değişiminde aracınıza uygun cam seçimi, özenli montaj ve işlem sonrası kontrollerle profesyonel servis desteği sunuyoruz.',
                'seo_title' => 'Binek Araç Cam Değişimi Eskişehir',
                'seo_description' => 'Eskişehir binek araç ön cam, yan cam ve arka cam değişimi. Araca uygun cam seçimi, özenli montaj ve işlem sonrası kontrol. Kral Oto Cam.',
                'seo_keywords' => 'binek araç cam değişimi, ön cam değişimi eskişehir, oto cam eskişehir, arka cam değişimi, yan cam değişimi',
                'content' => <<<'HTML'
<h2>Ön, Yan ve Arka Cam Değişimi</h2>
<p>Kırılan, derin çatlak oluşan veya onarım sınırlarını aşan araç camları, sürüş güvenliği için değiştirilmelidir. Kral Oto Cam olarak binek araçlarınızın ön, yan ve arka camlarının değişiminde aracınızın marka, model ve donanım özelliklerini dikkate alarak çalışıyoruz.</p>
<h2>Cam Seçiminde Nelere Dikkat Ediyoruz?</h2>
<ul>
<li>Aracın marka, model ve model yılına uygun cam ölçüsü</li>
<li>Yağmur/ışık sensörü, ön kamera, ısıtma ve anten gibi donanımlara uygunluk</li>
<li>Cam üzerindeki braket ve bağlantı noktalarının araçla uyumu</li>
</ul>
<h2>Değişim Süreci</h2>
<ol>
<li><strong>Ön bilgi:</strong> Aracınızın bilgilerini ve hasarın durumunu öğreniyoruz. Hasarın fotoğrafını WhatsApp üzerinden gönderebilirsiniz.</li>
<li><strong>Hazırlık:</strong> Aracın iç ve dış yüzeyleri işlem sırasında korunur, eski cam ve yapıştırıcı kalıntıları temizlenir.</li>
<li><strong>Montaj:</strong> Yeni cam, uygun yapıştırıcı ve yöntemlerle yerine yerleştirilir.</li>
<li><strong>Kontrol:</strong> Montaj sonrası sızdırmazlık, bağlantılar ve cam üzerindeki donanımlar kontrol edilir.</li>
</ol>
<h2>Ön Cam Değişiminden Sonra ADAS Kontrolü</h2>
<p>Ön camında kamera bulunan araçlarda, cam değişiminden sonra şerit takip, acil fren ve trafik işareti tanıma gibi sürüş destek sistemlerinin doğru çalışması için kalibrasyon gerekebilir. Bu konuda detaylı bilgi için <a href="/adas-ve-kamera-kalibrasyonlari/">ADAS ve Kamera Kalibrasyonu</a> sayfamızı inceleyebilirsiniz.</p>
<h2>Önce Onarım Seçeneğini Değerlendirin</h2>
<p>Her çatlak cam değişimi gerektirmez. Küçük taş izleri ve kısa çatlaklar çoğu zaman onarılabilir. Hasarınızın onarıma uygun olup olmadığını öğrenmek için <a href="/oto-cam-onarim-ve-tamiri/">Oto Cam Onarım ve Tamiri</a> sayfamıza göz atabilir ya da bizi arayabilirsiniz.</p>
HTML,
            ],
            [
                'title' => 'Ticari Araç Cam Değişimi',
                'slug' => 'ticari-arac-cam-degisimi',
                'icon' => 'images/theme/icon-ticari-arac.svg',
                'cover_image' => 'arac-cami-hasar-degerlendirmesi.jpg',
                'excerpt' => 'Eskişehir\'de panelvan, minibüs ve uygun ticari araçların ön, yan ve arka cam değişiminde araç özelliklerine uygun, planlı ve özenli servis çözümleri sunuyoruz.',
                'seo_title' => 'Ticari Araç Cam Değişimi Eskişehir',
                'seo_description' => 'Eskişehir\'de panelvan, minibüs ve ticari araçlar için ön, yan ve arka cam değişimi. Araç özelliklerine uygun, planlı servis. Kral Oto Cam.',
                'seo_keywords' => 'ticari araç cam değişimi, panelvan cam değişimi, minibüs ön cam, ticari araç ön cam eskişehir',
                'content' => <<<'HTML'
<h2>Ticari Araçlarınız İçin Cam Değişimi</h2>
<p>Ticari araçlar günlük iş akışının önemli bir parçasıdır. Cam hasarı nedeniyle aracınızın uzun süre servis dışında kalmaması için işlemi önceden planlıyor, aracınızın özelliklerine uygun camı tedarik ederek değişimi gerçekleştiriyoruz.</p>
<h2>Hizmet Verdiğimiz Araç Grupları</h2>
<ul>
<li>Panelvan ve hafif ticari araçlar</li>
<li>Minibüsler</li>
<li>Uygun ölçü ve özellikteki diğer ticari araçlar</li>
</ul>
<p>Aracınızın cam değişimine uygunluğunu ve gerekli parçaların durumunu öğrenmek için marka, model ve model yılı bilgisiyle bize ulaşabilirsiniz.</p>
<h2>Planlı Servis</h2>
<p>Cam tedariki ve işlem zamanını sizinle birlikte planlıyoruz. Böylece aracınızın iş programınızı mümkün olduğunca az etkilemesini amaçlıyoruz.</p>
<h2>Değişim Sonrası Kontroller</h2>
<p>Montajın ardından sızdırmazlık, silecek ve sensör bağlantıları kontrol edilir. Ön kamerası bulunan araçlarda <a href="/adas-ve-kamera-kalibrasyonlari/">ADAS kalibrasyonu</a> ihtiyacı ayrıca değerlendirilir.</p>
HTML,
            ],
            [
                'title' => 'Traktör ve İş Makinesi Cam Değişimi',
                'slug' => 'traktor-ve-is-makinesi-cam-degisimi',
                'icon' => 'images/theme/icon-is-makinesi.svg',
                'cover_image' => null,
                'excerpt' => 'Eskişehir\'de traktör, yükleyici, ekskavatör ve uygun iş makinelerinin kabin camı değişimi için araç modeline, cam ölçülerine ve çalışma koşullarına uygun çözümler sunuyoruz.',
                'seo_title' => 'Traktör ve İş Makinesi Cam Değişimi Eskişehir',
                'seo_description' => 'Eskişehir\'de traktör, yükleyici, ekskavatör ve iş makinesi kabin camı değişimi. Model, ölçü ve çalışma koşullarına uygun çözümler. Kral Oto Cam.',
                'seo_keywords' => 'traktör camı, iş makinesi camı, kabin camı değişimi, ekskavatör camı, kepçe camı eskişehir',
                'content' => <<<'HTML'
<h2>Kabin Camı Değişimi</h2>
<p>Traktör ve iş makinelerinin kabin camları; tarla, şantiye ve sanayi gibi zorlu çalışma koşullarında taş, toz ve titreşime maruz kalır. Hasar gören kabin camları hem görüşü hem de operatörün güvenliğini etkiler.</p>
<h2>Hangi Makineler İçin?</h2>
<ul>
<li>Traktörler</li>
<li>Yükleyiciler (loder)</li>
<li>Ekskavatörler</li>
<li>Uygun ölçü ve özellikteki diğer iş makineleri</li>
</ul>
<h2>Doğru Cam İçin Gerekli Bilgiler</h2>
<p>İş makinesi camlarında model ve ölçü çeşitliliği fazladır. Doğru çözümü belirleyebilmemiz için makinenin marka ve modelini, hasarlı camın konumunu ve mümkünse fotoğrafını bizimle paylaşmanız süreci hızlandırır.</p>
<p>Cam ölçüsü, kalınlığı ve bağlantı şekli değerlendirildikten sonra size uygun seçenek ve süreç hakkında bilgi veriyoruz.</p>
HTML,
            ],
            [
                'title' => 'Oto Cam Onarım ve Tamiri',
                'slug' => 'oto-cam-onarim-ve-tamiri',
                'icon' => 'bi-tools',
                'cover_image' => 'on-cam-tas-izi-onarimi.jpg',
                'excerpt' => 'Ön camdaki taş izi ve küçük çatlakları, hasarın boyutu ve konumunu değerlendirerek uygun durumlarda cam değişimine gerek kalmadan onarıyoruz.',
                'seo_title' => 'Oto Cam Onarımı ve Taş İzi Tamiri Eskişehir',
                'seo_description' => 'Eskişehir\'de ön cam taş izi ve küçük çatlak onarımı. Hasarın boyut ve konumuna göre onarım değerlendirmesi. Her çatlak cam değişimi gerektirmez.',
                'seo_keywords' => 'oto cam onarımı, taş izi tamiri, ön cam çatlak tamiri, cam tamiri eskişehir',
                'content' => <<<'HTML'
<h2>Her Çatlak Cam Değişimi Gerektirmez</h2>
<p>Yolda sıçrayan küçük bir taş, ön camda taş izi veya kısa bir çatlak oluşturabilir. Bu tür hasarlar, büyüklüğüne, derinliğine ve camdaki konumuna bağlı olarak çoğu zaman onarılabilir.</p>
<h2>Onarım Ne Zaman Mümkündür?</h2>
<ul>
<li>Hasar küçük ve yüzeysel ise</li>
<li>Çatlak henüz ilerlememiş ise</li>
<li>Hasar sürücünün doğrudan görüş alanında değilse</li>
<li>Hasar camın kenarına ulaşmamışsa</li>
</ul>
<p>Kesin karar, hasar yerinde incelendikten sonra verilir. Onarımın uygun olmadığı durumlarda size cam değişimi seçeneği hakkında bilgi veririz.</p>
<h2>Neden Erken Müdahale Önemlidir?</h2>
<p>Isı farkları, araç yıkama, sarsıntılar ve bozuk yollar küçük bir hasarın kısa sürede büyümesine neden olabilir. Hasarın erken değerlendirilmesi, onarım seçeneğinin korunma ihtimalini artırır.</p>
<h2>Hasar Oluştuğunda Yapabilecekleriniz</h2>
<ul>
<li>Hasarlı bölgeyi kirden korumaya çalışın.</li>
<li>Camın ani sıcak veya soğuk etkiye maruz kalmasından kaçının.</li>
<li>Hasarın fotoğrafını çekip WhatsApp üzerinden bize gönderin.</li>
</ul>
HTML,
            ],
            [
                'title' => 'ADAS ve Kamera Kalibrasyonu',
                'slug' => 'adas-ve-kamera-kalibrasyonlari',
                'icon' => 'bi-camera-video',
                'cover_image' => 'adas-kamera-kalibrasyon-ekipmanlari.jpg',
                'gallery_images' => ['adas-kalibrasyon-sistem-kontrolu.jpg'],
                'excerpt' => 'Ön cam değişiminden sonra ön kamera ve sürüş destek sistemlerinin (ADAS) doğru çalışması için kalibrasyon ve sistem kontrolü hizmeti veriyoruz.',
                'seo_title' => 'ADAS ve Ön Kamera Kalibrasyonu Eskişehir',
                'seo_description' => 'Eskişehir\'de ön cam değişimi sonrası ADAS ve ön kamera kalibrasyonu. Şerit takip ve acil fren gibi sürüş destek sistemlerinin kontrolü. Kral Oto Cam.',
                'seo_keywords' => 'adas kalibrasyonu, ön kamera kalibrasyonu, adas kalibrasyon eskişehir, şerit takip kamera kalibrasyonu',
                'content' => <<<'HTML'
<h2>ADAS Nedir?</h2>
<p>ADAS (Gelişmiş Sürücü Destek Sistemleri); şerit takip asistanı, acil fren desteği, trafik işareti tanıma ve adaptif hız sabitleyici gibi sürüşe yardımcı sistemlerin genel adıdır. Bu sistemlerin önemli bir kısmı, genellikle ön camın iç yüzeyine yerleştirilen kamera ile çalışır.</p>
<h2>Cam Değişiminden Sonra Neden Kalibrasyon Gerekir?</h2>
<p>Ön cam değiştirildiğinde kameranın cama göre konumu ve bakış açısı çok küçük ölçülerde de olsa değişebilir. Bu sapma, sistemin yol çizgilerini, araçları veya trafik işaretlerini hatalı algılamasına neden olabilir. Bu nedenle kamera bulunan araçlarda, cam değişiminden sonra üretici talimatlarına uygun kalibrasyon yapılması önerilir.</p>
<h2>Kalibrasyon Süreci</h2>
<ol>
<li>Aracın ADAS donanımı ve kalibrasyon gereksinimi belirlenir.</li>
<li>Kalibrasyon için uygun hedef panoları ve ekipman konumlandırılır.</li>
<li>Diyagnostik cihazı ile kalibrasyon işlemi gerçekleştirilir.</li>
<li>İşlem sonrası sistem kontrol edilerek arıza kaydı olup olmadığı incelenir.</li>
</ol>
<h2>Hangi Durumlarda Kalibrasyon Düşünülmelidir?</h2>
<ul>
<li>Ön cam değişiminden sonra</li>
<li>Ön kamera sökülüp takıldığında</li>
<li>Gösterge panelinde sürüş destek sistemi uyarısı görüldüğünde</li>
</ul>
<p>Aracınızın kalibrasyon gerektirip gerektirmediğini öğrenmek için marka, model ve model yılı bilgisiyle bize ulaşabilirsiniz.</p>
HTML,
            ],
            [
                'title' => 'Cam Filmi',
                'slug' => 'cam-filmi',
                'icon' => 'bi-brightness-high',
                'cover_image' => null,
                'excerpt' => 'Araç camlarınız için güneş ışığını ve ısıyı azaltmaya, sürüş konforunu ve mahremiyeti artırmaya yönelik cam filmi uygulaması yapıyoruz.',
                'seo_title' => 'Araç Cam Filmi Uygulaması Eskişehir',
                'seo_description' => 'Eskişehir\'de araç cam filmi uygulaması. Güneş ışığı ve ısı kontrolü, sürüş konforu ve mahremiyet için uygun film seçimi. Kral Oto Cam.',
                'seo_keywords' => 'cam filmi, araç cam filmi eskişehir, oto cam filmi, güneş kontrol filmi',
                'content' => <<<'HTML'
<h2>Araç Cam Filmi</h2>
<p>Cam filmi, araç camlarına uygulanan ince bir katmandır. Doğru seçilen film; güneş ışığının ve ısının araç içine etkisini azaltmaya, sürüş konforunu artırmaya ve araç içi mahremiyeti sağlamaya yardımcı olur.</p>
<h2>Cam Filminin Sağladığı Avantajlar</h2>
<ul>
<li>Güneş ışığının yarattığı parlamanın azaltılması</li>
<li>Araç içi ısı artışının sınırlandırılması</li>
<li>Araç içi mahremiyet</li>
<li>Döşeme ve iç aksamların güneşten korunmasına katkı</li>
</ul>
<h2>Film Seçerken Dikkat Edilmesi Gerekenler</h2>
<p>Cam filminin ışık geçirgenlik oranı ve uygulanacağı camlar, yürürlükteki trafik mevzuatına uygun olmalıdır. Uygulama öncesinde ihtiyacınızı, aracınızın camlarını ve yasal sınırları birlikte değerlendirerek size uygun seçeneği belirliyoruz.</p>
<h2>Uygulama Sonrası</h2>
<p>Filmin cama tam olarak oturabilmesi için uygulamadan sonraki ilk günlerde camların açılıp kapatılmaması ve film yüzeyinin sert malzemelerle temizlenmemesi önerilir. Uygulama sonunda bakım konusunda size bilgi veriyoruz.</p>
HTML,
            ],
            [
                'title' => 'Dikiz ve Yan Ayna Onarımı ve Değişimi',
                'menu_title' => 'Dikiz ve Yan Ayna Onarımı ve Değişimi',
                'slug' => 'dikiz-ve-yan-aynalar-onarim-ve-degisimleri',
                'icon' => 'bi-eye',
                'cover_image' => 'yan-ayna-degisimi.jpg',
                'excerpt' => 'Eskişehir\'de kırık, çatlak veya işlevini kaybetmiş dikiz ve yan aynalar için araç modeline ve mevcut donanımlara uygun onarım ve değişim çözümleri sunuyoruz.',
                'seo_title' => 'Dikiz ve Yan Ayna Onarımı ve Değişimi Eskişehir',
                'seo_description' => 'Eskişehir\'de kırık veya çatlak dikiz ve yan aynalar için araç modeline ve donanımına uygun onarım ve değişim. Kral Oto Cam.',
                'seo_keywords' => 'yan ayna değişimi, dikiz aynası, ayna camı değişimi, yan ayna tamiri eskişehir',
                'content' => <<<'HTML'
<h2>Aynalar Güvenli Sürüşün Parçasıdır</h2>
<p>Dikiz ve yan aynalar; şerit değiştirme, park etme ve manevra sırasında çevreyi doğru görebilmeniz için gereklidir. Kırık veya çatlak bir ayna görüşü bozar ve sürüş güvenliğini olumsuz etkiler.</p>
<h2>Hangi Durumlarda Hizmet Veriyoruz?</h2>
<ul>
<li>Kırık veya çatlak yan ayna camı</li>
<li>Hasar görmüş ayna kapağı veya gövdesi</li>
<li>İşlevini kaybetmiş dikiz aynası</li>
</ul>
<h2>Donanıma Uygun Çözüm</h2>
<p>Günümüz araçlarında aynalar; ısıtma, elektrikli ayar, katlanma, sinyal lambası veya kör nokta uyarısı gibi donanımlar içerebilir. Onarım ya da değişim seçeneğini, aracınızın modeli ve mevcut ayna donanımı doğrultusunda belirliyoruz.</p>
<p>Hasarın sadece ayna camında mı yoksa gövdede mi olduğunu anlamak için fotoğrafını WhatsApp üzerinden göndermeniz değerlendirmeyi kolaylaştırır.</p>
HTML,
            ],
        ];
    }
}

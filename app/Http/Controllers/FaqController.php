<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Page;
use App\Support\Seo;
use Illuminate\View\View;

/** /sikca-sorulan-sorular/ — panelden yönetilen aktif sorular, kategoriye göre gruplanır. */
class FaqController extends Controller
{
    public function __invoke(): View
    {
        $faqs = Faq::query()->active()->ordered()->get();

        // Kategoriler panel listesindeki sırayla; tanımsız kategori en sonda "Genel" altında
        $groups = $faqs->groupBy(fn (Faq $faq) => array_key_exists($faq->category, Faq::CATEGORIES) ? $faq->category : 'genel')
            ->sortBy(fn ($items, $key) => array_search($key, array_keys(Faq::CATEGORIES), true) === false
                ? PHP_INT_MAX : array_search($key, array_keys(Faq::CATEGORIES), true));

        $seo = Seo::make(
            'Oto Cam Sıkça Sorulan Sorular | Kral Oto Cam',
            'Cam değişimi süresi, taş izi tamiri, kasko şartları ve ADAS kalibrasyonu hakkında sık sorulan soruların yanıtlarını okuyun.'
        )
            ->canonical(site_url('sikca-sorulan-sorular'))
            ->breadcrumbs([['Kurumsal', null], ['Sıkça Sorulan Sorular']]);

        // FAQPage verisi yalnızca sayfada görünen soru-cevaplarla birebir oluşturulur
        if ($faqs->isNotEmpty()) {
            $seo->addSchema([
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => $faqs->map(fn (Faq $faq) => [
                    '@type' => 'Question',
                    'name' => $faq->question,
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq->answer],
                ])->values()->all(),
            ]);
        }

        return view('faq.index', [
            'seo' => $seo,
            'groups' => $groups,
            'corporatePages' => Page::query()->active()->ordered()
                ->where(fn ($q) => $q->whereIn('slug', Page::CORPORATE_SLUGS)->orWhereIn('template', array_keys(Page::TEMPLATES)))
                ->get(),
        ]);
    }
}

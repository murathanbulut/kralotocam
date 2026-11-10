<?php

namespace App\Http\Controllers;

use App\Models\FleetPartner;
use App\Models\InsuranceCompany;
use App\Models\Page;
use App\Models\Service;
use App\Support\RedirectResolver;
use App\Support\Seo;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Kök seviyesindeki adresleri çözer: önce hizmetler, sonra kurumsal sayfalar.
 * Örn: /binek-arac-cam-degisimi/, /hakkimizda/
 */
class SlugController extends Controller
{
    public function __invoke(string $slug, RedirectResolver $redirects): View|RedirectResponse
    {
        if ($service = Service::query()->active()->where('slug', $slug)->first()) {
            return $this->service($service);
        }

        if ($page = Page::query()->active()->where('slug', $slug)->first()) {
            return $this->page($page);
        }

        if ($target = $redirects->resolve($slug)) {
            return redirect()->to($target[0], $target[1]);
        }

        abort(404);
    }

    private function service(Service $service): View
    {
        $seo = Seo::make($service->seo_title ?: $service->title, $service->seo_description ?: $service->excerpt)
            ->keywords($service->seo_keywords)
            ->image($service->cover_image)
            ->canonical($service->url())
            ->breadcrumbs([['Hizmetler', site_url('hizmetler')], [$service->title]])
            ->addSchema(array_filter([
                '@context' => 'https://schema.org',
                '@type' => 'Service',
                'name' => $service->title,
                'serviceType' => $service->title,
                'description' => $service->seo_description ?: $service->excerpt,
                'url' => $service->url(),
                'image' => $service->cover_image ? media_url($service->cover_image) : null,
                'areaServed' => ['@type' => 'City', 'name' => 'Eskişehir'],
                'provider' => ['@id' => site_url().'#isletme'],
            ]))
            ->withLocalBusiness();

        return view('services.show', [
            'seo' => $seo,
            'service' => $service,
            'services' => Service::query()->active()->ordered()->get(),
            'related' => Service::query()->active()->ordered()->whereKeyNot($service->id)->limit(3)->get(),
        ]);
    }

    private function page(Page $page): View
    {
        $seo = Seo::make($page->seo_title ?: $page->title, $page->seo_description ?: $page->excerpt)
            ->image($page->cover_image)
            ->canonical($page->url())
            ->breadcrumbs([['Kurumsal', null], [$page->title]]);

        // Sigorta/filo listeleri: yalnızca aktif VE doğrulanmış kayıtlar (published kapsamı)
        $organizations = match ($page->template) {
            Page::TEMPLATE_INSURANCE => InsuranceCompany::query()->published()->get(),
            Page::TEMPLATE_FLEET => FleetPartner::query()->published()->get(),
            default => null,
        };

        return view('pages.show', [
            'seo' => $seo,
            'page' => $page,
            'organizations' => $organizations,
            'corporatePages' => Page::query()->active()->ordered()
                ->where(fn ($q) => $q->whereIn('slug', Page::CORPORATE_SLUGS)->orWhereIn('template', array_keys(Page::TEMPLATES)))
                ->get(),
        ]);
    }
}

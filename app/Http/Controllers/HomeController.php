<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\GalleryImage;
use App\Models\Page;
use App\Models\Service;
use App\Models\Slider;
use App\Support\Seo;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $seo = Seo::make(setting('seo_home_title'), setting('seo_home_description'))
            ->canonical(site_url())
            ->withLocalBusiness()
            ->addSchema([
                '@context' => 'https://schema.org',
                '@type' => 'WebSite',
                'name' => setting('site_name'),
                'url' => site_url(),
                'inLanguage' => 'tr-TR',
            ]);

        return view('pages.home', [
            'seo' => $seo,
            'sliders' => Slider::query()->active()->ordered()->get(),
            'services' => Service::query()->active()->ordered()->get(),
            'about' => Page::query()->active()->where('slug', 'hakkimizda')->first(),
            'galleryImages' => GalleryImage::query()
                ->active()
                ->whereHas('album', fn ($q) => $q->active())
                ->with('album')
                ->ordered()
                ->limit(6)
                ->get(),
            'posts' => BlogPost::query()->latestPublished()->with('category')->limit(3)->get(),
        ]);
    }
}

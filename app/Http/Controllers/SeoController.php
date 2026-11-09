<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\GalleryAlbum;
use App\Models\Page;
use App\Models\Service;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $urls = collect([
            ['loc' => site_url(), 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['loc' => site_url('hizmetler'), 'priority' => '0.9', 'changefreq' => 'monthly'],
            ['loc' => site_url('galeri'), 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['loc' => site_url('blog'), 'priority' => '0.6', 'changefreq' => 'weekly'],
            ['loc' => site_url('iletisim'), 'priority' => '0.8', 'changefreq' => 'yearly'],
        ]);

        Service::query()->active()->ordered()->get()->each(fn ($s) => $urls->push([
            'loc' => $s->url(), 'lastmod' => $s->updated_at, 'priority' => '0.9', 'changefreq' => 'monthly',
        ]));

        Page::query()->active()->ordered()->get()->each(fn ($p) => $urls->push([
            'loc' => $p->url(), 'lastmod' => $p->updated_at, 'priority' => '0.5', 'changefreq' => 'yearly',
        ]));

        GalleryAlbum::query()->active()->whereHas('images', fn ($q) => $q->active())->get()->each(fn ($a) => $urls->push([
            'loc' => $a->url(), 'lastmod' => $a->updated_at, 'priority' => '0.4', 'changefreq' => 'monthly',
        ]));

        BlogCategory::query()->active()->whereHas('posts', fn ($q) => $q->published())->get()->each(fn ($c) => $urls->push([
            'loc' => $c->url(), 'lastmod' => $c->updated_at, 'priority' => '0.4', 'changefreq' => 'weekly',
        ]));

        BlogPost::query()->latestPublished()->get()->each(fn ($p) => $urls->push([
            'loc' => $p->url(), 'lastmod' => $p->updated_at, 'priority' => '0.6', 'changefreq' => 'monthly',
        ]));

        return response()
            ->view('seo.sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots(): Response
    {
        $lines = app()->isProduction()
            ? ['User-agent: *', 'Disallow: /admin', 'Disallow: /livewire', '', 'Sitemap: '.url('sitemap.xml')]
            // Lokal/test ortamlarının dizine eklenmesini engelle.
            : ['User-agent: *', 'Disallow: /'];

        return response(implode("\n", $lines)."\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}

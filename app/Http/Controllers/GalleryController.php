<?php

namespace App\Http\Controllers;

use App\Models\GalleryAlbum;
use App\Support\Seo;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        $albums = GalleryAlbum::query()
            ->active()
            ->ordered()
            ->whereHas('images', fn ($q) => $q->active())
            ->withCount(['images' => fn ($q) => $q->active()])
            ->with(['images' => fn ($q) => $q->active()->ordered()])
            ->get();

        $seo = Seo::make(
            'Galeri | Oto Cam Uygulamalarımız',
            'Kral Oto Cam atölyesinden araç camı değişimi, cam onarımı, ADAS kalibrasyonu ve ayna uygulamalarına ait görseller.'
        )->breadcrumbs([['Galeri']]);

        return view('gallery.index', compact('seo', 'albums'));
    }

    public function show(GalleryAlbum $album): View
    {
        abort_unless($album->is_active, 404);

        $images = $album->activeImages()->get();

        $seo = Seo::make($album->seo_title ?: $album->title.' | Galeri', $album->seo_description ?: $album->description)
            ->image($album->coverPath())
            ->breadcrumbs([['Galeri', site_url('galeri')], [$album->title]]);

        return view('gallery.show', [
            'seo' => $seo,
            'album' => $album,
            'images' => $images,
            'albums' => GalleryAlbum::query()->active()->ordered()
                ->whereHas('images', fn ($q) => $q->active())->get(),
        ]);
    }
}

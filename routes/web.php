<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SlugController;
use App\Http\Middleware\EnsureTrailingSlash;
use App\Support\RedirectResolver;
use Illuminate\Support\Facades\Route;

/*
| Ön yüz adresleri sonu "/" ile biten kanonik biçimdedir (örn. /iletisim/).
| Eğik çizgisiz istekler EnsureTrailingSlash ile 301 yönlendirilir.
*/

Route::get('sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('robots.txt', [SeoController::class, 'robots'])->name('robots');

Route::middleware(EnsureTrailingSlash::class)->group(function () {
    Route::get('/', HomeController::class)->name('home');

    Route::get('hizmetler', [ServiceController::class, 'index'])->name('services.index');

    Route::get('galeri', [GalleryController::class, 'index'])->name('gallery.index');
    Route::get('galeri/{album:slug}', [GalleryController::class, 'show'])->name('gallery.show');

    Route::get('blog', [BlogController::class, 'index'])->name('blog.index');
    Route::get('blog/kategori/{category:slug}', [BlogController::class, 'category'])->name('blog.category');
    Route::get('blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');

    Route::get('iletisim', [ContactController::class, 'show'])->name('contact');

    // Hizmet ve kurumsal sayfalar: /binek-arac-cam-degisimi/, /hakkimizda/ ...
    Route::get('{slug}', SlugController::class)
        ->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*')
        ->name('slug');
});

Route::post('iletisim', [ContactController::class, 'store'])
    ->middleware('throttle:contact')
    ->name('contact.store');

// Eski canlı site adresleri (/tr/hizmet/..., /tr/sayfa/...) ve tanımsız adresler
Route::fallback(function (RedirectResolver $redirects) {
    if ($target = $redirects->resolve(request()->path())) {
        return redirect()->to($target[0], $target[1]);
    }

    abort(404);
});

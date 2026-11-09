<?php

namespace App\Providers;

use App\Models\MenuItem;
use App\Models\Service;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Carbon::setLocale('tr');
        Paginator::defaultView('partials.pagination');

        // İletişim formu: IP başına dakikada 3, saatte 10 gönderim.
        RateLimiter::for('contact', fn (Request $request) => [
            Limit::perMinute(3)->by($request->ip()),
            Limit::perHour(10)->by($request->ip()),
        ]);

        // Ön yüz şablonlarının ortak verileri (menü ve footer hizmet listesi).
        View::composer(['partials.header', 'partials.footer'], function ($view) {
            $view->with('menu', MenuItem::tree());
            $view->with('footerServices', once(fn () => Service::query()
                ->active()->ordered()->get(['id', 'title', 'menu_title', 'slug'])));
        });
    }
}

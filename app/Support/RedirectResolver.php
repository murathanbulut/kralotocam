<?php

namespace App\Support;

use App\Models\Page;
use App\Models\Redirect;
use App\Models\Service;

/**
 * Eski (canlı) site adreslerini yeni adreslere eşler.
 * 1) Yönetim panelindeki "Yönlendirmeler" tablosu
 * 2) Eski yapı kalıpları: /tr/hizmet/{slug}, /tr/sayfa/{slug}-{id}
 */
class RedirectResolver
{
    /** @return array{0: string, 1: int}|null [hedef adres, durum kodu] */
    public function resolve(string $path): ?array
    {
        $normalized = Redirect::normalize($path);

        if ($normalized === '') {
            return null;
        }

        $redirect = Redirect::query()->where('from_path', $normalized)->where('is_active', true)->first();

        if ($redirect) {
            $redirect->increment('hits');

            return [$this->absolute($redirect->to_path), $redirect->status_code ?: 301];
        }

        if (preg_match('#^tr/(hizmet|sayfa)/([a-z0-9-]+)$#', $normalized, $m)) {
            $slug = $m[2];
            $candidates = array_unique([$slug, preg_replace('/-\d+$/', '', $slug)]);

            foreach ($candidates as $candidate) {
                if ($service = Service::query()->active()->where('slug', $candidate)->first()) {
                    return [$service->url(), 301];
                }

                if ($page = Page::query()->active()->where('slug', $candidate)->first()) {
                    return [$page->url(), 301];
                }
            }
        }

        return null;
    }

    private function absolute(string $target): string
    {
        if (preg_match('#^https?://#i', $target)) {
            return $target;
        }

        return site_url($target);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ön yüz adreslerini sonu "/" ile biten tek bir kanonik biçime yönlendirir.
 * Örn: /iletisim  ->  /iletisim/  (301)
 * Uzantılı dosyalar (sitemap.xml, robots.txt) etkilenmez.
 */
class EnsureTrailingSlash
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethod('GET') && ! $request->isMethod('HEAD')) {
            return $next($request);
        }

        $path = $request->getPathInfo();

        if ($path !== '/' && ! str_ends_with($path, '/') && ! preg_match('/\.[a-z0-9]{2,5}$/i', $path)) {
            $query = $request->getQueryString();
            $target = $request->getSchemeAndHttpHost().$request->getBaseUrl().$path.'/'.($query ? '?'.$query : '');

            return redirect()->to($target, 301);
        }

        return $next($request);
    }
}

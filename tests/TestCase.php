<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Uri;

abstract class TestCase extends BaseTestCase
{
    /**
     * Laravel test istemcisi adresin sonundaki "/" karakterini siler.
     * Sitenin kanonik adresleri "/" ile bittiği için tarayıcı davranışını korumak üzere geri eklenir.
     */
    protected function prepareUrlForRequest($uri)
    {
        $raw = $uri instanceof Uri ? $uri->value() : (string) $uri;
        $path = (string) parse_url($raw, PHP_URL_PATH);
        $url = parent::prepareUrlForRequest($uri);

        if ($path !== '' && $path !== '/' && str_ends_with($path, '/')) {
            [$base, $query] = array_pad(explode('?', $url, 2), 2, null);
            $url = rtrim($base, '/').'/'.($query !== null ? '?'.$query : '');
        }

        return $url;
    }
}

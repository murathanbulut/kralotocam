<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Sayfa bazlı SEO verisi: title, description, canonical, Open Graph,
 * breadcrumb ve JSON-LD yapılandırılmış veri.
 */
class Seo
{
    public string $title;

    public ?string $description = null;

    public ?string $keywords = null;

    public string $canonical;

    public ?string $image = null;

    public string $type = 'website';

    public bool $noindex = false;

    /** @var array<int, array{label: string, url: ?string}> */
    public array $breadcrumbs = [];

    /** @var array<int, array<string, mixed>> */
    public array $schema = [];

    public ?string $publishedTime = null;

    public ?string $modifiedTime = null;

    public function __construct(?string $title = null, ?string $description = null)
    {
        $siteName = (string) setting('site_name');

        $this->title = $title ? $title : $siteName;
        $this->description = $description ?: setting('seo_default_description');
        $this->canonical = site_url(request()->path());
        $this->image = media_url(setting('seo_og_image'), 'images/seo/og-default.jpg');
    }

    public static function make(?string $title = null, ?string $description = null): static
    {
        return new static($title, $description);
    }

    /** Başlık sonuna marka adı ekler (zaten içermiyorsa). */
    public function fullTitle(): string
    {
        $siteName = (string) setting('site_name');

        return Str::contains($this->title, $siteName) ? $this->title : $this->title.' | '.$siteName;
    }

    public function metaDescription(): ?string
    {
        return $this->description ? Str::limit(trim(strip_tags($this->description)), 160, '…') : null;
    }

    public function description(?string $description): static
    {
        if ($description) {
            $this->description = $description;
        }

        return $this;
    }

    public function keywords(?string $keywords): static
    {
        $this->keywords = $keywords;

        return $this;
    }

    public function canonical(string $url): static
    {
        $this->canonical = $url;

        return $this;
    }

    public function image(?string $path): static
    {
        if ($path) {
            $this->image = media_url($path);
        }

        return $this;
    }

    public function type(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function noindex(bool $noindex = true): static
    {
        $this->noindex = $noindex;

        return $this;
    }

    public function article(?\DateTimeInterface $published, ?\DateTimeInterface $modified): static
    {
        $this->type = 'article';
        $this->publishedTime = $published?->format(DATE_ATOM);
        $this->modifiedTime = $modified?->format(DATE_ATOM);

        return $this;
    }

    /** Ana Sayfa otomatik eklenir; son öğe mevcut sayfadır. */
    public function breadcrumbs(array $items): static
    {
        $this->breadcrumbs = array_merge(
            [['label' => 'Ana Sayfa', 'url' => site_url()]],
            array_map(fn ($item) => ['label' => $item[0], 'url' => $item[1] ?? null], $items),
        );

        return $this;
    }

    public function addSchema(array $schema): static
    {
        $this->schema[] = $schema;

        return $this;
    }

    public function withLocalBusiness(): static
    {
        return $this->addSchema(self::localBusinessSchema());
    }

    /** @return array<int, array<string, mixed>> */
    public function allSchema(): array
    {
        $schema = $this->schema;

        if (count($this->breadcrumbs) > 1) {
            $schema[] = [
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => collect($this->breadcrumbs)->values()->map(fn ($item, $i) => array_filter([
                    '@type' => 'ListItem',
                    'position' => $i + 1,
                    'name' => $item['label'],
                    'item' => $item['url'] ?? $this->canonical,
                ]))->all(),
            ];
        }

        return $schema;
    }

    public static function localBusinessSchema(): array
    {
        $sameAs = array_values(array_filter([
            setting('facebook_url'), setting('instagram_url'), setting('x_url'), setting('youtube_url'),
        ]));

        return array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'AutoRepair',
            '@id' => site_url().'#isletme',
            'name' => setting('site_name'),
            'url' => site_url(),
            'logo' => media_url(setting('logo'), 'images/theme/logo.png'),
            'image' => media_url(setting('seo_og_image'), 'images/seo/og-default.jpg'),
            'description' => setting('seo_home_description'),
            'telephone' => str_replace('tel:', '', phone_href(setting('phone_gsm'))),
            'email' => setting('email'),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => 'Teksan Sanayi Sitesi, A4 Blok No: 1',
                'addressLocality' => setting('address_locality'),
                'addressRegion' => setting('address_region'),
                'addressCountry' => setting('address_country'),
            ],
            'areaServed' => ['@type' => 'City', 'name' => 'Eskişehir'],
            'hasMap' => setting('map_link'),
            'sameAs' => $sameAs ?: null,
        ]);
    }
}

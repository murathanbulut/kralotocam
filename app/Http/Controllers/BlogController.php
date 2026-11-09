<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Support\Seo;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $seo = Seo::make(
            'Blog | Oto Cam Rehberi',
            'Araç camı onarımı ve değişimi, ADAS kalibrasyonu ve cam filmi hakkında Kral Oto Cam blog yazıları.'
        )->breadcrumbs([['Blog']]);

        return $this->listing($seo, null);
    }

    public function category(BlogCategory $category): View
    {
        abort_unless($category->is_active, 404);

        $seo = Seo::make(
            $category->seo_title ?: $category->name.' | Blog',
            $category->seo_description ?: $category->description
        )->breadcrumbs([['Blog', site_url('blog')], [$category->name]]);

        return $this->listing($seo, $category);
    }

    public function show(BlogPost $post): View
    {
        // Taslak yazılar yalnızca yönetici oturumunda önizlenebilir.
        $isPreview = ! $post->isPublished();
        abort_if($isPreview && ! auth()->user()?->is_admin, 404);

        $seo = Seo::make($post->seo_title ?: $post->title, $post->seo_description ?: $post->excerpt)
            ->image($post->cover_image)
            ->canonical($post->url())
            ->article($post->published_at, $post->updated_at)
            ->noindex($isPreview)
            ->breadcrumbs(array_values(array_filter([
                ['Blog', site_url('blog')],
                $post->category ? [$post->category->name, $post->category->url()] : null,
                [Str::limit($post->title, 60)],
            ])))
            ->addSchema(array_filter([
                '@context' => 'https://schema.org',
                '@type' => 'BlogPosting',
                'headline' => $post->title,
                'description' => $post->seo_description ?: $post->excerpt,
                'image' => $post->cover_image ? media_url($post->cover_image) : null,
                'datePublished' => $post->published_at?->toAtomString(),
                'dateModified' => $post->updated_at?->toAtomString(),
                'mainEntityOfPage' => $post->url(),
                'author' => ['@type' => 'Organization', 'name' => setting('site_name')],
                'publisher' => ['@id' => site_url().'#isletme'],
            ]));

        return view('blog.show', [
            'seo' => $seo,
            'post' => $post,
            'isPreview' => $isPreview,
            'categories' => $this->categories(),
            'recent' => BlogPost::query()->latestPublished()->whereKeyNot($post->id)->limit(4)->get(),
        ]);
    }

    private function listing(Seo $seo, ?BlogCategory $category): View
    {
        $posts = BlogPost::query()
            ->latestPublished()
            ->with('category')
            ->when($category, fn ($q) => $q->where('blog_category_id', $category->id))
            ->paginate(9);

        if ($posts->currentPage() > 1) {
            $seo->canonical(site_url(request()->path()).'?page='.$posts->currentPage());
        }

        return view('blog.index', [
            'seo' => $seo,
            'posts' => $posts,
            'category' => $category,
            'categories' => $this->categories(),
        ]);
    }

    private function categories()
    {
        return BlogCategory::query()->active()->ordered()
            ->withCount(['posts' => fn ($q) => $q->published()])
            ->get();
    }
}

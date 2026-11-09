<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\BlogPosts\BlogPostResource;
use App\Filament\Resources\ContactMessages\ContactMessageResource;
use App\Filament\Resources\GalleryImages\GalleryImageResource;
use App\Filament\Resources\Services\ServiceResource;
use App\Models\BlogPost;
use App\Models\ContactMessage;
use App\Models\GalleryImage;
use App\Models\Service;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SiteOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $unread = ContactMessage::query()->where('is_read', false)->count();
        $drafts = BlogPost::query()->where('status', BlogPost::STATUS_DRAFT)->count();

        return [
            Stat::make('Okunmamış mesaj', $unread)
                ->description('Toplam '.ContactMessage::count().' mesaj')
                ->color($unread > 0 ? 'danger' : 'gray')
                ->url(ContactMessageResource::getUrl('index')),
            Stat::make('Aktif hizmet', Service::query()->active()->count())
                ->url(ServiceResource::getUrl('index')),
            Stat::make('Galeri görseli', GalleryImage::query()->active()->count())
                ->url(GalleryImageResource::getUrl('index')),
            Stat::make('Yayındaki blog yazısı', BlogPost::query()->published()->count())
                ->description($drafts > 0 ? $drafts.' taslak yazı bekliyor' : null)
                ->url(BlogPostResource::getUrl('index')),
        ];
    }
}

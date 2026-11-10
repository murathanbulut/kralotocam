/**
 * Galeri vitrini (yatay carousel) - resources/views/components/gallery-showcase.blade.php
 * Bayrakla değil, sayfada [data-gallery-showcase] işaretlemesi olduğunda çalışır (galeri sayfaları).
 *
 * Swiper yapılandırması:
 *  - slidesPerView: 'auto'  -> kart genişliği CSS'ten gelir (masaüstünde ~2 kart tam, sonraki kısmen görünür)
 *  - spaceBetween 16 / 24 (768px+)
 *  - Navigation: altın oklar; Pagination: 'progressbar'; Keyboard: ok tuşları; A11y: Türkçe metinler
 *  - grabCursor, watchOverflow (kaydırılacak görsel yoksa oklar gizlenir), centerInsufficientSlides
 *  - loop kapalı: fslightbox'ın kopya slaytları ayrı görsel sanmaması için
 * Karta tıklama lightbox'ı açar; sürükleme sonrası tıklama Swiper tarafından engellenir (preventClicks).
 */
import Swiper from 'swiper';
import { A11y, Keyboard, Navigation, Pagination } from 'swiper/modules';
import '../../css/features/gallery-showcase.css';

export default function initGalleryShowcase() {
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    document.querySelectorAll('[data-gallery-showcase]').forEach((root) => {
        const el = root.querySelector('.kral-showcase__swiper');
        if (!el || el.swiper) return;

        new Swiper(el, {
            modules: [A11y, Keyboard, Navigation, Pagination],
            slidesPerView: 'auto',
            spaceBetween: 16,
            speed: reducedMotion ? 0 : 600,
            grabCursor: true,
            watchOverflow: true,
            centerInsufficientSlides: true,
            keyboard: { enabled: true, onlyInViewport: true },
            navigation: {
                prevEl: root.querySelector('[data-showcase-prev]'),
                nextEl: root.querySelector('[data-showcase-next]'),
                disabledClass: 'is-disabled',
                lockClass: 'is-locked',
            },
            pagination: {
                el: root.querySelector('[data-showcase-progress]'),
                type: 'progressbar',
                lockClass: 'is-locked',
            },
            a11y: {
                prevSlideMessage: 'Önceki görseller',
                nextSlideMessage: 'Sonraki görseller',
                firstSlideMessage: 'İlk görsel',
                lastSlideMessage: 'Son görsel',
                slideLabelMessage: '{{index}} / {{slidesLength}}',
            },
            breakpoints: {
                768: { spaceBetween: 24 },
            },
        });

        root.classList.add('is-ready');
    });
}

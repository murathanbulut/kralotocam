/**
 * Galeri vitrini (durmadan akan yatay şerit) - resources/views/components/gallery-showcase.blade.php
 * Bayrakla değil, sayfada [data-gallery-showcase] işaretlemesi olduğunda çalışır (galeri sayfaları).
 *
 * - Orijinal görseller, ekranı dolduracak kadar kopyalanır; şerit bir set genişliği kadar
 *   sola kayıp başa sarar (CSS animasyonu, kesintisiz döngü).
 * - Hız sabittir (SPEED px/sn); fare üzerindeyken veya klavye odağı içerideyken durur.
 * - Kopyalar ekran okuyuculardan gizlenir ve fslightbox'a ayrı görsel olarak eklenmez;
 *   bir kopyaya tıklamak, ilgili orijinal görselin lightbox'ını açar.
 * - prefers-reduced-motion: animasyon yok, şerit elle kaydırılır.
 */
import '../../css/features/gallery-showcase.css';

const SPEED = 60; // px / saniye

function build(root) {
    const track = root.querySelector('[data-showcase-track]');
    const viewport = root.querySelector('.kral-showcase__viewport');
    if (!track || !viewport) return;

    track.querySelectorAll('[data-showcase-clone]').forEach((el) => el.remove());
    const originals = [...track.querySelectorAll('[data-showcase-item]:not([data-showcase-clone])')];
    if (!originals.length) return;

    const setWidth = track.scrollWidth;
    if (!setWidth) return;

    // Kaydırma sırasında boşluk görünmemesi için: set + ekran genişliği kadar kopya
    const copies = Math.max(1, Math.ceil(viewport.clientWidth / setWidth));
    for (let c = 0; c < copies; c += 1) {
        originals.forEach((item, index) => {
            const clone = item.cloneNode(true);
            clone.setAttribute('data-showcase-clone', '');
            clone.setAttribute('aria-hidden', 'true');
            const link = clone.querySelector('a');
            link.removeAttribute('data-fslightbox');
            link.setAttribute('tabindex', '-1');
            link.dataset.showcaseIndex = String(index);
            clone.querySelector('img')?.setAttribute('loading', 'eager');
            track.appendChild(clone);
        });
    }

    root.style.setProperty('--showcase-shift', `-${setWidth}px`);
    root.style.setProperty('--showcase-duration', `${(setWidth / SPEED).toFixed(2)}s`);
    root.classList.add('is-ready');
}

export default function initGalleryShowcase() {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    document.querySelectorAll('[data-gallery-showcase]').forEach((root) => {
        if (root.dataset.showcaseInit) return;
        root.dataset.showcaseInit = '1';

        const group = root.dataset.group;
        root.addEventListener('click', (event) => {
            const link = event.target.closest('a[data-showcase-index]');
            if (!link) return;
            event.preventDefault();
            window.fsLightboxInstances?.[group]?.open(Number(link.dataset.showcaseIndex));
        });

        // Kart genişliği CSS'te sabit; ekran boyutu değişince yeniden hesaplanır
        const rebuild = () => build(root);
        let timer;
        new ResizeObserver(() => { clearTimeout(timer); timer = setTimeout(rebuild, 150); })
            .observe(root.querySelector('.kral-showcase__viewport'));
        rebuild();
    });
}

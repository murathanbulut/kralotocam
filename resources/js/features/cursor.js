/**
 * B. Fare takip efekti (KRAL_FX_CURSOR)
 * Referans temadaki "bnz-pointer" efektinin (cursor-effect.js) sade karşılığı:
 * aynı sınıf adları (bnz-large / bnz-small / bnz-drag), jQuery yerine tek bir dinleyici ve requestAnimationFrame.
 * - Yalnızca hassas işaretçili, hover destekli cihazlarda (masaüstü) çalışır; dokunmatikte hiç oluşturulmaz.
 * - pointer-events: none; hiçbir tıklamayı engellemez, sistem imleci gizlenmez.
 * - prefers-reduced-motion durumunda devre dışıdır.
 */
import '../../css/features/cursor.css';

const LARGE = 'a, button, .btn, input[type="submit"], .swiper-button-prev, .swiper-button-next, [role="button"]';
const SMALL = '.swiper-pagination-bullet, .form-control, .form-select, textarea, input:not([type="submit"]):not([type="checkbox"])';
const DRAG = '.wptb-swiper-slider-two .swiper-slide';

export default function initCursor() {
    const body = document.body;
    if (!body.classList.contains('fx-cursor')) return;
    if (!window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    const pointer = document.createElement('div');
    pointer.className = 'bnz-pointer';
    pointer.id = 'bnz-pointer';
    pointer.setAttribute('aria-hidden', 'true');
    body.appendChild(pointer);

    let x = 0;
    let y = 0;
    let cx = 0;
    let cy = 0;
    let visible = false;
    let frame = null;

    const render = () => {
        // Hafif gecikmeli takip (lerp)
        cx += (x - cx) * 0.22;
        cy += (y - cy) * 0.22;
        pointer.style.transform = `translate3d(${cx}px, ${cy}px, 0)`;
        frame = Math.abs(x - cx) > 0.1 || Math.abs(y - cy) > 0.1 ? requestAnimationFrame(render) : null;
    };

    document.addEventListener('mousemove', (e) => {
        x = e.clientX;
        y = e.clientY;
        if (!visible) {
            cx = x;
            cy = y;
            visible = true;
            pointer.classList.add('is-visible');
        }
        if (!frame) frame = requestAnimationFrame(render);
    }, { passive: true });

    document.addEventListener('mouseover', (e) => {
        const target = e.target instanceof Element ? e.target : null;
        if (!target) return;
        const small = target.closest(SMALL);
        const large = !small && target.closest(LARGE);
        pointer.classList.toggle('bnz-small', !!small);
        pointer.classList.toggle('bnz-large', !!large);
        pointer.classList.toggle('bnz-drag', !small && !large && !!target.closest(DRAG));
    }, { passive: true });

    document.documentElement.addEventListener('mouseleave', () => {
        visible = false;
        pointer.classList.remove('is-visible');
    });
    document.addEventListener('mousedown', () => pointer.classList.add('is-pressed'));
    document.addEventListener('mouseup', () => pointer.classList.remove('is-pressed'));
}

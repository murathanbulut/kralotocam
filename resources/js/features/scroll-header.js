/**
 * C. Kaydırma yönüne duyarlı header (KRAL_FX_SCROLL_HEADER)
 * Aşağı kaydırırken header yukarı kayarak gizlenir, yukarı kaydırınca üstten yumuşakça geri gelir.
 * Mevcut header işaretlemesi, yüksekliği, "sticky" davranışı ve menüler değiştirilmez; yalnızca bir sınıf eklenir.
 * Gizlenmez: sayfanın üst kısmında, mobil menü / yan panel açıkken, fare veya klavye odağı header içindeyken.
 */
import '../../css/features/scroll-header.css';

const HIDE_AFTER = 240; // px: bu noktadan önce header hiç gizlenmez
const DELTA = 8;        // px: küçük titreşimleri yok say

export default function initScrollHeader() {
    const body = document.body;
    const header = document.getElementById('site-header');
    if (!header || !body.classList.contains('fx-scroll-header')) return;

    let lastY = window.scrollY;
    let ticking = false;
    let pointerInside = false;

    header.addEventListener('mouseenter', () => { pointerInside = true; show(); });
    header.addEventListener('mouseleave', () => { pointerInside = false; });
    header.addEventListener('focusin', show);

    function show() {
        header.classList.remove('kral-header-hidden');
    }

    function locked() {
        return pointerInside
            || header.contains(document.activeElement)
            || body.classList.contains('mr_menu_active')
            || document.getElementById('aside-info')?.classList.contains('show');
    }

    function update() {
        ticking = false;
        const y = window.scrollY;
        const diff = y - lastY;

        if (y < HIDE_AFTER || locked()) {
            show();
        } else if (diff > DELTA) {
            header.classList.add('kral-header-hidden');
        } else if (diff < -DELTA) {
            show();
        }

        if (Math.abs(diff) > DELTA || y < HIDE_AFTER) lastY = y;
    }

    window.addEventListener('scroll', () => {
        if (!ticking) {
            ticking = true;
            requestAnimationFrame(update);
        }
    }, { passive: true });
}

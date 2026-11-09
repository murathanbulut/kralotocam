/**
 * E. İsteğe bağlı açık tema (KRAL_FX_LIGHT_THEME)
 * Varsayılan görünüm her zaman koyu temadır. Sistem teması (prefers-color-scheme) dikkate alınmaz;
 * açık tema yalnızca ziyaretçi düğmeye bastığında etkinleşir ve tercih localStorage'da saklanır.
 * İlk boyamadan önce tercihi uygulayan küçük betik layout'un <head> bölümündedir.
 * Düğmeler: üst bant (masaüstü/tablet) ve mobil menü.
 */
import '../../css/features/theme-toggle.css';

const KEY = 'kral-theme';

export default function initThemeToggle() {
    const buttons = [...document.querySelectorAll('[data-theme-toggle]')];
    if (!buttons.length) return;

    const root = document.documentElement;
    const meta = document.querySelector('meta[name="theme-color"]');

    const apply = (theme) => {
        const light = theme === 'light';
        if (light) {
            root.setAttribute('data-theme', 'light');
        } else {
            root.removeAttribute('data-theme');
        }
        buttons.forEach((button) => {
            button.setAttribute('aria-pressed', String(light));
            button.setAttribute('aria-label', light ? 'Koyu temaya geç' : 'Açık temaya geç');
            button.setAttribute('title', light ? 'Koyu tema' : 'Açık tema');
        });
        meta?.setAttribute('content', light ? '#ffffff' : '#151515');
    };

    apply(root.getAttribute('data-theme') === 'light' ? 'light' : 'dark');

    buttons.forEach((button) => button.addEventListener('click', () => {
        const next = root.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
        apply(next);
        try {
            if (next === 'light') {
                localStorage.setItem(KEY, 'light');
            } else {
                localStorage.removeItem(KEY);
            }
        } catch {
            // Gizli pencere vb.: tercih yalnızca bu sayfa için geçerli olur
        }
    }));
}

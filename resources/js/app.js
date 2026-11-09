/*
 * Kral Oto Cam ön yüz davranışları (vanilla JS).
 * Referans temadaki jQuery tabanlı theme.js davranışlarının sade karşılığıdır:
 * mobil menü, alt menü açma, yan bilgi paneli, yapışkan header, Swiper slider,
 * galeri lightbox (fslightbox) ve kaydırınca görünme (WOW) animasyonları.
 */
import Swiper from 'swiper';
import { Autoplay, EffectFade, Navigation, Pagination, A11y } from 'swiper/modules';
import 'fslightbox';

const body = document.body;
const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/* ---------- Mobil menü ---------- */
function initMobileMenu() {
    const menu = document.getElementById('mobile-menu');
    const toggle = document.querySelector('.mr_menu_toggle');
    if (!menu || !toggle) return;

    const open = () => {
        body.classList.add('mr_menu_active');
        menu.setAttribute('aria-hidden', 'false');
        toggle.setAttribute('aria-expanded', 'true');
        menu.querySelector('.mr_menu_close')?.focus();
    };
    const close = () => {
        body.classList.remove('mr_menu_active');
        menu.setAttribute('aria-hidden', 'true');
        toggle.setAttribute('aria-expanded', 'false');
    };

    toggle.addEventListener('click', (e) => { e.preventDefault(); open(); });
    menu.querySelector('.mr_menu_close')?.addEventListener('click', (e) => { e.preventDefault(); close(); toggle.focus(); });
    document.querySelectorAll('[data-menu-close]').forEach((el) => el.addEventListener('click', close));
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && body.classList.contains('mr_menu_active')) { close(); toggle.focus(); } });

    // Alt menü açıcıları
    menu.querySelectorAll('li.menu-item-has-children').forEach((li) => {
        const opener = li.querySelector(':scope > .submenu_opener');
        const parentLink = li.querySelector(':scope > a[data-submenu-toggle]');
        const sub = li.querySelector(':scope > ul');
        const toggleSub = (e) => {
            e.preventDefault();
            const isOpen = li.classList.toggle('nav_open');
            sub.style.display = isOpen ? 'block' : 'none';
            opener?.setAttribute('aria-expanded', String(isOpen));
            parentLink?.setAttribute('aria-expanded', String(isOpen));
        };
        opener?.addEventListener('click', toggleSub);
        parentLink?.addEventListener('click', toggleSub);
        // Aktif sayfanın bulunduğu alt menü açık başlasın
        if (li.classList.contains('active') && sub) {
            li.classList.add('nav_open');
            sub.style.display = 'block';
            opener?.setAttribute('aria-expanded', 'true');
        }
    });
}

/* ---------- Masaüstü menü: "#" bağlantılı üst öğeler, klavye ile açılabilir ---------- */
function initDesktopMenu() {
    document.querySelectorAll('.mainnav li.menu-item-has-children').forEach((li) => {
        const link = li.querySelector(':scope > a');
        link?.addEventListener('click', (e) => {
            if (link.hasAttribute('data-submenu-toggle')) {
                e.preventDefault();
                const isOpen = li.classList.toggle('is-open');
                link.setAttribute('aria-expanded', String(isOpen));
            }
        });
        li.addEventListener('mouseenter', () => link?.setAttribute('aria-expanded', 'true'));
        li.addEventListener('mouseleave', () => { li.classList.remove('is-open'); link?.setAttribute('aria-expanded', 'false'); });
        li.addEventListener('focusout', (e) => {
            if (!li.contains(e.relatedTarget)) { li.classList.remove('is-open'); link?.setAttribute('aria-expanded', 'false'); }
        });
    });
    document.addEventListener('keydown', (e) => {
        if (e.key !== 'Escape') return;
        document.querySelectorAll('.mainnav li.is-open').forEach((li) => li.classList.remove('is-open'));
    });
}

/* ---------- Yan bilgi paneli ---------- */
function initAsidePanel() {
    const opener = document.querySelector('.aside_open');
    const panel = document.getElementById('aside-info');
    if (!opener || !panel) return;
    const closeBtn = panel.querySelector('.aside_close');

    const setState = (open) => {
        opener.classList.toggle('close', open);
        panel.classList.toggle('show', open);
        panel.setAttribute('aria-hidden', String(!open));
        opener.setAttribute('aria-expanded', String(open));
        if (open) closeBtn?.focus();
    };
    opener.addEventListener('click', (e) => { e.preventDefault(); setState(!panel.classList.contains('show')); });
    closeBtn?.addEventListener('click', (e) => { e.preventDefault(); setState(false); opener.focus(); });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && panel.classList.contains('show')) { setState(false); opener.focus(); } });
}

/* ---------- Yapışkan header ---------- */
function initStickyHeader() {
    const header = document.getElementById('site-header');
    if (!header) return;
    const update = () => header.classList.toggle('sticky', window.scrollY >= 50);
    update();
    window.addEventListener('scroll', update, { passive: true });
}

/* ---------- Ana sayfa slider (referans: wptb-swiper-slider-two) ---------- */
function initHeroSlider() {
    const el = document.querySelector('.wptb-swiper-slider-two');
    if (!el) return;
    const section = el.closest('.wptb-slider');
    const slides = el.querySelectorAll('.swiper-slide').length;

    new Swiper(el, {
        modules: [Autoplay, EffectFade, Navigation, Pagination, A11y],
        loop: slides > 1,
        effect: 'fade',
        fadeEffect: { crossFade: true },
        speed: reducedMotion ? 0 : 1500,
        autoplay: slides > 1 && !reducedMotion ? { delay: 6000, disableOnInteraction: false, pauseOnMouseEnter: true } : false,
        pagination: { el: section.querySelector('.swiper-pagination'), clickable: true },
        navigation: { nextEl: section.querySelector('.swiper-button-next'), prevEl: section.querySelector('.swiper-button-prev') },
        a11y: {
            prevSlideMessage: 'Önceki slayt',
            nextSlideMessage: 'Sonraki slayt',
            paginationBulletMessage: '{{index}}. slayta git',
        },
    });
}

/* ---------- Kaydırınca görünme animasyonu (WOW.js yerine) ---------- */
function initReveal() {
    const items = document.querySelectorAll('.wow');
    if (!items.length) return;
    if (reducedMotion || !('IntersectionObserver' in window)) {
        items.forEach((el) => el.classList.add('is-visible'));
        return;
    }
    body.classList.add('js-reveal');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { rootMargin: '0px 0px -60px 0px', threshold: 0.1 });
    items.forEach((el) => observer.observe(el));
}

/* ---------- Form mesajına odaklan ---------- */
function initFormFeedback() {
    const alert = document.querySelector('[data-autofocus]');
    if (alert) {
        alert.scrollIntoView({ behavior: reducedMotion ? 'auto' : 'smooth', block: 'center' });
        alert.focus({ preventScroll: true });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    initMobileMenu();
    initDesktopMenu();
    initAsidePanel();
    initStickyHeader();
    initHeroSlider();
    initReveal();
    initFormFeedback();
    if (typeof window.refreshFsLightbox === 'function') window.refreshFsLightbox();
});

/**
 * A. Slider yazı animasyonları (KRAL_FX_SLIDER_TEXT)
 * Mevcut Swiper örneğine bağlanır; yeni slider kurmaz.
 * Her slayt değişiminde aktif slayta "kral-text-in" sınıfı yeniden eklenir ve metinler sırayla belirir.
 */
import '../../css/features/slider-text.css';

export default function initSliderText({ swiper } = {}) {
    const body = document.body;
    if (!swiper || !body.classList.contains('fx-slider-text')) return;

    // Hareket azaltma tercihi varsa temanın varsayılan davranışı korunur.
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    body.classList.add('js-slider-fx');

    const play = () => {
        swiper.slides.forEach((slide) => slide.classList.remove('kral-text-in'));
        const active = swiper.slides[swiper.activeIndex];
        if (!active) return;
        void active.offsetWidth; // yeniden başlatmak için reflow
        active.classList.add('kral-text-in');
    };

    swiper.on('slideChangeTransitionStart', play);
    play();
}

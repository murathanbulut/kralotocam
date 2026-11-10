/**
 * Görsel regresyon için ekran görüntüsü alır.
 *
 * Kullanım:
 *   node scripts/visual/capture.mjs <çıktı-klasörü> [baseUrl] [--theme=light]
 *   node scripts/visual/capture.mjs docs/baseline-screenshots http://127.0.0.1:8001
 *
 * Kurulu Chrome/Edge kullanılır (puppeteer-core). Karşılaştırılabilir sonuç için:
 * animasyon/geçişler kapatılır, kaydırma animasyonları görünür yapılır, slider ilk slayta sabitlenir,
 * Google Maps iframe'i gizlenir (dış içerik her seferinde farklı yüklenebilir).
 */
import fs from 'node:fs';
import path from 'node:path';
import puppeteer from 'puppeteer-core';

const outDir = process.argv[2] ?? 'docs/baseline-screenshots';
const baseUrl = (process.argv[3] && !process.argv[3].startsWith('--') ? process.argv[3] : 'http://127.0.0.1:8001').replace(/\/$/, '');
const theme = (process.argv.find((a) => a.startsWith('--theme=')) ?? '').split('=')[1] ?? null;

const BROWSERS = [
    'C:/Program Files/Google/Chrome/Application/chrome.exe',
    'C:/Program Files (x86)/Microsoft/Edge/Application/msedge.exe',
    '/usr/bin/google-chrome',
    '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',
];
const executablePath = process.env.CHROME_PATH ?? BROWSERS.find((p) => fs.existsSync(p));

const DESKTOP = { width: 1440, height: 900, deviceScaleFactor: 1 };
const MOBILE = { width: 390, height: 844, deviceScaleFactor: 1, isMobile: true, hasTouch: true };

const STABILIZE_CSS = `
  *, *::before, *::after { transition: none !important; animation: none !important; caret-color: transparent !important; }
  .js-reveal .wow { opacity: 1 !important; transform: none !important; }
  .kral-map iframe { visibility: hidden !important; }
`;

async function prepare(page) {
    await page.addStyleTag({ content: STABILIZE_CSS });
    await page.evaluate(async () => {
        document.querySelectorAll('.wow').forEach((el) => el.classList.add('is-visible'));
        const swiper = document.querySelector('.wptb-swiper-slider-two')?.swiper;
        if (swiper) {
            swiper.autoplay?.stop();
            swiper.slideToLoop(0, 0);
        }
        // Tembel yüklenen görselleri tetikle
        for (let y = 0; y < document.body.scrollHeight; y += 600) {
            window.scrollTo(0, y);
            await new Promise((r) => setTimeout(r, 40));
        }
        window.scrollTo(0, 0);
        // Lazy görselleri hemen yükle ve en fazla 5 sn bekle
        document.querySelectorAll('img[loading="lazy"]').forEach((i) => { i.loading = 'eager'; });
        const pending = [...document.images].filter((i) => !i.complete).map((i) => new Promise((r) => { i.onload = i.onerror = r; }));
        await Promise.race([Promise.all(pending), new Promise((r) => setTimeout(r, 5000))]);
        await document.fonts.ready;
    });
    await new Promise((r) => setTimeout(r, 400));
}

async function open(browser, url, viewport) {
    const page = await browser.newPage();
    await page.setViewport(viewport);
    if (theme) {
        await page.evaluateOnNewDocument((t) => { try { localStorage.setItem('kral-theme', t); } catch {} }, theme);
    }
    await page.goto(baseUrl + url, { waitUntil: 'networkidle0', timeout: 60000 });
    await prepare(page);
    return page;
}

const shots = [
    { name: '01-home-desktop', url: '/', viewport: DESKTOP, fullPage: true },
    { name: '02-home-mobile', url: '/', viewport: MOBILE, fullPage: true },
    { name: '03-slider-desktop', url: '/', viewport: DESKTOP },
    {
        name: '04-header-dropdown', url: '/', viewport: DESKTOP,
        action: async (page) => {
            const items = await page.$$('.mainnav .main-menu > li.menu-item-has-children');
            await items[1].hover();
            await new Promise((r) => setTimeout(r, 300));
        },
        clip: { x: 0, y: 0, width: 1440, height: 560 },
    },
    { name: '05-services-section', url: '/', viewport: DESKTOP, element: '#hizmetler' },
    { name: '06-footer', url: '/', viewport: DESKTOP, element: 'footer.kral-footer' },
    { name: '07-service-detail', url: '/adas-ve-kamera-kalibrasyonlari/', viewport: DESKTOP, fullPage: true },
    { name: '08-gallery', url: '/galeri/', viewport: DESKTOP, fullPage: true },
    { name: '09-contact', url: '/iletisim/', viewport: DESKTOP, fullPage: true },
    {
        name: '10-mobile-menu-open', url: '/', viewport: MOBILE,
        action: async (page) => {
            await page.click('.mr_menu_toggle');
            await new Promise((r) => setTimeout(r, 300));
        },
    },
    { name: '11-service-detail-mobile', url: '/binek-arac-cam-degisimi/', viewport: MOBILE, fullPage: true },
    { name: '12-gallery-album', url: '/galeri/adas-ve-kamera-kalibrasyonu/', viewport: DESKTOP, fullPage: true },
    { name: '13-gallery-mobile', url: '/galeri/', viewport: MOBILE, fullPage: true },
    { name: '14-topbar-closeup', url: '/', viewport: DESKTOP, clip: { x: 0, y: 0, width: 1440, height: 130 } },
    { name: '15-topbar-inner-page', url: '/iletisim/', viewport: DESKTOP, clip: { x: 0, y: 0, width: 1440, height: 130 } },
];

fs.mkdirSync(outDir, { recursive: true });
const browser = await puppeteer.launch({ executablePath, headless: true, args: ['--hide-scrollbars', '--font-render-hinting=none'] });

try {
    for (const shot of shots) {
        const page = await open(browser, shot.url, shot.viewport);
        if (shot.action) await shot.action(page);
        const file = path.join(outDir, `${shot.name}.png`);

        if (shot.element) {
            // Bölüm görüntülerinde sabit header/butonlar içeriğin üstüne binmesin
            await page.addStyleTag({ content: ".header, .kral-floating { visibility: hidden !important; }" });
            const el = await page.$(shot.element);
            await el.screenshot({ path: file });
        } else {
            await page.screenshot({ path: file, fullPage: !!shot.fullPage, clip: shot.clip });
        }
        console.log('saved', file);
        await page.close();
    }
} finally {
    await browser.close();
}

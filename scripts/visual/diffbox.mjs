/** Fark görüntüsündeki değişen piksellerin sınır kutusunu yazdırır: node scripts/visual/diffbox.mjs <diff-klasörü> */
import fs from 'node:fs';
import path from 'node:path';
import { PNG } from 'pngjs';

const dir = process.argv[2] ?? 'docs/diff-screenshots';
for (const file of fs.readdirSync(dir).filter((f) => f.endsWith('.png')).sort()) {
    const d = PNG.sync.read(fs.readFileSync(path.join(dir, file)));
    let minx = Infinity, miny = Infinity, maxx = -1, maxy = -1;
    for (let y = 0; y < d.height; y++) {
        for (let x = 0; x < d.width; x++) {
            const i = (y * d.width + x) * 4;
            const [r, g, b] = [d.data[i], d.data[i + 1], d.data[i + 2]];
            if (r > 200 && b < 80 && (g < 80 || g > 200)) {
                minx = Math.min(minx, x); maxx = Math.max(maxx, x); miny = Math.min(miny, y); maxy = Math.max(maxy, y);
            }
        }
    }
    console.log(file.padEnd(28), maxx < 0 ? 'no diff pixels' : `x ${minx}-${maxx}  y ${miny}-${maxy}`);
}

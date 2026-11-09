/**
 * İki ekran görüntüsü klasörünü piksel bazında karşılaştırır.
 *
 * Kullanım:
 *   node scripts/visual/compare.mjs docs/baseline-screenshots docs/current-screenshots [diff-klasörü]
 *
 * Her dosya için farklı piksel oranı yazdırılır; farklı olanların fark görüntüsü kaydedilir.
 */
import fs from 'node:fs';
import path from 'node:path';
import pixelmatch from 'pixelmatch';
import { PNG } from 'pngjs';

const [baseDir, currentDir, diffDir = path.join(currentDir ?? '.', '_diff')] = process.argv.slice(2);

if (!baseDir || !currentDir) {
    console.error('Kullanım: node scripts/visual/compare.mjs <baseline> <current> [diff]');
    process.exit(2);
}

fs.mkdirSync(diffDir, { recursive: true });
let failed = 0;

for (const file of fs.readdirSync(baseDir).filter((f) => f.endsWith('.png')).sort()) {
    const currentFile = path.join(currentDir, file);
    if (!fs.existsSync(currentFile)) {
        console.log(`MISSING  ${file}`);
        failed++;
        continue;
    }

    const a = PNG.sync.read(fs.readFileSync(path.join(baseDir, file)));
    const b = PNG.sync.read(fs.readFileSync(currentFile));

    if (a.width !== b.width || a.height !== b.height) {
        console.log(`SIZE     ${file}  ${a.width}x${a.height} -> ${b.width}x${b.height}`);
        failed++;
        continue;
    }

    const diff = new PNG({ width: a.width, height: a.height });
    const pixels = pixelmatch(a.data, b.data, diff.data, a.width, a.height, { threshold: 0.1 });
    const ratio = (pixels / (a.width * a.height)) * 100;

    if (pixels === 0) {
        console.log(`SAME     ${file}`);
    } else {
        fs.writeFileSync(path.join(diffDir, file), PNG.sync.write(diff));
        console.log(`DIFF     ${file}  ${pixels} px (${ratio.toFixed(3)}%)`);
        failed++;
    }
}

process.exitCode = failed ? 1 : 0;

/** PNG'den bölge kırpar: node scripts/visual/crop.mjs <girdi> <çıktı> <x> <y> <w> <h> */
import fs from 'node:fs';
import { PNG } from 'pngjs';
const [input, output, x, y, w, h] = process.argv.slice(2);
const src = PNG.sync.read(fs.readFileSync(input));
const out = new PNG({ width: +w, height: +h });
PNG.bitblt(src, out, +x, +y, +w, +h, 0, 0);
fs.writeFileSync(output, PNG.sync.write(out));

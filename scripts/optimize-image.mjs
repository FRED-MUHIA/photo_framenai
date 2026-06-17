import fs from 'node:fs/promises';
import path from 'node:path';
import sharp from 'sharp';

const [, , sourcePath, targetPath, maxDimensionArg = '1600', qualityArg = '78'] = process.argv;

if (!sourcePath || !targetPath) {
    console.error('Usage: node scripts/optimize-image.mjs <source> <target> [maxDimension] [quality]');
    process.exit(1);
}

const maxDimension = Number.parseInt(maxDimensionArg, 10) || 1600;
const quality = Number.parseInt(qualityArg, 10) || 78;
const tempPath = `${targetPath}.${process.pid}.tmp`;

try {
    await fs.mkdir(path.dirname(targetPath), { recursive: true });

    const sourceStat = await fs.stat(sourcePath);

    await sharp(sourcePath, {
        animated: false,
        limitInputPixels: 80_000_000,
    })
        .rotate()
        .resize({
            width: maxDimension,
            height: maxDimension,
            fit: 'inside',
            withoutEnlargement: true,
        })
        .webp({
            quality,
            effort: 4,
            smartSubsample: true,
        })
        .toFile(tempPath);

    const targetStat = await fs.stat(tempPath);

    if (targetStat.size >= sourceStat.size) {
        await fs.rm(tempPath, { force: true });
        process.exit(3);
    }

    await fs.rename(tempPath, targetPath);
} catch (error) {
    await fs.rm(tempPath, { force: true }).catch(() => {});
    console.error(error instanceof Error ? error.message : String(error));
    process.exit(2);
}

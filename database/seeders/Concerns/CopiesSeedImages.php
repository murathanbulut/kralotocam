<?php

namespace Database\Seeders\Concerns;

use Illuminate\Support\Facades\Storage;

trait CopiesSeedImages
{
    /**
     * database/seeders/images altındaki görseli public diske kopyalar ve yolunu döndürür.
     * Görseller referanslar klasöründeki canlı site görsellerinden web için sıkıştırılarak üretilmiştir.
     */
    protected function seedImage(?string $file, string $folder): ?string
    {
        if (! $file) {
            return null;
        }

        $source = database_path('seeders/images/'.$file);

        if (! is_file($source)) {
            return null;
        }

        $target = trim($folder, '/').'/'.$file;

        if (! Storage::disk('public')->exists($target)) {
            Storage::disk('public')->put($target, file_get_contents($source));
        }

        return $target;
    }
}

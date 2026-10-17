<?php

namespace App\Concerns;

use RuntimeException;

trait ReadsJsonData
{
    /**
     * Read database/data/{$file}.json and return it as an array.
     *
     * @return array<int, mixed>
     */
    protected function readJson(string $file): array
    {
        $path = database_path("data/{$file}.json");

        if (! file_exists($path)) {
            throw new RuntimeException("JSON seed file not found: {$path}");
        }

        return json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
    }
}

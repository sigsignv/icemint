<?php

declare(strict_types=1);

namespace Sigsign\IceMint;

final class FileUtils
{
    private string $baseDir;

    public function __construct(string $directory)
    {
        $dir = realpath($directory);
        if (!$dir) {
            throw new \InvalidArgumentException(
                "Does not exist or normalization failed: {$directory}",
            );
        }
        clearstatcache(true, $dir);
        if (!is_dir($dir) || !is_readable($dir)) {
            throw new \InvalidArgumentException(
                "Either not a directory or not readable: {$dir}",
            );
        }

        $this->baseDir = $dir;
    }

    public function read(string $filename): string
    {
        if (!file_exists($filename)) {
            throw new \RuntimeException(
                "Does not exist: {$filename}",
            );
        }
        return file_get_contents($filename);
    }
}

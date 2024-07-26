<?php

declare(strict_types=1);

namespace Sigsign\IceMint\DataStore;

final class FileDataStore implements DataStoreInterface
{
    private string $dir;

    public function __construct(string $dir)
    {
        $dir = realpath($dir);
        if (!$dir || !is_dir($dir) || !is_readable($dir)) {
            throw new \InvalidArgumentException(
                "The directory is either not a directory or not readable: {$dir}",
            );
        }

        $this->dir = $dir;
    }

    public function read(string $page): string
    {
        $path = $this->joinPath($page);
        if (!is_file($path) || !is_readable($path)) {
            // Todo:
            return "Not found";
        }

        $content = file_get_contents($path);

        return $content !== false ? $content : "Not Available";
    }

    public function write(string $page, string $content): void
    {
        $path = $this->joinPath($page);
        file_put_contents($path, $content);
    }

    private function joinPath(string $page): string
    {
        if (mb_scrub($page, 'UTF-8') !== $page) {
            throw new \InvalidArgumentException(
                "Contains an invalid UTF-8 sequence: {$page}",
            );
        }

        $ignore = ['..', '.'];
        foreach(preg_split("/\//u", $page) as $part) {
            if (in_array($part, $ignore, true)) {
                throw new \InvalidArgumentException(
                    "Page name cannot contain '.' or '..' to prevent path traversal: {$page}",
                );
            }
        }

        return "{$this->dir}/{$page}.md";
    }
}

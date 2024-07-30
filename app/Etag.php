<?php

declare(strict_types=1);

namespace Sigsign\IceMint;

final class Etag implements \Stringable
{
    private function __construct(
        private int $size,
        private int $mtime,
        private bool $isWeak,
    ) {}

    public static function create(int $size, int $mtime, bool $isWeak = true): Etag
    {
        return new Etag($size, $mtime, $isWeak);
    }

    public function __toString(): string
    {
        $prefix = $this->isWeak ? 'W/"' : '"';
        $size = dechex($this->size);
        $mtime = dechex($this->mtime);
        $suffix = '"';

        return "{$prefix}{$size}-{$mtime}{$suffix}";
    }
}

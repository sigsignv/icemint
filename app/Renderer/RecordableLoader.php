<?php

declare(strict_types=1);

namespace Sigsign\IceMint\Renderer;

use Twig\Loader\LoaderInterface;
use Twig\Source;

final class RecordableLoader implements LoaderInterface
{
    private array $records = [];

    public function __construct(private LoaderInterface $loader) {}

    public function getSourceContext(string $name): Source
    {
        return $this->loader->getSourceContext($name);
    }

    public function getCacheKey(string $name): string
    {
        /**
         * Twig calls getCacheKey() and only calls getSourceContent() if cache does not exist.
         * Therefore, this is the best place to log the filename.
         */
        if (!in_array($name, $this->records, true)) {
            $this->records[] = $name;
        }
        return $this->loader->getCacheKey($name);
    }

    public function isFresh(string $name, int $time): bool
    {
        return $this->loader->isFresh($name, $time);
    }

    public function exists(string $name)
    {
        return $this->loader->exists($name);
    }

    public function getRecords(): array
    {
        return $this->records;
    }
}

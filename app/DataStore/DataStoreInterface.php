<?php

declare(strict_types=1);

namespace Sigsign\IceMint\DataStore;

interface DataStoreInterface
{
    public function read(string $page);
    public function write(string $page, string $content);
}

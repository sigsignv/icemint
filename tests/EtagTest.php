<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Sigsign\IceMint\Etag;

class EtagTest extends TestCase
{
    public function testEtag(): void
    {
        $unixtime = strtotime("2024-01-01T00:00:00+0000");
        $etag = Etag::create(1000, $unixtime, false);

        $this->assertEquals('"3e8-65920080"', $etag->__toString());
    }

    public function testWeakEtag(): void
    {
        $unixtime = strtotime("2024-01-01T00:00:00+0000");
        $etag = Etag::create(1000, $unixtime, true);

        $this->assertEquals('W/"3e8-65920080"', $etag->__toString());
    }
}

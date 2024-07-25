<?php

declare(strict_types=1);

namespace Sigsign\IceMint\Controller;

use Sigsign\IceMint\DataStore\FileDataStore;

class ViewController extends AbstractController
{
    public function show(string $page)
    {
        $backend = new FileDataStore(__DIR__ . '/../../data/markdown/');
        $content = $backend->read($page);

        return $this->render('', [
            "title" => "{$page}",
            "content" => "{$content}",
        ]);
    }
}

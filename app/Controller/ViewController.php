<?php

declare(strict_types=1);

namespace Sigsign\IceMint\Controller;

class ViewController extends AbstractController
{
    public function show(string $page)
    {
        return $this->render('', [
            "title" => "view",
            "content" => "<h1>Hello, World!</h1>",
        ]);
    }
}

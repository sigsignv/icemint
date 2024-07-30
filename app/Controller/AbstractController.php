<?php

declare(strict_types=1);

namespace Sigsign\IceMint\Controller;

use Sigsign\IceMint\Renderer\TwigRenderer;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController
{
    public function render(string $view, array $params): Response
    {
        $renderer = new TwigRenderer('default');
        return $renderer->render($view, $params);
    }
}

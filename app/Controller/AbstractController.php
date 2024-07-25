<?php

declare(strict_types=1);

namespace Sigsign\IceMint\Controller;

use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController
{
    public function render(string $view, array $params): Response
    {
        ['title' => $title, 'content' => $content] = $params;

        ob_start();
        include __DIR__ . '/../../skin/default/view.php';
        $html = ob_get_clean();

        return new Response($html);
    }
}

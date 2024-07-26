<?php

declare(strict_types=1);

namespace Sigsign\IceMint\Controller;

use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController
{
    public function render(string $view, array $params): Response
    {
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../skin/default/');
        $twig = new \Twig\Environment($loader, [
            'auto_reload' => true,
        ]);
        $html = $twig->render($view, $params);

        return new Response($html);
    }
}

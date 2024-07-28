<?php

declare(strict_types=1);

namespace Sigsign\IceMint\Controller;

use Sigsign\IceMint\Renderer\RecordableLoader;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController
{
    public function render(string $view, array $params): Response
    {
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../skin/default/');
        $recorder = new RecordableLoader($loader);
        $twig = new \Twig\Environment($recorder, [
            'auto_reload' => true,
        ]);
        $html = $twig->render($view, $params);

        return new Response($html);
    }
}

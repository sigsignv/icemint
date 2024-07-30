<?php

declare(strict_types=1);

namespace Sigsign\IceMint\Renderer;

use Sigsign\IceMint\FileUtils;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final class TwigRenderer implements RendererInterface
{
    private FileUtils $utils;

    public function __construct(string $skinName, ?string $baseDir = null)
    {
        $baseDir = $baseDir ?? __DIR__ . '/../../skin';
        $this->utils = new FileUtils("{$baseDir}/{$skinName}");
    }

    public function render(string $name, array $variables): Response
    {
        $loader = new FilesystemLoader($this->utils->baseDir());
        $recorder = new RecordableLoader($loader);
        $twig = new Environment($recorder, [
            'auto_reload' => true,
        ]);
        $response = new Response($twig->render($name, $variables));

        return $response;
    }
}

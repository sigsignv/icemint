<?php

declare(strict_types=1);

namespace Sigsign\IceMint\Renderer;

use Symfony\Component\HttpFoundation\Response;

interface RendererInterface
{
    public function render(string $name, array $variables): Response;
}

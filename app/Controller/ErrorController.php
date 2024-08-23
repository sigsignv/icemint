<?php

declare(strict_types=1);

namespace Sigsign\IceMint\Controller;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;

class ErrorController extends AbstractController
{
    public function exception(FlattenException $exception): Response
    {
        $msg = "Error: " . $exception->getMessage();
        $response = $this->render('error.twig', [
            'title' => "ERROR",
            'content' => $msg,
        ]);
        $response->setStatusCode($exception->getStatusCode());

        return $response;
    }
}
